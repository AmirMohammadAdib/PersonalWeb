<?php
include "../database/db.php";
include "../script/jdf.php";
$id = $_GET['id'];
$success_comment = null;


$menus = $conn->prepare('SELECT * FROM menus ORDER BY Sort');
$menus->execute();
$menus = $menus->fetchAll(PDO::FETCH_ASSOC);

$blog = $conn->prepare("SELECT * FROM blog WHERE id=?");
$blog->bindValue(1, $id);
$blog->execute();
$blog = $blog->fetch(PDO::FETCH_ASSOC);

if (isset($_SESSION['login'])) {

    if (isset($_POST['sub_comm'])) {
        $comment = $_POST['caption'];
        $comments = $conn->prepare('INSERT INTO comment_blog SET sender=? , content=? , date=? , replay=0 , course=? , image=?');
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

$select_comments = $conn->prepare('SELECT * FROM comment_blog');
$select_comments->execute();
$select_comments = $select_comments->fetchAll(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="../css/style.css">
    <!-- <link rel="stylesheet" href="sweetalert2.min.css"> -->
    <!-- <link rel="stylesheet" href="../css/sweetalert2.min.css"> -->
    <link href='http://www.fontonline.ir/css/BYekan.css' rel='stylesheet' type='text/css'>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="style/blogstyle.css">
</head>

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
                                <a href="<?php echo $menu['Src'] ?>"
                                    class="nav-link <?php foreach ($menus as $z) {
                                                                                                    if ($menu['Id'] == $z['Z']) {  ?> dropdown-toggle <?php }
                                                                                                                                                }; ?> "
                                    id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true"
                                    aria-expanded="false">

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
                        </p><span><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                class="bi bi-arrow-down-square" viewBox="0 0 16 16" id="icon-username">
                                <path fill-rule="evenodd"
                                    d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                            </svg></span>
                        <div class="box-profile">
                            <div class="profile-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                    <path fill-rule="evenodd"
                                        d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                </svg>
                                <h6>مشاهده حساب کاربری</h6>
                            </div>
                            <div class="profile-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-pen" viewBox="0 0 16 16">
                                    <path
                                        d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                                </svg>
                                <h6>ویرایش حساب کاربری</h6>
                            </div>
                            <div class="profile-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-basket" viewBox="0 0 16 16">
                                    <path
                                        d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                                </svg>
                                <a href="../basket-store/basket.php">
                                    <h6>سبد خرید</h6>
                                </a>
                            </div>
                            <div class="profile-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-wallet2" viewBox="0 0 16 16">
                                    <path
                                        d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z" />
                                </svg>
                                <h6>کیف پول من</h6>
                            </div>
                            <div class="profile-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor"
                                    class="bi bi-bell" viewBox="0 0 16 16">
                                    <path
                                        d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2zM8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z" />
                                </svg>
                                <h6>
                                    <a href="page/loginAdmin.php"
                                        style="color: #ffffff; text-decoration: none;"><?php if ($_SESSION['level'] == 2) {
                                                                                                                                echo "پنل نویسندگان";
                                                                                                                            } elseif ($_SESSION['level'] == 3) {
                                                                                                                                echo "پنل مدرسین";
                                                                                                                            } elseif ($_SESSION['level'] == 4) {
                                                                                                                                echo "پنل مدیریت";
                                                                                                                            } ?></a>
                                </h6>
                            </div>

                            <div class="profile-item">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                    <path fill-rule="evenodd"
                                        d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
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

                                    <?php foreach ($user as $u) { ?> <img
                                        src="../image/profile-img/<?php echo $u['image'] . '.png'; ?>"><?php } ?>
                                    <p>
                                        <?php echo $_SESSION['username'] ?>
                                    </p><span><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" class="bi bi-arrow-down-square" viewBox="0 0 16 16"
                                            id="icon-username">
                                            <path fill-rule="evenodd"
                                                d="M15 2a1 1 0 0 0-1-1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2zM0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2zm8.5 2.5a.5.5 0 0 0-1 0v5.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V4.5z" />
                                        </svg></span>
                                    <div class="box-profile">
                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z" />
                                                <path fill-rule="evenodd"
                                                    d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z" />
                                            </svg>
                                            <h6>مشاهده حساب کاربری</h6>
                                        </div>
                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
                                                <path
                                                    d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z" />
                                            </svg>
                                            <h6>ویرایش حساب کاربری</h6>
                                        </div>
                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-basket" viewBox="0 0 16 16">
                                                <path
                                                    d="M5.757 1.071a.5.5 0 0 1 .172.686L3.383 6h9.234L10.07 1.757a.5.5 0 1 1 .858-.514L13.783 6H15a1 1 0 0 1 1 1v1a1 1 0 0 1-1 1v4.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 13.5V9a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h1.217L5.07 1.243a.5.5 0 0 1 .686-.172zM2 9v4.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V9H2zM1 7v1h14V7H1zm3 3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 4 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 6 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3A.5.5 0 0 1 8 10zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5zm2 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5z" />
                                            </svg>
                                            <a href="../basket-store/basket.php">
                                                <h6>سبد خرید</h6>
                                            </a>
                                        </div>
                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-wallet2" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.136.326A1.5 1.5 0 0 1 14 1.78V3h.5A1.5 1.5 0 0 1 16 4.5v9a1.5 1.5 0 0 1-1.5 1.5h-13A1.5 1.5 0 0 1 0 13.5v-9a1.5 1.5 0 0 1 1.432-1.499L12.136.326zM5.562 3H13V1.78a.5.5 0 0 0-.621-.484L5.562 3zM1.5 4a.5.5 0 0 0-.5.5v9a.5.5 0 0 0 .5.5h13a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-13z" />
                                            </svg>
                                            <h6>کیف پول من</h6>
                                        </div>
                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-folder-plus" viewBox="0 0 16 16">
                                                <path
                                                    d="m.5 3 .04.87a1.99 1.99 0 0 0-.342 1.311l.637 7A2 2 0 0 0 2.826 14H9v-1H2.826a1 1 0 0 1-.995-.91l-.637-7A1 1 0 0 1 2.19 4h11.62a1 1 0 0 1 .996 1.09L14.54 8h1.005l.256-2.819A2 2 0 0 0 13.81 3H9.828a2 2 0 0 1-1.414-.586l-.828-.828A2 2 0 0 0 6.172 1H2.5a2 2 0 0 0-2 2zm5.672-1a1 1 0 0 1 .707.293L7.586 3H2.19c-.24 0-.47.042-.683.12L1.5 2.98a1 1 0 0 1 1-.98h3.672z" />
                                                <path
                                                    d="M13.5 10a.5.5 0 0 1 .5.5V12h1.5a.5.5 0 1 1 0 1H14v1.5a.5.5 0 1 1-1 0V13h-1.5a.5.5 0 0 1 0-1H13v-1.5a.5.5 0 0 1 .5-.5z" />
                                            </svg>
                                            <h6>فاکتور های من</h6>
                                        </div>
                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                fill="currentColor" class="bi bi-youtube" viewBox="0 0 16 16">
                                                <path
                                                    d="M8.051 1.999h.089c.822.003 4.987.033 6.11.335a2.01 2.01 0 0 1 1.415 1.42c.101.38.172.883.22 1.402l.01.104.022.26.008.104c.065.914.073 1.77.074 1.957v.075c-.001.194-.01 1.108-.082 2.06l-.008.105-.009.104c-.05.572-.124 1.14-.235 1.558a2.007 2.007 0 0 1-1.415 1.42c-1.16.312-5.569.334-6.18.335h-.142c-.309 0-1.587-.006-2.927-.052l-.17-.006-.087-.004-.171-.007-.171-.007c-1.11-.049-2.167-.128-2.654-.26a2.007 2.007 0 0 1-1.415-1.419c-.111-.417-.185-.986-.235-1.558L.09 9.82l-.008-.104A31.4 31.4 0 0 1 0 7.68v-.123c.002-.215.01-.958.064-1.778l.007-.103.003-.052.008-.104.022-.26.01-.104c.048-.519.119-1.023.22-1.402a2.007 2.007 0 0 1 1.415-1.42c.487-.13 1.544-.21 2.654-.26l.17-.007.172-.006.086-.003.171-.007A99.788 99.788 0 0 1 7.858 2h.193zM6.4 5.209v4.818l4.157-2.408L6.4 5.209z" />
                                            </svg>
                                            <h6>
                                                <a style="color: #ffffff; text-decoration: none;"
                                                    href="../page/my-course.php">ویدئو های من</a>
                                            </h6>
                                        </div>

                                        <div class="profile-item">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd"
                                                    d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                                <path fill-rule="evenodd"
                                                    d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
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
                                    <span><a href="page/register.php"
                                            style="color: #ffffff; text-decoration: none; font-size: 20px;">ثبت
                                            نام</a></span><span style="color: #ffffff;
                    flex-direction: row-reverse;
                    /* font-weight: 300; */
                    font-family: hekaiyat;
                    font-size: 14px;
                "> / </span><span><a href="page/login.php"
                                            style="color: #ffffff; text-decoration: none; font-size: 20px;">ورود</a></span>
                                </span>

                                <span><svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                        class="bi bi-person icon-user-header" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                    </svg></span>


                            </div>

                            <?php } ?>
                        </div>
                    </div>




                    <div class="back-side ">

                    </div>
    </header><br><br>

    <!-- EndHeader -->


    <!-- Start Content -->
    <div class="container">
        <div class="row">
            <div class="all-box-blog">
                <div class="content-blog">
                    <div class="image-blog">
                        <img src="../uploader/upload-image-blog/<?= $blog['image']; ?>" alt="">
                        <div class="information-writer">
                            <div class="image-writer">
                                <img src="../uploader/img-profile-writer/<?= $blog['profile']; ?> " alt="">
                            </div>
                            <div style="margin-top: -70px; margin-bottom: -13px; margin-right: 13px;">
                                <div class="vio-blog">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-eye" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                        <path
                                            d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                    </svg>
                                    <p>۲۵۶</p>
                                    <p>بازدید</p>
                                </div>
                                <div class="vio-blog">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-calendar-week" viewBox="0 0 16 16">
                                        <path
                                            d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm-5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z" />
                                        <path
                                            d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z" />
                                    </svg>
                                    </svg>
                                    <p>۲۰/۵۴/۱۴۰۰</p>
                                </div>
                                <div class="vio-blog">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                        class="bi bi-person" viewBox="0 0 16 16">
                                        <path
                                            d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                                    </svg>
                                    <p>امیرمحمد ادیب</p>
                                </div>
                            </div>
                            <hr style="margin-bottom: 0;">
                        </div>
                    </div><br>
                    <div class="titre">
                        <h1><?= $blog['title'] ?></h1>
                    </div>
                    <div class="content" style="text-align: right; width: 100%;">
                        <?= $blog['caption'] ?>
                    </div>
                </div>
                <div class="nav-bar">
                    <div class="handel-name">
                        <div class="title-hanel">
                            <h1>دسته بندی دوره های آموزشی
                            </h1>
                            <hr>
                            <div class="li-handel">
                                <?php foreach ($menus as $menu) { ?>
                                <a href="<?= $menu['Src'] ?>">
                                    <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-right-short" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z" />
                                        </svg><?= $menu['Title'] ?></p>
                                </a>

                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div style="display: flex; justify-content: end; width: 100%;">
                <div class="comments">
                    <div class="titre-comment ">
                        <svg xmlns="http://www.w3.org/2000/svg " width="35 " style="margin-bottom: 20px;" height="35 "
                            fill="currentColor " class="bi bi-card-heading " viewBox="0 0 16 16 ">
                            <path
                                d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-13z " />
                            <path
                                d="M3 8.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5zm0-5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5v-1z " />
                        </svg>
                        <b style="margin-bottom: 20px;">نظرات کاربران در رابطه با این مقاله
                        </b>
                    </div>
                    <div>
                        <p
                            style=" text-align: right; background-color: rgb(160, 213, 153); padding: 2%; border-radius: 5px; color: grreen; color: rgb(30, 71, 17); opacity: 0.9; ">
                            لطفا سوالات خود را راجع به این آموزش در این بخش پرسش و پاسخ مطرح کنید به سوالات در قسمت
                            نظرات پاسخ داده نخواهد شد و آن نظر حذف میشود.</p>
                    </div>
                    <div class="textarya ">
                        <form method="post">

                            <textarea name="caption" class="editor" id="my-editor" cols="30" rows="10"
                                required></textarea><br>
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
                                    console.error(
                                        'Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:'
                                        );
                                    console.warn('Build id: fotmady28o1t-fx1wlfayz8ed');
                                    console.error(error);
                                });
                            </script>

                            <p>
                            </p>

                            <input type="submit" value="ثبت دیدگاه" class="btn" name="sub_comm"
                                style="background-color: #6fc341; color: #ffffff; width: 100%;">
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
                                <div class="get-date-comment "> ارسال شده در <span>
                                        <?php echo jdate("Y/n/d", $select_comment['date']) ?> </span></div>
                                <div class="btns-comment ">
                                    <button class="btn"
                                        style="background-color: #71c55e; color: #ffffff; padding: 0px; padding-left: 5px; padding-right: 5px;">ثبت
                                        پاسخ</button>
                                    <button class="btn"
                                        style="background-color: #daa520; color: #ffffff; padding: 0px; padding-left: 5px; padding-right: 5px;">گزارش</button>
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
    </div><br>
</body>

</html>

<?php if ($success_comment) { ?>

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