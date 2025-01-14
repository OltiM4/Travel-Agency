<?php

session_start();
include '../models/Database.php';
include '../models/Flight.php';
include '../models/Booking.php';
include '../models/User.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'manager') {
    header("Location: ../auth/login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    $db = new Database();
    $conn = $db->getConnection();

    if ($action === 'getFlights') {
        $flight = new Flight($conn);
        $flights = $flight->getAllFlights();
        echo json_encode($flights);
        exit();
    } elseif ($action === 'getBookings') {
        $booking = new Booking($conn);
        $bookings = $booking->getAllBookings();
        echo json_encode($bookings);
        exit();
    } elseif ($action === 'getUsers') {
        $user = new User($conn);
        $users = $user->getAllUsers();
        echo json_encode($users);
        exit();
    } else {
        echo json_encode(['status' => 'invalid action']);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Interface</title>
    <style>
  
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .section {
            margin: 20px 0;
        }

        .response {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manager Dashboard</h1>
        <div class="section">
            <button id="view-flights">View Flights</button>
            <div id="flights-container"></div>
        </div>
        <div class="section">
            <button id="view-bookings">View Bookings</button>
            <div id="bookings-container"></div>
        </div>
        <div class="section">
            <button id="view-users">View Users</button>
            <div id="users-container"></div>
        </div>
    </div>

    <script>
        
        function fetchAndDisplay(action, containerId) {
            fetch("manager.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=${action}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    const container = document.getElementById(containerId);
                    container.innerHTML = `<pre>${JSON.stringify(data, null, 2)}</pre>`;
                });
        }

        document.getElementById("view-flights").addEventListener("click", () => {
            fetchAndDisplay("getFlights", "flights-container");
        });

        document.getElementById("view-bookings").addEventListener("click", () => {
            fetchAndDisplay("getBookings", "bookings-container");
        });

        document.getElementById("view-users").addEventListener("click", () => {
            fetchAndDisplay("getUsers", "users-container");
        });
    </script>
</body>
</html>