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
      btn.classList.add('loto-num-pulse');
      setTimeout(() => btn.classList.remove('loto-num-pulse'), 400);
    }

    updateUI();
  });

  function updateUI() {
    const arr = [...selected].sort((a, b) => a - b);
    selEl.innerHTML = arr.length
      ? arr.map(n => `<span class="loto-sel-pill">${n}</span>`).join('')
      : '—';
    playBtn.disabled = arr.length !== LOTO_CONFIG.pick;
    if (arr.length === LOTO_CONFIG.pick) playBtn.classList.add('loto-play-btn--ready');
    else playBtn.classList.remove('loto-play-btn--ready');
  }

  playBtn.addEventListener('click', async () => {
    if (selected.size !== LOTO_CONFIG.pick) return;
    playBtn.disabled  = true;
    playBtn.classList.remove('loto-play-btn--ready');
    playBtn.innerHTML = '<span class="loto-btn-icon loto-btn-spin">🎰</span><span>Tirage en cours…</span>';
    lotoGrid.style.pointerEvents = 'none';

    // Suspense visuel sur la grille
    lotoGrid.querySelectorAll('.loto-num-selected').forEach(b => b.classList.add('loto-num-suspense'));

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
      lotoGrid.querySelectorAll('.loto-num-selected').forEach(b => b.classList.remove('loto-num-suspense'));
      return;
    }

    if (data.error) { showResult(null, data.message, true); return; }

    // Retire suspense, met les boules gagnantes en évidence sur la grille
    lotoGrid.querySelectorAll('.loto-num').forEach(btn => {
      const n = +btn.dataset.n;
      btn.classList.remove('loto-num-suspense');
      if (data.drawn.includes(n) && selected.has(n)) btn.classList.add('loto-num-match');
      else if (data.drawn.includes(n))                btn.classList.add('loto-num-drawn');
    });

    if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;

    showDrawn(data.drawn, () => showResult(data.gain, null, false, data.matches));
  });

  // ── Affichage du tirage balle par balle ──────────────────────────────────
  function showDrawn(drawn, cb) {
    result.innerHTML     = '';
    result.style.display = 'block';

    const drawnDiv = document.createElement('div');
    drawnDiv.className = 'loto-drawn-row';

    const label = document.createElement('span');
    label.className   = 'loto-drawn-label';
    label.textContent = 'Tirage système :';
    drawnDiv.appendChild(label);

    const wrap = document.createElement('div');
    wrap.className = 'loto-drawn-nums';
    drawnDiv.appendChild(wrap);

    // Compteur de correspondances en temps réel
    const counter = document.createElement('div');
    counter.className = 'loto-match-counter';
    counter.innerHTML = 'Correspondances&nbsp;: <strong id="lotoMatchCount">0</strong>';
    drawnDiv.appendChild(counter);

    result.appendChild(drawnDiv);

    let matchCount = 0;
    const DELAY = 320;

    drawn.forEach((n, i) => {
      setTimeout(() => {
        const ball = document.createElement('span');
        const isMatch = selected.has(n);
        ball.className   = 'loto-ball' + (isMatch ? ' loto-ball-match' : '');
        ball.textContent = n;
        ball.style.animationDelay = '0ms';
        wrap.appendChild(ball);

        if (isMatch) {
          matchCount++;
          const mc = document.getElementById('lotoMatchCount');
          if (mc) {
            mc.textContent = matchCount;
            mc.classList.add('loto-count-bump');
            setTimeout(() => mc.classList.remove('loto-count-bump'), 400);
          }
        }

        if (i === drawn.length - 1) setTimeout(cb, 650);
      }, 200 + i * DELAY);
    });
  }

  function showResult(gain, msg, isError, matches) {
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
      div.innerHTML  = `<span class="loto-result-emoji">🏆</span>${matches} bon${matches > 1 ? 's' : ''} numéro${matches > 1 ? 's' : ''} ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> !`;
    } else {
      div.className += ' lose';
      div.innerHTML  = `<span class="loto-result-emoji">😔</span>0 ou 1 bon numéro… Réessayez demain !`;
    }

    result.appendChild(div);
    playBtn.style.display = 'none';
  }
})();
