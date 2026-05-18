(function () {
  'use strict';

  // ── Segments (must match PHP order exactly) ──────────────────────────────────
  const SEGMENTS = [
    { label: 'Rien',       gain: 0,  color: '#607d8b', textColor: '#fff',    emoji: '😔' },
    { label: '+1 jeton',   gain: 1,  color: '#e53935', textColor: '#fff',    emoji: '🌸' },
    { label: '+2 jetons',  gain: 2,  color: '#fb8c00', textColor: '#fff',    emoji: '🍊' },
    { label: '+3 jetons',  gain: 3,  color: '#fdd835', textColor: '#333',    emoji: '⭐' },
    { label: '+5 jetons',  gain: 5,  color: '#43a047', textColor: '#fff',    emoji: '🍀' },
    { label: '+7 jetons',  gain: 7,  color: '#039be5', textColor: '#fff',    emoji: '💎' },
    { label: '+10 jetons', gain: 10, color: '#8e24aa', textColor: '#fff',    emoji: '💜' },
    { label: 'JACKPOT!',   gain: 20, color: '#d63031', textColor: '#ffd54f', emoji: '🎉' },
  ];

  const N   = SEGMENTS.length;
  const ARC = (2 * Math.PI) / N;

  const canvas      = document.getElementById('wheelCanvas');
  const spinBtn     = document.getElementById('spinBtn');
  const resultPanel = document.getElementById('resultPanel');
  const jetonCount  = document.getElementById('jetonCount');

  if (!canvas) return;
  const ctx = canvas.getContext('2d');

  let currentAngle = 0;
  let animating    = false;
  let rafId        = null;

  // ── Drawing ──────────────────────────────────────────────────────────────────

  function drawWheel(angle) {
    const W  = canvas.width;
    const H  = canvas.height;
    const cx = W / 2;
    const cy = H / 2;
    const r  = Math.min(cx, cy) - 10;

    ctx.clearRect(0, 0, W, H);

    // Segments
    for (let i = 0; i < N; i++) {
      const seg   = SEGMENTS[i];
      const start = angle + i * ARC - Math.PI / 2;
      const end   = start + ARC;

      ctx.beginPath();
      ctx.moveTo(cx, cy);
      ctx.arc(cx, cy, r, start, end);
      ctx.closePath();
      ctx.fillStyle = seg.color;
      ctx.fill();

      // Subtle inner gradient highlight
      ctx.beginPath();
      ctx.moveTo(cx, cy);
      ctx.arc(cx, cy, r, start, end);
      ctx.closePath();
      const grad = ctx.createRadialGradient(cx, cy, r * 0.3, cx, cy, r);
      grad.addColorStop(0, 'rgba(255,255,255,0.18)');
      grad.addColorStop(1, 'rgba(0,0,0,0.08)');
      ctx.fillStyle = grad;
      ctx.fill();

      // Separator line
      ctx.strokeStyle = 'rgba(255,255,255,0.8)';
      ctx.lineWidth   = 2.5;
      ctx.stroke();

      // Label text
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(start + ARC / 2);

      const textX = r - 18;

      ctx.font      = '18px serif';
      ctx.textAlign = 'right';
      ctx.fillStyle = seg.textColor;
      ctx.fillText(seg.emoji, textX, -7);

      ctx.font      = `bold ${N > 8 ? 11 : 13}px Poppins, Segoe UI, sans-serif`;
      ctx.fillStyle = seg.textColor;
      ctx.shadowColor = 'rgba(0,0,0,0.35)';
      ctx.shadowBlur  = 3;
      ctx.fillText(seg.label, textX, 11);
      ctx.shadowBlur  = 0;

      ctx.restore();
    }

    // Outer decorative ring
    ctx.beginPath();
    ctx.arc(cx, cy, r + 1, 0, 2 * Math.PI);
    ctx.strokeStyle = '#ffd54f';
    ctx.lineWidth   = 7;
    ctx.stroke();

    // Tick marks at each segment boundary
    for (let i = 0; i < N; i++) {
      const tickAngle = angle + i * ARC - Math.PI / 2;
      ctx.save();
      ctx.translate(cx, cy);
      ctx.rotate(tickAngle);
      ctx.beginPath();
      ctx.moveTo(r - 2, 0);
      ctx.lineTo(r + 9, 0);
      ctx.strokeStyle = '#fff';
      ctx.lineWidth   = 2.5;
      ctx.stroke();
      ctx.restore();
    }

    // Center hub (metallic radial gradient)
    const hubGrad = ctx.createRadialGradient(cx - 5, cy - 5, 2, cx, cy, 24);
    hubGrad.addColorStop(0, '#fffde7');
    hubGrad.addColorStop(0.6, '#ffd54f');
    hubGrad.addColorStop(1, '#e65100');

    ctx.beginPath();
    ctx.arc(cx, cy, 24, 0, 2 * Math.PI);
    ctx.fillStyle = hubGrad;
    ctx.fill();

    ctx.beginPath();
    ctx.arc(cx, cy, 24, 0, 2 * Math.PI);
    ctx.strokeStyle = '#fff';
    ctx.lineWidth   = 3;
    ctx.stroke();

    // Hub dot
    ctx.beginPath();
    ctx.arc(cx, cy, 7, 0, 2 * Math.PI);
    ctx.fillStyle = '#fff';
    ctx.fill();
  }

  // ── Highlight winning segment ─────────────────────────────────────────────────
  function highlightWinner(segIdx) {
    const cx = canvas.width / 2;
    const cy = canvas.height / 2;
    const r  = Math.min(cx, cy) - 10;

    const start = currentAngle + segIdx * ARC - Math.PI / 2;
    const end   = start + ARC;

    ctx.save();
    ctx.beginPath();
    ctx.moveTo(cx, cy);
    ctx.arc(cx, cy, r, start, end);
    ctx.closePath();
    ctx.fillStyle = 'rgba(255,255,255,0.28)';
    ctx.fill();
    ctx.restore();
  }

  // ── Easing ───────────────────────────────────────────────────────────────────
  function easeOut(t) {
    // Quartic ease-out: fast start, long soft deceleration
    return 1 - Math.pow(1 - t, 4);
  }

  // ── Spin animation ───────────────────────────────────────────────────────────

  function spinTo(targetIdx, duration, onComplete) {
    if (animating) return;
    animating = true;

    // Full extra spins + angle to land target under the pointer (top = -π/2)
    const extraSpins    = 6 + Math.floor(Math.random() * 3);
    const targetAngle   = -(targetIdx + 0.5) * ARC + 2 * Math.PI * extraSpins;
    const startAngle    = currentAngle;
    const startTime     = performance.now();

    function frame(now) {
      const elapsed  = Math.min(now - startTime, duration);
      const progress = elapsed / duration;
      const eased    = easeOut(progress);

      currentAngle = startAngle + targetAngle * eased;
      drawWheel(currentAngle);

      if (progress < 1) {
        rafId = requestAnimationFrame(frame);
      } else {
        currentAngle = startAngle + targetAngle;
        drawWheel(currentAngle);
        animating = false;
        if (onComplete) onComplete();
      }
    }

    rafId = requestAnimationFrame(frame);
  }

  // ── Result display ───────────────────────────────────────────────────────────

  function showResult(data) {
    if (!resultPanel) return;

    let cls, html;
    const g = data.gain;

    if (g === 20) {
      cls  = 'jackpot';
      html = `<span class="result-emoji">🎉🎊🎉</span>
              <strong>JACKPOT !</strong> Vous remportez <strong>${g} jetons</strong> !<br>
              <small style="opacity:.85">Félicitations, c'est exceptionnel !</small>`;
    } else if (g > 0) {
      cls  = 'win';
      html = `<span class="result-emoji">🏆</span>
              Bravo ! Vous gagnez <strong>${g} jeton${g > 1 ? 's' : ''}</strong> !`;
    } else {
      cls  = 'lose';
      html = `<span class="result-emoji">😔</span>
              Pas de chance cette fois… Revenez demain !`;
    }

    resultPanel.className        = `result-panel ${cls}`;
    resultPanel.innerHTML        = html;
    resultPanel.style.display    = 'block';

    if (jetonCount && data.jetons !== undefined) {
      jetonCount.textContent = data.jetons;
    }
  }

  // ── Spin button click ────────────────────────────────────────────────────────

  if (spinBtn && !ROUE_CONFIG.alreadyPlayed && ROUE_CONFIG.isLoggedIn) {
    spinBtn.addEventListener('click', async () => {
      if (animating) return;

      spinBtn.disabled     = true;
      spinBtn.innerHTML    = '<span class="spin-btn-icon">⏳</span><span class="spin-btn-text">Tirage…</span>';

      let data;
      try {
        const res = await fetch(window.location.href, {
          method:  'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body:    'action=spin',
        });
        if (!res.ok) throw new Error('HTTP ' + res.status);
        data = await res.json();
      } catch {
        spinBtn.disabled  = false;
        spinBtn.innerHTML = '<span class="spin-btn-icon">🎡</span><span class="spin-btn-text">Faire tourner !</span>';
        resultPanel.className     = 'result-panel lose';
        resultPanel.innerHTML     = '<span class="result-emoji">⚠️</span>Erreur réseau. Réessayez.';
        resultPanel.style.display = 'block';
        return;
      }

      if (data.error) {
        spinBtn.innerHTML         = '<span class="spin-btn-icon">🎡</span><span class="spin-btn-text">Faire tourner !</span>';
        spinBtn.disabled          = false;
        resultPanel.className     = 'result-panel lose';
        resultPanel.innerHTML     = `<span class="result-emoji">⚠️</span>${data.message || 'Une erreur est survenue.'}`;
        resultPanel.style.display = 'block';
        return;
      }

      spinTo(data.segment, 5400, () => {
        highlightWinner(data.segment);
        showResult(data);
      });
    });
  }

  // ── Initial draw ─────────────────────────────────────────────────────────────

  if (ROUE_CONFIG.alreadyPlayed && ROUE_CONFIG.todaySegment !== null) {
    // Replay a short entrance animation to the today's result position
    const entranceTarget = -(ROUE_CONFIG.todaySegment + 0.5) * ARC + 2 * Math.PI * 2;
    const startTime      = performance.now();
    const duration       = 1600;

    function entranceFrame(now) {
      const elapsed  = Math.min(now - startTime, duration);
      const progress = elapsed / duration;
      currentAngle   = entranceTarget * easeOut(progress);
      drawWheel(currentAngle);

      if (progress < 1) {
        requestAnimationFrame(entranceFrame);
      } else {
        currentAngle = entranceTarget;
        drawWheel(currentAngle);
        highlightWinner(ROUE_CONFIG.todaySegment);
      }
    }
    requestAnimationFrame(entranceFrame);
  } else {
    drawWheel(0);
  }

})();
