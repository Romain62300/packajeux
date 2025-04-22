<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

// Vérification de la connexion
if (!isset($_SESSION['utilisateur'])) {
  header('Location: /gamepack/public/login.php');
  exit;
}

// Exemple : un seul grattage par jour
$userId = $_SESSION['utilisateur']['id'];
$aujourdhui = date('Y-m-d');

// Vérifier si l’utilisateur a déjà joué aujourd’hui
$stmt = $pdo->prepare("SELECT * FROM grattages WHERE user_id = ? AND jeu = 'cochogratt' AND date = ?");
$stmt->execute([$userId, $aujourdhui]);
$grattage = $stmt->fetch();

// Générer un gain si première fois aujourd’hui
if (!$grattage) {
  $gain = rand(0, 5); // gain aléatoire entre 0 et 5 jetons
  $stmt = $pdo->prepare("INSERT INTO grattages (user_id, jeu, date, gain) VALUES (?, 'cochogratt', ?, ?)");
  $stmt->execute([$userId, $aujourdhui, $gain]);

  // Ajouter les jetons au compte de l'utilisateur
  $stmt = $pdo->prepare("UPDATE utilisateurs SET jetons = jetons + ? WHERE id = ?");
  $stmt->execute([$gain, $userId]);
} else {
  $gain = $grattage['gain'];
}
?>

<?php include_once("../../includes/header.php"); ?>
<link rel="stylesheet" href="assets/css/grattage.css">
<script src="assets/js/grattage.js" defer></script>

<main class="grattage-container">
  <h2>🐷 Cochogratt' – Ton jeu quotidien !</h2>
  <p>Gratte la zone pour découvrir ton gain du jour !</p>

  <div class="scratch-zone" id="scratchZone">
    <div class="gain-message" id="gainMessage">
      🎉 Tu as gagné <strong><?= $gain ?></strong> jeton<?= $gain > 1 ? 's' : '' ?> !
    </div>
    <canvas id="scratchCanvas" width="300" height="150"></canvas>
  </div>

  <p class="info">🔁 Un seul grattage par jour autorisé.</p>
</main>

<?php include_once("../../includes/footer.php"); ?>
