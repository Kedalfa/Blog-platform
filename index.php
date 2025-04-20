<?php
session_start();

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");


require 'db.php';

// Fetch posts
$stmt = $pdo->query("SELECT posts.*, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>My Blog - Home</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(to right, #fdfbfb, #ebedee);
      font-family: 'Segoe UI', sans-serif;
    }
    .navbar {
      background-color: #ffffff;
    }
    .hero {
      background: linear-gradient(to right, #00c6ff, #0072ff);
      color: white;
      padding: 60px 20px;
      border-radius: 10px;
      margin-bottom: 30px;
      text-align: center;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .card {
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
<body>

<nav class="navbar navbar-expand-lg shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="index.php"><i class="fa-solid fa-feather"></i> My Blog</a>
    <div class="d-flex">
      <?php if (isset($_SESSION['user_id'])): ?>
        <a href="create_post.php" class="btn btn-success me-2 btn-animated"><i class="fa-solid fa-pen-to-square"></i> Create Post</a>
        <a href="logout.php" class="btn btn-danger btn-animated"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary me-2 btn-animated"><i class="fa-solid fa-right-to-bracket"></i> Login</a>
        <a href="signup.php" class="btn btn-secondary btn-animated"><i class="fa-solid fa-user-plus"></i> Sign Up</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<div class="container mt-4">
  <div class="hero">
    <h1 class="display-5">Welcome to My Blog</h1>
    <p class="lead">Read and share amazing stories from peoples around the world 🌍</p>
  </div>

  <h3 class="mb-4 text-primary">📝 Latest Posts</h3>

  <?php if (count($posts) > 0): ?>
    <?php foreach ($posts as $post): ?>
      <div class="card mb-4 shadow-sm">
        <div class="card-body">
          <h4 class="card-title"><?= htmlspecialchars($post['title']) ?></h4>
          <p class="card-text text-muted">By <?= htmlspecialchars($post['username']) ?> on <?= date('F j, Y', strtotime($post['created_at'])) ?></p>
          <p class="card-text"><?= nl2br(htmlspecialchars(substr($post['content'], 0, 150))) ?>...</p>
          <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-outline-primary btn-sm btn-animated"><i class="fa-solid fa-book-open-reader"></i> Make changes</a>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="alert alert-info">No blog posts yet. Be the first to write!</div>
  <?php endif; ?>
</div>

</body>
</html>
