<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|  $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route[ 'default_controller' ]  = 'homeController';
$route[ '404_override' ]        = 'myerrorController/not_found';

$route['depositinspection/create'] = 'Deposit_inspection/create';
$route['depositinspection_form_process_create'] = 'Deposit_inspection/create_form_process';
$route['depositinspection'] = 'Deposit_inspection/depositinspection';
$route['depositinspection_datatable'] = 'Deposit_inspection/depositinspection_datatable';
$route['depositinspection/edit/(:any)'] = 'Deposit_inspection/edit';
$route['pwd/extention_process'] = 'PwdController/extention_process';

// $route['ward'] = 'WardController/index';
// $route['ward/create'] = 'WardController/create';
// $route['ward/edit/(:any)'] = 'WardController/edit_ward_from'; 
// $route['ward/get_roles']['post'] = 'WardController/get_roles_by_dept'; 
// $route['ward/create_ward_process']['post'] = 'WardController/create_ward_process';
// $route['word/ward_datatable']['post'] = 'WardController/ward_datatable';
// $route['userdata/get_user_ward']['post'] = 'UsersController/get_ward_by_dept_role';
// $route['ward/update_ward_process'] = 'WardController/update_ward_process';
// $route['ward/delete'] = 'WardController/delete_ward_process';

// $route['pwd/get_company_address'] = 'PwdController/getCompanyAddressByCompID';
// $route['pwd/app_delete_by_user'] = 'PwdController/user_delete_app';

$route['pwd/check_defect_laiblity'] = 'PwdController/check_defect_laiblity';
$route['pwd/check_approve_access'] = 'PwdController/check_approve_access';
$route['user_authentication/pwd_paymnet_getway'] = 'AdminController/pwd_paymnet_getway';
$route['user_authentication/process_payment'] = 'AdminController/process_payment';
$route['pwd/joint_visit_process'] = 'PwdController/joint_visit_process';
$route['pwd/extention_approvel_datafetch'] = 'PwdController/extention_approvel_datafetch';
$route['pwd/extention_approvel_process'] = 'PwdController/extention_approvel_process';
$route['pwd/check_permition_latter_send'] = 'PwdController/check_permition_latter_send';
$route['pwd/payment_verification'] = 'PwdController/payment_verification';
$route['pwd/pv_process'] = 'PwdController/payment_verification_process';
$route['pwd/process_jv_refno'] = 'PwdController/process_jv_refno';
$route['pwd/validate_jv_refno'] = 'PwdController/validate_jv_refno';
$route['pwd/getward'] = 'wardController/getAllWardActive';
$route['pwd/get_old_joint_visit'] = 'PwdController/get_old_joint_visit';
$route['pwd/refrence_order_by_appid'] = 'PwdController/refrence_order_by_appid';
$route['pwd/create_refrence_number'] = 'PwdController/create_refrence_number';
$route['role/vlidate_rolename_create'] = 'RolesController/vlidate_rolename_create';
$route['role/validate_rolename_edit'] = 'RolesController/validate_rolename_edit';
$route['pwd/rejected_application_test'] = 'PwdController/rejected_application_test';
$route['pwd/check_file_close'] = 'PwdController/check_file_close';
$route['pwd/close_application_form'] = 'PwdController/close_application_form';
$route['pwd/close_application_process'] = 'PwdController/close_application_process';
$route['pwd/validation_extention'] = 'PwdController/validation_extention';
$route['pwd/get_user_extention_form'] = 'PwdController/get_user_extention_form';
$route['user_authentication/thankyou_page'] = 'AdminController/thankyou_page';

//new code
$route['update_treecutting_application'] = 'TreeCuttingController/update_treecutting_application';


$route['pwd/testingmailtest'] = 'PwdController/testingmailtest';


$route['hospital/get_application_status'] = 'HospitalController/get_application_status';
$route['hospital/application_type_process'] = 'HospitalController/application_type_process';
$route['hospital/inspection_form_display'] = 'HospitalController/inspection_form_display';
$route['hospital/inspection_form_process'] = 'HospitalController/inspection_form_process';
$route['hospital/inspection_report'] = 'HospitalController/display_inspection_report';
$route['hospital/payment_reqeust_popup'] = 'HospitalController/payment_reqeust_popup';
$route['hospital/payment_request_process'] = 'HospitalController/payment_request_process';
$route['payment/hospital'] = 'HospitalController/user_payment_form';
$route['payment/user_payment_process'] = 'HospitalController/user_payment_process';
$route['hospital/payment_approvel_modal'] = 'HospitalController/payment_approvel_modal';
$route['hospital/payment_approvel_process'] = 'HospitalController/payment_approvel_process';
$route['letter/medical_certificate'] = 'LetterController/medical_certificate';
$route['hospital/user_apps_list'] = 'HospitalController/user_apps_list';
$route['hospital/datatable_userapplist'] = 'HospitalController/datatable_userapplist';


