<?php

// ── Connexion 
$host     = "localhost";   // Adresse du serveur MySQL (XAMPP = localhost)
$user     = "root";        // Nom d'utilisateur MySQL
$password = "";            // Mot de passe MySQL (XAMPP = vide par défaut)
$dbname   = "aniprizza";   // Nom de ta base de données

// ── Connexion MySQLi
$conn = new mysqli($host, $user, $password, $dbname);
$conn->set_charset("utf8mb4");

if ($conn->connect_error) {
    die(json_encode([
        "error" => true,
        "message" => "Erreur de connexion : " . $conn->connect_error
    ]));
}

// ── Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
