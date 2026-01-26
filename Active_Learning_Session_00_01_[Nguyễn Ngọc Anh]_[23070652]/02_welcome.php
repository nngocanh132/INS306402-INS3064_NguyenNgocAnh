<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome | INS3064</title>

  <style>
    body{
      margin: 0;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 24px;
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 55%, #fbc2eb 100%);
    }

    .card{
      width: 100%;
      max-width: 650px;
      background: #ffffff;
      border-radius: 14px;
      padding: 34px;
      box-shadow: 0 12px 35px rgba(0,0,0,0.18);
      text-align: center;
    }

    .card h1{
      margin: 0 0 8px;
      color: #6a11cb;
      font-size: 30px;
    }

    .subtitle{
      margin: 0 0 22px;
      color: #555;
      font-size: 15px;
    }

    .details{
      background: #f7f7ff;
      border: 1px solid #ececff;
      padding: 18px 20px;
      border-radius: 10px;
      text-align: left;
    }

    .row{
      padding: 10px 0;
      border-bottom: 1px dashed #d9d9f0;
      font-size: 16px;
    }

    .row:last-child{
      border-bottom: none;
    }

    .tag{
      display: inline-block;
      min-width: 130px;
      font-weight: 700;
      color: #6a11cb;
    }
  </style>
</head>

<body>
  <div class="card">
    <h1>Welcome to INS3064</h1>
    <p class="subtitle">Student Information & Live Date/Time</p>

    <?php
      // Basic student profile
      $name = "Nguyen Ngoc Anh";
      $studentId = "23070652";
      $class = "INS306402";
      $email = "ngocanhh05@gmail.com";

      // Current date & time
      $today = date("l, F j, Y");
      $now = date("H:i:s");

      echo '<div class="details">';
      echo '<div class="row"><span class="tag">Full Name:</span> ' . $name . '</div>';
      echo '<div class="row"><span class="tag">Student ID:</span> ' . $studentId . '</div>';
      echo '<div class="row"><span class="tag">Class/Section:</span> ' . $class . '</div>';
      echo '<div class="row"><span class="tag">Email:</span> ' . $email . '</div>';
      echo '<div class="row"><span class="tag">Date:</span> ' . $today . '</div>';
      echo '<div class="row"><span class="tag">Time:</span> ' . $now . '</div>';
      echo '</div>';
    ?>
  </div>
</body>
</html>
