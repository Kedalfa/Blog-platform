<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$title = $content = "";
$error = $success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Please fill in both title and content.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, user_id, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$title, $content, $_SESSION['user_id']]);

        header("Location: index.php");
        exit();
        
        $title = $content = ""; // clear form
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .form-container {
      max-width: 700px;
      margin: auto;
      margin-top: 60px;
    }
    .btn-animated {
      transition: all 0.3s ease;
    }
    .btn-animated:hover {
      transform: scale(1.05);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body class="bg-light">

<div class="container form-container">
  <h2 class="mb-4 text-primary">📝 Create a New Post</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>

  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Post Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Post Content</label>
      <textarea name="content" rows="6" class="form-control" required><?= htmlspecialchars($content) ?></textarea>
    </div>

    <button type="submit" class="btn btn-success btn-animated"><i class="fa-solid fa-upload"></i> Publish</button>
    <a href="index.php" class="btn btn-outline-secondary btn-animated">Cancel</a>
  </form>
</div>

</body>
</html>
