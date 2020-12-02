<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MBMC | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/custom/css/custom.css">
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html"><b>MBMC</b>Login</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-header">
      <img class="logo" src="<?php echo base_url()?>/assets/images/mbmc_offical/logo.png">
    </div>
    <div class="card-body login-card-body">
      <!-- <p class="login-box-msg">Sign in to start your session</p> -->

      <form id="login-form" class="form" method="post" novalidate>
        <div class="mb-3 form-group">
            <label for="email_id" class="text-info" placeholder="Email">Email</label><br>
            <input type="text" name="email_id" id="email_id"  class="form-control" value = "<?php if(get_cookie('email') != ''){ echo get_cookie('email'); }else{ echo ''; } ?>">
        </div>
        <div class="mb-3 form-group">
            <label for="password" class="text-info">Password</label><br>
            <input type="password" name="password" id="password" placeholder="Password"  class="form-control" value = "<?php if(get_cookie('password') != ''){ echo get_cookie('password'); }else{ echo ''; } ?>">
        </div>
        <!-- <div class="input-group mb-3">
          <input type="text" name="username" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div> -->
        <div class = "row justify-content-center">
            <div class = "form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="loginType" value = '1' checked>User
                </label>
            </div>
            <div class = "form-check-inline">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" name="loginType" value = '0'>Admin
                </label>
            </div>
        </div>
        <div class = "row">
            <p class = "error_msg text-center" style = "display:none"></p>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" name = "remember" id="remember" <?php if(get_cookie('user_id') != ''){ echo "checked"; } ?>>
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <div class="social-auth-links text-center mb-3">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i> Sign in using Google+
        </a>
      </div> -->
      <!-- /.social-auth-links -->

      <!--<p class="mb-1">-->
      <!--  <a href=" ">I forgot my password</a>-->
      <!--</p>-->
      <br>

      <p class="mb-1"> 
        <!--<a href=" " style = "font-weight: bold !important;">I forgot my password</a>-->
		<a href = "<?= base_url().'register' ?>" class = "float-right btn btn-danger" style = "font-weight: bold !important;">New Account</a>
    <a class="btn btn-warning" href="<?php echo base_url('user_authentication/forgot_password') ?>">I forgot my password</a> 
      </p>
      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url()?>/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>/assets/dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<!-- <script src="<?php echo base_url()?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script> -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">

  var base_url = "<?php echo base_url()?>";
</script>
<script src="<?php echo base_url()?>assets/custom/js/custom.js" id = "usersPage" is_type = "login"></script>

</body>
</html>
