		$.validator.addMethod("customEmail", function (value, element) {
	        return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/i.test(value);
	    }, "Please enter valid email address!");

        $.validator.addMethod("password_format", function(value, element) {
            return this.optional(element) || /[A-Z]/.test(value) && /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{1,100}$/i.test(value);
        }, "Password should contain atleast 1 Capital letter ,Small letter,one special character And Number");


        $("#changetPasswordForm").validate({
            rules: {
                keygen:{
                    required:true,
                    remote: {
                        url: base_url + 'user_authentication/validate_keygen',
                        type: "post",
                    }
                },
                password:{
                    required: true,
                    minlength: 8,
                    password_format:true
                },
                confirm_password:{
                    required: true,
                    minlength: 8,
                    equalTo: "#password",
                },
            },
            messages:{
                keygen:{
                    remote:"Please enter valid access key.",
                }
            },
            submitHandler: function(form) {
                var form_data = JSON.stringify($(form).serializeArray());
                $.ajax({
                    type: "POST",
                    url: $(form).attr('action'),
                    data: JSON.parse(form_data),
                    success: response => {
                        if (response.status == true) {
                            notify_success(response.message);
                            setTimeout(()=>{ 
                                window.location.href = response.redirect_url;
                            }, 3000);
                        } else {
                            notify_error(response.message);
                        }
                    },
                    error: response => {
                        console.log(response);notify_error();
                    }
                });
            },
        });


		$("#forgotPasswordForm").validate({
            rules: {
                email: {
                    required: true,
                    customEmail: true,
                },
            },
            messages:{
                email: {
                    required: "Email ID is required",
                },  
            },
            submitHandler: function(form) {
                var form_data = JSON.stringify($(form).serializeArray());
                $('#submit_btn').text('Sending...').prop('disabled', true);
                $.ajax({
                    type: "POST",
                    url: $(form).attr('action'),
                    data: JSON.parse(form_data),
                    success: response => {
                        $('#submit_btn').text('Submit').prop('disabled', false);
                        if (response.status == true) {
                            notify_success(response.message);
                            setTimeout(()=>{ 
                                window.location.href = response.redirect_url;
                            }, 3000);
                        } else {
                            notify_error(response.message);
                        }
                    },
                    error: response => {
                        console.log(response);notify_error();
                        $('#submit_btn').text('Submit').prop('disabled', false);
                    }
                });
            },
        });

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