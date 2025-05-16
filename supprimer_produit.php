<?php
// filepath: c:\wamp64\www\boutique-en-ligne\supprimer_produit.php

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
require_once "config/config.php";

// Vérifier si un ID de produit est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de produit invalide.");
}

$idProduit = intval($_GET['id']);

// Supprimer le produit
try {
    $sql = "DELETE FROM produit WHERE id_produit = :id_produit";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
    $stmt->execute();

    // Rediriger vers la page de gestion des produits avec un message de succès
    $_SESSION['message'] = "Le produit a été supprimé avec succès.";
    header("Location: gestion_produit.php");
    exit();
} catch (PDOException $e) {
    die("Erreur lors de la suppression du produit : " . $e->getMessage());
}
?>