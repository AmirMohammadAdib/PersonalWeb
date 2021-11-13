<?php

include "../database/db.php";
$successmessag = null;
$errormessag = null;

if (isset($_POST['sub'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $gt = $conn->prepare('SELECT * FROM users WHERE Email=? AND Password=?');
    $gt->bindValue(1, $email);
    $gt->bindValue(2, $password);
    $gt->execute();

    if ($gt->rowCount() >= 1) {
        $successmessag = true;
        $result = $gt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['login'] = true;
        $_SESSION['id'] = $result['Id'];
        $_SESSION['username'] = $result['UserName'];
        $_SESSION['email'] = $result['Email'];
        $_SESSION['password'] = $result['Password'];
        $_SESSION['level'] = $result['UserLevel'];
        $_SESSION['status'] = $result['StatusLog'];

        header('location: ../index.php');
    } else if ($gt->rowCount() <= 0) {
        $errormessag = true;
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
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                    <span><a href="#" style="color: #ffffff; text-decoration: none;">ثبت نام</a></span><span style="color: #ffffff;
                    flex-direction: row-reverse;
                    /* font-weight: 300; */
                    font-family: hekaiyat;
                    font-size: 20px;
                "> / </span><span><a href="#" style="color: #ffffff; text-decoration: none;">ورود</a></span>
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
                <img src="../image/user-showing-user-login-page-in-website-or-application-1886527-1597938.png" id="icon" alt="User Icon" />
            </div>

            <!-- Login Form -->
            <!-- <div style="width: 100%;"> -->
            <form method="POST">
                <input type="email" id="login" class="fadeIn second" name="email" placeholder="ایمیلتو وارد کن">
                <input type="password" id="password" class="fadeIn third" name="password" placeholder="پسوورد">
                <input type="submit" class="fadeIn fourth" value="ورود" name="sub" style="font-size: 16px;">
            </form>
            <!-- </div> -->

            <!-- Remind Passowrd -->
            <div id="formFooter" style="display: flex; flex-direction: row-reverse; justify-content: space-between;">
                <a class="underlineHover" href="#" style="font-size: 15px;">نکنه رمزتو یادت رفته ؟؟؟</a>
                <a class="underlineHover" href="register.php" style="font-size: 15px;">!شایدم حساب نداری (یکی بساز)</a>

            </div>

        </div>
    </div>
    <!-- footer -->
    <div class="footer ">

        <p class="txt-footer ">تمامی حقوق مادی و معنوی این وبسایت متعلق به تاپ لرن میباشد</p>
    </div>
    <!-- footer -->
</body>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<?php if ($successmessag) { ?>
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
            title: 'ورود با موفقیت انجام شد'
        })
    </script>


<?php } else if ($errormessag) { ?>
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
            icon: 'error',
            title: 'ایمیل یا رمز عبور اشتباه است'
        })
    </script>

<?php } ?>






</html>