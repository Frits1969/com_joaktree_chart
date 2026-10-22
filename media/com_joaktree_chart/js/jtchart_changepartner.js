/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : jtchart_changepartner.js
 * Locatie      : media/com_joaktree_chart/js/
 * Aangemaakt   : 15-10-2026
 * Gewijzigd    : 19-10-2026
 *
 * Doel:
 * Doel         :
 * Afhandelen van interacties op inactieve (gestapelde) partners.
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
