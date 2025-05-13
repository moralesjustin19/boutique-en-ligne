<?php
// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';

// Connexion DB
require_once "config.php";

// Récupération des produits
try {
    $sql = "SELECT * FROM produit";
    $stmt = $pdo->query($sql);
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des produits : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Gérer les Produits - Senteurs du Monde</title>

  <!-- Bootstrap CSS + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #0D1B2A;
      color: #1B263B;
    }

    .navbar-custom {
      background-color: white;
    }

    .navbar-custom .nav-link,
    .navbar-custom .navbar-brand,
    .navbar-custom .dropdown-toggle {
      color: #0D1B2A !important;
      font-weight: 500;
    }

    .navbar-custom .dropdown-menu {
      background-color:rgb(255, 255, 255)
    }

    .navbar-custom .dropdown-item:hover {
      background-color:rgb(88, 124, 192);
    }

    h1 {
      color: white;
    }

    .btn-success {
      background-color: #1B263B;
      border: none;
    }

    .btn-success:hover {
      background-color: #1B263B;
    }

    .table {
      background-color: #1B263B;
      color: white;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .table thead {
      background-color: #415A77;
    }

    .btn-warning {
      background-color: #1B263B;
      border: none;
    }

    .btn-warning:hover {
      background-color: #e0a800;
    }

    .btn-danger {
      background-color: #dc3545;
      border: none;
    }

    .btn-danger:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-custom border-bottom shadow-sm">
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
        <a href="connexion.php" class="me-3 text-dark text-decoration-none"><i class="bi bi-person"></i> Connexion</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<!-- Contenu -->
<div class="container mt-5">
  <h1 class="text-center mb-4">Gérer les Produits</h1>
  <a href="ajouter_produit.php" class="btn btn-success mb-3"><i class="bi bi-plus-circle me-1"></i>Ajouter un Produit</a>
  <div class="table-responsive">
    <table class="table table-bordered shadow-sm">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nom</th>
          <th>Description</th>
          <th>Prix</th>
          <th>Stock</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($produits as $produit): ?>
        <tr>
          <td><?php echo $produit['id_produit']; ?></td>
          <td><?php echo htmlspecialchars($produit['nom']); ?></td>
          <td><?php echo htmlspecialchars($produit['description']); ?></td>
          <td><?php echo number_format($produit['prix'], 2, ',', ' ') . ' €'; ?></td>
          <td><?php echo $produit['stock']; ?></td>
          <td>
            <a href="modifier_produit.php?id=<?php echo $produit['id_produit']; ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-square"></i></a>
            <a href="supprimer_produit.php?id=<?php echo $produit['id_produit']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');"><i class="bi bi-trash"></i></a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
