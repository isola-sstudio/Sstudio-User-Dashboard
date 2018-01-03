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
<?php
  //bring in the admin user controller
  require_once __DIR__ . '/utils/admin_user_controller.php';
  //bring in the task controller
  require_once __DIR__ . '/utils/task_controller.php';
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
    <title>Profile - The Startup Studio</title>
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
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="../plugins/images/users/varun.jpg" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo $adminUserDetails['company_name']; ?></b><span class="caret"></span> </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="../plugins/images/users/varun.jpg" alt="user" /></div>
                                    <div class="u-text">
                                        <h4><?php echo $adminUserDetails['company_name']; ?></h4>
                                        <p class="text-muted"><?php echo $adminUserDetails['company_email']; ?></p><a href="profile.php" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div>
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
                    <li><a href="#" class="waves-effect"><i class="fa fa-clone fa-fw" aria-hidden="true"></i> Request<span class="fa arrow"></span><span class="label label-rouded label-warning pull-right"><?php echo $justCreatedTasks; ?></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="request.php#new"><i class="fa fa-sticky-note-o fa-fw" aria-hidden="true"></i>New Task</a></li>
                            <li><a href="request.php#ongoing"><i class="fa fa-tasks fa-fw" aria-hidden="true"></i>Ongoing Tasks</a></li>
                        </ul>
                    </li>
                    <li><a href="#"><i class="fa fa-comment-o fa-fw" aria-hidden="true"></i>Chat</a></li>
                    <li><a href="history.php"><i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>History</a></li>
                    <li class="devider"></li>
                    <li><a href="profile.php"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Profile</a></li>
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
                    <li><a href="#" class="waves-effect"><i class="fa fa-credit-card fa-fw" aria-hidden="true"></i>Billing</a></li>
                    <li class="devider"></li>
                    <li><a href="#" class="waves-effect"><i class="fa fa-circle-o fa-fw text-success"></i> Faqs</a></li>
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
                        <h4 class="page-title">Profile page</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <a href="request.php#new" class="btn btn-danger pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Create a Task</a>
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Profile Page</li>
                        </ol>
                    </div>
                </div>
                <!-- /.row -->
                <!-- .row -->
                <div class="row">
                    <div class="col-md-4 col-xs-12">
                        <div class="white-box">
                            <div class="user-bg"> <img width="100%" alt="user" src="../plugins/images/large/img1.jpg">
                                <div class="overlay-box">
                                    <div class="user-content">
                                        <a href="javascript:void(0)"><img src="../plugins/images/users/genu.jpg" class="thumb-lg img-circle" alt="img"></a>
                                        <h4 class="text-white"><?php echo $adminUserDetails['company_name']; ?></h4>
                                        <h5 class="text-white"><?php echo $adminUserDetails['company_email']; ?></h5> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 col-xs-12">
                        <div class="white-box">
                            <ul class="nav nav-tabs tabs customtab">
                                <li class="active tab">
                                    <a href="#profile" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Profile</span> </a>
                                </li>
                                <li class="tab">
                                    <a href="#settings" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Settings</span> </a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="profile">
                                    <div class="row">
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Full Name</strong>
                                            <br>
                                            <p class="text-muted"><?php echo $adminUserDetails['company_name']; ?></p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Mobile</strong>
                                            <br>
                                            <p class="text-muted"><?php echo $adminUserDetails['contact_number']; ?></p>
                                        </div>
                                        <div class="col-md-3 col-xs-6 b-r"> <strong>Email</strong>
                                            <br>
                                            <p class="text-muted"><?php echo $adminUserDetails['company_email']; ?></p>
                                        </div>
                                        <div class="col-md-3 col-xs-6"> <strong>Location</strong>
                                            <br>
                                            <p class="text-muted"><?php echo $adminUserDetails['country']; ?></p>
                                        </div>
                                    </div>
                                    <hr>
                                    <p class="m-t-30"><?php echo $adminUserDetails['project_description']; ?></p>
                                    <hr>
                                </div>
                                <div class="tab-pane" id="settings">
                                    <form class="form-horizontal form-material" action="" method="post">
                                        <div class="form-group">
                                            <label class="col-md-12">Full Name</label>
                                            <div class="col-md-12">
                                                <input type="text" name="company_name" placeholder="<?php echo $adminUserDetails['company_name']; ?>" class="form-control form-control-line"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="example-email" class="col-md-12">Email</label>
                                            <div class="col-md-12">
                                                <input type="email" name="company_email" placeholder="<?php echo $adminUserDetails['company_email']; ?>" class="form-control form-control-line" name="example-email" id="example-email"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Password</label>
                                            <div class="col-md-12">
                                                <input type="password" name="password" placeholder="password" class="form-control form-control-line" required> </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Phone No</label>
                                            <div class="col-md-12">
                                                <input type="text" name="contact_number" placeholder="<?php echo $adminUserDetails['contact_number']; ?>" class="form-control form-control-line"> </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-12">Message</label>
                                            <div class="col-md-12">
                                                <textarea name="project_description" rows="5" class="form-control form-control-line" placeholder="<?php echo $adminUserDetails['company_name']; ?>"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-12">Select Country</label>
                                            <div class="col-sm-12">
                                                <select name="country" class="form-control form-control-line">
                                                    <option value=""></option>
                                                    <option>Nigeria</option>
                                                    <option>London</option>
                                                    <option>India</option>
                                                    <option>Usa</option>
                                                    <option>Canada</option>
                                                    <option>Thailand</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button name="update_profile" class="btn btn-success">Update Profile</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-theme="default" class="default-theme">1</a></li>
                                <li><a href="javascript:void(0)" data-theme="green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-theme="gray" class="yellow-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna" class="megna-theme">6</a></li>
                                <li><b>With Dark sidebar</b></li>
                                <br/>
                                <li><a href="javascript:void(0)" data-theme="default-dark" class="default-dark-theme">7</a></li>
                                <li><a href="javascript:void(0)" data-theme="green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-theme="gray-dark" class="yellow-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-theme="blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-theme="purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-theme="megna-dark" class="megna-dark-theme working">12</a></li>
                            </ul>
                            <ul class="m-t-20 all-demos">
                                <li><b>Choose other demos</b></li>
                            </ul>
                            <ul class="m-t-20 chatonline">
                                <li><b>Chat option</b></li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/varun.jpg" alt="user-img" class="img-circle"> <span>Varun Dhavan <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/genu.jpg" alt="user-img" class="img-circle"> <span>Genelia Deshmukh <small class="text-warning">Away</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/ritesh.jpg" alt="user-img" class="img-circle"> <span>Ritesh Deshmukh <small class="text-danger">Busy</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/arijit.jpg" alt="user-img" class="img-circle"> <span>Arijit Sinh <small class="text-muted">Offline</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/govinda.jpg" alt="user-img" class="img-circle"> <span>Govinda Star <small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/hritik.jpg" alt="user-img" class="img-circle"> <span>John Abraham<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/john.jpg" alt="user-img" class="img-circle"> <span>Hritik Roshan<small class="text-success">online</small></span></a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)"><img src="../plugins/images/users/pawandeep.jpg" alt="user-img" class="img-circle"> <span>Pwandeep rajan <small class="text-success">online</small></span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2017 &copy; The Startup Studio </footer>
        </div>
        <!-- /#page-wrapper -->
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
