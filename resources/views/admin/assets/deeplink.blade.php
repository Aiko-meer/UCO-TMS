{{--
    Deep links: a notification navigates to its dashboard with
    ?open=<modalKey>&type=<type>. This script opens that request's detail modal.

    If the dashboard does not render a modal with that id (video, and any page
    without a detail popup), it falls back to the standalone detail view so the
    user still sees the request they clicked.
--}}
<script>
(function () {
    function openFromQuery() {
        var params = new URLSearchParams(window.location.search);
        var key = params.get('open');
        if (!key) { return; }

        var modal = document.getElementById('viewModal-' + key);

        if (modal && window.jQuery) {
            window.jQuery(modal).modal('show');

            // Strip the params so a refresh does not reopen it.
            params.delete('open');
            params.delete('type');
            var qs = params.toString();
            window.history.replaceState({}, '', window.location.pathname + (qs ? '?' + qs : '') + window.location.hash);
            return;
        }

        // Page may render its rows with data-request-id: highlight in place.
        var row = document.querySelector('[data-request-id="' + key + '"]');
        if (row) {
            row.classList.add('table-active');
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return;
        }

        // Last resort: the standalone detail view.
        var type = params.get('type');
        if (type) {
            window.location.href = '/notifications/' + encodeURIComponent(type + ':' + key);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', openFromQuery);
    } else {
        openFromQuery();
    }
})();
</script>
