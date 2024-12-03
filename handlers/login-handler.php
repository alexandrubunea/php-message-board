<?php

function handleRequest(&$errorText): void
{
    /**
     * @var PDO $conn
     */

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_SESSION["username"] != "") {
            header("Location: index.php");
            exit();
        }

        include '../db.php';

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
            header("Location: ../index.php");
            exit();

        } catch(PDOException) {
            $errorText = "Something went wrong, try again later!";
        }
    }
}
