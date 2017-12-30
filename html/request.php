<?php

  session_start();

  require_once __DIR__ .'/vendor/autoload.php';

  use Libs\AdminUser\AdminUser;

  if (!isset($adminUser)) {
    # let's create an admin user object
    $adminUser = new AdminUser();
  }
  if (!$adminUser->loggedIn()) {
    # this admin user is not logged in, so, redirect
    header('Location: index.php');
  }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" type="image/png" sizes="16x16" href="../plugins/images/favicon.png">
    <title>Request - The Startup Studio</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- animation CSS -->
    <link href="css/animate.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="css/style.css" rel="stylesheet">
    <!-- color CSS -->
    <link href="css/colors/default.css" id="theme" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="fix-header">
    <!-- ============================================================== -->
    <!-- Preloader -->
    <!-- ============================================================== -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" />
        </svg>
    </div>
    <!-- ============================================================== -->
    <!-- Wrapper -->
    <!-- ============================================================== -->
    <div id="wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <nav class="navbar navbar-default navbar-static-top m-b-0">
            <div class="navbar-header">
                <div class="top-left-part">
                    <!-- Logo -->
                    <a class="logo" href="dashboard.php">
                      <b>
                        <img src="../plugins/images/admin-logo-dark.png" style="width:80%;" alt="home" class="light-logo" />
                      </b>
                    </a>
                </div>
                <!-- /Logo -->
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <li>
                        <form role="search" class="app-search hidden-sm hidden-xs m-r-10">
                            <input type="text" placeholder="Search..." class="form-control"> <a href=""><i class="fa fa-search"></i></a> </form>
                    </li>
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="../plugins/images/users/varun.jpg" alt="user-img" width="36" class="img-circle"><b class="hidden-xs">Steave</b><span class="caret"></span> </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="../plugins/images/users/varun.jpg" alt="user" /></div>
                                    <div class="u-text">
                                        <h4>Steave Jobs</h4>
                                        <p class="text-muted">varun@gmail.com</p><a href="profile.html" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                            <li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
                            <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="ti-settings"></i> Account Setting</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="utils/logout_admin_user.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li>
                    <!-- /.dropdown -->
                </ul>
            </div>
            <!-- /.navbar-header -->
            <!-- /.navbar-top-links -->
            <!-- /.navbar-static-side -->
        </nav>
        <!-- End Top Navigation -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav slimscrollsidebar">
                <div class="sidebar-head">
                    <h3><span class="fa-fw open-close"><i class="ti-close ti-menu"></i></span> <span class="hide-menu">Navigation</span></h3>
                </div>
                <ul class="nav" id="side-menu">
                    <li style="padding: 70px 0 0;"><a href="dashboard.php" class="waves-effect"><i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>Dashboard</a> </li>
                    <li><a href="#" class="waves-effect"><i class="fa fa-clone fa-fw" aria-hidden="true"></i> Request<span class="fa arrow"></span><span class="label label-rouded label-warning pull-right">3</span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="request.php#new"><i class="fa fa-hourglass-start fa-fw" aria-hidden="true"></i>New Task</a></li>
                            <li><a href="request.php#ongoing"><i class="fa fa-columns fa-fw" aria-hidden="true"></i>Ongoing Task</a></li>
                        </ul>
                    </li>
                    <li><a href="chat.html"><i class="fa fa-comment-o fa-fw" aria-hidden="true"></i>Chat</a></li>
                    <li><a href="form-basic.html"><i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>History</a></li>
                    <li class="devider"></li>
                    <li><a href="profile.html"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Profile</a></li>
<!--                    <li><a href="basic-table.html"><i class="fa fa-table fa-fw" aria-hidden="true"></i>Basic Table</a></li>
                    <li><a href="fontawesome.html"><i class="fa fa-font fa-fw" aria-hidden="true"></i>Font awesome</a></li>
                    <li><a href="map-google.html" class="waves-effect"><i class="fa fa-globe fa-fw" aria-hidden="true"></i>Google Map</a></li>
                    <li><a href="map-vector.html" class="waves-effect"><i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>Vector Map</a></li>
                    <li><a href="javascript:void(0)" class="waves-effect"><i class="fa fa-cog fa-fw" aria-hidden="true"></i>Multi-Level Dropdown<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="javascript:void(0)">Second Level Item</a></li>
                            <li><a href="javascript:void(0)">Second Level Item</a></li>
                            <li><a href="javascript:void(0)" class="waves-effect">Third Level <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li><a href="javascript:void(0)">Third Level Item</a></li>
                                    <li><a href="javascript:void(0)">Third Level Item</a></li>
                                    <li><a href="javascript:void(0)">Third Level Item</a></li>
                                    <li><a href="javascript:void(0)">Third Level Item</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li> -->
                    <li><a href="login.html" class="waves-effect"><i class="fa fa-credit-card fa-fw" aria-hidden="true"></i>Billing</a></li>
                    <li class="devider"></li>
                    <li><a href="faq.html" class="waves-effect"><i class="fa fa-circle-o fa-fw text-success"></i> Faqs</a></li>
                </ul>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- End Left Sidebar -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page Content -->
        <!-- ============================================================== -->
        <div id="page-wrapper">
            <div class="container-fluid">
                <div class="row bg-title">
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <h4 class="page-title">Request Page</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <a href="https:sstudio.io/" target="_blank" class="btn btn-danger pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Upgrade to Pro</a>
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Request Page</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Request Page</h3> </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2017 &copy; The Startup Studio </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- /#wrapper -->
    <!-- jQuery -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
