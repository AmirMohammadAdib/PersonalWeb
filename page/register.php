<?php
include "../database/db.php";

$errorfild = null;
$errorpass = null;
$successfild = null;
$haseemail = null;


if (isset($_POST['sub'])) {
    global $conn;
    $email = $_POST['email'];
    $user_name = $_POST['username'];
    $pass = $_POST['password'];
    $repass = $_POST['repass'];
    $active = rand(100000, 999999);


    if (!empty($_POST['email']) & !empty($_POST['username']) & !empty($_POST['password'])) {

            if ($pass === $repass) {
                $gt = $conn->prepare('SELECT * FROM users WHERE Email=?');
                $gt->bindValue(1, $email);
                $gt->execute();
                if ($gt->rowCount() >= 1) {
                    $haseemail = true;
                } elseif ($gt->rowCount() <= 0) {
                    $gt = $conn->prepare('INSERT INTO users SET Email=? , UserName=? , Password=? , Active=? , image=?');
                    $gt->bindValue(1, $email);
                    $gt->bindValue(2, $user_name);
                    $gt->bindValue(3, $pass);
                    $gt->bindValue(4, $active);
                    $gt->bindValue(5, rand(1, 3));

                    
                    header('location: ../index.php'); 

                    $gt->execute();

                }
            }else {
                $errorpass = true;
            }
    } else {
        $errorfild = true;
    }
};


// $gt = $gt->fetch(PDO::FETCH_ASSOC);


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

</head>

<body>
    <div class="header">
        <div class="back-header"></div>
        <div class="container" style="
        display: flex;
        align-items: baseline;
        flex-direction: row-reverse;
        justify-content: space-between;">
            <div class="row">
                <div class="row-right-header">
                    <div class="logo">
                        <!-- <img src="image/logo.svg" class="img-header"> -->
                        <p class="logo-txt">TopLearn</p>
                    </div>
                    <div class="window" onclick="sum()">
                        <div class="a"></div>
                        <div class="b"></div>
                        <div class="c"></div>

                    </div>
                    <div class="culomn-header">
                        <ul class="nav-item dropdown ul-header">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    گرافیک
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    وبلاگ
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </li>

                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    طراحی وب
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </li>


                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    برنامه نویسی
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="#">Action</a>
                                    <a class="dropdown-item" href="#">Another action</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#">Something else here</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row-left-header">
                <span>
                    <span><a href="#" style="color: #ffffff; text-decoration: none; font-size: 14px;">ثبت نام</a></span><span style="color: #ffffff;
                    flex-direction: row-reverse;
                    /* font-weight: 300; */
                    font-family: hekaiyat;
                    font-size: 14px;
                "> / </span><span><a href="#" style="color: #ffffff; text-decoration: none; font-size: 14px;">ورود</a></span>
                </span>

                <span><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person icon-user-header" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                    </svg></span>


            </div>
        </div>
    </div>

    <!------ Include the above in your HEAD tag ---------->

    <div class="wrapper fadeInDown">
        <div id="formContent">
            <!-- Tabs Titles -->

            <!-- Icon -->
            <div class="fadeIn first">
                <img src="../image/profile-details-3148751-2624928.png" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <form method="POST">
                <input type="email" id="login" class="fadeIn second" name="email" placeholder="ایمیلتو وارد کن">
                <input type="text" id="password" class="fadeIn third" name="username" placeholder="نام کاربری">
                <input type="password" id="login" class="fadeIn second" name="password" placeholder="پسورد">
                <input type="password" id="password" class="fadeIn third" name="repass" placeholder="یه بار دیگه بزن پسوردتو">
                <input type="submit" class="fadeIn fourth" value="ثبت" name="sub" style="font-size: 16px;">
            </form>

            <!-- Remind Passowrd -->
            <div id="formFooter">
                <a class="underlineHover" href="login.php">قبلنا حساب نساختی ؟</a>
            </div>

        </div>
    </div>
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

<?php if ($errorfild) { ?>
    <script>
        toastr.error('فیلد های مورد نظر رو پر کن', 'خطا');
    </script>

<?php } ?>

<?php if ($errorpass) { ?>
    <script>
        toastr.warning('پسورد ها یکسان نیست', 'خطا');
    </script>

<?php } ?>

<?php if ($successfild) { ?>

    <script>
        toastr.success('ثبت نام با موفقیت انجام شد', 'تبریک');
    </script>

<?php } ?>


<?php if ($haseemail) { ?>

    <script>
        toastr.error('کاربری با این مشخصات در سایت وجود دارد', 'خطا');
    </script>


<?php } ?>


</html>