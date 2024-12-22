<?php
// Include necessary files
include_once '../config/database.php';
include_once '../models/TimeTracking.php';

class TimeTrackingController {
    private $db;
    private $timeTracking;

    // Constructor to initialize database connection and TimeTracking model
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->timeTracking = new TimeTracking($this->db);
    }
    // Start time tracking for an employee
    public function startTracking() {
        $data = json_decode(file_get_contents("php://input"));

        // Validate input
        if (!isset($data->employee_id)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing employee ID."));
            return;
        }

        $this->timeTracking->employee_id = $data->employee_id;
        $this->timeTracking->start_time = date('Y-m-d H:i:s');

        // Attempt to start tracking
        if($this->timeTracking->startTracking()) {
            http_response_code(201);
            echo json_encode(array("message" => "Time tracking started.", "id" => $this->timeTracking->id));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to start time tracking."));
        }
    }

    // Stop time tracking for a specific tracking session
    public function stopTracking() {
        $data = json_decode(file_get_contents("php://input"));

        // Validate input
        if (!isset($data->id)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing tracking ID."));
            return;
        }

        $this->timeTracking->id = $data->id;
        $this->timeTracking->end_time = date('Y-m-d H:i:s');

        // Attempt to stop tracking
        if($this->timeTracking->stopTracking()) {
            http_response_code(200);
            echo json_encode(array("message" => "Time tracking stopped."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to stop time tracking."));
        }
    }

    // Get time tracking records, optionally filtered by employee and date range
    public function getRecords() {
        $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

        $result = $this->timeTracking->getRecords($employee_id, $start_date, $end_date);

        // Check if records were found
        if($result && $result->rowCount() > 0) {
            $records_arr = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                array_push($records_arr, $row);
            }
            http_response_code(200);
            echo json_encode($records_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No time tracking records found."));
        }
    }

    // Update an existing time tracking record
    public function updateRecord() {
        $data = json_decode(file_get_contents("php://input"));

        // Validate input
        if (!isset($data->id) || !isset($data->start_time) || !isset($data->end_time)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required data for update."));
            return;
        }

        $this->timeTracking->id = $data->id;
        $this->timeTracking->start_time = $data->start_time;
        $this->timeTracking->end_time = $data->end_time;

        // Attempt to update the record
        if($this->timeTracking->updateRecord()) {
            http_response_code(200);
            echo json_encode(array("message" => "Time tracking record was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update time tracking record."));
        }
    }

    // Delete a time tracking record
    public function deleteRecord() {
        $data = json_decode(file_get_contents("php://input"));

        // Validate input
        if (!isset($data->id)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing tracking ID."));
            return;
        }

        $this->timeTracking->id = $data->id;

        // Attempt to delete the record
        if($this->timeTracking->deleteRecord()) {
            http_response_code(200);
            echo json_encode(array("message" => "Time tracking record was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete time tracking record."));
        }
    }

    // Get total hours worked for an employee within a date range
    public function getTotalHours() {
        // Validate input
        if (!isset($_GET['employee_id']) || !isset($_GET['start_date']) || !isset($_GET['end_date'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required parameters."));
            return;
        }

        $employee_id = $_GET['employee_id'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];

        $total_hours = $this->timeTracking->getTotalHours($employee_id, $start_date, $end_date);

        http_response_code(200);
        echo json_encode(array("total_hours" => $total_hours));
    }
}