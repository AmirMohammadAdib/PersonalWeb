<?php
include "../database/db.php";
include "../script/jdf.php";

$number_part = 1;

$add_course = null;
$error_course = null;
$success_comment = null;
$error_comment = null;


$img_profile = rand(1, 3);

$menus = $conn->prepare('SELECT * FROM menus ORDER BY Sort');
$menus->execute();
$menus = $menus->fetchAll(PDO::FETCH_ASSOC);


$courses = $conn->prepare('SELECT * FROM course ORDER BY date');
$courses->execute();
$courses = $courses->fetchAll(PDO::FETCH_ASSOC);

$id = $_GET['id'];

$courses = $conn->prepare('SELECT * FROM course WHERE id=?');
$courses->bindValue(1, $id);
$courses->execute();
$courses = $courses->fetchAll(PDO::FETCH_ASSOC);



if (isset($_POST['btn'])) {

    $course_store = $conn->prepare('SELECT * FROM store WHERE user_id=? AND course_id=?');
    $course_store->bindValue(1, $_SESSION['id']);
    foreach ($courses as $course) {
        $course_store->bindValue(2, $course['id']);
    }
    $course_store->execute();


    if ($course_store->rowCount() >= 1) {
        $error_course = true;
    } else {
        $course_store = $conn->prepare('INSERT INTO store SET user_id=? , course_id=?');
        $course_store->bindValue(1, $_SESSION['id']);
        foreach ($courses as $course) {
            $course_store->bindValue(2, $course['id']);
        }
        $course_store->execute();
        $add_course = true;
    }
}


if (isset($_SESSION['login'])) {
    $courses_status = $conn->prepare('SELECT * FROM store WHERE course_id=? && user_id=?');
    $courses_status->bindValue(1, $id);
    $courses_status->bindValue(2, $_SESSION['id']);
    $courses_status->execute();
    $courses_status = $courses_status->fetch(PDO::FETCH_ASSOC);
}


$course_parts = $conn->prepare('SELECT * FROM part WHERE course=?');
$course_parts->bindValue(1, $id);
$course_parts->execute();
$course_parts = $course_parts->fetchAll(PDO::FETCH_ASSOC);


$part_pays = $conn->prepare('SELECT * FROM store WHERE course_id=?');
$part_pays->bindValue(1, $id);
$part_pays->execute();
$part_pays = $part_pays->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['login'])) {

    $a = $conn->prepare('SELECT * FROM store WHERE status=1 && course_id=? && user_id=?');
    $a->bindValue(1, $id);
    $a->bindValue(2, $_SESSION['id']);
    $a->execute();
    $a = $a->fetch(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['login'])) {
    $image_comment = $conn->prepare('SELECT image FROM comment WHERE sender=?');
    $image_comment->bindValue(1, $_SESSION['username']);
    $image_comment->execute();
    $image_comment = $image_comment->fetchAll(PDO::FETCH_ASSOC);
}


if (isset($_SESSION['login'])) {

    if (isset($_POST['sub_comm'])) {
        $comment = $_POST['caption'];
        $comments = $conn->prepare('INSERT INTO comment SET sender=? , content=? , date=? , replay=0 , course=? , image=?');
        $comments->bindValue(1, $_SESSION['username']);
        $comments->bindValue(2, $comment);
        $comments->bindValue(3, time());
        $comments->bindValue(4, $id);
        $comments->bindValue(5, rand(1, 3));

        $comments->execute();
        $success_comment = true;
    }
} elseif (!isset($_SESSION['login'])) {
    $error_comment = true;
}

$select_comments = $conn->prepare('SELECT * FROM comment');
$select_comments->execute();
$select_comments = $select_comments->fetchAll(PDO::FETCH_ASSOC);

if (isset($_SESSION['login'])) {

    $user = $conn->prepare('SELECT * FROM users WHERE Id=?');
    $user->bindValue(1, $_SESSION['id']);
    $user->execute();
    $user = $user->fetchAll(PDO::FETCH_ASSOC);
}


?>
<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="../css/style.css">
    <!-- <link rel="stylesheet" href="sweetalert2.min.css"> -->
    <!-- <link rel="stylesheet" href="../css/sweetalert2.min.css"> -->
    <link href='http://www.fontonline.ir/css/BYekan.css' rel='stylesheet' type='text/css'>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




