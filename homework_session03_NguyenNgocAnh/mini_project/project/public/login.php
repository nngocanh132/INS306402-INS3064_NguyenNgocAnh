<?php
require_once '../includes/auth.php';
session_init();

// Already logged in → redirect
if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

$error    = '';
$old_user = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Invalid request. Please try again.';
    } else {
        $username    = trim($_POST['username'] ?? '');
        $password    = $_POST['password'] ?? '';
        $old_user    = $username;

        $result = login($username, $password);
        if ($result['success']) {
            header('Location: profile.php');
            exit;
        } else {
            $error = $result['error'];
        }
    }
}

$attempts  = get_login_attempts();
$locked    = login_is_locked();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login — UserHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav>
    <a class="nav-brand" href="index.php">⚡ UserHub</a>
    <ul class="nav-links">
        <li><a href="login.php">Login</a></li>
        <li><a href="register.php" class="btn-nav">Sign up</a></li>
    </ul>
</nav>

<main>
    <div class="card">
        <h1>Welcome back</h1>

        <?php if (!empty($_GET['redirect'])): ?>
            <div class="alert alert-info">🔒 You must be logged in to view that page.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                ⚠️ <?= e($error) ?>
                <?php if ($attempts > 0 && !$locked): ?>
                    &nbsp;<span class="badge"><?= MAX_LOGIN_ATTEMPTS - $attempts ?> left</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($locked): ?>
            <div class="alert alert-error">
                🚫 Your session is locked after <?= MAX_LOGIN_ATTEMPTS ?> failed attempts.
                Close your browser and try again.
            </div>
        <?php endif; ?>

        <?php if (!$locked): ?>
        <form method="POST" action="login.php" novalidate>
            <?= csrf_input() ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= e($old_user) ?>"
                       placeholder="Your username" required autofocus>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php endif; ?>

        <p class="text-center">Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
</main>

</body>
</html>