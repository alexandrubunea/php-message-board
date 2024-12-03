<?php
/**
 * @var string $current_page
 */

if(empty($_SESSION))
    session_start();
const VALID_PAGES = array(
    'homepage',
    'messages',
    'register',
    'login',
    'logout',
    'about'
);

if(!in_array($current_page, VALID_PAGES)) {
    echo 'This page is throwing a fatal error. Please contact the administrator.';
    return;
}

define("ACTIVE_PAGE_LIST", array_map(
    fn($page) => $current_page == $page,
    VALID_PAGES
));

?>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">The Message Board <i class="fa-regular fa-comments"></i></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link <?php echo (ACTIVE_PAGE_LIST[0]) ? 'active' : '' ?>" aria-current="page" href="../index.php">Home</a>
                    <a class="nav-link <?php echo (ACTIVE_PAGE_LIST[1]) ? 'active' : '' ?>" href="../pages/messages.php">Message Board</a>

                    <?php if(empty($_SESSION['username'])): ?>
                    <a class="nav-link <?php echo (ACTIVE_PAGE_LIST[2]) ? 'active' : '' ?>" href="../pages/register.php">Register</a>
                    <a class="nav-link <?php echo (ACTIVE_PAGE_LIST[3]) ? 'active' : '' ?>" href="../pages/login.php">Log in</a>
                    <?php else: ?>
                    <a class="nav-link <?php echo (ACTIVE_PAGE_LIST[4]) ? 'active' : '' ?>" href="../pages/logout.php">Log out</a>
                    <?php endif; ?>

                    <a class="nav-link <?php echo (ACTIVE_PAGE_LIST[5]) ? 'active' : '' ?>" href="../pages/about.php">About</a>
                </div>
            </div>
        </div>
    </nav>
</header>
