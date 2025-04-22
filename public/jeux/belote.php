<?php include '../../includes/header.php'; ?>

<div class="container">
  <h1>Jeu de Belote 🃏</h1>

  <form id="modeForm">
    <label for="mode">Choisissez un mode de jeu :</label>
    <select id="mode" name="mode">
      <option value="annonces">Classique avec annonces (1501 pts)</option>
      <option value="sans_annonces">Sans annonces (1001 pts)</option>
    </select>
    <button type="button" onclick="startBelote()">Démarrer la partie</button>
  </form>

  <div id="tableBelote">
    <p>En attente de lancement...</p>
    <div class="cartes-dos">
      <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
      <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
      <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
      <img src="/gamepack/public/assets/images/cartes/bleu-foncé.png" alt="Dos de carte">
    </div>
  </div>
</div>

<?php include '../../includes/footer.php'; ?>
<link rel="stylesheet" href="/gamepack/public/assets/css/style-belote.css">
<script src="/gamepack/public/assets/js/script-belote.js" defer></script>
