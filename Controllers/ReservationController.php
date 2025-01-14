<?php

include $_SERVER['DOCUMENT_ROOT'] . '/project/Data/auth/config/config.php';

class ReservationController {
    private $conn;

    public function __construct() {
        $this->conn = DatabaseConnection::getConnection(); 
    }

   
    public function getReservations() {
        $query = "SELECT * FROM reservations";  
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $reservationDto = array_map(function ($reservation) {
            return [
                'ReservationID' => $reservation['ReservationID'],
                'ClientID' => $reservation['ClientID'],
                'AgencyID' => $reservation['AgencyID'],
                'ReservationDate' => $reservation['ReservationDate'],
                'Location' => $reservation['Location'],
                'Guests' => $reservation['Guests']
            ];
        }, $reservations);

        echo json_encode($reservationDto);  
    }

   
    public function createReservation($reservationDto) {
        $query = "INSERT INTO reservations (ClientID, AgencyID, ReservationDate, Location, Guests) VALUES (:ClientID, :AgencyID, :ReservationDate, :Location, :Guests)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ClientID', $reservationDto['ClientID']);
        $stmt->bindParam(':AgencyID', $reservationDto['AgencyID']);
        $stmt->bindParam(':ReservationDate', $reservationDto['ReservationDate']);
        $stmt->bindParam(':Location', $reservationDto['Location']);
        $stmt->bindParam(':Guests', $reservationDto['Guests']);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Reservation created successfully"]);
        } else {
            echo json_encode(["message" => "Error creating reservation"]);
        }
    }

   
    public function updateReservation($id, $reservationDto) {
        $query = "UPDATE reservations SET ClientID = :ClientID, AgencyID = :AgencyID, ReservationDate = :ReservationDate, Location = :Location, Guests = :Guests WHERE ReservationID = :ReservationID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ClientID', $reservationDto['ClientID']);
        $stmt->bindParam(':AgencyID', $reservationDto['AgencyID']);
        $stmt->bindParam(':ReservationDate', $reservationDto['ReservationDate']);
        $stmt->bindParam(':Location', $reservationDto['Location']);
        $stmt->bindParam(':Guests', $reservationDto['Guests']);
        $stmt->bindParam(':ReservationID', $id);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Reservation updated successfully"]);
        } else {
            echo json_encode(["message" => "Error updating reservation"]);
        }
    }

    
    public function deleteReservation($id) {
        $query = "DELETE FROM reservations WHERE ReservationID = :ReservationID";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':ReservationID', $id);
       
        if ($stmt->execute()) {
            echo json_encode(["message" => "Reservation deleted successfully"]);
        } else {
            echo json_encode(["message" => "Error deleting reservation"]);
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



$reservationController = new ReservationController();


$reservationController->getReservations();


$reservationController->createReservation([
    'ClientID' => 1,
    'AgencyID' => 2,
    'ReservationDate' => '2023-12-15',
    'Location' => 'Paris',
    'Guests' => 3
]);


$reservationController->updateReservation(1, [
    'ClientID' => 1,
    'AgencyID' => 3,
    'ReservationDate' => '2023-12-20',
    'Location' => 'Rome',
    'Guests' => 2
]);


$reservationController->deleteReservation(1);