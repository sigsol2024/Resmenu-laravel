<style>
.password-input-wrapper { position: relative; display: flex; align-items: center; width: 100%; }
.password-input-wrapper input[type="password"],
.password-input-wrapper input[type="text"] { padding-right: 40px !important; width: 100%; }
.password-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center; color: #6b7280; z-index: 10; outline: none; }
.password-toggle:hover { color: #374151; }
.password-toggle svg { width: 18px; height: 18px; pointer-events: none; }
.password-toggle.hidden .eye-open { display: none; }
.password-toggle.hidden .eye-closed { display: block; }
.password-toggle .eye-closed { display: none; }
.password-toggle .eye-open { display: block; }
</style>
<script>
(function() {
  function initPasswordToggles() {
    document.querySelectorAll('input[type="password"]').forEach(function(input) {
      if (input.closest('.password-input-wrapper')) return;
      const wrapper = document.createElement('div');
      wrapper.className = 'password-input-wrapper';
      input.parentNode.insertBefore(wrapper, input);
      wrapper.appendChild(input);
      const toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'password-toggle';
      toggle.setAttribute('aria-label', 'Toggle password visibility');
      toggle.innerHTML = '<svg class="eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg><svg class="eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228L3.98 8.223m13.793 5.772L21 21m-2.227-2.227L17.022 15.78M15.78 17.022l-2.227-2.227m0 0a3 3 0 01-4.243-4.243M13.553 13.553a3 3 0 01-4.243-4.243" /></svg>';
      toggle.addEventListener('click', function() {
        if (input.type === 'password') { input.type = 'text'; toggle.classList.add('hidden'); }
        else { input.type = 'password'; toggle.classList.remove('hidden'); }
      });
      wrapper.appendChild(toggle);
    });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initPasswordToggles);
  else initPasswordToggles();
  setTimeout(initPasswordToggles, 100);
})();
</script>
