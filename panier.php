<?php
session_start();
require_once "config.php";

// Authentification
$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';

// Variables
$totalPanier = 0;
$produits = [];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Votre Panier</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        body { display: flex; flex-direction: column; min-height: 100vh; }
        .content { flex: 1; }

        .footer-custom {
            background-color: #232F3E;
            color: white;
            text-align: center;
            padding: 1rem 0;
        }

        /* Carte bancaire dynamique */
        .credit-card-box {
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: white;
            border-radius: 15px;
            padding: 20px;
            font-family: monospace;
            margin-bottom: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
            width: 300px;
            height: 180px;
            position: relative;
        }

        /* Carte Face (avant) */
        .credit-card-box .chip {
            width: 50px;
            height: 40px;
            background: gold;
            border-radius: 5px;
            margin-bottom: 10px;
            position: absolute;
            top: 15px;
            left: 20px;
        }

        .credit-card-box .card-number-display {
            font-size: 1.4rem;
            letter-spacing: 2px;
            margin-top: 50px;
            margin-left: 10px;
            display: inline-block;
        }

        /* Icônes de cartes */
        .card-icons {
            position: absolute;
            top: 15px;
            right: 20px;
        }

        .card-icons img {
            width: 50px;
            height: auto;
            margin-left: 10px;
        }

        /* Effet flip pour CVC */
        .flip-card {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.3s ease-in-out;
        }

        .flip-card.flip .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-inner {
            position: absolute;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.3s ease-in-out;
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
        }

        .flip-card-back {
            transform: rotateY(180deg);
            background: #232F3E;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Positionnement icône Visa/Mastercard */
        .card-icons img {
            width: 50px;
        }

        /* Format réel de la carte */
        .card-number-display {
            width: 100%;
        }

        /* Alignement des champs CVC et expiration */
        .row .col-md-6 {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <img src="assets/logo.png.png" alt="Senteurs du Monde" width="80">
        </a>
        <form class="d-flex mx-auto" method="GET" action="produit.php">
            <input class="form-control me-2" type="text" name="q" placeholder="Rechercher un produit..." autocomplete="off">
        </form>
        <div class="d-flex align-items-center">
            <?php if ($estConnecte): ?>
                <div class="dropdown">
                    <button class="btn btn-link dropdown-toggle text-dark text-decoration-none" data-bs-toggle="dropdown">
                        <?= htmlspecialchars($nomUtilisateur) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="profil.php">Mon profil</a></li>
                        <li><a class="dropdown-item" href="commandes.php">Mes commandes</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="deconnexion.php">Déconnexion</a></li>
                    </ul>
                </div>
            <?php else: ?>
                <a href="connexion.php" class="me-3 text-dark text-decoration-none">Connexion</a>
            <?php endif; ?>
            <a href="panier.php" class="text-dark text-decoration-none">Mon Panier</a>
        </div>
    </div>
</nav>

<!-- Contenu principal -->
<div class="container content my-5">
    <h1 class="text-center mb-4">Votre panier</h1>
    <hr>

    <?php if (!empty($_SESSION['panier'])): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($_SESSION['panier'] as $idProduit => $quantite):
                $stmt = $pdo->prepare("SELECT nom, prix, image FROM produit WHERE id_produit = :id");
                $stmt->execute([':id' => $idProduit]);
                $produit = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($produit):
                    $sousTotal = $produit['prix'] * $quantite;
                    $totalPanier += $sousTotal;
                    $produits[] = $produit;
            ?>
                <tr>
                    <td>
                        <img src="assets/<?= htmlspecialchars($produit['image']) ?>" width="50" alt="<?= htmlspecialchars($produit['nom']) ?>">
                        <?= htmlspecialchars($produit['nom']) ?>
                    </td>
                    <td><?= number_format($produit['prix'], 2, ',', ' ') ?> €</td>
                    <td><?= $quantite ?></td>
                    <td><?= number_format($sousTotal, 2, ',', ' ') ?> €</td>
                    <td>
                        <a href="supprimer_panier.php?id_produit=<?= $idProduit ?>" class="btn btn-danger btn-sm">Supprimer</a>
                    </td>
                </tr>
            <?php endif; endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">Total :</th>
                    <th><?= number_format($totalPanier, 2, ',', ' ') ?> €</th>
                    <th></th>
                </tr>
            </tfoot>
        </table>

        <?php if (!isset($_GET['etape'])): ?>
            <div class="text-end mt-4">
                <a href="?etape=confirmation" class="btn btn-success">Confirmer la commande</a>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">Votre panier est vide.</div>
    <?php endif; ?>

    <?php if (isset($_GET['etape']) && $_GET['etape'] === 'confirmation' && !empty($produits)): ?>
        <hr>
        <h3>Validation de votre commande</h3>
        <p>Total à régler : <strong><?= number_format($totalPanier, 2, ',', ' ') ?> €</strong></p>

        <!-- Carte visuelle -->
        <div class="credit-card-box">
            <div class="chip"></div>
            <div class="card-number-display">0000 0000 0000 0000</div>
            <div class="card-icons">
                <img src="assets/visa.png" alt="Visa">
                <img src="assets/Mastercard-logo.svg" alt="MasterCard">
            </div>
        </div>

        <!-- Formulaire paiement -->
        <form method="POST" action="?etape=paiement" style="max-width: 500px;">
            <div class="card p-4 shadow rounded-4 border">
                <div class="mb-3">
                    <label for="card" class="form-label">Numéro de carte</label>
                    <input type="text" id="card" name="card" class="form-control" placeholder="4242 4242 4242 4242" required maxlength="19">
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="exp" class="form-label">Expiration</label>
                        <input type="text" id="exp" name="exp" class="form-control" placeholder="MM/AA" required maxlength="5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cvc" class="form-label">CVC</label>
                        <div class="flip-card" id="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front">
                                    <input type="text" id="cvc" name="cvc" class="form-control" placeholder="123" required maxlength="4">
                                </div>
                                <div class="flip-card-back">
                                    <div class="form-control text-center">***</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Payer</button>
            </div>
        </form>
    <?php endif; ?>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['etape']) && $_GET['etape'] === 'paiement') {
        $_SESSION['panier'] = [];
        echo '
        <hr>
        <div class="alert alert-success mt-4">
            ✅ Paiement fictif accepté ! Merci pour votre commande.
        </div>
        <a href="index.php" class="btn btn-outline-primary">Retour à l\'accueil</a>';
    }
    ?>
