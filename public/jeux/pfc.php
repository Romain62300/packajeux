<?php include_once('../../includes/header.php'); ?>

<main class="pfc-main">
  <h2 class="pfc-title">✊🖐✌️ Pierre Feuille Ciseau</h2>

  <div class="pfc-game">

    <div class="pfc-choices">
      <button onclick="res('Pierre')"  class="pfc-choice-btn" aria-label="Pierre">✊<span>Pierre</span></button>
      <button onclick="res('Feuille')" class="pfc-choice-btn" aria-label="Feuille">📄<span>Feuille</span></button>
      <button onclick="res('Ciseau')"  class="pfc-choice-btn" aria-label="Ciseau">✂️<span>Ciseau</span></button>
    </div>

    <div id="choix-user" class="pfc-chosen"></div>

    <button id="resultBtn" onclick="testing()" class="pfc-result-btn" disabled>
      🎯 Résultat
    </button>

    <div id="resultat" class="pfc-resultat"></div>

    <div id="score" class="pfc-score"></div>

    <button id="resetScoreBtn" class="pfc-reset-btn" title="Remettre le score à zéro">
      🔄 Réinitialiser le score
    </button>

  </div>
</main>

<script src="<?= $BASE_URL ?>/assets/js/pfc.js"></script>

<?php include_once('../../includes/footer.php'); ?>
