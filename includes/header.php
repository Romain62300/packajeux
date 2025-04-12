<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Gamepack</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/gamepack/public/assets/css/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <nav>
      <div class="brand">
        <img src="/gamepack/public/assets/images/logo.png" alt="Logo Gamepack" style="height: 100px;">
        <h1>Gamepack 🎮</h1>
      </div>

      <!-- Burger menu (visible uniquement sur mobile) -->
      <div class="burger" id="burger">☰</div>

      <!-- Liens de navigation -->
      <ul id="nav-links">
        <li><a href="/gamepack/public/index.php">Accueil</a></li>
        <li><a href="/gamepack/public/jeux/morpion.php">Morpion</a></li>
        <li><a href="/gamepack/public/jeux/memory.php">Mémoire</a></li>
        <li><a href="/gamepack/public/jeux/pfc.php">PFC</a></li>
        <li><a href="/gamepack/public/jeux/devine.php">Devine le nombre</a></li>
        <li><a href="/gamepack/public/create/des.php" class="dice-link">🎲 Créer un dé</a></li>
      </ul>

      <!-- Bouton de mode sombre -->
      <button id="darkModeToggle">🌙 Mode sombre</button>
    </nav>
  </header>
  <script src="/gamepack/public/assets/js/main.js"></script>
