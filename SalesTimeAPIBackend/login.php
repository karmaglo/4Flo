<?php
//Login
// Set the content type to JSON
// List of allowed origins for CORS
$allowedOriginList = array('http://localhost:4200');

// Check if the request's origin is in the allowed list
if (isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $allowedOriginList)) {
    $origin = $_SERVER['HTTP_ORIGIN'];
} else {
    $origin = '*';
}

// Set CORS headers
header('Access-Control-Allow-Origin: ' . $origin);
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header('Access-Control-Allow-Headers: Content-Type, Cookie');

// Decode the JSON payload from the POST request
$_POST = json_decode(file_get_contents('php://input'), true);

// Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// Replace mit  actual user validation logic, e.g., checking against a database
if ($username == 'validUser' && $password == 'validPassword') {
    // If the credentials are valid, start the session and store user information
    session_start(); // Start the session or resume the existing session
    $_SESSION['user_id'] = 123; // Example user ID

    // Send a success message as a JSON response
    echo json_encode(array("message" => "Successful login.", "status" => 200, "loginState" => "LOGGED_IN"));
} else {
    // If the credentials are invalid, send a failure message as a JSON response
    echo json_encode(array("message" => "Login failed.", "status" => 200, "loginState" => "FAILED"));
}
?>
