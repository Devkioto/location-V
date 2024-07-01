<?php
session_start();
if (!isset($_SESSION['id'])) {
    // Redirect to the login page or display an error message
    $_SESSION['error'] = 'Please log in to access this page.';
    header('Location: index.php');
    exit;
}

include('connexion.php');
$nom = htmlspecialchars($_SESSION['nom'], ENT_QUOTES, 'UTF-8');
$prenom = htmlspecialchars($_SESSION['prenom'], ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Location</title>
    <link rel="stylesheet" href="acceuil.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jersey+25+Charted&family=Playwrite+ES+Deco:wght@100..400&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>

<body>
    <?php include('header_location.html'); ?>

    <h1>Welcome to location page</h1>

    <div class="container">
        <div class="user">
            <nav>
                <ul>
                    <li><a href="acceuil.php">Acceuil</a></li>
                </ul>
            </nav>
            <?php
            echo "<h5>" . htmlspecialchars($_SESSION['welcome'], ENT_QUOTES, 'UTF-8') . " {$nom} {$prenom}</h5>";
            ?>
        </div>
        <div class="voiture">
            <h3>Voitures disponibles:</h3>
            <table border="1">
                <thead>
                    <tr>
                        <th>action</th>
                        <th>marque</th>
                        <th>modele</th>
                        <th>annee</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $sql = "SELECT idVoiture, marque, modele, annee FROM Voitures WHERE disponibilite ORDER BY marque";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();

                        while ($voiture = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $idV = htmlspecialchars($voiture['idVoiture'], ENT_QUOTES, 'UTF-8');
                            $marque = htmlspecialchars($voiture['marque'], ENT_QUOTES, 'UTF-8');
                            $modele = htmlspecialchars($voiture['modele'], ENT_QUOTES, 'UTF-8');
                            $annee = htmlspecialchars($voiture['annee'], ENT_QUOTES, 'UTF-8');

                            echo "<tr class='row'>";
                            echo "<td><input class='car-checkbox' type='checkbox' value='{$idV}'></td>";
                            echo "<td>{$marque}</td>";
                            echo "<td>{$modele}</td>";
                            echo "<td>{$annee}</td>";
                            echo "</tr>";
                        }
                    } catch (Exception $e) {
                        echo "<tr><td colspan='4'>Error fetching data: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <h3>Louer des voitures:</h3>
            <table id="rentalTable">
                <thead>
                    <tr>
                        <th>voiture</th>
                        <th>client name</th>
                        <th>date debut</th>
                        <th>date fin</th>
                        <th>duree</th>
                    </tr>
                </thead>
                <tbody id="rentalTableBody">
                    <!-- Rows will be dynamically added here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const carCheckboxes = document.querySelectorAll('.car-checkbox');

            carCheckboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', function() {
                    updateCarDetails();
                });
            });

            function updateCarDetails() {
                const selectedCars = Array.from(carCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                const dated = $('#dated').val();
                const datef = $('#datef').val();

                $.ajax({
                    url: 'update_car_details.php',
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        selectedCars: selectedCars,
                        dated: dated,
                        datef: datef
                    },
                    success: function(response) {
                        $('#rentalTableBody').empty();

                        response.forEach(function(car, index) {
                            let carRow = `
                        <tr>
                            <td>${car.marque} ${car.modele}</td>
                            <td><?php echo $nom . ' ' . $prenom; ?></td>
                            <td><input type='date' name='dated[]' id='dated${index}' value='${dated}'></td>
                            <td><input type='date' name='datef[]' id='datef${index}' value='${datef}'></td>
                            <td id='duree${index}'>${car.duration}</td>
                        </tr>`;
                            $('#rentalTableBody').append(carRow);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX request failed:', error);
                    }
                });
            }
        });
    </script>
</body>

</html>
