<?php
require_once 'db.php';
require_once 'handlers/index-handler.php';

/**
 * @var PDO $conn
 */

session_start();
$current_page = "homepage";

$images = getLatestImages($conn);
$hottest_messages = getHottestMessages($conn);
$latest_comments = getLatestComments($conn);
$latest_users = getLatestUsers($conn);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>The Message Board — Homepage</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'templates/header.php'; ?>

    <div class="container mb-5"  style="min-height: 100vh;">
        <div class="d-lg-flex lg-row gap-2">
            <div class="latest-images rounded mx-auto">
                <h4><i class="fa-solid fa-image"></i> Latest Images</h4>
                <hr class="thick-hr">

                <div id="carouselExampleAutoplaying" class="carousel slide" data-bs-ride="carousel">
                    <?php if(empty($images)): ?>
                            <h5>There are no images... YET!</h5>
                    <?php else: ?>
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo $images[0]['image_path']; ?>" class="d-block img-fluid w-100 mx-auto rounded carousel-img" alt="One of the latest images">
                                <?php array_shift($images); ?>
                            </div>
                            <?php foreach($images as $image): ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $image['image_path']; ?>" class="d-block img-fluid w-100 mx-auto rounded carousel-img" alt="One of the latest images">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            <div class="hot-messages rounded mx-auto">
                <h4><i class="fa-solid fa-fire"></i> Hottest Messages</h4>
                <hr class="thick-hr">
                <?php if(empty($hottest_messages)): ?>
                    <h5>There are no hot messages... YET!</h5>
                <?php else: ?>
                    <div class="d-flex row gap-2">
                        <?php foreach($hottest_messages as $message): ?>
                            <div class="hot-message w-100">
                                <h6><a href="/pages/message.php?id=<?php echo $message['message_id']; ?>"><?php echo $message['title']; ?></a></h6>
                                <p>
                                    Wrote by <?php echo $message['author']; ?> <br>
                                    On <?php echo $message['created_at']; ?> <br>
                                    <?php echo $message['likes']; ?> <i class="fa-solid fa-heart"></i> <br>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="d-lg-flex lg-row gap-2">
            <div class="latest-comments rounded mx-auto">
                <h4><i class="fa-solid fa-message"></i> Latest Comments</h4>
                <hr class="thick-hr">

                <?php if(empty($latest_comments)): ?>
                    <h5>There are no comments... YET!</h5>
                <?php else: ?>
                    <div class="d-flex row gap-1">
                        <?php foreach($latest_comments as $comment): ?>
                            <div class="latest-comment">
                                <h5>User <a href="/pages/message.php?id=<?php echo $comment['message_id'].'#comment-'.$comment['comment_id']; ?>" class="author"><?php echo $comment['author'] ?></a> commented on
                                    <a href="/pages/message.php?id=<?php echo $comment['message_id']; ?>" class="message-title"><?php echo $comment['message_title'] ?></a></h5>
                                <p class="comment-content">
                                    <?php echo $comment['content'] ?>
                                </p>
                                <hr>
                                <p class="comment-likes">
                                    <i class="fa-solid fa-heart"></i> <?php echo $comment['likes'] ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="latest-users rounded mx-auto">
                <h4><i class="fa-solid fa-users"></i> Latest Users</h4>
                <hr class="thick-hr">
                <?php if(empty($latest_users)): ?>
                    <h5>There are no users... YET!</h5>
                <?php else: ?>
                <div class="d-flex row gap-1">
                    <?php foreach($latest_users as $user): ?>
                        <div class="latest-user">
                            <h5><?php echo $user['username'] ?></h5>
                            <p>
                                Joined on <?php echo $user['created_at'] ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php include 'templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>