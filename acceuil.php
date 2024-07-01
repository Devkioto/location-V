<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or display an error message
    $_SESSION['error'] = 'Please log in to access this page.';
    header('Location: index.php');
    exit;
}

include('connexion.php');
$nom = $_SESSION['nom'];
$prenom = $_SESSION['prenom'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="stylesheet" href="acceuil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+25+Charted&family=Playwrite+ES+Deco:wght@100..400&display=swap" rel="stylesheet">
</head>

<body>
    <?php
    include('header_location.html');
    ?>
    <h1>Welcome to acceuil page</h1>
    <?php
   

    function welcome()
    {
        $welcome = "";
        $timezone = new DateTimeZone('Africa/Casablanca');
        $time = new DateTime('now', $timezone);
        // $time = new DateTime('6/23/2024 19:33:45');
        $current_hour = (int)date_format($time, "H");
        // echo $current_hour . "<br>"; display time
        if ($current_hour > 5 && $current_hour < 16) {
            # code...
            $welcome = "Bonjour";
        } else {
            # code...
            $welcome = "Bonsoir";
        }
        return $welcome;
    }

    $_SESSION['welcome'] = welcome();
    ?>
    <div class="container">
        <div class="user">
            <nav>
                <ul>
                    <li><a href="location.php">Location</a></li>
                </ul>
            </nav>
            <?php
            echo "<h5>" . welcome() . " {$nom} {$prenom}</h5>";
            ?>
        </div>
        <div class="voiture">
            <h3>Voitures disponibles:</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>marque</th>
                        <th>modele</th>
                        <th>annee</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    $sql = "SELECT marque,modele,annee FROM Voitures WHERE disponibilite ORDER BY marque";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();

                    while ($voiture = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        # code...
                        $marque = $voiture['marque'];
                        $modele = $voiture['modele'];
                        $annee = $voiture['annee'];
                        echo "<tr class='row'>";
                        echo "<td>{$marque}</td>";
                        echo "<td>{$modele}</td>";
                        echo "<td>{$annee}</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>