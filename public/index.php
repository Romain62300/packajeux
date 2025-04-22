<?php
require_once __DIR__ . '/../config/config.php';
session_start();
include_once("../includes/header.php"); ?>

<main>
  <h2>Bienvenue sur Gamepack 🎮</h2>
  <p>Explorez nos mini-jeux amusants, accessibles à tous âges !</p>

  <?php if (isset($_SESSION['utilisateur'])): ?>
  <p style="text-align:center; font-weight:bold; color:green;">
    👋 Bonjour <?= htmlspecialchars($_SESSION['utilisateur']['pseudo']) ?> !
  </p>
  <?php else: ?>
  <div class="cta-container">
    <a href="/gamepack/public/login.php" class="btn">🔐 Se connecter</a>
    <a href="/gamepack/public/register.php" class="btn">📝 Créer un compte</a>
  </div>
  <?php endif; ?>

  <div class="games-grid">
    <div class="game-card">
      <img src="/gamepack/public/assets/images/morpion.png" alt="Morpion">
      <h3>Morpion</h3>
      <p>Affrontez un ami ou l’IA dans ce jeu classique en 3x3.</p>
      <a href="/gamepack/public/jeux/morpion.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="/gamepack/public/assets/images/memory.png" alt="Jeu de mémoire">
      <h3>Mémoire</h3>
      <p>Retrouvez les paires d’images en un minimum d’essais !</p>
      <a href="/gamepack/public/jeux/memory.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="/gamepack/public/assets/images/pfc.png" alt="Pierre Feuille Ciseaux">
      <h3>Pierre-Feuille-Ciseaux</h3>
      <p>Jouez contre l’ordinateur à ce grand classique.</p>
      <a href="/gamepack/public/jeux/pfc.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="/gamepack/public/assets/images/devine.png" alt="Devine le nombre">
      <h3>Devine le nombre</h3>
      <p>Un chiffre mystère vous attend. Serez-vous assez malin ?</p>
      <a href="/gamepack/public/jeux/devine.php" class="btn">Jouer</a>
    </div>

    <div class="game-card">
      <img src="/gamepack/public/assets/images/belote.png" alt="Belote">
      <h3>Belote</h3>
      <p>Le jeu de cartes incontournable du Nord au Sud de la France</p>
      <a href="/gamepack/public/jeux/belote.php" class="btn">Jouer</a>
    </div>
  </div>

  <a href="/gamepack/public/jeux.php" class="cta-jeux">
    🎮 Voir tous les jeux
  </a>
</main>

<div class="charte-container">
  <a href="/gamepack/public/docs/charte_jeux_enfants_gamepack.pdf" target="_blank" class="charte-btn">
    📄 Lire la charte des jeux enfants
  </a>
</div>

<?php include_once("../includes/footer.php"); ?>
