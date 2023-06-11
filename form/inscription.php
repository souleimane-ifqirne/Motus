<?php
// Paramètres de connexion à la base de données
$serveur = 'localhost';
$utilisateur = 'root';
$motDePasse = 'Test_Bachelor_Web';
$baseDeDonnees = 'motus';

// Vérification du formulaire d'inscription
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $emailVerification = $_POST['email_verification'];
    $password = $_POST['password'];
    $passwordVerification = $_POST['password_verification'];

    $errorMessages = array(
        'username_length' => "Le nom d'utilisateur doit contenir au moins 3 caractères.",
        'empty_fields' => "Veuillez remplir tous les champs.",
        'password_mismatch' => "Les mots de passe ne correspondent pas.",
        'password_length' => "Le mot de passe est trop court. Veuillez en choisir un plus long.",
        'password_case' => "Le mot de passe doit contenir au moins une lettre majuscule et une minuscule.",
        'password_digit' => "Le mot de passe doit contenir au moins un chiffre.",
        'password_special_char' => "Le mot de passe doit contenir au moins un caractère spécial.",
        'username_exists' => "Le nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.",
        'email_mismatch' => "Les adresses email ne correspondent pas.",
        'email_invalid' => "L'adresse e-mail doit être au format example@example.com.",
        'email_exists' => "Cette adresse e-mail est déjà associée à un compte. Veuillez en choisir une autre."
    );

    $errors = array();

    // Vérifier si le nom d'utilisateur est valide
    if (strlen($username) < 3) {
        $errors[] = $errorMessages['username_length'];
    }

    // Vérifier si les champs sont vides
    if (empty($username) || empty($email) || empty($emailVerification) || empty($password) || empty($passwordVerification)) {
        $errors[] = $errorMessages['empty_fields'];
    }

    // Vérifier si les mots de passe correspondent
    if ($password !== $passwordVerification) {
        $errors[] = $errorMessages['password_mismatch'];
    }

    // Vérifier si le mot de passe est suffisamment long
    if (strlen($password) < 8) {
        $errors[] = $errorMessages['password_length'];
    }

    // Vérifier si le mot de passe contient une lettre majuscule et une minuscule
    if (!preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password)) {
        $errors[] = $errorMessages['password_case'];
    }

    // Vérifier si le mot de passe contient un chiffre
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = $errorMessages['password_digit'];
    }

    // Vérifier si le mot de passe contient un caractère spécial
    if (!preg_match('/[^A-Za-z0-9]/', $password)) {
        $errors[] = $errorMessages['password_special_char'];
    }

    // Vérifier si le nom d'utilisateur est déjà utilisé
    $conn = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);
    if ($conn->connect_error) {
        die("Échec de la connexion à la base de données : " . $conn->connect_error);
    }
    $stmt = $conn->prepare("SELECT * FROM joueurs WHERE nom = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $errors[] = $errorMessages['username_exists'];
    }
    $stmt->close();

    // Vérifier si les emails correspondent
    if (strcasecmp($email, $emailVerification) !== 0) {
        $errors[] = $errorMessages['email_mismatch'];
    }

    // Vérifier si l'adresse email est valide et non associée à un compte existant
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $errorMessages['email_invalid'];
    } else {
        $stmt = $conn->prepare("SELECT * FROM joueurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors[] = $errorMessages['email_exists'];
        }
        $stmt->close();
    }

    // Si aucune erreur n'est survenue, enregistrer les données dans la base de données
    if (empty($errors)) {
        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Préparer une requête SQL sécurisée
        $stmt = $conn->prepare("INSERT INTO joueurs (nom, email, mot_de_passe) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);

        // Exécuter la requête
        if ($stmt->execute()) {
            $success = "Inscription réussie !";
        } else {
            $errors[] = "Erreur lors de l'inscription : " . $stmt->error;
        }
        $stmt->close();
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Vérification en temps réel lors de la saisie du nom d'utilisateur
            $('#username').on('input', function() {
                var username = $('#username').val();

                $.ajax({
                    url: 'check_username.php',
                    type: 'POST',
                    data: { username: username },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#username_error_ajax').text("Le nom d'utilisateur est déjà utilisé. Veuillez en choisir un autre.");
                        } else {
                            $('#username_error_ajax').text("");
                        }
                    }
                });

                if (username.length < 3) {
                    $('#username_error').text("Le nom d'utilisateur doit contenir au moins 3 caractères.");
                } else {
                    $('#username_error').text("");
                }

            });

            // Vérification en temps réel lors de la saisie du mot de passe
            $('#password').on('input', function() {
                var password = $('#password').val();

                var errors = [];

                // Vérifier la longueur du mot de passe
                if (password.length < 8) {
                    errors.push("Le mot de passe doit contenir au moins 8 caractères.");
                }

                // Vérifier la présence d'une lettre majuscule et une minuscule
                if (!/[A-Z]/.test(password) || !/[a-z]/.test(password)) {
                    errors.push("Le mot de passe doit contenir au moins une lettre majuscule et une minuscule.");
                }

                // Vérifier la présence d'un chiffre
                if (!/[0-9]/.test(password)) {
                    errors.push("Le mot de passe doit contenir au moins un chiffre.");
                }

                // Vérifier la présence d'un caractère spécial
                if (!/[^A-Za-z0-9]/.test(password)) {
                    errors.push("Le mot de passe doit contenir au moins un caractère spécial.");
                }

                if (errors.length > 0) {
                    $('#password_error').html(errors.join("<br>"));
                } else {
                    $('#password_error').html("");
                }
            });

            // Vérification en temps réel lors de la saisie de l'email
            $('#email, #email_verification').on('input', function() {
                var email = $('#email').val();
                var emailVerification = $('#email_verification').val();

                $.ajax({
                    url: 'check_email.php',
                    type: 'POST',
                    data: { email: email },
                    success: function(response) {
                        if (response === 'exists') {
                            $('#email_error_ajax').text("Cette adresse e-mail est déjà associée à un compte. Veuillez en choisir une autre.");
                        } else {
                            $('#email_error_ajax').text("");
                        }
                    }
                });

                if ($('#email_verification').is(':focus') && email.toLowerCase() !== emailVerification.toLowerCase()) {
                    $('#email_verification_error').text("Les adresses email ne correspondent pas.");
                } else {
                    $('#email_verification_error').text("");
                }

                // Vérification de l'adresse e-mail
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                    $('#email_error').text("L'adresse e-mail doit être au format example@example.com.");
                } else {
                    $('#email_error').text("");
                }

            });

            // Vérification en temps réel lors de la saisie du mot de passe et de la vérification du mot de passe
            $('#password, #password_verification').on('input', function() {
                var password = $('#password').val();
                var passwordVerification = $('#password_verification').val();

                if ($('#password_verification').is(':focus') && password !== passwordVerification) {
                    $('#password_verification_error').text("Les mots de passe ne correspondent pas.");
                } else {
                    $('#password_verification_error').text("");
                }
            });

            // Fonction pour vérifier si des erreurs existent
            function checkErrors() {
                var errors = 0;

                // Vérification des erreurs pour chaque champ
                if ($('#username_error').text() !== "") {
                    errors++;
                }
                if ($('#email_error').text() !== "") {
                    errors++;
                }
                if ($('#email_verification_error').text() !== "") {
                    errors++;
                }
                if ($('#password_error').text() !== "") {
                    errors++;
                }
                if ($('#password_verification_error').text() !== "") {
                    errors++;
                }

                // Désactiver le bouton de soumission si des erreurs existent
                if (errors > 0) {
                    $('#inscription_button').prop('disabled', true);
                } else {
                    $('#inscription_button').prop('disabled', false);
                }
            }

            // Appeler la fonction checkErrors lors de la saisie dans les champs
            $('#username').on('input', checkErrors);
            $('#email, #email_verification').on('input', checkErrors);
            $('#password, #password_verification').on('input', checkErrors);

        });
    </script>
