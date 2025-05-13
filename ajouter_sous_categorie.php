<?php
// filepath: c:\wamp64\www\boutique-en-ligne\admin\ajouter_sous_categorie.php

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

// Récupérer les catégories pour le formulaire
try {
    $sql = "SELECT * FROM categorie";
    $stmt = $pdo->query($sql);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des catégories : " . $e->getMessage());
}

// Ajouter une sous-catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $idCategorie = intval($_POST['id_categorie']);

    try {
        $sql = "INSERT INTO sous_categorie (nom, id_categorie) VALUES (:nom, :id_categorie)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':id_categorie', $idCategorie, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la page de gestion des sous-catégories
        header("Location: gestion_sous_categorie.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout de la sous-catégorie : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Sous-Catégorie</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Ajouter une Sous-Catégorie</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="nom" class="form-label">Nom de la Sous-Catégorie</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="mb-3">
                <label for="id_categorie" class="form-label">Catégorie</label>
                <select class="form-select" id="id_categorie" name="id_categorie" required>
                    <option value="">Sélectionnez une catégorie</option>
                    <?php foreach ($categories as $categorie): ?>
                        <option value="<?php echo $categorie['id_categorie']; ?>"><?php echo htmlspecialchars($categorie['nom']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
            <a href="gestion_sous_categorie.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>