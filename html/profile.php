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
  //bring in the billing controller
  require_once __DIR__ . '/utils/billing_controller.php';
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
                            <li><a href="#"><i class="ti-user"></i> My Profile</a></li>
                            <li><a href="profile.php#billing"><i class="ti-wallet"></i> Billing</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="#"><i class="ti-settings"></i> Update Account</a></li>
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
                        <h4 class="page-title">Profile page</h4> </div>
                    <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                                                <a href="request.php#new" target="_blank" class="btn pull-right m-l-20 shadow-xl ui-gradient-peach">Create a Task</a>
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
                            <div class="user-bg">
                              <?php if ($adminUserDetails['wallpaper']): ?>
                                <img width="100%" alt="user" src="<?php echo $adminUserDetails['wallpaper']; ?>">
                              <?php endif; ?>
                                <div class="overlay-box">
                                    <div class="user-content">
                                        <a href="javascript:void(0)"><img src="<?php echo $adminUserDetails['picture']; ?>" class="thumb-lg img-circle" alt="img"></a>
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
                                <a href="#profile" data-toggle="tab" aria-expanded="false"> <span class="visible-xs"><i class="fa fa-cog"></i></span> <span class="hidden-xs">Profile</span> </a>
                              </li>
                              <li class="tab">
                                  <a href="#billing" data-toggle="tab"> <span class="visible-xs"><i class="fa fa-user"></i></span> <span class="hidden-xs">Billing</span> </a>
                              </li>
                          </ul>
                          <div class="tab-content">
                            <div class="tab-pane active" id="profile">
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
                                          <div class="col-sm-12">
                                              <button class="btn btn-success">Update Profile</button>
                                          </div>
                                      </div>
                                  </form>
                              </div>
                              <div class="tab-pane" id="billing">
                                <h4 class="font-bold m-t-30">Billing History</h4>
                                <hr>
                                <?php if ($billingHistory): ?>
                                  <div class="table-responsive">
                                    <table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th style="font-weight:700;">Date</th>
                                          <th style="font-weight:700;">Plan</th>
                                          <th style="font-weight:700;">Status</th>
                                          <th class="text-nowrap" style="font-weight:700;">Actions</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php foreach ($billingHistory as $key => $value): ?>
                                          <tr>
                                            <td><?php echo date('M d, Y',strtotime($value['date_created'])); ?></td>
                                            <td><?php echo strtoupper($value['plan']); ?></td>
                                            <td><?php if ($value['status'] == 0): ?>
                                              <span class="text-danger">Failed</span>
                                            <?php else: ?>
                                              <span class="text-success">Successful</span>
                                            <?php endif; ?></td>
                                            <td class="text-nowrap">
                                              <a href="#" data-toggle="tooltip" data-original-title="Download Pdf"> <i class="fa fa-file-pdf-o text-inverse m-r-10"></i> </a>
                                              <a href="#" data-toggle="tooltip" data-original-title="Receive a Mail"> <i class="fa fa-envelope-o text-inverse"></i> </a>
                                            </td>
                                          </tr>
                                        <?php endforeach; ?>
                                      </tbody>
                                    </table>
                                  </div>
                                <?php endif; ?>
                                <hr>
                                <h4 class="font-bold m-t-30">Current Plan</h4>
                                <hr>
                                  <div class="table-responsive">
                                    <table class="table table-striped">
                                      <thead>
                                        <tr>
                                          <th style="font-weight:700;">Current Plan</th>
                                          <th style="font-weight:700;">Started</th>
                                          <th style="font-weight:700;">Status</th>
                                          <th style="font-weight:700;">Expires</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <tr>
                                          <td><?php if (strtoupper($userDetails['subscription_plan'])): ?>
                                            <?php echo $userDetails['subscription_plan']; ?>
                                            <?php else: ?>
                                              None
                                            <?php endif; ?>
                                          </td>
                                          <td>
                                            <?php if ($userDetails['subscription_plan'] && $userDetails['subscription_status'] == 1): ?>
                                              <?php echo date('M d, Y',strtotime($mostRecentBillingHistory['date_created'])); ?>
                                              <?php else: ?>
                                                None
                                              <?php endif; ?>
                                          </td>
                                          <td>
                                            <?php if ($userDetails['subscription_status'] == 1): ?>
                                              Active
                                            <?php else: ?>
                                                Inactive
                                            <?php endif; ?>
                                          </td>
                                          <td class="text-nowrap">
                                            <?php if ($userDetails['subscription_plan'] && $userDetails['subscription_status'] == 1): ?>
                                              <?php echo date('M d, Y',strtotime($mostRecentBillingHistory['expires'])); ?>
                                              <?php else: ?>
                                                None
                                              <?php endif; ?>
                                          </td>
                                        </tr>
                                      </tbody>
                                    </table>
                                  </div>






                                   <!-- Pricing Cards Section -->
                                   <div id="pricing" class="section">
                                      <div class="">
                                         <!-- Section Heading -->
                                         <div class="section-heading">
                                            <h2 class="">
                                               Pricing Cards
                                            </h2>
                                         </div><!-- .section-heading -->

                                         <!-- UI Pricing Cards / Owl Carousel On Mobile -->
                                         <div class="ui-pricing-cards owl-carousel owl-theme">
                                            <!-- Card 1 -->
                                            <div class="ui-pricing-card animate" data-show="fade-in-left">
                                            <div class="ui-card ui-curve shadow-lg">
                                               <div class="card-header">
                                                  <!-- Heading -->
                                                  <h4 class="heading">Basic</h4>
                                                  <!-- Price -->
                                                  <div class="price">
                                                     <span class="curency">&dollar;</span>
                                                     <span class="price">153</span>
                                                     <span class="period">/mo</span>
                                                  </div>
                                                  <h6 class="sub-heading">&#x20A6;55,000 /mo</h6>
                                               </div>
                                               <!-- Features -->
                                               <div class="card-body">
                                                  <ul>
                                                     <li>
                                                        Branding
                                                     </li>
                                                     <li>
                                                       Website Development
                                                     </li>
                                                     <li>
                                                        Digital Marketing
                                                     </li>
                                                     <li>
                                                        Free Accounting Support
                                                     </li>
                                                  </ul>
                          												<script src="https://checkout.stripe.com/checkout.js"></script>
                                                  <?php if ($userDetails['subscription_plan'] && $userDetails['subscription_status'] == 1): ?>
                                                    <?php if (strtolower($userDetails['subscription_plan']) != 'basic'): ?>
                                                      <a class="btn ui-gradient-green shadow-md" id="basic">
                                                        Switch
                                                      </a>
                                                      <?php endif; ?>
                                                  <?php else: ?>
                                                    <a class="btn ui-gradient-green shadow-md" id="basic">
                                                      Get Started
                                                    </a>
                                                  <?php endif; ?>
                                               </div>
                                            </div>
                                            </div>
                                            <!-- Card 2 -->
                                            <div class="ui-pricing-card active animate" data-show="fade-in">
                                            <div class="ui-card ui-curve color-card shadow-xl">
                                               <div class="card-header ui-gradient-purple" style="padding:10px 0;color:#fff;">
                                                  <!-- Heading -->
                                                  <h4 class="heading">Standard</h4>
                                                  <!-- Price -->
                                                  <div class="price">
                                                     <span class="curency">&dollar;</span>
                                                     <span class="price">278</span>
                                                     <span class="period">/mo</span>
                                                  </div>
                                                  <h6 class="sub-heading">&#x20A6;100,000 /mo</h6>
                                               </div>
                                               <!-- Features -->
                                               <div class="card-body" style="padding:10px 0">
                                                  <ul>
                                                     <li>
                                                        Branding
                                                     </li>
                                                     <li>
                                                       Website Development
                                                     </li>
                                                     <li>
                                                       Mobile Apps Development
                                                     </li>
                                                     <li>
                                                        Digital Marketing
                                                     </li>
                                                     <li>
                                                        Free Accounting Support
                                                     </li>
                                                  </ul>
                                                    <?php if ($userDetails['subscription_plan'] && $userDetails['subscription_status'] == 1): ?>
                                                      <?php if (strtolower($userDetails['subscription_plan']) != 'standard'): ?>
                                                        <a class="btn ui-gradient-purple shadow-md" id="standard">
                                                          Switch
                                                        </a>
                                                      <?php endif; ?>
                                                    <?php else: ?>
                                                      <a class="btn ui-gradient-purple shadow-md" id="standard">
                                                        Get Started
                                                      </a>
                                                    <?php endif; ?>
                                                  </a>
                                               </div>
                                            </div>
                                         </div>
                                         <!-- Card 3 -->
                                            <div class="ui-pricing-card animate" data-show="fade-in-right">
                                            <div class="ui-card ui-curve shadow-lg">
                                               <div class="card-header">
                                                  <!-- Heading -->
                                                  <h4 class="heading custom-flag">Custom</h4>
                                                  <!-- Price -->
                                                  <div class="price custom-flag">
                                                     <span class="curency">  </span>
                                                     <span class="price" id="lets-talk">Let's Talk</span>
                                                  </div>
                                                  <h6 class="sub-heading custom-flag">Per Project</h6>
                                               </div>
                                               <!-- Features -->
                                               <div class="card-body">
                                                  <ul>
                                                     <li>
                                                        Branding
                                                     </li>
                                                     <li>
                                                       Website Development
                                                     </li>
                                                     <li>
                                                       Mobile Apps Development
                                                     </li>
                                                     <li>
                                                        Digital Marketing
                                                     </li>
                                                  </ul>
                                                    <?php if ($userDetails['subscription_plan'] && $userDetails['subscription_status'] == 1): ?>
                                                      <?php if (strtolower($userDetails['subscription_plan']) != 'custom'): ?>
                                                        <a class="btn ui-gradient-green shadow-md" id="custom">
                                                          Switch
                                                        </a>
                                                      <?php endif; ?>
                                                    <?php else: ?>
                                                      <a class="btn ui-gradient-green shadow-md" id="custom">
                                                        Get Started
                                                      </a>
                                                    <?php endif; ?>
                                                  </a>
                                               </div>
                                            </div>
                                         </div>
                                         </div><!-- .ui-pricing-cards -->

                                      </div><!-- .container -->
                                   </div><!-- .section -->








                                <hr>
                                <h4 class="font-bold m-t-30">Current Card Used</h4>


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
            <footer class="footer text-center" style="z-index:9999"> 2017 &copy; The Startup Studio </footer>
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
