<?php
include '../../auth/config/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_number = $_POST['flight_number'];
    $departure_location = $_POST['departure_location'];
    $arrival_location = $_POST['arrival_location'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("INSERT INTO flights (flight_number, departure_location, arrival_location, departure_time, arrival_time, price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $flight_number, $departure_location, $arrival_location, $departure_time, $arrival_time, $price);
    if ($stmt->execute()) {
        echo "Flight added successfully!";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>

    <link rel="stylesheet" href="../../web-design/css/flights.css">
</head>
<body>
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="brand">
                    <a href="dashboard.php">
                        <h1>JO-NA</h1>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section id="add-flight">
        <div class="container">
            <h1>Add Flight</h1>
            <form method="POST" class="flights-form">
                <input type="text" name="flight_number" placeholder="Flight Number" required>
                <input type="text" name="departure_location" placeholder="Departure Location" required>
                <input type="text" name="arrival_location" placeholder="Arrival Location" required>
                <input type="datetime-local" name="departure_time" required>
                <input type="datetime-local" name="arrival_time" required>
                <input type="number" name="price" placeholder="Price" required>
                <button type="submit">Add Flight</button>
            </form>
        </div>
    </section>

    <section id="footer">
        <div class="footer container">
            <div class="brand">
                <h1><span>JO</span>- NA</h1>
            </div>
            <p>&copy; 2023 JO-NA. All rights reserved.</p>
        </div>
    </section>
</body>
</html>
