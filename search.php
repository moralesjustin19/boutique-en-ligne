<?php
// filepath: c:\wamp64\www\boutique-en-ligne\search.php

// Inclure la configuration de la base de données
require_once "config/config.php";

if (isset($_GET['q'])) {
    $searchTerm = htmlspecialchars($_GET['q']); // Échapper les caractères spéciaux pour éviter les injections XSS

    try {
        // Requête pour rechercher les produits correspondant au terme
        $sql = "SELECT id_produit, nom FROM produit WHERE nom LIKE :searchTerm LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les résultats au format JSON
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur lors de la recherche : ' . $e->getMessage()]);
    }
}
?>