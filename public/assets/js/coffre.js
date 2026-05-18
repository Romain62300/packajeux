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

    // Fade out others, shake chosen chest
    chestsEl.querySelectorAll('.coffre-chest').forEach(c => {
      if (+c.dataset.index !== idx) c.classList.add('coffre-chest-faded');
    });
    chest.classList.add('coffre-chest-selected', 'coffre-chest-shake');
    setTimeout(() => chest.classList.remove('coffre-chest-shake'), 500);

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
        c.classList.remove('coffre-chest-faded', 'coffre-chest-selected', 'coffre-chest-shake');
      });
      return;
    }

    if (data.error) { showResult(0, data.message, true); return; }

    if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;

    // Opening bounce on chosen chest before full reveal
    const img = chest.querySelector('.coffre-chest-img');
    if (img) img.classList.add('coffre-chest-opening');

    setTimeout(() => revealAll(data.prizes, idx, data.gain), 420);
  });

  function revealAll(prizes, chosen, gain) {
    const chestEls = chestsEl.querySelectorAll('.coffre-chest');
    chestEls.forEach((c, i) => {
      setTimeout(() => {
        const closed = c.querySelector('.coffre-chest-closed');
        const open   = c.querySelector('.coffre-chest-open');
        const p      = prizes[i];
        open.textContent    = p > 0 ? `+${p}💰` : '📭';
        open.style.display  = 'inline';
        closed.style.display = 'none';
        c.classList.add('coffre-chest-revealed');
        if (i === chosen && gain > 0) {
          c.classList.add('coffre-chest-winner');
          setTimeout(() => spawnParticles(c), 200);
        }
      }, i * 160);
    });

    setTimeout(() => showResult(gain), chestEls.length * 160 + 420);
  }

  function spawnParticles(chestEl) {
    const EMOJIS = ['✨', '💰', '⭐', '🌟', '💫'];
    for (let i = 0; i < 12; i++) {
      const p     = document.createElement('span');
      p.className = 'coffre-particle';
      p.textContent = EMOJIS[i % EMOJIS.length];
      const angle = (i / 12) * 2 * Math.PI;
      const dist  = 55 + Math.random() * 40;
      p.style.left = '50%';
      p.style.top  = '40%';
      p.style.setProperty('--tx', `${Math.round(Math.cos(angle) * dist)}px`);
      p.style.setProperty('--ty', `${Math.round(Math.sin(angle) * dist)}px`);
      p.style.animationDelay = `${Math.round(Math.random() * 80)}ms`;
      chestEl.appendChild(p);
      setTimeout(() => p.remove(), 1100);
    }
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
