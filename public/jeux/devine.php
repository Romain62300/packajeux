<?php
session_start();
include_once("../includes/header.php");

// Générer un nombre aléatoire si ce n'est pas déjà fait
if (!isset($_SESSION['nombre_a_deviner'])) {
    $_SESSION['nombre_a_deviner'] = rand(1, 100);
    $_SESSION['tentatives'] = 0;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $choix = (int) $_POST["nombre"] ?? 0;
    $_SESSION['tentatives']++;

    if ($choix > $_SESSION['nombre_a_deviner']) {
        $message = "🔻 Trop haut ! Essayez un nombre plus petit.";
    } elseif ($choix < $_SESSION['nombre_a_deviner']) {
        $message = "🔺 Trop bas ! Essayez un nombre plus grand.";
    } else {
        $message = "🎉 Bravo ! Vous avez trouvé le nombre <strong>{$_SESSION['nombre_a_deviner']}</strong> en {$_SESSION['tentatives']} tentative(s) !";
        unset($_SESSION['nombre_a_deviner']);
        unset($_SESSION['tentatives']);
    }
}
?>

<main>
  <h2>Devine le nombre 🔢</h2>
  <p>Un nombre mystère entre <strong>1 et 100</strong> a été choisi… Saurez-vous le trouver ?</p>

  <form method="post" style="margin: 20px auto; max-width: 300px;">
    <input type="number" name="nombre" min="1" max="100" required placeholder="Entrez votre nombre"
      style="padding: 10px; width: 100%; border-radius: 6px; border: 1px solid #ccc;">
    <button type="submit" class="btn" style="margin-top: 10px; width: 100%;">Deviner</button>
  </form>

  <?php if ($message): ?>
  <div style="text-align: center; margin-top: 20px; font-weight: bold;"><?= $message ?></div>
  <?php endif; ?>
</main>

<?php include_once("../includes/footer.php"); ?>
