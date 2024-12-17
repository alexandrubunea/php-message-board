<?php
require_once '../utils.php';

session_start();

if(isUserLoggedIn())
    session_destroy();

header("Location: ../index.php");
die;