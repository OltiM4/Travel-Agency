<?php
session_start();
include '../../Data/auth/config/config.php'; 

$paymentSuccessful = false;
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['payment_submit'])) {
    $amount = (double) $_POST['amount']; 
    $method = $_POST['method'];
    $user_id = (int) $_SESSION['user_id']; 
    
    $stmt = $conn->prepare("INSERT INTO Payments (user_id, amount, payment_method, payment_status) VALUES (?, ?, ?, 'Pending')");
    $stmt->bind_param("ids", $user_id, $amount, $method); 
    
    if ($stmt->execute()) {
        $paymentSuccessful = true; 
    } else {
        echo "<p>Error submitting payment: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .payment-form {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            width: 300px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }
        input, select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #cccccc;
        }
        button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background-color: #45a049;
        }
    </style>
    <?php if ($paymentSuccessful): ?>
    <script>
        setTimeout(function() {
            window.location.href = '../../Models/web-design/pages/index.php'; // Redirect to index after 3 seconds
        }, 3000);
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="payment-form">
        <h1>Payment Form</h1>
        <form method="post">
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" required>
            </div>
            <div class="form-group">
                <label for="method">Payment Method:</label>
                <select id="method" name="method">
                    <option value="Credit Card">Credit Card</option>
                    <option value="PayPal">PayPal</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                </select>
            </div>
            <button type="submit" name="payment_submit">Submit Payment</button>
            <?php if ($paymentSuccessful): ?>
            <p>Payment submitted successfully!</p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
