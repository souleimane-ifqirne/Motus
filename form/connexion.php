<?php
// Paramètres de connexion à la base de données
$serveur = 'localhost';
$utilisateur = 'root';
$motDePasse = 'Test_Bachelor_Web';
$baseDeDonnees = 'motus';

// Récupération des données du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Connexion à la base de données
    $conn = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

    // Vérification des erreurs de connexion
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }

    // Requête pour vérifier les informations d'identification
    $query = "SELECT * FROM joueurs WHERE email = '$email'";
    $result = $conn->query($query);

    // Un utilisateur à été trouvé
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['mot_de_passe'];

        // Vérifier la correspondance du mot de passe
        if (password_verify($password, $hashedPassword)) {
            // Démarrage de la session et récupération de l'ID de l'utilisateur
            session_start();
            $_SESSION['user_id'] = $row['id'];

            // Envoie vers la page game.php
            header("Location: ./../game.php");
            exit();

        } else {
            $errorMessage = "Désolé, le mot de passe que vous avez saisi est incorrect. Veuillez vérifier votre saisie et réessayer.";
        }
    } else {
            $errorMessage = "L'adresse e-mail que vous avez saisie est introuvable. Veuillez vérifier votre saisie et réessayer.";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="./img/logo_title.png" type="image/png">
    <link rel="stylesheet" type="text/css" href="./../css/form.css">
    <title>Motus</title>
</head>
<body>
    <section>

    <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>

    <div class="signin">
        <div class="content">
            <h2>Connexion</h2>
            <form method="POST" action="" class="form">
                <div class="inputBx">
                    <input type="email" name="email" required>
                    <label for="email">E-mail</label>
                </div>
                <div class="inputBx">
                    <input type="password" name="password" required>
                    <label for="password">Mot de Passe</label>
                </div>
                <?php if (!empty($errorMessage)): ?>
                <div class="error-message" style="color: #E7002A">
                    <?php echo $errorMessage; ?>
                </div>
                <?php endif; ?>
                <div class="links">
                    <a href="inscription.php">S'inscrire</a>
                </div>
                <div class="inputBx">
                    <input type="submit" value="Se connecter">
                </div>
            </form>
        </div>
    </section>
</body>
</html>