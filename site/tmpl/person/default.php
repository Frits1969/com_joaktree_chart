<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : default.php
 * Location     : site/tmpl/person
 * Created      : 13-10-2026
 * Modified     : 20-10-2026
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

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper\SymbolHelper;
use VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper\PersonCardHelper;
use VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper\RelationCardHelper;

// Load stylesheets and javascript via the Uri root
$document = Joomla\CMS\Factory::getDocument();
$document->addStyleSheet(Uri::root(true) . '/media/com_joaktree_chart/css/jtchart.css');
$document->addStyleSheet(Uri::root(true) . '/media/com_joaktree_chart/css/jtchart_layers.css');
$document->addStyleSheet(Uri::root(true) . '/media/com_joaktree_chart/css/jtchart_personcard.css');
$document->addStyleSheet(Uri::root(true) . '/media/com_joaktree_chart/css/jtchart_lines.css');
$document->addStyleSheet(Uri::root(true) . '/media/com_joaktree_chart/css/jtchart_relationcard.css');
$document->addScript(Uri::root(true) . '/media/com_joaktree_chart/js/jtchart_calculator.js');
$document->addScript(Uri::root(true) . '/media/com_joaktree_chart/js/jtchart_link.js');
$document->addScript(Uri::root(true) . '/media/com_joaktree_chart/js/jtchart_changepartner.js');
$document->addScript(Uri::root(true) . '/media/com_joaktree_chart/js/jtchart_lines.js');

// Retrieve data from the view / model
$mainPerson     = $this->person ?? null;
$mainParents    = $this->mainParents ?? [];
$partnerParents = $this->partnerParents ?? [];
$partnerPerson  = $this->partnerPerson ?? null;
$children       = $this->children ?? [];
$layout         = $this->layout ?? [];

// Load symbols via SymbolHelper
$birthSymbol = SymbolHelper::birth();
$deathSymbol = SymbolHelper::death();
?>

