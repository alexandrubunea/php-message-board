<?php
session_start();
$current_page = "about";
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>About Us - The Message Board</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../templates/header.php'; ?>

<div class="container mb-5" style="min-height: 100vh;">
    <div class="about-section rounded mx-auto">
        <h1 class="mt-5">About Us</h1>
        <hr class="thick-hr">
        <p>
            Welcome to The Message Board! This web application is a simple, yet powerful platform for users to share messages, images, and comments.
            Built using PHP, PostgreSQL, and Bootstrap, our goal is to provide a seamless and interactive experience for our users.
        </p>
        <p>
            Our features include:
        </p>
            <ul>
                <li>Posting messages and images</li>
                <li>Viewing and interacting with the hottest messages</li>
                <li>Latest comments and user activity</li>
            </ul>
        <p>
            We hope you enjoy using The Message Board as much as we enjoyed building it. Feel free to reach out with any feedback or questions.
        </p>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>