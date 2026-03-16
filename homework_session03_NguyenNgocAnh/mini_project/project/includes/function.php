<?php
require_once __DIR__ . '/config.php';

// ─── Output escaping (XSS prevention) ────────────────────────────────────────
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// ─── CSRF token ───────────────────────────────────────────────────────────────
function generate_csrf(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(string $token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_input(): string {
    return '<input type="hidden" name="csrf_token" value="' . e(generate_csrf()) . '">';
}

// ─── User storage (JSON) ──────────────────────────────────────────────────────
function read_users(): array {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $json = file_get_contents(USERS_FILE);
    return json_decode($json, true) ?? [];
}

function write_users(array $users): void {
    file_put_contents(USERS_FILE, json_encode(array_values($users), JSON_PRETTY_PRINT));
}

function find_user_by_username(string $username): ?array {
    foreach (read_users() as $user) {
        if (strtolower($user['username']) === strtolower($username)) {
            return $user;
        }
    }
    return null;
}

function find_user_by_email(string $email): ?array {
    foreach (read_users() as $user) {
        if (strtolower($user['email']) === strtolower($email)) {
            return $user;
        }
    }
    return null;
}

function update_user(array $updated): void {
    $users = read_users();
    foreach ($users as &$user) {
        if ($user['username'] === $updated['username']) {
            $user = $updated;
            break;
        }
    }
    write_users($users);
}

// ─── Registration ─────────────────────────────────────────────────────────────
function register_user(string $username, string $email, string $password): array {
    $errors = [];

    // Trim & length checks
    $username = trim($username);
    $email    = trim($email);

    if (strlen($username) < 3 || strlen($username) > 30) {
        $errors[] = 'Username must be between 3 and 30 characters.';
    }
    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $errors[] = 'Username may only contain letters, numbers, and underscores.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long.';
    }
    if (find_user_by_username($username)) {
        $errors[] = 'That username is already taken.';
    }
    if (find_user_by_email($email)) {
        $errors[] = 'An account with that email already exists.';
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    $users = read_users();
    $users[] = [
        'username' => $username,
        'email'    => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'bio'      => '',
        'avatar'   => '',
        'created'  => date('Y-m-d H:i:s'),
    ];
    write_users($users);

    return ['success' => true];
}

// ─── Login attempt throttling ─────────────────────────────────────────────────
function get_login_attempts(): int {
    return $_SESSION['login_attempts'] ?? 0;
}

function increment_login_attempts(): void {
    $_SESSION['login_attempts'] = get_login_attempts() + 1;
}

function reset_login_attempts(): void {
    $_SESSION['login_attempts'] = 0;
}

function login_is_locked(): bool {
    return get_login_attempts() >= MAX_LOGIN_ATTEMPTS;
}

// ─── Avatar upload ────────────────────────────────────────────────────────────
function handle_avatar_upload(array $file): array {
    if ($file['error'] === UPLOAD_ERR_NO_FILE) {
        return ['success' => true, 'filename' => null]; // No file uploaded — OK
    }

    if ($file['error'] !== UPLOAD_ERR_OK) {
        return ['success' => false, 'error' => 'Upload failed with error code ' . $file['error']];
    }

    if ($file['size'] > MAX_FILE_SIZE) {
        return ['success' => false, 'error' => 'Image must be smaller than 2 MB.'];
    }

    // Check real MIME type (not just extension)
    $finfo    = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $finfo->file($file['tmp_name']);

    if (!in_array($mimeType, ALLOWED_IMAGE_TYPES, true)) {
        return ['success' => false, 'error' => 'Only JPG, PNG, GIF, and WEBP images are allowed.'];
    }

    // Also check extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ALLOWED_IMAGE_EXTS, true)) {
        return ['success' => false, 'error' => 'Invalid file extension.'];
    }

    // Generate safe, unique filename
    $newName  = bin2hex(random_bytes(16)) . '.' . $ext;
    $destPath = UPLOADS_DIR . '/' . $newName;

    if (!move_uploaded_file($file['tmp_name'], $destPath)) {
        return ['success' => false, 'error' => 'Could not save uploaded file. Check folder permissions.'];
    }

    return ['success' => true, 'filename' => $newName];
}