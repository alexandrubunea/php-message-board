<?php

function isUserLoggedIn(): bool {
    return isset($_SESSION['username']);
}

function checkCSRFToken($token): bool {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}

function isPOSTRequest(): bool {
    return isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'POST';
}