(function () {
  'use strict';
  if (!COFFRE_CONFIG.isLoggedIn || COFFRE_CONFIG.alreadyPlayed) return;

  const chestsEl = document.getElementById('coffreChests');
  const result   = document.getElementById('coffreResult');
  const jetonEl  = document.getElementById('jetonCount');
  if (!chestsEl) return;

  let picked = false;

  chestsEl.addEventListener('click', async e => {
    if (picked) return;
    const chest = e.target.closest('.coffre-chest');
    if (!chest) return;

    picked = true;
    const idx = +chest.dataset.index;

    // Animate selection
    chestsEl.querySelectorAll('.coffre-chest').forEach(c => {
      if (+c.dataset.index !== idx) c.classList.add('coffre-chest-faded');
    });
    chest.classList.add('coffre-chest-selected');

    let data;
    try {
      const res = await fetch(window.location.href, {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    `action=play&chest=${idx}`,
      });
      if (!res.ok) throw new Error();
      data = await res.json();
    } catch {
      picked = false;
      chestsEl.querySelectorAll('.coffre-chest').forEach(c => {
        c.classList.remove('coffre-chest-faded', 'coffre-chest-selected');
      });
      return;
    }

    if (data.error) { showResult(0, data.message, true); return; }

    if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;

    // Reveal all chests after short delay
    setTimeout(() => revealAll(data.prizes, idx, data.gain), 400);
  });

  function revealAll(prizes, chosen, gain) {
    const chestEls = chestsEl.querySelectorAll('.coffre-chest');
    chestEls.forEach((c, i) => {
      setTimeout(() => {
        const closed = c.querySelector('.coffre-chest-closed');
        const open   = c.querySelector('.coffre-chest-open');
        const p      = prizes[i];
        open.textContent = p > 0 ? `+${p}💰` : '📭';
        open.style.display  = 'inline';
        closed.style.display = 'none';
        c.classList.add('coffre-chest-revealed');
        if (i === chosen && gain > 0) c.classList.add('coffre-chest-winner');
      }, i * 150);
    });

    setTimeout(() => showResult(gain), chestEls.length * 150 + 400);
  }

  function showResult(gain, msg, isError) {
    let html, klass;
    if (isError) {
      klass = 'lose'; html = `<span class="coffre-result-emoji">⚠️</span>${msg}`;
    } else if (gain >= 8) {
      klass = 'jackpot'; html = `<span class="coffre-result-emoji">💰</span><strong>Grand lot ! ${gain} jetons !</strong> Vous avez trouvé le coffre au trésor !`;
    } else if (gain > 0) {
      klass = 'win'; html = `<span class="coffre-result-emoji">🎁</span>Bonne pioche ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> dans ce coffre !`;
    } else {
      klass = 'lose'; html = `<span class="coffre-result-emoji">📭</span>Coffre vide… Essayez encore demain !`;
    }
    result.className      = `coffre-result ${klass}`;
    result.innerHTML      = html;
    result.style.display  = 'block';
  }
})();
