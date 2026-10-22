<?php
/**
 * =============================================================================
 * Component    : com_joaktree_chart
 * File         : RelationCardHelper.php
 * Created      : 13-10-2026
 * Modified     :
 *
 * Purpose      : Central helper for rendering relation cards.
 *
 * This component provides the graphical visualisation of
 * genealogical data from com_joaktree.
 *
 * Author       : Frits van Leeuwen
 * Company      : Van Leeuwen Webdesign
 * Website      : https://vanleeuwenwebdesign.nl
 * License      : GNU General Public License v2 or later
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
