<?php

session_start();
include '../models/Database.php';
include '../models/Flight.php';
include '../models/Booking.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    $db = new Database();
    $conn = $db->getConnection();

    if ($action === 'getFlights') {
        $flight = new Flight($conn);
        $flights = $flight->getAllFlights();
        echo json_encode($flights);
        exit();
    } elseif ($action === 'makeBooking') {
        $user_id = $_POST['user_id'];
        $flight_id = $_POST['flight_id'];
        $booking = new Booking($conn);
        $result = $booking->createBooking($user_id, $flight_id);
        echo json_encode(['status' => $result ? 'success' : 'failed']);
        exit();
    } elseif ($action === 'cancelBooking') {
        $booking_id = $_POST['booking_id'];
        $booking = new Booking($conn);
        $result = $booking->cancelBooking($booking_id);
        echo json_encode(['status' => $result ? 'success' : 'failed']);
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
    <title>Agent Interface</title>
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
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 10px 0;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        form {
            margin: 20px 0;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        input {
            display: block;
            width: 100%;
            margin: 10px 0;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        #flights-container {
            margin-top: 20px;
        }

        .flight {
            background: #e7e7e7;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
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
        <h1>Flight Management Agent</h1>
        <button id="fetch-flights">Get All Flights</button>
        <div id="flights-container"></div>
        <form id="booking-form">
            <h2>Make a Booking</h2>
            <input type="text" id="user-id" placeholder="User ID" required>
            <input type="text" id="flight-id" placeholder="Flight ID" required>
            <button type="submit">Book Flight</button>
        </form>
        <form id="cancel-form">
            <h2>Cancel a Booking</h2>
            <input type="text" id="booking-id" placeholder="Booking ID" required>
            <button type="submit">Cancel Booking</button>
        </form>
        <div id="response-container"></div>
    </div>
    <script>
  
        document.getElementById("fetch-flights").addEventListener("click", () => {
            fetch("agent.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: "action=getFlights",
            })
                .then((response) => response.json())
                .then((data) => {
                    const flightsContainer = document.getElementById("flights-container");
                    flightsContainer.innerHTML = "";
                    data.forEach((flight) => {
                        const flightDiv = document.createElement("div");
                        flightDiv.className = "flight";
                        flightDiv.innerHTML = `
                            <p><strong>Flight Number:</strong> ${flight.flight_number}</p>
                            <p><strong>Departure:</strong> ${flight.departure_location}</p>
                            <p><strong>Arrival:</strong> ${flight.arrival_location}</p>
                            <p><strong>Price:</strong> ${flight.price}€</p>
                        `;
                        flightsContainer.appendChild(flightDiv);
                    });
                });
        });

        document.getElementById("booking-form").addEventListener("submit", (e) => {
            e.preventDefault();
            const userId = document.getElementById("user-id").value;
            const flightId = document.getElementById("flight-id").value;

            fetch("agent.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=makeBooking&user_id=${userId}&flight_id=${flightId}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    const responseContainer = document.getElementById("response-container");
                    responseContainer.innerHTML = `<p class="response">${data.status === "success" ? "Booking successful!" : "Booking failed."}</p>`;
                });
        });

        document.getElementById("cancel-form").addEventListener("submit", (e) => {
            e.preventDefault();
            const bookingId = document.getElementById("booking-id").value;

            fetch("agent.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=cancelBooking&booking_id=${bookingId}`,
            })
                .then((response) => response.json())
                .then((data) => {
                    const responseContainer = document.getElementById("response-container");
                    responseContainer.innerHTML = `<p class="response">${data.status === "success" ? "Cancellation successful!" : "Cancellation failed."}</p>`;
                });
        });
    </script>
</body>
</html>