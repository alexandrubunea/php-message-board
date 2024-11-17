<?php

// Database configuration
$servername = "localhost";
$username = "admin";
$password = "password";
$dbname = "the_message_board";

// Trying to connect to the database
try {
    $conn = new PDO("pgsql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}