<?php

require_once "./config.php";

session_start();

if (!isset($_SESSION['user_id'])) {
	header("Location: ./form/connexion.php");
	exit();
  }  

$user_id = $_SESSION['user_id'];

$conn = new mysqli($serveur, $utilisateur, $motDePasse, $baseDeDonnees);

if (isset($_POST['score']))
{
    $score = $_POST['score'];
    echo "Score reçu avec succès : " . $score;

    $stmt = $conn->prepare("UPDATE joueurs SET score = score + ? WHERE id = ?");
    $stmt->bind_param("ii", $score, $user_id);

    if ($stmt->execute()) {
        echo "\nLe score a été ajouté avec succès à la base de données.";
    } else {
        echo "Une erreur s'est produite lors de l'ajout du score à la base de données.";
    }
    $stmt->close();
}

$query = "SELECT * from joueurs ORDER BY score DESC";
$result = mysqli_query($conn, $query);
$position = 1;

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
<html> 
	<head>
        <link rel="icon" href="./img/logo_title.png" type="image/png">
        <link rel="stylesheet" href="css/wall_of_fame.css">
		<title>Wall Of Fame</title> 
	</head> 
	<body>
		<header>
            <nav>
				<a id="nothing" href="game.php">
                	<h2 class="logo">MOTUS</h2>
				</a>
                <ul>
                    <li><a href="wall_of_fame.php">Wall of Fame</a></li>
                    <li><a href="#"><?php echo $nom_joueur; ?></a></li>
                </ul>
                <a class="button_disc" href="form/deconnexion.php">Deconnexion</a>
            </nav>
        </header>
		<section>
			<span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span><span></span>
			<table align="center" border="1px" style="width:600px; line-height:40px;"> 
				<tr> 
					<th colspan="4"><h2>Wall Of Fame</h2></th> 
				</tr> 
			  		<th> Position </th> 
			  		<th> Nom </th> 
			  		<th> Score </th> 		  
				</tr> 	
				<?php while($rows=mysqli_fetch_assoc($result)) 
				{ 
				?> 
				<tr>
					<td><?php echo $position++; ?></td> 
					<td><?php echo $rows['nom']; ?></td> 
					<td><?php echo $rows['score']; ?></td> 
				</tr> 
	    		<?php 
        		} 
        		?> 
			</table>
		</section>
	</body> 
</html>