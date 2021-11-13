<?php
include '../../database/db.php';
?>
<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <meta name="author" content="Coderthemes">

    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <title>آقای ادمین | قالب واکنشگرا مدیریتی</title>

    <!--Morris Chart CSS -->
    <link rel="stylesheet" href="assets/plugins/morris/morris.css">

    <!-- App css -->
    <link href="assets/css/bootstrap-rtl.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/core.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/components.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/icons.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/pages.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/menu.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/responsive.css" rel="stylesheet" type="text/css" />
    <link rel="icon" type="image/png" href="https://c.cksource.com/a/1/logos/ckeditor5.png">
    <link rel="stylesheet" type="text/css" href="../../ckeditor5/sample/styles.css">

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    <!-- HTML5 Shiv and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->

    <script src="assets/js/modernizr.min.js"></script>


    <link rel="stylesheet" href="../../uploader/style.css">

</head>


<body class="fixed-left">

    <!-- Begin page -->
    <div id="wrapper">

        <!-- Top Bar Start -->
        <div class="topbar">

            <!-- LOGO -->
            <div class="topbar-left">
                <a href="index.php" class="logo"><span> تاپ <span>لرن</span></span><i class="zmdi zmdi-layers"></i></a>
            </div>

            <!-- Button mobile view to collapse sidebar menu -->
            <div class="navbar navbar-default" role="navigation">
                <div class="container">

                    <!-- Page title -->
                    <ul class="nav navbar-nav navbar-left">
                        <li>
                            <button class="button-menu-mobile open-left">
                                <i class="zmdi zmdi-menu"></i>
                            </button>
                        </li>
                        <li>
                            <h4 class="page-title">داشبورد</h4>
                        </li>

                    </ul>

                    <!-- Right(Notification and Searchbox -->
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <!-- Notification -->
                            <div class="notification-box">
                                <ul class="list-inline m-b-0">
                                    <li>
                                        <a href="javascript:void(0);" class="right-bar-toggle">
                                            <i class="zmdi zmdi-notifications-none"></i>
                                        </a>
                                        <div class="noti-dot">
                                            <span class="dot"></span>
                                            <span class="pulse"></span>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Notification bar -->
                        </li>
                        <li class="hidden-xs">
                            <form role="search" class="app-search">
                                <input type="text" placeholder="به دنبال چه می گردی ؟؟؟" class="form-control">
                                <a href=""><i class="fa fa-search"></i></a>
                            </form>
                        </li>
                    </ul>

                </div><!-- end container -->
            </div><!-- end navbar -->
        </div>
        <!-- Top Bar End -->


        <!-- ========== Left Sidebar Start ========== -->
        <div class="left side-menu">
            <div class="sidebar-inner slimscrollleft">

                <!-- User -->
                <div class="user-box">
                    <div class="user-img">
                        <img src="../../image/1631318987.jpg" alt="user-img" title="Mat Helme" class="img-circle img-thumbnail img-responsive">
                        <div class="user-status offline"><i class="zmdi zmdi-dot-circle"></i></div>
                    </div>
                    <h5><a href="#"><?php echo $_SESSION['username']; ?></a> </h5>
                    <ul class="list-inline">
                        <li>
                            <a href="#">
                                <i class="zmdi zmdi-settings"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#" class="text-custom">
                                <i class="zmdi zmdi-power"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- End User -->

                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <ul>
                        <li class="text-muted menu-title">دسته بندی ها</li>

                        <li>
                            <a href="index.php" class="waves-effect active"><i class="zmdi zmdi-view-dashboard"></i> <span> داشبورد </span> </a>
                        </li>

                        <li>
                            <a href="menu.php" class="waves-effect"><i class="fa fa-sort-amount-asc"></i> <span> منو </span> </a>
                        </li>

                        <li>
                            <a href="course.php" class="waves-effect"><i class="zmdi zmdi-case-play"></i> <span> دوره ها </span> </a>
                        </li>


                        <li>
                            <a href="user.php" class="waves-effect"><i class="zmdi zmdi-account-o"></i> <span>کاربران </span> </a>
                        </li>

                        <li>
                            <a href="blog.php" class="waves-effect"><i class="zmdi zmdi-puzzle-piece"></i> <span>بلاگ</span> </a>
                        </li>

                        <li>
                            <a href="writers.php" class="waves-effect"><i class="zmdi zmdi-edit"></i> <span>نویسندگان</span> </a>
                        </li>

                        <li>
                            <a href="blog.php" class="waves-effect"><i class="zmdi zmdi-accounts-list-alt"></i> <span>مدرسان</span> </a>
                        </li>
                        
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <!-- Sidebar -->
                <div class="clearfix"></div>

            </div>

        </div>
        <!-- Left Sidebar End -->
        <div class="content-page">
            <!-- Start content -->
            <div class="content">
                <div class="container">