<?php
/**
 * =============================================================================
 * Component   : com_joaktree_chart
 * File        : SymbolHelper.php
 * Location    : site/src/Helper
 * Created     : 13-10-2026
 * Modified    :
 *
 * Purpose:
 * Symbols in one central place
 *
 * This component provides the graphical visualisation of
 * genealogical data from com_joaktree.
 *
 * Author       : Frits van Leeuwen
 * Company      : Van Leeuwen Webdesign
 * Website      : https://vanleeuwenwebdesign.nl
 * License      : GNU General Public License v2 or later*
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
