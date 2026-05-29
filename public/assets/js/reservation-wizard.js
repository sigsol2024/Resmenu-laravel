document.addEventListener('DOMContentLoaded', function() {
    var cfg = window.RESERVATION_CONFIG || {};
    var primaryColor = cfg.primaryColor || '#f20d0d';
    var baseUrl = cfg.baseUrl || '';
    var slug = cfg.slug || '';
    var partySize = parseInt(cfg.partySize, 10) || 1;
    var minDateStr = cfg.minDate || new Date().toISOString().slice(0, 10);
    var slotsUrl = cfg.slotsUrl || (baseUrl + '/api/reservations/slots');
    var availabilityUrl = cfg.availabilityUrl || (baseUrl + '/api/reservations/availability');

    var partyInput = document.getElementById('party-size-input');
    var partyDisplay = document.getElementById('party-display');
    var timeInput = document.getElementById('reservation-time-input');
    var occasionInput = document.getElementById('special-occasion-input');
    var dateInput = document.getElementById('reservation-date-input');
    if (!partyInput || !timeInput || !dateInput) {
        return;
    }

    var currentStep = 1;

    function unwrap(data) {
        if (data && data.data !== undefined && data.data !== null) {
            return data.data;
        }
        return data;
    }

    function updateParty() {
        partySize = Math.max(1, Math.min(10, partySize));
        partyInput.value = partySize;
        if (partyDisplay) {
            partyDisplay.textContent = partySize + ' Guest' + (partySize !== 1 ? 's' : '');
        }
    }

    function showStep(step) {
        currentStep = step;
        document.querySelectorAll('.res-step').forEach(function(el) { el.classList.add('hidden'); });
        var s = document.querySelector('.res-step[data-step="' + step + '"]');
        if (s) s.classList.remove('hidden');
        document.querySelectorAll('.res-step-indicator').forEach(function(el) {
            var n = parseInt(el.getAttribute('data-step'), 10);
            el.classList.remove('ring-4');
            if (n < step) {
                el.style.backgroundColor = primaryColor;
                el.style.color = 'white';
            } else if (n === step) {
                el.style.backgroundColor = primaryColor;
                el.style.color = 'white';
                el.classList.add('ring-4');
            } else {
                el.style.backgroundColor = '';
                el.style.color = '';
            }
        });
        if (step === 4) {
            buildReviewSummary();
        }
    }

    function buildReviewSummary() {
        var html = '';
        var dateVal = dateInput.value;
        var timeVal = timeInput.value;
        var name = (document.querySelector('input[name="guest_name"]') || {}).value || '';
        var email = (document.querySelector('input[name="guest_email"]') || {}).value || '';
        var phone = (document.querySelector('input[name="guest_phone"]') || {}).value || '';
        var occ = occasionInput ? occasionInput.value : '';
        var notes = (document.querySelector('textarea[name="notes"]') || {}).value || '';
        if (dateVal) html += '<p><strong>Date:</strong> ' + dateVal + '</p>';
        if (timeVal) html += '<p><strong>Time:</strong> ' + timeVal + '</p>';
        html += '<p><strong>Guests:</strong> ' + partySize + '</p>';
        html += '<p><strong>Name:</strong> ' + name + '</p>';
        html += '<p><strong>Email:</strong> ' + email + '</p>';
        html += '<p><strong>Phone:</strong> ' + phone + '</p>';
        if (occ) html += '<p><strong>Occasion:</strong> ' + occ + '</p>';
        if (notes) html += '<p><strong>Notes:</strong> ' + notes + '</p>';
        var el = document.getElementById('res-review-summary');
        if (el) el.innerHTML = html;
    }

    function loadTimeSlots(date) {
        var container = document.getElementById('time-slots-container');
        if (!container) return;
        container.innerHTML = '<p class="col-span-full text-center text-gray-500 py-4">Loading...</p>';
        fetch(slotsUrl + '?slug=' + encodeURIComponent(slug) + '&date=' + encodeURIComponent(date))
            .then(function(r) { return r.json(); })
            .then(function(data) {
                var payload = unwrap(data);
                if (!data.success || !payload || !payload.slots) {
                    container.innerHTML = '<p class="col-span-full text-center text-red-500 py-4">Failed to load slots.</p>';
                    return;
                }
                var html = '';
                payload.slots.forEach(function(slot) {
                    var cls = 'time-slot py-3 px-2 text-sm font-bold rounded-lg transition-all border ';
                    cls += slot.available ? 'border-gray-200 hover:border-primary text-gray-700' : 'opacity-50 cursor-not-allowed line-through border-gray-200 text-gray-500';
                    html += '<button type="button" data-time="' + slot.time + '" class="' + cls + '"' + (slot.available ? '' : ' disabled') + '>' + slot.time + '</button>';
                });
                container.innerHTML = html;
                timeInput.value = '';
                container.querySelectorAll('.time-slot').forEach(function(btn) {
                    if (btn.disabled) return;
                    btn.addEventListener('click', function() {
                        container.querySelectorAll('.time-slot').forEach(function(b) {
                            b.style.backgroundColor = '';
                            b.style.color = '';
                            b.style.borderColor = '';
                        });
                        btn.style.backgroundColor = primaryColor;
                        btn.style.color = 'white';
                        btn.style.borderColor = primaryColor;
                        timeInput.value = btn.getAttribute('data-time');
                    });
                });
            })
            .catch(function() {
                container.innerHTML = '<p class="col-span-full text-center text-red-500 py-4">Failed to load slots.</p>';
            });
    }

    var partyMinus = document.getElementById('party-minus');
    var partyPlus = document.getElementById('party-plus');
    if (partyMinus) partyMinus.addEventListener('click', function() { partySize--; updateParty(); });
    if (partyPlus) partyPlus.addEventListener('click', function() { partySize++; updateParty(); });

    var slotsContainer = document.getElementById('time-slots-container');
    if (slotsContainer) {
        slotsContainer.addEventListener('click', function(e) {
            var btn = e.target.closest('.time-slot');
            if (!btn || btn.disabled) return;
            document.querySelectorAll('#time-slots-container .time-slot').forEach(function(b) {
                b.style.backgroundColor = '';
                b.style.color = '';
                b.style.borderColor = '';
            });
            btn.style.backgroundColor = primaryColor;
            btn.style.color = 'white';
            btn.style.borderColor = primaryColor;
            timeInput.value = btn.getAttribute('data-time');
        });
    }

    var preSelected = timeInput.value;
    if (preSelected) {
        var sel = document.querySelector('#time-slots-container .time-slot[data-time="' + preSelected + '"]');
        if (sel && !sel.disabled) {
            sel.style.backgroundColor = primaryColor;
            sel.style.color = 'white';
            sel.style.borderColor = primaryColor;
        }
    }

    var calYear = new Date().getFullYear();
    var calMonth = new Date().getMonth();

    function getCalMonthRange() {
        var start = new Date(calYear, calMonth, 1);
        var end = new Date(calYear, calMonth + 1, 0);
        return {
            start: start.getFullYear() + '-' + String(start.getMonth() + 1).padStart(2, '0') + '-' + String(start.getDate()).padStart(2, '0'),
            end: end.getFullYear() + '-' + String(end.getMonth() + 1).padStart(2, '0') + '-' + String(end.getDate()).padStart(2, '0')
        };
    }

    function loadReservationCalendar() {
        var r = getCalMonthRange();
        var monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
        var monthEl = document.getElementById('res-cal-month');
        if (monthEl) monthEl.textContent = monthNames[calMonth] + ' ' + calYear;
        fetch(availabilityUrl + '?slug=' + encodeURIComponent(slug) + '&start=' + encodeURIComponent(r.start) + '&end=' + encodeURIComponent(r.end))
            .then(function(res) { return res.json(); })
            .then(function(data) {
                var payload = unwrap(data);
                var dates = (data.success && payload && payload.dates) ? payload.dates : {};
                renderResCalendar(dates);
            })
            .catch(function() { renderResCalendar({}); });
    }

    function renderResCalendar(dates) {
        var first = new Date(calYear, calMonth, 1);
        var last = new Date(calYear, calMonth + 1, 0);
        var startPad = first.getDay();
        var daysInMonth = last.getDate();
        var prevMonth = calMonth === 0 ? 11 : calMonth - 1;
        var prevYear = calMonth === 0 ? calYear - 1 : calYear;
        var prevLast = new Date(prevYear, prevMonth + 1, 0).getDate();
        var today = new Date().toISOString().slice(0, 10);
        var selectedVal = dateInput.value || '';

        var html = '';
        ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'].forEach(function(d) { html += '<div class="font-semibold text-gray-500 py-1">' + d + '</div>'; });
        for (var i = 0; i < startPad; i++) {
            var d = prevLast - startPad + i + 1;
            html += '<div class="py-2 text-gray-300">' + d + '</div>';
        }
        for (var d = 1; d <= daysInMonth; d++) {
            var dateStr = calYear + '-' + String(calMonth + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
            var info = dates[dateStr] || { available: 0, total: 10, status: 'full' };
            var status = info.status;
            if (dateStr < today) status = 'past';
            var cls = 'py-2 rounded cursor-pointer transition-colors ';
            var clickable = false;
            if (status === 'past') {
                cls += 'text-gray-300 cursor-default';
            } else if (status === 'full') {
                cls += 'text-gray-400 bg-gray-100 cursor-not-allowed';
            } else if (status === 'limited') {
                cls += 'bg-amber-100 text-amber-900 hover:bg-amber-200';
                clickable = true;
            } else {
                cls += 'bg-green-100 text-green-800 hover:bg-green-200';
                clickable = true;
            }
            if (dateStr === selectedVal) cls += ' ring-2 ring-offset-1 font-bold';
            var label = status === 'past' ? '' : (status === 'limited' ? info.available + ' left' : '');
            var style = (dateStr === selectedVal) ? ' box-shadow: 0 0 0 2px ' + primaryColor + ';' : '';
            html += '<div class="' + cls + '" data-date="' + dateStr + '" data-clickable="' + (clickable ? '1' : '0') + '" style="' + style + '">' + d + (label ? '<br><span class="text-[10px]">' + label + '</span>' : '') + '</div>';
        }
        var totalCells = startPad + daysInMonth;
        var remainder = totalCells % 7;
        var nextDays = remainder === 0 ? 0 : 7 - remainder;
        for (var i = 1; i <= nextDays; i++) {
            html += '<div class="py-2 text-gray-300">' + i + '</div>';
        }
        var calEl = document.getElementById('reservation-calendar');
        if (!calEl) return;
        calEl.innerHTML = html;

        document.querySelectorAll('#reservation-calendar [data-clickable="1"]').forEach(function(el) {
            el.addEventListener('click', function() {
                var dt = this.getAttribute('data-date');
                dateInput.value = dt;
                loadTimeSlots(dt);
                timeInput.value = '';
                document.querySelectorAll('#reservation-calendar [data-date]').forEach(function(c) {
                    c.classList.remove('ring-2','ring-offset-1','font-bold');
                    c.style.boxShadow = '';
                });
                this.classList.add('ring-2','ring-offset-1','font-bold');
                this.style.boxShadow = '0 0 0 2px ' + primaryColor;
                var disp = document.getElementById('res-date-display');
                var wrap = document.getElementById('reservation-calendar-wrap');
                if (disp) disp.textContent = new Date(dt + 'T12:00:00').toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
                if (wrap) wrap.classList.add('hidden');
            });
        });
    }

    var initDate = dateInput.value || minDateStr;
    if (initDate) {
        var p = initDate.split('-');
        if (p.length === 3) {
            calYear = parseInt(p[0], 10);
            calMonth = parseInt(p[1], 10) - 1;
        }
        loadTimeSlots(initDate);
    }
    loadReservationCalendar();

    var dateTrigger = document.getElementById('reservation-date-trigger');
    var dateDisplay = document.getElementById('res-date-display');
    var calendarWrap = document.getElementById('reservation-calendar-wrap');
    function updateDateDisplay() {
        var v = dateInput.value;
        if (dateDisplay && v) {
            dateDisplay.textContent = new Date(v + 'T12:00:00').toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
        }
    }
    updateDateDisplay();
    if (dateTrigger && calendarWrap) {
        dateTrigger.addEventListener('click', function() { calendarWrap.classList.toggle('hidden'); });
        dateTrigger.addEventListener('keydown', function(e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); calendarWrap.classList.toggle('hidden'); } });
    }
    var calPrev = document.getElementById('res-cal-prev');
    var calNext = document.getElementById('res-cal-next');
    if (calPrev) calPrev.addEventListener('click', function() {
        if (calMonth === 0) { calMonth = 11; calYear--; } else calMonth--;
        loadReservationCalendar();
    });
    if (calNext) calNext.addEventListener('click', function() {
        if (calMonth === 11) { calMonth = 0; calYear++; } else calMonth++;
        loadReservationCalendar();
    });

    document.querySelectorAll('.occasion-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.occasion-btn').forEach(function(b) {
                b.style.backgroundColor = '';
                b.style.color = '';
                b.classList.remove('border-primary', 'text-white');
                b.classList.add('border-gray-200', 'bg-gray-50', 'text-gray-600');
            });
            btn.style.backgroundColor = primaryColor;
            btn.style.color = 'white';
            btn.classList.add('border-primary', 'text-white');
            btn.classList.remove('border-gray-200', 'bg-gray-50', 'text-gray-600');
            if (occasionInput) occasionInput.value = btn.getAttribute('data-occasion') || '';
        });
    });

    function isValidEmailClient(val) {
        return /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test((val || '').trim());
    }
    function isValidPhoneClient(val) {
        var digits = (val || '').replace(/\D/g, '');
        return digits.length >= 10 && digits.length <= 15;
    }
    document.querySelectorAll('.res-next-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (currentStep === 1) {
                if (!dateInput.value || !timeInput.value) {
                    alert('Please select a date and time slot.');
                    return;
                }
            }
            if (currentStep === 2) {
                var emailEl = document.querySelector('input[name="guest_email"]');
                var phoneEl = document.querySelector('input[name="guest_phone"]');
                var ok = true;
                if (emailEl) {
                    if (!isValidEmailClient(emailEl.value)) {
                        emailEl.setCustomValidity('Please enter a valid email address (e.g. name@example.com)');
                        emailEl.reportValidity();
                        ok = false;
                    } else { emailEl.setCustomValidity(''); }
                }
                if (phoneEl && ok) {
                    if (!isValidPhoneClient(phoneEl.value)) {
                        phoneEl.setCustomValidity('Please enter a valid phone number (digits only, 10-15 characters)');
                        phoneEl.reportValidity();
                        ok = false;
                    } else { phoneEl.setCustomValidity(''); }
                }
                if (!ok) return;
            }
            if (currentStep < 4) showStep(currentStep + 1);
        });
    });
    document.querySelectorAll('.res-back-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (currentStep > 1) showStep(currentStep - 1);
        });
    });
    var phoneInput = document.querySelector('input[name="guest_phone"]');
    if (phoneInput) phoneInput.addEventListener('input', function() { this.value = this.value.replace(/[^\d+\s\-]/g, ''); });
    var formEl = document.getElementById('reservation-form');
    if (formEl) formEl.addEventListener('submit', function(e) {
        var emailEl = document.querySelector('input[name="guest_email"]');
        var phoneEl = document.querySelector('input[name="guest_phone"]');
        if (emailEl && !isValidEmailClient(emailEl.value)) { e.preventDefault(); emailEl.setCustomValidity('Please enter a valid email address'); emailEl.reportValidity(); return false; }
        if (phoneEl && !isValidPhoneClient(phoneEl.value)) { e.preventDefault(); phoneEl.setCustomValidity('Please enter a valid phone number (digits only)'); phoneEl.reportValidity(); return false; }
    });
    updateParty();
    showStep(1);
});
