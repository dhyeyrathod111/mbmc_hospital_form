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
        <div class="login-box" style="width: 60%">
            <div class="card">
                <div class="card-header text-center">
                    <h3><b>MBMC</b> Payment process</h3>
                </div>
                <div class="card-body login-card-body">
                    <div id="alert_message"></div>
                    <form id="payment_from" action="<?php echo base_url('payment/clinic_user_payment_process') ?>" method="post" enctype="multipart/form-data">
                        <div class="mb-3 form-group">
                            <label for="email_id" class="text-info" placeholder="Email">Payment Type:</label><br>
                            <select name="payment_method" class="browser-default custom-select">
                                <option value="">---Select payment method---</option>
                                <option disabled value="1">Online</option>
                                <option value="2">Cash</option>
                            </select>
                        </div>

                        <div class="mb-3 form-group">
                            <label for="email_id" class="text-info" placeholder="Email">Amount:</label><br>
                            <input readonly type="text" value="<?= $payment_stack->total_amount ?>" class="form-control" name="payment_amount">
                        </div>

                        <div class="form-group mb-3">
                            <label for="email_id" class="text-info">Document:</label><br>
                            <input type="file" class="form-control" name="payment_document">
                        </div>

                        <input type="hidden" value="<?= $application->app_id ?>" name="app_id">

                        <div class="row">
                            <div class="col-12">
                                <a href="<?php echo base_url('login') ?>" type="submit" class="btn btn-danger">Cancel</a>
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
        <script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
        <script type="text/javascript">

            $("#payment_from").validate({
                rules: {
                    payment_method:{
                       required: true,
                    },
                    payment_amount:{
                        required: true,
                    },
                    payment_document:{
                        required: true,
                    }
                },
                messages:{
                    payment_document:{
                        extension: 'Only PDF are allowed.',
                    }
                },
                submitHandler: function(form) {
                    $('#submit_btn').text('Processing...').prop('disabled', true);
                    var form_data = new FormData(form);
                    $.ajax({
                        type: "POST",
                        url: $(form).attr('action'),
                        data: form_data,processData: false,
                        contentType: false,cache: false,async: false,
                        success: response => {
                            if (response.status == true) {
                                notify_success(response.message);
                                setTimeout(()=>{ window.location.href = response.redirect_url },3000);
                            } else {
                                notify_error(response.message);
                            }
                            $('#submit_btn').text('Submit').prop('disabled', false);
                        },
                        error: response => {
                            console.log(response);notify_error();
                        }
                    });
                },
            });

            var base_url = "<?php echo base_url()?>";

            function notify_success(message) {
                let html_str = '<div class="alert alert-info text-center"><strong>'+ message +'</strong></div>';
                $('#alert_message').fadeIn();
                $('#alert_message').html(html_str).fadeOut(4000);
            }
            function notify_error(message = '') {
                if (message === '') {
                      message = "Sorry, we have to face some technical issues please try again later."
                } 
                let html_str = '<div class="alert alert-warning text-center"><strong>'+ message +'</strong></div>';
                $('#alert_message').fadeIn();
                $('#alert_message').html(html_str).fadeOut(4000);
            }
        </script>

    </body>
</html>
