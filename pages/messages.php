<?php
require_once '../handlers/message-handler.php';
require_once '../db.php';
require_once '../utils.php';

/**
 * @var PDO $conn
 */

$current_page = "messages";
$errorText = '';

session_start();

$messages = viewMessages($errorText, $conn);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Message Board — Explore messages</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../templates/header.php'; ?>
<div class="container pt-5">
    <a href="create-message.php" class="btn btn-primary btn-create-message
        <?php echo (!isUserLoggedIn()) ? 'disabled' : ''; ?>"><i class="fa-solid fa-square-plus"></i> Create message</a>
    <hr>
    <?php if(!empty($errorText)): ?>
        <div class="alert alert-danger d-flex align-items-center mt-5" role="alert">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <div>
                <?php echo $errorText; ?>
            </div>
        </div>
    <?php else: ?>
        <?php if(sizeof($messages) == 0): ?>
            <div class="alert alert-info d-flex align-items-center mt-5" role="alert">
                <i class="fa-solid fa-star"></i>
                There is no message yet. Be the first to write one!
            </div>
        <?php else: ?>
            <?php $id = 0; ?>
            <?php foreach($messages as $message): ?>
                <div class="message" id="message-<?php echo $message['message_id']; ?>'">
                    <h3><?php echo $message['title']; ?></h3>
                    <p class="data data-styling">
                        <i class="fa-solid fa-user"></i> Wrote by <?php echo $message['author']; ?> <br>
                        <i class="fa-solid fa-clock"></i> <?php echo $message['created_at']; ?>  <br>
                        <i class="fa-solid fa-heart"></i>
                        <span id="number_of_likes_<?php echo $id; ?>"><?php echo $message['likes']; ?></span> Likes
                    </p>
                    <p class="short-text"><?php echo $message['content']; ?></p>
                    <hr>
                    <div class="d-flex flex-column flex-lg-row align-items-center gap-2">
                        <a href="message.php?id=<?php echo $message['message_id']; ?>" class="btn btn-success btn-action-message">
                            <i class="fa-solid fa-glasses"></i> Continue reading
                        </a>

                        <button is_liked="<?php echo $message['is_liked']; ?>" message_id="<?php echo $message['message_id']; ?>"
                                type="button" class="btn-like btn btn-danger btn-action-message"
                                <?php echo (!isUserLoggedIn()) ? 'disabled' : ''; ?>>
                        </button>

                        <?php if(isUserLoggedIn() && $message['author'] == $_SESSION['username']): ?>
                            <a href="#" class="btn btn-secondary btn-action-message">
                                <i class="fa-solid fa-trash-can"></i> Delete
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php $id++; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
    const csrf_token = "<?php echo (!isUserLoggedIn())? '' : $_SESSION['csrf_token']; ?>";
</script>
<script src="../assets/js/messages.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>