<div id="jtchart-container">

    <div id="person-header-badge">
        <div id="badge-inner">

            <!-- ======================================================== -->
            <!-- OVERARCHING CONNECTION LINES (LAYER 1 -> LAYER 2)         -->
            <!-- ======================================================== -->
            <?php if (!empty($mainParents['father']) || !empty($mainParents['mother'])) : ?>
            <div id="jt-main-parents-vertical-line" class="jt-line-vertical"></div>
            <?php endif; ?>

            <?php if (!empty($partnerParents['father']) || !empty($partnerParents['mother'])) : ?>
            <div id="jt-partner-parents-vertical-line" class="jt-line-vertical"></div>
            <?php endif; ?>

            <!-- ======================================================== -->
            <!-- LAYER 1: PARENTS                                          -->
            <!-- ======================================================== -->
            <div id="parents-layer">

                <!-- 1. PARENTS OF MAIN PERSON -->
                <?php if (!empty($mainParents['father']) || !empty($mainParents['mother'])) : ?>
                <?php if (!empty($mainParents['father'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($mainParents['father'], $birthSymbol, $deathSymbol, 'mainParents-father-card', true); ?>
                <?php endif; ?>

                <!-- Connection line between parents of main person -->
                <div id="jt-main-parents-line" class="jt-line-parents"></div>

                <!-- Relation card between parents of main person -->
                <?php if (!empty($mainParents['marriageInfo']) || !empty($mainParents['divorceInfo'])) : ?>
                <div id="jt-main-parents-relation-card-wrapper">
                    <?php echo RelationCardHelper::render($mainParents['marriageInfo'] ?? '', $mainParents['divorceInfo'] ?? ''); ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($mainParents['mother'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($mainParents['mother'], $birthSymbol, $deathSymbol, 'mainParents-mother-card', true); ?>
                <?php endif; ?>
                <?php endif; ?>

                <!-- 2. PARENTS OF PARTNER -->
                <?php if (!empty($partnerParents['father']) || !empty($partnerParents['mother'])) : ?>
                <?php if (!empty($partnerParents['father'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($partnerParents['father'], $birthSymbol, $deathSymbol, 'partnerParents-father-card', true); ?>
                <?php endif; ?>

                <!-- Connection line between parents of partner -->
                <div id="jt-partner-parents-line" class="jt-line-parents"></div>

                <!-- Relation card between parents of partner -->
                <?php if (!empty($partnerParents['marriageInfo']) || !empty($partnerParents['divorceInfo'])) : ?>
                <div id="jt-partner-parents-relation-card-wrapper">
                    <?php echo RelationCardHelper::render($partnerParents['marriageInfo'] ?? '', $partnerParents['divorceInfo'] ?? ''); ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($partnerParents['mother'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($partnerParents['mother'], $birthSymbol, $deathSymbol, 'partnerParents-mother-card', true); ?>
                <?php endif; ?>
                <?php endif; ?>

            </div>

            <!-- ======================================================== -->
            <!-- LAYER 2: MAIN PERSON + PARTNER(S)                         -->
            <!-- ======================================================== -->
            <div id="main-layer">

                <!-- Main person card -->
                <?php
                    echo PersonCardHelper::renderMainCard($mainPerson, $birthSymbol, $deathSymbol);
                ?>
                <!-- Relation card above the relation line -->
                <?php if (!empty($partnerPerson)) : ?>
                <div id="jt-main-relation-card-wrapper">
                    <?php
                        echo RelationCardHelper::render(
                            $partnerPerson['marriageInfo'] ?? '',
                            $partnerPerson['divorceInfo'] ?? ''
                        );
                    ?>
                </div>
                <?php endif; ?>
                <!-- Connection line between main person and active partner -->
                <div id="jt-partner-connection-line" class="jt-line-partner"></div>

                <!-- ======================================================== -->
                <!-- PARTNER SECTION (Dynamic number of placeholders)          -->
                <!-- ======================================================== -->
                <div id="partner-container">

                    <!-- 1. EARLIER PARTNERS -->
                    <?php if (!empty($this->earlierPartners)) : ?>
                    <?php foreach ($this->earlierPartners as $index => $earlierPartner) : ?>
                    <?php echo PersonCardHelper::renderStackedCard($earlierPartner, 'earlier', $index + 1); ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- 2. ACTIVE PARTNER -->
                    <?php
                        echo PersonCardHelper::renderActivePartnerCard($partnerPerson, $birthSymbol, $deathSymbol);
                    ?>

                    <!-- 3. LATER PARTNERS -->
                    <?php if (!empty($this->laterPartners)) : ?>
                    <?php foreach ($this->laterPartners as $index => $laterPartner) : ?>
                    <?php echo PersonCardHelper::renderStackedCard($laterPartner, 'later', $index + 1); ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- LAYER 3: CHILDREN                                         -->
            <!-- ======================================================== -->
            <div id="children-layer">
                <?php if (!empty($children)) : ?>
                <?php foreach ($children as $index => $child) : ?>
                <?php
                        echo PersonCardHelper::renderChildCard(
                            $child,
                            $birthSymbol,
                            $deathSymbol,
                            'child-card-' . $index,
                            true
                        );
                    ?>
                <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- ======================================================== -->
            <!-- CONNECTION LINES TO CHILDREN (COMB / RAKE STRUCTURE)      -->
            <!-- ======================================================== -->
            <?php if (!empty($children)) : ?>
            <div id="jt-children-vertical-main" class="jt-line-vertical"></div>
            <div id="jt-children-horizontal-bar" class="jt-line-horizontal-children"></div>
            <?php foreach ($children as $index => $child) : ?>
            <div id="jt-child-vertical-<?php echo $index; ?>" class="jt-line-vertical child-vertical-line"></div>
            <?php endforeach; ?>
            <?php endif; ?>

        </div>
    </div>
</div>
