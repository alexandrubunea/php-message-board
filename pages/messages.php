<?php
session_start();
$current_page = "messages";

/**
 * @var PDO $conn
 */
include '../db.php';

$sql_command = "
SELECT
    m.message_id,
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
ORDER BY
    m.created_at DESC
LIMIT 20";

$stmt = $conn->query($sql_command);
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

    <?php
    if ($stmt->rowCount() > 0) {
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rows as $row) {
            $formatted_date = "";
            try {
                $formatted_date = (new DateTime($row['created_at']))->format('d F Y H:i');
            } catch (DateMalformedStringException) {
                echo "Something went wrong.";
            }

            $short_content = substr(strip_tags($row['content']), 0, 1000) . " [...]";

            echo <<<HTML
            <div class="message">
            <h3>{$row['title']}</h3>
            <p class="data">
                <i class="fa-solid fa-user"></i> Written by {$row['author']} <br>
                <i class="fa-solid fa-clock"></i> {$formatted_date} <br>
                <i class="fa-solid fa-heart"></i> 1252 Likes
            </p>
            <p class="short-text">{$short_content}</p>
            <hr>
            <div class="d-flex flex-column flex-lg-row align-items-center gap-2">
                <a href="#" class="btn btn-success btn-action-message">
                    <i class="fa-solid fa-glasses"></i> Continue reading
                </a>
                <a href="#" class="btn btn-danger btn-action-message">
                    <i class="fa-solid fa-heart"></i> Like
                </a>
            HTML;

            if ($row['author'] == $_SESSION['username']) {
                echo <<<HTML
                    <a href="#" class="btn btn-secondary btn-action-message">
                        <i class="fa-solid fa-trash-can"></i> Delete
                    </a>
                HTML;
            }

            echo "</div></div>";
        }
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>