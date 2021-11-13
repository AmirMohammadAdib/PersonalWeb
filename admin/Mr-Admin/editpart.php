<?php
include "header.php";
include "../../script/jdf.php";
$id = $_GET['id'];
$course = $_GET['course'];
$course = $conn->prepare('SELECT * FROM course WHERE id=?');
$course->bindValue(1, $id);
$course->execute();
$course = $course->fetch(PDO::FETCH_ASSOC);

$parts = $conn->prepare('SELECT * FROM part WHERE id=?');
$parts->bindValue(1, $id);
$parts->execute();
$parts = $parts->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['sub'])) {

    $status = $_POST['status'];
    $title = $_POST['title'];
    $time = $_POST['time'];

    //uploader//
    $target_dir = "../../uploader/part_video/";
    $new_name = rand(1000, 100000) . basename($_FILES["fileToUpload"]["name"]);
    $target_file = $target_dir . $new_name;
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));


    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 2400000000) {
        echo "<script>
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'error',
                        title: 'حداکثر حجم اپلود 300 مگابایته'
                    })
                </script>";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "mp4" && $imageFileType != "mkv" && $imageFileType != "mp3") {
        echo "<script>
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })

                    Toast.fire({
                        icon: 'error',
                        title: 'فرمت ویدئو mp4, mkv نیست'
                    })
                </script>";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "<script>
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
                        icon: 'error',
                        title: 'اپلود با مشکل مواجه شد'
                    })
                </script>";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>
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
                        title: 'ویدئو با موفقیت بروزرسانی شد'
                    })
                </script>";
        } else {
            echo "اپلود با مشکل مواجه شد";
        }

        $result = $conn->prepare('UPDATE part SET link=? , status=? , title=? , time=? WHERE id=?');
        $result->bindValue(1, $new_name);
        $result->bindValue(2, $status);
        $result->bindValue(3, $title);
        $result->bindValue(4, $time);
        $result->bindValue(5, $id);
        $result->execute();
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <?php foreach ($parts as $part) { ?>
        <label> عنوان قسمت : </label>
        <input type="text" name="title" class="form-control" value="<?php echo $part['title']; ?>"><br>

        <label> ویدئو : </label>
        <input type="file" name="fileToUpload" id="fileToUpload" class="form-control" value="<?php echo $part['link']; ?>"><br>

        <label>نوع : </label>
        <select value="status" name="status" class="form-control shadow">
            <option name="free" <?php if($part['status'] == "رایگان"){ ?> selected <?php }?>>رایگان</option>
            <option name="cash" <?php if($part['status'] == "نقدی"){ ?> selected <?php }?>>نقدی</option>
        </select><br>

        <label> مدت زمان دوره : </label>
        <input type="text" name="time" class="form-control" value="<?php echo $part['time']; ?>"><br>

        <input type="submit" value="بروزرسانی دوره" name="sub" class="btn btn-info"><br>
    <?php } ?>
</form><br>

<?php include "footer.php"; ?>