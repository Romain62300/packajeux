<?php
require_once __DIR__ . '/../config/config.php';
include_once("../includes/header.php");
?>

<main>
  <h2>Suggestions reçues 💡</h2>

  <?php
  $filepath = __DIR__ . '/suggestions.txt';

  if (file_exists($filepath)) {
    $lines = file($filepath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    if (!empty($lines)) {
      echo "<ul class='suggestion-list'>";
      for ($i = 0; $i < count($lines); $i += 2) {
        $jeu = htmlspecialchars($lines[$i] ?? '');
        $type = htmlspecialchars($lines[$i + 1] ?? '');
        echo "<li><strong>$jeu</strong><br>$type</li>";
      }
      echo "</ul>";
    } else {
      echo "<p>Aucune suggestion pour le moment.</p>";
    }
  } else {
    echo "<p>Le fichier de suggestions est introuvable.</p>";
  }
  ?>
</main>

<?php include_once("../includes/footer.php"); ?>
