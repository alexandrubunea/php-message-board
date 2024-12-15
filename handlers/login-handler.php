<?php

use Random\RandomException;

function handleRequest(string &$errorText, PDO $conn): void
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return;

    if(isUserLoggedIn()) {
        header("Location: ../index.php");
        die;
    }

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if(empty($username) || empty($password)){
        $errorText = "Username or Password is empty";
        return;
    }

    $sql_command = "SELECT * FROM users WHERE username = :username";

    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':username' => $username
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$result || !password_verify($password, $result['password'])){
            $errorText = "Invalid username or password";
            return;
        }

        session_start();
        $_SESSION['user_id'] = $result['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        header("Location: ../index.php");
        exit();

    } catch(PDOException|RandomException $e) {
        error_log($e->getMessage());

        $errorText = "Something went wrong, try again later!";
    }
}
