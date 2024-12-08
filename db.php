<?php

$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "the_message_board";

try {
    $conn = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    error_log($e->getMessage());
}