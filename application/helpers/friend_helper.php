<?php 

function file_upload_config()
{
	$config['upload_path']          = './uploads/marriage/';
    $config['allowed_types']        = 'gif|jpg|png|jpeg|webp';
    $config['encrypt_name']            = TRUE;
    return $config;
}

function pwd_document_upload()
{
	$config['upload_path']          = './uploads/pwd/';
	$config['allowed_types']        = 'pdf';
	$config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}

function pwd_payment_document_config()
{
	$config['upload_path']          = './uploads/pwd/payment_document/';
    $config['allowed_types']        = 'pdf';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}

function hospital_payment_document_config()
{
	$config['upload_path']          = './uploads/hospital/payment_docs/';
    $config['allowed_types']        = '*';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}

function clinic_document_config()
{
	$config['upload_path']          = './uploads/clinic/';
    $config['allowed_types']        = '*';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}

function clinic_payment_document_config()
{
	$config['upload_path']          = './uploads/clinic/payment_docs/';
    $config['allowed_types']        = '*';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}

function lab_payment_document_config()
{
	$config['upload_path']          = './uploads/lab/payment_docs/';
    $config['allowed_types']        = '*';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}

function reconfig_file_structure($file_data,$key)
{

	if (!empty($file_data['name'][$key])) {
		$oneTreeImage['name'] = $file_data['name'][$key];
		$oneTreeImage['type'] = $file_data['type'][$key];
		$oneTreeImage['tmp_name'] = $file_data['tmp_name'][$key];
		$oneTreeImage['error'] = $file_data['error'][$key];
		$oneTreeImage['size'] = $file_data['size'][$key];
		return $oneTreeImage ;
	} else {
		return FALSE ;
	}
}
function get_tablename_by_deptid($user_department = [], $user_data = [])
{
	$tableArrayExceptMedical = array("pwd_applications", "treecuttingapplications", "trade_faclicapplication", "godownapplication", "filmdata", "temp_lic", "advertisement_applications", "mandap_applications","hall_applications");
	switch (strtolower($user_department->dept_title)) {
		case 'garden':
			$user_department->dept_title = 'tree';break;
		case 'PWD':
			$user_department->dept_title = 'pwd';break;
	}
	$getTableName = preg_grep("/^".strtolower($user_department->dept_title)."/i", $tableArrayExceptMedical);
	return trim(array_values($getTableName)[0]);
}
function get_ri_chargers($surface_rate,$total_length,$mul_factor)
{
	return $surface_rate*$total_length*$mul_factor;
}
function get_supervision_charges($ri_chargers)
{
	return (15 / 100) * $ri_chargers;
}
function get_land_rant($total_length)
{
	return $total_length*200;
}
function get_total_ri_charges($ri_chargers , $supervision_charges , $land_rant)
{
	return $ri_chargers+$supervision_charges+$land_rant;
}
function get_security_deposit($ri_chargers)
{
	return (10 / 100) * $ri_chargers;
}
function get_sgst($total_ri_charges)
{
	return (9 / 100) * $total_ri_charges;
}
function get_cgst($total_ri_charges)
{
	return (9 / 100) * $total_ri_charges;
}
function get_total_gst($sgst , $cgst)
{
	return $sgst+$cgst;
}
function hospital_document_config()
{
	$config['upload_path']          = './uploads/hospital/';
    $config['allowed_types']        = '*';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}
function lab_document_config()
{
	$config['upload_path']          = './uploads/lab/';
    $config['allowed_types']        = '*';
    $config['max_size']        		= '5000';
    $config['encrypt_name']         = TRUE;
    return $config;
}
function hospital_payment_calculation($number_of_beds,$appliaction_type)
{

	// 1 = new application , 2 = renual application.

	$payment_array = array();
	if ($number_of_beds <= 5) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 1000 : 500;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} elseif ($number_of_beds >= 6 && $number_of_beds <= 10) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 1500 : 750;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} elseif ($number_of_beds >= 11 && $number_of_beds <= 15) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 2000 : 1000 ;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} else if ($number_of_beds >= 16) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 5000 : 2500;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} else {
		$payment_array['amount'] = ($appliaction_type == 1) ? 0 : 1;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	}
	return json_decode(json_encode($payment_array));
}
function lab_payment_calculation($number_of_beds,$appliaction_type)
{

	// 1 = new application , 2 = renual application.

	$payment_array = array();
	if ($number_of_beds <= 5) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 1000 : 500;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} elseif ($number_of_beds >= 6 && $number_of_beds <= 10) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 1500 : 750;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} elseif ($number_of_beds >= 11 && $number_of_beds <= 15) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 2000 : 1000 ;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} else if ($number_of_beds >= 16) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 5000 : 2500;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} else {
		$payment_array['amount'] = ($appliaction_type == 1) ? 0 : 1;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	}
	return json_decode(json_encode($payment_array));
}
function clinic_payment_calculation($number_of_beds,$appliaction_type)
{

	// 1 = new application , 2 = renual application.

	$payment_array = array();
	if ($number_of_beds <= 5) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 1000 : 500;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} elseif ($number_of_beds >= 6 && $number_of_beds <= 10) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 1500 : 750;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} elseif ($number_of_beds >= 11 && $number_of_beds <= 15) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 2000 : 1000 ;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} else if ($number_of_beds >= 16) {
		$payment_array['amount'] = ($appliaction_type == 1) ? 5000 : 2500;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	} else {
		$payment_array['amount'] = ($appliaction_type == 1) ? 0 : 1;
		$payment_array['form_chargis'] = 100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];
	}
	return json_decode(json_encode($payment_array));
}