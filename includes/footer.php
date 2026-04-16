<footer class="footer">
  <div class="footer-container">

    <div class="footer-columns">
      <div class="footer-col">
        <h3>📍 Adresse</h3>
        <p>
          <strong>Alakachan Dev (auto-entreprise)</strong><br>
          24 Avenue Raoul Briquet, Apt 4<br>
          62300 Lens, France
        </p>
        <p>
          📞 07 72 02 94 68<br>
          📧 <a href="mailto:r.monier62@hotmail.com">r.monier62@hotmail.com</a><br>
          📍 <a href="https://www.google.com/maps/place/24+Avenue+Raoul+Briquet,+Lens" target="_blank"
            rel="noopener">Voir sur Google Maps</a>
        </p>
      </div>

      <div class="footer-col">
        <h3>📝 Disponibilités</h3>
        <p>
          ✅ Sur rendez-vous (mail ou téléphone)<br>
          ⏱️ Réponse garantie sous 24h en semaine<br>
          🔗 <a href="https://github.com/Romain62300/Packajeux" target="_blank" rel="noopener">Voir le projet GitHub</a>
        </p>
      </div>
    </div>

    <div class="footer-bottom">
      <p>
        <img src="<?= $BASE_URL ?>/assets/images/alakachan-logo.png" alt="Alakachan Dev" style="height:30px; vertical-align:middle; margin-right:8px;">
        © 2025 <strong>Alakachan Dev</strong> – Site créé avec ❤️ par
        <a href="https://github.com/Romain62300" target="_blank" rel="noopener">Romain Monier</a>
      </p>

      <div class="footer-links">
        <a href="<?= $BASE_URL ?>/mentions-legales.php">Mentions légales</a> ·
        <a href="<?= $BASE_URL ?>/conditions.php">Conditions d'utilisation</a> ·
        <a href="<?= $BASE_URL ?>/confidentialite.php">Politique de confidentialité</a> ·
        <a href="#">Gestion des cookies</a>
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
