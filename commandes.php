<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$estConnecte = isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
$nomUtilisateur = $estConnecte ? $_SESSION['email'] : '';

require_once "config.php";
$idUtilisateur = $_SESSION['id_utilisateur'];

try {
    $sqlCommandes = "SELECT * FROM commande WHERE id_utilisateur = :id_utilisateur ORDER BY date_commande DESC";
    $stmtCommandes = $pdo->prepare($sqlCommandes);
    $stmtCommandes->bindValue(':id_utilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmtCommandes->execute();
    $commandes = $stmtCommandes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des commandes : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mes Commandes - Senteurs du Monde</title>

  <!-- Bootstrap CSS + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #0D1B2A; /* Bleu nuit */
      color: white;
    }

    /* Navbar blanche */
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
      background-color: #f8f9fa;
    }

    .navbar-custom .dropdown-item:hover {
      background-color: #e9ecef;
    }

    .card {
      background-color: #1B263B;
      border: 2px solid rgb(244, 241, 237); /* Contour orange visible */
      color: white;
    }

    .card-header {
      background-color: #415A77;
      font-weight: bold;
    }

    h1 {
      color:rgb(255, 255, 255);
    }

    .dropdown-menu a {
      color: #0D1B2A;
    }

    .dropdown-menu a:hover {
      background-color: #eaeaea;
    }

    .btn-primary {
      background-color: #FF9900;
      border: none;
    }

    .btn-primary:hover {
      background-color: #e68a00;
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
      <a href="panier.php" class="ms-3 text-dark text-decoration-none"><i class="bi bi-basket"></i> Mon Panier</a>
    </div>
  </div>
</nav>

<?php if (count($commandes) > 0): ?>
    <?php foreach ($commandes as $commande): ?>
        <div class="card mb-4 shadow">
            <div class="card-header">
                Commande #<?php echo $commande['id_commande']; ?> — 
                Date : <?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?>
            </div>
            <div class="card-body">
                <p><strong>Total :</strong> 
                    <?php echo isset($commande['total']) ? number_format($commande['total'], 2, ',', ' ') . ' €' : 'Non disponible'; ?>
                </p>

                <!-- Détails de commande -->
                <?php
                try {
                    $sqlDetails = "SELECT d.quantite, d.prix_unitaire, p.nom 
                                   FROM detail_commande d
                                   INNER JOIN produit p ON d.id_produit = p.id_produit
                                   WHERE d.id_commande = :id_commande";
                    $stmtDetails = $pdo->prepare($sqlDetails);
                    $stmtDetails->bindValue(':id_commande', $commande['id_commande'], PDO::PARAM_INT);
                    $stmtDetails->execute();
                    $details = $stmtDetails->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    die("Erreur lors de la récupération des détails de la commande : " . $e->getMessage());
                }
                ?>

                <h5>Détails de la commande :</h5>
                <ul>
                    <?php foreach ($details as $detail): ?>
                        <li>
                            <?php echo htmlspecialchars($detail['nom']); ?> — 
                            Quantité : <?php echo $detail['quantite']; ?> — 
                            Prix unitaire : <?php echo number_format($detail['prix_unitaire'], 2, ',', ' '); ?> €
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p class="text-center">Vous n'avez pas encore passé de commande.</p>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