</head>
<style>
    @font-face {
        font-family: vazir;
        src: url(../../../xampp/htdocs/storeWeb/fonts/Vazir-Medium-FD-WOL.ttf);
    }

    .box {
        display: flex;
        flex-direction: row-reverse;
        align-items: baseline;
        border: 1px dashed;
        border-color: #a9b7c4;
        border-radius: 5px;
        padding: 5px 15px;
        width: 100%;
        justify-content: space-between;
    }

    .left {
        display: flex;
        flex-direction: row-reverse;
        align-items: baseline;
    }

    .right {
        display: flex;
        flex-direction: row-reverse;
        align-items: baseline;
        margin-top: 10px;
    }

    .download svg {
        width: 19px;
        opacity: 0.9;
        color: rgb(32, 190, 0);
        height: 20 px;
        transition: 0.4s;
    }

    .download {
        width: 35px;
        height: 35px;
        background-color: rgb(255, 255, 255);
        border: 1px solid rgb(32, 190, 0);
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        margin-right: 12px;
        position: relative;
        top: 4px;
    }

    .download:hover {
        background-color: rgb(32, 190, 0);
        opacity: 0.5;
    }

    .download svg:hover {
        color: #ffffff;
    }

    .time p {
        color: #abb7be;
        font-family: 'vazir';
        font-size: 13px;
    }

    .titre p {
        font-family: vazir;
        color: #333;
        opacity: 0.8;
        margin-right: 12px;
        font-size: 15px;
        text-align: right;
    }

    .number_part {
        border: 3px solid;
        border-color: rgba(201, 207, 220, 0.588);
        border-radius: 50px;
        width: 28px;
        height: 28px;
        align-items: center;
        justify-content: center;
        background-color: #ffffff;
        display: flex;
        margin: 0px -30px 0px 0px;
    }

    .hr {
        width: 1px;
        height: 25px;
        background-color: #9aa0a6;
        position: relative;
        top: 7px;
        margin: 0px 13px;
        opacity: 0.2;
    }

    .status p {
        font-size: 14px;
        font-family: 'vazir';
        color: rgb(32, 190, 0);
        opacity: 0.7;
        margin-right: 13px;
    }

    .play {
        width: 30px;
        height: 30px;
        background-color: rgb(255, 255, 255);
        border: 1px solid rgb(0, 190, 184);
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        margin-right: 12px;
        position: relative;
        top: 4px;
        cursor: pointer;
    }

    .play svg {
        width: 19px;
        opacity: 0.9;
        color: rgb(0, 190, 184);
        /* height: 20px; */
        transition: 0.4s;
    }

    .play:hover {
        background-color: rgb(0, 190, 184);
        opacity: 0.5;
    }

    .play svg:hover {
        color: #ffffff;
    }

    .securite svg {
        opacity: 0.5;
        color: rgb(0, 190, 184);
    }

    .icon-download {
        opacity: 0.3;
        margin-right: 10px;
        position: relative;
        top: 2px;
    }
</style>

