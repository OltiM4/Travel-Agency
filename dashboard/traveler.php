<?php
// Start the session
session_start();

// Database connection
$conn = new mysqli("localhost", "root", "", "travel_agency");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle registration
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];

    $sql = "INSERT INTO travelers (name, email, password, phone) 
            VALUES ('$name', '$email', '$password', '$phone')";
    if ($conn->query($sql) === TRUE) {
        $message = "Registration successful! You can now log in.";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM travelers WHERE email = '$email'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $traveler = $result->fetch_assoc();
        if (password_verify($password, $traveler['password'])) {
            $_SESSION['traveler_id'] = $traveler['id'];
            $_SESSION['traveler_name'] = $traveler['name'];
            header("Location: traveler.php");
            exit;
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "No traveler found with this email.";
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: traveler.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Traveler Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            color: #555;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        button {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin: 10px 0;
            color: red;
        }
        .dashboard {
            text-align: center;
        }
        .logout {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #f44336;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout:hover {
            background: #e53935;
        }
    </style>
</head>
<body>
<div class="container">
    <?php if (!isset($_SESSION['traveler_id'])): ?>
        <h2>Traveler Management</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?= $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <h3>Register</h3>
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone">

            <button type="submit" name="register">Register</button>
        </form>

        <form method="POST" action="" style="margin-top: 30px;">
            <h3>Login</h3>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
    <?php else: ?>
        <div class="dashboard">
            <h2>Welcome, <?= $_SESSION['traveler_name']; ?>!</h2>
            <p>You are logged in. Manage your bookings here.</p>
            <a class="logout" href="?logout=true">Logout</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
