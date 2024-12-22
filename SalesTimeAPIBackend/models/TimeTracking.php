<?php

class TimeTracker {
    private $conn;
    private $table_name = "time_tracking";

    public $user_id;
    public $start_time;
    public $end_time;
    public $task;
    public $description;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (user_id, start_time, end_time, task, description) VALUES (?, ?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            die("Error preparing statement: " . $this->conn->error);
        }

        $stmt->bind_param("issss", $this->user_id, $this->start_time, $this->end_time, $this->task, $this->description);

        if ($stmt->execute()) {
            return true;
        } else {
            die("Error executing statement: " . $stmt->error);
        }
    }
}
?>