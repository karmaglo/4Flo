<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

// Endpoints
$endpoints = ['employees', 'time-tracking', 'overtime', 'vacation', 'reports', 'auth'];

// Checks if the endpoint exists
if (!isset($uri[2]) || !in_array($uri[2], $endpoints)) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// Includes the appropriate controller
$controllerName = ucfirst($uri[2]) . 'Controller';
require "./controllers/{$controllerName}.php";

// Create the controller and process the request
$controller = new $controllerName();
$controller->processRequest();
?>