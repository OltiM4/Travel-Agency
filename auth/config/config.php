<?php
// Konfigurimi i bazës së të dhënave
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_database";

// Krijo lidhjen
$conn = new mysqli($servername, $username, $password, $dbname);

// Kontrollo nëse lidhja është e suksesshme
if ($conn->connect_error) {
    die("Lidhja me bazën e të dhënave dështoi: " . $conn->connect_error);
}
?>
