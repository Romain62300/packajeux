<?php
session_start();
require_once __DIR__ . '/../config/config.php';

if (isset($_SESSION['utilisateur'])) {
    $userId = $_SESSION['utilisateur']['id'];
    $stmt = $pdo->prepare("SELECT pseudo, jetons FROM utilisateurs WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    echo "Session active<br>";
    echo "Pseudo : " . htmlspecialchars($user['pseudo']) . "<br>";
    echo "Jetons : 💰 " . $user['jetons'];
} else {
    echo "⚠️ Aucun utilisateur connecté.";
}
