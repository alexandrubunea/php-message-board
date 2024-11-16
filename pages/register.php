<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Message Board — Create account</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
<div class="d-flex justify-content-center p-5">
    <div class="register-card">
        <h3 class="text-center">Create an account...</h3>
        <br>

        <p class="small">
            Create your account <b>right now</b>, but remember, there is no way to recover your password.<br>
            Your <b>DATA</b> will never be stored on our servers. Enjoy your <u>privacy</u>.
        </p>

        <form>
            <div class="mb-3">
                <label for="username-input" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username-input" aria-describedby="username-help" required>
                <div id="username-help" class="form-text">Choose something <b>unique</b>.</div>
            </div>
            <div class="mb-5">
                <label for="password-input" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password-input" aria-describedby="password-help" required>
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
                    <button type="button" class="btn btn-outline-primary btn-lg p-3">LOGIN</button>
                </div>
            </div>

        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>