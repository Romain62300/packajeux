<?php
include_once(__DIR__ . "/../../includes/header.php");
?>
<main class="grattage-container">
  <h2>🐖 Cochogratt' — Ton jeu quotidien !</h2>
  <p>Gratte la zone pour découvrir ton gain du jour !</p>

  <div class="scratch-container">
    <canvas id="scratchCanvas"></canvas>
    <div id="gainMessage" style="display:none;">
      🎉 Tu as gagné 1 jeton !
    </div>
  </div>

  <div id="cochonDance" style="display:none; margin-top:20px;">
    <div class="tenor-gif-embed" data-postid="659959108264028199" data-share-method="host" data-aspect-ratio="0.875"
      data-width="100%">

        href="https://tenor.com/view/dancing-pig-piggyverse-piggyverse-dancing-piggy-dancing-dancing-gif-659959108264028199">
        Dancing Pig Piggyverse GIF
      </a>
      from <a href="https://tenor.com/search/dancing+pig-gifs">Dancing Pig GIFs</a>
    </div>
  </div>

  <p><span style="color: blue;"> 📓 </span> Un seul grattage par jour autorisé.</p>
</main>

<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/grattage.css">
<script src="<?= $BASE_URL ?>/assets/js/grattage.js"></script>
<script type="text/javascript" async src="https://tenor.com/embed.js"></script>

<?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
