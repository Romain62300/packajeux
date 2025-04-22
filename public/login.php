<?php
// Démarre une session pour suivre l'utilisateur connecté
session_start();
require_once __DIR__ . '/../config/config.php';

// Initialisation du message d'erreur
$erreur = "";

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $mot_de_passe = $_POST["mot_de_passe"];

    // Requête préparée pour récupérer l'utilisateur par email
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    // Vérifie si un utilisateur a été trouvé et si le mot de passe est correct
    if ($utilisateur && password_verify($mot_de_passe, $utilisateur["mot_de_passe"])) {
        $_SESSION["utilisateur"] = $utilisateur;

        // Gère l'option "Se souvenir de moi" avec un cookie
        if (!empty($_POST['remember'])) {
            setcookie("email", $email, time() + (86400 * 30), "/"); // 30 jours
        }

        // Redirige vers le tableau de bord
        header("Location: dashboard.php");
        exit;
    } else {
        // Message d'erreur si authentification échouée
        $erreur = "Email ou mot de passe incorrect.";
    }
}
?>

<?php include_once("../includes/header.php"); ?>

<main class="auth-container">
  <h2 class="auth-title">Connexion</h2>

  <?php if ($erreur): ?>
  <p class="auth-error"><?= htmlspecialchars($erreur) ?></p>
  <?php endif; ?>

  <form method="post" class="auth-form">
    <!-- Champ email -->
    <label for="email">Adresse e-mail</label>
    <input type="email" name="email" id="email" placeholder="votre@email.com"
      value="<?= isset($_COOKIE['email']) ? htmlspecialchars($_COOKIE['email']) : '' ?>" required>

    <!-- Champ mot de passe -->
    <label for="mot_de_passe">Mot de passe</label>
    <input type="password" name="mot_de_passe" id="mot_de_passe" placeholder="Mot de passe" required>

    <!-- Options supplémentaires -->
    <div class="auth-options">
      <label><input type="checkbox" name="remember"> Se souvenir de moi</label>
      <a href="#">Mot de passe oublié ?</a>
    </div>

    <!-- Bouton de connexion -->
    <button type="submit" class="btn btn-login">Se connecter</button>

    <!-- Lien vers l'inscription -->
    <p class="auth-footer">Pas encore de compte ? <a href="register.php">Créer un compte</a></p>
  </form>
</main>

<?php include_once("../includes/footer.php"); ?>
