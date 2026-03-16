<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

function session_init(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function is_logged_in(): bool {
    return !empty($_SESSION['user']);
}

function current_user(): ?array {
    if (!is_logged_in()) return null;
    // Re-read from storage so profile changes are reflected
    return find_user_by_username($_SESSION['user']['username']);
}

function require_login(string $redirect = 'login.php'): void {
    if (!is_logged_in()) {
        header('Location: ' . $redirect);
        exit;
    }
}

function login(string $username, string $password): array {
    if (login_is_locked()) {
        return ['success' => false, 'error' => 'Too many failed attempts. Please close and reopen your browser to reset.'];
    }

    $user = find_user_by_username($username);

    if (!$user || !password_verify($password, $user['password'])) {
        increment_login_attempts();
        $remaining = MAX_LOGIN_ATTEMPTS - get_login_attempts();
        if ($remaining <= 0) {
            return ['success' => false, 'error' => 'Too many failed attempts. Access is locked.'];
        }
        return ['success' => false, 'error' => "Invalid username or password. {$remaining} attempt(s) remaining."];
    }

    reset_login_attempts();
    // Store only non-sensitive data in session
    $_SESSION['user'] = [
        'username' => $user['username'],
        'email'    => $user['email'],
    ];

    return ['success' => true];
}

function logout(): void {
    session_destroy();
}