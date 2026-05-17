<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo = trim($_POST["pseudo"]);
    $email = trim($_POST["email"]);
    $mot_de_passe = trim($_POST["mot_de_passe"]);
    $confirmation = trim($_POST["confirmation"]);

    if (strlen($mot_de_passe) < 6) {
        $message = "❌ Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($mot_de_passe !== $confirmation) {
        $message = "❌ Les mots de passe ne correspondent pas.";
    } else {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (pseudo, email, mot_de_passe) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$pseudo, $email, $hash]);
            $message = "🎉 Compte créé avec succès ! <a href='login.php'>Se connecter</a>";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $message = "❌ Un compte existe déjà avec cet email.";
            } else {
                error_log("Erreur inscription PDO : " . $e->getMessage());
                $message = "❌ Une erreur inattendue s'est produite. Veuillez réessayer.";
            }
        }
    }
}
?>

<?php include_once("../includes/header.php"); ?>
<main>
  <div class="auth-container">
    <h2 class="auth-title">Créer un compte</h2>

    <?php if ($message): ?>
    <p class="auth-error"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post" class="auth-form">
      <input type="text" name="pseudo" placeholder="Pseudo" required>
      <input type="email" name="email" placeholder="Adresse e-mail" required>
      <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
      <input type="password" name="confirmation" placeholder="Confirmer le mot de passe" required>

      <button type="submit" class="btn-login">S'inscrire</button>
    </form>

    <div class="auth-footer">
      Déjà inscrit ? <a href="login.php">Se connecter</a>
    </div>
  </div>
</main>
<?php include_once("../includes/footer.php"); ?>
