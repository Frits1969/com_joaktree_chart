<?php
/**
 * =============================================================================
 * Component     : com_joaktree_chart
 * Bestand       : PersonCardHelper.php
 * Aangemaakt    : 13-10-2026
 * Gewijzigd     : 19-10-2026
 *
 * Doel          : Centrale helper voor het renderen van alle personenkaarten.
 *
 * Dit component verzorgt de grafische visualisatie van
 * genealogische gegevens uit com_joaktree.
 *
 * Auteur        : Frits van Leeuwen
 * Bedrijf       : Van Leeuwen Webdesign
 * Website       : https://vanleeuwenwebdesign.nl
 * Licentie      : GNU General Public License v2 of later
 * =============================================================================
 */

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper;

defined('_JEXEC') or die;

class PersonCardHelper
{
    /**
     * Interne hulpmethode om de HTML-inhoud (naam en datums) van een kaart te genereren.
     */
    private static function renderCardInner(array $person, string $birthSymbol, string $deathSymbol): string
    {
        $firstNames = htmlspecialchars($person['firstNames'] ?? '', ENT_QUOTES, 'UTF-8');
        $familyName = htmlspecialchars($person['familyName'] ?? '', ENT_QUOTES, 'UTF-8');
        $birthInfo  = htmlspecialchars($person['birthInfo'] ?? '', ENT_QUOTES, 'UTF-8');
        $deathInfo  = htmlspecialchars($person['deathInfo'] ?? '', ENT_QUOTES, 'UTF-8');

        $html  = '<div class="person-name">' . $firstNames . '</div>';
        $html .= '<div class="person-family-name">' . $familyName . '</div>';

        if (!empty($birthInfo) || !empty($deathInfo)) {
            $html .= '<div class="person-dates">';
            if (!empty($birthInfo)) {
                $html .= '<div>' . $birthSymbol . ' ' . $birthInfo . '</div>';
            }
            if (!empty($deathInfo)) {
                $html .= '<div>' . $deathSymbol . ' ' . $deathInfo . '</div>';
            }
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Basis-renderfunctie voor standaard kaarten (zoals kinderen).
     */
    public static function render(
        ?array $person,
        string $birthSymbol,
        string $deathSymbol,
        bool $mainCard = false,
        bool $clickable = false
    ): string {
        if (empty($person)) {
            return '';
        }

        $cardClass = 'person-card shadow-sm';
        if ($clickable) {
            $cardClass .= ' person-card-clickable';
        }
        if ($mainCard) {
            $cardClass .= ' person-card-main';
        }

        return '<div class="' . $cardClass . '">'
             . self::renderCardInner($person, $birthSymbol, $deathSymbol)
             . '</div>';
    }

    /**
     * 1. Rendert de Hoofdpersoon.
     */
    public static function renderMainCard(?array $person, string $birthSymbol, string $deathSymbol): string
    {
        if (empty($person)) {
            return '<div id="main-person-card" class="person-card person-card-main">'
                 . '<div class="person-name">Selecteer</div>'
                 . '<div class="person-family-name">Hoofdpersoon</div>'
                 . '<div class="person-dates">* - †</div>'
                 . '</div>';
        }

        $rendered = self::render($person, $birthSymbol, $deathSymbol, true, false);
        return str_replace('class="person-card', 'id="main-person-card" class="person-card person-card-main', $rendered);
    }

    /**
     * 2. Rendert de Actieve Partner (wel of geen kaart afhankelijk van aanwezigheid).
     */
    public static function renderActivePartnerCard(?array $person, string $birthSymbol, string $deathSymbol): string
    {
        if (empty($person)) {
            return ''; // Geen partner = helemaal niets tonen
        }

        $partnerUrl = $person['url'] ?? '';
        if (empty($partnerUrl) && !empty($person['personKey'])) {
            $appId      = $person['appId'] ?? '1';
            $partnerKey = $person['personKey'];
            $partnerUrl = \Joomla\CMS\Router\Route::_('index.php?option=com_joaktree_chart&view=person&id=' . $appId . '!' . $partnerKey);
        }

        $rendered = self::render($person, $birthSymbol, $deathSymbol, false, true);
        $renderedWithId = str_replace('class="person-card', 'id="partner-person-card" class="person-card person-card-partner"', $rendered);

        return '<div id="partner-person-card-wrapper" class="jt-person-link" data-url="' . htmlspecialchars($partnerUrl, ENT_QUOTES, 'UTF-8') . '">'
             . $renderedWithId
             . '</div>';
    }

    /**
     * 3. Rendert een Gestapelde (inactieve) Partnerkaart.
     */
    public static function renderStackedCard(
        ?array $person,
        string $position = 'earlier',
        int $level = 1
    ): string {
        if (empty($person)) {
            return '';
        }

        $fullName  = trim(($person['firstNames'] ?? '') . ' ' . ($person['familyName'] ?? '')) ?: 'Test Partner';
        $isEarlier = ($position === 'earlier');
        $stackClass = $isEarlier ? 'partner-stack-earlier' : 'partner-stack-later';
        $personKey  = htmlspecialchars($person['personKey'] ?? '', ENT_QUOTES, 'UTF-8');
        $appId      = htmlspecialchars($person['appId'] ?? '1', ENT_QUOTES, 'UTF-8');

        $jinput     = \Joomla\CMS\Factory::getApplication()->input;
        $treeId     = $jinput->getInt('treeId', 0);

        $html  = '<div class="person-card person-card-partner-stack ' . $stackClass . ' jt-inactive-partner" '
               . 'data-level="' . $level . '" '
               . 'data-partner-key="' . $personKey . '" '
               . 'data-app-id="' . $appId . '" '
               . 'data-tree-id="' . $treeId . '">';

        if ($isEarlier) {
            $html .= '<div class="person-name stack-name-top">' . htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') . '</div>';
        } else {
            $html .= '<div class="person-name stack-name-bottom">' . htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8') . '</div>';
        }

        $html .= '</div>';

        return $html;
    }

    /**
     * 4. Rendert een Ouderkaart (vader/moeder van hoofdpersoon of partner).
     */
    public static function renderParentCard(?array $person, string $birthSymbol, string $deathSymbol, string $cardId, bool $clickable = false): string
    {
        if (empty($person)) {
            return ''; // Geen ouder = geen kaart renderen
        }

        $url = htmlspecialchars($person['url'] ?? '', ENT_QUOTES, 'UTF-8');

        $cardClass = 'person-card person-card-partner shadow-sm';
        if ($clickable && !empty($url)) {
            $cardClass .= ' person-card-clickable jt-person-link';
        }

        $html  = '<div id="' . $cardId . '" class="' . $cardClass . '"';
        if (!empty($url)) {
            $html .= ' data-url="' . $url . '"';
        }
        $html  .= '>';
        $html  .= self::renderCardInner($person, $birthSymbol, $deathSymbol);
        $html  .= '</div>';

        return $html;
    }

    /**
     * 5. Rendert een Kinderkaart voor Laag 3.
     */
    public static function renderChildCard(?array $person, string $birthSymbol, string $deathSymbol, string $cardId, bool $clickable = true): string
    {
        if (empty($person)) {
            return '';
        }

        $url = htmlspecialchars($person['url'] ?? '', ENT_QUOTES, 'UTF-8');

        // Voeg 'person-card-partner' toe voor exact dezelfde opmaak en kader als de ouders
        $cardClass = 'person-card person-card-partner shadow-sm child-card';
        if ($clickable && !empty($url)) {
            $cardClass .= ' person-card-clickable jt-person-link';
        }

        $html  = '<div id="' . $cardId . '" class="' . $cardClass . '"';
        if (!empty($url)) {
            $html .= ' data-url="' . $url . '"';
        }
        $html  .= '>';
        $html  .= self::renderCardInner($person, $birthSymbol, $deathSymbol);
        $html  .= '</div>';

        return $html;
    }
}
