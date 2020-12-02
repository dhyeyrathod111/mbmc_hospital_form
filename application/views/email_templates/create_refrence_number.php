<!DOCTYPE html>
<html>
<head>
	<title>Create refrence number</title>
</head>
<body>

	<?php if ($post_data['is_close'] == 0) : ?>
		<p>Dear <?= $application->applicant_name ?>,</p>
		<p>&nbsp;</p>
		<p>This is to inform that your refrence number has been genrated with regard to the Application No: MBMC-00000<?= $application->app_id ?>.</p>
		<p>Refrence number : <?= $application->reference_no ?></p>
		<p>Joint visit letter : <a href="<?= $file_url ?>">Joint visit</a></p>
		<p>Remark : <?= $post_data['joint_visit_remark'] ?></p>
		<p>&nbsp;</p>
		<p>Thanks and Regards,</p>
		<p>PWD Department</p>
		<p>MBMC</p>
		<p>For more information login to the MBMC portal:</p>
		<p><a href="<?= base_url() ?>">MBMC</a></p>
	<?php else : ?>
		<p>Dear <?= $application->applicant_name ?>,</p>
		<p>&nbsp;</p>
		<p>This is to inform that your joint visit has been completed regard to the Application No: MBMC-00000<?= $application->app_id ?></p>
		<p>Remark : <?= $post_data['joint_visit_remark'] ?></p>
		<p>&nbsp;</p>
		<p>Thanks and Regards,</p>
		<p>PWD Department</p>
		<p>MBMC</p>
		<p>For more information login to the MBMC portal:</p>
		<p><a href="<?= base_url() ?>">MBMC</a></p>
	<?php endif ; ?>
</body>
</html>