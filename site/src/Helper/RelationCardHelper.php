<?php
/**
 * =============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : RelationCardHelper.php
 * Aangemaakt   : 13-10-2026
 * Gewijzigd    :
 *
 * Doel         : Centrale helper voor het renderen van relatiekaarten.
 *
 * Dit component verzorgt de grafische visualisatie van
 * genealogische gegevens uit com_joaktree.
 *
 * Auteur       : Frits van Leeuwen
 * Bedrijf      : Van Leeuwen Webdesign
 * Website      : https://vanleeuwenwebdesign.nl
 * Licentie     : GNU General Public License v2 of later
 * =============================================================================
 */

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper;

defined('_JEXEC') or die;

class RelationCardHelper
{
    public static function render(
        string $marriageInfo = '',
        string $divorceInfo = ''
    ): string {
        $html = '<div class="relation-card">';

         if (!empty($marriageInfo)) {
             $html .= '<div>∞ ' .
                 htmlspecialchars(
                     $marriageInfo,
                     ENT_QUOTES,
                     'UTF-8'
                 ) .
                 '</div>';
         }

        if (!empty($divorceInfo)) {
            $html .= '<div>⚮ ' .
                htmlspecialchars(
                    $divorceInfo,
                    ENT_QUOTES,
                    'UTF-8'
                ) .
                '</div>';
        }

        $html .= '</div>';

        return $html;
    }
}
