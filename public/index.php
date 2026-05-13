<?php
require_once __DIR__ . '/../config/config.php';
// NE PAS redémarrer la session ici, header.php s'en charge déjà
include_once("../includes/header.php");
?>

<main>
  <h2>Bienvenue sur Packajeux 🎮</h2>
  <p>Explorez nos mini-jeux amusants, accessibles à tous âges !</p>

  <?php if (isset($_SESSION['utilisateur'])): ?>
  <p style="text-align:center; font-weight:bold; color:green;">
    👋 Bonjour <?= htmlspecialchars($_SESSION['utilisateur']['pseudo']) ?> !
  </p>
  <?php else: ?>
  <div class="cta-container">
    <a href="<?= $BASE_URL ?>/login.php" class="btn">🔐 Se connecter</a>
    <a href="<?= $BASE_URL ?>/register.php" class="btn">📝 Créer un compte</a>
  </div>
  <?php endif; ?>

  <div class="games-grid">
    <div class="game-card">
      <img src="<?= $BASE_URL ?>/assets/images/morpion.png" alt="Morpion">
      <h3>Morpion</h3>
      <p>Affrontez un ami ou l’IA dans ce jeu classique en 3x3.</p>
      <a href="<?= $BASE_URL ?>/jeux/morpion.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="<?= $BASE_URL ?>/assets/images/memory.png" alt="Jeu de mémoire">
      <h3>Mémoire</h3>
      <p>Retrouvez les paires d’images en un minimum d’essais !</p>
      <a href="<?= $BASE_URL ?>/jeux/memory.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="<?= $BASE_URL ?>/assets/images/pfc.png" alt="Pierre Feuille Ciseaux">
      <h3>Pierre-Feuille-Ciseaux</h3>
      <p>Jouez contre l’ordinateur à ce grand classique.</p>
      <a href="<?= $BASE_URL ?>/jeux/pfc.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="<?= $BASE_URL ?>/assets/images/devine.png" alt="Devine le nombre">
      <h3>Devine le nombre</h3>
      <p>Un chiffre mystère vous attend. Serez-vous assez malin ?</p>
      <a href="<?= $BASE_URL ?>/jeux/devine.php" class="btn">Jouer</a>
    </div>

<div class="game-card">
  <img src="<?= $BASE_URL ?>/assets/images/belote.png" alt="Solitaire">
  <h3>Solitaire</h3>
  <p>Disposez toutes les cartes sur les fondations, du As au Roi !</p>
  <a href="<?= $BASE_URL ?>/jeux/solitaire.php" class="btn">Jouer</a>
</div>



    <div class="game-card">
      <img src="<?= $BASE_URL ?>/assets/images/belote.png" alt="Belote">
      <h3>Belote</h3>
      <p>Le jeu de cartes incontournable du Nord au Sud de la France</p>
      <a href="<?= $BASE_URL ?>/jeux/belote.php" class="btn">Jouer</a>
    </div>
  </div>

  <a href="<?= $BASE_URL ?>/jeux.php" class="cta-jeux">
    🎮 Voir tous les jeux
  </a>
</main>

<div class="charte-container">
  <a href="<?= $BASE_URL ?>/docs/charte_jeux_enfants_packajeux.pdf" target="_blank" class="charte-btn">
    📄 Lire la charte des jeux enfants
  </a>
</div>

<?php include_once("../includes/footer.php"); ?>
