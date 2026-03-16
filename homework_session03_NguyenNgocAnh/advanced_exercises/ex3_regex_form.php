<?php
session_start();

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Username validation: only alphanumeric characters
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        $errors['username'] = 'Username must contain only alphanumeric characters.';
    } elseif (strlen($username) < 3 || strlen($username) > 20) {
        $errors['username'] = 'Username must be between 3 and 20 characters.';
    }

    // Password validation
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } else {
        if (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = 'Password missing uppercase letter.';
        }
        if (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = 'Password missing lowercase letter.';
        }
        if (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Password missing number.';
        }
        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $password)) {
            $errors['password'] = 'Password missing symbol.';
        }
        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        }
    }

    // Confirm password
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // If no errors, registration is successful
    if (empty($errors)) {
        $success = true;
        $_SESSION['username'] = htmlspecialchars($username);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form - Regex Validation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .form-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        }
        .error {
            color: #d32f2f;
            font-size: 13px;
            margin-top: 5px;
        }
        input.error-input {
            border-color: #d32f2f;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .requirements {
            background-color: #f9f9f9;
            border-left: 4px solid #4CAF50;
            padding: 15px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
        }
        .requirements h3 {
            margin-top: 0;
            color: #333;
        }
        .requirements ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .requirements li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Registration Form</h1>

        <?php if ($success): ?>
            <div class="success">
                ✓ Registration successful! Welcome, <?php echo $_SESSION['username']; ?>!
            </div>
        <?php endif; ?>

        <div class="requirements">
            <h3>Password Requirements:</h3>
            <ul>
                <li>At least 1 uppercase letter (A-Z)</li>
                <li>At least 1 lowercase letter (a-z)</li>
                <li>At least 1 number (0-9)</li>
                <li>At least 1 symbol (!@#$%^&*...</li>
                <li>Minimum 8 characters long</li>
            </ul>
            <h3>Username Requirements:</h3>
            <ul>
                <li>Only alphanumeric characters (a-z, A-Z, 0-9)</li>
                <li>3-20 characters long</li>
            </ul>
        </div>

        <form method="POST">
            <div class="form-group">
                <label for="username">Username:</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="example123"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    <?php echo isset($errors['username']) ? 'class="error-input"' : ''; ?>
                >
                <?php if (isset($errors['username'])): ?>
                    <div class="error"><?php echo $errors['username']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Abc123!@#"
                    <?php echo isset($errors['password']) ? 'class="error-input"' : ''; ?>
                >
                <?php if (isset($errors['password'])): ?>
                    <div class="error"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Abc123!@#"
                    <?php echo isset($errors['confirm_password']) ? 'class="error-input"' : ''; ?>
                >
                <?php if (isset($errors['confirm_password'])): ?>
                    <div class="error"><?php echo $errors['confirm_password']; ?></div>
                <?php endif; ?>
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</body>
</html>