(function () {
  'use strict';
  if (!COCHON_CONFIG.isLoggedIn || COCHON_CONFIG.alreadyPlayed) return;

  const wrap      = document.getElementById('scratchWrap');
  const canvas    = document.getElementById('scratchCanvas');
  const revealEl  = document.getElementById('cochonReveal');
  const revealTxt = document.getElementById('revealText');
  const hintEl    = document.getElementById('scratchHint');
  const resultEl  = document.getElementById('cochonResult');
  const jetonEl   = document.getElementById('jetonCount');
  if (!canvas) return;

  // ── Dimensionner le canvas sur le wrapper ──────────────────────────────────
  function resizeCanvas() {
    const rect = wrap.getBoundingClientRect();
    canvas.width  = rect.width  || 320;
    canvas.height = rect.height || 160;
  }
  resizeCanvas();

  const ctx = canvas.getContext('2d');

  // ── Dessine la couche de grattage ──────────────────────────────────────────
  function drawScratchLayer() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.globalCompositeOperation = 'source-over';

    // Fond rose/cochon avec texture dégradée
    const grad = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
    grad.addColorStop(0, '#f8b4c8');
    grad.addColorStop(0.5, '#f48caa');
    grad.addColorStop(1, '#ec6490');
    ctx.fillStyle = grad;
    ctx.beginPath();
    ctx.roundRect ? ctx.roundRect(0, 0, canvas.width, canvas.height, 14)
                  : ctx.rect(0, 0, canvas.width, canvas.height);
    ctx.fill();

    // Motif points
    ctx.fillStyle = 'rgba(255,255,255,0.12)';
    for (let x = 16; x < canvas.width; x += 28) {
      for (let y = 16; y < canvas.height; y += 28) {
        ctx.beginPath();
        ctx.arc(x, y, 3, 0, Math.PI * 2);
        ctx.fill();
      }
    }

    // Texte d'invitation
    ctx.font = `bold ${Math.min(22, canvas.width / 12)}px Poppins, Segoe UI, sans-serif`;
    ctx.fillStyle = 'rgba(255,255,255,0.9)';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.shadowColor = 'rgba(0,0,0,0.2)';
    ctx.shadowBlur  = 4;
    ctx.fillText('🐷  Grattez ici !', canvas.width / 2, canvas.height / 2);
    ctx.shadowBlur = 0;
  }
  drawScratchLayer();

  // ── État ──────────────────────────────────────────────────────────────────
  let isDrawing   = false;
  let fetchDone   = false;
  let gainData    = null;
  let revealed    = false;

  // ── Scratch helpers ────────────────────────────────────────────────────────
  function getPos(e) {
    const rect = canvas.getBoundingClientRect();
    const src  = e.touches ? e.touches[0] : e;
    return {
      x: (src.clientX - rect.left) * (canvas.width  / rect.width),
      y: (src.clientY - rect.top)  * (canvas.height / rect.height),
    };
  }

  function scratch(pos) {
    ctx.globalCompositeOperation = 'destination-out';
    ctx.beginPath();
    ctx.arc(pos.x, pos.y, 28, 0, Math.PI * 2);
    ctx.fill();
  }

  function clearPercent() {
    const data = ctx.getImageData(0, 0, canvas.width, canvas.height).data;
    let cleared = 0;
    for (let i = 3; i < data.length; i += 4) if (data[i] < 128) cleared++;
    return (cleared / (canvas.width * canvas.height)) * 100;
  }

  function onReveal() {
    if (revealed) return;
    revealed = true;
    canvas.style.transition = 'opacity .5s ease';
    canvas.style.opacity    = '0';
    canvas.style.pointerEvents = 'none';
    hintEl.style.display = 'none';

    if (gainData) showResult(gainData.gain);
    else {
      // Attend les données AJAX si pas encore arrivées
      const poll = setInterval(() => {
        if (gainData) { clearInterval(poll); showResult(gainData.gain); }
      }, 80);
    }
  }

  function checkCompletion() {
    if (revealed) return;
    if (clearPercent() > 45) onReveal();
  }

  // ── AJAX — déclenché au premier trait de grattage ─────────────────────────
  async function fetchResult() {
    if (fetchDone) return;
    fetchDone = true;
    try {
      const res = await fetch(window.location.href, {
        method:  'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body:    'action=play',
      });
      if (!res.ok) throw new Error();
      const data = await res.json();
      if (data.error) { gainData = { gain: -1, message: data.message }; return; }
      gainData = data;

      // Pré-rempli la zone cachée
      if (data.gain > 0) {
        revealEl.classList.add('cochon-reveal--win');
        revealTxt.textContent = `+${data.gain} jeton${data.gain > 1 ? 's' : ''} ! 🎉`;
      } else {
        revealEl.classList.add('cochon-reveal--lose');
        revealTxt.textContent = 'Rien cette fois… 😔';
      }
      if (jetonEl && data.jetons !== undefined) jetonEl.textContent = data.jetons;
    } catch {
      gainData = { gain: -1, message: 'Erreur réseau.' };
    }
  }

  // ── Événements souris ──────────────────────────────────────────────────────
  canvas.addEventListener('mousedown', (e) => {
    isDrawing = true;
    fetchResult();
    scratch(getPos(e));
    hintEl.style.opacity = '0';
  });
  canvas.addEventListener('mousemove', (e) => {
    if (!isDrawing) return;
    scratch(getPos(e));
    checkCompletion();
  });
  canvas.addEventListener('mouseup',    () => { isDrawing = false; });
  canvas.addEventListener('mouseleave', () => { isDrawing = false; });

  // ── Événements tactiles (mobile) ──────────────────────────────────────────
  canvas.addEventListener('touchstart', (e) => {
    e.preventDefault();
    isDrawing = true;
    fetchResult();
    scratch(getPos(e));
    hintEl.style.opacity = '0';
  }, { passive: false });
  canvas.addEventListener('touchmove', (e) => {
    e.preventDefault();
    if (!isDrawing) return;
    scratch(getPos(e));
    checkCompletion();
  }, { passive: false });
  canvas.addEventListener('touchend', () => { isDrawing = false; });

  // ── Affichage du résultat ─────────────────────────────────────────────────
  function showResult(gain) {
    if (!resultEl) return;
    let html, klass;
    if (gain < 0) {
      klass = 'lose'; html = `<span class="cochon-result-emoji">⚠️</span>${gainData.message}`;
    } else if (gain >= 5) {
      klass = 'jackpot'; html = `<span class="cochon-result-emoji">🎉</span><strong>Incroyable ! +${gain} jetons !</strong>`;
    } else if (gain > 0) {
      klass = 'win'; html = `<span class="cochon-result-emoji">🏆</span>Bravo ! <strong>+${gain} jeton${gain > 1 ? 's' : ''}</strong> remporté${gain > 1 ? 's' : ''} !`;
    } else {
      klass = 'lose'; html = `<span class="cochon-result-emoji">🐷</span>Pas de chance… Revenez demain !`;
    }
    resultEl.className     = `cochon-result ${klass}`;
    resultEl.innerHTML     = html;
    resultEl.style.display = 'block';
  }
})();
