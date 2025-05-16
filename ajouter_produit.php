<?php
// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once "config/config.php";

try {
    $categories = $pdo->query("SELECT * FROM categorie")->fetchAll(PDO::FETCH_ASSOC);
    $sousCategories = $pdo->query("SELECT * FROM sous_categorie")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $prix = floatval($_POST['prix']);
    $stock = intval($_POST['stock']);
    $idCategorie = intval($_POST['id_categorie']);
    $idSousCategorie = intval($_POST['id_sous_categorie']);

    try {
        $stmt = $pdo->prepare("
            INSERT INTO produit (nom, description, prix, stock, id_categorie, id_sous_categorie) 
            VALUES (:nom, :description, :prix, :stock, :id_categorie, :id_sous_categorie)
        ");
        $stmt->execute([
            ':nom' => $nom,
            ':description' => $description,
            ':prix' => $prix,
            ':stock' => $stock,
            ':id_categorie' => $idCategorie,
            ':id_sous_categorie' => $idSousCategorie
        ]);
        $_SESSION['message'] = "Le produit a été ajouté avec succès.";
        header("Location: gestion_produit.php");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de l'ajout du produit : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ajouter un Produit</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0D1B2A;
      color: white;
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
      background-color: #1B263B;
    }

    .navbar-custom .dropdown-item {
      color: white;
    }

    .navbar-custom .dropdown-item:hover {
      background-color: #324966;
    }

    .form-label {
      color: white;
    }

    .form-control,
    .form-select {
      background-color: #1B263B;
      border: 1px solid #415A77;
      color: white;
    }

    .form-control::placeholder {
      color: #ccc;
    }

    .form-control:focus,
    .form-select:focus {
      border-color: #778DA9;
      box-shadow: 0 0 0 0.2rem rgba(65, 90, 119, 0.25);
      background-color: #1B263B;
      color: white;
    }

    .btn-success {
      background-color: #415A77;
      border: none;
    }

    .btn-success:hover {
      background-color: #324966;
    }

    .btn-secondary {
      background-color: #778DA9;
      border: none;
    }

    .btn-secondary:hover {
      background-color: #6b7e99;
    }

    .container {
      max-width: 600px;
      background-color: #1B263B;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 0 20px rgba(0,0,0,0.3);
    }
  </style>
</head>
<body>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="assets/logo.png.png" alt="Senteurs du Monde" width="80">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="gestion_produit.php">Produits</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="adminMenu" role="button" data-bs-toggle="dropdown">
              Admin
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item text-white" href="ajouter_produit.php">Ajouter un produit</a></li>
              <li><a class="dropdown-item text-white" href="deconnexion.php">Déconnexion</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- FORMULAIRE -->
  <div class="container mt-5">
    <h1 class="text-center mb-4">Ajouter un Produit</h1>
    <form method="POST">
      <div class="mb-3">
        <label for="nom" class="form-label">Nom du Produit</label>
        <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez le nom du produit" required>
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" id="description" name="description" rows="3" placeholder="Entrez une description" required></textarea>
      </div>
      <div class="mb-3">
        <label for="prix" class="form-label">Prix (€)</label>
        <input type="number" step="0.01" class="form-control" id="prix" name="prix" placeholder="Entrez le prix" required>
      </div>
      <div class="mb-3">
        <label for="stock" class="form-label">Stock</label>
        <input type="number" class="form-control" id="stock" name="stock" placeholder="Entrez la quantité en stock" required>
      </div>
      <div class="mb-3">
        <label for="id_categorie" class="form-label">Catégorie</label>
        <select class="form-select" id="id_categorie" name="id_categorie" required>
          <option value="">Sélectionnez une catégorie</option>
          <?php foreach ($categories as $categorie): ?>
            <option value="<?= $categorie['id_categorie']; ?>"><?= htmlspecialchars($categorie['nom']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-3">
        <label for="id_sous_categorie" class="form-label">Sous-Catégorie</label>
        <select class="form-select" id="id_sous_categorie" name="id_sous_categorie" required>
          <option value="">Sélectionnez une sous-catégorie</option>
          <?php foreach ($sousCategories as $sousCategorie): ?>
            <option value="<?= $sousCategorie['id_sous_categorie']; ?>"><?= htmlspecialchars($sousCategorie['nom']); ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-success">Ajouter</button>
        <a href="gestion_produit.php" class="btn btn-secondary">Annuler</a>
      </div>
    </form>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
