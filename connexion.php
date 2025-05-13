<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration pour la base de données
require_once "config.php";

// Initialiser les variables
$email = $mot_de_passe = "";
$email_err = $mot_de_passe_err = $login_err = "";

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    try {
        $sql = "SELECT id_utilisateur, email, role, mot_de_passe FROM utilisateur WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Démarrer la session
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // Définir les variables de session
            $_SESSION['connecte'] = true;
            $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // Assurez-vous que le rôle est défini ici

            // Rediriger vers la page d'accueil
            header("Location: index.php");
            exit();
        } else {
            $login_err = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        die("Erreur : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2>Connexion</h2>
                <p>Veuillez remplir vos identifiants pour vous connecter.</p>

                <?php 
                if (!empty($login_err)) {
                    echo '<div class="alert alert-danger">' . $login_err . '</div>';
                }
                ?>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>    
                    <div class="form-group mb-3">
                        <label>Mot de passe</label>
                        <input type="password" name="password" class="form-control <?php echo (!empty($mot_de_passe_err)) ? 'is-invalid' : ''; ?>">
                        <span class="invalid-feedback"><?php echo $mot_de_passe_err; ?></span>
                    </div>
                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Connexion">
                    </div>
                    <p class="mt-3">Vous n'avez pas de compte? <a href="inscription.php">Inscrivez-vous ici</a>.</p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>