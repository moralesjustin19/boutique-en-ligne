<?php
// filepath: c:\wamp64\www\boutique-en-ligne\modifier_sous_categorie.php

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

// Vérifier si un ID de sous-catégorie est passé en paramètre
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de sous-catégorie invalide.");
}

$idSousCategorie = intval($_GET['id']);

// Récupérer les informations de la sous-catégorie
try {
    $sql = "SELECT * FROM sous_categorie WHERE id_sous_categorie = :id_sous_categorie";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_sous_categorie', $idSousCategorie, PDO::PARAM_INT);
    $stmt->execute();
    $sousCategorie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$sousCategorie) {
        die("Sous-catégorie introuvable.");
    }

    // Récupérer toutes les catégories pour le formulaire
    $sql = "SELECT * FROM categorie";
    $stmt = $pdo->query($sql);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

// Mettre à jour la sous-catégorie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $idCategorie = intval($_POST['id_categorie']);

    try {
        $sql = "UPDATE sous_categorie SET nom = :nom, id_categorie = :id_categorie WHERE id_sous_categorie = :id_sous_categorie";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
        $stmt->bindValue(':id_sous_categorie', $idSousCategorie, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la page de gestion des sous-catégories avec un message de succès
        $_SESSION['message'] = "La sous-catégorie a été mise à jour avec succès.";
        header("Location: gestion_sous_categorie.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour de la sous-catégorie : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Sous-Catégorie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Modifier une Sous-Catégorie</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la Sous-Catégorie</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($sousCategorie['nom']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="id_categorie" name="id_categorie" required>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id_categorie']; ?>" <?php echo $categorie['id_categorie'] == $sousCategorie['id_categorie'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categorie['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="gestion_sous_categorie.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>