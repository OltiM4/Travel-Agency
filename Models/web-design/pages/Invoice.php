<?php
class Invoice
{
    private $conn;
    private $invoiceId;
    private $userId;
    private $transactionId;
    private $amount;
    private $status;
    private $createdAt;
    private $updatedAt;

    public function __construct($dbConnection, $userId, $transactionId, $amount)
    {
        $this->conn = $dbConnection;
        $this->userId = $userId;
        $this->transactionId = $transactionId;
        $this->amount = $amount;
        $this->status = 'pending';  
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    
    public function generateInvoice()
    {
        $sql = "INSERT INTO invoices (user_id, transaction_id, amount, status, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isds", $this->userId, $this->transactionId, $this->amount, $this->status, $this->createdAt, $this->updatedAt);

        if ($stmt->execute()) {
            $this->invoiceId = $stmt->insert_id; 
            return "Invoice generated with ID: " . $this->invoiceId;
        } else {
            return "Error: " . $this->conn->error;
        }
    }

    
    public function sendInvoice()
    {
        $subject = "Invoice #" . $this->invoiceId;
        $message = "Dear Customer,\n\nHere are the details of your invoice:\n";
        $message .= "Transaction ID: " . $this->transactionId . "\n";
        $message .= "Amount: $" . number_format($this->amount, 2) . "\n";
        $message .= "Status: " . $this->status . "\n";
        $message .= "Created At: " . $this->createdAt . "\n\nThank you for your business.";

        
        if (mail("customer@example.com", $subject, $message)) {
            return "Invoice sent to customer.";
        } else {
            return "Error: Failed to send invoice.";
        }
    }

    
    public function cancelInvoice()
    {
        $this->status = 'cancelled';
        return $this->updateInvoiceStatus();
    }

    
    public function getInvoiceStatus()
    {
        $sql = "SELECT status FROM invoices WHERE invoice_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $this->invoiceId);
        $stmt->execute();
        $stmt->bind_result($status);
        $stmt->fetch();
        return $status;
    }

    
    private function updateInvoiceStatus()
    {
        $sql = "UPDATE invoices SET status = ?, updated_at = ? WHERE invoice_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssi", $this->status, $this->updatedAt, $this->invoiceId);

        if ($stmt->execute()) {
            return "Invoice status updated to: " . $this->status;
        } else {
            return "Error: " . $this->conn->error;
        }
    }
}
?>