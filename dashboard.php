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

if (isset($_GET['q'])) {
    $searchTerm = htmlspecialchars($_GET['q']); // Échapper les caractères spéciaux pour éviter les injections XSS

    try {
        // Requête pour rechercher les produits correspondant au terme
        $sql = "SELECT id, nom FROM produit WHERE nom LIKE :searchTerm LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':searchTerm', '%' . $searchTerm . '%', PDO::PARAM_STR);
        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retourner les résultats au format JSON
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur lors de la recherche : ' . $e->getMessage()]);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
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

    <!-- Contenu principal -->
    <div class="container mt-5">
        <h1 class="text-center mb-4">Dashboard Admin</h1>
        <div class="row">
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Produits</h5>
                        <p class="card-text">Ajoutez, modifiez ou supprimez des produits.</p>
                        <a href="gestion_produit.php" class="btn btn-primary">Gérer les Produits</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Catégories</h5>
                        <p class="card-text">Ajoutez, modifiez ou supprimez des catégories.</p>
                        <a href="gestion_categorie.php" class="btn btn-primary">Gérer les Catégories</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-body">
                        <h5 class="card-title">Gestion des Sous-Catégories</h5>
                        <p class="card-text">Ajoutez, modifiez ou supprimez des sous-catégories.</p>
                        <a href="gestion_sous_categorie.php" class="btn btn-primary">Gérer les Sous-Catégories</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom mt-auto text-center py-3">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Senteurs du Monde. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>