</head>
<body>
    <section>

    <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>

    <div class="signin">
        <div class="content">
            <h2>Inscription</h2>
            <form method="POST" action="" class="form">
                <div class="inputBx">
                    <input type="text" name="username" id="username" required minlength="3" maxlength="20">
                    <label for="username">Nom d'utilisateur</label>
                    <p id="username_error" style="color: #E7002A;"></p>
                    <p id="username_error_ajax" style="color: #E7002A;"></p>
                </div>
                <div class="inputBx">
                    <input type="email" name="email" id="email" required maxlength="100">
                    <label for="email">Email</label>
                    <p id="email_error" style="color: #E7002A;"></p>
                    <p id="email_error_ajax" style="color: #E7002A;"></p>
                </div>
                <div class="inputBx">
                    <input type="email" name="email_verification" id="email_verification" required autocomplete="off">
                    <label for="email_verification">Vérification de l'Email</label>
                    <p id="email_verification_error" style="color: #E7002A;"></p>
                </div>
                <div class="inputBx">
                    <input type="password" name="password" id="password" required maxlength="100">
                    <label for="password">Mot de Passe</label>
                    <p id="password_error" style="color: #E7002A;"></p>
                </div>
                <div class="inputBx">
                    <input type="password" name="password_verification" id="password_verification" required>
                    <label for="password_verification">Vérification du mot de passe</label>
                    <p id="password_verification_error" style="color: #E7002A;"></p>
                </div>
                <?php if (!empty($errors)): ?>
                    <ul style="color: #E7002A;">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <p style="color: green;"><?php echo $success; ?></p>
                <?php endif; ?>
                <div class="links">
                    <a href="connexion.php">Se connecter</a>
                </div>
                <div class="inputBx">
                    <input type="submit" value="S'inscrire" id="inscription_button">
                </div>
            </form>
        </div>
    </section>
</body>
</html>