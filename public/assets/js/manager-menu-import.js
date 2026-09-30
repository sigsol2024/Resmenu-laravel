/**
 * Menu import preview. The browser only edits the flat draft rows (names and
 * values); every change is sent to the server, which re-runs the full analysis
 * and returns the tree, statuses and warnings. No matching rules live here.
 */
(function () {
    'use strict';

    var cfg = window.MENU_IMPORT;
    if (!cfg) {
        return;
    }

    var STATUS_LABELS = {
        'new': 'New',
        update: 'Will update',
        unchanged: 'No changes',
        duplicate: 'Duplicate',
        error: 'Needs fixing',
        conflict: 'Conflict',
        attention: 'Needs fixing',
        excluded: 'Excluded',
        existing: 'Existing, will be reused'
    };
    var NEW_CATEGORY = '__new_category__';
    var NEW_SECTION = '__new_section__';

    var state = {
        analysis: null,
        rows: {},
        order: [],
        timer: null,
        inflight: false,
        seq: 0,
        editVersion: 0,
        busy: false
    };

    function $(id) {
        return document.getElementById(id);
    }

    function h(tag, attrs, children) {
        var el = document.createElement(tag);
        Object.keys(attrs || {}).forEach(function (key) {
            var value = attrs[key];
            if (value === null || value === undefined || value === false) {
                return;
            }
            if (key === 'class') {
                el.className = value;
            } else if (key === 'text') {
                el.textContent = value;
            } else if (key === 'value') {
                el.value = value;
            } else if (key.indexOf('on') === 0) {
                el.addEventListener(key.slice(2), value);
            } else {
                el.setAttribute(key, value === true ? '' : value);
            }
        });
        (children || []).forEach(function (child) {
            if (child === null || child === undefined || child === false) {
                return;
            }
            el.appendChild(typeof child === 'string' ? document.createTextNode(child) : child);
        });
        return el;
    }

    function norm(value) {
        return String(value || '').trim().replace(/\s+/g, ' ').toLowerCase();
    }

    function plural(count, one, many) {
        return count + ' ' + (count === 1 ? one : many);
    }

    function money(value) {
        var n = Number(value);
        return isNaN(n) ? String(value) : n.toLocaleString('en-NG', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    // ---------------------------------------------------------------- server

    function post(url, body) {
        return fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': cfg.csrf
            },
            body: JSON.stringify(body)
        }).then(function (res) {
            return res.json().then(
                function (data) { return { status: res.status, data: data }; },
                function () { return { status: res.status, data: null }; }
            );
        }, function () {
            return { status: 0, data: null };
        });
    }

    function payloadRows() {
        return state.order.map(function (id) {
            return state.rows[id];
        });
    }

    function markEdited(immediate) {
        state.editVersion++;
        schedule(immediate ? 0 : 700);
    }

    function schedule(delay) {
        clearTimeout(state.timer);
        state.timer = setTimeout(runAnalyze, delay);
        updateStatus();
    }

    function runAnalyze() {
        clearTimeout(state.timer);
        state.timer = null;
        var version = state.editVersion;
        var seq = ++state.seq;
        state.inflight = true;
        updateStatus();

        return post(cfg.urls.analyze, { rows: payloadRows() }).then(function (res) {
            if (seq !== state.seq) {
                return null;
            }
            state.inflight = false;
            if (res.status === 200 && res.data && res.data.revision) {
                if (version !== state.editVersion) {
                    schedule(250);
                    return null;
                }
                showBanner(null);
                setAnalysis(res.data);
                return res.data;
            }
            handleFailure(res, true);
            updateStatus();
            return null;
        });
    }

    function isPending() {
        return state.timer !== null || state.inflight;
    }

    function handleFailure(res, fromAnalyze) {
        var data = res.data || {};
        if (res.status === 410) {
            window.alert(data.message || 'This import has expired. Please upload your file again.');
            window.location.href = data.redirect || cfg.urls.sections;
            return;
        }
        if (res.status === 419 || (res.status === 200 && !res.data)) {
            showBanner('Your session has expired. Reload this page to continue; your changes up to the last check are kept.');
            return;
        }
        if (res.status === 429) {
            showBanner('Too many changes in a short time. Waiting a few seconds before checking again…', 'warning');
            if (fromAnalyze) {
                schedule(5000);
            }
            return;
        }
        if (res.status === 0) {
            showBanner('Could not reach the server. Check your connection; your changes will be checked again when you edit.');
            return;
        }
        showBanner(data.message || 'Something went wrong. Please try again.');
    }

    function showBanner(message, level) {
        var box = $('mimpBanner');
        box.textContent = '';
        if (message) {
            box.appendChild(h('div', { 'class': 'mimp-alert mimp-alert--' + (level || 'error'), role: 'alert', text: message }));
        }
    }

    // ---------------------------------------------------------------- state

    function setAnalysis(analysis) {
        state.analysis = analysis;
        state.order = analysis.row_order.slice();
        state.rows = {};
        state.order.forEach(function (id) {
            var r = analysis.rows[id];
            state.rows[id] = {
                id: r.id,
                row_number: r.row_number,
                section: r.section,
                category: r.category,
                name: r.name,
                description: r.description,
                price: r.price,
                excluded: !!r.excluded,
                parse_error: r.parse_error
            };
        });
        render();
    }

    function editField(id, field, value) {
        var row = state.rows[id];
        row[field] = value;
        row.parse_error = null;
        markEdited(false);
    }

    function setRows(ids, changes) {
        ids.forEach(function (id) {
            Object.keys(changes).forEach(function (field) {
                state.rows[id][field] = changes[field];
            });
        });
        markEdited(true);
    }

    function askName(label) {
        var value = window.prompt(label);
        return value && value.trim() ? value.trim() : null;
    }

    // ---------------------------------------------------------------- options

    function knownSections() {
        var seen = {};
        var list = [];
        function add(name) {
            var key = norm(name);
            if (key && !seen[key]) {
                seen[key] = true;
                list.push(name);
            }
        }
        state.analysis.tree.forEach(function (s) { add(s.name); });
        state.analysis.existing_categories.forEach(function (c) { add(c.section); });
        return list;
    }

    function knownCategories() {
        var groups = {};
        var order = [];
        function add(section, category) {
            var sKey = norm(section);
            if (!groups[sKey]) {
                groups[sKey] = { name: section, categories: [], seen: {} };
                order.push(sKey);
            }
            var cKey = norm(category);
            if (!groups[sKey].seen[cKey]) {
                groups[sKey].seen[cKey] = true;
                groups[sKey].categories.push(category);
            }
        }
        state.analysis.tree.forEach(function (s) {
            s.categories.forEach(function (c) { add(s.name, c.name); });
        });
        state.analysis.existing_categories.forEach(function (c) { add(c.section, c.category); });
        return order.map(function (k) { return groups[k]; });
    }

    // ---------------------------------------------------------------- render

    function render() {
        var focus = captureFocus();
        renderSummary();
        renderGlobalErrors();
        renderAttention();
        renderTree();
        renderExcluded();
        renderWarnings();
        updateStatus();
        restoreFocus(focus);
    }

    function captureFocus() {
        var el = document.activeElement;
        if (!el || !el.getAttribute || !el.getAttribute('data-focus-key')) {
            return null;
        }
        var focus = { key: el.getAttribute('data-focus-key') };
        try {
            focus.start = el.selectionStart;
            focus.end = el.selectionEnd;
        } catch (e) { /* not a text field */ }
        return focus;
    }

    function restoreFocus(focus) {
        if (!focus) {
            return;
        }
        var el = document.querySelector('[data-focus-key="' + focus.key.replace(/["\\]/g, '\\$&') + '"]');
        if (!el) {
            return;
        }
        el.focus();
        if (typeof focus.start === 'number') {
            try {
                el.setSelectionRange(focus.start, focus.end);
            } catch (e) { /* not a text field */ }
        }
    }

    function renderSummary() {
        var s = state.analysis.summary;
        var box = $('mimpSummary');
        box.textContent = '';
        function stat(label, lines, danger) {
            var value = h('div', { 'class': 'mimp-stat-value' });
            lines.forEach(function (line, i) {
                if (i) {
                    value.appendChild(h('br'));
                }
                value.appendChild(document.createTextNode(line));
            });
            box.appendChild(h('div', { 'class': 'mimp-stat' + (danger ? ' mimp-stat--danger' : '') }, [
                h('div', { 'class': 'mimp-stat-label', text: label }), value
            ]));
        }
        stat('Sections', [s.sections['new'] + ' new', s.sections.existing + ' existing']);
        stat('Categories', [s.categories['new'] + ' new', s.categories.existing + ' existing']);
        stat('Menu items', [s.items['new'] + ' new', s.items.update + ' to update', s.items.unchanged + ' unchanged']);
        stat('Skipped', [plural(s.items.duplicate, 'duplicate', 'duplicates'), s.items.excluded + ' excluded']);
        stat('Problems', [state.analysis.blocking === 0 ? 'None' : plural(state.analysis.blocking, 'problem to fix', 'problems to fix')], state.analysis.blocking > 0);
    }

    function renderGlobalErrors() {
        var box = $('mimpErrors');
        box.textContent = '';
        if (!state.analysis.errors.length) {
            return;
        }
        box.appendChild(h('div', { 'class': 'mimp-panel mimp-panel--error', role: 'alert' }, [
            h('h2', { 'class': 'mimp-panel-title', text: 'This import cannot be completed yet' }),
            h('ul', {}, state.analysis.errors.map(function (text) { return h('li', { text: text }); }))
        ]));
    }

    function fieldInput(row, analyzed, field, type) {
        var errors = analyzed.errors || {};
        var attrs = {
            'class': errors[field] ? 'has-error' : '',
            value: row[field] || '',
            'data-focus-key': row.id + ':' + field,
            'aria-label': field,
            oninput: function (e) { editField(row.id, field, e.target.value); }
        };
        var input;
        if (type === 'textarea') {
            if (analyzed.existing && analyzed.existing.description && !row.description) {
                attrs.placeholder = 'Keeps current: ' + analyzed.existing.description;
            }
            input = h('textarea', attrs);
            input.value = row[field] || '';
        } else {
            attrs.type = 'text';
            if (field === 'price') {
                attrs.inputmode = 'decimal';
            }
            input = h('input', attrs);
        }
        return h('div', {}, [input, errors[field] ? h('div', { 'class': 'mimp-field-error', text: errors[field] }) : null]);
    }

    function statusCell(analyzed) {
        var cell = h('td', { 'class': 'mimp-col-status' }, [
            h('span', { 'class': 'mimp-badge mimp-badge--' + analyzed.status, text: STATUS_LABELS[analyzed.status] || analyzed.status })
        ]);
        var changes = analyzed.changes || {};
        if (changes.price) {
            cell.appendChild(h('div', { 'class': 'mimp-change', text: 'Price: ' + money(changes.price.from) + ' → ' + money(changes.price.to) }));
        }
        if (changes.description) {
            cell.appendChild(h('div', {
                'class': 'mimp-change',
                title: 'Current: ' + (changes.description.from || '(empty)'),
                text: 'Description will be replaced'
            }));
        }
        var list = h('ul', { 'class': 'mimp-row-messages' });
        if (analyzed.errors && analyzed.errors.row) {
            list.appendChild(h('li', { 'class': 'is-error', text: analyzed.errors.row }));
        }
        (analyzed.messages || []).forEach(function (m) {
            list.appendChild(h('li', { 'class': 'mimp-msg--' + m.level, text: m.text }));
        });
        if (list.childNodes.length) {
            cell.appendChild(list);
        }
        return cell;
    }

    function moveSelect(row) {
        var select = h('select', {
            'aria-label': 'Move to category',
            onchange: function (e) { moveRow(row, e.target.value); }
        }, [h('option', { value: '', text: 'Move to category…' })]);
        knownCategories().forEach(function (group) {
            var optgroup = h('optgroup', { label: group.name });
            group.categories.forEach(function (category) {
                optgroup.appendChild(h('option', { value: JSON.stringify([group.name, category]), text: category }));
            });
            select.appendChild(optgroup);
        });
        select.appendChild(h('option', { value: NEW_CATEGORY, text: 'New category in this section…' }));
        select.appendChild(h('option', { value: NEW_SECTION, text: 'New section and category…' }));
        return select;
    }

    function moveRow(row, value) {
        if (!value) {
            return;
        }
        if (value === NEW_CATEGORY) {
            var category = askName('Name of the new category:');
            if (!category) {
                return render();
            }
            return setRows([row.id], { category: category });
        }
        if (value === NEW_SECTION) {
            var section = askName('Name of the new section:');
            var newCategory = section ? askName('Name of the category in ' + section + ':') : null;
            if (!section || !newCategory) {
                return render();
            }
            return setRows([row.id], { section: section, category: newCategory });
        }
        var pair = JSON.parse(value);
        setRows([row.id], { section: pair[0], category: pair[1] });
    }

    function itemRow(id, withParents) {
        var row = state.rows[id];
        var analyzed = state.analysis.rows[id];
        var muted = analyzed.status === 'duplicate' || analyzed.status === 'unchanged';
        var cells = [h('td', { 'class': 'mimp-col-row', text: String(row.row_number) })];
        if (withParents) {
            cells.push(h('td', {}, [fieldInput(row, analyzed, 'section')]));
            cells.push(h('td', {}, [fieldInput(row, analyzed, 'category')]));
        }
        cells.push(h('td', {}, [fieldInput(row, analyzed, 'name')]));
        cells.push(h('td', {}, [fieldInput(row, analyzed, 'description', 'textarea')]));
        cells.push(h('td', { 'class': 'mimp-col-price' }, [fieldInput(row, analyzed, 'price')]));
        cells.push(statusCell(analyzed));
        cells.push(h('td', { 'class': 'mimp-col-actions' }, [h('div', { 'class': 'mimp-actions' }, [
            withParents ? null : moveSelect(row),
            h('button', {
                type: 'button',
                'class': 'mimp-text-btn mimp-text-btn--danger',
                text: 'Exclude row',
                onclick: function () { setRows([id], { excluded: true }); }
            })
        ])]));
        return h('tr', { 'class': analyzed.errors && Object.keys(analyzed.errors).length ? 'is-error' : (muted ? 'is-muted' : '') }, cells);
    }

    function itemTable(ids, withParents) {
        var heads = ['Row'];
        if (withParents) {
            heads.push('Section', 'Category');
        }
        heads.push('Menu item', 'Description', 'Price', 'Status', '');
        return h('div', { 'class': 'mimp-table-wrap' }, [h('table', { 'class': 'mimp-table' }, [
            h('thead', {}, [h('tr', {}, heads.map(function (label) { return h('th', { text: label }); }))]),
            h('tbody', {}, ids.map(function (id) { return itemRow(id, withParents); }))
        ])]);
    }

    function nodeMessages(messages) {
        if (!messages.length) {
            return null;
        }
        return h('ul', { 'class': 'mimp-node-messages' }, messages.map(function (m) {
            return h('li', { 'class': 'mimp-msg--' + m.level, text: m.text });
        }));
    }

    function renameInput(label, node, ids, field) {
        return h('input', {
            type: 'text',
            'class': 'mimp-name-input',
            value: node.name,
            'aria-label': label + ' name',
            'data-focus-key': field + ':' + node.key,
            onchange: function (e) {
                var value = e.target.value.trim();
                if (!value) {
                    e.target.value = node.name;
                    return;
                }
                var changes = {};
                changes[field] = value;
                setRows(ids, changes);
            }
        });
    }

    function sectionSelect(category) {
        var select = h('select', {
            'aria-label': 'Move category to section',
            onchange: function (e) {
                var value = e.target.value;
                if (!value) {
                    return;
                }
                var section = value === NEW_SECTION ? askName('Name of the new section:') : value;
                if (!section) {
                    return render();
                }
                setRows(category.row_ids, { section: section });
            }
        }, [h('option', { value: '', text: 'Move to section…' })]);
        knownSections().forEach(function (name) {
            select.appendChild(h('option', { value: name, text: name }));
        });
        select.appendChild(h('option', { value: NEW_SECTION, text: 'New section…' }));
        return select;
    }

    function renderTree() {
        var box = $('mimpTree');
        box.textContent = '';
        state.analysis.tree.forEach(function (section) {
            var sectionIds = [];
            section.categories.forEach(function (c) { sectionIds = sectionIds.concat(c.row_ids); });

            var card = h('div', { 'class': 'mimp-section' }, [
                h('div', { 'class': 'mimp-section-head' }, [
                    h('span', { 'class': 'mimp-level', text: 'Section' }),
                    renameInput('Section', section, sectionIds, 'section'),
                    h('span', { 'class': 'mimp-badge mimp-badge--' + section.status, text: STATUS_LABELS[section.status] }),
                    nodeMessages(section.messages)
                ])
            ]);

            section.categories.forEach(function (category) {
                card.appendChild(h('div', { 'class': 'mimp-category' }, [
                    h('div', { 'class': 'mimp-category-head' }, [
                        h('span', { 'class': 'mimp-level', text: 'Category' }),
                        renameInput('Category', category, category.row_ids, 'category'),
                        h('span', { 'class': 'mimp-badge mimp-badge--' + category.status, text: STATUS_LABELS[category.status] }),
                        sectionSelect(category),
                        nodeMessages(category.messages)
                    ]),
                    itemTable(category.row_ids, false)
                ]));
            });
            box.appendChild(card);
        });
    }

    function renderAttention() {
        var box = $('mimpAttention');
        box.textContent = '';
        var ids = state.analysis.attention;
        if (!ids.length) {
            return;
        }
        box.appendChild(h('div', { 'class': 'mimp-panel mimp-panel--error' }, [
            h('h2', { 'class': 'mimp-panel-title', text: 'Rows needing attention (' + ids.length + ')' }),
            h('p', { 'class': 'mimp-intro', text: 'These rows are missing a section or category, so they cannot be placed in your menu yet. Fill in the missing values or exclude the row.' }),
            itemTable(ids, true)
        ]));
    }

    function renderExcluded() {
        var box = $('mimpExcluded');
        box.textContent = '';
        var ids = state.analysis.excluded;
        if (!ids.length) {
            return;
        }
        box.appendChild(h('div', { 'class': 'mimp-panel' }, [
            h('h2', { 'class': 'mimp-panel-title', text: 'Excluded rows (' + ids.length + ')' }),
            h('ul', {}, ids.map(function (id) {
                var row = state.rows[id];
                return h('li', {}, [
                    'Row ' + row.row_number + ': ' + (row.name || '(no name)') + ' (' + (row.section || '?') + ' / ' + (row.category || '?') + ') ',
                    h('button', {
                        type: 'button',
                        'class': 'mimp-text-btn',
                        text: 'Restore',
                        onclick: function () { setRows([id], { excluded: false }); }
                    })
                ]);
            }))
        ]));
    }

    function sortedWarnings() {
        return state.analysis.warnings.slice().sort(function (a, b) {
            return (a.level === 'warning' ? 0 : 1) - (b.level === 'warning' ? 0 : 1);
        });
    }

    function renderWarnings() {
        var box = $('mimpWarnings');
        box.textContent = '';
        var warnings = sortedWarnings();
        if (!warnings.length) {
            return;
        }
        box.appendChild(h('details', { 'class': 'mimp-panel', open: warnings.length <= 12 }, [
            h('summary', { 'class': 'mimp-panel-title', text: 'Warnings and notes (' + warnings.length + ')' }),
            h('ul', {}, warnings.map(function (m) { return h('li', { 'class': 'mimp-msg--' + m.level, text: m.text }); }))
        ]));
    }

    function updateStatus() {
        var a = state.analysis;
        var status = $('mimpStatus');
        var button = $('mimpVerify');
        if (!a) {
            return;
        }
        var text;
        var cls = '';
        if (state.busy) {
            text = 'Importing your menu…';
        } else if (isPending()) {
            text = 'Checking your changes…';
        } else if (a.blocking > 0) {
            text = 'Fix ' + plural(a.blocking, 'problem', 'problems') + ' before importing.';
            cls = 'is-error';
        } else if (!a.has_changes) {
            text = 'Nothing to import: every item already exists with the same details.';
        } else {
            text = 'Ready to import.';
            cls = 'is-ready';
        }
        status.textContent = text;
        status.className = 'mimp-status' + (cls ? ' ' + cls : '');
        button.disabled = state.busy || isPending() || !a.can_import;
    }

    // ---------------------------------------------------------------- confirm

    function openConfirm(message) {
        var a = state.analysis;
        var s = a.summary;
        var msgBox = $('mimpConfirmMessage');
        msgBox.textContent = '';
        if (message) {
            msgBox.appendChild(h('div', { 'class': 'mimp-alert mimp-alert--warning', text: message }));
        }

        var list = $('mimpConfirmSummary');
        list.textContent = '';
        [
            'Sections: ' + s.sections['new'] + ' new, ' + s.sections.existing + ' reused',
            'Categories: ' + s.categories['new'] + ' new, ' + s.categories.existing + ' reused',
            'Menu items: ' + s.items['new'] + ' new, ' + s.items.update + ' updated, ' + s.items.unchanged + ' unchanged',
            'Skipped: ' + plural(s.items.duplicate, 'duplicate row', 'duplicate rows') + ', ' + s.items.excluded + ' excluded'
        ].forEach(function (line) { list.appendChild(h('li', { text: line })); });

        var warnBox = $('mimpConfirmWarnings');
        warnBox.textContent = '';
        var warnings = sortedWarnings();
        if (warnings.length) {
            warnBox.appendChild(h('p', { 'class': 'mimp-sample-label', text: 'Please review (' + warnings.length + ')' }));
            warnBox.appendChild(h('ul', { 'class': 'mimp-confirm-warnings' }, warnings.map(function (m) {
                return h('li', { 'class': 'mimp-msg--' + m.level, text: m.text });
            })));
        }

        $('mimpConfirmButton').disabled = false;
        $('mimpConfirmModal').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeConfirm() {
        $('mimpConfirmModal').classList.remove('is-open');
        document.body.style.overflow = '';
    }

    function verify() {
        $('mimpVerify').disabled = true;
        runAnalyze().then(function (analysis) {
            if (!analysis) {
                return;
            }
            if (analysis.can_import) {
                openConfirm(null);
            } else {
                var target = $('mimpErrors').firstChild || $('mimpAttention').firstChild || document.querySelector('.mimp-table tr.is-error');
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    }

    function confirmImport() {
        if (state.busy) {
            return;
        }
        state.busy = true;
        $('mimpConfirmButton').disabled = true;
        $('mimpConfirmButton').textContent = 'Importing…';
        updateStatus();

        post(cfg.urls['import'], { revision: state.analysis.revision }).then(function (res) {
            var data = res.data || {};
            if (res.status === 200 && data.redirect) {
                window.location.href = data.redirect;
                return;
            }
            state.busy = false;
            $('mimpConfirmButton').textContent = 'Confirm import';
            if (data.analysis) {
                setAnalysis(data.analysis);
            }
            if (res.status === 409 && data.analysis && data.analysis.can_import) {
                openConfirm(data.message);
                return;
            }
            closeConfirm();
            handleFailure(res, false);
            updateStatus();
        });
    }

    // ---------------------------------------------------------------- init

    $('mimpVerify').addEventListener('click', verify);
    $('mimpConfirmButton').addEventListener('click', confirmImport);
    Array.prototype.forEach.call(document.querySelectorAll('[data-close-confirm]'), function (el) {
        el.addEventListener('click', function () {
            if (!state.busy) {
                closeConfirm();
            }
        });
    });
    window.addEventListener('beforeunload', function (e) {
        if (isPending() && !state.busy) {
            e.preventDefault();
            e.returnValue = '';
        }
    });

    setAnalysis(cfg.analysis);
})();
