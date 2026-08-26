<?php
require_once "config/database.php";

$limit = 5;

$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$countStmt = $pdo->query("SELECT COUNT(*) FROM posts");
$totalPosts = $countStmt->fetchColumn();
$totalPages = ceil($totalPosts / $limit);

$stmt = $pdo->prepare(
    "SELECT * FROM posts
     ORDER BY created_at DESC
     LIMIT :limit OFFSET :offset"
);

$stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>وبلاگ من</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<header>
    <div class="container">
        <h1><a href="index.php">وبلاگ من</a></h1>
    </div>
</header>

<div class="container">

    <a href="create.php" class="btn btn-success">ایجاد مطلب جدید</a>

    <br><br>

    <?php if (count($posts) == 0): ?>
        <div class="post">
            <p>هنوز مطلبی منتشر نشده است.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($posts as $post): ?>

        <article class="post">

            <h2>
                <a href="post.php?id=<?= $post['id'] ?>">
                    <?= htmlspecialchars($post['title']) ?>
                </a>
            </h2>

            <div class="post-date">
                <?= htmlspecialchars($post['created_at']) ?>
            </div>

            <?php if (!empty($post['image'])): ?>
                <img
                    src="uploads/<?= htmlspecialchars($post['image']) ?>"
                    class="post-image"
                    alt=""
                >
            <?php endif; ?>

            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
            </div>

            <br>

            <a href="post.php?id=<?= $post['id'] ?>" class="btn btn-primary">
                ادامه مطلب
            </a>

            <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-warning">
                ویرایش
            </a>

            <a
                href="delete_post.php?id=<?= $post['id'] ?>"
                class="btn btn-danger"
                onclick="return confirm('آیا از حذف این مطلب مطمئن هستید؟')"
            >
                حذف
            </a>

            <a
                href="toggle_comments.php?id=<?= $post['id'] ?>"
                class="btn btn-warning"
            >
                <?= $post["comments_enabled"] ? "خاموش کردن نظرات" : "روشن کردن نظرات" ?>
            </a>

        </article>

    <?php endforeach; ?>

    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a
                href="index.php?page=<?= $i ?>"
                class="<?= ($i == $page) ? 'active' : '' ?>"
            >
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

</div>

</body>
</html>
