<?php
// filepath: c:\wamp64\www\boutique-en-ligne\produit.php

// Inclure la configuration de la base de données
require_once "config.php";

// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';

$productId = isset($_GET['id_produit']) ? intval($_GET['id_produit']) : null; // Récupérer l'ID du produit
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produit - Senteurs du Monde</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheet.css">
    <style>
        /* Assurez-vous que le footer reste en bas */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .content {
            flex: 1;
        }
        .footer-custom {
            background-color:#232F3E;
            color: white;
        }
    </style>
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
                <?php if ($estConnecte): ?>
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

    <!-- Contenu principal -->
    <div class="content container my-5">
        <?php if ($productId): ?>
            <?php
            try {
                // Requête pour récupérer les détails du produit
                $sql = "SELECT * FROM produit WHERE id_produit = :id_produit";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id_produit', $productId, PDO::PARAM_INT);
                $stmt->execute();

                $produit = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($produit): ?>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="assets/<?php echo htmlspecialchars($produit['image']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($produit['nom']); ?>">
                        </div>
                        <div class="col-md-6">
                            <h1 class="fw-bold"><?php echo htmlspecialchars($produit['nom']); ?></h1>
                            <p class="text-muted"><?php echo htmlspecialchars($produit['description']); ?></p>
                            <p class="h4 text-primary fw-bold"><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</p>
                            <a href="ajouter_panier.php?id_produit=<?php echo $produit['id_produit']; ?>" class="btn btn-dark btn-lg mt-3">
    <i class="bi bi-cart-plus"></i> Ajouter au panier
</a>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning text-center">Produit introuvable.</div>
                <?php endif;
            } catch (PDOException $e) {
                echo "<div class='alert alert-danger'>Erreur lors de la récupération du produit : " . $e->getMessage() . "</div>";
            }
            ?>
        <?php else: ?>
            <div class="alert alert-warning text-center">Aucun produit sélectionné.</div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer-custom text-white py-5 mt-auto">
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