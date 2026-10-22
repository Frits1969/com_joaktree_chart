/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : jtchart_lines.js
 * Locatie      : media/com_joaktree_chart/js/
 * Aangemaakt   : 16-10-2026
 * Gewijzigd    : 21-10-2026
 *
 * Doel:
 * Beheert het tekenen van alle lijnen tussen personen nadat de kaarten
 * gepositioneerd zijn. Horizontale en verticale ouderlijnen zijn samengevoegd
 * om dubbeling en uitlijningsfouten te voorkomen.
 * ============================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    drawAllLines();
    window.addEventListener('resize', drawAllLines);
});

// Kleine pauze zodat de calculator eerst de kaarten kan neerzetten
function drawAllLines() {
    setTimeout(() => {
        drawPartnerLine();
        drawParentLinesAndVerticalLines();
        drawChildrenLines();
    }, 200);
}

function drawPartnerLine() {
    const mainCard = document.getElementById('main-person-card');
    const partnerCard = document.getElementById('partner-person-card');
    const line = document.getElementById('jt-partner-connection-line');
    const relationWrapper = document.getElementById('jt-main-relation-card-wrapper');

    if (!mainCard || !partnerCard || !line) {
        if (relationWrapper) relationWrapper.style.display = 'none';
        return;
    }

    const partnerName = partnerCard.querySelector('.person-family-name');
    if (!partnerName || partnerName.textContent.trim() === 'Partner') {
        line.style.display = 'none';
        if (relationWrapper) relationWrapper.style.display = 'none';
        return;
    }

    const mainLeft = mainCard.offsetLeft;
    const mainTop = mainCard.offsetTop;
    const mainWidth = mainCard.offsetWidth;
    const mainHeight = mainCard.offsetHeight;

    const partnerLeft = partnerCard.offsetLeft;

    const startX = mainLeft + mainWidth;
    const endX = partnerLeft;
    const width = endX - startX;

    if (width <= 0) {
        line.style.display = 'none';
        if (relationWrapper) relationWrapper.style.display = 'none';
        return;
    }

    const centerY = mainTop + (mainHeight / 2);

    line.style.display = 'block';
    line.style.left = startX + 'px';
    line.style.top = centerY + 'px';
    line.style.width = width + 'px';

    if (relationWrapper) {
        const lineCenterX = startX + (width / 2);

        relationWrapper.style.display = 'block';
        relationWrapper.style.setProperty('position', 'absolute', 'important');
        relationWrapper.style.setProperty('left', lineCenterX + 'px', 'important');
        relationWrapper.style.setProperty('top', centerY + 'px', 'important');
    }
}

/**
 * Gecombineerde functie voor zowel de horizontale ouderlijnen als de verticale lijnen
 * van ouders naar kinderen (Laag 1 -> Laag 2). Ze delen exact dezelfde coördinatenberekening.
 */
