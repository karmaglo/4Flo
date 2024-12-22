<?php
// talk about the vacation model with Katrin= feedback
class Vacation {
    // Database connection s
    private $conn;
    private $table_name = "vacation";

    // Vacation properties
    public $id;
    public $employee_id;
    public $start_date;
    public $end_date;
    public $days;
    public $reason;
    public $status; // e.g., 'pending', 'approved', 'rejected'

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new vacation request
    public function create() {
        // Implementation for creating a new vacation request
    }

    // Read vacation records (can be for a specific employee or all)
    public function read($employee_id = null) {
        // Implementation for reading vacation records
    }

    // Update vacation record
    public function update() {
        // Implementation for updating a vacation record
    }

    // Delete vacation record
    public function delete() {
        // Implementation for deleting a vacation record
    }

    // Calculate total vacation days for an employee in a given year
    public function getTotalVacationDays($employee_id, $year) {
        // Implementation for calculating total vacation days
    }

    // Approve or reject vacation request
    public function updateStatus($status) {
        // Implementation for updating the status of a vacation request
    }

    // CCheck if dates conflict with existing vacation requests
    public function checkConflict($employee_id, $start_date, $end_date) {
        // Implementation to check for conflicting vacation dates
    }
}