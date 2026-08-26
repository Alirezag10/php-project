<?php
require_once "config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);
    $imageName = null;

    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] === UPLOAD_ERR_OK
    ) {
        $allowed = [
            "image/jpeg",
            "image/png",
            "image/gif",
            "image/webp"
        ];

        $fileType = mime_content_type($_FILES["image"]["tmp_name"]);

        if (!in_array($fileType, $allowed)) {
            die("فرمت تصویر مجاز نیست.");
        }

        $extension = strtolower(
            pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION)
        );

        $imageName = uniqid() . "." . $extension;

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            "uploads/" . $imageName
        );
    }

    $stmt = $pdo->prepare(
        "INSERT INTO posts (title, content, image)
         VALUES (?, ?, ?)"
    );

    $stmt->execute([
        $title,
        $content,
        $imageName
    ]);

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">
<head>
    <meta charset="UTF-8">
    <title>ایجاد مطلب</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h1>ایجاد مطلب جدید</h1>

    <form method="POST" enctype="multipart/form-data">

        <label>عنوان مطلب</label>

        <input type="text" name="title" required>

        <label>متن مطلب</label>

        <textarea name="content" required></textarea>

        <label>تصویر</label>

        <input type="file" name="image" accept="image/*">

        <button type="submit" class="btn btn-success">
            انتشار مطلب
        </button>

        <a href="index.php" class="btn btn-primary">
            بازگشت
        </a>

    </form>

</div>

</body>
</html>
