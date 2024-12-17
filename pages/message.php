<?php
require_once '../handlers/message-handler.php';
require_once '../handlers/comment-handler.php';
require_once '../db.php';

/**
 * @var PDO $conn
 */

$current_page = "messages";
$errorText = '';
$errorTextComment = '';
$errorTextViewComments = '';

session_start();

$message_data = viewMessage($_GET['id'], $errorText, $conn);
$comments = viewComments($errorTextViewComments, $conn);
createComment($errorTextComment, $conn);

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
<div class="container" style="min-height: 100vh;">
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
        <p class="data-styling">
            <i class="fa-solid fa-user"></i> Wrote by <?php echo $message_data['author']; ?> <br>
            <i class="fa-solid fa-clock"></i> <?php echo $message_data['created_at']; ?> <br>
            <i class="fa-solid fa-heart"></i> <span id="number_of_likes"><?php echo $message_data['likes']; ?></span>
        </p>
        <hr>
        <div class="p-3 message-text">
            <?php if(strcmp($message_data['image_path'], '(null)') != 0): ?>
                <img class="img-thumbnail thumbnail rounded w-50 m-3" alt="Attached image" src="<?php echo $message_data['image_path']; ?>">
            <?php endif; ?>
            <p class="mt-2">
                <?php echo $message_data['content']; ?>
            </p>
        </div>
        <hr>
        <div class="d-flex flex-column flex-lg-row align-items-center gap-2">
            <button is_liked="<?php echo $message_data['is_liked']; ?>" message_id="<?php echo $_GET['id']; ?>"
                    type="button" class="btn-like btn btn-danger btn-action-message"
                    <?php echo (!isUserLoggedIn()) ? 'disabled' : ''; ?>>
            </button>

            <?php if(isUserLoggedIn() && $message_data['author'] == $_SESSION['username']): ?>
                <button class="btn-delete btn btn-secondary btn-action-message" message_id="<?php echo $_GET['id']; ?>">
                    <i class="fa-solid fa-trash-can"></i> Delete
                </button>
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
            <input type="hidden" name="csrf_token" value="<?php echo (!isUserLoggedIn())? '' : $_SESSION['csrf_token']; ?>">
            <label for="comment-text-area" class="small mb-3">
                <?php echo (!isUserLoggedIn())? "You must to be authenticated to comment." : "Add comment as " . $_SESSION['username'] . ':'; ?>
            </label>
            <textarea class="form-control" name="comment-text-area" id="comment-text-area" placeholder="Type your thoughts..." required <?php echo (!isUserLoggedIn())? 'disabled' : ''; ?>></textarea>
            <button type="submit" class="btn btn-secondary btn-action-message my-3" <?php echo (!isUserLoggedIn())? 'disabled' : ''; ?>><i class="fa-solid fa-arrow-right"></i> Add Comment</button>
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
                    <?php $id = 0; ?>
                    <div class="comments">
                        <?php foreach($comments as $comment): ?>
                            <div id="comment-<?php echo $comment['comment_id']; ?>" class="px-2 py-3 comment d-flex flex-row rounded my-3">
                                <div class="d-flex flex-column px-4 cassette">
                                    <i class="fa-solid fa-circle-user mx-auto user-icon"></i>
                                    <p class="mx-auto username mt-2"><?php echo $comment['author']; ?></p>
                                </div>
                                <div class="d-flex flex-column w-100 px-4 pt-2 pb-0">
                                    <p class="comment-text"><?php echo $comment['content']; ?></p>
                                    <p class="small text-end data-styling">
                                        <i class="fa-solid fa-clock"></i> <?php echo $comment['date']; ?> <br>
                                        <i class="fa-solid fa-heart"></i>
                                        <span id="number_of_likes_<?php echo $id; ?>"><?php echo $comment['likes']; ?></span>
                                    </p>
                                    <div class="d-flex flex-row w-100 gap-2">
                                        <button is_liked="<?php echo $comment['is_liked']; ?>" comment_id="<?php echo $comment['comment_id']; ?>"
                                                class="btn-comment-like btn btn-outline-danger btn-comment-action fw-bold"
                                            <?php echo (!isUserLoggedIn()) ? 'disabled' : ''; ?>>
                                            <i class="fa-solid fa-heart"></i> Like</button>
                                        <?php if(isUserLoggedIn() && $comment['author'] == $_SESSION['username']): ?>
                                            <button comment_id="<?php echo $comment['comment_id']; ?>"
                                                    class="btn-comment-delete btn btn-outline-secondary btn-comment-action fw-bold"><i class="fa-solid fa-trash-can"></i> Delete</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php $id++ ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../templates/footer.php'; ?>
<script>
    const csrf_token = "<?php echo (!isUserLoggedIn())? '' : $_SESSION['csrf_token']; ?>";
</script>
<script src="../assets/js/message.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>