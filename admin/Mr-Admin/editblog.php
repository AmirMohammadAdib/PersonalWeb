<?php
include "header.php";

$id = $_GET['id'];

$blogs = $conn->prepare('SELECT * FROM blog WHERE id=?');
$blogs->bindValue(1, $id);
$blogs->execute();
$blogs = $blogs->fetch(PDO::FETCH_ASSOC);


$writers = $conn->prepare('SELECT * FROM writers');
$writers->execute();
$writers = $writers->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['sub'])) {
    //uploader//
    $target_dir = "../../uploader/upload-image-blog/";
    $new_name = rand(1000, 100000) . basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $new_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (
        $imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif"
    ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file " . htmlspecialchars(basename($new_name)) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $title = $_POST['title'];
    $caption = $_POST['caption'];
    $caption_box = $_POST['caption_box'];
    $writer = $_POST['writer'];

    $add_blog = $conn->prepare("UPDATE blog SET title=? , image=? , caption=? , caption_box=? , date=? , writer=? WHERE id=?");
    $add_blog->bindValue(1, $title);
    $add_blog->bindValue(2, $new_name);
    $add_blog->bindValue(3, $caption);
    $add_blog->bindValue(4, $caption_box);
    $add_blog->bindValue(5, time());
    $add_blog->bindValue(6, $writer);
    $add_blog->bindValue(7, $id);

    $add_blog->execute();

    $success_add = true;
}

?>

<form method="POST" enctype="multipart/form-data">
    <label for="">عنوان مقاله</label>
    <input type="text" name="title" class="form-control" placeholder="عنوان مقاله" value="<?= $blogs['title']; ?>"><br>

    <label>عکس مقاله</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>

    <label for="">کپشن مقاله</label>
    <textarea name="caption" class="editor" id="my-editor"><?= $blogs['caption']; ?></textarea><br>

    <label>کپشن باکس</label>
    <input type="text" name="caption_box" class="form-control" placeholder="کپشن باکس" value="<?= $blogs['caption_box']; ?>"><br>

    <label>نویسنده</label>
    <select name="writer" class="form-control">
        <?php foreach($writers as $writer){ ?>
            <option value="<?= $writer['id']; ?>" class="form-control" ><?= $writer['fullname'] ?></option>
        <?php } ?>
    </select><br>

    <input type="submit" value="افزودن مقاله" class="btn btn-info" name="sub">
</form><br>

<?php include "footer.php"; ?>