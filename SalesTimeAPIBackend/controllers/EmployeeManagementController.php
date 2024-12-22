<?php
//Entwurf
//Controller file that defines the business logic related to employee management.
include_once '../config/database.php';
include_once '../models/EmployeeManagement.php';

class EmployeeManagementController {
    private $employeeManagement;

    public function __construct() {
        $database = new Database();
        $db = $database->getConnection();
        $this->employeeManagement = new EmployeeManagement($db);
    }

    public function getAllEmployees() {
        return $this->employeeManagement->read();
    }


}