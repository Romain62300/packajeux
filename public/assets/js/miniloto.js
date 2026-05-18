(function () {
  'use strict';
  if (!LOTO_CONFIG.isLoggedIn || LOTO_CONFIG.alreadyPlayed) return;

  const lotoGrid = document.getElementById('lotoGrid');
  const playBtn  = document.getElementById('lotoPlayBtn');
  const result   = document.getElementById('lotoResult');
  const selEl    = document.getElementById('lotoSelected');
  const jetonEl  = document.getElementById('jetonCount');
  if (!lotoGrid || !playBtn) return;

  const selected = new Set();

  lotoGrid.addEventListener('click', e => {
    const btn = e.target.closest('.loto-num');
    if (!btn) return;
    const n = +btn.dataset.n;

    if (selected.has(n)) {
      selected.delete(n);
      btn.classList.remove('loto-num-selected');
    } else if (selected.size < LOTO_CONFIG.pick) {
      selected.add(n);
      btn.classList.add('loto-num-selected');
    }

    updateUI();
  });

  function updateUI() {
    const arr = [...selected].sort((a, b) => a - b);
    selEl.textContent  = arr.length ? arr.join(' — ') : '—';
    playBtn.disabled   = arr.length !== LOTO_CONFIG.pick;
  }

  playBtn.addEventListener('click', async () => {
    if (selected.size !== LOTO_CONFIG.pick) return;
    playBtn.disabled  = true;
    playBtn.innerHTML = '<span class="loto-btn-icon">⏳</span><span>Tirage en cours…</span>';
    lotoGrid.style.pointerEvents = 'none';

    let data;
    try {
      const res = await fetch(window.location.href, {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    `action=play&numbers=${[...selected].join(',')}`,
      });
      if (!res.ok) throw new Error();
      data = await res.json();
    } catch {
      playBtn.disabled  = false;
      playBtn.innerHTML = '<span class="loto-btn-icon">🎟️</span><span>Valider ma grille !</span>';
      lotoGrid.style.pointerEvents = '';
      return;
    }

    if (data.error) { showResult(null, data.message, true); return; }

    // Highlight drawn numbers
    lotoGrid.querySelectorAll('.loto-num').forEach(btn => {
      const n = +btn.dataset.n;
      if (data.drawn.includes(n) && selected.has(n)) btn.classList.add('loto-num-match');
      else if (data.drawn.includes(n))                btn.classList.add('loto-num-drawn');
    });

    if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;

    // Show drawn numbers with animation
    showDrawn(data.drawn, () => showResult(data.gain, null, false, data.matches));
  });

  function showDrawn(drawn, cb) {
    const drawnDiv = document.createElement('div');
    drawnDiv.className = 'loto-drawn-row';
    drawnDiv.innerHTML = '<span class="loto-drawn-label">Tirage système&nbsp;:</span>';
    const wrap = document.createElement('span');
    wrap.className = 'loto-drawn-nums';
    drawn.forEach((n, i) => {
      setTimeout(() => {
        const ball = document.createElement('span');
        ball.className   = 'loto-ball' + (selected.has(n) ? ' loto-ball-match' : '');
        ball.textContent = n;
        wrap.appendChild(ball);
        if (i === drawn.length - 1) setTimeout(cb, 500);
      }, i * 200);
    });
    drawnDiv.appendChild(wrap);
    result.innerHTML     = '';
    result.style.display = 'block';
    result.appendChild(drawnDiv);
  }

  function showResult(gain, msg, isError, matches) {
    const existing = result.querySelector('.loto-drawn-row');
    const div = document.createElement('div');
    div.className = 'loto-result-msg';

    if (isError) {
      div.className += ' lose';
      div.innerHTML  = `<span class="loto-result-emoji">⚠️</span>${msg}`;
    } else if (gain >= 20) {
      div.className += ' jackpot';
      div.innerHTML  = `<span class="loto-result-emoji">🎉</span><strong>JACKPOT — 5/5 !</strong> Vous gagnez <strong>${gain} jetons</strong> !`;
    } else if (gain > 0) {
      div.className += ' win';
      div.innerHTML  = `<span class="loto-result-emoji">🏆</span>${matches} bons numéros ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> !`;
    } else {
      div.className += ' lose';
      div.innerHTML  = `<span class="loto-result-emoji">😔</span>0 ou 1 bon numéro… Réessayez demain !`;
    }

    if (existing) result.appendChild(div);
    else { result.innerHTML = ''; result.appendChild(div); result.style.display = 'block'; }

    playBtn.style.display = 'none';
  }
})();
