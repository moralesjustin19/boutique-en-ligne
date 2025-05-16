<?php
// filepath: c:\wamp64\www\boutique-en-ligne\modifier_profil.php

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['connecte']) || $_SESSION['connecte'] !== true) {
    header("Location: connexion.php");
    exit();
}

// Inclure la configuration de la base de données
require_once "config/config.php";

// Récupérer les informations de l'utilisateur
$userId = $_SESSION['id_utilisateur']; // Assurez-vous que l'ID utilisateur est stocké dans la session
try {
    $sql = "SELECT nom, prenom, email, adresse FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id_utilisateur', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo "Utilisateur introuvable.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des informations utilisateur : " . $e->getMessage();
    exit();
}

// Mettre à jour les informations utilisateur si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $adresse = htmlspecialchars($_POST['adresse']);

    try {
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email, adresse = :adresse WHERE id_utilisateur = :id_utilisateur";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindValue(':prenom', $prenom, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':adresse', $adresse, PDO::PARAM_STR);
        $stmt->bindValue(':id_utilisateur', $userId, PDO::PARAM_INT);
        $stmt->execute();

        // Rediriger vers la page profil avec un message de succès
        $_SESSION['message'] = "Vos informations ont été mises à jour avec succès.";
        header("Location: profil.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour des informations utilisateur : " . $e->getMessage();
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Profil - Senteurs du Monde</title>
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
                <a href="panier.php" class="text-dark text-decoration-none me-3"><i class="bi bi-basket"></i> Mon Panier</a>
                <a href="deconnexion.php" class="text-dark text-decoration-none"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <div class="container content mt-5">
        <h1 class="text-center mb-4">Modifier Mon Profil</h1>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo htmlspecialchars($user['nom']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo htmlspecialchars($user['prenom']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <textarea class="form-control" id="adresse" name="adresse" rows="3" required><?php echo htmlspecialchars($user['adresse']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="profil.php" class="btn btn-secondary">Annuler</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer-custom mt-auto">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Senteurs du Monde. Tous droits réservés.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>