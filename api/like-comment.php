<?php
session_start();

$data = json_decode(file_get_contents("php://input"));

require_once '../utils.php';