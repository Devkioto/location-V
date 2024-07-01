<?php
include('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedCars'])) {
    $selectedCars = $_POST['selectedCars'];
    $carDetailsArray = [];

    // Function to calculate duration between two dates
    function calculateDuration($dated, $datef)
    {
        $dateDebut = new DateTime($dated);
        $dateFin = new DateTime($datef);
        $duree = $dateDebut->diff($dateFin);
        return $duree->days;
    }

    foreach ($selectedCars as $selectedCarId) {
        $carSql = "SELECT marque, modele FROM Voitures WHERE idVoiture = ?";
        $carStmt = $conn->prepare($carSql);
        $carStmt->bindParam(1, $selectedCarId, PDO::PARAM_INT);
        $carStmt->execute();
        $carDetails = $carStmt->fetch(PDO::FETCH_ASSOC);

        if ($carDetails) {
            $dated = $_POST['dated'] ?? '';
            $datef = $_POST['datef'] ?? '';

            $duration = calculateDuration($dated, $datef);

            $carDetailsArray[] = [
                'marque' => $carDetails['marque'],
                'modele' => $carDetails['modele'],
                'duration' => $duration
            ];
        }
    }

    header('Content-Type: application/json');
    echo json_encode($carDetailsArray);
    exit;
}
