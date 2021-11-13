<?php
include "../database/db.php";


$add_blog = null;
$menus = $conn->prepare('SELECT * FROM menus ORDER BY Sort');
$menus->execute();
$menus = $menus->fetchAll(PDO::FETCH_ASSOC);

$user = $conn->prepare('SELECT * FROM users WHERE Id=?');
$user->bindValue(1, $_SESSION['id']);
$user->execute();
$user = $user->fetch(PDO::FETCH_ASSOC);


if (isset($_POST['sub'])) {
    $username = $_POST['username'];
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    //uploader//
    $target_dir = "../uploader/img-profile-writer/";
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

    $request = $conn->prepare('INSERT INTO writers SET username=? , fullname=? , blog=? , image=? , phone_number=?');
    $request->bindValue(1, $username);
    $request->bindValue(2, $fullname);
    $request->bindValue(3, 3);
    $request->bindValue(4, $new_name);
    $request->bindValue(5, $phone);

    $request->execute();

    header('location: ../index.php');
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



    <!-- //form -->
    <div class="container">
        <div class="row" style="justify-content: center;">
            <div class="box-register-writer">
                <div class="p-txt-writer">
                    <p>همکاری به عنوان نویسنده با تاپ لرن</p>
                </div>
                <div class="header-writer">
                    <div class="logo">
                        <img src="../image/logo.svg">
                    </div>
                </div><br>
                <div class="form" enctype="multipart/form-data">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="line-form">
                            <div>
                                <label>نام کاربری</label>
                                <input type="text" name="username" class="form-control" placeholder="نام کاربری" value="<?= $user['UserName']; ?>" required>
                            </div>
                            <div>
                                <label>نام کامل(فارسی)</label>
                                <input type="text" name="fullname" class="form-control" placeholder="نام کامل(فارسی)" required>
                            </div>
                        </div>

                        <div class="line-form">
                            <div>
                                <label>ایمیل</label>
                                <input type="email" name="email" class="form-control" placeholder="ایمیل" value="<?= $user['Email']; ?>" required>
                            </div>
                            <div>
                                <label>تلفن</label>
                                <input type="number" name="phone" class="form-control" placeholder="شماره تلفن" required>
                            </div>
                        </div>

                        <div class="line-form" style="margin-bottom: -15px;">
                            <input type="file" name="fileToUpload" id="fileToUpload" required><br>

                        </div>

                        <div class="img-profile">
                            <img src="../image/profile-img/2.png" class="profile-writer-img">
                            <div class="edit-profile">
                                <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="currentColor" class="bi bi-vector-pen" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10.646.646a.5.5 0 0 1 .708 0l4 4a.5.5 0 0 1 0 .708l-1.902 1.902-.829 3.313a1.5 1.5 0 0 1-1.024 1.073L1.254 14.746 4.358 4.4A1.5 1.5 0 0 1 5.43 3.377l3.313-.828L10.646.646zm-1.8 2.908-3.173.793a.5.5 0 0 0-.358.342l-2.57 8.565 8.567-2.57a.5.5 0 0 0 .34-.357l.794-3.174-3.6-3.6z" />
                                    <path fill-rule="evenodd" d="M2.832 13.228 8 9a1 1 0 1 0-1-1l-4.228 5.168-.026.086.086-.026z" />
                                </svg>
                            </div>
                        </div>


                        <div class="btn-wrater">
                            <input type="submit" value="درخواست همکاری" name="sub" class="btn btn-warning">
                        </div>

                    </form>
                </div>
            </div>
            <div class="box-register-writer-back"></div>
            <div class="box-register-writer-back2"></div>

        </div>
    </div><br><br>


    <!-- footer -->
    <div class="footer ">

        <p class="txt-footer ">تمامی حقوق مادی و معنوی این وبسایت متعلق به تاپ لرن میباشد</p>
    </div>
    <!-- footer -->

</body>

</html>

<?php
if ($add_blog) { ?>

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
            title: 'درخواست با موفقیت ارسال شد'
        })
    </script>


<?php }
?>