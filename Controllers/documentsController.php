// Placeholder content for documents.php
<?php
session_start();
include '../../auth/config/config.php';
include '../../models/Documents.php';


if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../auth/login.php");
    exit();
}

$documents = new Documents($conn);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['generate_csv'])) {
        $filename = $documents->generateCSV();
        if ($filename) {
            header("Content-Type: application/csv");
            header("Content-Disposition: attachment; filename=" . basename($filename));
            readfile($filename);
            unlink($filename); 
            exit();
        } else {
            $message = "No bookings available to export!";
        }
    } elseif (isset($_POST['generate_html'])) {
        $htmlReport = $documents->generateHTMLReport();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        .container {
            width: 80%;
            margin: auto;
        }

        h1 {
            text-align: center;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .report {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Documents Manager</h1>

        <?php if (isset($message)) { ?>
            <p style="color: red; text-align: center;"><?= htmlspecialchars($message); ?></p>
        <?php } ?>

        <div class="buttons">
            <form method="POST">
                <button type="submit" name="generate_csv">Generate CSV</button>
            </form>
            <form method="POST">
                <button type="submit" name="generate_html">Generate HTML Report</button>
            </form>
        </div>

        <?php if (isset($htmlReport)) { ?>
            <div class="report">
                <?= $htmlReport; ?>
            </div>
        <?php } ?>
    </div>
</body>
</html>