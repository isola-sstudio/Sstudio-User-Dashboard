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
  //bring in the request controller
  require_once __DIR__ . '/utils/request_controller.php';
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
                    <li class="dropdown" style="padding-top:10px;">
                      <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#" style="display:inline-block;">
                        <?php if ($adminUserDetails['picture']): ?>
                          <img src="<?php echo $adminUserDetails['picture']; ?>" alt="user-img" width="36" class="img-circle">
                        <?php else: ?>
                          <img src="../plugins/images/blank-profile-picture.png" alt="user-img" width="36" class="img-circle">
                        <?php endif; ?>
                        <b class="hidden-xs"><?php echo $adminUserDetails['company_name']; ?></b>
                      </a>
                      <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12" style="width:50%;display:inline-block;float:none;">
                          <a href="request.php#new" target="_blank" class="btn m-l-20 shadow-xl ui-gradient-peach">Create a Task</a>
                      </div>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img">
                                      <?php if ($adminUserDetails['picture']): ?>
                                        <img src="<?php echo $adminUserDetails['picture']; ?>" alt="user" />
                                      <?php else: ?>
                                        <img src="../plugins/images/blank-profile-picture.png" alt="user" />
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
                    <li><a href="history.php"><i class="fa fa-calendar-o fa-fw" aria-hidden="true"></i>Task History</a></li>
                    <li class="devider"></li>
                    <li><a href="#"><i class="fa fa-comment-o fa-fw" aria-hidden="true"></i>Chat</a></li>
                    <li class="devider"></li>
                    <li><a href="profile.php" class="waves-effect"><i class="fa fa-cogs fa-fw" aria-hidden="true"></i>Settings<span class="fa arrow"></span></a>
                      <ul class="nav nav-second-level">
                        <li><a href="profile.php"><i class="fa fa-user fa-fw" aria-hidden="true"></i>Profile</a></li>
                      </ul>
                    </li>

                    <li class="devider"></li>
                    <li><a href="#" class="waves-effect"><i class="fa fa fa-question-circle fa-fw"></i>Support</a></li>
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
                    <!-- /.col-lg-12 -->
                </div>
                <div class="row" id="new">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">Request A New Task</h3> </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="white-box">
                            <h3 class="box-title m-b-0">New Task Form</h3>
                            <p class="text-muted <?php if(isset($requestResponse)){echo 'text-success';} ?> m-b-30 font-13">
                              <?php
                                if (isset($requestResponse)) {
                                  echo $requestResponse;
                                  # if we have a response.. i.e user has already submitted a form
                                }else {
                                  // user has not submitted a form.. but we need to change this to JS
                                    echo "Please supply the following information about the new task you are about to create";
                                  }
                                ?>
                            </p>
                            <form class="form-horizontal" action="" method="post">
                                <div class="form-group">
                                    <label for="task_name" class="col-sm-3 control-label">Task Name*</label>
                                    <div class="col-sm-9">
                                    <input type="text" name="task_name" class="form-control" id="task_name" placeholder="Task Name" required> </div>
                                </div>
                                <div class="form-group">
                                    <label for="task_priority" class="col-sm-3 control-label">Task Priority*</label>
                                    <div class="range-slider">
                                      <input class="range-slider__range" name="task_priority" type="range" value="50" min="10" max="100" style="display:inline-block;width:auto;">
                                      <span class="range-slider__value">0</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="due_date" class="col-sm-3 control-label">Due Date*</label>
                                    <div class="col-sm-9">
                                    <input type="date" name="due_date" class="form-control" id="due_date" required>
                                  </div>

                                </div>





                                <div class="form-group">
                                    <label for="task_desc" class="col-sm-3 control-label">Description*</label>
                                    <div class="col-sm-9">
                                        <textarea name="task_desc" class="form-control" id="task_desc" rows="5" placeholder="Task Description" required></textarea>
                                    </div>
                                </div>

                                <div class="form-group m-b-0">
                                    <div class="col-sm-offset-3 col-sm-9">
                                        <button type="submit" name="create_request" class="btn ui-gradient-green m-t-10">Submit</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
                <div class="row" id="ongoing">
                    <div class="col-md-12">
                        <div class="white-box">
                            <h3 class="box-title">View Ongoing Tasks</h3> </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="white-box">
                            <div class="table-responsive">
                              <?php if ($ongoingTasksInfo): ?>

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Task</th>
                                            <th>Progress</th>
                                            <th>Date Created</th>
                                            <th class="text-nowrap">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      <?php foreach ($ongoingTasksInfo as $key => $value): ?>
                                        <tr>
                                          <td><?php echo $value['task_name']; ?></td>
                                          <td>
                                            <div class="progress progress-xs margin-vertical-10 ">
                                              <div class="progress-bar progress-bar-danger" style="width: 35%"></div>
                                            </div>
                                          </td>
                                          <td><?php echo date('M d, Y', strtotime($value['created'])); ?></td>
                                          <td class="text-nowrap">
                                            <a href="#" data-toggle="tooltip" data-original-title="Edit"> <i class="fa fa-pencil text-inverse m-r-10"></i></a>
                                            <a href="#" data-toggle="tooltip" data-original-title="Suspend"> <i class="fa fa-pause text-warning"></i></a>
                                          </td>
                                        </tr>
                                      <?php endforeach; ?>
                                    </tbody>
                                </table>
                              <?php else: ?>
                                  <h3>
                                    No task here! Just a click away to your next task.
                                  </h3>
                              <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
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
