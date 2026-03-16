<?php
require_once '../includes/auth.php';
session_init();

// ── Access control ──────────────────────────────────────────────────────────
if (!is_logged_in()) {
    header('Location: login.php?redirect=1');
    exit;
}

$user    = current_user();
$errors  = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $errors[] = 'Invalid request. Please try again.';
    } else {
        $bio = trim($_POST['bio'] ?? '');

        // Bio length limit (hard cap at 500 chars)
        if (strlen($bio) > 500) {
            $errors[] = 'Bio must not exceed 500 characters.';
        }

        // Handle avatar upload
        $avatarResult = handle_avatar_upload($_FILES['avatar'] ?? ['error' => UPLOAD_ERR_NO_FILE]);

        if (!$avatarResult['success']) {
            $errors[] = $avatarResult['error'];
        }

        if (empty($errors)) {
            // Remove old avatar file if a new one was uploaded
            if ($avatarResult['filename'] !== null && !empty($user['avatar'])) {
                $oldPath = UPLOADS_DIR . '/' . $user['avatar'];
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Update user record
            $user['bio']    = $bio;
            if ($avatarResult['filename'] !== null) {
                $user['avatar'] = $avatarResult['filename'];
            }
            update_user($user);

            // Refresh local copy
            $user    = current_user();
            $success = 'Profile updated successfully!';
        }
    }
}

// Build avatar URL
$avatarUrl = '';
if (!empty($user['avatar'])) {
    $avatarUrl = UPLOADS_URL . '/' . e($user['avatar']);
}

// Initial letter for placeholder
$initial = strtoupper(substr($user['username'], 0, 1));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile — UserHub</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav>
    <a class="nav-brand" href="index.php">⚡ UserHub</a>
    <ul class="nav-links">
        <li><a href="index.php">Home</a></li>
        <li><a href="profile.php">Profile</a></li>
        <!-- Logout only shown when logged in (which it always is here) -->
        <li><a href="logout.php" class="btn-nav">Logout</a></li>
    </ul>
</nav>

<main>
    <div class="card card-wide">

        <!-- Profile Header -->
        <div class="profile-header">
            <div class="avatar-wrap">
                <?php if ($avatarUrl): ?>
                    <img src="<?= $avatarUrl ?>" alt="Avatar" class="avatar">
                <?php else: ?>
                    <div class="avatar-placeholder"><?= e($initial) ?></div>
                <?php endif; ?>
            </div>
            <div class="profile-meta">
                <h2><?= e($user['username']) ?></h2>
                <span class="email"><?= e($user['email']) ?></span>
                <?php if (!empty($user['bio'])): ?>
                    <!-- bio is escaped — XSS safe -->
                    <p class="bio"><?= e($user['bio']) ?></p>
                <?php else: ?>
                    <p class="bio" style="color:var(--muted);">No bio yet — add one below!</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Feedback messages -->
        <?php if ($success): ?>
            <div class="alert alert-success">✅ <?= e($success) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Could not save changes:</strong>
                <ul>
                    <?php foreach ($errors as $err): ?>
                        <li><?= e($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <h2>Edit Profile</h2>

        <form method="POST" action="profile.php" enctype="multipart/form-data" novalidate>
            <?= csrf_input() ?>

            <div class="form-group">
                <label for="avatar">Avatar Image <small>(JPG, PNG, GIF, WEBP · max 2 MB)</small></label>
                <input type="file" id="avatar" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp">
            </div>

            <div class="form-group">
                <label for="bio">Bio <small>(max 500 characters)</small></label>
                <textarea id="bio" name="bio" maxlength="500"
                          placeholder="Tell us a little about yourself..."><?= e($user['bio']) ?></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>

        <hr class="divider">

        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
</main>

</body>
</html>