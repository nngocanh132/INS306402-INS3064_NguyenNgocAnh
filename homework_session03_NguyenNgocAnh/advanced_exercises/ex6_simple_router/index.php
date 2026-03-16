<?php
// Simple Router - Front Controller Pattern

// Get the page parameter from query string, default to 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Sanitize the page name to prevent directory traversal
$page = basename($page);

// Define the path to the pages directory
$pagesDir = __DIR__ . '/pages/';
$pagePath = $pagesDir . $page . '.php';

// Check if the page file exists
if (file_exists($pagePath)) {
    include $pagePath;
} else {
    // Handle 404 - Page Not Found
    http_response_code(404);
    echo '<h1>404 - Page Not Found</h1>';
    echo '<p>The page "' . htmlspecialchars($page) . '" does not exist.</p>';
    echo '<a href="?page=home">Return to Home</a>';
}
?>