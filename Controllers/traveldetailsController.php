<?php
session_start();
include '../../auth/config/config.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../auth/login.php");
    exit();
}


if (!isset($_GET['id'])) {
    echo "Travel details not found.";
    exit();
}

$travelId = $_GET['id'];


$stmt = $conn->prepare("SELECT bookings.id AS booking_id, users.name, users.surname, users.email, users.phone, flights.flight_number, flights.departure_location, flights.arrival_location, flights.departure_time, flights.arrival_time, flights.price
                        FROM bookings
                        JOIN users ON bookings.user_id = users.id
                        JOIN flights ON bookings.flight_id = flights.id
                        WHERE bookings.id = ?");
$stmt->bind_param("i", $travelId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Travel details not found.";
    exit();
}

$details = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../web-design/css/style.css">
    <title>Travel Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .details {
            margin: 20px 0;
        }

        .details p {
            font-size: 16px;
            line-height: 1.6;
        }

        .details strong {
            color: #007bff;
        }

        .back-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
        }

        .back-btn a {
            padding: 10px 20px;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            border-radius: 5px;
            font-size: 16px;
        }

        .back-btn a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Travel Details</h1>
        <div class="details">
            <p><strong>Booking ID:</strong> <?= htmlspecialchars($details['booking_id']); ?></p>
            <p><strong>Passenger Name:</strong> <?= htmlspecialchars($details['name']) . ' ' . htmlspecialchars($details['surname']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($details['email']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($details['phone']); ?></p>
            <hr>
            <p><strong>Flight Number:</strong> <?= htmlspecialchars($details['flight_number']); ?></p>
            <p><strong>Departure Location:</strong> <?= htmlspecialchars($details['departure_location']); ?></p>
            <p><strong>Arrival Location:</strong> <?= htmlspecialchars($details['arrival_location']); ?></p>
            <p><strong>Departure Time:</strong> <?= htmlspecialchars($details['departure_time']); ?></p>
            <p><strong>Arrival Time:</strong> <?= htmlspecialchars($details['arrival_time']); ?></p>
            <p><strong>Price:</strong> <?= htmlspecialchars($details['price']); ?> €</p>
        </div>
        <div class="back-btn">
            <a href="bookings.php">Back to Bookings</a>
        </div>
    </div>
</body>
</html>