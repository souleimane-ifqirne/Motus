<?php

require_once "./../config.php";

function removeAccents($str)
{
    $str = str_replace(
        ['à', 'â', 'ä', 'á', 'ã', 'å', 'æ'],
        'a',
        $str
    );
    $str = str_replace(
        ['ç', 'ć', 'č'],
        'c',
        $str
    );
    $str = str_replace(
        ['è', 'é', 'ê', 'ë', 'ě'],
        'e',
        $str
    );
    $str = str_replace(
        ['ì', 'í', 'î', 'ï'],
        'i',
    $str
    );
    $str = str_replace(
        ['ñ'],
        'n',
        $str
    );
    $str = str_replace(
        ['ò', 'ô', 'ö', 'ó', 'õ', 'ø', 'œ'],
        'o',
        $str
    );
    $str = str_replace(
        ['ß', 'š'],
        's',
        $str
    );
    $str = str_replace(
        ['ù', 'ú', 'û', 'ü', 'ů'],
        'u',
        $str
    );
    $str = str_replace(
        ['ÿ', 'ý'],
        'y',
        $str
    );
    $str = str_replace(
        ['ž'],
        'z',
        $str
    );

    return $str;
}

try
{
    $conn = new PDO("mysql:host=$serveur;port=$port;dbname=$baseDeDonnees", $utilisateur, $motDePasse);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $filename = "./mots.txt";
    $file = fopen($filename, "r");

    if ($file) {
        $sql = "CREATE TABLE IF NOT EXISTS mots (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        mot VARCHAR(255) NOT NULL,
        taille_mot INT NOT NULL
        )";
        $conn->exec($sql);

        $stmt = $conn->prepare("INSERT INTO mots (mot, taille_mot) VALUES (:mot, :taille_mot)");

        $Size = 250;
        $count = 0;

        $conn->beginTransaction();

        while (($word = fgets($file)) !== false) {
            $word = trim($word);
            $word = removeAccents($word);
            $word = mb_strtoupper($word, 'UTF-8');
            $tailleMot = strlen($word);

            $stmt->bindParam(':mot', $word);
            $stmt->bindParam(':taille_mot', $tailleMot);
            $stmt->execute();

            $count++;
            if ($count % $Size === 0) {
                $conn->commit();
                $conn->beginTransaction();
            }
        }

        $conn->commit(); // Valider le dernier lot

        fclose($file);
        echo "Les mots ont été importés avec succès dans la table 'mots'.";
    } else {
      echo "Erreur lors de l'ouverture du fichier.";
    }
}

catch(PDOException $e)
{
    echo "Erreur : " . $e->getMessage();
}

$conn = null;

?>