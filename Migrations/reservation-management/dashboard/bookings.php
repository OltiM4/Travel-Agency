<?php
session_start();


if (!isset($_SESSION['user_id'])) {
    header("Location: ../../../../Models/web-design/pages/login.php");
    exit();
}

include $_SERVER['DOCUMENT_ROOT'] . '/project/Data/auth/config/config.php'; 


$bookingsQuery = $conn->query("SELECT * FROM bookings");
if (!$bookingsQuery) {
    die("Gabim në marrjen e të dhënave: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../Models/web-design/css/style.css">
    <link rel="stylesheet" href="../../../Models/web-design/css/bookings.css">
    <title>Bookings</title>
</head>

<body>
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="brand">
                    <a href="dashboard.php">
                        <h1><span>JO</span>- NA</h1>
                    </a>
                </div>
                <div class="nav-list">
                    <ul>
                        <li><a href="dashboard.php">Dashboard</a></li>
                        <li><a href="users.php">Users</a></li>
                        <li><a href="bookings.php">Bookings</a></li>
                        <li><a href="../../../../Data/auth/config/logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="bookings">
        <div class="bookings container">
            <h1>All Bookings</h1>
            <form method="POST" action="add_booking.php">
                <label for="user_id">User ID:</label>
                <input type="text" name="user_id" required>
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="surname">Surname:</label>
                <input type="text" name="surname" required>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <label for="phone">Phone:</label>
                <input type="text" name="phone" required>
                <label for="address">Address:</label>
                <input type="text" name="address" required>
                <label for="location">Location:</label>
                <input type="text" name="location" required>
                <label for="guests">Guests:</label>
                <input type="number" name="guests" required>
                <label for="arrival_date">Arrival Date:</label>
                <input type="date" name="arrival_date" required>
                <label for="leaving_date">Leaving Date:</label>
                <input type="date" name="leaving_date" required>
                <button type="submit">Add Booking</button>
            </form>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Guests</th>
                        <th>Arrival Date</th>
                        <th>Leaving Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $bookingsQuery->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['user_id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['surname']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                            <td><?php echo htmlspecialchars($row['guests']); ?></td>
                            <td><?php echo htmlspecialchars($row['arrival_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['leaving_date']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="footer">
        <div class="footer container">
            <div class="brand">
                <h1><span>JO</span>- NA</h1>
            </div>
            <p>&copy; 2023 JO-NA. All rights reserved</p>
        </div>
    </section>

    <script src="../../../../Models/web-design/js/main.js"></script>
</body>

</html>
