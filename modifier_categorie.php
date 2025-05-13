<?php
// filepath: c:\wamp64\www\boutique-en-ligne\modifier_categorie.php

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

// Récupérer les informations de la catégorie
try {
    $sql = "SELECT * FROM categorie WHERE id_categorie = :id_categorie";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
    $stmt->execute();
    $categorie = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categorie) {
        die("Catégorie introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

// Mettre à jour la catégorie si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);

    try {
        $sql = "UPDATE categorie SET nom = :nom WHERE id_categorie = :id_categorie";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la page de gestion des catégories avec un message de succès
        $_SESSION['message'] = "La catégorie a été mise à jour avec succès.";
        header("Location: gestion_categorie.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour de la catégorie : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une Catégorie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Modifier une Catégorie</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la Catégorie</label>
                <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($categorie['nom']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
            <a href="gestion_categorie.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>