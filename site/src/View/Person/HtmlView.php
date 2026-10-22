<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : HtmlView.php
 * Locatie      : site/src/View/Person
 * Aangemaakt   : 12-10-2026
 * Gewijzigd    : 20-10-2026
 *
 * Doel:
 * View voor de persoonspagina.
 *
 * Dit component verzorgt de grafische visualisatie van
 * genealogische gegevens uit com_joaktree.
 *
 * Auteur       : Frits van Leeuwen
 * Bedrijf      : Van Leeuwen Webdesign
 * Website      : https://vanleeuwenwebdesign.nl
 * Licentie     : GNU General Public License v2 of later
 * ============================================================================
 */

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\View\Person;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as JoomlaHtmlView;

class HtmlView extends JoomlaHtmlView
{
    protected $person;
    protected $partnerPerson;
    protected $earlierPartners;
    protected $laterPartners;
    protected $mainParents;
    protected $partnerParents;
    protected $children;

    public function display($tpl = null)
    {
        $model = $this->getModel();
        $app   = \Joomla\CMS\Factory::getApplication();

        $selectedPartnerKey = null;

        $this->mainParents    = ['father' => null, 'mother' => null, 'marriageInfo' => '', 'divorceInfo' => ''];
        $this->partnerParents = ['father' => null, 'mother' => null, 'marriageInfo' => '', 'divorceInfo' => ''];
        $this->children       = [];

        if ($model) {
            if (method_exists($model, 'getPerson')) {
                $this->person = $model->getPerson();
            }

            if ($this->person && method_exists($model, 'getPartners')) {
                $selectedPartnerKey = $app->input->getString('selectedPartnerKey', null);

                $partners = $model->getPartners(
                    $this->person['personKey'],
                    $this->person['appId'],
                    $selectedPartnerKey
                );

                $this->partnerPerson   = $partners['active'] ?? null;
                $this->earlierPartners = $partners['earlier'] ?? [];
                $this->laterPartners   = $partners['laters'] ?? [];
            }

            if ($this->person && method_exists($model, 'getParents')) {
                $this->mainParents = $model->getParents(
                    $this->person['personKey'],
                    $this->person['appId']
                );
            }

            if ($this->partnerPerson && method_exists($model, 'getParents')) {
                $this->partnerParents = $model->getParents(
                    $this->partnerPerson['personKey'],
                    $this->partnerPerson['appId']
                );
            }

            // Haal de kinderen op van de hoofdpersoon
            if ($this->person && method_exists($model, 'getChildren')) {
                $this->children = $model->getChildren(
                    $this->person['personKey'],
                    $this->person['appId']
                );
            }
        }

        parent::display($tpl);
    }
}
