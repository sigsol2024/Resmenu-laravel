@php
    $menuImportErrors = $errors->getBag('menuImport');
    $menuImportOpen = $menuImportErrors->any() || session('menu_import_open');
@endphp
@once
@push('head')
<link rel="stylesheet" href="{{ resmenu_public_asset('css/pages/manager-menu-import.css') }}?v={{ is_file(public_path('assets/css/pages/manager-menu-import.css')) ? filemtime(public_path('assets/css/pages/manager-menu-import.css')) : '1' }}">
@endpush
@endonce

<div class="mimp-modal{{ $menuImportOpen ? ' is-open' : '' }}" id="menuImportModal" role="dialog" aria-modal="true" aria-labelledby="menuImportModalTitle">
    <div class="mimp-modal-overlay" onclick="closeMenuImportModal()"></div>
    <div class="mimp-modal-content">
        <div class="mimp-modal-header">
            <h2 class="mimp-modal-title" id="menuImportModalTitle">Import your menu</h2>
            <button type="button" class="mimp-modal-close" onclick="closeMenuImportModal()" aria-label="Close">&times;</button>
        </div>
        <form method="POST" action="{{ route('manager.menu-import.upload') }}" enctype="multipart/form-data" id="menuImportForm">
            @csrf
            <div class="mimp-modal-body">
                @if($menuImportErrors->any())
                    <div class="mimp-errors" role="alert">
                        <ul>
                            @foreach($menuImportErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="mimp-intro">
                    Download the sample, replace the example rows with your menu, keep the header row, save as CSV, and upload.
                    Nothing is saved until you review and confirm.
                </p>

                <p class="mimp-sample-label">Sample CSV (5 columns)</p>
                @include('manager.menu-import.partials.sample-table')

                <div class="mimp-downloads">
                    <a href="{{ route('manager.menu-import.sample') }}" class="btn btn-primary btn-small">Download sample CSV</a>
                    <a href="{{ route('manager.menu-import.template') }}" class="mimp-link">Download blank template</a>
                </div>

                <label class="mimp-file-label" for="menuImportFile">Your menu file</label>
                <input type="file" name="file" id="menuImportFile" class="mimp-file-input" accept=".csv,text/csv">
                <p class="mimp-hint">
                    CSV, max {{ \App\Services\MenuImport\MenuImportCsvReader::maxFileLabel() }}, up to {{ number_format(\App\Services\MenuImport\MenuImportCsvReader::maxRows()) }} rows.
                    Excel: File, Save As, CSV UTF-8.
                </p>
            </div>
            <div class="mimp-modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeMenuImportModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="menuImportContinue" disabled>Continue</button>
            </div>
        </form>
    </div>
</div>

@once
@push('scripts')
<script>
    function openMenuImportModal() {
        document.getElementById('menuImportModal').classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeMenuImportModal() {
        document.getElementById('menuImportModal').classList.remove('is-open');
        document.body.style.overflow = '';
    }

    (function () {
        var input = document.getElementById('menuImportFile');
        var submit = document.getElementById('menuImportContinue');
        var form = document.getElementById('menuImportForm');
        input.addEventListener('change', function () {
            submit.disabled = !input.files || input.files.length === 0;
        });
        form.addEventListener('submit', function () {
            submit.disabled = true;
            submit.textContent = 'Reading file…';
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && document.getElementById('menuImportModal').classList.contains('is-open')) {
                closeMenuImportModal();
            }
        });
        if (document.getElementById('menuImportModal').classList.contains('is-open')) {
            document.body.style.overflow = 'hidden';
        }
    })();
</script>
@endpush
@endonce
