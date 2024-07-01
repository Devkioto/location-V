<?php

try {
    $conn = new PDO('mysql:host=localhost;dbname=mysql', 'root', '');
    echo 'connected! <br>';

    $sql = "CREATE DATABASE IF NOT EXISTS location_voiture";
    $conn->exec($sql);
    echo 'database created! <br>';

    $sql = "USE location_voiture";
    $conn->exec($sql);

    $sql = "CREATE TABLE IF NOT EXISTS Voitures(
    idVoiture INT PRIMARY KEY AUTO_INCREMENT,
    marque VARCHAR(20),
    modele VARCHAR(20),
    annee INT,
    disponibilite BOOL
    );";
    $conn->exec($sql);
    echo 'Table voitures created! <br>';

    $sql = "CREATE TABLE IF NOT EXISTS Clients(
        idClient INT PRIMARY KEY AUTO_INCREMENT,
        nom VARCHAR(25),
        prenom VARCHAR(25),
        email VARCHAR(30) UNIQUE,
        motdepasse VARCHAR(100)
        );";
    $conn->exec($sql);
    echo 'Table Clients created! <br>';

    $sql = "CREATE TABLE IF NOT EXISTS Locations(
            idLocation INT PRIMARY KEY AUTO_INCREMENT,
            idClient INT,
            FOREIGN KEY (idClient) REFERENCES Clients(idClient),
            idVoiture INT,
            FOREIGN KEY (idVoiture) REFERENCES Voitures(idVoiture),
            dateDebut DATE,
            dateFin DATE
            );";
    $conn->exec($sql);
    echo 'Table Locations created! <br>';
} catch (PDOException) {
    echo 'error <br>';
}