</div>

<!-- Footer -->
<footer class="footer-custom mt-auto">
    <div class="container">
        <p>&copy; <?= date('Y') ?> Ma Boutique en Ligne. Tous droits réservés.</p>
    </div>
</footer>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const cardInput = document.getElementById("card");
    const expInput = document.getElementById("exp");
    const cvcInput = document.getElementById("cvc");
    const flipCard = document.getElementById("flip-card");

    const numberDisplay = document.querySelector(".card-number-display");
    const expDisplay = document.querySelector(".exp-display");
    const cvcDisplay = document.querySelector(".cvc-display");

    cardInput.addEventListener("input", () => {
        let val = cardInput.value.replace(/\D/g, "").slice(0, 16);
        cardInput.value = val.replace(/(.{4})/g, "$1 ").trim();
        numberDisplay.textContent = cardInput.value || "0000 0000 0000 0000";
    });

    expInput.addEventListener("input", () => {
        let val = expInput.value.replace(/\D/g, "").slice(0, 4);
        if (val.length >= 3) {
            val = val.slice(0, 2) + "/" + val.slice(2);
        }
        expInput.value = val;
        expDisplay.textContent = val || "MM/AA";
    });

    cvcInput.addEventListener("focus", () => {
        flipCard.classList.add("flip");
    });

    cvcInput.addEventListener("blur", () => {
        flipCard.classList.remove("flip");
    });

    cvcInput.addEventListener("input", () => {
        let val = cvcInput.value.replace(/\D/g, "").slice(0, 4);
        cvcInput.value = val;
        cvcDisplay.textContent = val || "000";
    });
});
</script>

</body>
</html>
