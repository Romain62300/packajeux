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
      <p>© 2025 <strong>Alakachan Dev</strong> – Site créé avec ❤️ par
        <a href="https://github.com/Romain62300" target="_blank" rel="noopener">Romain Monier</a>
      </p>

      <div class="footer-links">
        <a href="/Packajeux/public/mentions-legales.php">Mentions légales</a> ·
        <a href="/Packajeux/public/conditions.php">Conditions d'utilisation</a> ·
        <a href="/Packajeux/public/confidentialite.php">Politique de confidentialité</a> ·
        <a href="#">Gestion des cookies</a>
      </div>
    </div>

  </div>
</footer>

<?php
// 🔁 Déduction du nom de la page
$page = basename($_SERVER['PHP_SELF'], ".php");

// 🔗 Inclusion JS auto
$jsPath = "/Packajeux/public/assets/js/$page.js";
$jsFile = $_SERVER['DOCUMENT_ROOT'] . $jsPath;
if (file_exists($jsFile)) {
    echo "<script src='$jsPath' defer></script>";
}

// 🎨 Inclusion CSS auto
$cssPath = "/Packajeux/public/assets/css/style-$page.css";
$cssFile = $_SERVER['DOCUMENT_ROOT'] . $cssPath;
if (file_exists($cssFile)) {
    echo "<link rel='stylesheet' href='$cssPath'>";
}
?>
