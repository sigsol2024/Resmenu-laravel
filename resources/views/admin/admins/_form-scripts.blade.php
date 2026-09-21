<script>
(function () {
    var roleSelect = document.getElementById('role');
    var panel = document.getElementById('permissions-panel');
    var note = document.getElementById('full-access-note');
    if (!roleSelect || !panel || !note) return;

    function syncRoleUi() {
        var isSuper = roleSelect.value === 'super';
        panel.style.display = isSuper ? 'none' : '';
        note.style.display = isSuper ? '' : 'none';
    }

    roleSelect.addEventListener('change', syncRoleUi);
    syncRoleUi();
})();
</script>
