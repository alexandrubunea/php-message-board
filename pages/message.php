<?php
session_start();
$current_page = "messages";

require_once '../handlers/message-handler.php';

$errorText = '';
$errorTextComment = '';
$errorTextViewComments = '';

$message_data = viewMessage($_GET['id'], $errorText);

require '../handlers/comment-handler.php';
createComment($errorTextComment);
$comments = viewComments($errorTextViewComments);

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
            <i class="fa-solid fa-user"></i> Wrote by <?php echo $message_data['author']; ?> <br>
            <i class="fa-solid fa-clock"></i> <?php echo $message_data['created_at']; ?> <br>
            <i class="fa-solid fa-heart"></i> 1252 Likes
        </p>
        <hr>
        <?php if(strcmp($message_data['image_path'], '(null)') != 0): ?>
        <img class="img-thumbnail thumbnail rounded w-50 float-md-start m-3" alt="Attached image" src="<?php echo $message_data['image_path']; ?>">
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
        <h1 id="comments"><i class="fa-solid fa-comment"></i> Comments</h1>
        <hr class="my-3">
        <div class="mt-5 mb-3">
            <?php if(!empty($errorTextComment)): ?>
                <div class="alert alert-danger d-flex align-items-center mt-5" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <?php echo $errorTextComment; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <form action="message.php?id=<?php echo $_GET['id']; ?>" method="POST">
            <label for="comment-text-area" class="small mb-3">
                <?php echo (empty($_SESSION))? "You must to be authenticated to comment." : "Add comment as " . $_SESSION['username'] . ':'; ?>
            </label>
            <textarea class="form-control" name="comment-text-area" id="comment-text-area" placeholder="Type your thoughts..." required <?php echo (empty($_SESSION))? 'disabled' : ''; ?>></textarea>
            <button type="submit" class="btn btn-secondary btn-action-message my-3"><i class="fa-solid fa-arrow-right"></i> Add Comment</button>
        </form>
        <div class="my-5">
            <?php if(sizeof($comments) == 0): ?>
                <div class="alert alert-info d-flex align-items-center mt-5" role="alert">
                    <i class="fa-solid fa-star"></i>
                    Nobody commented on this message. Be the first to do it!
                </div>
            <?php else: ?>
                <?php if(!empty($errorTextViewComments)): ?>
                    <div class="alert alert-danger d-flex align-items-center mt-5" role="alert">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <div>
                            <?php echo $errorText; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach($comments as $comment): ?>
                        <div class="px-2 py-2 my-3 comment d-flex flex-row rounded">
                            <div class="d-flex flex-column px-4 my-auto cassette">
                                <i class="fa-solid fa-circle-user mx-auto user-icon"></i>
                                <p class="mx-auto username mt-2"><?php echo $comment['author']; ?></p>
                            </div>
                            <div class="d-flex flex-column w-100 px-4 pt-2 pb-0">
                                <p class="comment-text"><?php echo $comment['content']; ?></p>
                                <p class="small text-end timestamp">
                                    <i class="fa-solid fa-clock"></i> <?php echo $comment['date']; ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>