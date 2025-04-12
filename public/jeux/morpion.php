<?php include_once("../../includes/header.php"); ?>

<main>
  <h2>Morpion</h2>
  <p>Affrontez un ami dans ce classique du 3x3 !</p>

  <div id="turnInfo">Tour de : X</div>

  <div class="morpion-board">
    <?php for ($i = 0; $i < 9; $i++): ?>
    <div class="cell" data-index="<?= $i ?>"></div>
    <?php endfor; ?>
  </div>

  <button onclick="resetGame()" id="restart-btn">🔁 Recommencer</button>
</main>

<script src="/gamepack/public/assets/js/morpion.js"></script>

<?php include_once("../../includes/footer.php"); ?>
