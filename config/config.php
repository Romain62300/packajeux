<?php
// Configuration pour Packajeux (version locale)

// 🌐 En local (WAMP/XAMPP)
$host = 'localhost';
$dbname = 'Packajeux'; // ⚠️ À modifier si tu choisis un autre nom de base
$username = 'root';
$password = ''; // vide en local
// Base URL de ton app (en local)
$BASE_URL = '/packajeux/public';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // ✅ placé au bon endroit
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>