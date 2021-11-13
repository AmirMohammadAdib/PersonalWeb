<?php 
include "header.php";
$id = $_GET['id'];

$menus = $conn->prepare("SELECT * FROM menus WHERE Z!=0");
$menus->execute();

$gt = $conn->prepare('SELECT * FROM course WHERE id=?');
$gt->bindValue(1, $id);
$gt->execute();
$gt = $gt->fetch(PDO::FETCH_ASSOC);


if (isset($_POST['sub'])) {
    $title = $_POST['title'];
    $caption = $_POST['caption'];
    $target_dir = "../../uploader/uploads/";
    $image_name = $_FILES["fileToUpload"]["name"];
    if(empty($image_name)){
        $new_name = $gt['image'];
    }else{
        $new_name = rand(1000, 100000) . basename($_FILES["fileToUpload"]["name"]);
    }
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
    $slug = $_POST['slug'];
    $value = $_POST['value'];
    $level = $_POST['level'];
    $tag = $_POST['tag'];
    $category = $_POST['category'];

    $date = time();

    $result = $conn->prepare('UPDATE course SET title=?  , content=? , image=? , slug=? , value=? , level=? , date=? , tag=? , category=? WHERE id=?');
    $result->bindValue(1, $title);
    $result->bindValue(2, $caption);
    $result->bindValue(3, $new_name);
    $result->bindValue(4, $slug);
    $result->bindValue(5, $value);
    $result->bindValue(6, $level);
    $result->bindValue(7, $date);
    $result->bindValue(8, $tag);

    $result->bindValue(9, $category);
    $result->bindValue(10, $id);

    
    $result->execute();
}







?>

<div>
    <form method="POST" enctype="multipart/form-data">

        <label> عنوان دوره اموزشی : </label>
        <input type="text" class="form-control" name="title" placeholder="عنوان دوره اموزشی" value="<?php echo $gt['title']; ?>" required><br>

        <label> توضیحات : </label>
        <textarea name="caption" class="editor" id="my-editor"><?php echo $gt['content']; ?></textarea><br>

        <label> عکس : </label>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>


        <label> ادرس اینترنتی : </label>
        <input type="text" class="form-control" name="slug" placeholder="ادرس اینترنتی دوره" value="<?php echo $gt['slug']; ?>" required><br>

        <label> دسته بندی : </label>
        <select name="category" class="form-control">
            <?php foreach($menus as $menu){ ?>
                <option value="<?php echo $menu['Id'] ?>"><?php echo $menu['Title']; ?></option>
            <?php } ?>
        </select><br>


        <label> قیمت دوره : </label>
        <input type="text" class="form-control" name="value" placeholder="قیمت دوره اموزشی" value="<?php echo $gt['value']; ?>" required><br>

        <label> سطح دوره : </label>
        <select name="level" class="form-control" value="<?php echo $gt['level']; ?>">
            <option value="0">مقدماتی</option>
            <option value="1">متوسط</option>
            <option value="2">پیشرفته</option>

        </select><br>

        <label> برچسب های دوره : </label>
        <input type="text" class="form-control" name="tag" placeholder="برچسب های دوره اموزشی" value="<?php echo $gt['tag']; ?>" required><br>


        <input type="submit" name="sub" value="اپدیت دوره" class="btn btn-primary">
        <a href="course.php"><input type="submit" value="برگشت" class="btn" style="background-color: #f5aa42; border: none; color: #ffffff;"></a>


    </form><br>
</div>

<?php

include "footer.php";

?>