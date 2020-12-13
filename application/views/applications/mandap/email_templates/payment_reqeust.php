<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	Reqeust url : <a href="<?= base_url('payment/mandap?app_id='.base64_encode($application->app_id)) ?>">Payment Link</a>
	<br /> 
	Remark : <?= $postdata['description'] ?>
</body>
</html>