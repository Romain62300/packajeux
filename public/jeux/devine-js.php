<?php include_once("../../includes/header.php"); ?>

<main class="container">
  <h2>Devine le nombre 🔢 (version JS)</h2>
  <p>Un nombre mystère entre <strong>1 et 100</strong> a été généré... Tente ta chance !</p>

  <div id="game-area">
    <input type="number" id="guess" min="1" max="100" placeholder="Entrez un nombre" required>
    <button id="submitGuess">Deviner</button>

    <div id="feedback" style="margin-top: 1rem; font-weight: bold;"></div>
    <div id="attempts" style="margin-top: 0.5rem;"></div>
    <button id="restart" style="display: none; margin-top: 1rem;">🔁 Rejouer</button>
  </div>
</main>
<script src="/Packajeux/public/assets/js/devine.js"></script>
<link rel="stylesheet" href="/Packajeux/public/assets/css/style-devine.css">

<?php include_once("../../includes/footer.php"); ?>
