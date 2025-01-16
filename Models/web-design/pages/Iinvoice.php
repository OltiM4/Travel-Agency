<?php   

function handleInvoice($userId, $transactionId, $amount) {
   
    $conn = new mysqli('localhost', 'root', 'password', 'user_database');

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $invoice = new Invoice($conn, $userId, $transactionId, $amount);

    
    echo $invoice->generateInvoice(); 

    
    echo $invoice->sendInvoice(); 

    
    echo "Invoice Status: " . $invoice->getInvoiceStatus();

 
    echo $invoice->cancelInvoice();

    $conn->close();
}
?>
