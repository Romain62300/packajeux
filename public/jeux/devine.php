<?php
include_once(__DIR__ . "/../../includes/header.php");

// Démarrage d'une partie
if (!isset($_SESSION['nombre_a_deviner'])) {
    $_SESSION['nombre_a_deviner'] = rand(1, 100);
    $_SESSION['tentatives'] = 0;
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre'])) {
    $choix = (int) $_POST['nombre'];
    $_SESSION['tentatives']++;

    if ($choix > $_SESSION['nombre_a_deviner']) {
        $_SESSION['message'] = "🔻 Trop haut ! Essayez un nombre plus petit.";
    } elseif ($choix < $_SESSION['nombre_a_deviner']) {
        $_SESSION['message'] = "🔺 Trop bas ! Essayez un nombre plus grand.";
    } else {
        $_SESSION['message'] = "🎉 Bravo ! Vous avez trouvé le nombre <strong>{$_SESSION['nombre_a_deviner']}</strong> en {$_SESSION['tentatives']} tentative(s) !";
        $_SESSION['partie_terminee'] = true;
    }

    header("Location: devine.php");
    exit();
}

if (isset($_POST['rejouer'])) {
    unset($_SESSION['nombre_a_deviner'], $_SESSION['tentatives'], $_SESSION['message'], $_SESSION['partie_terminee']);
    header("Location: devine.php");
    exit();
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style-devine.css">

<main>
  <h2>Devine le nombre 🔢</h2>
  <p>Un nombre mystère entre <strong>1 et 100</strong> a été choisi… Saurez-vous le trouver ?</p>

  <?php if (!empty($_SESSION['partie_terminee'])): ?>
  <div style="text-align:center; font-weight:bold;"><?= $message ?></div>
  <form method="post" style="text-align:center;">
    <button name="rejouer" class="btn">🔁 Rejouer</button>
  </form>
  <?php else: ?>
  <form method="post" style="margin: 20px auto; max-width: 300px;">
    <input type="number" name="nombre" min="1" max="100" required placeholder="Entrez votre nombre"
      style="padding: 10px; width: 100%; border-radius: 6px; border: 1px solid #ccc;">
    <button type="submit" class="btn" style="margin-top: 10px; width: 100%;">Deviner</button>
  </form>
  <?php if ($message): ?>
  <div style="text-align:center; font-weight:bold;"><?= $message ?></div>
  <?php endif; ?>
  <?php endif; ?>
</main>

<?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
