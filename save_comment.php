<?php
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$postId = (int) $_POST["post_id"];
$name = trim($_POST["name"]);
$comment = trim($_POST["comment"]);

$stmt = $pdo->prepare(
    "SELECT comments_enabled
     FROM posts
     WHERE id = ?"
);

$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("مطلب پیدا نشد.");
}

if (!$post["comments_enabled"]) {
    die("ارسال نظر برای این مطلب غیرفعال است.");
}

$stmt = $pdo->prepare(
    "INSERT INTO comments (post_id, name, comment)
     VALUES (?, ?, ?)"
);

$stmt->execute([
    $postId,
    $name,
    $comment
]);

header("Location: post.php?id=" . $postId);
exit;
