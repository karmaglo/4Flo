<?php
// feedback Kathrin ???
class Overtime {
    // Database connection
    private $conn;
    private $table_name = "overtime";

    // Overtime properties
    public $id;
    public $employee_id;
    public $date;
    public $hours;
    public $reason;
    public $status; // e.g., 'pending', 'approved', 'rejected'

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new overtime record
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET employee_id=:employee_id, date=:date, hours=:hours, reason=:reason, status=:status";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":employee_id", $this->employee_id);
        $stmt->bindParam(":date", $this->date);
        $stmt->bindParam(":hours", $this->hours);
        $stmt->bindParam(":reason", $this->reason);
        $stmt->bindParam(":status", $this->status);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Read overtime records (can be for a specific employee or all)
    public function read($employee_id = null) {
        $query = "SELECT * FROM " . $this->table_name;
        if($employee_id) {
            $query .= " WHERE employee_id = :employee_id";
        }

        $stmt = $this->conn->prepare($query);

        if($employee_id) {
            $stmt->bindParam(":employee_id", $employee_id);
        }

        $stmt->execute();
        return $stmt;
    }

    // Update overtime record
    public function update() {
        $query = "UPDATE " . $this->table_name . " 
                  SET hours=:hours, reason=:reason, status=:status 
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Bind values
        $stmt->bindParam(":hours", $this->hours);
        $stmt->bindParam(":reason", $this->reason);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete overtime record
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get total overtime hours for an employee in a given period
    public function getTotalOvertimeHours($employee_id, $start_date, $end_date) {
        $query = "SELECT SUM(hours) as total_hours FROM " . $this->table_name . " 
                  WHERE employee_id = :employee_id AND date BETWEEN :start_date AND :end_date";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":employee_id", $employee_id);
        $stmt->bindParam(":start_date", $start_date);
        $stmt->bindParam(":end_date", $end_date);

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_hours'];
    }

    // Approve or reject overtime request
    public function updateStatus($status) {
        $query = "UPDATE " . $this->table_name . " SET status = :status WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":status", $status);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}