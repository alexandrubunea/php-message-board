<?php
session_start();

$data = json_decode(file_get_contents("php://input"));

if(!isset($_SESSION['csrf_token'])){
    http_response_code(403);
    echo json_encode([array("status" => "error", "message" => "CSRF Token Required")]);
    die;
}

if($data['csrf_token'] !== $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(array("status" => "error", "message" => "Invalid CSRF Token"));
    die;
}

if(empty($data['comment_id'])) {
   http_response_code(400);
   echo json_encode(array("status" => "error", "message" => "Comment ID is required"));
   die;
}