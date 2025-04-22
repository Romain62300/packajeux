<?php
session_start();
include_once __DIR__ . '/../includes/header.php';
?>

<main class="auth-container">
  <h2 class="auth-title">🎯 Jeux Quotidiens</h2>
  <p>Gagnez des <strong>jetons virtuels</strong> chaque jour en jouant à nos jeux quotidiens !</p>

  <ul class="daily-games">
    <li><a href="jeux-quotidiens/cochogratt.php">🐷 Grattage Cochon</a></li>
    <li><a href="jeux-quotidiens/ecogratt.php">🌳 Grattage Écolo</a></li>
    <li><a href="jeux-quotidiens/smilegratt.php">😄 SmileGratt'</a></li>
    <li><a href="jeux-quotidiens/vipgratt.php">👑 Grattage VIP</a></li>
    <li><a href="jeux-quotidiens/roue.php">🎡 Roue</a></li>
    <li><a href="jeux-quotidiens/miniloto.php">🎟️ Mini Loterie</a></li>
    <li><a href="jeux-quotidiens/coffre.php">🔐 Coffre</a></li>
  </ul>
</main>

<?php
include_once __DIR__ . '/../includes/footer.php';
?>
