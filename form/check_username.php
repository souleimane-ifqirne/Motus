<?php

require_once "./../config.php";

// Récupération du nom d'utilisateur envoyé par la requête Ajax
$username = $_POST['username'];

// Connexion à la base de données
$conn = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);
if ($conn->connect_error) {
    die("Échec de la connexion à la base de données : " . $conn->connect_error);
}

// Vérification de l'existence du nom d'utilisateur dans la base de données
$stmt = $conn->prepare("SELECT * FROM joueurs WHERE nom = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Le nom d'utilisateur existe déjà
    echo 'exists';
} else {
    // Le nom d'utilisateur est disponible
    echo 'not_exists';
}

$stmt->close();
$conn->close();
?>