<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
	<body>
		<?php if ($is_approve == 1) : ?>
			<p>Dear <?= $application->applicant_name ?>,</p>
			<p>With reference to the Application No: MBMC-00000<?= $application->app_id ?> submitted in the Public Work Department to Mira Bhayander Mahanagarpalika Corporation kindly find the below link for the permission letter.</p>
			<p>Click on the below link for the permission letter</p>
			<p><a href="<?= $permition_latter_url ?>">Permission letter</a></p>
			<p>&nbsp;</p>
			<p>Thanks and Regards,</p>
			<p>PWD Department</p>
			<p>MBMC</p>
			<p>For more information login to the MBMC portal:</p>
			<p><a href="<?= base_url() ?>">MBMC</a></p>
		<?php else : ?>
			<p>Dear <?= $application->applicant_name ?>,</p>
			<p>This is to inform that permission letter with regard to the Application No: MBMC-00000<?= $application->app_id ?> could not be generated as the documents attached could not be verified. You are requested to use the below link for payment.</p>
			<p><a href="<?= base_url('user_authentication/pwd_paymnet_getway?app_id='.$application->app_id) ?>">Payment link</a></p>
			<p>Thanks and Regards,</p>
			<p>PWD Department</p>
			<p>MBMC</p>
			<p>For more information login to the MBMC portal:</p>
			<p><a href="<?= base_url() ?>">MBMC</a></p>
		<?php endif ; ?>
	</body>
</html>

