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
    <title>Dashboard - The Startup Studio</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Menu CSS -->
    <link href="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css" rel="stylesheet">
    <!-- toast CSS -->
    <link href="../plugins/bower_components/toast-master/css/jquery.toast.css" rel="stylesheet">
    <!-- morris CSS -->
    <link href="../plugins/bower_components/morrisjs/morris.css" rel="stylesheet">
    <!-- chartist CSS -->
    <link href="../plugins/bower_components/chartist-js/dist/chartist.min.css" rel="stylesheet">
    <link href="../plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.css" rel="stylesheet">
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
                        <a class="dropdown-toggle profile-pic" data-toggle="dropdown" href="#"> <img src="<?php echo $adminUserDetails['picture']; ?>" alt="user-img" width="36" class="img-circle"><b class="hidden-xs"><?php echo $adminUserDetails['company_name']; ?></b><span class="caret"></span> </a>
                        <ul class="dropdown-menu dropdown-user animated flipInY">
                            <li>
                                <div class="dw-user-box">
                                    <div class="u-img"><img src="<?php echo $adminUserDetails['picture']; ?>" alt="user" /></div>
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
                        <h4 class="page-title">Dashboard</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                        <a href="request.php#new" target="_blank" class="btn btn-danger pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light">Create a Task</a>
                        <ol class="breadcrumb">
                            <li><a href="#">Dashboard</a></li>
                        </ol>
                    </div>
                    <!-- /.col-lg-12 -->
                </div>
                <!-- /.row -->
                <!-- ============================================================== -->
                <!-- Different data widgets -->
                <!-- ============================================================== -->
                <!-- .row -->
                <div class="row">
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Total Task Created</h3>
                            <ul class="list-inline two-part">
                                <li>
                                    <div id="sparklinedash"></div>
                                </li>
                                <li class="text-right"><i class="ti-arrow-up text-success"></i> <span class="counter text-success"><?php echo $totalTaskCreated; ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Ongoing Tasks</h3>
                            <ul class="list-inline two-part">
                                <li>
                                    <div id="sparklinedash2"></div>
                                </li>
                                <li class="text-right"><i class="ti-arrow-up text-purple"></i> <span class="counter text-purple"><?php echo $ongoingTasks; ?></span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6 col-xs-12">
                        <div class="white-box analytics-info">
                            <h3 class="box-title">Completed Tasks</h3>
                            <ul class="list-inline two-part">
                                <li>
                                    <div id="sparklinedash3"></div>
                                </li>
                                <li class="text-right"><i class="ti-arrow-up text-info"></i> <span class="counter text-info"><?php echo $completedTask; ?></span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--/.row -->
                <!--row -->
                <!-- /.row -->
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">






                          <div class="white-box">
                            <h3 class="box-title">Task Timeline</h3>
                            <?php if ($tasksGraphTimeline): ?>
                            <ul class="list-inline text-right">
                              <li>
                                <h5><i class="fa fa-circle m-r-5 text-info"></i>Ongoing</h5> </li>
                                <li>
                                  <h5><i class="fa fa-circle m-r-5 text-inverse"></i>Completed</h5> </li>
                                </ul>
                                <div id="ct-visits" style="height: 405px;"></div>
                              <?php else: ?>
                                <h3 >You have not created any task. Click the button below to Create one!</h3>
                                <a href="request.php#new" target="_blank" class="btn btn-danger m-l-20 hidden-xs hidden-sm waves-effect waves-light">Create a Task</a>
                              <?php endif; ?>
                              </div>






                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- Recent comment, table & feed widgets -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-md-12 col-lg-6 col-sm-12" style="width:60%;">
                        <div class="white-box">
                            <div class="col-md-3 col-sm-4 col-xs-6 pull-right" style="width:30%;">
                                <select class="form-control pull-right row b-none">
                                  <?php foreach ($lastFiveMonths as $month): ?>
                                    <option><?php echo $month; ?></option>
                                  <?php endforeach; ?>
                                </select>
                            </div>
                            <h3 class="box-title">Recent Tasks</h3>



                            <?php if ($lastFiveMonths): ?>
                             <div class="row sales-report">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                  <!-- JS to pick the selected month from the dropdown box -->
                                    <h2>January 2018</h2>
                                    <p>TASKS REPORT</p>
                                </div>
