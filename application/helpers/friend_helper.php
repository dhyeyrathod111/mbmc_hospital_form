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

function getDiffrentBetweenTwoDatesInMonth($applications_expiry_date , $currrent_date)
{
	$date1 = $applications_expiry_date;
	$date2 = $currrent_date;
	$ts1 = strtotime($date1);
	$ts2 = strtotime($date2);
	$year1 = date('Y', $ts1);
	$year2 = date('Y', $ts2);
	$month1 = date('m', $ts1);
	$month2 = date('m', $ts2);
	return (($year2 - $year1) * 12) + ($month2 - $month1);
}
function hospital_payment_calculation($number_of_beds,$application)
{
	// 1 = new application , 2 = renual application.

	if ($application->application_type == 2) $peneltyOnTotalMonth = getDiffrentBetweenTwoDatesInMonth($application->date_of_expiry_certificate,date('Y-m-d'));

	$payment_array = array();
	if ($number_of_beds <= 5) {
		$payment_array['amount'] = ($application->application_type == 1) ? 1000*2 : 500*3 + $peneltyOnTotalMonth*100 ;
		$payment_array['form_chargis'] = ($application->application_type == 1) ? 200 : 100 ;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];

	} elseif ($number_of_beds >= 6 && $number_of_beds <= 10) {
		$payment_array['amount'] = ($application->application_type == 1) ? 1500*2 : 750*3 + $peneltyOnTotalMonth*100;
		$payment_array['form_chargis'] = ($application->application_type == 1) ? 200 : 100 ;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];

	} elseif ($number_of_beds >= 11 && $number_of_beds <= 15) {
		$payment_array['amount'] = ($application->application_type == 1) ? 2000*2 : 1000*3 + $peneltyOnTotalMonth*100;
		$payment_array['form_chargis'] = ($application->application_type == 1) ? 200 : 100 ;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];

	} else if ($number_of_beds >= 16) {
		$payment_array['amount'] = ($application->application_type == 1) ? 5000*2 : 2500*3 + $peneltyOnTotalMonth*100;
		$payment_array['form_chargis'] = ($application->application_type == 1) ? 200 : 100 ;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'];

	} else {
		$payment_array['amount'] = ($application->application_type == 1) ? 0*3 : 1*3 + $peneltyOnTotalMonth*100;
		$payment_array['form_chargis'] = ($application->application_type == 1) ? 200 : 100 ;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['amount'] + $beds_renewal_charges;
	}
	return json_decode(json_encode($payment_array));
}
function clinic_payment_calculation($appliaction)
{
	// 1 = new application , 2 = renual application.
	if ($appliaction->application_type == 1) {
		$payment_array['form_chargis'] = 200;
		$payment_array['registration_chargis'] = 500;
		$payment_array['renual_charges'] = 250*2;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['registration_chargis'] + $payment_array['renual_charges'];
	} else {
		$peneltyOnTotalMonth = getDiffrentBetweenTwoDatesInMonth($appliaction->date_of_expiry_certificate,date('Y-m-d'));
		$payment_array['form_chargis'] = 100;
		$payment_array['registration_chargis'] = 250*3 + $peneltyOnTotalMonth*100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['registration_chargis'];
	}
	return json_decode(json_encode($payment_array));
}
function lab_payment_calculation($appliaction)
{
	// 1 = new application , 2 = renual application.
	if ($appliaction->application_type == 1) {
		$payment_array['form_chargis'] = 200;
		$payment_array['registration_chargis'] = 500;
		$payment_array['renual_charges'] = 250*2;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['registration_chargis'] + $payment_array['renual_charges'];
	} else {
		$peneltyOnTotalMonth = getDiffrentBetweenTwoDatesInMonth($appliaction->date_of_expiry_certificate,date('Y-m-d'));
		$payment_array['form_chargis'] = 100;
		$payment_array['registration_chargis'] = 250*3 + $peneltyOnTotalMonth*100;
		$payment_array['total_amount'] = $payment_array['form_chargis'] + $payment_array['registration_chargis'];
	}
	return json_decode(json_encode($payment_array));
}

function image_formate_in_array($application_images , $application)
{

	if (!empty($application_images)) {
		foreach ($application_images as  $oneImage) :
			if ($oneImage->image_id == $application->ownership_agreement) {
				$final_iamge_array['ownership_agreement'] = $oneImage;
			} else if ($oneImage->image_id == $application->tax_receipt) {
				$final_iamge_array['tax_receipt'] = $oneImage;
			} else if ($oneImage->image_id == $application->doc_certificate) {
				$final_iamge_array['doc_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->reg_certificate) {
				$final_iamge_array['reg_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->staff_certificate) {
				$final_iamge_array['staff_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->nursing_staff_deg_certificate) {
				$final_iamge_array['nursing_staff_deg_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->nursing_staff_reg_certificate) {
				$final_iamge_array['nursing_staff_reg_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->bio_des_certificate) {
				$final_iamge_array['bio_des_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->society_noc) {
				$final_iamge_array['society_noc'] = $oneImage;
			} else if ($oneImage->image_id == $application->fire_noc) {
				$final_iamge_array['fire_noc'] = $oneImage;
			}
		endforeach;
		return json_decode(json_encode($final_iamge_array));
	} else {
		return FALSE;
	}
}
function image_formate_in_array_clinic($application_images , $application)
{
	if (!empty($application_images)) {
		foreach ($application_images as  $oneImage) :
			if ($oneImage->image_id == $application->ownership_agreement) {
				$final_iamge_array['ownership_agreement'] = $oneImage;
			} else if ($oneImage->image_id == $application->tax_receipt) {
				$final_iamge_array['tax_receipt'] = $oneImage;
			} else if ($oneImage->image_id == $application->doc_certificate) {
				$final_iamge_array['doc_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->aadhaar_card) {
				$final_iamge_array['aadhaar_card'] = $oneImage;
			} else if ($oneImage->image_id == $application->bio_medical_certificate) {
				$final_iamge_array['bio_medical_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->user_image) {
				$final_iamge_array['user_image'] = $oneImage;
			} 
		endforeach;
		return json_decode(json_encode($final_iamge_array));
	} else {
		return FALSE;
	}
}
function image_formate_in_array_lab($application_images , $application)
{
	if (count($application_images) > 0) {
		foreach ($application_images as  $oneImage) :
			if ($oneImage->image_id == $application->ownership_agreement) {
				$final_iamge_array['ownership_agreement'] = $oneImage;
			} else if ($oneImage->image_id == $application->tax_receipt) {
				$final_iamge_array['tax_receipt'] = $oneImage;
			} else if ($oneImage->image_id == $application->doc_certificate) {
				$final_iamge_array['doc_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->aadhaar_card) {
				$final_iamge_array['aadhaar_card'] = $oneImage;
			} else if ($oneImage->image_id == $application->bio_medical_certificate) {
				$final_iamge_array['bio_medical_certificate'] = $oneImage;
			} else if ($oneImage->image_id == $application->user_image) {
				$final_iamge_array['user_image'] = $oneImage;
			} 
		endforeach;
		return json_decode(json_encode($final_iamge_array));
	} else {
		return FALSE;
	}
}