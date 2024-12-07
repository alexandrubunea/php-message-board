<?php

function createMessage(&$errorText): void
{
    /**
     * @var PDO $conn
     */

    if($_SERVER['REQUEST_METHOD'] != 'POST')
        return;

    if(empty($_SESSION))
        return;

    if(empty($_SESSION['username']))
        return;

    if(strlen(trim($_POST['title'])) === 0) {
        $errorText = 'Title cannot be empty';
        return;
    }
    if(strlen(trim($_POST['content'])) === 0) {
        $errorText = 'Message cannot be empty';
        return;
    }

    include '../db.php';

    $dest = "(null)";
    if(!empty($_FILES["image"]["name"])) {
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($_FILES["image"]["name"]);
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if(!getimagesize($_FILES["image"]["tmp_name"])) {
            $errorText = "File is not an image.";
            return;
        }
        if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") {
            $errorText = "Only JPG, JPEG, PNG files are allowed.";
            return;
        }
        if($_FILES["image"]["size"] > 500000) {
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
    } catch(PDOException) {
        $errorText = "Something went wrong, try again later!";
    }
}

function viewMessage($id, &$errorText): array
{
    /**
     * @var PDO $conn
     */
    include '../db.php';

    $result_arr = [];

    $sql_command = "
        SELECT
            m.title,
            m.content,
            u.username as author,
            m.image_path,
            m.created_at
        FROM
            messages m
        JOIN
            users u
        ON
            m.author = u.user_id
        WHERE m.message_id = :id";
    $stmt = $conn->prepare($sql_command);

    try {
        $stmt->execute([
           ':id' => $id
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

        $formatted_date = "";
        try {
            $formatted_date = (new DateTime($res['created_at']))->format('d F Y H:i');
        } catch (DateMalformedStringException) {
            echo "Something went wrong.";
        }
        $result_arr['created_at'] = $formatted_date;

    } catch (PDOException) {
        $errorText = "Something went wrong, try again later!";
        return $result_arr;
    }

    return $result_arr;
}