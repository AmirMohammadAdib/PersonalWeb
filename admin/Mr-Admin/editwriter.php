<?php
include "header.php";

$success_update = null;
$id = $_GET['id'];
$select = $conn->prepare('SELECT * FROM writers WHERE id=?');
$select->bindValue(1, $id);
$select->execute();
$select = $select->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['sub'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone_number'];

    //uploader//
    $target_dir = "../../uploader/img-profile-writer/";
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
            $add_blog = true;
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
    $update = $conn->prepare('UPDATE writers SET username=? , fullname=? , image=? , blog=? , phone_number=? WHERE id=?');
    $update->bindValue(1, $username);
    $update->bindValue(2, $fullname);
    $update->bindValue(3, $new_name);
    $update->bindValue(4, $id);
    $update->bindValue(5, $phone);
    $update->bindValue(6, $id);
    $update->execute();
    $success_update = true;

}
?>

<form method="POST" enctype="multipart/form-data">
    <label>نام کاربری</label>
    <input type="text" name="username" class="form-control" placeholder="نام کاربری" value="<?= $select['username']; ?> "><br>
    <label>نام کامل(فارسی)</label>
    <input type="text" name="fullname" class="form-control" placeholder="نام کامل(فارسی)" value="<?= $select['fullname']; ?> "><br>
    <label>شماره تلفن</label>
    <input type="text" name="phone_number" class="form-control" placeholder="شماره تلفن" value="<?= $select['phone_number']; ?> "><br>
    <label>پروفایل کاربر</label>
    <input type="file" name="fileToUpload" id="fileToUpload" value="<?= $select['image']; ?> " required><br>

    <input type="submit" value="بروزرسانی مشخصات نویسنده" class="btn btn-warning" name="sub">
</form>

<?php if ($success_update) { ?>

<script>
const Toast = Swal.mixin({
    toast: true,
    position: 'top',
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
    title: 'نظر با موفقیت ثبت شد'
})
</script>
<?php } ?>

<?php include "footer.php" ?>
