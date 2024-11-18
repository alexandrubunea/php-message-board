<?php
function handleRequest(&$errorText, &$accountCreated) {
    /**
     * @var PDO $conn
     */

    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        include '../db.php';

        $username = trim($_POST['username']);
        $password = $_POST['password'];

        if(empty($username) || empty($password)){
            $errorText = "Username or Password is empty";
            return;
        }

        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql_command = "INSERT INTO users (username, password) VALUES (:username, :password)";

        $stmt = $conn->prepare($sql_command);

        try {
            $stmt->execute([
                ':username' => $username,
                ':password' => $password_hashed
            ]);
            $accountCreated = true;
        } catch (PDOException $e) {
            if($e->getCode() == '23505'){
                $errorText = "Username already taken";
                return;
            }

            $errorText = "Something went wrong, try again later!";
        }
    }
}
