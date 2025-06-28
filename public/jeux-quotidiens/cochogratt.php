<?php
require_once __DIR__ . '/../../config/config.php';
session_start();
include_once("../../includes/header.php");
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
    <img src="../assets/images/cochon-danse.gif" alt="Cochon qui danse" style="max-width:100%; height:auto;">
  </div>

  <p><span style="color: blue;"> 📓 </span> Un seul grattage par jour autorisé.</p>
</main>
<link rel="stylesheet" href="/gamepack/public/assets/css/grattage.css">
<script src="/gamepack/public/assets/js/grattage.js"></script>
<?php include_once("../../includes/footer.php"); ?>
