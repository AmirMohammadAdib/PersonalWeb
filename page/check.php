<?php

include "../database/db.php";
$successcheck = null;
$errorcheck = null;
if (isset($_POST['sub'])) {
    global $conn;
    $check_email = $_POST['check-email'];
    $gt = $conn->prepare('UPDATE users SET StatusLog=? WHERE Active=?');
    $gt->bindValue(1, 1);
    $gt->bindValue(2, $check_email);

    $gt->execute();

    $gt = $conn->prepare('SELECT * FROM users WHERE Active=?');
    $gt->bindValue(1, $check_email);
    $gt->execute();

    if ($gt->rowCount() >= 1) {
        $successcheck = true;
        $result = $gt->fetch(PDO::FETCH_ASSOC);
                    $_SESSION['login'] = true;
                    $_SESSION['id'] = $result['Id'];
                    $_SESSION['username'] = $result['UserName'];
                    $_SESSION['email'] = $result['Email'];
                    $_SESSION['password'] = $result['Password'];
                    $_SESSION['level'] = $result['UserLevel'];
                    $_SESSION['status'] = $result['StatusLog'];
        header('location: ../index.php?success=true');

    } elseif ($gt->rowCount() <= 0) {
        $errorcheck = true;
    }

}







?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style/login.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href='http://www.fontonline.ir/css/BYekan.css' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="style/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body>
    
<?php include "page/header.php"; ?>


    <!------ Include the above in your HEAD tag ---------->

    <br><br><div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="../image/businessman-launching-new-business-2937685-2426387.png" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <form method="POST">
                <input type="number" style="background-color: #f6f6f6;
    border: none;
    color: #0d0d0d;
    padding: 15px 32px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 5px;
    width: 78%;
    border: 2px solid #f6f6f6;
    -webkit-transition: all 0.5s ease-in-out;
    -moz-transition: all 0.5s ease-in-out;
    -ms-transition: all 0.5s ease-in-out;
    -o-transition: all 0.5s ease-in-out;
    transition: all 0.5s ease-in-out;
    -webkit-border-radius: 5px 5px 5px 5px;
    border-radius: 5px 5px 5px 5px;" id="login" class="fadeIn second" name="check-email" placeholder="کد ارسال شده رو بنویس">
                <input type="submit" class="fadeIn fourth" value="ثبت نام" name="sub" style="font-size: 16px;">
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="register.php?success=true">ایمیل رو اشتباه زدم</a>
            </div>

        </div>
    </div><br><br>
    <!-- footer -->
    <div class="footer ">

        <p class="txt-footer ">تمامی حقوق مادی و معنوی این وبسایت متعلق به تاپ لرن میباشد</p>
    </div>
    <!-- footer -->
</body>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js " integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy " crossorigin="anonymous "></script>


<?php if ($successcheck == true) {
?>

    <script>
        toastr.success('کد فعالسازی شما با موفقیت تایید شد', 'تبریک');
    </script>

<?php } ?>

<?php if ($errorcheck == true) {
?>

    <script>
        toastr.error(' کد نوشته شده اشتباه است', 'خطا');
    </script>

<?php } ?>
</html>