<?php
$errors = [];
$formData = [
    'username' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData['username'] = trim($_POST['username'] ?? '');
    $formData['email'] = trim($_POST['email'] ?? '');
    $formData['password'] = $_POST['password'] ?? '';
    $formData['confirm_password'] = $_POST['confirm_password'] ?? '';

    // Validation
    if (empty($formData['username'])) {
        $errors['username'] = 'Username is required.';
    } elseif (strlen($formData['username']) < 3) {
        $errors['username'] = 'Username must be at least 3 characters.';
    }

    if (empty($formData['email'])) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($formData['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    if (empty($formData['password'])) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($formData['password']) < 6) {
        $errors['password'] = 'Password must be at least 6 characters.';
    }

    if ($formData['password'] !== $formData['confirm_password']) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // If no errors, process form
    if (empty($errors)) {
        echo '<div class="alert alert-success">Form submitted successfully!</div>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert ul {
            margin: 0;
            padding-left: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            font-size: 14px;
        }
        input.error {
            border: 2px solid #dc3545;
            background-color: #fff5f5;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>User Registration</h1>

    <!-- Error Summary Block -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input 
                type="text" 
                id="username" 
                name="username" 
                value="<?php echo htmlspecialchars($formData['username']); ?>"
                class="<?php echo isset($errors['username']) ? 'error' : ''; ?>"
            >
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                value="<?php echo htmlspecialchars($formData['email']); ?>"
                class="<?php echo isset($errors['email']) ? 'error' : ''; ?>"
            >
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input 
                type="password" 
                id="password" 
                name="password"
                class="<?php echo isset($errors['password']) ? 'error' : ''; ?>"
            >
        </div>

        <div class="form-group">
            <label for="confirm_password">Confirm Password:</label>
            <input 
                type="password" 
                id="confirm_password" 
                name="confirm_password"
                class="<?php echo isset($errors['confirm_password']) ? 'error' : ''; ?>"
            >
        </div>

        <button type="submit">Register</button>
    </form>
</body>
</html>