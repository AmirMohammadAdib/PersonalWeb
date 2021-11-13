<?php
include "header.php";

$id = $_GET['id'];

if (isset($_POST['sub'])) {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $level = $_POST['userlevel'];

    $update = $conn->prepare('UPDATE users SET UserName=? , Email=? , Password=? , UserLevel=? WHERE Id=?');
    $update->bindValue(1, $name);
    $update->bindValue(2, $email);
    $update->bindValue(3, $pass);
    $update->bindValue(4, $level);
    $update->bindValue(5, $id);

    $update->execute();
}

$user = $conn->prepare('SELECT * FROM users WHERE Id=?');
$user->bindValue(1, $id);
$user->execute();
$user = $user->fetch(PDO::FETCH_ASSOC);


?>


<div class="card-box">
    <form method="POST">
        <input type="text" name="username" placeholder="نام کاربری" class="form-control" value="<?php echo $user['UserName'] ?>" required><br>
        <input type="text" name="email" placeholder="ایمیل" class="form-control" value="<?php echo $user['Email'] ?>" required><br>
        <input type="text" class="form-control" name="password" placeholder="رمز عبور" value="<?php echo $user['Password'] ?>" required><br>

        <select name="userlevel" class="form-control">
            <option value="1" <?php if($user['UserLevel'] == 1){?> selected <?php } ?>>کاربر معمولی</option>
            <option value="2" <?php if($user['UserLevel'] == 2){?> selected <?php } ?>>مدرس</option>
            <option value="3" <?php if($user['UserLevel'] == 3){?> selected <?php } ?>>نویسنده</option>
            <option value="4" <?php if($user['UserLevel'] == 4){?> selected <?php } ?>>مدیر وبسایت</option>

        </select><br>
        <input type="submit" name="sub" value="ویرایش منو" class="btn btn-primary">
        <a href="menu.php" class="btn btn-warning">بازگشت</a>

    </form><br>


</div>
<?php include "footer.php" ?>


</body>

</html>