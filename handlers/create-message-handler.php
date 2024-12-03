<?php

function handleRequest(&$errorText): void
{
    /**
     * @var PDO $conn
     */

    if($_SERVER['REQUEST_METHOD'] != 'POST')
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

    $target_file = "(null)";
    if(!empty($_FILES["image"])) {
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
            ':image_path' => $target_file,
            ':author_id' => $_SESSION['user_id']
        ]);

        header("Location: ../pages/messages.php");
    } catch(PDOException) {
        $errorText = "Something went wrong, try again later!";
    }
}