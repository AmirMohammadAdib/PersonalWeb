<?php
include "header.php";
include "../../script/jdf.php";
$id = $_GET['id'];
$number = 1;

$course = $conn->prepare('SELECT * FROM course WHERE id=?');
$course->bindValue(1, $id);
$course->execute();
$course = $course->fetch(PDO::FETCH_ASSOC);

$parts = $conn->prepare('SELECT * FROM part WHERE course=?');
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
                        title: 'ویدئو با موفقیت اپلود شد'
                    })
                </script>";
        } else {
            echo "اپلود با مشکل مواجه شد";
        }
        $result = $conn->prepare('INSERT INTO part SET link=? , course=? , status=? , title=? , time=?');
        $result->bindValue(1, $new_name);
        $result->bindValue(2, $id);
        $result->bindValue(3, $status);
        $result->bindValue(4, $title);
        $result->bindValue(5, $time);

        $result->execute();
    }
}
?>


<form method="POST" enctype="multipart/form-data">

    <label> عنوان قسمت : </label>
    <input type="text" name="title" class="form-control"><br>

    <label> ویدئو : </label>
    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control"><br>

    <label>نوع : </label>
    <select value="status" name="status" class="form-control shadow">
        <option name="free" selected>رایگان</option>
        <option name="cash">نقدی</option>
    </select><br>

    <label> مدت زمان دوره : </label>
    <input type="text" name="time" class="form-control"><br>

    <input type="submit" value="ثبت دوره" name="sub" class="btn btn-info"><br>
</form><br>

<table class="table table-striped bg-light">
    <thead>
        <th scope="col">عنوان قسمت</th>
        <th scope="col">ویدئو</th>
        <th scope="col">وضعیت</th>
        <th scope="col">زمان</th>
        <th scope="col">ویرایش، حذف</th>
    </thead>
    <tbody>
        <?php foreach ($parts as $part) { ?>
            <tr>
                <td><?php echo $number++; ?></td>
                <td><?php echo $part['title']; ?></td>
                <td>
                    <video src="../../uploader/part_video/<?php echo $part['link']; ?>" controls style="width: 180px; background-color: #ffffff; border-radius: 5px; padding: 6px; box-shadow: 0px 0px 5px #7f848a;"></video>
                </td>
                <td><?php if ($part['status'] == "رایگان") {
                        echo "رایگان";
                    } elseif ($part['status'] == "نقدی") {
                        echo "نقدی";
                    } ?></td>
                <td><?php echo $part['time']; ?></td>
                <td>
                    <a href="deletepart.php?id=<?php echo $allMenu['Id']; ?>" class="btn btn-danger">حذف</a>
                    <a href="editpart.php?id=<?php echo $part['id']; ?>&course=<?php echo $id; ?>" class="btn btn-warning">ویرایش</a>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>


<?php include "footer.php"; ?>