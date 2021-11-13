<?php
include "header.php";
include "../../script/jdf.php";
$nummber = 1;
$success_add = null;

$blogs = $conn->prepare('SELECT * FROM blog');
$blogs->execute();
$blogs = $blogs->fetchAll(PDO::FETCH_ASSOC);

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

    $add_blog = $conn->prepare("INSERT INTO blog SET title=? , image=? , caption=? , caption_box=? , date=? , writer=?");
    $add_blog->bindValue(1, $title);
    $add_blog->bindValue(2, $new_name);
    $add_blog->bindValue(3, $caption);
    $add_blog->bindValue(4, $caption_box);
    $add_blog->bindValue(5, time());
    $add_blog->bindValue(6, $writer);
    $add_blog->execute();

    $success_add = true;
}

?>

<form method="POST" enctype="multipart/form-data">
    <label for="">عنوان مقاله</label>
    <input type="text" name="title" class="form-control" placeholder="عنوان مقاله"><br>

    <label>عکس مقاله</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>

    <label for="">کپشن مقاله</label>
    <textarea name="caption" class="editor" id="my-editor"></textarea><br>

    <label>کپشن باکس</label>
    <input type="text" name="caption_box" class="form-control" placeholder="کپشن باکس"><br>

    <label>نویسنده</label>
    <select name="writer" class="form-control">
        <?php foreach($writers as $writer){ ?>
            <option value="<?= $writer['id']; ?>" class="form-control"><?= $writer['fullname'] ?></option>
        <?php } ?>
    </select><br>

    <input type="submit" value="افزودن مقاله" class="btn btn-info" name="sub">
</form><br>

<table class="table">
    <thead>
        <tr>
            <th scope="col">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bar-chart" viewBox="0 0 16 16">
                    <path d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5v12h-2V2h2zm-2-1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1h-2zM6 7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm-5 4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3z" />
                </svg>
            </th>
            <th scope="col">عنوان بلاگ</th>
            <th scope="col">پوستر</th>
            <th scope="col">تاریخ</th>
            <th scope="col">نویسنده</th>
            <th scope="col">عکس نویسنده</th>
            <th scope="col">ویرایش، حذف</th>

        </tr>
    </thead>
    <?php foreach ($blogs as $blog) { ?>
        <tbody>
            <td><?= $nummber++; ?></td>
            <td><?= $blog['title']; ?></td>
            <td>
                <img src="../../uploader/upload-image-blog/<?= $blog['image']; ?>" width="140" style="border-radius: 5px;">
            </td>
            <td><?= jdate('Y-m-d', $blog['date']); ?> </td>
            <td><?php foreach($writers as $writer){if($blog['writer'] == $writer['id']){echo $writer['fullname'];}} ?></td>
            <td>
                <img src="../../uploader/img-profile-writer/<?php foreach($writers as $writer){if($blog['writer'] == $writer['id']){echo $writer['image'];}} ?>" width="100" style="border-radius: 70px;">
            </td>
            <td>
                <a href="editblog.php?id=<?= $blog['id']; ?> "><input type="submit" value="ویرایش" class="btn btn-warning"></a>
                <input type="submit" value="حذف" class="btn btn-danger">
            </td>
        </tbody>
    <?php } ?>

</table><br>

<?php

if ($success_add) { ?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: 'success',
            title: 'مقاله با موفقیت افزوده شد'
        })
    </script>
<?php }


include "footer.php"; ?>