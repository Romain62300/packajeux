<?php
session_start();
if (!isset($_SESSION["utilisateur"])) {
    header("Location: login.php");
    exit;
}
?>

<?php include_once("../includes/header.php"); ?>
<main>
  <h2>Bienvenue <?= htmlspecialchars($_SESSION["utilisateur"]["pseudo"]) ?> 👋</h2>
  <p>Vous êtes connecté à votre espace personnel.</p>
</main>
<?php include_once("../includes/footer.php"); ?>
