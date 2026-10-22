<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : joaktree_chart.php
 * Location     : site
 * Created      : 12-10-2026
 * Modified     :
 *
 * Purpose:
 * First test page of Joaktree Chart.
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

use Joomla\CMS\Factory;
use Joomla\CMS\MVC\Controller\BaseController;

// Let Joomla access the application and the MVC factory for this component
$app = Factory::getApplication();
$input = $app->input;

// Determine the controller name (default: 'display' or whatever comes from the input)
$controllerName = $input->get('controller', 'Display');

// Path to the controller within the namespace structure
$classname = '\\VanLeeuwenWebdesign\\Component\\JoaktreeChart\\Site\\Controller\\' . ucfirst($controllerName) . 'Controller';

if (!class_exists($classname)) {
    // Fallback to the standard BaseController if the specific controller does not exist
    $classname = BaseController::class;
}

// Instantiate and execute the controller
/** @var BaseController $controller */
$controller = new $classname();
$controller->execute($input->get('task', 'display'));
$controller->redirect();
