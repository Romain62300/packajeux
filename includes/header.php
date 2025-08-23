<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once __DIR__ . '/../config/config.php';

/**
 * Base URL de l'app :
 * - si tu es sous WAMP dans http://localhost/packajeux/ => "/packajeux"
 * - si tu es sous php -S lancé dans /public               => ""
 */
$projectFolder = basename(dirname(__DIR__)); // "packajeux"
$scriptName    = $_SERVER['SCRIPT_NAME'] ?? '/';
$BASE_URL      = (strpos($scriptName, "/$projectFolder/") === 0) ? "/$projectFolder" : "";
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <title>Packajeux</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- La balise base fixe la racine pour TOUS les liens relatifs ci-dessous -->
  <base href="<?= htmlspecialchars($BASE_URL ?: '/') ?>">

  <!-- Styles (chemins relatifs à la <base>) -->
  <link rel="stylesheet" href="assets/css/style.css?v=1.0.3">
  <link rel="stylesheet" href="assets/css/dropdown-extra.css">
  <link rel="stylesheet" href="assets/css/style_patch.css">
  <link rel="stylesheet" href="assets/css/style-belote.css">

  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <nav>
      <div class="brand">
        <img src="assets/images/logo.png" alt="Logo Packajeux" style="height:100px;">
        <h1>Packajeux 🎮</h1>
      </div>

      <div class="burger" id="burger">☰</div>

      <ul id="nav-links">
        <li><a href="index.php">Accueil</a></li>
        <li class="dropdown">
          <a href="#">Jeux 🎮</a>
          <ul class="dropdown-content">
            <li><a href="jeux/morpion.php">❌ Morpion</a></li>
            <li><a href="jeux/memory.php">🧠 Mémoire</a></li>
            <li><a href="jeux/pfc.php">✊🖐✌ PFC</a></li>
            <li><a href="jeux/devine.php">❓ Devine le nombre</a></li>
            <li><a href="jeux/belote.php">🎴 Belote</a></li>
          </ul>
        </li>
        <li><a href="create/des.php" class="dice-link">🎲 Créer un dé</a></li>
        <li><a href="jeux-quotidiens.php">🗓️ Jeux quotidiens</a></li>

        <?php if (!empty($_SESSION['utilisateur'])): ?>
        <li><a href="logout.php">🔓 Déconnexion</a></li>
        <?php else: ?>
        <li><a href="login.php">🔐 Connexion</a></li>
        <?php endif; ?>
      </ul>

      <?php
      if (!empty($_SESSION['utilisateur'])) {
        $userId = $_SESSION['utilisateur']['id'] ?? null;
        if ($userId) {
          $stmt = $pdo->prepare("SELECT pseudo, jetons FROM utilisateurs WHERE id = ?");
          $stmt->execute([$userId]);
          if ($user = $stmt->fetch()) {
            echo '<div class="user-info" style="
              background:#ff69b4;color:#fff;padding:8px 14px;border-radius:12px;
              margin-left:20px;box-shadow:0 0 15px #ff69b4;font-weight:bold;">
              Bonjour ' . htmlspecialchars($user['pseudo']) .
              ' | Jetons : 💰 <strong>' . (int)$user['jetons'] . '</strong></div>';
          }
        }
      }
      ?>

      <label class="switch">
        <input type="checkbox" id="darkModeToggle">
        <span class="slider round" title="Changer de thème"></span>
      </label>
    </nav>
  </header>

  <script src="assets/js/main.js"></script>
  <script src="assets/js/menu.js"></script>