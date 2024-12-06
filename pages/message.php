<?php
session_start();
$current_page = "messages";

require_once '../handlers/message-handler.php';

$errorText = '';
$message_data = viewMessage($_GET['id'], $errorText);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Message Board — <?php echo (empty($errorText)) ? $message_data['title'] : "Message not found" ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../templates/header.php'; ?>
<div class="container">
    <?php if(!empty($errorText)): ?>
    <div class="alert alert-danger d-flex align-items-center mt-5" role="alert">
        <i class="fa-solid fa-triangle-exclamation"></i>
        <div>
            <?php echo $errorText; ?>
        </div>
    </div>
    <?php else: ?>
    <div class="message-content mt-5">
        <h1><?php echo $message_data['title']; ?></h1>
        <p>
            <i class="fa-solid fa-user"></i> Wrote by: <?php echo $message_data['author']; ?> <br>
            <i class="fa-solid fa-clock"></i> Date: <?php echo $message_data['created_at']; ?> <br>
            <i class="fa-solid fa-heart"></i> 1252 Likes
        </p>
        <hr>
        <?php if(strcmp($message_data['image_path'], '(null)') != 0): ?>
        <img class="img-fluid rounded" alt="Attached image" src="<?php echo $message_data['image_path']; ?>">
        <?php endif; ?>
        <p class="mt-2">
            <?php echo $message_data['content']; ?>
        </p>
        <hr>
        <div class="d-flex flex-column flex-lg-row align-items-center gap-2">
            <a href="#" class="btn btn-danger btn-action-message">
                <i class="fa-solid fa-heart"></i> Like
            </a>

            <?php if($message_data['author'] == $_SESSION['username']): ?>
                <a href="#" class="btn btn-secondary btn-action-message">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="message-comments my-3">
        <h1>Comments</h1>
        <!-- Will be implemented -->
    </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>