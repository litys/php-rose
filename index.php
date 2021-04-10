<?php
header('Access-Control-Allow-Origin: *'); // Only for tests (disable on production)
header('Access-Control-Allow-Methods: GET, POST');
header("Content-Type: application/json");

include_once './system/auth.php';
include_once './config/database.php';
include_once './system/router.php';
include_once 'router.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = $_SERVER['REQUEST_URI'];

ROUTING::handle_request($method, $request);
