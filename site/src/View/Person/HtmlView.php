<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : HtmlView.php
 * Location     : site/src/View/Person
 * Created      : 12-10-2026
 * Modified     : 20-10-2026
 *
 * Purpose:
 * View for the person page.
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

namespace VanLeeuwenWebdesign\Component\JoaktreeChart\Site\View\Person;

defined('_JEXEC') or die;

use Joomla\CMS\MVC\View\HtmlView as JoomlaHtmlView;

class HtmlView extends JoomlaHtmlView
{
    protected $person;
    protected $partnerPerson;
    protected $earlierPartners;
    protected $laterPartners;
    protected $mainParents;
    protected $partnerParents;
    protected $children;

    public function display($tpl = null)
    {
        $model = $this->getModel();
        $app   = \Joomla\CMS\Factory::getApplication();

        $selectedPartnerKey = null;

        $this->mainParents    = ['father' => null, 'mother' => null, 'marriageInfo' => '', 'divorceInfo' => ''];
        $this->partnerParents = ['father' => null, 'mother' => null, 'marriageInfo' => '', 'divorceInfo' => ''];
        $this->children       = [];

        if ($model) {
            if (method_exists($model, 'getPerson')) {
                $this->person = $model->getPerson();
            }

            if ($this->person && method_exists($model, 'getPartners')) {
                $selectedPartnerKey = $app->input->getString('selectedPartnerKey', null);

                $partners = $model->getPartners(
                    $this->person['personKey'],
                    $this->person['appId'],
                    $selectedPartnerKey
                );

                $this->partnerPerson   = $partners['active'] ?? null;
                $this->earlierPartners = $partners['earlier'] ?? [];
                $this->laterPartners   = $partners['laters'] ?? [];
            }

            if ($this->person && method_exists($model, 'getParents')) {
                $this->mainParents = $model->getParents(
                    $this->person['personKey'],
                    $this->person['appId']
                );
            }

            if ($this->partnerPerson && method_exists($model, 'getParents')) {
                $this->partnerParents = $model->getParents(
                    $this->partnerPerson['personKey'],
                    $this->partnerPerson['appId']
                );
            }

            // Retrieve the children of the main person
            if ($this->person && method_exists($model, 'getChildren')) {
                $this->children = $model->getChildren(
                    $this->person['personKey'],
                    $this->person['appId']
                );
            }
        }

        parent::display($tpl);
    }
}