<body>

    <header style="display: contents;">
        <div class="header">
            <div class="container" style="
                display: flex;
                align-items: center;
                flex-direction: row-reverse;
                justify-content: space-between;">


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
                            <?php foreach ($menus as $menu) {
                                if ($menu['Z'] == 0) { ?>
                                    <li class="nav-item dropdown">
                                        <a href="<?php echo $menu['Src'] ?>" class="nav-link <?php foreach ($menus as $z) {
                                                                                                    if ($menu['Id'] == $z['Z']) {  ?> dropdown-toggle <?php }
                                                                                                                                                }; ?> " id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                            <?php echo $menu['Title']; ?>
                                        </a>
                                        <?php foreach ($menus as $li) {
                                            if ($menu['Id'] == $li['Z']) { ?>
                                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                    <?php foreach ($menus as $li) {
                                                        if ($menu['Id'] == $li['Z']) { ?>
                                                            <a class="dropdown-item" href="<?php echo $li['Src'] ?>">
                                                                <?php echo $li['Title'] ?>
                                                            </a>
                                                    <?php };
                                                    }; ?>

                                                </div>
                                        <?php }
                                        } ?>
                                    </li>
                            <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
                <?php
                if (isset($_SESSION['login'])) {
                ?>

                    <?php if ($_SESSION['level'] == 2 or $_SESSION['level'] == 3 or $_SESSION['level'] == 4) { ?>
                        <div class="row-left-header" style="display: flex; align-items: center;">
                            <div class="profile">
                                <img src="../image/profile-img/<?php echo rand(1, 3) . '.png'; ?>">
                                <p>
                                    <?php echo $_SESSION['username'] ?>
                                </p><span><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16" id="icon-username">
                                        <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                                    </svg></span>
                                <div class="box-profile">
                                    <div class="profile-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                        </svg>
                                        <h6>مشاهده حساب کاربری</h6>
                                    </div>
                                    <div class="profile-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                            <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                                        </svg>
                                        <h6>ویرایش حساب کاربری</h6>
                                    </div>
                                    <div class="profile-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                                            <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                                        </svg>
                                        <a href="../basket-store/basket.php">
                                            <h6>سبد خرید</h6>
                                        </a>
                                    </div>
                                    <div class="profile-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                            <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z" />
                                        </svg>
                                        <h6>کیف پول من</h6>
                                    </div>
                                    <div class="profile-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                                        </svg>
                                        <h6>
                                            <a href="page/loginAdmin.php" style="color: #ffffff; text-decoration: none;"><?php if ($_SESSION['level'] == 2) {
                                                                                                                                echo "پنل نویسندگان";
                                                                                                                            } elseif ($_SESSION['level'] == 3) {
                                                                                                                                echo "پنل مدرسین";
                                                                                                                            } elseif ($_SESSION['level'] == 4) {
                                                                                                                                echo "پنل مدیریت";
                                                                                                                            } ?></a>
                                        </h6>
                                    </div>

                                    <div class="profile-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                            <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                        </svg>
                                        <a href="page/logout.php">
                                            <h6>
                                                <?php echo "خروج از حساب کاربری" ?>
                                            </h6>
                                        </a>
                                    </div>
                                <?php } else { ?>

                                    <div class="row-left-header">
                                        <div class="profile">

                                            <?php foreach ($user as $u) { ?> <img src="../image/profile-img/<?php echo $u['image'] . '.png'; ?>"><?php } ?>
                                            <p>
                                                <?php echo $_SESSION['username'] ?>
                                            </p><span><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16" id="icon-username">
                                                    <path fill-rule="evenodd" d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                                                </svg></span>
                                            <div class="box-profile">
                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                                    </svg>
                                                    <h6>مشاهده حساب کاربری</h6>
                                                </div>
                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                        <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                                                    </svg>
                                                    <h6>ویرایش حساب کاربری</h6>
                                                </div>
                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                                                        <path d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                                                    </svg>
                                                    <a href="../basket-store/basket.php">
                                                        <h6>سبد خرید</h6>
                                                    </a>
                                                </div>
                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                                        <path d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z" />
                                                    </svg>
                                                    <h6>کیف پول من</h6>
                                                </div>
                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-folder-plus" viewBox="0 0 16 16">
                                                        <path d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z" />
                                                        <path d="M13.5 10a.5.5 0 0 1 .5.5V12h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V13h-1.5a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z" />
                                                    </svg>
                                                    <h6>فاکتور های من</h6>
                                                </div>
                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                                                        <path d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                                                    </svg>
                                                    <h6>
                                                        <a style="color: #ffffff; text-decoration: none;" href="../page/my-course.php">ویدئو های من</a>
                                                    </h6>
                                                </div>

                                                <div class="profile-item">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                                    </svg>
                                                    <a href="../page/logout.php">
                                                        <h6>
                                                            <?php echo "خروج از حساب کاربری" ?>
                                                        </h6>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php }; ?>

                                        </div>



                                    </div>

                                <?php } else { ?>

                                    <div class="row-left-header">
                                        <span>
                                            <span><a href="page/register.php" style="color: #ffffff; text-decoration: none; font-size: 20px;">ثبت نام</a></span><span style="color: #ffffff;
                    flex-direction: row-reverse;
                    /* font-weight: 300; */
                    font-family: hekaiyat;
                    font-size: 14px;
                "> / </span><span><a href="page/login.php" style="color: #ffffff; text-decoration: none; font-size: 20px;">ورود</a></span>
                                        </span>

                                        <span><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-person icon-user-header" viewBox="0 0 16 16">
                                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                            </svg></span>


                                    </div>

                                <?php } ?>
                                </div>
                            </div>




                            <div class="back-side ">

                            </div>
    </header><br><br>

    <div class="container">
        <div class="img-poster">
            <img src="../image/4057b38e-da55-41c7-b858-ce6d54ca1c06بنرتاپلرن 2.png" style="border-radius: 10px; width: 100%;"><br><br>
        </div>
    </div>



    <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
    <!-- content -->
    <!-- ////////////////////////////////////////////////////////////////////////////////////////////////////////// -->

    <div class="container">
        <?php foreach ($courses as $course) { ?>
            <div class="content-blog-singlepage ">
                <div class="one-row-content ">

                    <div class="row-right-titre ">
                        <div class="titre-blog-singlepage ">
                            <h1>
                                <?php echo $course['title'] ?>
                            </h1>
                        </div>
                        <div class="down-titre-singlepage ">
                            <p>تاپ لرن / دوره های اموزشی /طراحی وب /اموزش فرانت اند</p>
                        </div>
                    </div>

                    <div class="row-left-titre ">
                        <div class="link-short ">
                            <div class="icon-cpoy ">
                                <svg xmlns="http://www.w3.org/2000/svg " width="27 " height="27 " fill="currentColor " class="bi bi-files icon-copy " viewBox="0 0 16 16 " onclick="clickCopy() ">
                                    <path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zm0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1zM3 4a1 1 0 0 1 1-1h7a1 1 0 0 1
        1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V4z " />
                                </svg>
                            </div>
                            <div class="line-likn-short ">
                            </div>
                            <div class="link " style="display: flex; ">
                                <script>
                                    function clickCopy() {
                                        let myVar = "https://toplearn.com/c/4729 "
                                        navigator.clipboard.writeText(myVar);

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
                                            title: 'لینک با موفقیت کپی شد'
                                        })

                                    }
                                </script>
                                <p class="id-link">https://toplearn.com/c/4729</p><span>
                                    <p class="link-text">لینک کوتاه</p>
                                </span>
                            </div>
                        </div>
                    </div>
                </div><br>
                <div class="poster-gif-up ">
                    <img src="../image/takhfif-banner.gif " style="width: 100%; ">
                </div>
            </div><br>


            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
            <!-- /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->


            <div class="all-row-page" style="display: flex;">
                <div class="left-page-single">
                    <div class="left-row-page">
                        <div class="content-blog-page ">
                            <div class="img-page-blog ">
                                <img src="../uploader/uploads/<?php echo $course['image'] ?>">
                            </div>
                            <div class="text-page ">
                                <div class="titre-page-blog ">
                                    <h1>
                                        <?php echo $course['title']; ?>
                                    </h1>
                                </div>
                                <div class="caption-page-blog ">
                                    <p><?php echo $course['content']; ?>
                                    </p>
                                </div><br>
                                <div class="question-post ">
                                    <p>لطفا سوالات خود را راجع به این آموزش در این بخش پرسش و پاسخ مطرح کنید به سوالات در قسمت نظرات پاسخ داده نخواهد شد و آن نظر حذف میشود</p>
                                </div>
                            </div>
                        </div><br>
                        <div class="network-poster ">
                            <img src="../image/dd234b4e-65d3-4b7c-9c1e-ae6d8bc16d8eTelegramBanner.png " class="tel-poster ">
                            <img src="../image/bebcd3fa-2de7-454f-9abc-bb7965fdcbfaInstagramBanner.png " class="insta-poster ">
                        </div>
                    </div><br>
                    <div class="number-video ">
                        <div class="header-play-video ">
                            <div class="right-header-play ">
                                <svg xmlns="http://www.w3.org/2000/svg " width="23 " height="23 " fill="currentColor " class="bi bi-file-earmark-play " viewBox="0 0 16 16 ">
                                    <path d="M6 6.883v4.234a.5.5 0 0 0 .757.429l3.528-2.117a.5.5 0 0 0 0-.858L6.757 6.454a.5.5 0 0 0-.757.43z " />
                                    <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z " />
                                </svg>
                                <b>فهرستـــ ویدیوها</b>
                            </div>
                            <div class="left-header-play ">
                                <svg xmlns="http://www.w3.org/2000/svg " width="23 " height="23 " fill="currentColor " class="bi bi-alarm " viewBox="0 0 16 16 ">
                                    <path d="M8.5 5.5a.5.5 0 0 0-1 0v3.362l-1.429 2.38a.5.5 0 1 0 .858.515l1.5-2.5A.5.5 0 0 0 8.5 9V5.5z " />
                                    <path d="M6.5 0a.5.5 0 0 0 0 1H7v1.07a7.001 7.001 0 0 0-3.273 12.474l-.602.602a.5.5 0 0 0 .707.708l.746-.746A6.97 6.97 0 0 0 8 16a6.97 6.97 0 0 0 3.422-.892l.746.746a.5.5 0 0 0 .707-.708l-.601-.602A7.001 7.001 0 0 0 9 2.07V1h.5a.5.5
            0 0 0 0-1h-3zm1.038 3.018a6.093 6.093 0 0 1 .924 0 6 6 0 1 1-.924 0zM0 3.5c0 .753.333 1.429.86 1.887A8.035 8.035 0 0 1 4.387 1.86 2.5 2.5 0 0 0 0 3.5zM13.5 1c-.753 0-1.429.333-1.887.86a8.035 8.035 0 0 1 3.527 3.527A2.5 2.5 0 0 0 13.5 1z " />
                                </svg>
                                <p> مدت زمان دوره <span> 4 : 20 : 31 </span> </p>
                            </div>
                        </div><br>
                        <div class="video">
                            <video src="../image/Prison.Break.S01E01.720p.Farsi.Dubbed.HexDL.com_2.mkv" id="video_item" controls>
                                <!-- <source src="video.mp4" type="video/mp4"> -->
                            </video>
                        </div><br>
                        <div class="error-video-play ">
                            <div class="error-one ">
                                <p>در صورتیکه ویدئو نمایش داده نشد آن را دانلود کنید و با KmPlayer مشاهده کنید
                                </p>
                            </div><br>
                            <div class="error-two ">
                                <p>
                                    لطفا در صورت اقدام به دانلود تا انتها فرایند دانلود ، این صفحه را باز نگاه دارید.
                                </p>
                            </div><br>
                            <div class="error-one ">
                                <p>راهنما ! جهت دریافت لینک دانلود تمامی قسمت ها بر روی این لینک .کلیک کنید.
                                </p>
                            </div><br>
                        </div>
                        <?php foreach ($course_parts as $course_part) { ?>
                            <?php if ($course_part['course'] == $id) { ?>
                                <div class="box">
                                    <div class="left">
                                        <div class="number_part">
                                            <?php echo $number_part++; ?>
                                        </div>
                                        <div class="titre" style="padding: 0;">
                                            <p><?php echo $course_part['title'] ?></p>
                                        </div>
                                    </div>
                                    <div class="right">
                                        <?php if ($course['value'] != 0) { ?>
                                            <?php if (isset($_SESSION['login'])) { ?>
                                                <?php if ($courses_status['status'] == 1 && $courses_status['user_id'] == $_SESSION['id']) { ?>
                                                    <div class="free_part" style="display: flex; flex-direction: row-reverse; align-items: baseline;">
                                                        <a href="#video_item">
                                                            <div class="play" id="part_video" link="<?php echo $course_part['link']; ?>">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play" viewBox="0 0 16 16">
                                                                    <path d="M10.804 8 5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z" />
                                                                </svg>
                                                            </div>
                                                        </a>
                                                        <div class="status">
                                                            <p><?php if ($course_part['status'] == "رایگان") {
                                                                    echo "رایگان";
                                                                } elseif ($course_part['status'] == "نقدی") {
                                                                    echo "نقدی";
                                                                } ?></p>
                                                        </div>
                                                        <div class="hr"></div>

                                                        <div class="time">
                                                            <p><?php echo $course_part['time']; ?></p>
                                                        </div>
                                                        <a href="../uploader/part_video/<?php echo $course_part['link'] ?>">
                                                            <div class="download">
                                                                <svg xmlns="http://www.w3.org/2000/svg " width="16 " height="16 " fill="currentColor " class="bi bi-download " viewBox="0 0 16 16 ">
                                                                    <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z "></path>
                                                                    <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z "></path>
                                                                </svg>
                                                            </div>
                                                        </a>
                                                    </div>
                                                <?php
                                                } elseif (isset($_SESSION['login'])) { ?>

                                                    <div class="pay_part" style="display: flex; flex-direction: row-reverse; align-items: baseline;">
                                                        <div class="securite">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                                                <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
                                                                <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z" />
                                                            </svg>
                                                        </div>
                                                        </svg>

                                                        <div class="hr"></div>
                                                        <div class="status">
                                                            <p style="font-size: 15px; font-family: 'hekaiyat'; color: black; opacity: 0.5;">ابتدا دوره رو خریداری کنید</p>
                                                        </div>
                                                        <div class="hr"></div>

                                                        <img src="../image/direct-download.png" class="icon-download">
                                                        </svg>
                                                    </div>

                                                <?php }
                                            } elseif (!isset($_SESSION['login'])) { ?>
                                                <div class="pay_part" style="display: flex; flex-direction: row-reverse; align-items: baseline; margin-top: -5px;">
                                                    <div class="securite">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                                                            <path d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
                                                            <path d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z" />
                                                        </svg>
                                                    </div>
                                                    </svg>

                                                    <div class="hr"></div>
                                                    <div class="status">
                                                        <p style="font-size: 15px; font-family: 'hekaiyat'; color: black; opacity: 0.5;">ابتدا وارد شوید</p>
                                                    </div>
                                                    <div class="hr"></div>

                                                    <img src="../image/direct-download.png" class="icon-download">
                                                    </svg>
                                                </div>
                                            <?php }
                                        } else { ?>
                                            <div class="free_part" style="display: flex; flex-direction: row-reverse; align-items: baseline;">
                                                <a href="#video_item">
                                                    <div class="play" id="part_video" link="<?php echo $course_part['link']; ?>">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play" viewBox="0 0 16 16">
                                                            <path d="M10.804 8 5 4.633v6.734L10.804 8zm.792-.696a.802.802 0 0 1 0 1.392l-6.363 3.692C4.713 12.69 4 12.345 4 11.692V4.308c0-.653.713-.998 1.233-.696l6.363 3.692z" />
                                                        </svg>
                                                    </div>
                                                </a>
                                                <div class="status">
                                                    <p><?php if ($course_part['status'] == "رایگان") {
                                                            echo "رایگان";
                                                        } elseif ($course_part['status'] == "نقدی") {
                                                            echo "نقدی";
                                                        } ?></p>
                                                </div>
                                                <div class="hr"></div>

                                                <div class="time">
                                                    <p><?php echo $course_part['time']; ?></p>
                                                </div>
                                                <a href="../uploader/part_video/<?php echo $course_part['link'] ?>">
                                                    <div class="download">
                                                        <svg xmlns="http://www.w3.org/2000/svg " width="16 " height="16 " fill="currentColor " class="bi bi-download " viewBox="0 0 16 16 ">
                                                            <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z "></path>
                                                            <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z "></path>
                                                        </svg>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div><br>
                        <?php }
                        } ?>
                        <script>
                            $(document).on('click', '#part_video', function() {
                                var link = $(this).attr('link');
                                $("#video_item").attr('src', "../uploader/part_video/" + link);
                            })
                        </script>
                        <div class="comments">
                            <div class="titre-comment ">
                                <svg xmlns="http://www.w3.org/2000/svg " width="35 " height="35 " fill="currentColor " class="bi bi-card-heading " viewBox="0 0 16 16 ">
                                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z " />
                                    <path d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1z " />
                                </svg>
                                <b>نظرات کاربران در رابطه با این دوره
                                </b>
                            </div><br>
                            <div>
                                <p style=" text-align: right; background-color: rgb(160, 213, 153); padding: 2%; border-radius: 5px; color: grreen; color: rgb(30, 71, 17); opacity: 0.9; ">لطفا سوالات خود را راجع به این آموزش در این بخش پرسش و پاسخ مطرح کنید به سوالات در قسمت نظرات پاسخ داده نخواهد شد و آن نظر حذف میشود.</p>
                            </div>
                            <div class="textarya ">
                                <form method="post">

                                    <textarea name="caption" class="editor" id="my-editor" cols="30" rows="10"></textarea><br>
                                    <script src="../ckeditor5/build/ckeditor.js"></script>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('.editor'), {

                                                toolbar: {
                                                    items: [
                                                        'heading',
                                                        '|',
                                                        'bold',
                                                        'italic',
                                                        'link',
                                                        'bulletedList',
                                                        'numberedList',
                                                        '|',
                                                        'outdent',
                                                        'indent',
                                                        '|',
                                                        'imageUpload',
                                                        'blockQuote',
                                                        'insertTable',
                                                        'mediaEmbed',
                                                        'undo',
                                                        'redo',
                                                        'code',
                                                        'codeBlock',
                                                        'fontBackgroundColor',
                                                        'fontColor',
                                                        'fontSize',
                                                        'highlight'
                                                    ]
                                                },
                                                language: 'fa',
                                                image: {
                                                    toolbar: [
                                                        'imageTextAlternative',
                                                        'imageStyle:full',
                                                        'imageStyle:side',
                                                        'linkImage'
                                                    ]
                                                },
                                                table: {
                                                    contentToolbar: [
                                                        'tableColumn',
                                                        'tableRow',
                                                        'mergeTableCells'
                                                    ]
                                                },
                                                licenseKey: '',

                                            })
                                            .then(editor => {
                                                window.editor = editor;

                                            })
                                            .catch(error => {
                                                console.error('Oops, something went wrong!');
                                                console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
                                                console.warn('Build id: fotmady28o1t-fx1wlfayz8ed');
                                                console.error(error);
                                            });
                                    </script>

                                    <p>
                                    </p>

                                    <input type="submit" value="ثبت دیدگاه" class="btn" name="sub_comm" style="background-color: #6fc341; color: #ffffff; padding-left: 120px; padding-right: 120px; margin-top: -20px;">
                                </form>


                            </div><br>
                            <?php foreach ($select_comments as $select_comment) { ?>
                                <?php if ($select_comment['course'] == $id) { ?>
                                    <div class="box-comment">
                                        <div class="img-comment">

                                            <img src="../image/profile-img/<?php echo $select_comment['image'] . '.png'; ?>">
                                        </div>
                                        <div class="text-comment ">
                                            <div class="header-box-text-comment ">
                                                <div class="username-box-comment ">
                                                    <p><?php echo $select_comment['sender']; ?></p>
                                                </div>
                                                <div class="line-name-comment "></div>
                                                <div class="get-date-comment "> ارسال شده در <span> <?php echo jdate("Y/n/d", $select_comment['date']) ?> </span></div>
                                                <div class="btns-comment ">
                                                    <button class="btn" style="background-color: #71c55e; color: #ffffff; padding: 0px; padding-left: 5px; padding-right: 5px;">ثبت پاسخ</button>
                                                    <button class="btn" style="background-color: #daa520; color: #ffffff; padding: 0px; padding-left: 5px; padding-right: 5px;">گزارش</button>
                                                </div>
                                            </div>

                                            <div class="content-comment ">
                                                <p><?php echo $select_comment['content']; ?></p>
                                            </div>
                                            <div class="date-down">
                                                <p>
                                                    6 / 2 / 1399
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            } ?>
                        </div>
                    </div>


                </div>


                <div class="right-row-page ">
                    <div class="up-poster-searche">
                        <img src="../image/7ef1bbdb-6ef1-4ef8-80bc-ee78d1d8482esearch.png" style="width: 100%;">
                    </div><br>
                    <div style="top: 15px; position: sticky; width: 100%;">
                        <div class="comtion-post">
                            <div class="header-monye">
                                <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-currency-dollar" viewBox="0 0 16 16">
                                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z" />
                                </svg>
                                <?php if ($course['value'] == 0) { ?>
                                    <p style="
    font-size: 20px;
    font-family: 'vazir';
    color: #8ade4e;
    opacity: 0.8;
">رایگانــ</p>
                                <?php
                                } else { ?>
                                    <p> : قیمت این دوره</p><span> <?php echo $course['value']; ?> </span></p>

                                <?php } ?>
                            </div>
                            <hr style="margin-top: -10px;">
                            <div class="item-comition">
                                <div class="item-to">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                    <p> : مدرس دوره</p><span> امیر محمد ادیب</span>
                                </div>
                            </div>
                            <div class="item-comition">
                                <div class="item-to">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor" class="bi bi-camera-reels-fill" viewBox="0 0 16 16">
                                        <path d="M6 3a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                        <path d="M9 6a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                                        <path d="M9 6h.5a2 2 0 0 1 1.983 1.738l3.11-1.382A1 1 0 0 1 16 7.269v7.462a1 1 0 0 1-1.406.913l-3.111-1.382A2 2 0 0 1 9.5 16H2a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h7z" />
                                    </svg>
                                    <p> : تعداد ویدئو ها</p><span style="margin-top: -19px;"><span>۶۱</span>ویدئو</span>
                                </div>
                            </div>
                            <div class="item-comition">
                                <div class="item-to">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-clock" viewBox="0 0 16 16">
                                        <path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z" />
                                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z" />
                                    </svg>
                                    <p> : مدت زمان دوره </p><span> 21 : 12 : 1400 </span>
                                </div>
                            </div>

                            <div class="item-comition">
                                <div class="item-to">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-layers-fill" viewBox="0 0 16 16">
                                        <path d="M7.765 1.559a.5.5 0 0 1 .47 0l7.5 4a.5.5 0 0 1 0 .882l-7.5 4a.5.5 0 0 1-.47 0l-7.5-4a.5.5 0 0 1 0-.882l7.5-4z" />
                                        <path d="m2.125 8.567-1.86.992a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882l-1.86-.992-5.17 2.756a1.5 1.5 0 0 1-1.41 0l-5.17-2.756z" />
                                    </svg>
                                    <p> : سطح دوره </p><span> <?php if ($course['level'] == 0) {
                                                                    echo "مقدماتی";
                                                                } elseif ($course['level'] == 1) {
                                                                    echo "متوسط";
                                                                } else {
                                                                    echo "پیشرفته";
                                                                } ?> </span>
                                </div>
                            </div>

                            <div class="item-comition">
                                <div class="item-to">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-broadcast" viewBox="0 0 16 16">
                                        <path d="M3.05 3.05a7 7 0 0 0 0 9.9.5.5 0 0 1-.707.707 8 8 0 0 1 0-11.314.5.5 0 0 1 .707.707zm2.122 2.122a4 4 0 0 0 0 5.656.5.5 0 1 1-.708.708 5 5 0 0 1 0-7.072.5.5 0 0 1 .708.708zm5.656-.708a.5.5 0 0 1 .708 0 5 5 0 0 1 0 7.072.5.5 0 1 1-.708-.708 4 4 0 0 0 0-5.656.5.5 0 0 1 0-.708zm2.122-2.12a.5.5 0 0 1 .707 0 8 8 0 0 1 0 11.313.5.5 0 0 1-.707-.707 7 7 0 0 0 0-9.9.5.5 0 0 1 0-.707zM10 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0z" />
                                    </svg>
                                    <p> : وضعیت دوره </p><span style="color: #40c3ee;"> <?php if ($course['status'] == 1) {
                                                                                            echo "به اتمام رسیده";
                                                                                        } elseif ($course['status'] == 0) {
                                                                                            echo "درحال برگزاری";
                                                                                        } ?> </span>
                                </div>
                            </div>

                            <div class="item-comition">
                                <div class="item-to">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-calendar-week" viewBox="0 0 16 16">
                                        <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                                        <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                    </svg>
                                    <p> : تاریخ آخرین بروزرسانی </p><span> <?php echo Jdate('Y/n/j', $course['date']); ?> </span>
                                </div>
                            </div>
                            <div>

                                <?php if ($course['value'] != 0) { ?>
                                    <?php if (isset($_SESSION['login']) && $courses_status['status'] == 1 && $courses_status['user_id'] == $_SESSION['id']) { ?>
                                        <input type="submit" class="btn" value="شما دانشجوی این دوره هستید" style="width: 100%; background-color: #ffffff; color: #71c55e; border: 2px solid #71c55e; font-family: 'vazir';">

                                    <?php } elseif (isset($_SESSION['login'])) { ?>
                                        <form method="POST"><input type="submit" name="btn" class="btn" value="ثبت نام در دوره" style="width: 100%; background-color: #71c55e; color: #ffffff;"></form>

                                    <?php } else { ?>
                                        <a href="../page/register.php"><input type="submit" name="btn" class="btn" value="ثبت نام در دوره" style="width: 100%; background-color: #71c55e; color: #ffffff;"></a>
                                    <?php }
                                } elseif ($course['value'] == 0) { ?>
                                    <input type="submit" class="btn" value="این دوره رایگانه" style="width: 100%; background-color: #ffffff; color: #71c55e; border: 2px solid #71c55e; font-family: 'vazir';">
                                <?php } ?>
                            </div>
                        </div><br>
                        <div class="item-network">
                            <p>به اشتراک گذاری</p>
                            <div class="icon-networks">
                                <div class="icon-telegram">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-telegram" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8.287 5.906c-.778.324-2.334.994-4.666 2.01-.378.15-.577.298-.595.442-.03.243.275.339.69.47l.175.055c.408.133.958.288 1.243.294.26.006.549-.1.868-.32 2.179-1.471 3.304-2.214 3.374-2.23.05-.012.12-.026.166.016.047.041.042.12.037.141-.03.129-1.227 1.241-1.846 1.817-.193.18-.33.307-.358.336a8.154 8.154 0 0 1-.188.186c-.38.366-.664.64.015 1.088.327.216.589.393.85.571.284.194.568.387.936.629.093.06.183.125.27.187.331.236.63.448.997.414.214-.02.435-.22.547-.82.265-1.417.786-4.486.906-5.751a1.426 1.426 0 0 0-.013-.315.337.337 0 0 0-.114-.217.526.526 0 0 0-.31-.093c-.3.005-.763.166-2.984 1.09z" />
                                    </svg>
                                </div>
                                <div class="icon-instagram">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-instagram" viewBox="0 0 16 16">
                                        <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z" />
                                    </svg>
                                </div>
                                <div class="icon-twiter">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-twitter" viewBox="0 0 16 16">
                                        <path d="M5.026 15c6.038 0 9.341-5.003 9.341-9.334 0-.14 0-.282-.006-.422A6.685 6.685 0 0 0 16 3.542a6.658 6.658 0 0 1-1.889.518 3.301 3.301 0 0 0 1.447-1.817 6.533 6.533 0 0 1-2.087.793A3.286 3.286 0 0 0 7.875 6.03a9.325 9.325 0 0 1-6.767-3.429 3.289 3.289 0 0 0 1.018 4.382A3.323 3.323 0 0 1 .64 6.575v.045a3.288 3.288 0 0 0 2.632 3.218 3.203 3.203 0 0 1-.865.115 3.23 3.23 0 0 1-.614-.057 3.283 3.283 0 0 0 3.067 2.277A6.588 6.588 0 0 1 .78 13.58a6.32 6.32 0 0 1-.78-.045A9.344 9.344 0 0 0 5.026 15z" />
                                    </svg>
                                </div>
                            </div>
                        </div><br>
                        <div class="hashtag">
                            <div class="titre-hashtag">
                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-hash" viewBox="0 0 16 16">
                                    <path d="M8.39 12.648a1.32 1.32 0 0 0-.015.18c0 .305.21.508.5.508.266 0 .492-.172.555-.477l.554-2.703h1.204c.421 0 .617-.234.617-.547 0-.312-.188-.53-.617-.53h-.985l.516-2.524h1.265c.43 0 .618-.227.618-.547 0-.313-.188-.524-.618-.524h-1.046l.476-2.304a1.06 1.06 0 0 0 .016-.164.51.51 0 0 0-.516-.516.54.54 0 0 0-.539.43l-.523 2.554H7.617l.477-2.304c.008-.04.015-.118.015-.164a.512.512 0 0 0-.523-.516.539.539 0 0 0-.531.43L6.53 5.484H5.414c-.43 0-.617.22-.617.532 0 .312.187.539.617.539h.906l-.515 2.523H4.609c-.421 0-.609.219-.609.531 0 .313.188.547.61.547h.976l-.516 2.492c-.008.04-.015.125-.015.18 0 .305.21.508.5.508.265 0 .492-.172.554-.477l.555-2.703h2.242l-.515 2.492zm-1-6.109h2.266l-.515 2.563H6.859l.532-2.563z" />
                                </svg>
                                <h1>برچسب ها</h1>
                            </div>
                            <div class="content-hastag">
                                <p><?php echo $course['tag']; ?></p>
                            </div>
                        </div>
                    </div>
                </div>

            </div><br>
    </div>

<?php } ?>




<!-- footer -->
<div class="footer ">

    <p class="txt-footer ">تمامی حقوق مادی و معنوی این وبسایت متعلق به تاپ لرن میباشد</p>
</div>
<!-- footer -->


</body>
<script src="../js/app.js "></script>

<!-- <script src="../js/sweetalert2.min.js "></script> -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js " integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo " crossorigin="anonymous "></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js " integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49 " crossorigin="anonymous "></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js " integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy " crossorigin="anonymous "></script>


</html>

<?php if ($add_course) { ?>
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
            title: 'دوره به سبد خرید اضافه شد'
        })
    </script>
<?php } elseif ($error_course) { ?>

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
            icon: 'warning',
            title: 'اینو که داری تو سبد خریدت'
        })
    </script>

<?php } elseif ($success_comment) { ?>

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
<?php } elseif ($error_comment) { ?>

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
            icon: 'warning',
            title: 'هنوز وارد حسابت نشدی که'
        })
    </script>
<?php } ?>