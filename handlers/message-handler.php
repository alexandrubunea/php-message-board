<?php

function createMessage(string &$errorText, PDO $conn): void
{
    require_once '../utils.php';

    if(!isPOSTRequest())
        return;

    if(!isUserLoggedIn())
        return;

    if(isset($_POST['csrf_token']) && !checkCSRFToken($_POST['csrf_token']))
        return;

    if(strlen(trim($_POST['title'])) === 0) {
        $errorText = 'Title cannot be empty';
        return;
    }
    if(strlen(trim($_POST['content'])) === 0) {
        $errorText = 'Message cannot be empty';
        return;
    }

    $dest = "(null)";
    if(!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if(!getimagesize($_FILES["image"]["tmp_name"])) {
            $errorText = "File is not an image.";
            return;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $_FILES["image"]["tmp_name"]);
        finfo_close($finfo);

        $allowed_mime_types = ['image/jpeg', 'image/png'];
        if (!in_array($mime_type, $allowed_mime_types)) {
            $errorText = 'Invalid file type.';
            return;
        }

        if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
            $errorText = "Only JPG, JPEG, PNG files are allowed.";
            return;
        }
        if($_FILES["image"]["size"] > 5000000) {
            $errorText = "File is too large.";
            return;
        }

        $dest = $target_dir . round(microtime(true)) . '.' . $image_file_type;
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $dest)) {
            $errorText = "Failed to upload the file.";
            return;
        }
    }

    $sql_command = "INSERT INTO messages (title, content, image_path, author) VALUES (:title, :content, :image_path, :author_id)";
    $stmt = $conn->prepare($sql_command);
    try {
        $stmt->execute([
            ':title' => $_POST['title'],
            ':content' => $_POST['content'],
            ':image_path' => $dest,
            ':author_id' => $_SESSION['user_id']
        ]);

        header("Location: ../pages/messages.php");
    } catch(PDOException $e) {
        error_log($e->getMessage());

        $errorText = "Something went wrong, try again later!";
    }
}

function viewMessages(string &$errorText, PDO $conn): array
{
    $sql_command = "
SELECT
    m.message_id,
    m.title,
    m.content,
    u.username AS author,
    m.image_path,
    m.created_at,
    CASE
        WHEN l.user_id IS NOT NULL THEN 1
        ELSE 0
    END AS is_liked,
    COUNT(l2) AS number_of_likes,
    (COUNT(l2) - EXTRACT(EPOCH FROM (CURRENT_TIMESTAMP - m.created_at)) / 3600) AS score
    FROM
        messages m
            JOIN
        users u ON m.author = u.user_id
            LEFT JOIN
        likes l ON l.user_id = :user_id AND l.message_id = m.message_id
            LEFT JOIN
        likes l2 ON l2.message_id = m.message_id
    GROUP BY
        m.message_id, u.username, m.title, m.content, m.image_path, m.created_at, l.user_id
    ORDER BY
        score DESC
    LIMIT 50;";
    $result_arr = [];

    $stmt = $conn->prepare($sql_command);
    try {
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'] ?? null
        ]);

        if($stmt->rowCount() == 0)
            return $result_arr;

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach($rows as $row) {
            $result = [];

            $result['message_id'] = $row['message_id'];
            $result['title'] = $row['title'];
            $result['author'] = $row['author'];
            $result['is_liked'] = $row['is_liked'];
            $result['likes'] = $row['number_of_likes'];

            $formatted_date = "";
            try {
                $formatted_date = (new DateTime($row['created_at']))->format('d F Y H:i');
            } catch (DateMalformedStringException) {
                echo "Something went wrong.";
            }
            $short_content = substr(strip_tags($row['content']), 0, 1000) . " [...]";

            $result['content'] = $short_content;
            $result['created_at'] = $formatted_date;

            $result_arr[] = $result;
        }
    } catch(PDOException $e) {
        error_log($e->getMessage());

        $errorText = "Something went wrong, try again later!";
    }

    return $result_arr;
}

function viewMessage(int $id, string &$errorText, PDO $conn): array
{
    $result_arr = [];

    $sql_command = "
        SELECT
            m.title,
            m.content,
            u.username as author,
            m.image_path,
            m.created_at,
            CASE
                WHEN l.user_id IS NOT NULL THEN 1
                ELSE 0
            END AS is_liked
        FROM
            messages m
        JOIN users u ON m.author = u.user_id
        LEFT JOIN
            likes l ON l.user_id = :user_id AND l.message_id = m.message_id
        WHERE m.message_id = :id";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
            ':id' => $id,
            ':user_id' => $_SESSION['user_id'] ?? null
        ]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$res) {
            $errorText = "There is no message with id: " . $id;
            return $result_arr;
        }

        $result_arr['title'] = $res['title'];
        $result_arr['content'] = $res['content'];
        $result_arr['image_path'] = $res['image_path'];
        $result_arr['author'] = $res['author'];
        $result_arr['is_liked'] = $res['is_liked'];

        $formatted_date = "";
        try {
            $formatted_date = (new DateTime($res['created_at']))->format('d F Y H:i');
        } catch (DateMalformedStringException) {
            echo "Something went wrong.";
        }
        $result_arr['created_at'] = $formatted_date;

    } catch (PDOException $e) {
        error_log($e->getMessage());

        $errorText = "Something went wrong, try again later!";
        return $result_arr;
    }

    return $result_arr;
}