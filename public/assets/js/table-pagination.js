/**
 * Client-side pagination for dashboard tables.
 *
 * Pages every <table class="table"> at 10 rows, keeps List.js search/sort
 * working, and re-initialises tables that are swapped in by AJAX refreshes.
 */
(function () {
    'use strict';

    var PAGE_SIZE = 10;
    var HIDDEN_CLASS = 'table-page-hidden';
    var states = new WeakMap();

    function injectStyles() {
        if (document.getElementById('table-pagination-styles')) return;
        var style = document.createElement('style');
        style.id = 'table-pagination-styles';
        style.textContent =
            '.' + HIDDEN_CLASS + ' { display: none !important; }' +
            '.table-pagination__info { font-size: .75rem; }' +
            '.table-pagination .pagination { flex-wrap: wrap; }';
        document.head.appendChild(style);
    }

    function isEligible(table) {
        return table.matches('table.table') &&
            !table.hasAttribute('data-no-pagination') &&
            !table.closest('.modal');
    }

    function dataRows(table) {
        var rows = [];
        Array.prototype.forEach.call(table.querySelectorAll('tbody > tr'), function (row) {
            // Skip "no records" placeholder rows that span every column.
            if (row.children.length === 1 && row.children[0].hasAttribute('colspan')) return;
            rows.push(row);
        });
        return rows;
    }

    function ensurePager(table) {
        var host = table.closest('.table-responsive') || table;
        var sibling = host.nextElementSibling;
        if (sibling && sibling.classList.contains('table-pagination')) return sibling;

        var pager = document.createElement('div');
        pager.className = 'table-pagination d-flex flex-wrap align-items-center justify-content-between px-3 py-2 border-top';
        pager.innerHTML =
            '<small class="text-50 table-pagination__info"></small>' +
            '<ul class="pagination pagination-xsm m-0"></ul>';
        host.parentNode.insertBefore(pager, host.nextSibling);
        return pager;
    }

    function pageNumbers(current, total) {
        if (total <= 7) {
            var all = [];
            for (var i = 1; i <= total; i++) all.push(i);
            return all;
        }

        var pages = [1];
        var start = Math.max(2, current - 1);
        var end = Math.min(total - 1, current + 1);
        if (start > 2) pages.push('…');
        for (var p = start; p <= end; p++) pages.push(p);
        if (end < total - 1) pages.push('…');
        pages.push(total);
        return pages;
    }

    function renderControls(list, current, total, go) {
        var prevDisabled = current === 1;

        list.innerHTML =
            '<li class="page-item ' + (prevDisabled ? 'disabled' : '') + '">' +
            '<a class="page-link" href="#" data-page="' + (current - 1) + '" aria-label="Previous">' +
            '<span aria-hidden="true" class="material-icons">chevron_left</span>' +
            '</a></li>' +
            pageNumbers(current, total).map(function (page) {
                if (page === '…') {
                    return '<li class="page-item disabled"><span class="page-link">…</span></li>';
                }
                return '<li class="page-item ' + (page === current ? 'active' : '') + '">' +
                    '<a class="page-link" href="#" data-page="' + page + '">' + page + '</a></li>';
            }).join('') +
            '<li class="page-item ' + (current === total ? 'disabled' : '') + '">' +
            '<a class="page-link" href="#" data-page="' + (current + 1) + '" aria-label="Next">' +
            '<span aria-hidden="true" class="material-icons">chevron_right</span>' +
            '</a></li>';

        list.onclick = function (event) {
            var link = event.target.closest('a[data-page]');
            if (!link) return;
            event.preventDefault();
            var page = parseInt(link.getAttribute('data-page'), 10);
            if (!page || page < 1 || page > total || page === current) return;
            go(page);
        };
    }

    function render(table) {
        var state = states.get(table);
        if (!state) return;

        var rows = dataRows(table);

        if (rows.length <= PAGE_SIZE) {
            rows.forEach(function (row) { row.classList.remove(HIDDEN_CLASS); });
            if (state.pager) {
                state.pager.remove();
                state.pager = null;
            }
            return;
        }

        // Reset only our own hiding classes; List.js filters are inline styles.
        rows.forEach(function (row) { row.classList.remove(HIDDEN_CLASS); });

        var active = rows.filter(function (row) {
            return row.style.display !== 'none';
        });

        var totalPages = Math.max(1, Math.ceil(active.length / PAGE_SIZE));
        if (state.page > totalPages) state.page = totalPages;
        if (state.page < 1) state.page = 1;

        var start = (state.page - 1) * PAGE_SIZE;
        var end = start + PAGE_SIZE;

        active.forEach(function (row, index) {
            if (index < start || index >= end) row.classList.add(HIDDEN_CLASS);
        });

        var pager = state.pager || (state.pager = ensurePager(table));
        var info = pager.querySelector('.table-pagination__info');
        if (info) {
            info.textContent = 'Showing ' +
                (active.length ? start + 1 : 0) + '–' +
                Math.min(end, active.length) + ' of ' + active.length;
        }

        renderControls(pager.querySelector('.pagination'), state.page, totalPages, function (page) {
            state.page = page;
            render(table);
        });
    }

    function initTable(table) {
        if (!isEligible(table)) return;

        if (states.has(table)) {
            render(table);
            return;
        }

        var state = { page: 1, pager: null };
        states.set(table, state);

        render(table);

        // List.js fires "updated" after its own search/sort passes.
        var container = table.closest('[data-toggle="lists"]');
        if (container && container.List && typeof container.List.on === 'function') {
            container.List.on('updated', function () {
                state.page = 1;
                render(table);
            });
        }

        // Also cover plain inputs and AJAX row replacements inside the table.
        var search = container && container.querySelector('.search');
        if (search) {
            search.addEventListener('input', function () {
                state.page = 1;
                render(table);
            });
        }

        // Rows can be appended or the whole tbody swapped by AJAX/sort.
        if (window.MutationObserver) {
            new MutationObserver(function () {
                state.page = 1;
                render(table);
            }).observe(table, { childList: true, subtree: true });
        }
    }

    function initAll(root) {
        var scope = root || document;
        if (scope.matches && scope.matches('table.table')) initTable(scope);
        if (scope.querySelectorAll) {
            Array.prototype.forEach.call(scope.querySelectorAll('table.table'), initTable);
        }
    }

    // Lets page scripts (e.g. the archive month/year filters) recompute the
    // visible rows without having to know anything about this module's state.
    window.refreshTablePagination = function (root, resetPage) {
        var scope = root || document;
        var tables = [];
        if (scope.matches && scope.matches('table.table')) tables.push(scope);
        if (scope.querySelectorAll) {
            Array.prototype.forEach.call(scope.querySelectorAll('table.table'), function (table) {
                tables.push(table);
            });
        }
        if (resetPage) {
            tables.forEach(function (table) {
                var state = states.get(table);
                if (state) state.page = 1;
            });
        }
        initAll(scope);
    };

    function boot() {
        injectStyles();
        initAll(document);

        if (!window.MutationObserver) return;

        new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                Array.prototype.forEach.call(mutation.addedNodes, function (node) {
                    if (node.nodeType === 1) initAll(node);
                });
            });
        }).observe(document.body, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();
