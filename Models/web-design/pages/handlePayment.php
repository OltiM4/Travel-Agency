<?php
function handlePayment($userId, $amount) {
    $conn = new mysqli('localhost', 'root', 'password', 'user_database');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $transaction = new Transaction($conn, $userId, $amount);

    $transactionId = $transaction->startTransaction();
    echo "Transaction started with ID: $transactionId";

    if ($transaction->completeTransaction()) {
        echo "Transaction perfundoi me sukses !";
    } else {
        echo "Transaksioni deshtoi";
    }

    echo "Transaction status: " . $transaction->getTransactionStatus();


    $conn->close();
}
