<?php include(__DIR__ . '/../../includes/header.php'); ?>

<div class="container">
  <h1>Jeu de Belote 🃏</h1>

  <p style="background:#fff3cd; padding:15px; border-radius:8px; border-left:4px solid #ffc107; max-width:600px; margin:20px auto; color:#333;">
    <strong>🚧 En cours de développement</strong><br>
    La belote est un jeu complexe (plis, atout, annonces…). Je travaille dessus, reviens bientôt !
  </p>

  <form id="modeForm" style="opacity:0.5; pointer-events:none;">
    <label for="mode">Choisissez un mode de jeu :</label>
    <select id="mode" name="mode">
      <option value="annonces">Classique avec annonces (1501 pts)</option>
      <option value="sans_annonces">Sans annonces (1001 pts)</option>
    </select>
    <button type="button">Démarrer la partie</button>
  </form>
</div>

<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style-belote.css">

<?php include(__DIR__ . '/../../includes/footer.php'); ?>
