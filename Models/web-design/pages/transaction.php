<?php
class Transaction
{
    private $conn;
    private $transactionId;
    private $userId;
    private $amount;
    private $status;
    private $transactionDate;

    public function __construct($dbConnection, $userId, $amount)
    {
        $this->conn = $dbConnection;
        $this->userId = $userId;
        $this->amount = $amount;
        $this->status = 'pending'; 
        $this->transactionDate = date('Y-m-d H:i:s'); 
    }

    public function startTransaction()
    {
        
        $this->transactionId = uniqid('txn_');

        
        $sql = "INSERT INTO transactions (transaction_id, user_id, amount, status, transaction_date)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        
        
        $stmt->bind_param("sisds", $this->transactionId, $this->userId, $this->amount, $this->status, $this->transactionDate);

        if ($stmt->execute()) {
            return $this->transactionId; 
        } else {
            return "Error: " . $this->conn->error; 
        }
    }

    
    public function completeTransaction()
    {
        $this->status = 'completed'; 
        return $this->updateTransactionStatus();
    }

    
    public function cancelTransaction()
    {
        $this->status = 'cancelled'; 
        return $this->updateTransactionStatus();
    }


    public function getTransactionStatus()
    {
        $sql = "SELECT status FROM transactions WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $this->transactionId); 
        $stmt->execute();
    
        
        $status = null; 
        $stmt->bind_result($status); 
    
        
        if ($stmt->fetch()) {
            return $status; 
        } else {
            return "Transaction not found"; 
        }
    }

    private function updateTransactionStatus()
    {
        $sql = "UPDATE transactions SET status = ? WHERE transaction_id = ?";
        $stmt = $this->conn->prepare($sql);
        
        
        $stmt->bind_param("ss", $this->status, $this->transactionId); 

        if ($stmt->execute()) {
            return "Transaction status updated to: " . $this->status;
        } else {
            return "Error: " . $this->conn->error; 
        }
    }
}
?>
