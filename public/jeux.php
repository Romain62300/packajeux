<?php include_once("../includes/header.php"); ?>

<main>
  <h2>Nos mini-jeux 🎮</h2>
  <p>Découvrez les jeux disponibles sur Gamepack. De nouveaux jeux seront ajoutés régulièrement !</p>

  <section class="games-grid">

    <!-- Morpion -->
    <div class="game-card">
      <img src="assets/images/morpion.png" alt="Morpion">
      <h3>Morpion</h3>
      <p>Affrontez un ami dans ce classique jeu de réflexion en 3x3.</p>
      <a href="jeux/morpion.php" class="btn">Jouer</a>
    </div>

    <!-- Mémoire -->
    <div class="game-card">
      <img src="assets/images/memory.png" alt="Jeu de mémoire">
      <h3>Mémoire</h3>
      <p>Testez votre mémoire en retrouvant les paires d’images identiques.</p>
      <a href="jeux/memory.php" class="btn">Jouer</a>
    </div>

    <!-- Pierre-Feuille-Ciseaux -->
    <div class="game-card">
      <img src="assets/images/pfc.png" alt="Pierre-Feuille-Ciseaux">
      <h3>Pierre-Feuille-Ciseaux</h3>
      <p>Faites le meilleur choix et battez votre adversaire !</p>
      <a href="jeux/pfc.php" class="btn">Jouer</a>
    </div>

    <!-- Créer un dé -->
    <div class="game-card">
      <img src="assets/images/des.png" alt="Créer un dé">
      <h3>Créer un dé</h3>
      <p>Personnalisez vos propres dés et testez-les facilement.</p>
      <a href="create/des.php" class="btn">Créer</a>
    </div>

    <!-- Devine le nombre -->
    <div class="game-card">
      <img src="assets/images/devine.png" alt="Devine le nombre">
      <h3>Devine le nombre</h3>
      <p>Un jeu de hasard où tu dois deviner le chiffre mystère choisi par l’ordinateur !</p>
      <a href="jeux/devine.php" class="btn">Jouer</a>
    </div>

  </section>

  <!-- Section À venir -->
  <section class="future-games">
    <h2>À venir !</h2>
    <p>De nouveaux jeux seront ajoutés dans les prochaines mises à jour. Quels jeux aimeriez-vous voir ?</p>
  </section>

  <!-- Formulaire de suggestion -->
  <section class="game-suggestions">
    <h3>Proposez un jeu !</h3>
    <form action="submit_suggestion.php" method="POST">
      <label for="suggestion">Quel jeu aimeriez-vous voir ?</label>
      <input type="text" name="suggestion" id="suggestion" required>

      <label for="type">Type de jeu :</label>
      <select name="type" id="type" required>
        <option value="classique">Classique</option>
        <option value="stratégie">Stratégie</option>
        <option value="hasard">Hasard</option>
      </select>

      <button type="submit">Soumettre la suggestion</button>
    </form>
  </section>
</main>

<?php include_once("../includes/footer.php"); ?>
