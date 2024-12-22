<?php
class EmployeeManagement {
    // Database connection
    private $conn;
    private $table_name = "employees";

    // Employee properties
    public $id;
    public $first_name;
    public $last_name;
    public $email;
    public $phone;
    public $position;
    public $department;
    public $hire_date;
    public $status; // e.g., 'active', 'inactive', 'on_leave'

    // Constructor with DB connection
    public function __construct($db) {
        $this->conn = $db;
    }

    // Create new employee
    public function create() {
        // SQL query to insert a new employee record
        $query = "INSERT INTO " . $this->table_name . "
                  SET first_name = :first_name, last_name = :last_name, email = :email,
                      phone = :phone, position = :position, department = :department,
                      hire_date = :hire_date, status = :status";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind parameters
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':position', $this->position);
        $stmt->bindParam(':department', $this->department);
        $stmt->bindParam(':hire_date', $this->hire_date);
        $stmt->bindParam(':status', $this->status);

        // Execute the query and return success or failure
        return $stmt->execute();
    }

    // Read employee records (can be for a specific employee or all)
    public function read($id = null) {
        if ($id) {
            // SQL query to read a specific employee's details
            $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id";
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
            // Bind parameter
            $stmt->bindParam(':id', $id);
        } else {
            // SQL query to read all employees' details
            $query = "SELECT * FROM " . $this->table_name;
            // Prepare the statement
            $stmt = $this->conn->prepare($query);
        }

        // Execute the query
        if ($stmt->execute()) {
            return $stmt; // Return statement for fetching results later
        }

        return null; // Return null if execution fails
    }

    // Update employee record
    public function update() {
        // Implementation for updating an employee record
    }

    // Delete employee record
    public function delete() {
        // Implementation for deleting an employee record (or setting status to inactive)
    }

    // Get employees by department
    public function getEmployeesByDepartment($department) {
        // Implementation for retrieving employees in a specific department
    }

    // Search employees
    public function searchEmployees($keyword) {
        // Implementation for searching employees based on name, email, or other criteria
    }

    // Get employee work history
    public function getWorkHistory($employee_id) {
        // Implementation for retrieving an employee's work history
    }

    // Update employee status
    public function updateStatus($status) {
        // Implementation for updating an employee's status
    }
}