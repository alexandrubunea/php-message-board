<?php
session_start();
if(empty($_SESSION['username'])) {
    header('Location:messages.php');
}

$current_page = "messages";

require_once '../handlers/create-message-handler.php';

$errorText = '';
handleRequest($errorText);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Message Board — Create message</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../templates/header.php'; ?>
<div class="container pt-5">
    <form class="create-message-form mx-auto w-75" action="create-message.php" method="POST" enctype="multipart/form-data">
        <label for="message-title" class="form-label">Title of the message:</label>
        <input class="form-control" type="text" id="message-title" placeholder="Try something new..." name="title" required>

        <label for="message-content" class="form-label mt-4">The message:</label>
        <textarea id="message-content" class="form-control" placeholder="Your thoughts..." name="content" required></textarea>

        <label for="message-image" class="form-label mt-4">Attach image:</label>
        <input type="file" id="message-image" class="form-control" accept="image/png, image/jpeg" name="image">

        <hr class="my-5">

        <button type="submit" class="btn btn-success text-uppercase btn-action-message">create message</button>
        <?php if ($errorText != "") : ?>
            <div class="alert alert-danger d-flex align-items-center mt-5" role="alert">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <div>
                    <?php echo $errorText; ?>
                </div>
            </div>
        <?php endif; ?>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>