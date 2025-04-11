<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suggestion = htmlspecialchars($_POST['suggestion']);
    $type = htmlspecialchars($_POST['type']);
    $message = "Jeu suggéré : $suggestion\nType : $type\n\n";
    file_put_contents('suggestions.txt', $message, FILE_APPEND);
    echo "<div class='success-message'>✅ Merci pour votre suggestion !</div>";
}
?>
