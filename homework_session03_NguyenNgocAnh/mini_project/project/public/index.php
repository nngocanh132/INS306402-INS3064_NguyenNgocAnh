<?php
require_once '../includes/auth.php';
session_init();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UserHub — Home</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav>
    <a class="nav-brand" href="index.php">⚡ UserHub</a>
    <ul class="nav-links">
        <?php if (is_logged_in()): ?>
            <li><a href="profile.php">Profile</a></li>
            <li><a href="logout.php" class="btn-nav">Logout</a></li>
        <?php else: ?>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php" class="btn-nav">Sign up</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <div class="card">
        <div class="hero">
            <h1>Welcome to UserHub 👋</h1>
            <?php if (is_logged_in()): ?>
                <p>Hello, <strong><?= e($user['username']) ?></strong>! Ready to manage your profile?</p>
                <div class="btn-group">
                    <a href="profile.php" class="btn btn-primary" style="width:auto;">Go to My Profile</a>
                    <a href="logout.php" class="btn-outline">Logout</a>
                </div>
            <?php else: ?>
                <p>A simple user profile system built with PHP. Register, login, and manage your profile.</p>
                <div class="btn-group">
                    <a href="register.php" class="btn btn-primary" style="width:auto;">Get Started</a>
                    <a href="login.php" class="btn-outline">Login</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

</body>
</html>