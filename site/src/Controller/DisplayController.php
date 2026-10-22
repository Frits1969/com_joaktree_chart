<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : DisplayController.php
 * Location     : site/src/Controller
 * Created      : 12-10-2026
 *
 * Purpose:
 * Default controller for com_joaktree_chart.
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

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController
{
    protected $default_view = 'person';

    public function display($cachable = false, $urlparams = false)
    {
        // Get the view name from the URL (default: 'person')
        $viewName = $this->input->get('view', 'person');
        $viewType = $this->app->getDocument()->getType();
        $viewLayout = $this->input->get('layout', 'default');

        // Determine the full class name of the View within the namespace
        $viewClass = '\\VanLeeuwenWebdesign\\Component\\JoaktreeChart\\Site\\View\\' . ucfirst($viewName) . '\\HtmlView';
        $modelClass = '\\VanLeeuwenWebdesign\\Component\\JoaktreeChart\\Site\\Model\\' . ucfirst($viewName) . 'Model';

        // If the HTML view class exists, instantiate it directly
        if (class_exists($viewClass)) {
            $view = new $viewClass([
                'base_path' => $this->basePath,
                'layout'    => $viewLayout
            ]);

            // Attach a model if one exists
            if (class_exists($modelClass)) {
                $model = new $modelClass();
                $view->setModel($model, true);
            }

            $view->document = $this->app->getDocument();

            // Execute the display method of the view
            $view->display(null);
            return $this;
        }

        // Fallback to parent behaviour if something differs
        return parent::display($cachable, $urlparams);
    }
}
