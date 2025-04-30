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
    <title>Votre Panier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
            text-align: center;
            padding: 1rem 0;
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
      <a href="panier.php" class="text-dark text-decoration-none"><i class="bi bi-basket"></i> Mon Panier</a>
    </div>
  </div>
</nav>

    <!-- Titre de la page -->
    <div class="container my-5">
        <h1 class="text-center">Panier</h1>
        <hr class="blurred-hr">
    </div>

    <div class="container content mt-4">

        <?php
        // filepath: c:\wamp64\www\boutique-en-ligne\panier.php

        // Inclure la configuration de la base de données
        require_once "config.php";

        // Démarrer la session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Vérifier si le panier est vide
        if (!isset($_SESSION['panier']) || empty($_SESSION['panier'])) {
            echo '<div class="alert alert-warning text-center">Votre panier est vide.</div>';
            exit();
        }

        // Afficher les produits du panier
        echo '<table class="table table-bordered">';
        echo '<thead><tr><th>Produit</th><th>Prix</th><th>Quantité</th><th>Total</th><th>Action</th></tr></thead>';
        echo '<tbody>';

        $totalPanier = 0;

        foreach ($_SESSION['panier'] as $idProduit => $quantite) {
            // Récupérer les informations du produit depuis la base de données
            $sql = "SELECT nom, prix, image FROM produit WHERE id_produit = :id_produit";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id_produit', $idProduit, PDO::PARAM_INT);
            $stmt->execute();
            $produit = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($produit) {
                $total = $produit['prix'] * $quantite;
                $totalPanier += $total;

                echo '<tr>';
                echo '<td><img src="assets/' . htmlspecialchars($produit['image']) . '" alt="' . htmlspecialchars($produit['nom']) . '" width="50"> ' . htmlspecialchars($produit['nom']) . '</td>';
                echo '<td>' . number_format($produit['prix'], 2, ',', ' ') . ' €</td>';
                echo '<td>' . $quantite . '</td>';
                echo '<td>' . number_format($total, 2, ',', ' ') . ' €</td>';
                echo '<td><a href="supprimer_panier.php?id_produit=' . $idProduit . '" class="btn btn-danger btn-sm">Supprimer</a></td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '<tfoot><tr><th colspan="3" class="text-end">Total :</th><th>' . number_format($totalPanier, 2, ',', ' ') . ' €</th><th></th></tr></tfoot>';
        echo '</table>';
        ?>

    </div>

    <footer class="footer-custom mt-auto">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Ma Boutique en Ligne. Tous droits réservés.</p>
        </div>
    </footer>

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
