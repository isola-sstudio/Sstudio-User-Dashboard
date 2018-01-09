<?php
ini_set( "display_errors", 0);
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
    <title>History - The Startup Studio</title>
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
                    <li class="dropdown">
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#">
                          <?php if ($adminUserDetails['picture']): ?>
                            <img src="<?php echo $adminUserDetails['picture']; ?>" alt="user-img" width="36" class="img-circle">
                          <?php endif; ?>
                          <b class="hidden-xs"><?php echo $adminUserDetails['company_name']; ?></b>
                          <span class="caret"></span>
                          <span class="caret" style="visibility:hidden;"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img">
                                      <?php if ($adminUserDetails['picture']): ?>
                                        <img src="<?php echo $adminUserDetails['picture']; ?>" alt="user" />
                                      <?php endif; ?>
                                    </div>
                                    <div class="u-text">
                                        <h4><?php echo $adminUserDetails['company_name']; ?></h4>
                                        <p class="text-muted"><?php echo $adminUserDetails['company_email']; ?></p><a href="profile.php" class="btn btn-rounded btn-danger btn-sm">View Profile</a></div>
                                </div>
                            </li>
                            <li role="separator" class="divider"></li>
                            <li><a href="profile.php"><i class="ti-user"></i> My Profile</a></li>
                            <li><a href="#"><i class="ti-wallet"></i> My Balance</a></li>
                            <li><a href="#"><i class="ti-email"></i> Inbox</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="profile.php"><i class="ti-settings"></i> Account Setting</a></li>
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
                    <li class="devider"></li>
                    <li><a href="#" class="waves-effect"><i class="fa fa-clone fa-fw" aria-hidden="true"></i> Request<span class="fa arrow"></span><span class="label label-rouded label-success pull-right"><?php echo $justCreatedTasks; ?></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="request.php#new"><i class="fa fa-sticky-note-o fa-fw" aria-hidden="true"></i>New Task</a></li>
                            <li><a href="request.php#ongoing"><i class="fa fa-tasks fa-fw" aria-hidden="true"></i>Ongoing Tasks</a></li>
                        </ul>
                    </li>
                    <li class="devider"></li>
                    <li><a href="#"><i class="fa fa-comment-o fa-fw" aria-hidden="true"></i>Chat</a></li>
                    <li class="devider"></li>
                    <li><a href="history.php"><i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>History</a></li>
                    <li class="devider"></li>
                    <li><a href="profile.php"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Profile</a></li>
                    <li class="devider"></li>
                    <li><a href="#" class="waves-effect"><i class="fa fa-user-circle fa-fw text-success"></i>Customer Support</a></li>
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
                        <h4 class="page-title">Task History</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                                                <a href="request.php#new" target="_blank" class="btn pull-right m-l-20 shadow-xl ui-gradient-peach">Create a Task</a>
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Task History</li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row" id="new">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">View all Task</h3> </div>
                    </div>
                </div>
                <!-- /row -->
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                              <?php if ($taskHistory): ?>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Task Name</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Date Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php
                                        $count = 1;//initialize count for the # column
                                      ?>
                                      <?php if ($taskHistory): ?>
                                        <?php foreach ($taskHistory as $key => $value): ?>
                                          <?php switch ($value['status']) {
                                            case 0:
                                              # a just created task
                                              $colorClass = 'label-danger';
                                              $text = 'NOT STARTED';
                                              $textColorClass = 'text-danger';
                                              break;
                                            case 1:
                                              # a just created task
                                              $colorClass = 'label-success';
                                              $text = 'ONGOING';
                                              $textColorClass = 'text-success';
                                              break;
                                            case 3:
                                              # a just created task
                                              $colorClass = 'label-info';
                                              $text = 'COMPLETED';
                                              $textColorClass = 'text-info';
                                              break;
                                            default:
                                              # well, don't know where this falls
                                              $colorClass = 'label-info';
                                              $text = '';
                                              $textColorClass = '';
                                              break;
                                          } ?>
                                          <tr>
                                            <td><?php echo $count; ?></td>
                                            <td class="txt-oflo"><?php echo $value['task_name']; ?></td>
                                            <td><span class="<?php echo $textColorClass; ?>"><?php echo $value['task_description']; ?></span></td>
                                            <td><span class="label <?php echo $colorClass; ?> label-rouded"><?php echo $text; ?></span> </td>
                                            <td><?php echo date('M d, Y', strtotime($value['created'])); ?></td>
                                          </tr>
                                          <?php $count++; ?>
                                        <?php endforeach; ?>
                                      <?php endif; ?>
                                    </tbody>
                                </table>
                              <?php else: ?>
                                <h3>
                                  Task History gives you information about your created Tasks.
                                </h3>
                                  <a href="request.php#new" target="_blank" class="btn pull-right m-l-20 shadow-xl ui-gradient-peach">Create a Task</a>
                              <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->




            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center" style="z-index:9999"> 2017 &copy; The Startup Studio </footer>
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
