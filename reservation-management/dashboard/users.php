<?php
session_start();

// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

include '../../auth/config/config.php'; // Include the database configuration file

// Fetch all users
$usersQuery = $conn->query("SELECT * FROM users");

// Delete user
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $deleteQuery = $conn->prepare("DELETE FROM users WHERE id = ?");
    $deleteQuery->bind_param("i", $userId);
    $deleteQuery->execute();
}

// Edit user
if (isset($_POST['action']) && $_POST['action'] == 'edit') {
    $userId = $_POST['id'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $userType = $_POST['user_type'];

    $updateQuery = $conn->prepare("UPDATE users SET name = ?, surname = ?, email = ?, user_type = ? WHERE id = ?");
    $updateQuery->bind_param("ssssi", $name, $surname, $email, $userType, $userId);
    $updateQuery->execute();
}

// Fetch user by email
if (isset($_GET['action']) && $_GET['action'] == 'getByEmail' && isset($_GET['email'])) {
    $email = $_GET['email'];
    $getByEmailQuery = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $getByEmailQuery->bind_param("s", $email);
    $getByEmailQuery->execute();
    $result = $getByEmailQuery->get_result();
    $userByEmail = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../web-design/css/style.css">
    <link rel="stylesheet" href="../../web-design/css/users.css">
    <title>Dashboard</title>
</head>

<body>
    <section id="header">
        <div class="header container">
            <div class="nav-bar">
                <div class="brand">
                    <a href="dashboard.php">
                        <h1><span>JO</span>-NA</h1>
                    </a>
                </div>
                <div class="nav-list">
                    <div class="hamburger">
                        <div class="bar"></div>
                    </div>
                    <ul>
                        <li><a href="dashboard.php" data-after="Dashboard">Dashboard</a></li>
                        <li><a href="users.php" data-after="Users">Users</a></li>
                        <li><a href="bookings.php" data-after="Bookings">Bookings</a></li>
                        <li><a href="../config/logout.php" data-after="Logout">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="users">
        <div class="users container">
            <h1>All Users</h1>
            <form method="POST" action="add_user.php">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="surname">Surname:</label>
                <input type="text" name="surname" required>
                <label for="email">Email:</label>
                <input type="email" name="email" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <label for="user_type">User Type:</label>
                <input type="text" name="user_type" required>
                <button type="submit">Add User</button>
            </form>

            <h2>Search User By Email</h2>
            <form method="GET" action="users.php">
                <label for="email">Enter Email:</label>
                <input type="email" name="email" required>
                <input type="hidden" name="action" value="getByEmail">
                <button type="submit">Search</button>
            </form>

            <?php if (isset($userByEmail)) { ?>
                <h3>User Found:</h3>
                <p><strong>ID:</strong> <?php echo $userByEmail['id']; ?></p>
                <p><strong>Name:</strong> <?php echo $userByEmail['name']; ?></p>
                <p><strong>Surname:</strong> <?php echo $userByEmail['surname']; ?></p>
                <p><strong>Email:</strong> <?php echo $userByEmail['email']; ?></p>
                <p><strong>User Type:</strong> <?php echo $userByEmail['user_type']; ?></p>
            <?php } ?>

            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Surname</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>User Type</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $usersQuery->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['surname']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['password']; ?></td>
                            <td><?php echo $row['user_type']; ?></td>
                            <td>
                                <a href="users.php?action=edit&id=<?php echo $row['id']; ?>">Edit</a>
                                <a href="users.php?action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure to delete this user?')">Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </section>

    <section id="footer">
        <div class="footer container">
            <div class="brand">
                <h1><span>JO</span>-NA</h1>
            </div>
            <div class="social-icon">
            </div>
            <p>&copy; 2023 JO-NA. All rights reserved</p>
        </div>
    </section>

    <script src="../../web-design/js/main.js"></script>
</body>

</html>
