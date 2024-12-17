<?php

use Random\RandomException;

require_once '../utils.php';
require_once '../handlers/register-handler.php';
require_once '../db.php';

/**
 * @var PDO $conn
 */

$errorText = '';
$accountCreated = false;
$current_page = "register";

session_start();

if($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $one_time_csrf_token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $one_time_csrf_token;
    } catch (RandomException $e) {
        error_log($e->getMessage());
        $errorText = "Something went wrong, try again later!";
    }
}

if(isUserLoggedIn())
    header("Location: ../index.php");

handleRequest($errorText, $accountCreated, $conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Message Board — Create account</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<?php include '../templates/header.php'; ?>
<div class="container" style="min-height: 100vh;">
    <div class="d-flex justify-content-center p-5">
        <div class="auth-card">
            <h3 class="text-center">Create an account...</h3>
            <br>

            <p class="small">
                Create your account <b>right now</b>, but remember, there is no way to recover your password.<br>
                Your <b>DATA</b> will never be stored on our servers. Enjoy your <u>privacy</u>.
            </p>

            <form class="mb-5" action="register.php" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="mb-3">
                    <label for="username-input" class="form-label">Username:</label>
                    <input type="text" class="form-control" id="username-input" aria-describedby="username-help" name="username" required>
                    <div id="username-help" class="form-text">Choose something <b>unique</b>.</div>
                </div>
                <div class="mb-5">
                    <label for="password-input" class="form-label">Password:</label>
                    <input type="password" class="form-control" id="password-input" aria-describedby="password-help" name="password" required>
                    <div id="password-help" class="form-text">Choose something <b>strong</b>.</div>
                </div>

                <div class="row">
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary btn-lg p-3">REGISTER</button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="d-flex flex-column align-items-center">
                        <p class="small mb-2 fw-bold">Already have an account?</p>
                        <a href="login.php" class="btn btn-outline-primary btn-lg p-3">LOG IN</a>
                    </div>
                </div>

            </form>

            <?php if ($accountCreated) :?>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        Account created, you can log in whenever you please.
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($errorText != "") : ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <div>
                        <?php echo $errorText; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>