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
            c.comment_id,
            c.content,
            u.username as author,
            c.created_at,
            CASE
                WHEN l.user_id IS NOT NULL THEN 1
                ELSE 0
                END AS is_liked,
            COUNT(l2) as number_of_likes,
            (COUNT(l2) - EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - c.created_at)) / 3600) AS score
        FROM
            comments c
                JOIN
                    users u ON c.author_id = u.user_id
                LEFT JOIN
                    likes l ON l.user_id = :user_id AND l.comment_id = c.comment_id
                LEFT JOIN
                    likes l2 ON l2.comment_id = c.comment_id
        WHERE c.message_id = :message_id
        GROUP BY c.comment_id, c.content, u.username, c.created_at, l.user_id
        ORDER BY score DESC";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            'message_id' => $_GET['id'],
            'user_id' => $_SESSION['user_id'] ?? null
        ]);

        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach($rows as $row) {
            $result = [];
            $result['comment_id'] = $row['comment_id'];
            $result['content'] = $row['content'];
            $result['author'] = $row['author'];
            $result['is_liked'] = $row['is_liked'];
            $result['likes'] = $row['number_of_likes'];

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

function checkCommentOwnership(int $comment_id, int $user_id, PDO $conn): bool
{
    $sql_command = "SELECT COUNT(*) FROM comments WHERE comment_id = :comment_id AND author_id = :user_id";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':comment_id' => $comment_id,
            ':user_id' => $user_id
        ]);

        return $stmt->fetchColumn() > 0;
    } catch(PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

function deleteComment(int $comment_id, PDO $conn): string
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return "error";

    if(!isUserLoggedIn())
        return "error";

    if(!checkCommentOwnership($comment_id, $_SESSION['user_id'], $conn))
        return "error";

    $conn->beginTransaction();
    try {
        $stmt = $conn->prepare("DELETE FROM likes WHERE comment_id = :comment_id");
        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM comments WHERE comment_id = :comment_id");
        $stmt->bindParam(':comment_id', $comment_id, PDO::PARAM_INT);
        $stmt->execute();

        $conn->commit();
    } catch (PDOException $e) {
        error_log($e->getMessage());
        return "error";
    }

    return "success";
}