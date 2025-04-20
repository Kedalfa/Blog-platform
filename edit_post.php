<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require 'db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['id'];

// Fetch post and check ownership
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post || $post['user_id'] != $_SESSION['user_id']) {
    die("Access denied.");
}

$title = $post['title'];
$content = $post['content'];
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    if (empty($title) || empty($content)) {
        $error = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ?, created_at = NOW() WHERE id = ?");
        $stmt->execute([$title, $content, $post_id]);
        $success = "Post updated successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .container {
      max-width: 700px;
      margin-top: 50px;
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

<div class="container bg-white p-5 shadow rounded">
  <h2 class="mb-4 text-warning">✏️ Edit Post</h2>

  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <form method="post">
    <div class="mb-3">
      <label class="form-label">Title</label>
      <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($title) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Content</label>
      <textarea name="content" rows="6" class="form-control" required><?= htmlspecialchars($content) ?></textarea>
    </div>

    <button type="submit" class="btn btn-warning btn-animated"><i class="fa-solid fa-check"></i> Save Changes</button>
    <a href="post.php?id=<?= $post_id ?>" class="btn btn-outline-secondary btn-animated">Cancel</a>
  </form>
</div>

</body>
</html>
