<footer class="footer">
  <div class="footer-container">

    <div class="footer-columns">
      <div class="footer-col">
        <h3>🎮 À propos</h3>
        <p>
          <strong>Packajeux</strong> est un projet personnel de développement web.<br>
          Plateforme gratuite de mini-jeux en ligne, sans publicité.
        </p>
        <p>
          📧 <a href="mailto:r.monier62@hotmail.com">r.monier62@hotmail.com</a><br>
          🐙 <a href="https://github.com/Romain62300/packajeux" target="_blank" rel="noopener">Projet sur GitHub</a>
        </p>
      </div>

      <div class="footer-col">
        <h3>📝 Informations</h3>
        <p>
          🟢 En développement actif<br>
          💻 Projet open-source (licence CC BY-NC 4.0)<br>
          🔗 <a href="https://www.linkedin.com/in/romainmonier" target="_blank" rel="noopener">LinkedIn du développeur</a>
        </p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>
        © 2025 – Site créé avec ❤️ par
        <a href="https://github.com/Romain62300" target="_blank" rel="noopener">Romain Monier</a>
      </p>

      <div class="footer-links">
        <a href="<?= $BASE_URL ?>/mentions-legales.php">Mentions légales</a> ·
        <a href="<?= $BASE_URL ?>/confidentialite.php">Politique de confidentialité</a>
      </div>
    </div>

  </div>
</footer>

<?php
// Chargement automatique du JS et CSS spécifique à la page (si existant)
$page = basename($_SERVER['PHP_SELF'], ".php");

$jsRelative  = "/assets/js/$page.js";
$cssRelative = "/assets/css/style-$page.css";

$jsAbsolute  = __DIR__ . "/../public" . $jsRelative;
$cssAbsolute = __DIR__ . "/../public" . $cssRelative;

if (file_exists($jsAbsolute)) {
    echo "<script src='{$BASE_URL}{$jsRelative}' defer></script>";
}
if (file_exists($cssAbsolute)) {
    echo "<link rel='stylesheet' href='{$BASE_URL}{$cssRelative}'>";
}
?>
</body>
</html>
