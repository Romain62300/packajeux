(function () {
  'use strict';
  if (!SMILE_CONFIG.isLoggedIn || SMILE_CONFIG.alreadyPlayed) return;

  const grid     = document.getElementById('smileGrid');
  const playBtn  = document.getElementById('smilePlayBtn');
  const result   = document.getElementById('smileResult');
  const jetonEl  = document.getElementById('jetonCount');
  if (!grid || !playBtn) return;

  let revealed = false;

  playBtn.addEventListener('click', async () => {
    if (revealed) return;
    playBtn.disabled  = true;
    playBtn.innerHTML = '<span class="smile-btn-icon">⏳</span><span>Tirage en cours…</span>';

    let data;
    try {
      const res = await fetch(window.location.href, {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    'action=play',
      });
      if (!res.ok) throw new Error();
      data = await res.json();
    } catch {
      playBtn.disabled  = false;
      playBtn.innerHTML = '<span class="smile-btn-icon">😄</span><span>Révéler la carte !</span>';
      return;
    }

    if (data.error) {
      showResult(0, data.message, 'lose');
      return;
    }

    revealed = true;
    revealGrid(data.grid, () => {
      if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;
      showResult(data.gain);
    });
  });

  function revealGrid(symbols, onDone) {
    const cells = grid.querySelectorAll('.smile-cell');
    cells.forEach((cell, i) => {
      setTimeout(() => {
        const back = cell.querySelector('.smile-cell-back');
        back.textContent = symbols[i];
        cell.classList.add('smile-flipped');
        if (i === cells.length - 1) setTimeout(onDone, 400);
      }, i * 120);
    });
  }

  function showResult(gain, msg, cls) {
    let html, klass;
    if (cls === 'lose') {
      klass = 'lose'; html = `<span class="smile-result-emoji">⚠️</span>${msg || 'Erreur réseau.'}`;
    } else if (gain >= 5) {
      klass = 'jackpot'; html = `<span class="smile-result-emoji">🎉</span><strong>${gain} jetons</strong> remportés ! Exceptionnel !`;
    } else if (gain > 0) {
      klass = 'win'; html = `<span class="smile-result-emoji">🏆</span>Bravo ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> !`;
    } else {
      klass = 'lose'; html = `<span class="smile-result-emoji">😔</span>Aucune paire… Réessayez demain !`;
    }
    result.className      = `smile-result ${klass}`;
    result.innerHTML      = html;
    result.style.display  = 'block';
    playBtn.style.display = 'none';
  }
})();
