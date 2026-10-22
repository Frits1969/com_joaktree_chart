/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : jtchart_lines.js
 * Location     : media/com_joaktree_chart/js/
 * Created      : 16-10-2026
 * Modified     : 21-10-2026
 *
 * Purpose:
 * Manages drawing of all lines between persons after the cards have been
 * positioned. Horizontal and vertical parent lines have been merged
 * to avoid duplication and alignment errors.
 * ============================================================================
 */

document.addEventListener('DOMContentLoaded', function () {
    drawAllLines();
    window.addEventListener('resize', drawAllLines);
});

// Brief pause to allow the calculator to position the cards first
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
 * Combined function for both the horizontal parent lines and the vertical lines
 * from parents to children (Layer 1 -> Layer 2). They share the exact same coordinate calculation.
 */
function drawParentLinesAndVerticalLines() {
    const parentsLayer = document.getElementById('parents-layer');
    if (!parentsLayer) return;

    const parentsLayerLeft = parentsLayer.offsetLeft || 0;
    const parentsLayerTop = parentsLayer.offsetTop || 0;
    const mainLayer = document.getElementById('main-layer');

    // ==========================================
    // 1. PARENTS OF MAIN PERSON
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

            // Draw horizontal line
            lineMain.style.display = 'block';
            lineMain.style.left = startX + 'px';
            lineMain.style.top = centerY + 'px';
            lineMain.style.width = width + 'px';

            if (relationWrapperMain) {
                relationWrapperMain.style.display = 'block';
            }

            // Derive the vertical line from the exact same width and startX
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
    // 2. PARENTS OF PARTNER
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

            // Draw horizontal line
            linePartner.style.display = 'block';
            linePartner.style.left = startX + 'px';
            linePartner.style.top = centerY + 'px';
            linePartner.style.width = width + 'px';

            if (relationWrapperPartner) {
                relationWrapperPartner.style.display = 'block';
            }

            // Derive the vertical line from the exact same width and startX
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
 * Draws the connection lines from Layer 2 (partner relation line) to Layer 3 (children)
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

    // 1. Calculate the X-centre of the partner relation line exactly between main person and partner
    const mainRight = mainCard.offsetLeft + mainCard.offsetWidth;
    const partnerLeft = partnerCard.offsetLeft;
    const relationCenterX = mainRight + ((partnerLeft - mainRight) / 2);
    const startX = mainLayer.offsetLeft + relationCenterX;

    // 2. Start Y is the Y-position of the partner relation line
    const startY = mainLayer.offsetTop + partnerLine.offsetTop;

    // 3. End Y (where the horizontal line sits at the top of Layer 3)
    const endY = childrenLayer.offsetTop;

    // Get the centre of the first and last child relative to #badge-inner
    const firstChild = childCards[0];
    const lastChild = childCards[childCards.length - 1];

    const firstChildCenterX = childrenLayer.offsetLeft + firstChild.offsetLeft + (firstChild.offsetWidth / 2);
    const lastChildCenterX = childrenLayer.offsetLeft + lastChild.offsetLeft + (lastChild.offsetWidth / 2);

    if (childCards.length === 1) {
        // With 1 child: straight vertical line from relation line directly to the top of the child card
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
        // With multiple children: comb structure with horizontal bar at the top of Layer 3 (endY)

        // 1. Main vertical line from relation line to the top of Layer 3 (endY)
        verticalMain.style.display = 'block';
        verticalMain.style.setProperty('position', 'absolute', 'important');
        verticalMain.style.setProperty('left', startX + 'px', 'important');
        verticalMain.style.setProperty('top', startY + 'px', 'important');
        verticalMain.style.setProperty('height', (endY - startY) + 'px', 'important');

        // 2. Horizontal bar precisely at the top of Layer 3 (endY), running from centre of 1st child to centre of last child
        const barLeft = Math.min(firstChildCenterX, startX);
        const barRight = Math.max(lastChildCenterX, startX);
        const barWidth = barRight - barLeft;

        horizontalBar.style.display = 'block';
        horizontalBar.style.setProperty('position', 'absolute', 'important');
        horizontalBar.style.setProperty('left', barLeft + 'px', 'important');
        horizontalBar.style.setProperty('top', endY + 'px', 'important');
        horizontalBar.style.setProperty('width', barWidth + 'px', 'important');
        horizontalBar.style.setProperty('height', '2px', 'important');

        // 3. Vertical branches from each child upward to the horizontal bar at endY
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
