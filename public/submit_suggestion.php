<?php
require_once __DIR__ . '/../config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suggestion = htmlspecialchars($_POST['suggestion']);
    $type = htmlspecialchars($_POST['type']);

    // Sauvegarde dans le fichier
    $message = "Jeu suggéré : $suggestion\nType : $type\n\n";
    file_put_contents('suggestions.txt', $message, FILE_APPEND);

    // ✅ Redirection vers jeux.php avec message
    header("Location: jeux.php?success=1");
    exit;
}
?>
