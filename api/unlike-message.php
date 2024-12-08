<?php
session_start();

$headers = getallheaders();
$csrf_token = $headers['X-CSRF-Token'] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

if(!isset($_SESSION['csrf_token'])){
    http_response_code(403);
    echo json_encode([array("status" => "error", "message" => "CSRF Token Required")]);
    die;
}

if($csrf_token != $_SESSION['csrf_token']) {
    http_response_code(403);
    echo json_encode(array("status" => "error", "message" => "Invalid CSRF Token"));
    die;
}

if (!isset($data['message_id']) || !is_int($data['message_id']) || $data['message_id'] <= 0) {
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "Valid Message ID is required"));
    die;
}

$message_id = $data['message_id'];

include '../handlers/like-handler.php';

$does_like_already_exists = doesLikeAlreadyExists(null, $message_id);
if(!$does_like_already_exists) {
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "There is no such like for this message"));
    die;
}
if($does_like_already_exists == "error") {
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "There was an error with your request"));
    die;
}

$res = removeLikeFromMessage($message_id);
echo json_encode(array("status" => $res));