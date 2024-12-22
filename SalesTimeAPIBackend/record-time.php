<?php
// Endpoint script that handles API requests for recording time tracking data.
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$servername = "your_server";
$username = "your_username";
$password = "your_password";
$dbname = "your_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents("php://input"));

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(["message" => "Invalid JSON"]);
    exit();
}

if (!isset($data->user_id, $data->start_time, $data->end_time, $data->task, $data->description)) {
    echo json_encode(["message" => "Missing required fields"]);
    exit();
}

$user_id = $data->user_id;
$start_time = $data->start_time;
$end_time = $data->end_time;
$task = $data->task;
$description = $data->description;

$stmt = $conn->prepare("INSERT INTO time_tracking (user_id, start_time, end_time, task, description) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $user_id, $start_time, $end_time, $task, $description);

if ($stmt->execute()) {
    echo json_encode(["message" => "Record created successfully"]);
} else {
    echo json_encode(["message" => "Error: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>