<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$errors = [];
$success = false;

// Detect POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';

    // Validation
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($message)) {
        $errors[] = "Message is required";
    }

    // PRG pattern: redirect on success
    if (empty($errors)) {
        header("Location: contact_self.php?success=1");
        exit;
    }
}

// Detect GET feedback
if (isset($_GET['success'])) {
    $success = true;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Self Form</title>

<style>
@import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap');

body {
    margin: 0;
    min-height: 100vh;
    font-family: 'Quicksand', sans-serif;

    background:
        linear-gradient(rgba(255,192,203,0.6), rgba(255,192,203,0.6)),
        url("https://i.pinimg.com/736x/9d/8e/7f/9d8e7f6f9f3f4d0cfc2d3b8dba6f5d25.jpg");

    background-size: cover;
    background-position: center;

    display: flex;
    justify-content: center;
    align-items: center;
}

.box {
    background: white;
    width: 380px;
    padding: 30px;
    border-radius: 22px;
    box-shadow: 0 10px 25px rgba(255,105,180,0.35);
    text-align: center;
}

h2 {
    color: #ff69b4;
}

input, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 14px;
    border-radius: 12px;
    border: 1px solid #ffc0cb;
    outline: none;
    font-family: inherit;
}

textarea {
    resize: none;
    height: 90px;
}

button {
    width: 100%;
    padding: 10px;
    background: #ff69b4;
    color: white;
    border: none;
    border-radius: 15px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background: #ff85c1;
}

.error {
    color: #ff1493;
    margin-bottom: 10px;
    text-align: left;
}

.success {
    color: #ff1493;
    font-weight: 600;
}
</style>
</head>

<body>

<div class="box">
    <h2>🎀 Contact 🎀</h2>

    <?php if ($success): ?>
        <p class="success">💖 Message sent successfully!</p>
    <?php else: ?>

        <?php if ($errors): ?>
            <div class="error">
                <ul>
                    <?php foreach ($errors as $e): ?>
                        <li><?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <input type="text" name="name" placeholder="Your name">
            <input type="email" name="email" placeholder="Your email">
            <textarea name="message" placeholder="Your message"></textarea>
            <button type="submit">Send 💌</button>
        </form>

    <?php endif; ?>
</div>

</body>
</html>
