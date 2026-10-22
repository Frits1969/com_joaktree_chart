<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : provider.php
 * Location     : administrator/services
 * Created      : 12-10-2026
 * Modified     :
 *
 * Purpose:
 * Service Provider for Joomla 6.
 * Registers the MVC factory and component dispatcher for
 * com_joaktree_chart.
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
