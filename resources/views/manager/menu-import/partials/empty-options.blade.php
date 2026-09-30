{{-- Empty-menu choice: the page's existing manual create action, or import. --}}
<div class="mimp-empty-options">
    <div class="mimp-empty-card">
        <h3>Create menu manually</h3>
        <p>Add sections, categories and items one at a time.</p>
        <button type="button" class="btn btn-secondary btn-small" onclick="{{ $manualAction }}">{{ $manualLabel }}</button>
    </div>
    <div class="mimp-empty-card mimp-empty-card--primary">
        <h3>Import your menu</h3>
        <p>Upload a CSV with your whole menu. You can review and fix everything before it is saved.</p>
        <button type="button" class="btn btn-primary btn-small" onclick="openMenuImportModal()">Import menu</button>
    </div>
</div>
