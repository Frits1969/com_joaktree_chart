<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : provider.php
 * Locatie      : administrator/services
 * Aangemaakt   : 12-10-2026
 * Gewijzigd    :
 *
 * Doel:
 * Service Provider voor Joomla 6.
 * Registreert de MVC-fabriek en component-dispatcher voor
 * com_joaktree_chart.
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

defined('_JEXEC') or die;

use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

return new class () implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        $container->registerServiceProvider(
            new MVCFactory('\\VanLeeuwenWebdesign\\Component\\JoaktreeChart')
        );

        $container->registerServiceProvider(
            new ComponentDispatcherFactory('\\VanLeeuwenWebdesign\\Component\\JoaktreeChart')
        );
    }
};
