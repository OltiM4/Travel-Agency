<?php

include $_SERVER['DOCUMENT_ROOT'] . '/project/Data/auth/config/config.php';

class AgencyController {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::getConnection(); 
    }

 
    public function getAgencies() {
        $query = "SELECT * FROM agencies"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $agencies = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $agencyDto = array_map(function ($agency) {
            return [
                'AgencyID' => $agency['AgencyID'],
                'AgencyName' => $agency['AgencyName'],
                'Location' => $agency['Location'],
                'Phone' => $agency['Phone']
            ];
        }, $agencies);

        echo json_encode($agencyDto);  
    }

  
    public function createAgency($agencyDto) {
        $query = "INSERT INTO agencies (AgencyName, Location, Phone) VALUES (:AgencyName, :Location, :Phone)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':AgencyName', $agencyDto['AgencyName']);
        $stmt->bindParam(':Location', $agencyDto['Location']);
        $stmt->bindParam(':Phone', $agencyDto['Phone']);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Agency created successfully"]);
        } else {
            echo json_encode(["message" => "Error creating agency"]);
        }
    }

  
    public function addClientToAgency($model) {
        $query = "INSERT INTO agency_clients (AgencyID, ClientID) VALUES (:AgencyID, :ClientID)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':AgencyID', $model['AgencyID']);
        $stmt->bindParam(':ClientID', $model['ClientID']);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Client added to agency successfully"]);
        } else {
            echo json_encode(["message" => "Error adding client"]);
        }
    }

    
    public function getClientsFromAgency() {
        $query = "SELECT c.ClientName, a.AgencyName
                  FROM agency_clients ac
                  JOIN clients c ON ac.ClientID = c.ClientID
                  JOIN agencies a ON ac.AgencyID = a.AgencyID";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($clients);
    }

 
    public function updateAgency($id, $agencyDto) {
        $query = "UPDATE agencies SET AgencyName = :AgencyName, Location = :Location, Phone = :Phone WHERE AgencyID = :AgencyID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':AgencyName', $agencyDto['AgencyName']);
        $stmt->bindParam(':Location', $agencyDto['Location']);
        $stmt->bindParam(':Phone', $agencyDto['Phone']);
        $stmt->bindParam(':AgencyID', $id);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Agency updated successfully"]);
        } else {
            echo json_encode(["message" => "Error updating agency"]);
        }
    }


    public function deleteAgency($id) {
        $query = "DELETE FROM agencies WHERE AgencyID = :AgencyID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':AgencyID', $id);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Agency deleted successfully"]);
        } else {
            echo json_encode(["message" => "Error deleting agency"]);
        }
    }
}

class DatabaseConnection {
    private static $connection;

    public static function getConnection() {
        if (self::$connection == null) {
            try {
                self::$connection = new PDO("mysql:host=localhost;dbname=agency_database", "root", "");  // Emri i bazës së të dhënave "agency_database"
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
        return self::$connection;
    }
}



$agencyController = new AgencyController();

$agencyController->getAgencies();


$agencyController->createAgency([
    'AgencyName' => 'Best Travel Agency',
    'Location' => 'New York',
    'Phone' => '123-456-789'
]);


$agencyController->addClientToAgency([
    'AgencyID' => 1,
    'ClientID' => 2
]);


$agencyController->getClientsFromAgency();


$agencyController->updateAgency(1, [
    'AgencyName' => 'Updated Travel Agency',
    'Location' => 'Los Angeles',
    'Phone' => '987-654-321'
]);

$agencyController->deleteAgency(1);

?>