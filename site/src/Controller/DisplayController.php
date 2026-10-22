<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : DisplayController.php
 * Locatie      : site/src/Controller
 * Aangemaakt   : 12-10-2026
 *
 * Doel:
 * Standaard controller voor com_joaktree_chart.
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

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

class DisplayController extends BaseController
{
    protected $default_view = 'person';

    public function display($cachable = false, $urlparams = false)
    {
        // Haal de view op die in de URL staat (standaard 'person')
        $viewName = $this->input->get('view', 'person');
        $viewType = $this->app->getDocument()->getType();
        $viewLayout = $this->input->get('layout', 'default');

        // Bepaal de volledige class van de View binnen jouw namespace
        $viewClass = '\\VanLeeuwenWebdesign\\Component\\JoaktreeChart\\Site\\View\\' . ucfirst($viewName) . '\\HtmlView';
        $modelClass = '\\VanLeeuwenWebdesign\\Component\\JoaktreeChart\\Site\\Model\\' . ucfirst($viewName) . 'Model';

        // Als de HTML view klasse bestaat, roepen we hem direct handmatig en feilloos aan
        if (class_exists($viewClass)) {
            $view = new $viewClass([
                'base_path' => $this->basePath,
                'layout'    => $viewLayout
            ]);

            // Koppel eventueel een model als het bestaat
            if (class_exists($modelClass)) {
                $model = new $modelClass();
                $view->setModel($model, true);
            }

            $view->document = $this->app->getDocument();

            // Voer de display uit van de view
            $view->display(null);
            return $this;
        }

        // Fallback naar oudergedrag als er iets afwijkt
        return parent::display($cachable, $urlparams);
    }
}
