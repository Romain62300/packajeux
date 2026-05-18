(function () {
  'use strict';
  if (!VIP_CONFIG.isLoggedIn || VIP_CONFIG.alreadyPlayed) return;

  const zones   = document.getElementById('vipZones');
  const playBtn = document.getElementById('vipPlayBtn');
  const result  = document.getElementById('vipResult');
  const jetonEl = document.getElementById('jetonCount');
  if (!zones || !playBtn) return;

  playBtn.addEventListener('click', async () => {
    playBtn.disabled  = true;
    playBtn.innerHTML = '<span class="vip-btn-icon">⏳</span><span>Tirage…</span>';

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
      playBtn.innerHTML = '<span class="vip-btn-icon">👑</span><span>Gratter le ticket !</span>';
      return;
    }

    if (data.error) { showResult(0, data.message, true); return; }

    revealZones(data.grid, data.gain, () => {
      if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;
      showResult(data.gain);
    });
  });

  function revealZones(symbols, gain, onDone) {
    const zoneEls = zones.querySelectorAll('.vip-zone');
    const isWin   = gain > 0;

    zoneEls.forEach((zone, i) => {
      setTimeout(() => {
        const cover  = zone.querySelector('.vip-zone-cover');
        const symbol = zone.querySelector('.vip-zone-symbol');

        symbol.textContent = symbols[i];
        cover.classList.add('vip-cover-removed');

        // Pop du symbole une fois la cover disparue
        setTimeout(() => {
          symbol.classList.add('vip-symbol-pop');
          if (isWin) zone.classList.add('vip-zone-win');
        }, 380);

        if (i === zoneEls.length - 1) {
          setTimeout(() => {
            // Shimmer sur le ticket entier en cas de victoire
            if (isWin) {
              const ticket = document.querySelector('.vip-ticket');
              if (ticket) ticket.classList.add('vip-ticket-win');
            }
            onDone();
          }, 750);
        }
      }, i * 380);
    });
  }

  function showResult(gain, msg, isError) {
    let html, klass;
    if (isError) {
      klass = 'lose'; html = `<span class="vip-result-emoji">⚠️</span>${msg}`;
    } else if (gain >= 15) {
      klass = 'jackpot'; html = `<span class="vip-result-emoji">💎</span><strong>MEGA JACKPOT — ${gain} jetons !</strong>`;
    } else if (gain > 0) {
      klass = 'win'; html = `<span class="vip-result-emoji">🏆</span>Triple alignement ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> !`;
    } else {
      klass = 'lose'; html = `<span class="vip-result-emoji">💔</span>Pas d'alignement… Réessayez demain !`;
    }
    result.className      = `vip-result ${klass}`;
    result.innerHTML      = html;
    result.style.display  = 'block';
    playBtn.style.display = 'none';
  }
})();
