<?php
// Përfshi konfigurimin dhe fillo sesionin
include __DIR__ . '/config.php';
session_start();

// Kontrollo nëse forma është dorëzuar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Merr të dhënat nga forma
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Kontrollo nëse fusha email dhe password janë të mbushura
    if (empty($email) || empty($password)) {
        echo "<script>alert('Ju lutem plotësoni të gjitha fushat!'); window.location.href='../../web-design/pages/login.php';</script>";
        exit();
    }

    // Përgatit një query për të marrë përdoruesin nga baza e të dhënave
    $stmt = $conn->prepare("SELECT id, name, surname, email, password, user_type FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kontrollo nëse përdoruesi ekziston
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Kontrollo nëse fjalëkalimi është i saktë
        if ($password === $row['password']) { // Krahasim i fjalëkalimit (tekst i thjeshtë)
            // Ruaj të dhënat e përdoruesit në sesion
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_surname'] = $row['surname'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_type'] = $row['user_type'];

            // Mbyll lidhjen dhe deklaratën përpara ridrejtimit
            $stmt->close();
            $conn->close();

            // Ridrejto bazuar në llojin e përdoruesit
            if ($row['user_type'] == 1) {
header("Location: ../../reservation-management/dashboard/dashboard.php");
            } else {
header("Location: ../../web-design/pages/afterlogin.php");
            }
            exit();
        } else {
            // Fjalëkalimi është i gabuar
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
