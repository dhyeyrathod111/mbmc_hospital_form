<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MBMC | Log in</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/fontawesome-free/css/all.min.css">
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
        <link rel="stylesheet" href="<?php echo base_url()?>/assets/dist/css/adminlte.min.css">
        <link rel="stylesheet" href="<?php echo base_url()?>/assets/custom/css/custom.css">
        <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
        <link rel="stylesheet" href="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.css">
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo base_url('login') ?>"><b>MBMC</b> Forgot password</a>
            </div>
            <div class="card">
                <div class="card-header">
                    <img class="logo" src="<?php echo base_url()?>/assets/images/mbmc_offical/logo.png">
                </div>
                <div class="card-body login-card-body">
                    <div id="alert_message"></div>
                    <form id="forgotPasswordForm" class="form" action="<?php echo base_url('user_authentication/forgot_password_process') ?>" method="post" novalidate>
                        <div class="mb-3 form-group">
                            <label for="email_id" class="text-info" placeholder="Email">Email:</label><br>
                            <input type="text" name="email" id="email_id" class="form-control">
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <a href="<?php echo base_url('login') ?>" type="submit" class="btn btn-danger">Back</a>
                                <button type="submit" id="submit_btn" class="btn btn-success float-right">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url()?>/assets/plugins/jquery/jquery.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="<?php echo base_url()?>/assets/dist/js/adminlte.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url()?>/assets/dist/js/validate.js"></script>
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script src="<?php echo base_url()?>/assets/plugins/toastr/toastr.min.js"></script>
        <script type="text/javascript">
            var base_url = "<?php echo base_url()?>";
        </script>
        <script src="<?php echo base_url()?>assets/custom/js/auth.js"></script>
    </body>
</html>