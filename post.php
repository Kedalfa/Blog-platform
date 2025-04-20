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

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$post_id = $_GET['id'];

// Get the post
$stmt = $pdo->prepare("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id WHERE posts.id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("Post not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($post['title']) ?> - Blog</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    .container {
      max-width: 800px;
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

<div class="container bg-white shadow-sm p-5 rounded">
  <h1 class="mb-3"><?= htmlspecialchars($post['title']) ?></h1>
  <p class="text-muted">By <strong><?= htmlspecialchars($post['username']) ?></strong> on <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
  <hr>
  <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>

  <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
    <div class="mt-4">
      <a href="edit_post.php?id=<?= $post['id'] ?>" class="btn btn-warning btn-animated"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
      <a href="delete_post.php?id=<?= $post['id'] ?>" class="btn btn-danger btn-animated" onclick="return confirm('Are you sure?')"><i class="fa-solid fa-trash"></i> Delete</a>
    </div>
  <?php endif; ?>

  <div class="mt-5">
    <a href="index.php" class="btn btn-outline-primary btn-animated"><i class="fa-solid fa-arrow-left"></i> Back to Home</a>
  </div>
</div>

</body>
</html>
