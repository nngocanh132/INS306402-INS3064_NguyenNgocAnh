<?php
session_start();

// Generate CSRF token if not exists

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('403 Forbidden');

    }
    
    // Process form data safely
    echo "<p>Form submitted successfully!</p>";
    echo "<p>Data: " . htmlspecialchars($_POST['data'] ?? '') . "</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSRF Protection Form</title>
</head>
<body>
    <h1>Secure Form with CSRF Protection</h1>
    
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        
        <label for="data">Enter Data:</label>
        <input type="text" id="data" name="data" required>
        
        <button type="submit">Submit</button>
    </form>
</body>
</html>