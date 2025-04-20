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

// Fetch the post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

// Verify ownership
if (!$post || $post['user_id'] != $_SESSION['user_id']) {
    die("Access denied.");
}

// Delete the post
$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
$stmt->execute([$post_id]);

// Redirect to home
header("Location: index.php");
exit();
