<?php
// filepath: c:\wamp64\www\boutique-en-ligne\supprimer_panier.php

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si un produit est envoyé via GET
if (isset($_GET['id_produit'])) {
    $idProduit = intval($_GET['id_produit']);

    // Supprimer le produit du panier
    if (isset($_SESSION['panier'][$idProduit])) {
        unset($_SESSION['panier'][$idProduit]);
    }

    // Rediriger vers la page panier
    header("Location: panier.php");
    exit();
}
?>