<!-- filepath: c:\wamp64\www\boutique-en-ligne\parfum_femme.php -->
<?php
// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclure le fichier de configuration pour la connexion à la base de données
require_once "config.php";

// Vérifier si l'utilisateur est connecté
$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parfums Femme - Senteurs du Monde</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
<!-- Header -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="assets/logo.png.png" alt="Senteurs du Monde" width="80">
    </a>
    <form class="d-flex mx-auto search-bar position-relative" id="searchForm" method="GET" action="produit.php">
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

    <!-- Titre de la page -->
    <div class="container my-5">
        <h1 class="text-center">Parfums pour Femme</h1>
        <p class="text-center text-muted">Découvrez notre sélection de parfums pour femme, alliant élégance et raffinement.</p>
    </div>

    <!-- Liste des produits -->
    <div class="container">
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php
            try {
                // Récupérer les parfums pour femmes (sous-catégorie "Femmes")
                $sql = "SELECT produit.nom AS produit_nom, produit.description, produit.prix, produit.image 
                        FROM produit 
                        INNER JOIN sous_categorie ON produit.id_sous_categorie = sous_categorie.id_sous_categorie 
                        WHERE sous_categorie.nom = 'Femmes'";
                $stmt = $pdo->query($sql);

                while ($produit = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo '<div class="col">';
                    echo '  <div class="card h-100">';
                    echo '    <img src="assets/' . htmlspecialchars($produit['image']) . '" class="card-img-top" alt="' . htmlspecialchars($produit['produit_nom']) . '">';
                    echo '    <div class="card-body">';
                    echo '      <h5 class="card-title">' . htmlspecialchars($produit['produit_nom']) . '</h5>';
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

    <!-- Footer -->
    <footer class="footer-custom text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">À propos</h5>
                    <p>Senteurs du Monde vous propose une large gamme de parfums et coffrets pour hommes, femmes et enfants.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php" class="text-white text-decoration-none">Accueil</a></li>
                        <li><a href="parfum_femme.php" class="text-white text-decoration-none">Parfums Femme</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold">Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-geo-alt-fill me-2"></i>123 Rue des Parfums, Paris</li>
                        <li><i class="bi bi-envelope-fill me-2"></i>contact@senteursdumonde.com</li>
                        <li><i class="bi bi-telephone-fill me-2"></i>+33 1 23 45 67 89</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 border-light">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 Senteurs du Monde. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
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