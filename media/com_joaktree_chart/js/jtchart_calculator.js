/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : jtchart_calculator.js
 * Location     : media/com_joaktree_chart/js/
 * Created      : 13-10-2026
 * Modified     : 21-10-2026
 *
 * Purpose:
 * Manages the calculations, dimensions and calculation logic for the graphical
 * visualisation (chart) within the person view of com_joaktree_chart,
 * including dynamic positioning of the main person, partners and children.
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

function updateContainerDimensions() {
    const container = document.getElementById('jtchart-container');
    if (!container) return;

    const width = container.clientWidth;
    const height = container.clientHeight;

    const widthEl = document.getElementById('container-width');
    const heightEl = document.getElementById('container-height');

    if (widthEl) widthEl.textContent = width;
    if (heightEl) heightEl.textContent = height;

    // 1. Calculate Layer 1 (parents)
    const parentPositions = positionParentCards(width);

    // 2. Position Layer 2 (main person + partner)
    positionMainPerson(width, parentPositions);
    positionPartnerPerson(width, parentPositions);

    // 3. Position Layer 3 (children)
    positionChildrenCards(width, parentPositions);
}

/**
 *** Parents & In-laws (Layer 1) - The Foundation ---
 * Returns the calculated X positions (left) and widths for use in Layer 2.
 */
function positionParentCards(totalWidth) {
    const parentsLayer = document.getElementById('parents-layer');
    if (!parentsLayer) return { mainCenter: totalWidth / 4, partnerCenter: (totalWidth / 4) * 3 };

    const layerHeight = parentsLayer.clientHeight;
    const parentLayerWidth = parentsLayer.clientWidth || totalWidth;
    const gapCenter = 20; // Fixed gap between mother HP and father Partner

    const mainFatherContainer = document.getElementById('mainParents-father-card');
    const mainMotherContainer = document.getElementById('mainParents-mother-card');
    const partnerFather = document.getElementById('partnerParents-father-card');
    const partnerMother = document.getElementById('partnerParents-mother-card');

    const wFatherMain = mainFatherContainer ? mainFatherContainer.offsetWidth : 0;
    const wMotherMain = mainMotherContainer ? mainMotherContainer.offsetWidth : 0;
    const wFatherPartner = partnerFather ? partnerFather.offsetWidth : 0;
    const wMotherPartner = partnerMother ? partnerMother.offsetWidth : 0;

    const totalCardsWidth = wFatherMain + wMotherMain + wFatherPartner + wMotherPartner;
    const remainingSpace = parentLayerWidth - totalCardsWidth - gapCenter;
    const lineWidth = Math.max(0, remainingSpace / 2);

    let currentX = 0;
    let fatherMainLeft = 0, motherMainLeft = 0, fatherPartnerLeft = 0, motherPartnerLeft = 0;

    // 1. Father of Main Person
    if (mainFatherContainer) {
        fatherMainLeft = currentX;
        const height = mainFatherContainer.offsetHeight || 100;
        const top = (layerHeight - height) / 2;

        mainFatherContainer.style.setProperty('position', 'absolute', 'important');
        mainFatherContainer.style.setProperty('left', fatherMainLeft + 'px', 'important');
        mainFatherContainer.style.setProperty('top', (top > 0 ? top : 0) + 'px', 'important');
    }
    currentX += wFatherMain + lineWidth;

    // Relation card for Parents of Main Person
    const mainParentsRelWrapper = document.getElementById('jt-main-parents-relation-card-wrapper');
    if (mainParentsRelWrapper && mainFatherContainer) {
        const relLeft = fatherMainLeft + wFatherMain + (lineWidth / 2);
        const relTop = layerHeight / 2;

        mainParentsRelWrapper.style.setProperty('position', 'absolute', 'important');
        mainParentsRelWrapper.style.setProperty('left', relLeft + 'px', 'important');
        mainParentsRelWrapper.style.setProperty('top', relTop + 'px', 'important');
        mainParentsRelWrapper.style.setProperty('display', 'block', 'important');
    }

    // 2. Mother of Main Person
    if (mainMotherContainer) {
        motherMainLeft = currentX;
        const height = mainMotherContainer.offsetHeight || 100;
        const top = (layerHeight - height) / 2;

        mainMotherContainer.style.setProperty('position', 'absolute', 'important');
        mainMotherContainer.style.setProperty('left', motherMainLeft + 'px', 'important');
        mainMotherContainer.style.setProperty('top', (top > 0 ? top : 0) + 'px', 'important');
    }
    currentX += wMotherMain + gapCenter;

    // 3. Father of Partner
    if (partnerFather) {
        fatherPartnerLeft = currentX;
        const height = partnerFather.offsetHeight || 100;
        const top = (layerHeight - height) / 2;

        partnerFather.style.setProperty('position', 'absolute', 'important');
        partnerFather.style.setProperty('left', fatherPartnerLeft + 'px', 'important');
        partnerFather.style.setProperty('top', (top > 0 ? top : 0) + 'px', 'important');
    }
    currentX += wFatherPartner + lineWidth;

    // Relation card for Parents of Partner
    const partnerParentsRelWrapper = document.getElementById('jt-partner-parents-relation-card-wrapper');
    if (partnerParentsRelWrapper && partnerFather) {
        const relLeft = fatherPartnerLeft + wFatherPartner + (lineWidth / 2);
        const relTop = layerHeight / 2;

        partnerParentsRelWrapper.style.setProperty('position', 'absolute', 'important');
        partnerParentsRelWrapper.style.setProperty('left', relLeft + 'px', 'important');
        partnerParentsRelWrapper.style.setProperty('top', relTop + 'px', 'important');
        partnerParentsRelWrapper.style.setProperty('display', 'block', 'important');
    }

    // 4. Mother of Partner
    if (partnerMother) {
        motherPartnerLeft = parentLayerWidth - wMotherPartner;
        const height = partnerMother.offsetHeight || 100;
        const top = (layerHeight - height) / 2;

        partnerMother.style.setProperty('position', 'absolute', 'important');
        partnerMother.style.setProperty('left', motherPartnerLeft + 'px', 'important');
        partnerMother.style.setProperty('top', (top > 0 ? top : 0) + 'px', 'important');
    }

    const mainCenter = (mainFatherContainer && mainMotherContainer)
        ? (fatherMainLeft + (wFatherMain / 2) + motherMainLeft + (wMotherMain / 2)) / 2
        : totalWidth / 4;

    const partnerCenter = (partnerFather && partnerMother)
        ? (fatherPartnerLeft + (wFatherPartner / 2) + motherPartnerLeft + (wMotherPartner / 2)) / 2
        : (totalWidth / 4) * 3;

    return { mainCenter, partnerCenter };
}

