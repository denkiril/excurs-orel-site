const regBtn = document.querySelector('#reg_btn');
const closeBtn = document.querySelector('#close_btn');
const regForm = document.querySelector('#reg_form');
/* global isInViewport */

// $('#regBtn').click(function() { $(this).hide(); $("#reg_form").slideDown(); })
if (regBtn) {
  regBtn.addEventListener('click', function showRegForm() {
    this.style.display = 'none';
    regForm.style.display = 'block'; // slideDown()
  });
}

if (closeBtn) {
  closeBtn.addEventListener('click', () => {
    regForm.style.display = 'none'; // slideUp
    regBtn.style.display = 'inline';
  });
}
