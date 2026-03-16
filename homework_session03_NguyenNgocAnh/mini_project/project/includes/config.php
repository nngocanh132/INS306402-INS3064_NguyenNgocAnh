<?php
// ─── Paths ───────────────────────────────────────────────────────────────────
define('BASE_PATH',    dirname(__DIR__));
define('INCLUDES',     BASE_PATH . '/includes');
define('UPLOADS_DIR',  BASE_PATH . '/assets/uploads');
define('UPLOADS_URL',  '../assets/uploads');
define('USERS_FILE',   INCLUDES . '/users.json');

// ─── Security ─────────────────────────────────────────────────────────────────
define('MAX_LOGIN_ATTEMPTS', 3);
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_IMAGE_EXTS',  ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('MAX_FILE_SIZE', 2 * 1024 * 1024); // 2 MB