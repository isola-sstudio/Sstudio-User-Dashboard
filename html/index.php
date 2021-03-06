<?php
ini_set( "display_errors", 0);
  session_start();
  ob_start(null, 4096);

  require_once __DIR__ .'/vendor/autoload.php';

  use Libs\AdminUser\AdminUser;

  if (!isset($adminUser)) {
    # let's create an admin user object
    $adminUser = new AdminUser();
  }
  if ($adminUser->loggedIn()) {
    # this admin user is not logged in, so, redirect
    header('Location: dashboard.php');
  }
?>

<?php
  //used for the login operation
  require_once __DIR__ . '/utils/login_admin_user.php';
  //used for fb signup and login
  require_once __DIR__ . '/includes/fblogin.php';
  //used for the signup operation
  require_once __DIR__ . '/utils/create_admin_user.php';
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
    <title>Startup Studio Admin - Power Your Startup</title>
    <!-- Bootstrap Core CSS -->
    <link href="bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
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

<body>
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>
    <section id="wrapper" class="new-login-register" style="background: linear-gradient(45deg, #3f1caa 0%, #3c56e1 100%);">
        <div class="inner-panel" style="text-align:center;">
          <a href="javascript:void(0)" class="p-20 di"><img src="../plugins/images/admin-logo.png" style="width:40%;"></a>
        </div>
        <div class="new-login-box">
            <div class="white-box">
                <h3 class="box-title m-b-0" id="forms-head-title">Sign into your Account</h3>
                <small>Let's get you started</small>
                <form class="form-horizontal new-lg-form" id="loginform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                  <small class="text-danger"><?php if (isset($loginError)) { echo $loginError; } ?></small>
                    <div class="form-group  m-t-20">
                        <div class="col-xs-12">
                            <!-- <label>Email Address</label> -->
                            <input class="form-control" type="email" name="email" required="" placeholder="you@company.com" autofocus>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <!-- <label>Password</label> -->
                            <input class="form-control" type="password" name="password" required="" placeholder="Password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-info pull-left p-t-0">
                                <input id="checkbox-signup" type="checkbox">
                                <label for="checkbox-signup"> Remember me </label>
                            </div>
                            <a href="javascript:void(0)" id="to-recover" class="text-dark pull-right"><i class="fa fa-lock m-r-5"></i> Forgot Password?</a> </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-lg btn-block text-uppercase ui-gradient-green" type="submit" name="login">Log In</button>
                        </div>
                    </div>
                    <div class="row" style="text-align:center;">
                      Log in with Facebook
                        <div class="col-xs-12 col-sm-12 col-md-12 m-t-10 text-center">
                            <div class="social">
                                <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="btn  btn-facebook" data-toggle="tooltip" title="Log in with Facebook" style="border-radius:3px;"> <i aria-hidden="true" class="fa fa-facebook"></i> </a>
                            </div>
                        </div>
                        <div style="text-align:center;">
                          <a href="javascript:void(0)" id="to-signup" class="text-dark">Create an Account</a> </div>
                        </div>

                </form>
                <form class="form-horizontal" id="recoverform" action="index.html">
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3>Recover Password</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset</button>
                        </div>
                    </div>
                </form>


                <form class="form-horizontal new-lg-form" id="signupform" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
                  <small id="signup-error-span" class="text-danger"><?php if (isset($signupError)) { echo $signupError; } ?></small>
                    <div class="form-group  m-t-20">
                        <div class="col-xs-12">
                            <!-- <label>Email</label> -->
                            <input class="form-control" type="email" name="email" required="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group  m-t-20">
                        <div class="col-xs-12">
                            <!-- <label>Password</label> -->
                            <input class="form-control" type="password" name="password" required="" placeholder="Password">
                            <input type="hidden" name="subscribe" value="<?php if(isset($_GET['subscribe'])){echo $_GET['subscribe']; }?>">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-lg btn-block text-uppercase ui-gradient-peach" type="submit" name="signup">Signup</button>
                        </div>
                    </div><br><br><br>
                    <small>Join with Facebook</small>
                    <a href="<?php echo htmlspecialchars($loginUrl); ?>" class="btn  btn-facebook" data-toggle="tooltip" title="Signup with Facebook" style="border-radius:3px;"> <i aria-hidden="true" class="fa fa-facebook"></i> </a>
                </form>

              </div>

            </div>








    </section>
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
