<?php
session_start();

$current_page = "messages";
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
        <?php echo (empty($_SESSION['username']) ? 'disabled' : ''); ?>"><i class="fa-solid fa-square-plus"></i> Create message</a>
    <hr>
    <div class="message">
        <h3>Title of the article</h3>
        <p class="data">
            <i class="fa-solid fa-user"></i> Written by John Doe <br>
            <i class="fa-solid fa-clock"></i> 17 February 2025 <br>
            <i class="fa-solid fa-heart"></i> 1252 Likes
        </p>
        <p class="short-text">Etiam ligula metus, imperdiet et diam quis, venenatis interdum velit. Sed scelerisque risus hendrerit varius accumsan. Sed fringilla massa in eleifend dignissim. Nulla tincidunt urna elit, vel pellentesque sem molestie sed. Fusce fermentum luctus arcu eu tempor. Phasellus eget velit fermentum, facilisis velit id, porta velit. Aenean finibus, lacus non tincidunt ultricies, orci felis scelerisque justo, quis pharetra metus mauris sed elit. Sed cursus euismod nisl, sit amet convallis urna consequat id. Aenean quis bibendum nunc, eget vestibulum ex. Nunc accumsan efficitur neque rhoncus volutpat. In quis urna massa. Aenean augue felis, volutpat sit amet ligula elementum, imperdiet dictum lacus. Nullam in lorem ut purus scelerisque pellentesque in non ante. Duis consectetur odio ac dui condimentum tincidunt. </p>
        <hr>
        <div class="d-flex flex-column flex-lg-row align-items-center gap-2">
            <a href="#" class="btn btn-success btn-action-message">
                <i class="fa-solid fa-glasses"></i> Continue reading
            </a>
            <a href="#" class="btn btn-danger btn-action-message">
                <i class="fa-solid fa-heart"></i> Like
            </a>
            <a href="#" class="btn btn-secondary btn-action-message">
                <i class="fa-solid fa-trash-can"></i> Delete
            </a>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>