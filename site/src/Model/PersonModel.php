<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : PersonModel.php
 * Location     : site/src/Model
 * Created      : 13-10-2026
 * Modified     : 20-10-2026
 *
 * Purpose:
 * Model for retrieving person and relation data from com_joaktree.
 *
 * This component provides the graphical visualisation of
 * genealogical data from com_joaktree.
 *
 * Author       : Frits van Leeuwen
 * Company      : Van Leeuwen Webdesign
 * Website      : https://vanleeuwenwebdesign.nl
 * License      : GNU General Public License v2 or later
 * ============================================================================
 */

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Router\Route;

class PersonModel extends BaseDatabaseModel
{
    /**
     * Retrieve the data of the main person
     */
    public function getPerson()
    {
        $app = Factory::getApplication();

        $personIdInput = $app->input->getString('personId', '')
                      ?: $app->input->getString('person_id', '')
                      ?: $app->input->getString('id', '')
                      ?: ($_GET['personId'] ?? $_GET['id'] ?? '');

        if (empty($personIdInput)) {
            return null;
        }

        list($appId, $idKey) = $this->parsePersonId($personIdInput);

        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');
        $person = $this->getPersonRecord($idKey, $appId);

        if (!$person) {
            return null;
        }

        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__joaktree_admin_persons', 'ap'))
            ->where($db->quoteName('ap.app_id') . ' = ' . $appId)
            ->where($db->quoteName('ap.id') . ' = ' . $db->quote($idKey));

        $db->setQuery($query);
        $person->adminData = $db->loadObject();

        $events = $this->getPersonEvents($idKey, ['BIRT', 'DEAT']);

        return [
            'appId'        => $appId,
            'treeId'       => '',
            'personKey'    => $idKey,
            'itemId'       => '',
            'url'          => '',
            'firstNames'   => $person->firstName ?? 'Unknown',
            'familyName'   => $person->familyName ?? '',
            'birthInfo'    => $this->formatEventInfo($events['BIRT'] ?? null),
            'deathInfo'    => $this->formatEventInfo($events['DEAT'] ?? null),
            'partnerCount' => $this->getPartnerCount($idKey, $appId),
            'children'     => $this->getChildren($idKey, $appId),
        ];
    }

    /**
     * Retrieve all children for a person
     */
    public function getChildren(string $personId, int $appId = 1): array
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');

        // In Joaktree, person_id_2 is the parent and person_id_1 is the child
        $query = $db->getQuery(true)
            ->select('r.*')
            ->from($db->quoteName('#__joaktree_relations', 'r'))
            ->where($db->quoteName('r.person_id_2') . ' = ' . $db->quote($personId))
            ->where($db->quoteName('r.type') . ' IN (' . $db->quote('father') . ', ' . $db->quote('mother') . ')')
            ->where($db->quoteName('r.app_id') . ' = ' . $appId);

        $db->setQuery($query);
        $relations = $db->loadObjectList() ?: [];

        $children = [];
        foreach ($relations as $rel) {
            $childKey = $rel->person_id_1; // The child is stored in person_id_1
            $childArray = $this->buildPersonArray($childKey, $appId);

            if ($childArray) {
                // Prevent duplicates (in case linked via both father and mother)
                $exists = false;
                foreach ($children as $c) {
                    if ($c['personKey'] === $childKey) {
                        $exists = true;
                        break;
                    }
                }
                if (!$exists) {
                    $children[] = $childArray;
                }
            }
        }

