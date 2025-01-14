<?php
include __DIR__ . '/../../../../Data/auth/config/config.php'; // Rruga relative për config.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kontrollo dhe pastro të dhënat e formës
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $user_type = intval($_POST['user_type']); // Sigurohemi që është numerik

    // Validimi bazik i të dhënave
    if (empty($name) || empty($surname) || empty($email) || empty($password)) {
        die("Të gjitha fushat janë të detyrueshme!");
    }

    // Kontrolli i formatit të email-it
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Email-i nuk është i vlefshëm!");
    }

    // Enkriptimi i fjalëkalimit
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Përgatit pyetjen SQL për futjen e përdoruesit
    $sql = "INSERT INTO users (name, surname, email, password, user_type) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Gabim në përgatitjen e pyetjes: " . $conn->error);
    }

    // Lidh parametrat me pyetjen SQL
    $stmt->bind_param("ssssi", $name, $surname, $email, $hashed_password, $user_type);

    // Ekzekuto pyetjen dhe kontrollo për gabime
    if ($stmt->execute()) {
        echo "Përdoruesi është shtuar me sukses!";
    } else {
        echo "Gabim gjatë shtimit të përdoruesit: " . $stmt->error;
    }

    // Mbyll deklaratën dhe lidhjen
    $stmt->close();
    $conn->close();
}
?>
