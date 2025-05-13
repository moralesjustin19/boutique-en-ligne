<?php
// filepath: c:\wamp64\www\boutique-en-ligne\supprimer_categorie.php

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

// Vérifier si un ID de catégorie est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de catégorie invalide.");
}

$idCategorie = intval($_GET['id']);

// Supprimer la catégorie
try {
    // Vérifier si des sous-catégories sont associées à cette catégorie
    $sql = "SELECT COUNT(*) FROM sous_categorie WHERE id_categorie = :id_categorie";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        die("Impossible de supprimer cette catégorie car elle contient des sous-catégories.");
    }

    // Supprimer la catégorie
    $sql = "DELETE FROM categorie WHERE id_categorie = :id_categorie";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
    $stmt->execute();

    // Rediriger vers la page de gestion des catégories avec un message de succès
    $_SESSION['message'] = "La catégorie a été supprimée avec succès.";
    header("Location: gestion_categorie.php");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression de la catégorie : " . $e->getMessage());
}
?>