<?php

function doesLikeAlreadyExists(int|null $comment_id, int|null $message_id, PDO $conn): int
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return -1;

    if(!isUserLoggedIn())
        return -1;

    $sql_command = "SELECT COUNT(*) FROM likes WHERE user_id = :user_id";

    if($message_id != null)
        $sql_command .= " AND message_id = :message_id AND comment_id IS NULL";
    else
        $sql_command .= " AND comment_id = :comment_id AND message_id IS NULL";

    $stmt = $conn->prepare($sql_command);
    $user_id = $_SESSION['user_id'];

    try {
        if($message_id != null)
            $stmt->execute([
                ':user_id' => $user_id,
                ':message_id' => $message_id
            ]);
        else
            $stmt->execute([
                ':user_id' => $user_id,
                ':comment_id' => $comment_id
            ]);

        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "error";
    }
}

function addLikeToMessage(int $message_id, PDO $conn): string
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return "error";

    if(!isUserLoggedIn())
        return "error";

    $sql_command = "INSERT INTO likes(user_id, message_id) VALUES(:user_id, :message_id)";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':message_id' => $message_id
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "error";
    }

    return "success";
}

function removeLikeFromMessage(int $message_id, PDO $conn): string
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return "error";

    if(!isUserLoggedIn())
        return "error";

    $sql_command = "DELETE FROM likes WHERE message_id = :message_id AND user_id = :user_id";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':message_id' => $message_id
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "error";
    }

    return "success";
}

function addLikeToComment(int $comment_id, PDO $conn): string
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return "error";

    if(!isUserLoggedIn())
        return "error";

    $sql_command = "INSERT INTO likes(user_id, comment_id) VALUES(:user_id, :comment_id)";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':comment_id' => $comment_id
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "error";
    }

    return "success";
}

function removeLikeFromComment(int $comment_id, PDO $conn): string
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return "error";

    if(!isUserLoggedIn())
        return "error";

    $sql_command = "DELETE FROM likes WHERE comment_id = :comment_id AND user_id = :user_id";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':comment_id' => $comment_id
        ]);
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "error";
    }

    return "success";
}