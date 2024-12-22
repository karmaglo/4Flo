<?php
// Include database and Overtime model
include_once '../config/database.php';
include_once '../models/Overtime.php';

class OvertimeController {
    private $db;
    private $overtime;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->overtime = new Overtime($this->db);
    }

    // Create new overtime record
    public function create() {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));

        // Set overtime property values
        $this->overtime->employee_id = $data->employee_id;
        $this->overtime->date = $data->date;
        $this->overtime->hours = $data->hours;
        $this->overtime->reason = $data->reason;
        $this->overtime->status = 'pending'; // Default status

        // Create the overtime record
        if($this->overtime->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Overtime record was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create overtime record."));
        }
    }

    // Read overtime records
    public function read() {
        $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
        $result = $this->overtime->read($employee_id);

        if($result->rowCount() > 0) {
            $overtime_arr = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $overtime_item = array(
                    "id" => $id,
                    "employee_id" => $employee_id,
                    "date" => $date,
                    "hours" => $hours,
                    "reason" => $reason,
                    "status" => $status
                );
                array_push($overtime_arr, $overtime_item);
            }
            http_response_code(200);
            echo json_encode($overtime_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No overtime records found."));
        }
    }

    // Update overtime record
    public function update() {
        $data = json_decode(file_get_contents("php://input"));

        $this->overtime->id = $data->id;
        $this->overtime->hours = $data->hours;
        $this->overtime->reason = $data->reason;

        if($this->overtime->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Overtime record was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update overtime record."));
        }
    }

    // Delete overtime record
    public function delete() {
        $data = json_decode(file_get_contents("php://input"));
        $this->overtime->id = $data->id;

        if($this->overtime->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Overtime record was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete overtime record."));
        }
    }

    // Get total overtime hours
    public function getTotalHours() {
        $employee_id = $_GET['employee_id'];
        $start_date = $_GET['start_date'];
        $end_date = $_GET['end_date'];

        $total_hours = $this->overtime->getTotalOvertimeHours($employee_id, $start_date, $end_date);

        http_response_code(200);
        echo json_encode(array("total_hours" => $total_hours));
    }

    // Update overtime status
    public function updateStatus() {
        $data = json_decode(file_get_contents("php://input"));

        $this->overtime->id = $data->id;
        $status = $data->status;

        if($this->overtime->updateStatus($status)) {
            http_response_code(200);
            echo json_encode(array("message" => "Overtime status was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update overtime status."));
        }
    }
}