$route['clinic/get_application_status'] = 'ClinicController/get_application_status';
$route['clinic/application_type_process'] = 'ClinicController/application_type_process';
$route['clinic/inspection_form_display'] = 'ClinicController/inspection_form_display';
$route['clinic/inspection_form_process'] = 'ClinicController/inspection_form_process';
$route['clinic/inspection_report'] = 'ClinicController/display_inspection_report';
$route['clinic/payment_reqeust_popup'] = 'ClinicController/payment_reqeust_popup';
$route['clinic/payment_request_process'] = 'ClinicController/payment_request_process';
$route['payment/clinic'] = 'ClinicController/user_payment_form';
$route['payment/clinic_user_payment_process'] = 'ClinicController/user_payment_process';
$route['clinic/payment_approvel_modal'] = 'ClinicController/payment_approvel_modal';
$route['clinic/payment_approvel_process'] = 'ClinicController/payment_approvel_process';
$route['letters/clinic_medical_certificate'] = 'LetterController/medical_certificate_for_clinic';
$route['clinic/user_apps_list'] = 'ClinicController/user_apps_list';
$route['clinic/datatable_userapplist'] = 'ClinicController/datatable_userapplist';



$route['lab/get_application_status'] = 'LabController/get_application_status';
$route['lab/application_type_process'] = 'LabController/application_type_process';
$route['lab/inspection_form_display'] = 'LabController/inspection_form_display';
$route['lab/inspection_form_process'] = 'LabController/inspection_form_process';
$route['lab/inspection_report'] = 'LabController/display_inspection_report';
$route['lab/payment_reqeust_popup'] = 'LabController/payment_reqeust_popup';
$route['lab/payment_request_process'] = 'LabController/payment_request_process';
$route['payment/lab'] = 'LabController/user_payment_form';
$route['payment/lab_user_payment_process'] = 'LabController/user_payment_process';
$route['lab/payment_approvel_modal'] = 'LabController/payment_approvel_modal';
$route['lab/payment_approvel_process'] = 'LabController/payment_approvel_process';
$route['letters/lab_medical_certificate'] = 'LetterController/medical_certificate_for_lab';
$route['lab/user_apps_list'] = 'LabController/user_apps_list';
$route['lab/datatable_userapplist'] = 'LabController/datatable_userapplist';
 
require_once( BASEPATH .'database/DB'. EXT );

$db =& DB();

$query = $db->select('slug,sub_slug,controller,method')
			->where(array('status'=>'1','is_deleted'=>'0'))->get( 'app_routes' );
$result = $query->result();

foreach( $result as $row ) {

	if($row->sub_slug !='') {

		if($row->slug == 'edit') {
			$route[ $row->sub_slug.'/'.$row->slug.'/(:any)' ] = $row->controller.'/'.$row->method.'/$1';
		} elseif($row->slug == 'checklist') {
			$route[ $row->sub_slug.'/'.$row->slug.'/(:any)/(:any)' ] = $row->controller.'/'.$row->method.'/$1/$2';
		} elseif(in_array($row->slug , array('permission_letter', 'extension_letter', 'jointvisit_letter'))) {
			$route[ $row->sub_slug.'/'.$row->slug.'/(:any)/(:any)' ] = $row->controller.'/'.$row->method.'/$1/$2';
		} elseif($row->slug == 'edits') {
		    $route[ $row->sub_slug.'/'.$row->slug.'/(:any)' ] = $row->controller.'/'.$row->method.'/$1';
		} elseif($row->slug == 'editss') {
		    $route[ $row->sub_slug.'/'.$row->slug.'/(:any)' ] = $row->controller.'/'.$row->method.'/$1';
		} else {
			$route[ $row->sub_slug.'/'.$row->slug] = $row->controller.'/'.$row->method;
		}
	
	} else {
		$route[$row->slug] = $row->controller.'/'.$row->method;
	}
    
    // $route[ $row->slug.'/:any' ]         = $row->controller;
    // $route[ $row->controller ]           = 'myerrorController/access_denied';
    // $route[ $row->controller.'/:any' ]   = 'myerrorController/access_denied';
}

?>