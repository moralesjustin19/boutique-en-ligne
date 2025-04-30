<?php
// filepath: c:\wamp64\www\boutique-en-ligne\ajouter_panier.php

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si un produit est envoyé via GET
if (isset($_GET['id_produit'])) {
    $idProduit = intval($_GET['id_produit']);

    // Initialiser le panier s'il n'existe pas
    if (!isset($_SESSION['panier'])) {
        $_SESSION['panier'] = [];
    }

    // Ajouter le produit au panier ou incrémenter la quantité
    if (!isset($_SESSION['panier'][$idProduit])) {
        $_SESSION['panier'][$idProduit] = 1; // Ajouter avec une quantité de 1
    } else {
        $_SESSION['panier'][$idProduit]++; // Incrémenter la quantité
    }

    // Définir un message de confirmation dans la session
    $_SESSION['message'] = "Produit ajouté au panier !";

    // Rediriger vers la page précédente ou la page panier
    header("Location: index.php");
    exit();
}
?>