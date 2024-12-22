<?php
// Include database and Vacation model
include_once '../config/database.php';
include_once '../models/Vacation.php';

class VacationController {
    private $db;
    private $vacation;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->vacation = new Vacation($this->db);
    }

    // Request vacation
    public function requestVacation() {
        $data = json_decode(file_get_contents("php://input"));

        if (!$this->validateVacationData($data)) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid vacation request data."));
            return;
        }

        $this->vacation->employee_id = $data->employee_id;
        $this->vacation->start_date = $data->start_date;
        $this->vacation->end_date = $data->end_date;
        $this->vacation->reason = $data->reason;
        $this->vacation->status = 'pending';

        if($this->vacation->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Vacation request was created."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create vacation request."));
        }
    }

    // Get vacation requests
    public function getVacationRequests() {
        $employee_id = isset($_GET['employee_id']) ? $_GET['employee_id'] : null;
        $result = $this->vacation->read($employee_id);

        if($result && $result->rowCount() > 0) {
            $vacation_arr = array();
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                array_push($vacation_arr, $row);
            }
            http_response_code(200);
            echo json_encode($vacation_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No vacation requests found."));
        }
    }

    // Update vacation request
    public function updateVacationRequest() {
        $data = json_decode(file_get_contents("php://input"));

        if (!$this->validateVacationData($data, true)) {
            http_response_code(400);
            echo json_encode(array("message" => "Invalid vacation update data."));
            return;
        }

        $this->vacation->id = $data->id;
        $this->vacation->start_date = $data->start_date;
        $this->vacation->end_date = $data->end_date;
        $this->vacation->reason = $data->reason;

        if($this->vacation->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Vacation request was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update vacation request."));
        }
    }

    // Delete vacation request
    public function deleteVacationRequest() {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing vacation request ID."));
            return;
        }

        $this->vacation->id = $data->id;

        if($this->vacation->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Vacation request was deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete vacation request."));
        }
    }

    // Approve or reject vacation request
    public function updateVacationStatus() {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->id) || !isset($data->status)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required data for status update."));
            return;
        }

        $this->vacation->id = $data->id;
        $status = $data->status;

        if($this->vacation->updateStatus($status)) {
            http_response_code(200);
            echo json_encode(array("message" => "Vacation request status was updated."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to update vacation request status."));
        }
    }

    // Get total vacation days
    public function getTotalVacationDays() {
        if (!isset($_GET['employee_id']) || !isset($_GET['year'])) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing employee ID or year."));
            return;
        }

        $employee_id = $_GET['employee_id'];
        $year = $_GET['year'];

        $total_days = $this->vacation->getTotalVacationDays($employee_id, $year);

        http_response_code(200);
        echo json_encode(array("total_vacation_days" => $total_days));
    }

    private function validateVacationData($data, $isUpdate = false) {
        $requiredFields = ['employee_id', 'start_date', 'end_date', 'reason'];
        if ($isUpdate) {
            array_unshift($requiredFields, 'id');
        }
        foreach ($requiredFields as $field) {
            if (!isset($data->$field)) {
                return false;
            }
        }
        return true;
    }
}