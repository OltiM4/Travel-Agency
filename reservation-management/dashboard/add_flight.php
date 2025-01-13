<?php
include '../../auth/config/config.php';


// Kontrollo nëse forma është dorëzuar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $flight_number = $_POST['flight_number'];
    $departure_location = $_POST['departure_location'];
    $arrival_location = $_POST['arrival_location'];
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $price = $_POST['price'];

    // Shto fluturimin në bazën e të dhënave
    $sql = "INSERT INTO flights (flight_number, departure_location, arrival_location, departure_time, arrival_time, price)
            VALUES ('$flight_number', '$departure_location', '$arrival_location', '$departure_time', '$arrival_time', '$price')";

    if ($conn->query($sql) === TRUE) {
        $message = "Flight added successfully!";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Flight</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f9;
        }
        .form-container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #ffffff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-container input, .form-container select {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45a049;
        }
        .message {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Add Flight</h2>
        <?php if (!empty($message)) : ?>
            <p class="message"><?= $message; ?></p>
        <?php endif; ?>
        <form action="add_flight.php" method="POST">
            <label for="flight_number">Flight Number:</label>
            <input type="text" id="flight_number" name="flight_number" required>

            <label for="departure_location">Departure Location:</label>
            <input type="text" id="departure_location" name="departure_location" required>

            <label for="arrival_location">Arrival Location:</label>
            <input type="text" id="arrival_location" name="arrival_location" required>

            <label for="departure_time">Departure Time:</label>
            <input type="datetime-local" id="departure_time" name="departure_time" required>

            <label for="arrival_time">Arrival Time:</label>
            <input type="datetime-local" id="arrival_time" name="arrival_time" required>

            <label for="price">Price:</label>
            <input type="number" id="price" name="price" step="0.01" required>

            <button type="submit">Add Flight</button>
        </form>
    </div>
</body>
</html>
