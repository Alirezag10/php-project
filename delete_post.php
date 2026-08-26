<?php
require_once "config/database.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare(
    "SELECT image FROM posts WHERE id = ?"
);

$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("مطلب پیدا نشد.");
}

if (!empty($post["image"])) {

    $imagePath = "uploads/" . $post["image"];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

$stmt = $pdo->prepare(
    "DELETE FROM posts WHERE id = ?"
);

$stmt->execute([$id]);

header("Location: index.php");
exit;
