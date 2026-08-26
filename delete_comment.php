<?php
require_once "config/database.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare(
    "SELECT post_id FROM comments WHERE id = ?"
);

$stmt->execute([$id]);
$comment = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comment) {
    die("نظر پیدا نشد.");
}

$postId = $comment["post_id"];

$stmt = $pdo->prepare(
    "DELETE FROM comments WHERE id = ?"
);

$stmt->execute([$id]);

header("Location: post.php?id=" . $postId);
exit;
