<?php
require_once 'Database.php';

$db = Database::getInstance()->getConnection();

/* Lấy danh sách category để đổ vào dropdown */
$catSql = "SELECT id, category_name FROM categories";
$catStmt = $db->prepare($catSql);
$catStmt->execute();
$categories = $catStmt->fetchAll();

/* Lấy dữ liệu từ form */
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

/* SQL chính để lấy sản phẩm */
$sql = "SELECT p.id, p.name, p.price, c.category_name, p.stock
        FROM products p
        LEFT JOIN categories c ON p.category_id = c.id
        WHERE 1=1";

$params = [];

/* Nếu người dùng nhập tên sản phẩm */
if (!empty($search)) {
    $sql .= " AND p.name LIKE :search";
    $params[':search'] = "%$search%";
}

/* Nếu người dùng chọn category */
if (!empty($category)) {
    $sql .= " AND p.category_id = :category";
    $params[':category'] = $category;
}

/* Chuẩn bị và chạy query */
$stmt = $db->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f5f5f5;
        }

        h2 {
            text-align: center;
        }

        form {
            margin-bottom: 20px;
            text-align: center;
        }

        input, select, button {
            padding: 8px 12px;
            margin: 5px;
        }

        table {
            width: 90%;
            margin: auto;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        .low-stock {
            background-color: #f8d7da;
        }
    </style>
</head>
<body>

    <h2>Product Administration Dashboard</h2>

    <form method="GET">
        <input type="text" name="search" placeholder="Search product name..."
               value="<?= htmlspecialchars($search) ?>">

        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"
                    <?= ($category == $cat['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['category_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filter</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Category Name</th>
                <th>Stock</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
                <tr class="<?= ($product['stock'] < 10) ? 'low-stock' : '' ?>">
                    <td><?= htmlspecialchars($product['id']) ?></td>
                    <td><?= htmlspecialchars($product['name']) ?></td>
                    <td><?= htmlspecialchars($product['price']) ?></td>
                    <td><?= htmlspecialchars($product['category_name'] ?? 'No Category') ?></td>
                    <td><?= htmlspecialchars($product['stock']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>