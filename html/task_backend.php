<?php
ini_set( "display_errors", 0);
  session_start();

  require_once __DIR__ .'/vendor/autoload.php';

  use Libs\AdminUser\AdminUser;

  if (!isset($adminUser)) {
    # let's create an admin user object
    $adminUser = new AdminUser();
  }
  if (!$adminUser->loggedIn() || $adminUser->getAdminUserInfo('id', $_SESSION['user_id'], 'privilege') == 0) {
    # this admin user is not logged in, and does not have privileged access
    $adminUser->logAdminUserOut();//logout
    header('Location: index.php');//then send home
  }
?>
<?php
  //bring in the super user controller
  require_once __DIR__ . '/utils/superuser_controller.php';
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
                    <a class="logo">
                      <b>
                        <img src="../plugins/images/admin-logo-dark.png" style="width:80%;" alt="home" class="light-logo" />
                      </b>
                    </a>
                </div>
                <!-- /Logo -->
                <ul class="nav navbar-top-links navbar-right pull-right">
                    <!-- <li class="dropdown">
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
                            <li><a href="profile.php#billing"><i class="ti-wallet"></i> Billing</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="profile.php"><i class="ti-settings"></i> Update Account</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="utils/logout_admin_user.php"><i class="fa fa-power-off"></i> Logout</a></li>
                        </ul>
                        <!-- /.dropdown-user -->
                    </li> -->
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
            <!-- <div class="sidebar-nav slimscrollsidebar">
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
            </div> -->
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
                        <h4 class="page-title">Task Backend</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                                                <!-- <a href="request.php#new" target="_blank" class="btn pull-right m-l-20 shadow-xl ui-gradient-peach">Create a Task</a> -->
                        <!-- <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                            <li class="active">Request Page</li>
                        </ol> -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row" id="new">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">New Tasks</h3> </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="white-box">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Created By</th>
                                    <th>Created On</th>
                                    <th>Task Name</th>
                                    <th>Task Description</th>
                                    <th>Priority</th>
                                    <th>Progress</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($newTasksInfo as $key => $value): ?>
                                <tr>
                                  <td><a href="?task-id=<?php echo $value['id']; ?>"><?php echo $superUserAdmin->getAdminUserInfo('id', $value['user_id'], 'company_name'); ?></a></td>
                                  <td><?php echo date('M d, Y @ g:ia',strtotime($value['created'])); ?></td>
                                  <td><a href="?task-id=<?php echo $value['id']; ?>"><?php echo $value['task_name']; ?></a></td>
                                  <td><a href="?task-id=<?php echo $value['id']; ?>"><?php echo $value['task_description']; ?></a></td>
                                  <td><?php echo $value['task_priority'].'%'; ?></td>
                                  <td><?php echo $value['task_progress'].'%'; ?></td>
                                  <td><?php echo date('M d, Y',strtotime($value['due_date'])); ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                  </div>
                </div>
                <div class="row" id="ongoing">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Ongoing Tasks</h3> </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                          <table class="table table-striped">
                              <thead>
                                  <tr>
                                      <th>Created By</th>
                                      <th>Created On</th>
                                      <th>Task Name</th>
                                      <th>Task Description</th>
                                      <th>Priority</th>
                                      <th>Progress</th>
                                      <th>Due Date</th>
                                  </tr>
                              </thead>
                              <tbody>
                                <?php foreach ($ongoingTasksInfo as $key => $value): ?>
                                    <tr>

                                      <td><a href="?task-id=<?php echo $value['id']; ?>"><?php echo $superUserAdmin->getAdminUserInfo('id', $value['user_id'], 'company_name'); ?></a></td>
                                      <td><?php echo date('M d, Y @ g:ia',strtotime($value['created'])); ?></td>
                                      <td><a href="?task-id=<?php echo $value['id']; ?>"><?php echo $value['task_name']; ?></a></td>
                                      <td><a href="?task-id=<?php echo $value['id']; ?>"><?php echo $value['task_description']; ?></a></td>
                                      <td><?php echo $value['task_priority'].'%'; ?></td>
                                      <td><?php echo $value['task_progress'].'%'; ?></td>
                                      <td><?php echo date('M d, Y',strtotime($value['due_date'])); ?></td>

                                    </tr>
                                <?php endforeach; ?>
                              </tbody>
                          </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
            <div id="update-form">

              <div class="col-md-6">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Update Task</h3>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Username*</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Username"> </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Email*</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email"> </div>
                        </div>
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-3 control-label">Website</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Website"> </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 control-label">Password*</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password"> </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword4" class="col-sm-3 control-label">Re Password*</label>
                            <div class="col-sm-9">
                                <input type="password" class="form-control" id="inputPassword4" placeholder="Retype Password"> </div>
                        </div>
                        <div class="form-group m-b-0">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-info waves-effect waves-light m-t-10">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            </div>

            <!-- Edit Form -->

            <!-- End Edit Form -->
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
