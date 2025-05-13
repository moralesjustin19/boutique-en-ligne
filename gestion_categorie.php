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
require_once "config.php";

// Récupérer toutes les catégories
try {
    $sql = "SELECT * FROM categorie";
    $stmt = $pdo->query($sql);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des catégories : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Catégories</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Style global */
        body {
            background-color: #f4f5f7; /* Gris clair */
            font-family: 'Arial', sans-serif;
        }

        /* Navbar */
        .navbar {
            background-color: #ffffff; /* Barre de navigation blanche */
            color: #333;
        }

        .navbar-brand img {
            width: 80px;
        }

        .navbar .navbar-nav .nav-link {
            color: #333;
        }

        .navbar .navbar-nav .nav-link:hover {
            color: #FF9900;
        }

        /* Table */
        .table {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .table thead {
            background-color: #2C3E50;
            color: #fff;
        }

        .table th, .table td {
            padding: 12px;
            text-align: center;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Boutons */
        .btn-success {
            background-color:rgb(15, 25, 85);
            border: none;
            font-weight: bold;
        }

        .btn-success:hover {
            background-color: #218838;
        }

        .btn-warning {
            background-color:rgba(5, 11, 68, 0.74);
            border: none;
            font-weight: bold;
        }

        .btn-warning:hover {
            background-color: #e0a800;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
            font-weight: bold;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Titre principal */
        h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 40px;
        }
    </style>
</head>
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo.png.png" alt="Senteurs du Monde" width="80">
    </a>
    <div class="d-flex align-items-center">
      <?php if($estConnecte): ?>
        <div class="dropdown">
          <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person me-1"></i><?php echo htmlspecialchars($nomUtilisateur); ?>
          </button>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
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
        <a href="connexion.php" class="me-3 text-dark text-decoration-none"><i class="bi bi-person"></i> Connexion</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Gérer les Catégories</h1>
        <a href="ajouter_categorie.php" class="btn btn-success mb-3">Ajouter une Catégorie</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $categorie): ?>
                    <tr>
                        <td><?php echo $categorie['id_categorie']; ?></td>
                        <td><?php echo htmlspecialchars($categorie['nom']); ?></td>
                        <td>
                            <a href="modifier_categorie.php?id=<?php echo $categorie['id_categorie']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                            <a href="supprimer_categorie.php?id=<?php echo $categorie['id_categorie']; ?>" class="btn btn-danger btn-sm">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
