<script>
(function () {
    function bindRoleUi(roleSelect, panel, note) {
        if (!roleSelect || !panel || !note) return;
        function syncRoleUi() {
            var isSuper = roleSelect.value === 'super';
            panel.style.display = isSuper ? 'none' : '';
            note.style.display = isSuper ? '' : 'none';
        }
        roleSelect.addEventListener('change', syncRoleUi);
        syncRoleUi();
    }

    bindRoleUi(
        document.getElementById('role'),
        document.getElementById('permissions-panel'),
        document.getElementById('full-access-note')
    );
    bindRoleUi(
        document.getElementById('edit_role'),
        document.getElementById('edit_permissions-panel'),
        document.getElementById('edit_full-access-note')
    );
})();
</script>
