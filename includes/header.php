<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Gamepack</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Styles -->
  <link rel="stylesheet" href="/gamepack/public/assets/css/style.css">
  <link rel="stylesheet" href="/gamepack/public/assets/css/dropdown-extra.css">
  <link rel="stylesheet" href="/gamepack/public/assets/css/style_patch.css">
  <link rel="stylesheet" href="/gamepack/public/assets/css/style-belote.css">
  <!-- Police Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <nav>
      <!-- Logo + Titre -->
      <div class="brand">
        <img src="/gamepack/public/assets/images/logo.png" alt="Logo Gamepack" style="height: 100px;">
        <h1>Gamepack 🎮</h1>
      </div>

      <!-- Menu burger mobile -->
      <div class="burger" id="burger">☰</div>

      <!-- Liens de navigation -->
      <ul id="nav-links">
        <li><a href="/gamepack/public/index.php">Accueil</a></li>
        <li class="dropdown">
          <a href="#">Jeux 🎮</a>
          <ul class="dropdown-content">
            <li><a href="/gamepack/public/jeux/morpion.php">❌ Morpion</a></li>
            <li><a href="/gamepack/public/jeux/memory.php">🧠 Mémoire</a></li>
            <li><a href="/gamepack/public/jeux/pfc.php">✊🖐✌ PFC</a></li>
            <li><a href="/gamepack/public/jeux/devine.php">❓ Devine le nombre</a></li>
            <li><a href="/gamepack/public/jeux/belote.php">🎴 Belote</a></li>

          </ul>
        </li>
        <li><a href="/gamepack/public/create/des.php" class="dice-link">🎲 Créer un dé</a></li>
        <li><a href="/gamepack/public/jeux-quotidiens.php">🗓️ Jeux quotidiens</a></li>
        <!-- Connexion / Déconnexion -->
        <?php if (isset($_SESSION['utilisateur'])): ?>
        <li><a href="/gamepack/public/logout.php">🔓 Déconnexion</a></li>
        <?php else: ?>
        <li><a href="/gamepack/public/login.php">🔐 Connexion</a></li>
        <?php endif; ?>
      </ul>

      <!-- Affichage du pseudo + jetons -->
      <?php
      require_once __DIR__ . '/../config/config.php';

      if (isset($_SESSION['utilisateur'])) {
          $userId = $_SESSION['utilisateur']['id'];
          $stmt = $pdo->prepare("SELECT pseudo, jetons FROM utilisateurs WHERE id = ?");
          $stmt->execute([$userId]);
          $user = $stmt->fetch();

          if ($user) {
              echo '<div class="user-info" style="
                  background-color: #ff69b4;
                  color: white;
                  padding: 8px 14px;
                  border-radius: 12px;
                  margin-left: 20px;
                  box-shadow: 0 0 15px #ff69b4;
                  font-weight: bold;
                  transition: all 0.3s ease;
              ">';
              echo 'Bonjour ' . htmlspecialchars($user['pseudo']) . ' | Jetons : 💰 <strong>' . $user['jetons'] . '</strong>';
              echo '</div>';
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

  <!-- Scripts JS -->
  <script src="/gamepack/public/assets/js/main.js"></script>
  <script src="/gamepack/public/assets/js/menu.js"></script>
