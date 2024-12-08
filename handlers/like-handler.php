<?php

function doesLikeAlreadyExists($comment_id, $message_id): int
{
    /**
     * @var PDO $conn
     */

    if($_SERVER['REQUEST_METHOD'] != 'POST')
        return -1;

    if(!isset($_SESSION['username']))
        return -1;

    include '../db.php';

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

function addLikeToMessage($message_id): string
{
    /**
     * @var PDO $conn
     */
    include '../db.php';

    if($_SERVER['REQUEST_METHOD'] != 'POST')
        return "error";

    if(!isset($_SESSION['username']))
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

function removeLikeFromMessage($message_id): string
{
    /**
     * @var PDO $conn
     */
    include '../db.php';

    if($_SERVER['REQUEST_METHOD'] != 'POST')
        return "error";

    if(!isset($_SESSION['username']))
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

function addLikeToComment(): void
{
    /**
     * @var PDO $conn
     */
}

function removeLikeFromComment(): void
{
    /**
     * @var PDO $conn
     */
}

function fetchLikesOfMessage($message_id): int
{
    /**
     * @var PDO $conn
     */

    return 0;
}

function fetchLikesOfComment($message_id): int
{
    /**
     * @var PDO $conn
     */

    return 0;
}