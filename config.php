<?php
// Paramètres de connexion à la base de données
define("DB_SERVER", "localhost");
define("DB_NAME", "boutique_en_ligne");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "");

try {
    // Créer une connexion PDO
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Configurer PDO pour afficher les erreurs
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>