<!--                                <div class="col-md-6 col-sm-6 col-xs-6 ">
                                    <h1 class="text-right text-info m-t-20">$3,690</h1> </div>-->

                            <div class="table-responsive">
                            </div>

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAME</th>
                                            <th>STATUS</th>
                                            <th>DATE</th>
                                            <th>DETAILS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!--For now.. let's just have something here  -->
                                        <?php
                                          $selectedTasks = recentTaskReport('January', 7);//selected task from the dropdown box
                                          $count = 1;//initialize count for the # column
                                        ?>
                                        <?php if ($selectedTasks): ?>
                                          <?php foreach ($selectedTasks as $key => $value): ?>
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
                                              <td><span class="label <?php echo $colorClass; ?> label-rouded"><?php echo $text; ?></span> </td>
                                              <td class="txt-oflo"><?php echo date('M d, Y', strtotime($value['created'])); ?></td>
                                              <td><span class="<?php echo $textColorClass; ?>"><?php echo $value['task_description']; ?></span></td>
                                            </tr>
                                            <?php $count++; ?>
                                          <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                          <?php else: ?>
                            <h3 >No Recent Task to show! Create one now!</h3>
                          <?php endif; ?>





                        </div>
                    </div>
                    <!-- <div class="col-md-12 col-lg-6 col-sm-12" style="width:40%;">
                      <div class="panel">
                       <div class="sk-chat-widgets">
                           <div class="panel panel-default">
                               <div class="panel-heading">
                                   PROJECT LISTING
                               </div>
                               <div class="panel-body">

                               </div>
                           </div>
                       </div>
                    </div>
                </div> -->
                <!-- ============================================================== -->
                <!-- chats, message & profile widgets -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- .col -->
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        </div>
                    </div>
                    <!-- /.col -->
                    <!-- .col -->
                    <div class="col-lg-4 col-md-6 col-sm-12">



                    </div>
                    <!-- /.col -->
                    <!-- .col -->
                    <div class="col-lg-4 col-md-12 col-sm-12">




                    </div>
                    <!-- /.col -->
                </div>
            </div>
            <!-- /.container-fluid -->
            <footer class="footer text-center"> 2017 &copy; The Startup Studio </footer>
        </div>
        <!-- ============================================================== -->
        <!-- End Page Content -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="../plugins/bower_components/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="../plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js"></script>
    <!--slimscroll JavaScript -->
    <script src="js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="js/waves.js"></script>
    <!--Counter js -->
    <script src="../plugins/bower_components/waypoints/lib/jquery.waypoints.js"></script>
    <script src="../plugins/bower_components/counterup/jquery.counterup.min.js"></script>
    <!-- chartist chart -->
    <script src="../plugins/bower_components/chartist-js/dist/chartist.min.js"></script>
    <script src="../plugins/bower_components/chartist-plugin-tooltip-master/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="../plugins/bower_components/chartist-plugin-axistitle/dist/chartist-plugin-axistitle.min.js "></script>
<!--    <script src="../plugins/bower_components/chartist-plugin-pointlabels/dist/chartist-plugin-pointlabels.min.js "></script>-->
    <!-- Sparkline chart JavaScript -->
    <script src="../plugins/bower_components/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Custom Theme JavaScript -->
    <script src="js/custom.min.js"></script>
    <script src="js/dashboard1.js"></script>
    <script src="../plugins/bower_components/toast-master/js/jquery.toast.js"></script>
    <!--Style Switcher -->
    <script src="../plugins/bower_components/styleswitcher/jQuery.style.switcher.js"></script>
</body>

</html>
