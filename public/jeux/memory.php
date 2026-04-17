<?php include_once(__DIR__ . "/../../includes/header.php"); ?>

<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style-memory.css">

<main>
  <h2>Jeu de Mémoire 🧠</h2>
  <p>Retrouvez les paires d'animaux en un minimum de coups !</p>

  <div class="memory-stats">
    <span>⏱️ Temps : <strong id="memoryTime">0s</strong></span>
    <span>🎯 Coups : <strong id="memoryMoves">0</strong></span>
    <span>✅ Paires : <strong id="memoryPairs">0 / 8</strong></span>
  </div>

  <div class="memory-board" id="memoryBoard"></div>

  <div class="memory-controls">
    <button onclick="resetMemory()" class="btn">🔁 Nouvelle partie</button>
  </div>

  <div id="memoryWin" class="memory-win" style="display:none;">
    🎉 Bravo ! Partie terminée en <strong id="winMoves">0</strong> coups et <strong id="winTime">0s</strong> !
  </div>
</main>

<script>
  const MEMORY_BASE_URL = "<?= $BASE_URL ?>";
</script>
<script src="<?= $BASE_URL ?>/assets/js/memory.js"></script>

<?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
