<?php
// filepath: c:\wamp64\www\boutique-en-ligne\modifier_produit.php

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

// Récupérer les informations du produit
try {
    $sqlProduit = "SELECT * FROM produit WHERE id_produit = :id_produit";
    $stmtProduit = $pdo->prepare($sqlProduit);
    $stmtProduit->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
    $stmtProduit->execute();
    $produit = $stmtProduit->fetch(PDO::FETCH_ASSOC);

    if (!$produit) {
        die("Produit introuvable.");
    }

    // Récupérer les catégories et sous-catégories pour le formulaire
    $sqlCategories = "SELECT * FROM categorie";
    $stmtCategories = $pdo->query($sqlCategories);
    $categories = $stmtCategories->fetchAll(PDO::FETCH_ASSOC);

    $sqlSousCategories = "SELECT * FROM sous_categorie";
    $stmtSousCategories = $pdo->query($sqlSousCategories);
    $sousCategories = $stmtSousCategories->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

// Mettre à jour le produit si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $idCategorie = intval($_POST['id_categorie']);
    $idSousCategorie = intval($_POST['id_sous_categorie']);

    try {
        $sql = "UPDATE produit 
                SET nom = :nom, description = :description, prix = :prix, stock = :stock, id_categorie = :id_categorie, id_sous_categorie = :id_sous_categorie 
                WHERE id_produit = :id_produit";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':prix', $prix, PDO::PARAM_STR);
        $stmt->bindValue(':stock', $stock, PDO::PARAM_INT);
        $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
        $stmt->bindValue(':id_sous_categorie', $idSousCategorie, PDO::PARAM_INT);
        $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la page de gestion des produits avec un message de succès
        $_SESSION['message'] = "Le produit a été mis à jour avec succès.";
        header("Location: gestion_produit.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour du produit : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Produit</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Modifier un Produit</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom du Produit</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($produit['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="prix" class="form-label">Prix (€)</label>
                <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="<?php echo htmlspecialchars($produit['prix']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="stock" class="form-label">Stock</label>
                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo htmlspecialchars($produit['stock']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="id_categorie" name="id_categorie" required>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id_categorie']; ?>" <?php echo $categorie['id_categorie'] == $produit['id_categorie'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categorie['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="id_sous_categorie" class="form-label">Sous-Catégorie</label>
                <select class="form-select" id="id_sous_categorie" name="id_sous_categorie" required>
                    <?php foreach ($sousCategories as $sousCategorie): ?>
                        <option value="<?php echo $sousCategorie['id_sous_categorie']; ?>" <?php echo $sousCategorie['id_sous_categorie'] == $produit['id_sous_categorie'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sousCategorie['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="gestion_produit.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>