<?php include(__DIR__ . '/../../includes/header.php'); ?>

<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style-solitaire.css">

<div class="solitaire-wrapper">

  <div class="solitaire-header">
    <h1>Solitaire <span class="card-emoji">🃏</span></h1>
    <div class="solitaire-stats">
      <div class="stat-box">
        <span class="stat-label">Score</span>
        <span class="stat-value" id="score">0</span>
      </div>
      <div class="stat-box">
        <span class="stat-label">Temps</span>
        <span class="stat-value" id="timer">00:00</span>
      </div>
      <div class="stat-box">
        <span class="stat-label">Mouvements</span>
        <span class="stat-value" id="moves">0</span>
      </div>
    </div>
    <div class="solitaire-actions">
      <button class="btn-action" id="btn-undo">↩ Annuler</button>
      <button class="btn-action btn-new" id="btn-new">🔄 Nouvelle partie</button>
      <button class="btn-action" id="btn-hint">💡 Indice</button>
    </div>
  </div>

  <div class="solitaire-game" id="game">

    <!-- Zone du haut : pioche + fondations -->
    <div class="top-zone">
      <div class="top-left">
        <div class="card-pile" id="deck" title="Cliquez pour piocher">
          <div class="pile-placeholder">🂠</div>
        </div>
        <div class="card-pile" id="waste"></div>
      </div>
      <div class="foundations">
        <div class="card-pile foundation" id="foundation-0" data-suit="♠" title="Pique"></div>
        <div class="card-pile foundation" id="foundation-1" data-suit="♥" title="Coeur"></div>
        <div class="card-pile foundation" id="foundation-2" data-suit="♦" title="Carreau"></div>
        <div class="card-pile foundation" id="foundation-3" data-suit="♣" title="Trèfle"></div>
      </div>
    </div>

    <!-- Colonnes -->
    <div class="tableau">
      <div class="card-pile tableau-col" id="col-0"></div>
      <div class="card-pile tableau-col" id="col-1"></div>
      <div class="card-pile tableau-col" id="col-2"></div>
      <div class="card-pile tableau-col" id="col-3"></div>
      <div class="card-pile tableau-col" id="col-4"></div>
      <div class="card-pile tableau-col" id="col-5"></div>
      <div class="card-pile tableau-col" id="col-6"></div>
    </div>

  </div>

  <!-- Message de victoire -->
  <div class="victory-screen" id="victory" style="display:none;">
    <div class="victory-card">
      <div class="victory-emoji">🎉</div>
      <h2>Bravo !</h2>
      <p>Partie terminée en <strong id="victory-time"></strong></p>
      <p>Score : <strong id="victory-score"></strong> pts — Mouvements : <strong id="victory-moves"></strong></p>
      <button class="btn-action btn-new" onclick="game.newGame()">Rejouer</button>
    </div>
  </div>

</div>

<script src="<?= $BASE_URL ?>/assets/js/solitaire.js"></script>

<?php include(__DIR__ . '/../../includes/footer.php'); ?>
