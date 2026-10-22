/**
 * ============================================================================
 * Component    : com_joaktree_chart
 * File         : jtchart_link.js
 * Location     : media/com_joaktree_chart/js/
 * Created      : 15-10-2026
 * Modified     :
 *
 * Purpose:
 * Handles standard navigation to other persons.
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

document.addEventListener('DOMContentLoaded', function () {
    // Listen for clicks on all person and partner links in the chart
    document.body.addEventListener('click', function (e) {
        const linkElement = e.target.closest('.jt-person-link');
        if (linkElement) {
            const url = linkElement.getAttribute('data-url');
            if (url) {
                e.preventDefault(); // Prevent Joomla from loading it in a container

                // Force the full browser window to navigate to the URL
                window.top.location.href = url;
            }
        }
    });
});
