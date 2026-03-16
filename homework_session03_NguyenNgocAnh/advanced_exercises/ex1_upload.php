<?php
// upload.php

// 1) Cấu hình yêu cầu
$uploadDir = __DIR__ . '/uploads/';     // đường dẫn thật trên server
$maxSize   = 2 * 1024 * 1024;           // 2MB
$allowedMime = [
  'image/jpeg' => 'jpg',
  'image/png'  => 'png',
];

// 2) Hàm escape output để tránh XSS khi in ra
function e(string $s): string {
  return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

// 3) Chuẩn hóa thông báo lỗi upload từ $_FILES['...']['error']
function uploadErrorMessage(int $code): string {
  return match ($code) {
    UPLOAD_ERR_OK         => 'OK',
    UPLOAD_ERR_INI_SIZE   => 'File vượt quá giới hạn upload_max_filesize trong php.ini.',
    UPLOAD_ERR_FORM_SIZE  => 'File vượt quá MAX_FILE_SIZE của form.',
    UPLOAD_ERR_PARTIAL    => 'File chỉ upload được một phần.',
    UPLOAD_ERR_NO_FILE    => 'Bạn chưa chọn file.',
    UPLOAD_ERR_NO_TMP_DIR => 'Thiếu thư mục tạm (tmp).',
    UPLOAD_ERR_CANT_WRITE => 'Không ghi được file lên disk.',
    UPLOAD_ERR_EXTENSION  => 'Upload bị chặn bởi PHP extension.',
    default               => 'Lỗi upload không xác định.',
  };
}

$message = '';
$successPath = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // 4) Kiểm tra tồn tại input file
  if (!isset($_FILES['avatar'])) {
    $message = 'Thiếu trường avatar trong form.';
  } else {
    $file = $_FILES['avatar'];

    // 5) PHP must check $_FILES for errors
    if (!is_array($file) || !isset($file['error'])) {
      $message = 'Dữ liệu upload không hợp lệ.';
    } elseif ($file['error'] !== UPLOAD_ERR_OK) {
      $message = uploadErrorMessage((int)$file['error']);
    } elseif (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
      // chống giả mạo đường dẫn tmp
      $message = 'File upload không hợp lệ (tmp_name).';
    } elseif (!isset($file['size']) || (int)$file['size'] <= 0) {
      $message = 'File rỗng hoặc size không hợp lệ.';
    } elseif ((int)$file['size'] > $maxSize) {
      // 6) Validate size (max 2MB)
      $message = 'File quá lớn. Tối đa 2MB.';
    } else {
      // 7) Validate MIME type (allow only image/jpeg, image/png)
      $finfo = new finfo(FILEINFO_MIME_TYPE);
      $mime  = $finfo->file($file['tmp_name']); // đọc MIME từ nội dung file

      if ($mime === false) {
        $message = 'Không thể xác định MIME type.';
      } elseif (!array_key_exists($mime, $allowedMime)) {
        $message = 'Chỉ cho phép JPG hoặc PNG.';
      } else {
        // 8) Đảm bảo uploads/ tồn tại
        if (!is_dir($uploadDir)) {
          $message = 'Thiếu thư mục uploads/. Hãy tạo folder uploads/ và cấp quyền ghi.';
        } elseif (!is_writable($uploadDir)) {
          $message = 'Thư mục uploads/ không có quyền ghi.';
        } else {
          // 9) Rename to a unique ID to prevent overwrites
          $ext = $allowedMime[$mime];
          $uniqueName = bin2hex(random_bytes(16)) . '.' . $ext; // 32 hex chars
          $destPath = $uploadDir . $uniqueName;

          // 10) Move the file using move_uploaded_file()
          if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $message = 'Upload avatar thành công!';
            $successPath = 'uploads/' . $uniqueName; // đường dẫn tương đối để hiển thị
          } else {
            $message = 'Không thể lưu file. Kiểm tra quyền ghi của uploads/.';
          }
        }
      }
    }
  }
}
?>
<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Secure Avatar Upload</title>
  <style>
    body { font-family: system-ui, -apple-system, Segoe UI, Roboto, Arial; max-width: 720px; margin: 32px auto; padding: 0 16px; }
    .card { border: 1px solid #ddd; border-radius: 12px; padding: 16px; }
    .row { margin: 12px 0; }
    .msg { padding: 12px; border-radius: 10px; background: #f6f6f6; border: 1px solid #e5e5e5; }
    img { max-width: 160px; height: auto; border-radius: 12px; border: 1px solid #ddd; }
    label { display:block; font-weight:600; margin-bottom:6px; }
  </style>
</head>
<body>
  <h1>4.11 Secure Avatar Upload</h1>

  <?php if ($message !== ''): ?>
    <div class="msg"><?= e($message) ?></div>
  <?php endif; ?>

  <?php if ($successPath !== ''): ?>
    <div class="row">
      <p>Avatar đã upload:</p>
      <img src="<?= e($successPath) ?>" alt="Uploaded avatar" />
      <p><small>Đường dẫn: <?= e($successPath) ?></small></p>
    </div>
  <?php endif; ?>

  <div class="card">
    <form method="post" enctype="multipart/form-data">
      <div class="row">
        <label for="avatar">Chọn ảnh avatar (JPG/PNG, tối đa 2MB)</label>
        <input id="avatar" name="avatar" type="file" accept="image/jpeg,image/png" required />
      </div>
      <div class="row">
        <button type="submit">Upload</button>
      </div>
    </form>
  </div>

  <p><small>Gợi ý chạy local: đặt trong htdocs (XAMPP) hoặc www (Laragon), truy cập /upload.php</small></p>
</body>
</html>