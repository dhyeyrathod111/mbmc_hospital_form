<!DOCTYPE html>
<html>
<head>
	<title>Demand note</title>
</head>
	<body>
		<p>Dear <?= $application->applicant_name ?>,</p>
		<p>With reference to your Application No: MBMC-00000<?= $application->app_id ?> submitted in the Public Work Department to Mira Bhayander Mahanagarpalika Corporation kindly find the below link for the Demand Notice and the Payment Link.</p>
		<p>&nbsp;</p>
		<p>Click on the below link to view your Demand Notice</p>
		<p><a target="_blank" href="<?= $file_url ?>">Demand note</a></p>
		<p>Click on the below link to make the payment.</p>
		<p><a href="<?= base_url('user_authentication/pwd_paymnet_getway?app_id='.$application->app_id) ?>">Payment link</a></p>
		<p>&nbsp;</p>
		<p>Thanks and Regards,</p>
		<p>PWD Department</p>
		<p>MBMC</p>
		<p>For more information login to the MBMC portal:</p>
		<p><a href="<?= base_url() ?>">MBMC</a></p>
	</body>
</html>