/**
 *** Main Person (Layer 2) ***
 */
function positionMainPerson(totalWidth, parentPositions) {
    const mainCard = document.getElementById('main-person-card');
    const mainLayer = document.getElementById('main-layer');
    if (!mainCard || !mainLayer) return;

    const cardWidth = mainCard.offsetWidth || 300;
    const calculatedLeft = parentPositions.mainCenter - (cardWidth / 2);

    const layerHeight = mainLayer.clientHeight;
    const cardHeight = mainCard.offsetHeight || 100;
    const calculatedTop = ((layerHeight - cardHeight) / 2);

    mainCard.style.setProperty('position', 'absolute', 'important');
    mainCard.style.setProperty('left', calculatedLeft + 'px', 'important');
    mainCard.style.setProperty('top', calculatedTop > 0 ? calculatedTop + 'px' : '0px', 'important');
}

/**
 * *** Active partner and stacked partners (Layer 2) ***
 */
function positionPartnerPerson(totalWidth, parentPositions) {
    const VISIBLE_STRIP_WIDTH = 40;

    const mainLayer = document.getElementById('main-layer');
    if (!mainLayer) return;

    const layerHeight = mainLayer.clientHeight;
    const partnerCard = document.getElementById('partner-person-card');
    if (!partnerCard) return;

    const earlierCards = Array.from(document.querySelectorAll('.partner-stack-earlier'));
    const laterCards = Array.from(document.querySelectorAll('.partner-stack-later'));

    const partnerWidth = partnerCard.offsetWidth || 300;
    const activeCardHeight = partnerCard.offsetHeight || 100;

    let allCards = [partnerCard].concat(earlierCards, laterCards);
    let maxWidth = partnerWidth;

    allCards.forEach(card => {
        const w = card.offsetWidth;
        if (w > maxWidth) maxWidth = w;
    });

    allCards.forEach(card => {
        card.style.setProperty('width', maxWidth + 'px', 'important');
        card.style.setProperty('height', activeCardHeight + 'px', 'important');
        card.style.setProperty('box-sizing', 'border-box', 'important');
    });

    const baseLeft = parentPositions.partnerCenter - (maxWidth / 2) + 10;
    const baseTop = (layerHeight - activeCardHeight) / 2;

    partnerCard.style.setProperty('position', 'absolute', 'important');
    partnerCard.style.setProperty('left', baseLeft + 'px', 'important');
    partnerCard.style.setProperty('top', (baseTop > 0 ? baseTop : 0) + 'px', 'important');
    partnerCard.style.setProperty('z-index', '5', 'important');

    const shiftOffset = VISIBLE_STRIP_WIDTH;

    // Earlier partners shift left and upward
    earlierCards.forEach((card) => {
        const level = parseInt(card.getAttribute('data-level')) || 1;
        const offset = level * shiftOffset;

        card.style.setProperty('position', 'absolute', 'important');
        card.style.setProperty('left', (baseLeft - offset) + 'px', 'important');
        card.style.setProperty('top', (baseTop - offset) + 'px', 'important');
        card.style.setProperty('z-index', (5 - level), 'important');
    });

    // Later partners shift right and downward
    laterCards.forEach((card) => {
        const level = parseInt(card.getAttribute('data-level')) || 1;
        const offset = level * shiftOffset;

        card.style.setProperty('position', 'absolute', 'important');
        card.style.setProperty('left', (baseLeft + offset) + 'px', 'important');
        card.style.setProperty('top', (baseTop + offset) + 'px', 'important');
        card.style.setProperty('z-index', (5 - level), 'important');
    });
}