        return $children;
    }

    /**
     * Retrieve person events
     */
    public function getPersonEvents(string $personId, array $codes = ['BIRT', 'DEAT']): array
    {
        if (empty($personId) || empty($codes)) {
            return [];
        }

        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');
        $quotedCodes = array_map([$db, 'quote'], $codes);

        $query = $db->getQuery(true)
            ->select([$db->quoteName('code'), $db->quoteName('eventDate'), $db->quoteName('location')])
            ->from($db->quoteName('#__joaktree_person_events'))
            ->where($db->quoteName('person_id') . ' = ' . $db->quote($personId))
            ->where($db->quoteName('code') . ' IN (' . implode(',', $quotedCodes) . ')');

        $db->setQuery($query);

        return $db->loadObjectList('code') ?: [];
    }

    /**
     * Retrieve the count of additional partners
     */
    public function getPartnerCount(string $personId, int $appId = 1): int
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');

        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__joaktree_relations', 'r'))
            ->where('(' . $db->quoteName('r.person_id_1') . ' = ' . $db->quote($personId) . ' OR ' . $db->quoteName('r.person_id_2') . ' = ' . $db->quote($personId) . ')')
            ->where($db->quoteName('r.type') . ' = ' . $db->quote('partner'))
            ->where($db->quoteName('r.app_id') . ' = ' . $appId);

        $db->setQuery($query);

        return max(0, (int) $db->loadResult() - 1);
    }

    /**
     * Retrieve all parents
     */
    public function getParents(string $personId, int $appId = 1): array
    {
        $result = [
            'father'       => null,
            'mother'       => null,
            'marriageInfo' => '',
            'divorceInfo'  => '',
        ];

        $relations = $this->getRelationsForParentsOnly($personId, $appId, ['father', 'mother']);

        if (empty($relations)) {
            return $result;
        }

        $fatherKey = null;
        $motherKey = null;

        foreach ($relations as $rel) {
            $parentKey = $rel->person_id_2;
            $parentArray = $this->buildPersonArray($parentKey, $appId);

            if (!$parentArray) {
                continue;
            }

            if ($rel->type === 'father') {
                $result['father'] = $parentArray;
                $fatherKey = $parentKey;
            } elseif ($rel->type === 'mother') {
                $result['mother'] = $parentArray;
                $motherKey = $parentKey;
            }
        }

        if ($fatherKey && $motherKey) {
            $partnerRel = $this->getPartnerRelationBetween($fatherKey, $motherKey, $appId);
            if ($partnerRel) {
                $relEvents = $this->getRelationEvents($partnerRel, ['MARR', 'DIV']);
                $result['marriageInfo'] = $this->formatEventInfo($relEvents['MARR'] ?? null);
                $result['divorceInfo']  = $this->formatEventInfo($relEvents['DIV'] ?? null);
            }
        }

        return $result;
    }

    /**
     * Retrieve all partners
     */
    public function getPartners(string $personId, int $appId = 1, ?string $selectedPartnerKey = null): array
    {
        $relations = $this->getRelationsForPerson($personId, $appId, ['partner'], 'r.orderNumber_1 ASC');

        if (empty($relations)) {
            return [
                'active'  => null,
                'earlier' => [],
                'laters'  => [],
            ];
        }

        $allPartners = [];

        foreach ($relations as $rel) {
            $partnerKey = ($rel->person_id_1 === $personId) ? $rel->person_id_2 : $rel->person_id_1;
            $partnerArray = $this->buildPersonArray($partnerKey, $appId);

            if (!$partnerArray) {
                continue;
            }

            $relEvents = $this->getRelationEvents($rel, ['MARR', 'DIV']);
            $partnerArray['marriageInfo'] = $this->formatEventInfo($relEvents['MARR'] ?? null);
            $partnerArray['divorceInfo']  = $this->formatEventInfo($relEvents['DIV'] ?? null);

            $allPartners[] = $partnerArray;
        }

        $activeIndex = 0;
        if (!empty($selectedPartnerKey)) {
            foreach ($allPartners as $index => $partner) {
                if ($partner['personKey'] === $selectedPartnerKey) {
                    $activeIndex = $index;
                    break;
                }
            }
        }

        $partners = [
            'active'  => null,
            'earlier' => [],
            'laters'  => [],
        ];

        foreach ($allPartners as $index => $partner) {
            if ($index < $activeIndex) {
                $partners['earlier'][] = $partner;
            } elseif ($index === $activeIndex) {
                $partners['active'] = $partner;
            } else {
                $partners['laters'][] = $partner;
            }
        }

        return $partners;
    }

    private function parsePersonId(string $personId): array
    {
        $appId = 1;
        $idKey = $personId;

        if (strpos($personId, '!') !== false) {
            list($appId, $idKey) = explode('!', $personId, 2);
        }

        return [(int) $appId, $idKey];
    }

    private function formatEventInfo(?object $event): string
    {
        if (!$event) {
            return '';
        }
        $date  = trim($event->eventDate ?? '');
        $place = trim($event->location ?? '');
        return trim($date . ($place ? ' ' . $place : ''));
    }

    private function getPersonUrl(string $personKey, int $appId): string
    {
        $jinput = Factory::getApplication()->input;
        $treeId = $jinput->getInt('treeId', 0);
        $urlParams = 'index.php?option=com_joaktree&view=joaktree&treeId=' . $treeId . '&personId=' . $appId . '!' . $personKey;

        return Route::_($urlParams);
    }

    private function getPersonRecord(string $personKey, int $appId): ?object
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__joaktree_persons', 'p'))
            ->where($db->quoteName('p.app_id') . ' = ' . $appId)
            ->where($db->quoteName('p.id') . ' = ' . $db->quote($personKey));

        $db->setQuery($query);
        return $db->loadObject();
    }

    private function buildPersonArray(string $personKey, int $appId): ?array
    {
        $personData = $this->getPersonRecord($personKey, $appId);

        if (!$personData) {
            return null;
        }

        $events = $this->getPersonEvents($personKey, ['BIRT', 'DEAT']);

        return [
            'appId'      => $appId,
            'personKey'  => $personKey,
            'firstNames' => $personData->firstName ?? '',
            'familyName' => $personData->familyName ?? '',
            'birthInfo'  => $this->formatEventInfo($events['BIRT'] ?? null),
            'deathInfo'  => $this->formatEventInfo($events['DEAT'] ?? null),
            'url'        => $this->getPersonUrl($personKey, $appId),
        ];
    }

    private function getRelationsForPerson(string $personId, int $appId, array $types, ?string $order = null): array
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');
        $quotedTypes = array_map([$db, 'quote'], $types);

        $query = $db->getQuery(true)
            ->select('r.*')
            ->from($db->quoteName('#__joaktree_relations', 'r'))
            ->where('(' . $db->quoteName('r.person_id_1') . ' = ' . $db->quote($personId) . ' OR ' . $db->quoteName('r.person_id_2') . ' = ' . $db->quote($personId) . ')')
            ->where($db->quoteName('r.type') . ' IN (' . implode(',', $quotedTypes) . ')')
            ->where($db->quoteName('r.app_id') . ' = ' . $appId);

        if ($order) {
            $query->order($order);
        }

        $db->setQuery($query);
        return $db->loadObjectList() ?: [];
    }

    private function getRelationEvents(object $rel, array $codes): array
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');
        $quotedCodes = array_map([$db, 'quote'], $codes);

        $query = $db->getQuery(true)
            ->select([$db->quoteName('code'), $db->quoteName('eventDate'), $db->quoteName('location')])
            ->from($db->quoteName('#__joaktree_relation_events'))
            ->where($db->quoteName('app_id') . ' = ' . (int) $rel->app_id)
            ->where($db->quoteName('person_id_1') . ' = ' . $db->quote($rel->person_id_1))
            ->where($db->quoteName('person_id_2') . ' = ' . $db->quote($rel->person_id_2))
            ->where($db->quoteName('code') . ' IN (' . implode(',', $quotedCodes) . ')');

        $db->setQuery($query);
        return $db->loadObjectList('code') ?: [];
    }

    private function getRelationsForParentsOnly(string $personId, int $appId, array $types): array
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');
        $quotedTypes = array_map([$db, 'quote'], $types);

        $query = $db->getQuery(true)
            ->select('r.*')
            ->from($db->quoteName('#__joaktree_relations', 'r'))
            ->where($db->quoteName('r.person_id_1') . ' = ' . $db->quote($personId))
            ->where($db->quoteName('r.type') . ' IN (' . implode(',', $quotedTypes) . ')')
            ->where($db->quoteName('r.app_id') . ' = ' . $appId);

        $db->setQuery($query);
        return $db->loadObjectList() ?: [];
    }

    private function getPartnerRelationBetween(string $person1, string $person2, int $appId): ?object
    {
        $db = $this->getDatabase() ?? Factory::getContainer()->get('DatabaseDriver');

        $query = $db->getQuery(true)
            ->select('r.*')
            ->from($db->quoteName('#__joaktree_relations', 'r'))
            ->where($db->quoteName('r.type') . ' = ' . $db->quote('partner'))
            ->where($db->quoteName('r.app_id') . ' = ' . $appId)
            ->where('((' . $db->quoteName('r.person_id_1') . ' = ' . $db->quote($person1) . ' AND ' . $db->quoteName('r.person_id_2') . ' = ' . $db->quote($person2) . ') OR (' . $db->quoteName('r.person_id_1') . ' = ' . $db->quote($person2) . ' AND ' . $db->quoteName('r.person_id_2') . ' = ' . $db->quote($person1) . '))');

        $db->setQuery($query);
        return $db->loadObject() ?: null;
    }
}
