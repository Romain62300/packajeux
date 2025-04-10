<?php include_once("../../includes/header.php"); ?>

<main>
  <h2>Créateur de dé personnalisé 🎲</h2>

  <form method="post">
    <label for="faces">Nombre de faces :</label>
    <input type="number" name="faces" min="2" max="20" required>
    <br><br>
    <button type="submit" name="generate">Créer mon dé</button>
  </form>

  <?php
  if (isset($_POST['generate']) && isset($_POST['faces'])) {
    $faces = intval($_POST['faces']);
    echo "<h3>Dé à $faces faces :</h3>";
    echo "<form method='post'>";
    for ($i = 1; $i <= $faces; $i++) {
      echo "Face $i : <input type='text' name='face_$i' required><br>";
    }
    echo "<br><button type='submit' name='roll'>Lancer le dé</button>";
    echo "</form>";
  }

  if (isset($_POST['roll'])) {
    $customFaces = [];
    foreach ($_POST as $key => $value) {
      if (strpos($key, 'face_') === 0) {
        $customFaces[] = $value;
      }
    }
    $rand = $customFaces[array_rand($customFaces)];
    echo "<p>Résultat du lancer : <strong>$rand</strong></p>";
  }
  ?>

</main>

<?php include_once("../../includes/footer.php"); ?>
