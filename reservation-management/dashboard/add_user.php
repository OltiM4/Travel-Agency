<?php
include '../../auth/config/config.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $name = $conn->real_escape_string($_POST['name']); 
    $surname = $conn->real_escape_string($_POST['surname']); 
    $email = $conn->real_escape_string($_POST['email']); 
    $password = $conn->real_escape_string($_POST['password']); 
    $user_type = $conn->real_escape_string($_POST['user_type']); 

  
    $sql = "INSERT INTO users (name, surname, email, password, user_type) VALUES ('$name', '$surname', '$email', '$password', '$user_type')";

    if ($conn->query($sql) === TRUE) {
        echo "Përdoruesi është shtuar me sukses!";
    } else {
        echo "Gabim: " . $conn->error;
    }

    $conn->close(); 
}
?>