function drawParentLinesAndVerticalLines() {
    const parentsLayer = document.getElementById('parents-layer');
    if (!parentsLayer) return;

    const parentsLayerLeft = parentsLayer.offsetLeft || 0;
    const parentsLayerTop = parentsLayer.offsetTop || 0;
    const mainLayer = document.getElementById('main-layer');

    // ==========================================
    // 1. OUDERS HOOFDPERSOON
    // ==========================================
    const fatherMain = document.getElementById('mainParents-father-card');
    const motherMain = document.getElementById('mainParents-mother-card');
    const lineMain = document.getElementById('jt-main-parents-line');
    const relationWrapperMain = document.getElementById('jt-main-parents-relation-card-wrapper');
    const verticalLineMain = document.getElementById('jt-main-parents-vertical-line');
    const mainCard = document.getElementById('main-person-card');

    if (fatherMain && motherMain && lineMain) {
        const startX = fatherMain.offsetLeft + fatherMain.offsetWidth;
        const endX = motherMain.offsetLeft;
        const width = endX - startX;

        if (width > 0) {
            const centerY = fatherMain.offsetTop + (fatherMain.offsetHeight / 2);

            // Horizontale lijn tekenen
            lineMain.style.display = 'block';
            lineMain.style.left = startX + 'px';
            lineMain.style.top = centerY + 'px';
            lineMain.style.width = width + 'px';

            if (relationWrapperMain) {
                relationWrapperMain.style.display = 'block';
            }

            // Verticale lijn exact afleiden uit dezelfde breedte en startX
            if (verticalLineMain && mainCard) {
                const centerX = parentsLayerLeft + startX + (width / 2);
                const startY = parentsLayerTop + centerY;
                const endY = (mainLayer ? mainLayer.offsetTop : 0) + mainCard.offsetTop;
                const height = endY - startY;

                if (height > 0) {
                    verticalLineMain.style.display = 'block';
                    verticalLineMain.style.setProperty('position', 'absolute', 'important');
                    verticalLineMain.style.setProperty('left', centerX + 'px', 'important');
                    verticalLineMain.style.setProperty('top', startY + 'px', 'important');
                    verticalLineMain.style.setProperty('height', height + 'px', 'important');
                } else {
                    verticalLineMain.style.display = 'none';
                }
            }
        } else {
            lineMain.style.display = 'none';
            if (relationWrapperMain) relationWrapperMain.style.display = 'none';
            if (verticalLineMain) verticalLineMain.style.display = 'none';
        }
    } else {
        if (lineMain) lineMain.style.display = 'none';
        if (relationWrapperMain) relationWrapperMain.style.display = 'none';
        if (verticalLineMain) verticalLineMain.style.display = 'none';
    }

    // ==========================================
    // 2. OUDERS PARTNER
    // ==========================================
    const fatherPartner = document.getElementById('partnerParents-father-card');
    const motherPartner = document.getElementById('partnerParents-mother-card');
    const linePartner = document.getElementById('jt-partner-parents-line');
    const relationWrapperPartner = document.getElementById('partnerParents-relation-card');
    const verticalLinePartner = document.getElementById('jt-partner-parents-vertical-line');
    const partnerCard = document.getElementById('partner-person-card');

    if (fatherPartner && motherPartner && linePartner) {
        const startX = fatherPartner.offsetLeft + fatherPartner.offsetWidth;
        const endX = motherPartner.offsetLeft;
        const width = endX - startX;

        if (width > 0) {
            const centerY = fatherPartner.offsetTop + (fatherPartner.offsetHeight / 2);

            // Horizontale lijn tekenen
            linePartner.style.display = 'block';
            linePartner.style.left = startX + 'px';
            linePartner.style.top = centerY + 'px';
            linePartner.style.width = width + 'px';

            if (relationWrapperPartner) {
                relationWrapperPartner.style.display = 'block';
            }

            // Verticale lijn exact afleiden uit dezelfde breedte en startX
            if (verticalLinePartner && partnerCard) {
                const centerX = parentsLayerLeft + startX + (width / 2);
                const startY = parentsLayerTop + centerY;
                const endY = (mainLayer ? mainLayer.offsetTop : 0) + partnerCard.offsetTop;
                const height = endY - startY;

                if (height > 0) {
                    verticalLinePartner.style.display = 'block';
                    verticalLinePartner.style.setProperty('position', 'absolute', 'important');
                    verticalLinePartner.style.setProperty('left', centerX + 'px', 'important');
                    verticalLinePartner.style.setProperty('top', startY + 'px', 'important');
                    verticalLinePartner.style.setProperty('height', height + 'px', 'important');
                } else {
                    verticalLinePartner.style.display = 'none';
                }
            }
        } else {
            linePartner.style.display = 'none';
            if (relationWrapperPartner) relationWrapperPartner.style.display = 'none';
            if (verticalLinePartner) verticalLinePartner.style.display = 'none';
        }
    } else {
        if (linePartner) linePartner.style.display = 'none';
        if (relationWrapperPartner) relationWrapperPartner.style.display = 'none';
        if (verticalLinePartner) verticalLinePartner.style.display = 'none';
    }
}

/**
 * Tekent de verbindingslijnen van Laag 2 (partner-relatielijn) naar Laag 3 (kinderen)
 */
