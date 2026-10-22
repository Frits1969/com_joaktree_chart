<?php
/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : default.php
 * Locatie      : site/tmpl/person
 * Aangemaakt   : 13-10-2026
 * Gewijzigd    : 20-10-2026
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

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;
use VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper\SymbolHelper;
use VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper\PersonCardHelper;
use VanLeeuwenWebdesign\Component\JoaktreeChart\Site\Helper\RelationCardHelper;

// Laad de stylesheets en javascript via de Uri-root
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

// Gegevens ophalen uit de view / model
$mainPerson     = $this->person ?? null;
$mainParents    = $this->mainParents ?? [];
$partnerParents = $this->partnerParents ?? [];
$partnerPerson  = $this->partnerPerson ?? null;
$children       = $this->children ?? [];
$layout         = $this->layout ?? [];

// Symbolen inladen via SymbolHelper
$birthSymbol = SymbolHelper::birth();
$deathSymbol = SymbolHelper::death();
?>

<div id="jtchart-container">

    <div id="person-header-badge">
        <div id="badge-inner">

            <!-- ======================================================== -->
            <!-- OVERKOEPELENDE VERBINDINGSLIJNEN (LAAG 1 -> LAAG 2)      -->
            <!-- ======================================================== -->
            <?php if (!empty($mainParents['father']) || !empty($mainParents['mother'])) : ?>
            <div id="jt-main-parents-vertical-line" class="jt-line-vertical"></div>
            <?php endif; ?>

            <?php if (!empty($partnerParents['father']) || !empty($partnerParents['mother'])) : ?>
            <div id="jt-partner-parents-vertical-line" class="jt-line-vertical"></div>
            <?php endif; ?>

            <!-- ======================================================== -->
            <!-- LAAG 1: OUDERS                                           -->
            <!-- ======================================================== -->
            <div id="parents-layer">

                <!-- 1. OUDERS HOOFDPERSOON -->
                <?php if (!empty($mainParents['father']) || !empty($mainParents['mother'])) : ?>
                <?php if (!empty($mainParents['father'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($mainParents['father'], $birthSymbol, $deathSymbol, 'mainParents-father-card', true); ?>
                <?php endif; ?>

                <!-- Verbindingslijn tussen ouders van hoofdpersoon -->
                <div id="jt-main-parents-line" class="jt-line-parents"></div>

                <!-- Relatiekaartje tussen ouders van hoofdpersoon -->
                <?php if (!empty($mainParents['marriageInfo']) || !empty($mainParents['divorceInfo'])) : ?>
                <div id="jt-main-parents-relation-card-wrapper">
                    <?php echo RelationCardHelper::render($mainParents['marriageInfo'] ?? '', $mainParents['divorceInfo'] ?? ''); ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($mainParents['mother'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($mainParents['mother'], $birthSymbol, $deathSymbol, 'mainParents-mother-card', true); ?>
                <?php endif; ?>
                <?php endif; ?>

                <!-- 2. OUDERS PARTNER -->
                <?php if (!empty($partnerParents['father']) || !empty($partnerParents['mother'])) : ?>
                <?php if (!empty($partnerParents['father'])) : ?>
                <?php echo PersonCardHelper::renderParentCard($partnerParents['father'], $birthSymbol, $deathSymbol, 'partnerParents-father-card', true); ?>
                <?php endif; ?>

                <!-- Verbindingslijn tussen ouders van partner -->
                <div id="jt-partner-parents-line" class="jt-line-parents"></div>

                <!-- Relatiekaartje tussen ouders van partner -->
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
            <!-- LAAG 2: HOOFDPERSOON + PARTNER(S)                        -->
            <!-- ======================================================== -->
            <div id="main-layer">

                <!-- Kaart hoofdpersoon -->
                <?php
                    echo PersonCardHelper::renderMainCard($mainPerson, $birthSymbol, $deathSymbol);
                ?>
                <!-- Relatiekaartje boven de relatielijn -->
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
                <!-- Verbindingslijn tussen hoofdpersoon en actieve partner -->
                <div id="jt-partner-connection-line" class="jt-line-partner"></div>

                <!-- ======================================================== -->
                <!-- PARTNER SECTIE (Dynamisch aantal placeholders)           -->
                <!-- ======================================================== -->
                <div id="partner-container">

                    <!-- 1. EERDERE PARTNERS -->
                    <?php if (!empty($this->earlierPartners)) : ?>
                    <?php foreach ($this->earlierPartners as $index => $earlierPartner) : ?>
                    <?php echo PersonCardHelper::renderStackedCard($earlierPartner, 'earlier', $index + 1); ?>
                    <?php endforeach; ?>
                    <?php endif; ?>

                    <!-- 2. ACTIEVE PARTNER -->
                    <?php
                        echo PersonCardHelper::renderActivePartnerCard($partnerPerson, $birthSymbol, $deathSymbol);
                    ?>

                    <!-- 3. LATERE PARTNERS -->
                    <?php if (!empty($this->laterPartners)) : ?>
                    <?php foreach ($this->laterPartners as $index => $laterPartner) : ?>
                    <?php echo PersonCardHelper::renderStackedCard($laterPartner, 'later', $index + 1); ?>
                    <?php endforeach; ?>
                    <?php endif; ?>
                </div>

            </div>

            <!-- ======================================================== -->
            <!-- LAAG 3: KINDEREN                                         -->
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
            <!-- VERBINDINGSLIJNEN NAAR KINDEREN (HARAK / KAM-STRUCTUUR)  -->
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
