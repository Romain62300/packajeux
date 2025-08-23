<?php
$mode = $_GET['mode'] ?? '2players';
$script = $mode === 'ia' ? 'morpion-ia.js' : 'morpion.js';

include_once("../../includes/header.php");
?>

<main>
  <h2>Morpion</h2>
  <p>Affrontez un ami dans ce classique du 3x3 !</p>

  <div id="turnInfo">Tour de : X</div>

  <div class="morpion-board">
    <?php for ($i = 0; $i < 9; $i++): ?>
    <div class="cell" data-index="<?= $i ?>"></div>
    <?php endfor; ?>
  </div>

  <button onclick="resetGame()" id="restart-btn" class="btn">🔁 Recommencer</button>

  <p style="margin-top: 10px;">
    <strong>Mode actuel :</strong>
    <?= $mode === 'ia' ? 'Contre l’ordinateur' : '2 Joueurs' ?>
  </p>
  <div class="game-options">
    <a href="?mode=ia" class="btn">Mode IA</a>
    <a href="?mode=2players" class="btn">2 Joueurs</a>
  </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script src="/Packajeux/public/assets/js/<?= $script ?>"></script>

<?php include_once("../../includes/footer.php"); ?>