function drawChildrenLines() {
    const badgeInner = document.getElementById('badge-inner');
    const mainCard = document.getElementById('main-person-card');
    const partnerCard = document.getElementById('partner-person-card');
    const mainLayer = document.getElementById('main-layer');
    const childrenLayer = document.getElementById('children-layer');
    const partnerLine = document.getElementById('jt-partner-connection-line');

    const verticalMain = document.getElementById('jt-children-vertical-main');
    const horizontalBar = document.getElementById('jt-children-horizontal-bar');

    if (!badgeInner || !mainCard || !partnerCard || !mainLayer || !childrenLayer || !partnerLine || !verticalMain || !horizontalBar) {
        if (verticalMain) verticalMain.style.display = 'none';
        if (horizontalBar) horizontalBar.style.display = 'none';
        return;
    }

    const childCards = Array.from(document.querySelectorAll('.child-card'));
    if (childCards.length === 0) {
        verticalMain.style.display = 'none';
        horizontalBar.style.display = 'none';
        return;
    }

    // 1. Bereken het X-midden van de partnerrelatielijn precies tussen hoofdpersoon en partner
    const mainRight = mainCard.offsetLeft + mainCard.offsetWidth;
    const partnerLeft = partnerCard.offsetLeft;
    const relationCenterX = mainRight + ((partnerLeft - mainRight) / 2);
    const startX = mainLayer.offsetLeft + relationCenterX;

    // 2. Start Y is de Y-positie van de partnerrelatielijn
    const startY = mainLayer.offsetTop + partnerLine.offsetTop;

    // 3. Eind Y (waar de horizontale lijn op de top van Laag 3 ligt)
    const endY = childrenLayer.offsetTop;

    // Haal het hart (midden) op van het eerste en laatste kind ten opzichte van #badge-inner
    const firstChild = childCards[0];
    const lastChild = childCards[childCards.length - 1];

    const firstChildCenterX = childrenLayer.offsetLeft + firstChild.offsetLeft + (firstChild.offsetWidth / 2);
    const lastChildCenterX = childrenLayer.offsetLeft + lastChild.offsetLeft + (lastChild.offsetWidth / 2);

    if (childCards.length === 1) {
        // Bij 1 kind: rechte verticale lijn van relatielijn direct naar de bovenkant van het kind
        const childTop = childrenLayer.offsetTop + firstChild.offsetTop;
        const totalHeight = childTop - startY;

        verticalMain.style.display = 'block';
        verticalMain.style.setProperty('position', 'absolute', 'important');
        verticalMain.style.setProperty('left', firstChildCenterX + 'px', 'important');
        verticalMain.style.setProperty('top', startY + 'px', 'important');
        verticalMain.style.setProperty('height', totalHeight + 'px', 'important');

        horizontalBar.style.display = 'none';

        childCards.forEach((_, idx) => {
            const vLine = document.getElementById('jt-child-vertical-' + idx);
            if (vLine) vLine.style.display = 'none';
        });

    } else {
        // Bij meerdere kinderen: Kam-structuur met horizontale bar op de top van Laag 3 (endY)

        // 1. Hoofd verticale lijn vanaf relatielijn tot aan de top van Laag 3 (endY)
        verticalMain.style.display = 'block';
        verticalMain.style.setProperty('position', 'absolute', 'important');
        verticalMain.style.setProperty('left', startX + 'px', 'important');
        verticalMain.style.setProperty('top', startY + 'px', 'important');
        verticalMain.style.setProperty('height', (endY - startY) + 'px', 'important');

        // 2. Horizontale bar precies op de top van Laag 3 (endY), lopend van hart 1e kind tot hart laatste kind
        const barLeft = Math.min(firstChildCenterX, startX);
        const barRight = Math.max(lastChildCenterX, startX);
        const barWidth = barRight - barLeft;

        horizontalBar.style.display = 'block';
        horizontalBar.style.setProperty('position', 'absolute', 'important');
        horizontalBar.style.setProperty('left', barLeft + 'px', 'important');
        horizontalBar.style.setProperty('top', endY + 'px', 'important');
        horizontalBar.style.setProperty('width', barWidth + 'px', 'important');
        horizontalBar.style.setProperty('height', '2px', 'important');

        // 3. Verticale takjes vanaf elk kind omhoog naar de horizontale bar op endY
        childCards.forEach((card, idx) => {
            const vLine = document.getElementById('jt-child-vertical-' + idx);
            if (vLine) {
                const childCenterX = childrenLayer.offsetLeft + card.offsetLeft + (card.offsetWidth / 2);
                const childTop = childrenLayer.offsetTop + card.offsetTop;
                const lineTop = Math.min(endY, childTop);
                const lineHeight = Math.abs(childTop - endY);

                vLine.style.display = 'block';
                vLine.style.setProperty('position', 'absolute', 'important');
                vLine.style.setProperty('left', childCenterX + 'px', 'important');
                vLine.style.setProperty('top', lineTop + 'px', 'important');
                vLine.style.setProperty('height', lineHeight + 'px', 'important');
            }
        });
    }
}
