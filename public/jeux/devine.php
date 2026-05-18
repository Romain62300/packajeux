<?php
include_once(__DIR__ . "/../../includes/header.php");

if (!isset($_SESSION['nombre_a_deviner'])) {
    $_SESSION['nombre_a_deviner'] = rand(1, 100);
    $_SESSION['tentatives'] = 0;
}

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

$message  = $_SESSION['message'] ?? '';
unset($_SESSION['message']);

$gameWon  = !empty($_SESSION['partie_terminee']);
$attempts = (int)($_SESSION['tentatives'] ?? 0);
?>

<main>
  <h2>Devine le nombre 🔢</h2>
  <p>Un nombre mystère entre <strong>1 et 100</strong> a été choisi… Saurez-vous le trouver ?</p>

  <div id="devineScore" class="devine-score">
    Meilleur score : <strong id="devineBest">—</strong> tentative(s)
  </div>

  <?php if ($gameWon): ?>
  <div class="devine-result-msg"><?= $message ?></div>
  <form method="post" class="devine-replay-form">
    <button name="rejouer" class="devine-btn">🔁 Rejouer</button>
  </form>
  <?php else: ?>
  <form method="post" class="devine-form">
    <input type="number" name="nombre" min="1" max="100" required
           placeholder="Entrez votre nombre" class="devine-input">
    <button type="submit" class="devine-btn">Deviner</button>
  </form>
  <?php if ($message): ?>
  <div class="devine-feedback"><?= $message ?></div>
  <?php endif; ?>
  <div class="devine-attempts-count">
    Tentative<?= $attempts !== 1 ? 's' : '' ?> : <strong><?= $attempts ?></strong>
  </div>
  <?php endif; ?>
</main>

<script>
const DEVINE_CONFIG = {
  won: <?= $gameWon ? 'true' : 'false' ?>,
  attempts: <?= $attempts ?>
};
</script>

<?php include_once(__DIR__ . "/../../includes/footer.php"); ?>
