<?php
// Démarre la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// BASE_URL en dur (local WAMP)
$BASE_URL = '/packajeux/public';

// Charge la configuration (doit définir $pdo)
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>Packajeux</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Styles -->
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style.css?v=1.0.3">
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/dropdown-extra.css">
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style_patch.css">
  <link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style-belote.css">

  <!-- Police Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <nav>
      <!-- Logo + Titre -->
      <div class="brand">
        <img src="<?= $BASE_URL ?>/assets/images/logo.png" alt="Logo Packajeux" style="height:100px;">
        <h1>Packajeux 🎮</h1>
      </div>

      <!-- Menu burger mobile -->
      <div class="burger" id="burger">☰</div>

      <!-- Liens de navigation -->
      <ul id="nav-links">
        <li><a href="<?= $BASE_URL ?>/index.php">Accueil</a></li>

        <li class="dropdown">
          <a href="#">Jeux 🎮</a>
          <ul class="dropdown-content">
            <li><a href="<?= $BASE_URL ?>/jeux/morpion.php">❌ Morpion</a></li>
            <li><a href="<?= $BASE_URL ?>/jeux/memory.php">🧠 Mémoire</a></li>
            <li><a href="<?= $BASE_URL ?>/jeux/pfc.php">✊🖐✌ PFC</a></li>
            <li><a href="<?= $BASE_URL ?>/jeux/devine.php">❓ Devine le nombre</a></li>
            <li><a href="<?= $BASE_URL ?>/jeux/belote.php">🎴 Belote</a></li>
          </ul>
        </li>

        <li><a href="<?= $BASE_URL ?>/create/des.php" class="dice-link">🎲 Créer un dé</a></li>
        <li><a href="<?= $BASE_URL ?>/jeux-quotidiens.php">🗓️ Jeux quotidiens</a></li>

        <?php if (!empty($_SESSION['utilisateur'])): ?>
        <li><a href="<?= $BASE_URL ?>/logout.php">🔓 Déconnexion</a></li>
        <?php else: ?>
        <li><a href="<?= $BASE_URL ?>/login.php">🔐 Connexion</a></li>
        <?php endif; ?>
      </ul>

      <!-- Pseudo + jetons -->
      <?php
      if (!empty($_SESSION['utilisateur'])) {
        $userId = (int)($_SESSION['utilisateur']['id'] ?? 0);
        if ($userId > 0) {
          $stmt = $pdo->prepare("SELECT pseudo, jetons FROM utilisateurs WHERE id = ?");
          $stmt->execute([$userId]);
          if ($user = $stmt->fetch()) {
            echo '<div class="user-info" style="
              background-color:#ff69b4;color:#fff;padding:8px 14px;border-radius:12px;
              margin-left:20px;box-shadow:0 0 15px #ff69b4;font-weight:bold;">
              Bonjour ' . htmlspecialchars($user['pseudo']) . ' | Jetons : 💰 <strong>' . (int)$user['jetons'] . '</strong>
            </div>';
          }
        }
      }
      ?>

      <!-- Dark mode switch -->
      <label class="switch">
        <input type="checkbox" id="darkModeToggle">
        <span class="slider round" title="Changer de thème"></span>
      </label>
    </nav>
  </header>

  <!-- Scripts -->
  <script src="<?= $BASE_URL ?>/assets/js/main.js"></script>
  <script src="<?= $BASE_URL ?>/assets/js/menu.js"></script>