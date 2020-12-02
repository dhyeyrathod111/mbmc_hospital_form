<!DOCTYPE html>
<html>
<head>
	<title>Application rejection</title>
</head>
<body>
	<p>Dear <?= $application->applicant_name ?>,</p>
	<p>&nbsp;</p>
	<p>With reference to the Application No: MBMC-00000<?= $application->app_id ?> submitted in the Public Work Department to Mira Bhayander Mahanagarpalika Corporation kindly Inform You That Your application has been closed.</p>
	<p>&nbsp;</p>
	<p>Thanks and Regards,</p>
	<p>PWD Department</p>
	<p>MBMC</p>
	<p>For more information login to the MBMC portal:</p>
	<p><a href="<?= base_url() ?>">MBMC</a></p>
</body>
</html>