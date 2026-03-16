<?php
require_once '../includes/auth.php';
session_init();

// Already logged in → redirect to profile
if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

$errors   = [];
$success  = false;
// Sticky form values
$old = ['username' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $username  = trim($_POST['username'] ?? '');
        $email     = trim($_POST['email'] ?? '');
        $password  = $_POST['password'] ?? '';
        $password2 = $_POST['password_confirm'] ?? '';

        $old = ['username' => $username, 'email' => $email];

        // Passwords must match — checked BEFORE calling register_user
        if ($password !== $password2) {
            $errors[] = 'Passwords do not match. Please try again.';
        }

        if (empty($errors)) {
            $result = register_user($username, $email, $password);
            if ($result['success']) {
                $success = true;
                $old     = ['username' => '', 'email' => ''];
            } else {
                $errors = $result['errors'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register — UserHub</title>
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
        <h1>Create an account</h1>

        <?php if ($success): ?>
            <div class="alert alert-success">
                ✅ Account created! <a href="login.php">Login now</a>.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Please fix the following errors:</strong>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="POST" action="register.php" novalidate>
            <?= csrf_input() ?>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?= e($old['username']) ?>"
                       placeholder="e.g. john_doe" maxlength="30" required>
            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email" id="email" name="email"
                       value="<?= e($old['email']) ?>"
                       placeholder="you@example.com" required>
            </div>

            <div class="form-group">
                <label for="password">Password <small>(min. 8 characters)</small></label>
                <input type="password" id="password" name="password"
                       placeholder="••••••••" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Confirm Password</label>
                <input type="password" id="password_confirm" name="password_confirm"
                       placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary">Create Account</button>
        </form>
        <?php endif; ?>

        <p class="text-center">Already have an account? <a href="login.php">Login</a></p>
    </div>
</main>

</body>
</html>