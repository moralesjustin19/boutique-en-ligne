<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Senteurs du Monde</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="stylesheet.css">
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo.png.png" alt="Senteurs du Monde" width="80">
    </a>
    <form class="d-flex mx-auto search-bar">
      <input class="form-control me-2" type="search" placeholder="Rechercher..." aria-label="Search">
      <button class="btn btn-dark" type="submit">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search-heart" viewBox="0 0 16 16">
        <path d="M6.5 4.482c1.664-1.673 5.825 1.254 0 5.018-5.825-3.764-1.664-6.69 0-5.018"/>
        <path d="M13 6.5a6.47 6.47 0 0 1-1.258 3.844q.06.044.115.098l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1-.1-.115h.002A6.5 6.5 0 1 1 13 6.5M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11"/>
        </svg>
      </button>
    </form>
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
            <li><a class="dropdown-item" href="deconnexion.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="connexion.php" class="me-3 text-dark text-decoration-none"><i class="bi bi-person"></i> Connexion</a>
      <?php endif; ?>
      <a href="#" class="text-dark text-decoration-none"><i class="bi bi-basket"></i> Mon Panier</a>
    </div>
  </div>
</nav>

<!-- Navigation -->
<div class="navbar navbar-expand-lg navbar-custom">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="coffret.php">Coffret</a></li>
      <li class="nav-item"><a class="nav-link" href="parfum_femme.php">Parfum Femme</a></li>
      <li class="nav-item"><a class="nav-link" href="parfum_homme.php">Parfum Homme</a></li>
      <li class="nav-item"><a class="nav-link" href="parfum_enfant.php">Parfum Enfant</a></li>
    </ul>
  </div>
</div>

<!-- Carousel -->
<div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active text-center">
      <img src="assets/Carrousel1.webp" class="d-block w-100" alt="Parfum 1">
    </div>
    <div class="carousel-item text-center">
      <img src="assets/Carrousel2.webp" class="d-block w-100" alt="Parfum 2">
    </div>
    <div class="carousel-item text-center">
      <img src="assets/Carrousel3.jpg" class="d-block w-100" alt="Parfum 3">
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
    <span class="carousel-control-prev-icon"></span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
    <span class="carousel-control-next-icon"></span>
  </button>
</div>

<!-- Info Bar -->
<div class="info-bar text-center">
  <div class="container d-flex justify-content-around flex-wrap">
    <div><i class="bi bi-gift"></i> Emballage cadeau 1€</div>
    <div><i class="bi bi-truck"></i> Livraison offerte dès 60€ d'achat</div>
    <div><i class="bi bi-percent"></i> 5% de remise sur votre prochaine commande</div>
    <div><i class="bi bi-shield-lock"></i> Paiements sécurisés</div>
  </div>
</div>

<!-- Mosaïque de produits populaires -->
<div class="container my-5">
  <h2 class="text-center mb-4">Produits Populaires</h2>
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    // Connexion à la base de données
    require_once "config.php";

    try {
        // Récupérer les produits populaires (par exemple, les 6 premiers produits)
        $sql = "SELECT nom, description, prix, image FROM produit ORDER BY date_ajout DESC LIMIT 6";
        $stmt = $pdo->query($sql);

        while ($produit = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col">';
            echo '  <div class="card h-100">';
            echo '    <img src="assets/' . htmlspecialchars($produit['image']) . '" class="card-img-top" alt="' . htmlspecialchars($produit['nom']) . '">';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . htmlspecialchars($produit['nom']) . '</h5>';
            echo '      <p class="card-text">' . htmlspecialchars($produit['description']) . '</p>';
            echo '      <p class="card-text fw-bold">' . number_format($produit['prix'], 2, ',', ' ') . ' €</p>';
            echo '      <a href="#" class="btn btn-primary">Ajouter au panier</a>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erreur lors de la récupération des produits : ' . $e->getMessage() . '</div>';
    }
    ?>
  </div>

  <!-- Tous les produits -->
<div class="container my-5">
  <h2 class="text-center mb-4">Tous Nos Produits</h2>
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    // Connexion à la base de données
    require_once "config.php";

    try {
        // Récupérer tous les produits
        $sql = "SELECT nom, description, prix, image FROM produit";
        $stmt = $pdo->query($sql);

        while ($produit = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col">';
            echo '  <div class="card h-100">';
            echo '    <img src="assets/' . htmlspecialchars($produit['image']) . '" class="card-img-top" alt="' . htmlspecialchars($produit['nom']) . '">';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . htmlspecialchars($produit['nom']) . '</h5>';
            echo '      <p class="card-text">' . htmlspecialchars($produit['description']) . '</p>';
            echo '      <p class="card-text fw-bold">' . number_format($produit['prix'], 2, ',', ' ') . ' €</p>';
            echo '      <a href="#" class="btn btn-primary">Ajouter au panier</a>';
            echo '    </div>';
            echo '  </div>';
            echo '</div>';
        }
    } catch (PDOException $e) {
        echo '<div class="alert alert-danger">Erreur lors de la récupération des produits : ' . $e->getMessage() . '</div>';
    }
    ?>
  </div>
</div>
</div>

<!-- Footer -->
<footer class="footer-custom text-white py-5">
  <div class="container">
    <div class="row">
      <!-- À propos -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold">À propos</h5>
        <p>Senteurs du Monde vous propose une large gamme de parfums et coffrets pour hommes, femmes et enfants. Découvrez nos produits de qualité et profitez d'une expérience unique.</p>
      </div>
      <!-- Liens rapides -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold">Liens rapides</h5>
        <ul class="list-unstyled">
          <li><a href="#" class="text-white text-decoration-none"><i class="bi bi-house-door me-2"></i>Accueil</a></li>
          <li><a href="#" class="text-white text-decoration-none"><i class="bi bi-bag me-2"></i>Produits</a></li>
          <li><a href="#" class="text-white text-decoration-none"><i class="bi bi-envelope me-2"></i>Contact</a></li>
          <li><a href="#" class="text-white text-decoration-none"><i class="bi bi-file-earmark-text me-2"></i>Politique de confidentialité</a></li>
        </ul>
      </div>
      <!-- Contact -->
      <div class="col-md-4 mb-4">
        <h5 class="fw-bold">Contact</h5>
        <ul class="list-unstyled">
          <li><i class="bi bi-geo-alt-fill me-2"></i>123 Rue des Parfums, Paris, France</li>
          <li><i class="bi bi-envelope-fill me-2"></i>contact@senteursdumonde.com</li>
          <li><i class="bi bi-telephone-fill me-2"></i>+33 1 23 45 67 89</li>
        </ul>
        <div class="mt-3">
          <a href="#" class="text-white me-3"><i class="bi bi-facebook fs-4"></i></a>
          <a href="#" class="text-white me-3"><i class="bi bi-instagram fs-4"></i></a>
          <a href="#" class="text-white"><i class="bi bi-twitter fs-4"></i></a>
        </div>
      </div>
    </div>
    <hr class="my-4 border-light">
    <div class="text-center">
      <p class="mb-0">&copy; 2025 Senteurs du Monde. Tous droits réservés.</p>
    </div>
  </div>
</footer>

<!-- Bootstrap JS Bundle with Popper (doit être en bas du body) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>