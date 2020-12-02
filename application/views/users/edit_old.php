<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>MBMC | Registration Page</title>
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
  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">

  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition register-page">
<div class="register-box">
  <div class="register-logo">
    <a href="javascript:void(0)"><b>MBMC</b>&nbsp;User Edit</a>
  </div>

  <div class="card">
    <div class="card-header">
      <img class="logo" src="<?php echo base_url()?>/assets/images/mbmc_offical/logo.png">
    </div>

    <div class="card-body register-card-body">

      <form id="register-form" class="form" method="post" autocomplete="off">
        
        <div class="mb-3 form-group">
          <label for="user_name" class="text-info">User name</label>
          <input type="hidden" name="user_id"  id="user_id" value="<?=($user['user_id']) ? $user['user_id'] : ''?>" class="form-control" placeholder="User name">
          <input type="text" name="user_name"  id="user_name" value="<?=($user['user_name']) ? $user['user_name'] : ''?>" class="form-control" placeholder="User name">
        </div>

        <div class="mb-3 form-group">
          <label for="email_id" class="text-info">Email Id</label>
          <input type="email" name="email_id" id="email_id" value="<?=($user['email_id']) ? $user['email_id'] : ''?>" class="form-control" placeholder="Email Id">
        </div>

        <div class="mb-3 form-group">
          <label for="role_id" class="text-info">Select Role</label>
          <select class="selectpicker form-control" id="role_id" name="role_id" data-live-search="true">
            <option>---Select Role---</option>
            <?php
            // echo'<pre>';print_r($roles);exit;
              foreach ($roles as $key => $val) {
                if($val['role_id'] == $user['role_id']) {
                  $select = "selected='selected'";
                } else {
                  $select = "selected=''";
                }
                // echo'<pre>';print_r($val['role_id']);exit;
                echo '<option value="'.$val['role_id'].'" '.$select.'>'.$val['role_title'].'</option>';
              }
            ?>
          </select>

        </div>

        <div class="mb-3 form-group">
          <label for="dept_id" class="text-info">Select Department</label>
          <select class="selectpicker form-control" id="dept_id" name="dept_id" data-live-search="true">
            <option>---Select Department---</option>
            <?php
            // echo'<pre>';print_r($roles);exit;
              foreach ($department as $key => $dept) {
                if($val['dept_id'] == $user['dept_id']) {
                  $select = "selected='selected'";
                } else {
                  $select = "selected=''";
                }
                // echo'<pre>';print_r($val['role_id']);exit;
                echo '<option value="'.$dept['dept_id'].'" '.$select.'>'.$dept['dept_title'].'</option>';
              }
            ?>
          </select>

        </div>

        <div class="mb-3 form-group">
          <label for="user_mobile" class="text-info">Mobile No</label>
          <input type="number" name="user_mobile" id="user_mobile" value="<?=($user['user_mobile']) ? $user['user_mobile'] : ''?>" class="form-control" placeholder="Mobile No">
        </div>

        <div class="row">
          <div class="col-4">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="1">
              <!-- <label for="agreeTerms">
               I agree to the <a href="javascript:void(0)">terms</a>
              </label> -->
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Edit</button>
          </div>
          <div class="col-4">
            <!-- <button type="submit" class="btn btn-primary btn-block">Edit</button> -->
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- <div class="social-auth-links text-center">
        <p>- OR -</p>
        <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i>
          Sign up using Facebook
        </a>
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-google-plus mr-2"></i>
          Sign up using Google+
        </a>
      </div> -->

      <!-- <a href="login.html" class="text-center">I already have a membership</a> -->
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="<?php echo base_url()?>/assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="<?php echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url()?>/assets/dist/js/adminlte.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
<script src="<?php echo base_url()?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="<?php echo base_url()?>/assets/plugins/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<!-- Toastr -->
<script src="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript">
  var base_url = "<?php echo base_url()?>";
  $('select').selectpicker();
  
</script>
<script src="<?php echo base_url()?>assets/custom/js/custom.js"></script>
</body>
</html>
