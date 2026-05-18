'use strict';

// ── Persistance localStorage ──────────────────────────────────────────────────
const LS_KEY = 'pfc_score';

function loadScore() {
  try {
    const saved = JSON.parse(localStorage.getItem(LS_KEY));
    if (saved && typeof saved.won === 'number' && typeof saved.lost === 'number') {
      return { won: saved.won, lost: saved.lost, draws: saved.draws || 0 };
    }
  } catch {}
  return { won: 0, lost: 0, draws: 0 };
}

function saveScore() {
  localStorage.setItem(LS_KEY, JSON.stringify({ won, lost, draws }));
}

function resetScore() {
  won = 0; lost = 0; draws = 0;
  saveScore();
  updateScoreDisplay();
  document.getElementById('resultat').textContent = '';
  document.getElementById('choix-user').textContent = '';
}

// ── État ──────────────────────────────────────────────────────────────────────
let user = '';
const score  = loadScore();
let won   = score.won;
let lost  = score.lost;
let draws = score.draws;

// ── Affichage du score ─────────────────────────────────────────────────────
function updateScoreDisplay() {
  const el = document.getElementById('score');
  if (!el) return;
  el.innerHTML =
    `<span class="pfc-score-item pfc-win">✅ ${won}</span>` +
    `<span class="pfc-score-item pfc-draw">🟰 ${draws}</span>` +
    `<span class="pfc-score-item pfc-lost">❌ ${lost}</span>`;
}

// ── Choix du joueur ────────────────────────────────────────────────────────
function res(val) {
  user = val;
  const icons = { Pierre: '✊', Feuille: '📄', Ciseau: '✂️' };
  const el = document.getElementById('choix-user');
  if (el) {
    el.innerHTML = `<span class="pfc-choice-badge">${icons[val]} ${val}</span>`;
  }
  // Active le bouton résultat
  const btn = document.getElementById('resultBtn');
  if (btn) btn.disabled = false;
}

// ── Tirage ordinateur ──────────────────────────────────────────────────────
function compute() {
  return ['Pierre', 'Feuille', 'Ciseau'][Math.floor(Math.random() * 3)];
}

// ── Résultat ───────────────────────────────────────────────────────────────
function testing() {
  if (!user) return;

  computer = compute();
  const icons = { Pierre: '✊', Feuille: '📄', Ciseau: '✂️' };
  let outcome, cls;

  if (user === computer) {
    outcome = '🟰 Égalité !'; cls = 'draw'; draws++;
  } else if (
    (user === 'Pierre'  && computer === 'Ciseau') ||
    (user === 'Feuille' && computer === 'Pierre') ||
    (user === 'Ciseau'  && computer === 'Feuille')
  ) {
    outcome = '✅ Gagné !'; cls = 'win'; won++;
  } else {
    outcome = '❌ Perdu !'; cls = 'lose'; lost++;
  }

  saveScore();
  updateScoreDisplay();

  const el = document.getElementById('resultat');
  if (el) {
    el.innerHTML =
      `<div class="pfc-result-row">` +
        `<span class="pfc-player">🧑 ${icons[user]} <em>${user}</em></span>` +
        `<span class="pfc-vs">VS</span>` +
        `<span class="pfc-cpu">🤖 ${icons[computer]} <em>${computer}</em></span>` +
      `</div>` +
      `<div class="pfc-outcome pfc-outcome--${cls}">${outcome}</div>`;
  }

  // Reset pour prochain tour
  user = '';
  const choixEl = document.getElementById('choix-user');
  if (choixEl) choixEl.innerHTML = '';
  const btn = document.getElementById('resultBtn');
  if (btn) btn.disabled = true;
}

// ── Init ───────────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  updateScoreDisplay();

  const resetBtn = document.getElementById('resetScoreBtn');
  if (resetBtn) resetBtn.addEventListener('click', resetScore);

  const resultBtn = document.getElementById('resultBtn');
  if (resultBtn) resultBtn.disabled = true;
});

var computer = '';
