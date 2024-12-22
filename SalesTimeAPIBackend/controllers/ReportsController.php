<?php
// Include necessary models and configurations
//  API implementation phase (29.10.24 to 29.10.24)
include_once 'models/Report.php';
include_once 'config/database.php';

class ReportsController {
    private $db;
    private $report;

    // Constructor too initialize database connection and Report model
    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->report = new Report($this->db);
    }

    // Method to generate a report
    public function generateReport() {
        // Get input data from the request
        $data = json_decode(file_get_contents("php://input"));

        // Validate input datas
        if (!$this->validateInput($data)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid input. Please provide all required fields.']);
            return;
        }

        // Set report parameters from input data
        $this->report->user_id = $data->user_id;
        $this->report->start_date = $data->start_date;
        $this->report->end_date = $data->end_date;
        $this->report->task = $data->task;
        $this->report->description = $data->description;

        // Attempt to generate the report
        if ($this->report->generateReport()) {
            http_response_code(201);
            echo json_encode(['message' => 'Report was created successfully.']);
        } else {
            http_response_code(503);
            echo json_encode(['message' => 'Unable to create report. Please try again later.']);
        }
    }

    // Helper method to validate input data
    private function validateInput($data) {
        return isset($data->user_id) && isset($data->start_date) && isset($data->end_date) && isset($data->task);
    }
}

// Usage: Instantiate the controller and call the generateReport method
$controller = new ReportsController();
$controller->generateReport();