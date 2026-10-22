/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * Bestand      : jtchart_link.js
 * Locatie      : media/com_joaktree_chart/js/
 * Aangemaakt   : 15-10-2026
 * Gewijzigd    :
 *
 * Doel:
 * Afhandelen van standaard navigatie naar andere personen.
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

document.addEventListener('DOMContentLoaded', function () {
    // Luister naar klikken op alle persoons- en partnerlinks in de grafiek
    document.body.addEventListener('click', function (e) {
        const linkElement = e.target.closest('.jt-person-link');
        if (linkElement) {
            const url = linkElement.getAttribute('data-url');
            if (url) {
                e.preventDefault(); // Voorkom dat Joomla het in een container laadt

                // Dwing het volledige browservenster om naar de URL te navigeren
                window.top.location.href = url;
            }
        }
    });
});
