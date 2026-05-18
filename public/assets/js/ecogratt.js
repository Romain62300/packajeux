(function () {
  'use strict';
  if (!ECO_CONFIG.isLoggedIn || ECO_CONFIG.alreadyPlayed) return;

  const grid    = document.getElementById('ecoGrid');
  const playBtn = document.getElementById('ecoPlayBtn');
  const result  = document.getElementById('ecoResult');
  const jetonEl = document.getElementById('jetonCount');
  if (!grid || !playBtn) return;

  playBtn.addEventListener('click', async () => {
    playBtn.disabled  = true;
    playBtn.innerHTML = '<span class="eco-btn-icon">⏳</span><span>Tirage…</span>';

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
      playBtn.innerHTML = '<span class="eco-btn-icon">🌿</span><span>Révéler les symboles !</span>';
      return;
    }

    if (data.error) { showResult(0, data.message, true); return; }

    // Symbole gagnant : le plus fréquent
    const counts = {};
    data.grid.forEach(s => counts[s] = (counts[s] || 0) + 1);
    const maxCount  = Math.max(...Object.values(counts));
    const winSymbol = data.gain > 0 ? Object.keys(counts).find(k => counts[k] === maxCount) : null;

    revealGrid(data.grid, winSymbol, () => {
      if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;
      showResult(data.gain);
    });
  });

  function revealGrid(symbols, winSym, onDone) {
    const cells = grid.querySelectorAll('.eco-cell');
    cells.forEach((cell, i) => {
      setTimeout(() => {
        const back = cell.querySelector('.eco-cell-back');
        back.textContent = symbols[i];
        cell.classList.add('eco-flipped');

        // Highlight gagnant + scale après retournement
        if (winSym && symbols[i] === winSym) {
          setTimeout(() => {
            cell.classList.add('eco-cell-win');
            back.classList.add('eco-back-win-pop');
          }, 380);
        }

        if (i === cells.length - 1) setTimeout(onDone, 550);
      }, i * 200);
    });
  }

  function showResult(gain, msg, isError) {
    let html, klass;
    if (isError) {
      klass = 'lose'; html = `<span class="eco-result-emoji">⚠️</span>${msg}`;
    } else if (gain >= 7) {
      klass = 'jackpot'; html = `<span class="eco-result-emoji">🌻</span><strong>Incroyable ! ${gain} jetons !</strong>`;
    } else if (gain > 0) {
      klass = 'win'; html = `<span class="eco-result-emoji">🌿</span>Bravo ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> !`;
    } else {
      klass = 'lose'; html = `<span class="eco-result-emoji">🍂</span>Pas de paire… La nature sera plus clémente demain !`;
    }
    result.className      = `eco-result ${klass}`;
    result.innerHTML      = html;
    result.style.display  = 'block';
    playBtn.style.display = 'none';
  }
})();
