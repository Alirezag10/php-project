<?php
require_once "config/database.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare(
    "SELECT comments_enabled
     FROM posts
     WHERE id = ?"
);

$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("مطلب پیدا نشد.");
}

$newStatus = $post["comments_enabled"] ? 0 : 1;

$stmt = $pdo->prepare(
    "UPDATE posts
     SET comments_enabled = ?
     WHERE id = ?"
);

$stmt->execute([
    $newStatus,
    $id
]);

header("Location: post.php?id=" . $id);
exit;
