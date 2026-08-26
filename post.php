<?php
require_once "config/database.php";

$id = isset($_GET["id"]) ? (int) $_GET["id"] : 0;

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);

$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    die("مطلب پیدا نشد.");
}

$commentStmt = $pdo->prepare(
    "SELECT * FROM comments
     WHERE post_id = ?
     ORDER BY created_at DESC"
);

$commentStmt->execute([$id]);
$comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">

    <title>
        <?= htmlspecialchars($post["title"]) ?>
    </title>

    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <article class="post">

        <h1><?= htmlspecialchars($post["title"]) ?></h1>

        <div class="post-date">
            <?= htmlspecialchars($post["created_at"]) ?>
        </div>

        <?php if (!empty($post["image"])): ?>
            <img
                src="uploads/<?= htmlspecialchars($post["image"]) ?>"
                class="post-image"
                alt=""
            >
        <?php endif; ?>

        <div class="post-content">
            <?= nl2br(htmlspecialchars($post["content"])) ?>
        </div>

        <table>
            <tr>
                <th>ردیف</th>
                <th>عنوان</th>
                <th>توضیحات</th>
            </tr>

            <tr>
                <td>1</td>
                <td>PHP</td>
                <td>زبان سمت سرور</td>
            </tr>

            <tr>
                <td>2</td>
                <td>MySQL</td>
                <td>سیستم مدیریت دیتابیس</td>
            </tr>
        </table>

        <a href="edit.php?id=<?= $post['id'] ?>" class="btn btn-warning">
            ویرایش
        </a>

        <a
            href="delete_post.php?id=<?= $post['id'] ?>"
            class="btn btn-danger"
            onclick="return confirm('آیا از حذف این مطلب مطمئن هستید؟')"
        >
            حذف مطلب
        </a>

        <a
            href="toggle_comments.php?id=<?= $post['id'] ?>"
            class="btn btn-warning"
        >
            <?= $post["comments_enabled"] ? "خاموش کردن نظرات" : "روشن کردن نظرات" ?>
        </a>

    </article>

    <?php if ($post["comments_enabled"]): ?>

        <section class="comments">

            <h2>نظرات کاربران</h2>

            <form action="save_comment.php" method="POST">

                <input
                    type="hidden"
                    name="post_id"
                    value="<?= $post["id"] ?>"
                >

                <label>نام شما</label>

                <input type="text" name="name" required>

                <label>نظر شما</label>

                <textarea name="comment" required></textarea>

                <button type="submit" class="btn btn-primary">
                    ارسال نظر
                </button>

            </form>

            <?php foreach ($comments as $comment): ?>

                <div class="comment">

                    <div class="comment-name">
                        <?= htmlspecialchars($comment["name"]) ?>
                    </div>

                    <div class="comment-date">
                        <?= htmlspecialchars($comment["created_at"]) ?>
                    </div>

                    <p>
                        <?= nl2br(htmlspecialchars($comment["comment"])) ?>
                    </p>

                    <a
                        href="delete_comment.php?id=<?= $comment["id"] ?>"
                        class="btn btn-danger"
                        onclick="return confirm('آیا از حذف این نظر مطمئن هستید؟')"
                    >
                        حذف نظر
                    </a>

                </div>

            <?php endforeach; ?>

        </section>

    <?php else: ?>

        <div class="post">
            <p>امکان ارسال نظر برای این مطلب غیرفعال است.</p>
        </div>

    <?php endif; ?>

    <a href="index.php" class="btn btn-primary">
        بازگشت به صفحه اصلی
    </a>

</div>

</body>
</html>
