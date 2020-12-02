<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<p>Dear <?= $application->applicant_name ?>,</p>
	<p>Your request for extension of work with reference to Application No: MBMC-00000<?= $application->app_id ?> has been successfully submitted. You will be shortly notified about the approval.</p>
	<p>Thanks and Regards,</p>
	<p>PWD Department</p>
	<p>MBMC</p>
	<p>For more information login to the MBMC portal:</p>
	<p><a href="<?= base_url() ?>">MBMC</a></p>
</body>
</html>