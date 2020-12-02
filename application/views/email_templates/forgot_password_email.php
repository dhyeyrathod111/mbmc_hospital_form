<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Forgot Password</title>
    <style>
        body { background-color: #FFFFFF; padding: 0; margin: 0; }
    </style>
</head>
<body style="background-color: #FFFFFF; padding: 0; margin: 0;">
	<table border="0" cellpadding="0" cellspacing="10" height="100%" bgcolor="#FFFFFF" width="100%" style="max-width: 650px;" id="bodyTable">
	    <tr>
	        <td align="center" valign="top">
	            <table border="0" cellpadding="0" cellspacing="0" width="100%" id="emailContainer" style="font-family:Arial; color: #333333;">
	                <tr>
	                    <td align="left" valign="top" colspan="2" style="border-bottom: 1px solid #CCCCCC; padding: 20px 0 10px 0;">
	                        <span style="font-size: 18px; font-weight: normal;">FORGOT PASSWORD</span>
	                    </td>
	                </tr>
	                <tr>
	                    <td align="left" valign="top" colspan="2" style="padding-top: 10px;">
	                        <span style="font-size: 12px; line-height: 1.5; color: #333333;">
	                            <p>Hello <?php echo $user_info->user_name ?> , We have sent you this email in response to your request to reset your password on <?php echo SITE_NAME ?>. go to this link and enter access key given below and chnage your password.</p>
	                            <p>
	                            	<b>Your password reset url <b/>: <a href="<?php echo base_url('user_authentication/reset_password') ?>"><?php echo base_url('user_authentication/reset_password') ?></a>
	                            	<br />
									<b>Your access key :</b> <?php echo $user_keygen ?>
	                            </p>
	                            <p>We recommend that you keep your password secure and not share it with anyone.If you feel your password has been compromised, you can change it by going to your $<?php echo SITE_NAME ?> My Account Page and clicking on the "Change Email Address or Password" link.</p>
	                            <p>If you need help, or you have any other questions, feel free to email info@mbmc.com , or call <?php echo SITE_NAME ?> customer service.</p>
	                            <p><?php echo SITE_NAME ?> Customer Service</p>
	                        </span>
	                    </td>
	                </tr>
	            </table>
	        </td>
	    </tr>
	</table>
</body>
</html>