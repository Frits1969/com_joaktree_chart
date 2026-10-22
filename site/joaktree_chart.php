<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : joaktree_chart.php
 * Locatie      : site
 * Aangemaakt   : 12-10-2026
 * Gewijzigd    :
 *
 * Doel:
 * Eerste testpagina van Joaktree Chart.
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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Laat Joomla de applicatie en de MVC factory aanspreken voor dit component
$app = Factory::getApplication();
$input = $app->input;

// Bepaal de controller naam (standaard 'display' of wat uit de input komt)
$controllerName = $input->get('controller', 'Display');

// Pad naar de controller binnen jouw namespace-structuur
$classname = '\\VanLeeuwenWebdesign\\Component\\JoaktreeChart\\Site\\Controller\\' . ucfirst($controllerName) . 'Controller';

if (!class_exists($classname)) {
    // Fallback naar de standaard BaseController als de specifieke controller niet bestaat
    $classname = BaseController::class;
}

// Instantieer en voer de controller uit
/** @var BaseController $controller */
$controller = new $classname();
$controller->execute($input->get('task', 'display'));
$controller->redirect();
