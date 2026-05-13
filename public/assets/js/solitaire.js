'use strict';

// ── CONSTANTES ──────────────────────────────────────
const SUITS    = ['♠','♥','♦','♣'];
const RANKS    = ['A','2','3','4','5','6','7','8','9','10','J','Q','K'];
const RED_SUITS  = ['♥','♦'];
const RANK_VAL = Object.fromEntries(RANKS.map((r,i) => [r, i]));
// Chaque fondation est assignée à une couleur fixe (ordre : ♠ ♥ ♦ ♣)
const FOUNDATION_SUITS = ['♠','♥','♦','♣'];

// ── UTILITAIRES ──────────────────────────────────────
function isRed(suit)  { return RED_SUITS.includes(suit); }
function rankVal(r)   { return RANK_VAL[r]; }
function pad(n)       { return String(n).padStart(2,'0'); }

// ── CLASSE PRINCIPALE ────────────────────────────────
class Solitaire {

  constructor() {
    this.deck        = [];
    this.waste       = [];
    this.foundations = [[],[],[],[]];
    this.tableau     = [[],[],[],[],[],[],[]];
    this.history     = [];
    this.selected    = null;
    this.score       = 0;
    this.moves       = 0;
    this.seconds     = 0;
    this.timerHandle = null;
    this._bindUI();
    this.newGame();
  }

  // ── INITIALISATION ──
  newGame() {
    clearInterval(this.timerHandle);
    this.deck        = this._buildDeck();
    this.waste       = [];
    this.foundations = [[],[],[],[]];
    this.tableau     = [[],[],[],[],[],[],[]];
    this.history     = [];
    this.selected    = null;
    this.score       = 0;
    this.moves       = 0;
    this.seconds     = 0;
    this._deal();
    this._startTimer();
    this._render();
    document.getElementById('victory').style.display = 'none';
  }

  _buildDeck() {
    const deck = [];
    for (const suit of SUITS)
      for (const rank of RANKS)
        deck.push({ suit, rank, faceUp: false });
    for (let i = deck.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [deck[i], deck[j]] = [deck[j], deck[i]];
    }
    return deck;
  }

  _deal() {
    for (let col = 0; col < 7; col++) {
      for (let row = 0; row <= col; row++) {
        const card = this.deck.pop();
        card.faceUp = (row === col);
        this.tableau[col].push(card);
      }
    }
    this.deck.forEach(c => c.faceUp = false);
  }

  // ── TIMER ──
  _startTimer() {
    this.timerHandle = setInterval(() => {
      this.seconds++;
      const m = Math.floor(this.seconds / 60);
      const s = this.seconds % 60;
      document.getElementById('timer').textContent = `${pad(m)}:${pad(s)}`;
    }, 1000);
  }

  // ── HISTORIQUE ──
  _saveState() {
    this.history.push({
      deck:        JSON.parse(JSON.stringify(this.deck)),
      waste:       JSON.parse(JSON.stringify(this.waste)),
      foundations: JSON.parse(JSON.stringify(this.foundations)),
      tableau:     JSON.parse(JSON.stringify(this.tableau)),
      score:       this.score,
      moves:       this.moves,
    });
    if (this.history.length > 50) this.history.shift();
  }

  undo() {
    if (!this.history.length) return;
    const s = this.history.pop();
    this.deck        = s.deck;
    this.waste       = s.waste;
    this.foundations = s.foundations;
    this.tableau     = s.tableau;
    this.score       = s.score;
    this.moves       = s.moves;
    this.selected    = null;
    this._render();
  }

  // ── PIOCHE ──
  drawFromDeck() {
    this._saveState();
    if (this.deck.length === 0) {
      // Bug fix : copie avant reverse pour ne pas muter waste
      this.deck  = [...this.waste].reverse().map(c => ({ ...c, faceUp: false }));
      this.waste = [];
      this.score = Math.max(0, this.score - 100);
    } else {
      const card = this.deck.pop();
      card.faceUp = true;
      this.waste.push(card);
      this.score += 5;
    }
    this.selected = null;
    this._render();
  }

  // ── SÉLECTION / DÉPLACEMENT ──
  selectCard(source, colIndex, cardIndex) {
    const cards = this._getCards(source, colIndex);
    if (!cards) return;

    const card = cards[cardIndex];
    if (!card || !card.faceUp) return;

    // Désélectionner si même carte
    if (this.selected &&
        this.selected.source === source &&
        this.selected.colIndex === colIndex &&
        this.selected.cardIndex === cardIndex) {
      this.selected = null;
      this._render();
      return;
    }

    // Tenter déplacement si sélection existante
    if (this.selected) {
      const moved = this._tryMove(source, colIndex, cardIndex);
      if (!moved) {
        this.selected = { source, colIndex, cardIndex };
      }
      this._render();
      return;
    }

    this.selected = { source, colIndex, cardIndex };
    this._render();
  }

