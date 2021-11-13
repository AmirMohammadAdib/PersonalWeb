<?php
include "header.php";
include "../../script/jdf.php";

$menus = $conn->prepare("SELECT * FROM menus WHERE Z!=0");
$menus->execute();

$numberCourse = 1;
$courses = $conn->prepare('SELECT * FROM course');
$courses->execute();
$courses = $courses->fetchAll(PDO::FETCH_ASSOC);
if (isset($_POST['sub'])) {
    $title = $_POST['title'];
    $caption = $_POST['caption'];
    $pay = $_POST['pay'];

    
    //uploader//
    $target_dir = "../../uploader/uploads/";
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

    $slug = $_POST['slug'];
    $value = $_POST['value'];
    $level = $_POST['level'];
    $tag = $_POST['tag'];
    $date = time();
    $status = $_POST['status'];


    $result = $conn->prepare('INSERT INTO course SET title=?  , content=? , image=? , slug=? , value=? , level=? , date=? , tag=? , status=? , pay=?');
    $result->bindValue(1, $title);
    $result->bindValue(2, $caption);
    $result->bindValue(3, $new_name);
    $result->bindValue(4, $slug);
    $result->bindValue(5, $value);
    $result->bindValue(6, $level);
    $result->bindValue(7, $date);
    $result->bindValue(8, $tag);
    $result->bindValue(9, $status);
    $result->bindValue(10, $pay);
    $result->execute();
}


?>


<div>
    <form method="POST" enctype="multipart/form-data">

        <label> عنوان دوره اموزشی : </label>
        <input type="text" class="form-control" name="title" placeholder="عنوان دوره اموزشی" required><br>

        <label> توضیحات : </label>
        <textarea name="caption" class="editor" id="my-editor"></textarea><br>

        <label> عکس : </label>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>

        <label> ادرس اینترنتی : </label>
        <input type="text" class="form-control" name="slug" placeholder="ادرس اینترنتی دوره" required><br>

        <label> دسته بندی : </label>
        <select name="category" class="form-control">
            <?php foreach ($menus as $menu) { ?>
                <option value="<?php echo $menu['Id'] ?>"><?php echo $menu['Title']; ?></option>
            <?php } ?>
        </select><br>

        <label>وضعیت پرداخت</label><br>
        <label>رایگان</label>
        <input type="radio" name="pay" value="0" onclick="sum1()"><br>
        <label value="1">نقدی</label>
        <input type="radio" name="pay" value="1" onclick="sum()"><br>

        <script>
            function sum() {
                document.querySelector("#pay_div").style = "display: block";
            }
            function sum1() {
                document.querySelector("#pay_div").style = "display: none";
            }
        </script>

        <div id="pay_div">
            <label> قیمت دوره : </label>
            <input type="text" class="form-control" name="value" placeholder="قیمت دوره اموزشی"><br>
        </div>

        <style>
            #pay_div {
                display: none;
            }
        </style>

        <label> سطح دوره : </label>
        <select name="level" class="form-control">
            <option value="0">مقدماتی</option>
            <option value="1">متوسط</option>
            <option value="2">پیشرفته</option>
        </select><br>

        <label> برچسب های دوره : </label>
        <input type="text" class="form-control" name="tag" placeholder="برچسب های دوره اموزشی required"><br>

        <label>وضعیت دوره</label><br>
        <label>فعال</label>
        <input type="radio" name="status" value="1"><br>
        <label>غیرفعال</label>
        <input type="radio" name="status" value="0"><br><br>

        <input type="submit" name="sub" value="افزودن دوره" class="btn btn-primary">



    </form><br>
</div>

<div class="table">
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">عنوان</th>
                <th scope="col">عکس</th>
                <th scope="col">قیمت</th>
                <th scope="col">سطح دوره</th>
                <th scope="col">تاریخ بروزرسانی</th>
                <th scope="col">حذف ویرایش</th>


            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course) { ?>
                <tr>
                    <th scope="row"><?php echo $numberCourse++ ?></th>
                    <td><?php echo $course['title'] ?></td>
                    <td><img src="../../uploader/uploads/<?php echo $course['image']; ?>" width="150" style="border-radius: 5px; background-color: #ffffff; padding: 5px; box-shadow: 0px 0px 5px #cdd3dbdb;"></td>
                    <td><?php echo $course['value'] ?></td>

                    <td><?php if ($course['level'] == 0) {
                            echo "مقدماتی";
                        } elseif ($course['level'] == 1) {
                            echo "متوسط";
                        } else {
                            echo "پیشرفته";
                        } ?></td>

                    <td><?php echo Jdate(' s : i : H  ساعت || Y/n/j', $course['date']); ?></td>

                    <td>
                        <a href="coursemaneger.php?id=<?php echo $course['id']; ?>"><input type="submit" value="مدیریت دوره" class="btn btn-info"></a>
                        <a href="editcourse.php?id=<?php echo $course['id']; ?>"><input type="submit" value="ویرایش" class="btn btn-warning"></a>
                        <a href="deletecourse.php?id=<?php echo $course['id']; ?>"><input type="submit" value="حذف" class="btn btn-danger"></a>
                    </td>
                </tr>


            <?php } ?>
        </tbody>
    </table>
    </table>
</div><br>
</body>
<?php

include "footer.php";

?>