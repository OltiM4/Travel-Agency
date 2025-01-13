<?php

include '../../auth/config/config.php';


$message = '';


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reserve'])) {
    $itinerary_id = $_POST['itinerary_id'];
    $traveler_id = $_POST['traveler_id'];
    $details = $_POST['details'];


    $stmt = $conn->prepare("INSERT INTO reservations (itinerary_id, traveler_id, details) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $itinerary_id, $traveler_id, $details);

    if ($stmt->execute()) {
        $message = "Rezervimi u bë me sukses!";
        header("Location: itineraries.php?success=1");
        exit;
    } else {
        $message = "Gabim gjatë rezervimit: " . $stmt->error;
    }
}


if (isset($_GET['success']) && $_GET['success'] == 1) {
    $message = "Rezervimi u bë me sukses!";
}


$query = "SELECT * FROM itineraries";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../web-design/css/style.css">
    <title>Itineraret</title>
</head>
<body>
    <section id="itineraries">
        <div class="container">
            <h1 class="section-title">Eksploroni <span>Itineraret</span></h1>
            <p>Zbuloni eksperiencat dhe udhëtimet më të mira që kemi për ju!</p>

           
            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <div class="itineraries-list">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <div class="itinerary-item">
                        <img src="../img/itinerary.png" alt="Itinerary Image">
                        <div class="itinerary-content">
                            <h2>Travel Itinerary</h2>
                            <form action="itineraries.php" method="POST">
                                <input type="hidden" name="itinerary_id" value="<?= htmlspecialchars($row['id']); ?>">
                                <input type="hidden" name="traveler_id" value="<?= htmlspecialchars($row['traveler_id']); ?>">
                                <label for="details">Details:</label>
                                <textarea name="details" placeholder="Shkruaj detajet për udhëtimin" required></textarea>
                                <button type="submit" name="reserve" class="btn">Rezervo</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <style>
      
        #itineraries {
            padding: 50px 0;
            background-color: #f4f4f9;
        }

        #itineraries .container {
            max-width: 1100px;
            margin: 0 auto;
            text-align: center;
        }

        #itineraries .section-title {
            font-size: 36px;
            margin-bottom: 20px;
            color: #333;
        }

        .itineraries-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .itinerary-item {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .itinerary-item img {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .itinerary-content {
            text-align: center;
        }

        .itinerary-content h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .itinerary-content textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: vertical;
        }

        .itinerary-content .btn {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .itinerary-content .btn:hover {
            background-color: #45a049;
        }

        .message {
            color: green;
            font-size: 18px;
            margin-bottom: 20px;
        }
    </style>
</body>
</html>
