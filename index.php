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
  <link rel="stylesheet" href="index.css">
</head>
<body>

<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
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
      <li class="nav-item"><a class="nav-link" href="#">Coffret</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Parfum Femme</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Parfum Homme</a></li>
      <li class="nav-item"><a class="nav-link" href="#">Parfum Enfant</a></li>
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

<!-- Bootstrap JS Bundle with Popper (doit être en bas du body) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>