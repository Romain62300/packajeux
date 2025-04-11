<?php include_once("../includes/header.php"); ?>

<main>
  <h2>Faites-nous part de vos suggestions !</h2>
  <p>De nouveaux jeux seront ajoutés dans les prochaines mises à jour. Quels jeux aimeriez-vous voir par la suite ?</p>

  <form action="submit_suggestion.php" method="POST">
    <label for="suggestion">Quel jeu aimeriez-vous voir ?</label>
    <input type="text" name="suggestion" id="suggestion" required>

    <label for="type">Type de jeu :</label>
    <select name="type" id="type" required>
      <option value="classique">Classique</option>
      <option value="stratégie">Stratégie</option>
      <option value="hasard">Hasard</option>
      <!-- Ajouter d'autres types si nécessaire -->
    </select>

    <button type="submit">Soumettre la suggestion</button>
  </form>
</main>

<?php include_once("../includes/footer.php"); ?>
