<?php

require_once "./config.php";

session_start();

if (!isset($_SESSION['user_id'])) {
  header("Location: ./form/connexion.php");
  exit();
}

$conn = mysqli_connect($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

if (!$conn) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

$query = "SELECT mot FROM mots";
$result = mysqli_query($conn, $query);

$listeMots = array();
while ($row = mysqli_fetch_assoc($result)) {
    $listeMots[] = $row['mot'];
}

mysqli_free_result($result);

$motSecret = generate_motSecret($listeMots);

function generate_motSecret($listeMots)
{
    $motSecret = $listeMots[array_rand($listeMots)];
    while (strlen($motSecret) > 10) {
        $motSecret = $listeMots[array_rand($listeMots)];
    }
    return $motSecret;
}

$nom_joueur;

$user_id = $_SESSION['user_id'];

// Requête pour récupérer le nom de l'utilisateur
$query = "SELECT nom FROM joueurs WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $nom_joueur);

// Vérification des résultats et affichage du nom d'utilisateur
if (!mysqli_stmt_fetch($stmt)) {  
    $nom_joueur = 'username';
}

// Fermeture de la connexion
mysqli_stmt_close($stmt);
mysqli_close($conn);

?>

<!DOCTYPE html>
<link rel="icon" href="./img/logo_title.png" type="image/png">
<link rel="stylesheet" type="text/css" href="./css/game.css">
<script src="myscript.js"></script>
<title>MOTUS</title>

<body class="gameContainer">
    <section>
        <header>
            <nav>
                <h2 class="logo">MOTUS</h2>
                <ul>
                    <li><a href="./Wall_of_fame.php">Wall of Fame</a></li>
                    <li><a href="#"><?php echo $nom_joueur; ?></a></li>
                </ul>
                <a class="button_disc" href="form/deconnexion.php">Deconnexion</a>
            </nav>
        </header>
        <span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
        <table id="word-container">
            <audio id="gameWinSound" src="sound/win_sound.mp3"></audio>
            <audio id="gameLoseSound" src="sound/lose_sound.mp3"></audio>
            <script>
                var motSecret = <?php echo json_encode($motSecret); ?>;
                InitializeGame(motSecret);
            </script>
        </table>
        <div id="pannel" style="visibility: hidden;">
                <div id="message">
                </div>
                <div id="playagain" >
                    <a href="game.php">PLAY AGAIN</a>
                </div>
        </div>
    </section>
</body>
</html>