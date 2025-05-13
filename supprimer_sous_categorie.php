<?php
// filepath: c:\wamp64\www\boutique-en-ligne\supprimer_sous_categorie.php

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// Inclure la configuration de la base de données
require_once "config.php";

// Vérifier si un ID de sous-catégorie est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de sous-catégorie invalide.");
}

$idSousCategorie = intval($_GET['id']);

// Supprimer la sous-catégorie
try {
    $sql = "DELETE FROM sous_categorie WHERE id_sous_categorie = :id_sous_categorie";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_sous_categorie', $idSousCategorie, PDO::PARAM_INT);
    $stmt->execute();

    // Rediriger vers la page de gestion des sous-catégories avec un message de succès
    $_SESSION['message'] = "La sous-catégorie a été supprimée avec succès.";
    header("Location: gestion_sous_categorie.php");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression de la sous-catégorie : " . $e->getMessage());
}
?>