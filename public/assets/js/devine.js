(function () {
  'use strict';
  const LS_KEY = 'devine_best';

  function getBest() {
    const v = parseInt(localStorage.getItem(LS_KEY), 10);
    return isNaN(v) ? null : v;
  }

  // Update best score if game just won this page load
  if (typeof DEVINE_CONFIG !== 'undefined' && DEVINE_CONFIG.won && DEVINE_CONFIG.attempts > 0) {
    const best = getBest();
    if (best === null || DEVINE_CONFIG.attempts < best) {
      localStorage.setItem(LS_KEY, DEVINE_CONFIG.attempts);
    }
  }

  // Display best score
  document.addEventListener('DOMContentLoaded', function () {
    const el   = document.getElementById('devineBest');
    const wrap = document.getElementById('devineScore');
    if (!el || !wrap) return;
    const best = getBest();
    if (best !== null) {
      el.textContent    = best;
      wrap.style.display = 'block';
    }
  });
})();
