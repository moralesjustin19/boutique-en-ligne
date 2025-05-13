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

// Récupérer l'ID de l'utilisateur connecté
$idUtilisateur = $_SESSION['id_utilisateur'];

// Récupérer les commandes de l'utilisateur
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Commandes</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
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
    <div class="container mt-5">
        <h1 class="text-center mb-4">Mes Commandes</h1>

        <?php if (count($commandes) > 0): ?>
            <?php foreach ($commandes as $commande): ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <strong>Commande #<?php echo $commande['id_commande']; ?></strong> - 
                        Date : <?php echo date('d/m/Y H:i', strtotime($commande['date_commande'])); ?>
                    </div>
                    <div class="card-body">


                        <!-- Récupérer les détails de la commande -->
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
                                    <?php echo htmlspecialchars($detail['nom']); ?> - 
                                    Quantité : <?php echo $detail['quantite']; ?> - 
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>