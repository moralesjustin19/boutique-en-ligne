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
    // Vérifier si l'email est vide
    if (empty(trim($_POST["email"]))) {
        $email_err = "Veuillez entrer votre email.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Vérifier si le mot de passe est vide
    if (empty(trim($_POST["mot_de_passe"]))) {
        $mot_de_passe_err = "Veuillez entrer votre mot de passe.";
    } else {
        $mot_de_passe = trim($_POST["mot_de_passe"]);
    }

    // Valider les identifiants
    if (empty($email_err) && empty($mot_de_passe_err)) {
        // Préparer une requête SQL
        $sql = "SELECT id_utilisateur, email, mot_de_passe FROM utilisateur WHERE email = :email";

        if ($stmt = $pdo->prepare($sql)) {
            // Lier les variables à la requête préparée
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);

            // Exécuter la requête
            if ($stmt->execute()) {
                // Vérifier si l'email existe
                if ($stmt->rowCount() == 1) {
                    if ($row = $stmt->fetch()) {
                        $id_utilisateur = $row["id_utilisateur"];
                        $email = $row["email"];
                        $hashed_mot_de_passe = $row["mot_de_passe"];

                        if (password_verify($mot_de_passe, $hashed_mot_de_passe)) {
                            // Mot de passe correct, démarrer une session
                            session_start();

                            // Stocker les données dans la session
                            $_SESSION["connecte"] = true;
                            $_SESSION["id_utilisateur"] = $id_utilisateur;
                            $_SESSION["email"] = $email;

                            // Rediriger vers la page de profil
                            header("location: index.php");
                            exit;
                        } else {
                            $login_err = "Email ou mot de passe incorrect.";
                        }
                    }
                } else {
                    $login_err = "Email ou mot de passe incorrect.";
                }
            } else {
                echo "Une erreur est survenue. Veuillez réessayer plus tard.";
            }

            // Fermer la requête
            unset($stmt);
        }
    }

    // Fermer la connexion
    unset($pdo);
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
                        <input type="password" name="mot_de_passe" class="form-control <?php echo (!empty($mot_de_passe_err)) ? 'is-invalid' : ''; ?>">
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