<?php

require_once "./../config.php";

// Récupération de l'adresse e-mail envoyée par la requête Ajax
$email = $_POST['email'];

// Connexion à la base de données
$conn = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérification de l'existence de l'adresse e-mail dans la base de données
$stmt = $conn->prepare("SELECT * FROM joueurs WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // L'adresse e-mail existe déjà
    echo 'exists';
} else {
    // L'adresse e-mail est disponible
    echo 'not_exists';
}

$stmt->close();
$conn->close();
?>