  selectPile(target, colIndex) {
    if (!this.selected) return;
    this._tryMove(target, colIndex, null);
    this._render();
  }

  _tryMove(targetSource, targetCol) {
    const { source, colIndex, cardIndex } = this.selected;
    const srcCards = this._getCards(source, colIndex);
    const moving   = srcCards.slice(cardIndex);

    if (targetSource === 'foundation') {
      if (moving.length !== 1) { this.selected = null; return false; }
      if (this._canPlaceOnFoundation(moving[0], targetCol)) {
        this._saveState();
        this.foundations[targetCol].push(moving[0]);
        srcCards.splice(cardIndex, 1);
        this._flipTop(source, colIndex);
        this.score += 15;
        this.moves++;
        this.selected = null;
        this._checkVictory();
        return true;
      }
      this.selected = null;
      return false;
    }

    if (targetSource === 'tableau') {
      const destCards = this.tableau[targetCol];
      if (this._canPlaceOnTableau(moving[0], destCards)) {
        this._saveState();
        destCards.push(...moving);
        srcCards.splice(cardIndex, moving.length);
        this._flipTop(source, colIndex);
        this.score += 5;
        this.moves++;
        this.selected = null;
        return true;
      }
      this.selected = null;
      return false;
    }

    this.selected = null;
    return false;
  }

  _canPlaceOnFoundation(card, fIdx) {
    const pile = this.foundations[fIdx];
    const expectedSuit = FOUNDATION_SUITS[fIdx];
    // Bug fix : As doit correspondre à la couleur de la fondation
    if (pile.length === 0) return card.rank === 'A' && card.suit === expectedSuit;
    const top = pile[pile.length - 1];
    return top.suit === card.suit && rankVal(card.rank) === rankVal(top.rank) + 1;
  }

  _canPlaceOnTableau(card, destCards) {
    if (destCards.length === 0) return card.rank === 'K';
    const top = destCards[destCards.length - 1];
    if (!top.faceUp) return false;
    return isRed(card.suit) !== isRed(top.suit) &&
           rankVal(card.rank) === rankVal(top.rank) - 1;
  }

  _flipTop(source, colIndex) {
    if (source !== 'tableau') return;
    const col = this.tableau[colIndex];
    if (col.length > 0 && !col[col.length - 1].faceUp) {
      col[col.length - 1].faceUp = true;
      this.score += 5;
    }
  }

  _getCards(source, colIndex) {
    if (source === 'waste')      return this.waste;
    if (source === 'foundation') return this.foundations[colIndex];
    if (source === 'tableau')    return this.tableau[colIndex];
    return null;
  }

  // ── INDICE ──
  hint() {
    const moves = this._findMoves();
    if (!moves.length) return;
    const m = moves[0];
    const el = document.querySelector(`[data-source="${m.source}"][data-col="${m.colIndex}"][data-idx="${m.cardIndex}"]`);
    if (el) {
      el.classList.add('hint');
      setTimeout(() => el.classList.remove('hint'), 2000);
    }
  }

  _findMoves() {
    const moves = [];
    if (this.waste.length) {
      const card = this.waste[this.waste.length - 1];
      for (let f = 0; f < 4; f++) {
        if (this._canPlaceOnFoundation(card, f))
          moves.push({ source: 'waste', colIndex: 0, cardIndex: this.waste.length - 1 });
      }
      for (let c = 0; c < 7; c++) {
        if (this._canPlaceOnTableau(card, this.tableau[c]))
          moves.push({ source: 'waste', colIndex: 0, cardIndex: this.waste.length - 1 });
      }
    }
    for (let col = 0; col < 7; col++) {
      const pile = this.tableau[col];
      for (let i = 0; i < pile.length; i++) {
        if (!pile[i].faceUp) continue;
        const card = pile[i];
        for (let f = 0; f < 4; f++) {
          if (this._canPlaceOnFoundation(card, f))
            moves.push({ source: 'tableau', colIndex: col, cardIndex: i });
        }
        for (let c = 0; c < 7; c++) {
          if (c === col) continue;
          if (this._canPlaceOnTableau(card, this.tableau[c]))
            moves.push({ source: 'tableau', colIndex: col, cardIndex: i });
        }
      }
    }
    return moves;
  }

