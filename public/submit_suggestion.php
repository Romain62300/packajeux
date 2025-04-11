<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $suggestion = htmlspecialchars($_POST['suggestion']);
    $type = htmlspecialchars($_POST['type']);

    // Traitement de la suggestion ici (par exemple, sauvegarder dans la base de données ou envoyer par email)
    // Exemple d'enregistrement dans un fichier :
    file_put_contents('suggestions.txt', "Jeu suggéré : $suggestion\nType : $type\n\n", FILE_APPEND);

    echo "Merci pour votre suggestion ! Nous l'examinerons.";
}
?>
