<?php
// includes/header.php
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>Gamepack</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/gamepack/public/assets/css/style.css">
  <link rel="stylesheet" href="/gamepack/public/assets/css/dropdown-extra.css">
  <link rel="stylesheet" href="/gamepack/public/assets/css/style_patch.css"> <!-- ✅ patch final ajouté ici -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;700&display=swap" rel="stylesheet">
</head>

<body>
  <header>
    <nav>
      <div class="brand">
        <img src="/gamepack/public/assets/images/logo.png" alt="Logo Gamepack" style="height: 100px;">
        <h1>Gamepack 🎮</h1>
      </div>

      <div class="burger" id="burger">☰</div>

      <ul id="nav-links">
        <li><a href="/gamepack/public/index.php">Accueil</a></li>
        <li class="dropdown">
          <a href="#">Jeux 🎮</a>
          <ul class="dropdown-content">
            <li><a href="/gamepack/public/jeux/morpion.php">❌ Morpion</a></li>
            <li><a href="/gamepack/public/jeux/memory.php">🧠 Mémoire</a></li>
            <li><a href="/gamepack/public/jeux/pfc.php">✊🖐✌ PFC</a></li>
            <li><a href="/gamepack/public/jeux/devine.php">❓ Devine le nombre</a></li>
          </ul>
        </li>
        <li><a href="/gamepack/public/create/des.php" class="dice-link">🎲 Créer un dé</a></li>
      </ul>

      <label class="switch">
        <input type="checkbox" id="darkModeToggle">
        <span class="slider round" title="Changer de thème"></span>
      </label>
    </nav>
  </header>

  <script src="/gamepack/public/assets/js/main.js"></script>
  <script src="/gamepack/public/assets/js/menu.js"></script>
