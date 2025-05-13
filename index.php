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
    <form class="d-flex mx-auto search-bar position-relative" id="searchForm" method="GET" action="search.php">
        <input class="form-control me-2" type="text" id="searchInput" name="q" placeholder="Rechercher un produit..." autocomplete="off">
        <div id="autocompleteList" class="list-group position-absolute w-100" style="z-index: 1000;"></div>
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
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li><a class="dropdown-item" href="dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard Admin</a></li>
            <?php endif; ?>
            <li><a class="dropdown-item" href="deconnexion.php"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</a></li>
          </ul>
        </div>
      <?php else: ?>
        <a href="connexion.php" class="me-3 text-dark text-decoration-none"><i class="bi bi-person"></i> Connexion</a>
      <?php endif; ?>
      <a href="panier.php" class="text-dark text-decoration-none"><i class="bi bi-basket"></i> Mon Panier</a>
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
      <img src="assets/sauvage.jpg" class="d-block w-100" alt="Parfum 1">
    </div>
    <div class="carousel-item text-center">
      <img src="assets/eros.jpg" class="d-block w-100" alt="Parfum 2">
    </div>
    <div class="carousel-item text-center">
      <img src="assets/scandal.jpg" class="d-block w-100" alt="Parfum 3">
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
    <div class="vr"></div>
    <div><i class="bi bi-gift"></i> Emballage cadeau 1€</div>
    <div class="vr"></div>
    <div><i class="bi bi-truck"></i> Livraison offerte dès 60€ d'achat</div>
    <div class="vr"></div>
    <div><i class="bi bi-percent"></i> 5% de remise sur votre prochaine commande</div>
    <div class="vr"></div>
    <div><i class="bi bi-shield-lock"></i> Paiements sécurisés</div>
  </div>
</div>

<!-- Mosaïque de produits populaires -->
<div class="container my-5">
  <h2 class="text-center mb-4">Produits populaires</h2>
  <hr class="hr-blurry">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    // Connexion à la base de données
    require_once "config.php";

    try {
        // Récupérer les produits populaires (par exemple, les 6 premiers produits)
        $sql = "SELECT id_produit, nom, description, prix, image FROM produit ORDER BY date_ajout DESC LIMIT 6";
        $stmt = $pdo->query($sql);

        while ($produit = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col">';
            echo '  <div class="card h-100">';
            echo '    <img src="assets/' . htmlspecialchars($produit['image']) . '" class="card-img-top" alt="' . htmlspecialchars($produit['nom']) . '">';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . htmlspecialchars($produit['nom']) . '</h5>';
            echo '      <p class="card-text">' . htmlspecialchars($produit['description']) . '</p>';
            echo '      <p class="card-text fw-bold">' . number_format($produit['prix'], 2, ',', ' ') . ' €</p>';
            echo '      <a href="ajouter_panier.php?id_produit=' . $produit['id_produit'] . '" class="btn btn-primary">Ajouter au panier</a>';
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
  <h2 class="text-center mb-4">Tous nos produits</h2>
  <hr class="hr-blurry">
  <div class="row row-cols-1 row-cols-md-3 g-4">
    <?php
    // Connexion à la base de données
    require_once "config.php";

    try {
        // Récupérer tous les produits
        $sql = "SELECT id_produit, nom, description, prix, image FROM produit";
        $stmt = $pdo->query($sql);

        while ($produit = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '<div class="col">';
            echo '  <div class="card h-100">';
            echo '    <img src="assets/' . htmlspecialchars($produit['image']) . '" class="card-img-top" alt="' . htmlspecialchars($produit['nom']) . '">';
            echo '    <div class="card-body">';
            echo '      <h5 class="card-title">' . htmlspecialchars($produit['nom']) . '</h5>';
            echo '      <p class="card-text">' . htmlspecialchars($produit['description']) . '</p>';
            echo '      <p class="card-text fw-bold">' . number_format($produit['prix'], 2, ',', ' ') . ' €</p>';
            echo '      <a href="ajouter_panier.php?id_produit=' . $produit['id_produit'] . '" class="btn btn-primary">Ajouter au panier</a>';
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");
    const autocompleteList = document.getElementById("autocompleteList");

    searchInput.addEventListener("input", function () {
        const query = searchInput.value.trim();

        if (query.length > 1) {
            fetch(`search.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    autocompleteList.innerHTML = ""; // Vider la liste précédente

                    if (data.length > 0) {
                        data.forEach(item => {
                            const listItem = document.createElement("a");
                            listItem.href = `produit.php?id_produit=${item.id_produit}`;
                            listItem.className = "list-group-item list-group-item-action";
                            listItem.textContent = item.nom;
                            autocompleteList.appendChild(listItem);
                        });
                    } else {
                        const noResult = document.createElement("div");
                        noResult.className = "list-group-item text-muted";
                        noResult.textContent = "Aucun produit trouvé.";
                        autocompleteList.appendChild(noResult);
                    }
                })
                .catch(error => {
                    console.error("Erreur lors de la recherche :", error);
                });
        } else {
            autocompleteList.innerHTML = ""; // Vider la liste si la recherche est vide
        }
    });

    // Cacher la liste d'autocomplétion si on clique en dehors
    document.addEventListener("click", function (e) {
        if (!autocompleteList.contains(e.target) && e.target !== searchInput) {
            autocompleteList.innerHTML = "";
        }
    });
});
</script>
</body>
</html>