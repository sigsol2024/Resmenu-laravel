(function() {
    var cfg = window.TABLE_INVENTORY_CONFIG || {};
    var apiBase = cfg.apiBase || '/api/table-inventory';
    var csrf = cfg.csrfToken || '';

    var currentYear = new Date().getFullYear();
    var currentMonth = new Date().getMonth();
    var selectedDate = null;
    var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    function unwrap(data) {
        return (data && data.data !== undefined && data.data !== null) ? data.data : data;
    }

    function apiGet(params) {
        var q = new URLSearchParams(params).toString();
        return fetch(apiBase + (q ? '?' + q : ''), { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(function(r) { return r.json(); });
    }

    function apiPost(fields) {
        var fd = new FormData();
        Object.keys(fields).forEach(function(k) { fd.append(k, fields[k]); });
        return fetch(apiBase, {
            method: 'POST',
            body: fd,
            credentials: 'same-origin',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
        }).then(function(r) { return r.json(); });
    }

    function getMonthRange() {
        var start = new Date(currentYear, currentMonth, 1);
        var end = new Date(currentYear, currentMonth + 1, 0);
        return {
            start: start.getFullYear() + '-' + String(start.getMonth() + 1).padStart(2, '0') + '-' + String(start.getDate()).padStart(2, '0'),
            end: end.getFullYear() + '-' + String(end.getMonth() + 1).padStart(2, '0') + '-' + String(end.getDate()).padStart(2, '0'),
        };
    }

    function loadMonth() {
        var title = document.getElementById('inventory-month-title');
        if (title) title.textContent = monthNames[currentMonth] + ' ' + currentYear;
        apiGet({ action: 'month', year: currentYear, month: currentMonth + 1 })
            .then(function(data) {
                var payload = unwrap(data);
                renderCalendar((payload && payload.dates) ? payload.dates : {});
            })
            .catch(function() { renderCalendar({}); });
        setBulkDateRange();
    }

    function renderCalendar(dates) {
        var cal = document.getElementById('inventory-calendar');
        if (!cal) return;
        var first = new Date(currentYear, currentMonth, 1);
        var last = new Date(currentYear, currentMonth + 1, 0);
        var startPad = first.getDay();
        var daysInMonth = last.getDate();
        var today = new Date().toISOString().slice(0, 10);
        var html = '';
        for (var i = 0; i < 7; i++) html += '<div class="inv-header">' + ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'][i] + '</div>';
        for (var i = 0; i < startPad; i++) html += '<div class="inv-day other-month"></div>';
        for (var d = 1; d <= daysInMonth; d++) {
            var dateStr = currentYear + '-' + String(currentMonth + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
            var a = dates[dateStr] || { total: 10, available: 10, confirmed: 0, pending: 0, walkins: 0, cancelled: 0 };
            var css = 'inv-day';
            if (dateStr < today) css += ' past';
            var booked = (a.confirmed || 0) + (a.walkins || 0);
            if (a.available >= a.total / 2) css += ' avail-dominant';
            else if (booked > 0) css += ' booked-dominant';
            else if (a.pending > 0) css += ' pending-dominant';
            html += '<div class="' + css + '" data-date="' + dateStr + '"><span class="inv-date">' + d + '</span><span class="inv-summary">' + a.available + '/' + a.total + '</span></div>';
        }
        cal.innerHTML = html;
        cal.querySelectorAll('.inv-day:not(.past)').forEach(function(el) {
            el.addEventListener('click', function() {
                selectedDate = this.getAttribute('data-date');
                showDayPanel(selectedDate);
            });
        });
    }

    function showDayPanel(dateStr) {
        var panel = document.getElementById('inventory-day-panel');
        if (panel) panel.style.display = 'block';
        var d = new Date(dateStr + 'T12:00:00');
        var label = document.getElementById('day-panel-date');
        if (label) label.textContent = d.toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' });
        apiGet({ action: 'day_detail', date: dateStr })
            .then(function(data) {
                var payload = unwrap(data);
                if (!data.success || !payload) return;
                var a = payload.availability || {};
                var booked = (a.confirmed || 0) + (a.walkins || 0);
                var stats = document.querySelector('.inventory-day-stats');
                if (stats) {
                    stats.innerHTML =
                        '<div><div style="font-size:0.7rem;color:#6b7280;">Total</div><div style="font-size:1.25rem;font-weight:700;">' + a.total + '</div></div>' +
                        '<div><div style="font-size:0.7rem;color:#6b7280;">Booked</div><div style="font-size:1.25rem;font-weight:700;color:#ef4444;">' + booked + '</div></div>' +
                        '<div><div style="font-size:0.7rem;color:#6b7280;">Available</div><div style="font-size:1.25rem;font-weight:700;color:#10b981;">' + a.available + '</div></div>';
                }
                var totalInput = document.getElementById('day-total-tables');
                if (totalInput) totalInput.value = a.total;
                var list = payload.reservations || [];
                var listEl = document.getElementById('day-reservations-list');
                if (listEl) {
                    listEl.innerHTML = list.length === 0 ? 'No reservations.' : '<ul style="list-style:none;padding:0;">' + list.map(function(r) {
                        var time = r.reservation_time ? String(r.reservation_time).substring(0, 5) : '-';
                        var ref = r.reservation_number ? ('#' + r.reservation_number) : ('#' + r.id);
                        return '<li style="padding:6px 0;border-bottom:1px solid #f3f4f6;">' + ref + ' – ' + (r.is_walkin == 1 ? '[Walk-in] ' : '') + (r.guest_name || '-') + ' @ ' + time + ' (' + (r.status || '-') + ')</li>';
                    }).join('') + '</ul>';
                }
            });
    }

    function setBulkDateRange() {
        var r = getMonthRange();
        var s = document.getElementById('bulk-start-date');
        var e = document.getElementById('bulk-end-date');
        if (s) s.value = r.start;
        if (e) e.value = r.end;
    }

    function showBulkStatus(msg, isError) {
        var el = document.getElementById('bulk-status');
        if (!el) return;
        el.textContent = msg;
        el.style.display = 'inline';
        el.style.color = isError ? '#dc2626' : '#059669';
        clearTimeout(window._bulkStatusTimer);
        window._bulkStatusTimer = setTimeout(function() { el.style.display = 'none'; }, 5000);
    }

    function doBulkUpdate(startDate, endDate, total) {
        apiPost({ action: 'bulk_set_total', start_date: startDate, end_date: endDate, total_tables: total })
            .then(function(data) {
                if (data.success) {
                    var p = unwrap(data);
                    showBulkStatus('Updated ' + ((p && p.updated_count) || 0) + ' day(s).', false);
                    loadMonth();
                    if (selectedDate && selectedDate >= startDate && selectedDate <= endDate) showDayPanel(selectedDate);
                } else {
                    showBulkStatus(data.message || 'Failed to update', true);
                }
            })
            .catch(function() { showBulkStatus('Failed to update.', true); });
    }

    var prev = document.getElementById('inv-prev-month');
    var next = document.getElementById('inv-next-month');
    if (prev) prev.onclick = function() { if (currentMonth === 0) { currentMonth = 11; currentYear--; } else currentMonth--; loadMonth(); };
    if (next) next.onclick = function() { if (currentMonth === 11) { currentMonth = 0; currentYear++; } else currentMonth++; loadMonth(); };

    var bulkSave = document.getElementById('bulk-save-btn');
    if (bulkSave) bulkSave.onclick = function() {
        var start = document.getElementById('bulk-start-date').value;
        var end = document.getElementById('bulk-end-date').value;
        var total = parseInt(document.getElementById('bulk-total-tables').value, 10) || 10;
        if (!start || !end || start > end) { showBulkStatus('Invalid date range.', true); return; }
        doBulkUpdate(start, end, total);
    };
    var bulkFill = document.getElementById('bulk-fill-month-btn');
    if (bulkFill) bulkFill.onclick = function() {
        var r = getMonthRange();
        doBulkUpdate(r.start, r.end, parseInt(document.getElementById('bulk-total-tables').value, 10) || 10);
    };

    var daySave = document.getElementById('day-save-total');
    if (daySave) daySave.onclick = function() {
        if (!selectedDate) return;
        apiPost({ action: 'set_total', date: selectedDate, total_tables: parseInt(document.getElementById('day-total-tables').value, 10) || 10 })
            .then(function(data) {
                if (data.success) { loadMonth(); showDayPanel(selectedDate); }
                else alert(data.message || 'Save failed');
            });
    };

    var walkinBtn = document.getElementById('day-add-walkin');
    var walkinModal = document.getElementById('walkin-modal');
    if (walkinBtn && walkinModal) {
        walkinBtn.onclick = function() { walkinModal.style.display = 'flex'; };
        document.getElementById('walkin-modal-cancel').onclick = function() { walkinModal.style.display = 'none'; };
        document.getElementById('walkin-modal-confirm').onclick = function() {
            var name = document.getElementById('walkin-guest-name').value.trim() || 'Walk-in';
            walkinModal.style.display = 'none';
            apiPost({ action: 'add_walkin', date: selectedDate, guest_name: name })
                .then(function(data) {
                    if (data.success) { loadMonth(); showDayPanel(selectedDate); }
                    else alert(data.message || 'Failed');
                });
        };
    }

    loadMonth();
})();
