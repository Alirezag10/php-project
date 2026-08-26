<?php
require_once "config/database.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("مطلب پیدا نشد.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    $stmt = $pdo->prepare(
        "UPDATE posts
         SET title = ?, content = ?
         WHERE id = ?"
    );

    $stmt->execute([
        $title,
        $content,
        $id
    ]);

    header("Location: post.php?id=" . $id);
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>ویرایش مطلب</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>ویرایش مطلب</h1>

    <form method="POST">

        <label>عنوان</label>

        <input
            type="text"
            name="title"
            value="<?= htmlspecialchars($post["title"]) ?>"
            required
        >

        <label>متن</label>

        <textarea name="content" required><?= htmlspecialchars($post["content"]) ?></textarea>

        <button type="submit" class="btn btn-success">
            ذخیره تغییرات
        </button>

        <a
            href="post.php?id=<?= $id ?>"
            class="btn btn-primary"
        >
            انصراف
        </a>

    </form>

</div>

</body>
</html>
