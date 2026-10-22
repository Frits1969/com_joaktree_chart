<?php
/**
 * =============================================================================
 * Component   : com_joaktree_chart
 * Bestand     : SymbolHelper.php
 * Locatie      : site/src/Helper
 * Aangemaakt   : 13-10-2026
 * Gewijzigd    :
 *
 * Doel:
 *Symbolen op 1 plek
 *
 * Dit component verzorgt de grafische visualisatie van
 * genealogische gegevens uit com_joaktree.
 *
 * Auteur       : Frits van Leeuwen
 * Bedrijf      : Van Leeuwen Webdesign
 * Website      : https://vanleeuwenwebdesign.nl
 * Licentie     : GNU General Public License v2 of later*
 * =============================================================================
 */

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;

class SymbolHelper
{
    public static function birth(): string
    {
        $symbol = Text::_('JT_SYMBOL_BIRTH');
        return ($symbol !== 'JT_SYMBOL_BIRTH')
            ? $symbol
            : '*';
    }

    public static function death(): string
    {
        $symbol = Text::_('JT_SYMBOL_DEATH');
        return ($symbol !== 'JT_SYMBOL_DEATH')
            ? $symbol
            : '†';
    }

    public static function marriage(): string
    {
        $symbol = Text::_('JT_SYMBOL_MARRIAGE');
        return ($symbol !== 'JT_SYMBOL_MARRIAGE')
            ? $symbol
            : '∞';
    }

    public static function divorce(): string
    {
        $symbol = Text::_('JT_SYMBOL_DIVORCE');
        return ($symbol !== 'JT_SYMBOL_DIVORCE')
            ? $symbol
            : '⚮';
    }
}
