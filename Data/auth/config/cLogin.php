<?php
include __DIR__ . '/config.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Kontrollo fushat e zbrazëta
    if (empty($email) || empty($password)) {
        echo "<script>alert('Ju lutem plotësoni të gjitha fushat!'); window.location.href='../../web-design/pages/login.php';</script>";
        exit();
    }

    // Përgatit pyetjen SQL
    $stmt = $conn->prepare("SELECT id, name, surname, email, password, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifikimi i të dhënave të përdoruesit
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kontrollo fjalëkalimin duke përdorur password_verify
        if (password_verify($password, $row['password'])) {
            // Vendos të dhënat e sesionit
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_surname'] = $row['surname'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_type'] = $row['user_type'];

            $stmt->close();
            $conn->close();

            // Ridrejto sipas tipit të përdoruesit
            if ($row['user_type'] == 1) {
                header("Location: ../../../Migrations/reservation-management/dashboard/dashboard.php");
            } else {
                header("Location: ../../../Models/web-design/pages/afterlogin.php");
            }
            exit();
        } else {
            // Fjalëkalimi është i pasaktë
            $stmt->close();
            $conn->close();
            echo "<script>alert('Fjalëkalimi është i pasaktë!'); window.location.href='../../web-design/pages/login.php';</script>";
            exit();
        }
    } else {
        // Përdoruesi nuk ekziston
        $stmt->close();
        $conn->close();
        echo "<script>alert('Ky email nuk është i regjistruar!'); window.location.href='../../web-design/pages/login.php';</script>";
        exit();
    }
}
?>