  // ── VICTOIRE ──
  _checkVictory() {
    const won = this.foundations.every(f => f.length === 13);
    if (!won) return;
    clearInterval(this.timerHandle);
    const m = Math.floor(this.seconds / 60);
    const s = this.seconds % 60;
    document.getElementById('victory-time').textContent = `${pad(m)}m ${pad(s)}s`;
    document.getElementById('victory-score').textContent = this.score;
    document.getElementById('victory-moves').textContent = this.moves;
    document.getElementById('victory').style.display = 'flex';
  }

  // ── RENDU ──
  _render() {
    document.getElementById('score').textContent = this.score;
    document.getElementById('moves').textContent = this.moves;
    this._renderDeck();
    this._renderWaste();
    this._renderFoundations();
    this._renderTableau();
  }

  _renderDeck() {
    const el = document.getElementById('deck');
    el.innerHTML = '';
    if (this.deck.length > 0) {
      const div = document.createElement('div');
      div.className = 'card face-down';
      el.appendChild(div);
    } else {
      el.innerHTML = '<div class="pile-placeholder" title="Retourner la défausse">↩</div>';
    }
  }

  _renderWaste() {
    const el = document.getElementById('waste');
    el.innerHTML = '';
    if (!this.waste.length) return;
    const card = this.waste[this.waste.length - 1];
    const idx  = this.waste.length - 1;
    const isSelected = this.selected &&
                       this.selected.source === 'waste' &&
                       this.selected.cardIndex === idx;
    el.appendChild(this._makeCardEl(card, 'waste', 0, idx, isSelected));
  }

  _renderFoundations() {
    for (let f = 0; f < 4; f++) {
      const el   = document.getElementById(`foundation-${f}`);
      const pile = this.foundations[f];
      el.innerHTML = '';
      if (pile.length > 0) {
        const card = pile[pile.length - 1];
        el.appendChild(this._makeCardEl(card, 'foundation', f, pile.length - 1, false));
      }
    }
  }

  _renderTableau() {
    for (let col = 0; col < 7; col++) {
      const el   = document.getElementById(`col-${col}`);
      const pile = this.tableau[col];
      el.innerHTML = '';
      el.style.height = pile.length > 0
        ? `${114 + (pile.length - 1) * 28}px`
        : '114px';
      pile.forEach((card, idx) => {
        const isSelected = this.selected &&
                           this.selected.source === 'tableau' &&
                           this.selected.colIndex === col &&
                           idx >= this.selected.cardIndex;
        const cardEl = this._makeCardEl(card, 'tableau', col, idx, isSelected);
        cardEl.style.top = `${idx * 28}px`;
        el.appendChild(cardEl);
      });
    }
  }

  _makeCardEl(card, source, colIndex, cardIndex, isSelected) {
    const div = document.createElement('div');
    div.className = 'card' + (card.faceUp ? (isRed(card.suit) ? ' red' : ' black') : ' face-down');
    if (isSelected) div.classList.add('selected');
    div.dataset.source = source;
    div.dataset.col    = colIndex;
    div.dataset.idx    = cardIndex;

    if (card.faceUp) {
      div.innerHTML = `
        <div class="card-corner">
          <span class="card-rank">${card.rank}</span>
          <span class="card-suit-small">${card.suit}</span>
        </div>
        <div class="card-center">${card.suit}</div>
        <div class="card-corner bottom">
          <span class="card-rank">${card.rank}</span>
          <span class="card-suit-small">${card.suit}</span>
        </div>`;
    }

    // Bug fix : stopPropagation pour éviter double-fire
    div.addEventListener('click', (e) => {
      e.stopPropagation();
      this.selectCard(source, colIndex, cardIndex);
    });
    return div;
  }

  // ── BINDINGS UI ──
  _bindUI() {
    document.getElementById('deck').addEventListener('click', () => this.drawFromDeck());
    document.getElementById('btn-new').addEventListener('click', () => this.newGame());
    document.getElementById('btn-undo').addEventListener('click', () => this.undo());
    document.getElementById('btn-hint').addEventListener('click', () => this.hint());

    // Bug fix : fondation — clic sur la pile vide seulement
    for (let f = 0; f < 4; f++) {
      document.getElementById(`foundation-${f}`).addEventListener('click', (e) => {
        if (e.target === e.currentTarget && this.selected) {
          this.selectPile('foundation', f);
        }
      });
    }

    // Colonnes tableau — clic sur zone vide seulement
    for (let c = 0; c < 7; c++) {
      document.getElementById(`col-${c}`).addEventListener('click', (e) => {
        if (e.target === e.currentTarget && this.selected) {
          this.selectPile('tableau', c);
        }
      });
    }
  }
}

// ── LANCEMENT ──
const game = new Solitaire();
