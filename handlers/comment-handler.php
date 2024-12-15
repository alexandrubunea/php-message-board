<?php

function createComment(string &$errorText, PDO $conn): void
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return;

    if(!isUserLoggedIn())
        return;

    if(isset($_POST['csrf_token']) && !checkCSRFToken($_POST['csrf_token']))
        return;

    if(empty($_GET['id']))
        return;

    if(strlen(trim($_POST['comment-text-area'])) === 0) {
        $errorText = 'The comment cannot be empty.';
        return;
    }

    $sql_command = "INSERT INTO comments (author_id, message_id, content) VALUES(:author_id, :message_id, :content)";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            'author_id' => $_SESSION['user_id'],
            'message_id' => $_GET['id'],
            'content' => $_POST['comment-text-area']
        ]);

        header('Location: ../pages/message.php?id=' . $_GET['id'] . '#comments');
    } catch(PDOException $e) {
        error_log($e->getMessage());

        $errorText = 'Something went wrong while trying to create comment.';
    }
}

function viewComments(string &$errorText, PDO $conn): array
{
    $result_arr = [];

    if(empty($_GET['id']))
        return $result_arr;

    $sql_command = "
        SELECT
            c.content,
            u.username as author,
            c.created_at
            FROM 
                comments c 
            JOIN
                users u
            ON 
                c.author_id = u.user_id
            WHERE message_id = :message_id
            ORDER BY c.created_at DESC";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->bindValue(':message_id', $_GET['id'], PDO::PARAM_INT);
        $stmt->execute();

        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row) {
            $result = [];
            $result['content'] = $row['content'];
            $result['author'] = $row['author'];

            $formatted_date = "";
            try {
                $formatted_date = (new DateTime($row['created_at']))->format('d F Y H:i');
            } catch (DateMalformedStringException) {
                $errorText = 'Something went wrong while trying to view comments.';
            }

            $result['date'] = $formatted_date;

            $result_arr[] = $result;
        }
    } catch(PDOException $e) {
        error_log($e->getMessage());

        $errorText = 'Something went wrong while trying to view comments.';
        return $result_arr;
    }

    return $result_arr;
}