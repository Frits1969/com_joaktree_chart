/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : jtchart_changepartner.js
 * Location     : media/com_joaktree_chart/js/
 * Created      : 15-10-2026
 * Modified     : 19-10-2026
 *
 * Purpose      :
 * Handles interactions on inactive (stacked) partners.
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

document.addEventListener('click', function (e) {
    const inactivePartner = e.target.closest('.jt-inactive-partner');

    if (!inactivePartner) {
        return;
    }

    e.preventDefault();

    const partnerKey = inactivePartner.getAttribute('data-partner-key');
    const treeId = inactivePartner.getAttribute('data-tree-id');

    if (!partnerKey) {
        return;
    }

    const url = new URL(window.location.href);
    url.searchParams.set('selectedPartnerKey', partnerKey);

    if (treeId) {
        url.searchParams.set('treeId', treeId);
    }

    window.location.href = url.toString();
});
