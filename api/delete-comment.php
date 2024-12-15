<?php
session_start();

$headers = getallheaders();
$csrf_token = $headers['X-CSRF-Token'] ?? null;
$data = json_decode(file_get_contents("php://input"), true);

require_once '../utils.php';
require_once '../db.php';

/**
 * @var PDO $conn
 */

if(!checkCSRFToken($csrf_token)) {
    http_response_code(403);
    echo json_encode(array("status" => "error", "message" => "Invalid CSRF Token"));
    die;
}

if (!isset($data['comment_id']) || !is_int($data['comment_id']) || $data['comment_id'] <= 0) {
    http_response_code(400);
    echo json_encode(array("status" => "error", "message" => "Valid Comment ID is required"));
    die;
}

$comment_id = $data['comment_id'];

require_once '../handlers/comment-handler.php';

$res = deleteComment($comment_id, $conn);
echo json_encode(array("status" => $res));