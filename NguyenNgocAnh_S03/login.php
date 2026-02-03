<?php
session_start();

// Tài khoản mẫu (hard-code cho bài tập)
$correctUser = "admin";
$correctPass = "123456";

// Khởi tạo counter nếu chưa có
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

$message = "";

// Xử lý khi submit form
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === $correctUser && $password === $correctPass) {
        $message = "Login successful!";
        $_SESSION['attempts'] = 0; // reset khi đúng
    } else {
        $_SESSION['attempts']++;
        $message = "Login failed. Attempts: " . $_SESSION['attempts'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login with Counter</title>
</head>
<body>

<h2>Login</h2>

<form method="post">
    <label>
        Username:
        <input type="text" name="username">
    </label>
    <br><br>

    <label>
        Password:
        <input type="password" name="password">
    </label>
    <br><br>

    <button type="submit">Login</button>
</form>

<p><?php echo $message; ?></p>

</body>
</html>
