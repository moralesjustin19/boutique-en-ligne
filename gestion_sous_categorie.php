<?php
// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';
?>

<?php
// filepath: c:\wamp64\www\boutique-en-ligne\search.php

// Inclure la configuration de la base de données
require_once "config/config.php";

// Récupérer toutes les sous-catégories
try {
    $sql = "SELECT sous_categorie.id_sous_categorie, sous_categorie.nom AS sous_categorie_nom, categorie.nom AS categorie_nom 
            FROM sous_categorie 
            INNER JOIN categorie ON sous_categorie.id_categorie = categorie.id_categorie";
    $stmt = $pdo->query($sql);
    $sousCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des sous-catégories : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Sous-Catégories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #001f3f; /* Bleu nuit */
            color: #ffffff; /* Blanc */
        }
        .navbar {
            background-color: #ffffff; /* Blanc */
        }
        .navbar a {
            color: #001f3f !important; /* Bleu nuit pour les liens */
        }
        .dropdown-toggle {
            color: #000000 !important; /* Adresse e-mail en noir */
        }
        .dropdown-menu {
            background-color: #ffffff; /* Fond blanc pour le menu déroulant */
        }
        .dropdown-menu a {
            color: #000000 !important; /* Texte noir dans le menu déroulant */
        }
        .btn {
            color: #ffffff;
        }
        .btn-success {
            background-color: #007bff; /* Bleu */
            border-color: #007bff;
        }
        .btn-warning {
            background-color:rgb(9, 12, 82); /* Jaune */
            border-color:rgb(0, 0, 0);
        }
        .btn-danger {
            background-color: #ff4136; /* Rouge */
            border-color:rgb(0, 0, 0);
        }
        table {
            background-color: #00264d; /* Bleu nuit plus clair */
            border: 2px solid #ffffff; /* Contour blanc */
            border-radius: 15px; /* Bord arrondi */
            overflow: hidden; /* Pour éviter que le contenu dépasse les bords arrondis */
        }
        th, td {
            color: #ffffff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="assets/logo.png.png" alt="Senteurs du Monde" width="80">
            </a>
            <div class="d-flex align-items-center">
                <?php if($estConnecte): ?>
                    <div class="dropdown">
                        <button class="btn btn-link dropdown-toggle text-white text-decoration-none" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($nomUtilisateur); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="profil.php"><i class="bi bi-person-circle me-2"></i>Mon profil</a></li>
                            <li><a class="dropdown-item" href="commandes.php"><i class="bi bi-receipt me-2"></i>Mes commandes</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="deconnexion.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="connexion.php" class="me-3 text-white text-decoration-none"><i class="bi bi-person"></i> Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4">Gérer les Sous-Catégories</h1>
        <a href="ajouter_sous_categorie.php" class="btn btn-success mb-3">Ajouter une Sous-Catégorie</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom de la Sous-Catégorie</th>
                    <th>Catégorie</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sousCategories as $sousCategorie): ?>
                    <tr>
                        <td><?php echo $sousCategorie['id_sous_categorie']; ?></td>
                        <td><?php echo htmlspecialchars($sousCategorie['sous_categorie_nom']); ?></td>
                        <td><?php echo htmlspecialchars($sousCategorie['categorie_nom']); ?></td>
                        <td>
                            <a href="modifier_sous_categorie.php?id=<?php echo $sousCategorie['id_sous_categorie']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="supprimer_sous_categorie.php?id=<?php echo $sousCategorie['id_sous_categorie']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>