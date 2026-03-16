<?php
$method = $_SERVER['REQUEST_METHOD'];
$data = [];
$submittedMethod = '';

if ($method === 'POST') {
    $data = $_POST;
    $submittedMethod = 'POST';
} elseif ($method === 'GET') {
    $data = $_GET;
    $submittedMethod = 'GET';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GET vs POST Toggle</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        form { border: 1px solid #ccc; padding: 20px; margin: 20px 0; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], select { width: 100%; padding: 8px; box-sizing: border-box; }
        button { padding: 10px 20px; background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        .result { background-color: #f0f0f0; padding: 15px; margin-top: 20px; border-left: 4px solid #007bff; }
        .result h3 { margin-top: 0; color: #007bff; }
        pre { background-color: #fff; padding: 10px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>GET vs POST Toggle</h1>

        <!-- GET Form -->
        <form method="GET" id="getForm">
            <h2>Send via GET</h2>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="country">Country:</label>
                <select id="country" name="country">
                    <option value="">Select a country</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="Vietnam">Vietnam</option>
                </select>
            </div>
            <button type="submit">Submit via GET</button>
        </form>

        <!-- POST Form -->
        <form method="POST" id="postForm">
            <h2>Send via POST</h2>
            <div class="form-group">
                <label for="name2">Name:</label>
                <input type="text" id="name2" name="name" placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="email2">Email:</label>
                <input type="email" id="email2" name="email" placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="country2">Country:</label>
                <select id="country2" name="country">
                    <option value="">Select a country</option>
                    <option value="USA">USA</option>
                    <option value="Canada">Canada</option>
                    <option value="Vietnam">Vietnam</option>
                </select>
            </div>
            <button type="submit">Submit via POST</button>
        </form>

        <!-- Display Results -->
        <?php if (!empty($data)): ?>
            <div class="result">
                <h3>Data Received via <?php echo htmlspecialchars($submittedMethod); ?>:</h3>
                <pre><?php echo htmlspecialchars(print_r($data, true)); ?></pre>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>