/**
 * *** Children (Layer 3) ***
 */
function positionChildrenCards(totalWidth, parentPositions) {
    const childrenLayer = document.getElementById('children-layer');
    if (!childrenLayer) return;

    const childCards = Array.from(document.querySelectorAll('.child-card'));
    const numChildren = childCards.length;
    if (numChildren === 0) return;

    const layerWidth = childrenLayer.clientWidth || totalWidth;
    const layerHeight = childrenLayer.clientHeight || 300;
    const gap = 10; // Exactly 10px gap between child cards

    // Determine the maximum width of the child cards
    let maxChildWidth = 250;
    childCards.forEach(card => {
        const w = card.offsetWidth;
        if (w > maxChildWidth) {
            maxChildWidth = w;
        }
    });

    const activeCardHeight = childCards[0].offsetHeight || 100;

    // Apply the width and height to all child cards
    childCards.forEach(card => {
        card.style.setProperty('width', maxChildWidth + 'px', 'important');
        card.style.setProperty('height', activeCardHeight + 'px', 'important');
        card.style.setProperty('box-sizing', 'border-box', 'important');
    });

    // Determine the centre of the relation line between the main person and active partner in Layer 2
    const mainCard = document.getElementById('main-person-card');
    const partnerCard = document.getElementById('partner-person-card');
    let relationCenterX = totalWidth / 2;

    if (mainCard && partnerCard) {
        const mainRight = mainCard.offsetLeft + mainCard.offsetWidth;
        const partnerLeft = partnerCard.offsetLeft;
        relationCenterX = mainRight + ((partnerLeft - mainRight) / 2);
    } else if (mainCard) {
        relationCenterX = mainCard.offsetLeft + (mainCard.offsetWidth / 2);
    }

    // Total width of all child cards including the 10px gaps
    const totalChildrenWidth = (numChildren * maxChildWidth) + ((numChildren - 1) * gap);

    // 1. Centre the row around relationCenterX
    let startX = relationCenterX - (totalChildrenWidth / 2);

    // 2. If the left side falls outside the layer, start flush at left = 0px
    if (startX < 0) {
        startX = 0;
    }

    // 3. Adjust the width of the children-layer to accommodate a scrollbar if needed
    const requiredWidth = Math.max(layerWidth, startX + totalChildrenWidth + 20);
    childrenLayer.style.setProperty('width', requiredWidth + 'px', 'important');

    // 4. Position each child neatly side by side with 10px gap
    let currentX = startX;
    childCards.forEach((card, index) => {
        const top = (layerHeight - activeCardHeight) / 2;

        card.style.setProperty('position', 'absolute', 'important');
        card.style.setProperty('left', currentX + 'px', 'important');
        card.style.setProperty('top', (top > 0 ? top : 0) + 'px', 'important');

        // Increment currentX for the next card
        currentX += maxChildWidth + gap;
    });
}

// Execute on load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateContainerDimensions);
} else {
    updateContainerDimensions();
}

setTimeout(updateContainerDimensions, 100);
window.addEventListener('resize', updateContainerDimensions);
