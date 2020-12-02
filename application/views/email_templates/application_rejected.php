<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<p>Dear <?= $application->applicant_name ?>,</p>
	<p>This is to inform that your Application No: MBMC-00000<?= $application->app_id ?> has been rejected.</p>
	<p>Reason for Rejection : <?= $remark ; ?></p>
	<p>Thanks and Regards,</p>
	<p>PWD Department</p>
	<p>MBMC</p>
	<p>For more information login to the MBMC portal:</p>
	<p><a href="<?= base_url() ?>">MBMC</a></p>
</body>
</html>