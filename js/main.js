// ============================================================
//  main.js  –  Simple JavaScript helpers
//  (Most interactivity is inline PHP – this is just extra)
// ============================================================

// Auto-hide alert messages after 5 seconds
document.addEventListener('DOMContentLoaded', function () {

    var alerts = document.querySelectorAll('.alert');
    alerts.forEach(function (alert) {
        // Don't auto-hide success on apply/register pages (user needs to read)
        if (alert.classList.contains('alert-info')) return;
        setTimeout(function () {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity    = '0';
            setTimeout(function () { alert.style.display = 'none'; }, 500);
        }, 5000);
    });

    // Confirm before any delete/reject buttons (add class "confirm-btn")
    var confirmBtns = document.querySelectorAll('.confirm-btn');
    confirmBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            if (!confirm('Are you sure you want to do this?')) {
                e.preventDefault();
            }
        });
    });

});
