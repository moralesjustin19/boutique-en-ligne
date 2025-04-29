<?php
// Initialisation des variables
$nom = $prenom = $email = $password = $confirm_password = $adresse = "";
$nom_err = $prenom_err = $email_err = $password_err = $confirm_password_err = $adresse_err = "";

// Traitement du formulaire lorsqu'il est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation du nom
    if (empty(trim($_POST["nom"]))) {
        $nom_err = "Veuillez entrer votre nom.";
    } else {
        $nom = trim($_POST["nom"]);
    }

    // Validation du prénom
    if (empty(trim($_POST["prenom"]))) {
        $prenom_err = "Veuillez entrer votre prénom.";
    } else {
        $prenom = trim($_POST["prenom"]);
    }

    // Validation de l'email
    if (empty(trim($_POST["email"]))) {
        $email_err = "Veuillez entrer un email.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Format d'email invalide.";
    } else {
        $email = trim($_POST["email"]);
    }

    // Validation du mot de passe
    if (empty(trim($_POST["password"]))) {
        $password_err = "Veuillez entrer un mot de passe.";
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        $password = trim($_POST["password"]);
    }

    // Validation de la confirmation du mot de passe
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Veuillez confirmer le mot de passe.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($password_err) && ($password != $confirm_password)) {
            $confirm_password_err = "Les mots de passe ne correspondent pas.";
        }
    }

    // Validation de l'adresse
    if (empty(trim($_POST["adresse"]))) {
        $adresse_err = "Veuillez entrer votre adresse.";
    } else {
        $adresse = trim($_POST["adresse"]);
    }

    // Vérification des erreurs avant enregistrement
    if (empty($nom_err) && empty($prenom_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($adresse_err)) {
        // Connexion à la base de données
        require_once "config.php";

        try {
            $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Vérification si l'email existe déjà
            $sql_check = "SELECT id_utilisateur FROM utilisateur WHERE email = :email";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt_check->execute();

            if ($stmt_check->rowCount() > 0) {
                $email_err = "Cet email est déjà utilisé.";
            } else {
                // Préparation de la requête d'insertion
                $sql = "INSERT INTO utilisateur (nom, prenom, email, mot_de_passe, adresse, role) VALUES (:nom, :prenom, :email, :password, :adresse, 'client')";

                if ($stmt = $pdo->prepare($sql)) {
                    // Hashage du mot de passe
                    $param_password = password_hash($password, PASSWORD_DEFAULT);

                    // Liaison des paramètres
                    $stmt->bindParam(":nom", $nom, PDO::PARAM_STR);
                    $stmt->bindParam(":prenom", $prenom, PDO::PARAM_STR);
                    $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                    $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                    $stmt->bindParam(":adresse", $adresse, PDO::PARAM_STR);

                    // Exécution de la requête
                    if ($stmt->execute()) {
                        // Redirection vers la page de connexion
                        header("location: connexion.php");
                        exit();
                    } else {
                        echo "<div class='alert alert-danger'>Une erreur est survenue. Veuillez réessayer plus tard.</div>";
                    }
                }
                unset($stmt);
            }
            unset($stmt_check);
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erreur de connexion : " . $e->getMessage() . "</div>";
        }
        unset($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .registration-form {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .form-floating label {
            padding-left: 35px;
        }
        .form-control {
            padding-left: 35px;
        }
        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            z-index: 5;
        }
        .btn-register {
            background-color: #0d6efd;
            border: none;
            padding: 10px;
            font-weight: 600;
        }
        .btn-register:hover {
            background-color: #0b5ed7;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="registration-form">
            <h2 class="form-title"><i class="fas fa-user-plus me-2"></i>Inscription</h2>
            <p class="text-center mb-4">Veuillez remplir ce formulaire pour créer un compte.</p>
            
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <!-- Nom -->
                <div class="form-floating mb-3">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="nom" class="form-control <?php echo (!empty($nom_err)) ? 'is-invalid' : ''; ?>" 
                           id="floatingNom" placeholder="Nom" value="<?php echo $nom; ?>">
                    <label for="floatingNom">Nom</label>
                    <div class="invalid-feedback">
                        <?php echo $nom_err; ?>
                    </div>
                </div>
                
                <!-- Prénom -->
                <div class="form-floating mb-3">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" name="prenom" class="form-control <?php echo (!empty($prenom_err)) ? 'is-invalid' : ''; ?>" 
                           id="floatingPrenom" placeholder="Prénom" value="<?php echo $prenom; ?>">
                    <label for="floatingPrenom">Prénom</label>
                    <div class="invalid-feedback">
                        <?php echo $prenom_err; ?>
                    </div>
                </div>
                
                <!-- Email -->
                <div class="form-floating mb-3">
                    <i class="fas fa-envelope input-icon"></i>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" 
                           id="floatingEmail" placeholder="Email" value="<?php echo $email; ?>">
                    <label for="floatingEmail">Email</label>
                    <div class="invalid-feedback">
                        <?php echo $email_err; ?>
                    </div>
                </div>
                
                <!-- Mot de passe -->
                <div class="form-floating mb-3">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" 
                           id="floatingPassword" placeholder="Mot de passe">
                    <label for="floatingPassword">Mot de passe</label>
                    <div class="invalid-feedback">
                        <?php echo $password_err; ?>
                    </div>
                    <div class="form-text">Au moins 6 caractères</div>
                </div>
                
                <!-- Confirmation mot de passe -->
                <div class="form-floating mb-3">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" 
                           id="floatingConfirmPassword" placeholder="Confirmer le mot de passe">
                    <label for="floatingConfirmPassword">Confirmer le mot de passe</label>
                    <div class="invalid-feedback">
                        <?php echo $confirm_password_err; ?>
                    </div>
                </div>
                
                <!-- Adresse -->
                <div class="form-floating mb-4">
                    <i class="fas fa-map-marker-alt input-icon"></i>
                    <textarea name="adresse" class="form-control <?php echo (!empty($adresse_err)) ? 'is-invalid' : ''; ?>" 
                              id="floatingAdresse" placeholder="Adresse" style="height: 100px"><?php echo $adresse; ?></textarea>
                    <label for="floatingAdresse">Adresse</label>
                    <div class="invalid-feedback">
                        <?php echo $adresse_err; ?>
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary btn-register">
                        <i class="fas fa-user-plus me-2"></i>S'inscrire
                    </button>
                </div>
                
                <div class="text-center">
                    <p>Vous avez déjà un compte? <a href="connexion.php">Connectez-vous ici</a></p>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>