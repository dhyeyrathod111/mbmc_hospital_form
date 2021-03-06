<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class ClinicController extends Common {

	public $response ;
	public $data ;

	public function __construct()
	{
		parent::__construct();
		$this->response = [];$this->data = [];
	}
	public function index()	{
		$dept_id = $this->get_dept_id_by_name('Medical');
		$this->data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
		$this->load->view("applications/clinic/index",$this->data);
	}
	public function create() {

		$this->data['app_id'] = $this->get_last_app_id();
		$this->data['designation'] = $this->designation_master_table->getAllDesignation();
		$this->data['qualification'] = $this->qualification_master_table->getAllQualification();
		$this->data['application_type'] = $this->session->flashdata('applications_type');
		if ($this->session->flashdata('applications_type')) {
			$this->load->view('applications/clinic/create',$this->data);
		} else {
			$this->load->view('applications/clinic/clinic_landing_page',$this->data);
		}
	}
	public function edit() {

		$clinic_id = base64_decode($this->uri->segment(3));
		$clinic_details = $this->clinic_applications_table->getApplicationByID($clinic_id);
		if (!empty($clinic_details)) {
			$application_images = $this->clinic_applications_table->getImageByApplication($clinic_details);
			$this->data['application'] = $clinic_details;
			$this->data['clinic_staff'] = $this->clinic_applications_table->getClinicStaffByAppID($clinic_id);
			$this->data['appimages'] = image_formate_in_array_clinic($application_images,$clinic_details);
			$this->data['designation'] = $this->designation_master_table->getAllDesignation();
			$this->data['qualification'] = $this->qualification_master_table->getAllQualification();
			$this->load->view('applications/clinic/edit',$this->data);
		} else {
			return redirect('clinic/user_apps_list');
		}
	}
	public function get_lists()	{

		$data = $row = array();
		$session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];
        $clinicList = $this->clinic_applications_table->getclinicsDataForDatatable(FALSE,$_POST);
        $clinicCount = $this->clinic_applications_table->getclinicsDataForDatatable(TRUE,$_POST);
        $i = $_POST['start'];


        $roleStackHealthOfficer = $this->clinic_applications_table->getRoleByName('health officer');
        $roleStacClerk = $this->clinic_applications_table->getRoleByName('Clerk');


        foreach($clinicList as $clinic){
            $i++;
            $id = $clinic['id'];

            if($clinic['app_id'] != null) {
              	$app_val = 'MBMC-00000'.$clinic['app_id'];
              	$app_no = $app_val;
          	} else {
            	$app_no = 'MBMC-000001';
          	}

            $application_no = $app_no;
            $applicant_name = $clinic['applicant_name'];
            $applicant_email_id = $clinic['applicant_email_id'];
            $applicant_mobile_no = $clinic['applicant_mobile_no'];
            // $applicant_nationality = $clinic['applicant_nationality'];
            // $technical_qualification = $clinic['technical_qualification'];
            // $un_reg_medical_practice = ($clinic['un_reg_medical_practice'] == '1') ? 'Yes' : 'NO';
            // $applicant_address = $clinic['applicant_address'];
            $clinic_name = $clinic['clinic_name'];
            $clinic_address = $clinic['clinic_address'];
			// $contact_no = $clinic['contact_no'];
            // $contact_person = $clinic['contact_person'];
            // $floor_space = $clinic['floor_space'];
            // $maternity_beds = $clinic['maternity_beds'];
            // $patient_beds = $clinic['patient_beds'];
            $status = $clinic['status'];

            $dept_id = $this->applications_details_table->getDeptId($clinic['app_id'])['dept_id'];
            if ($roleStackHealthOfficer->role_id == $this->authorised_user['role_id']) {
            	$inspection_form_btn = '<a href="javascript:void(0)" app_id="'.$clinic['app_id'].'" aria-label="Inspection form" data-microtip-position="top" role="tooltip" class="btn btn-danger inspection_form_btn"><i app_id="'.$clinic['app_id'].'" class="fa fa-indent inspection_form_btn" aria-hidden="true"></i></a>';
            } else {
            	$inspection_form_btn = '';
            }

        	if ($clinic['clinic_inspection_done'] != 0) {
        		$inspection_form = '<a target="_blank" href="'.base_url('clinic/inspection_report?id='.base64_encode($clinic['app_id'])).'" type="button" class="btn btn-primary btn-sm">Report</a>';
        	} else {
        		$inspection_form = '-';
        	}

        	if ($roleStacClerk->role_id == $this->authorised_user['role_id'] && $clinic['clinic_inspection_done'] != 0 && $clinic['payment_status'] == '') {
        		$payment_btn = '<a type="button" aria-label="Payment Request" data-microtip-position="top" role="tooltip" app_id="'.$clinic['app_id'].'" class="btn btn-warning payment_request_btn"><i class="fas fa-money-bill-wave payment_request_btn" app_id="'.$clinic['app_id'].'"></i></a>';
        	} else if($roleStacClerk->role_id == $this->authorised_user['role_id'] && $clinic['payment_status'] == 4) {	
        		$payment_btn = '<a type="button" aria-label="Payment Approval" data-microtip-position="top" role="tooltip" app_id="'.$clinic['app_id'].'" class="btn btn-warning payment_approvel_btn"><i class="fas fa-check payment_approvel_btn" app_id="'.$clinic['app_id'].'"></i></a>';
        	} else {
        		$payment_btn = '';
        	}

            $sub_dept_id = $this->sub_department_table->getSubDepartmentByName('Clinic');
            $status_details = $this->App_status_level_table->getAllStatusById($status);
            if($status_details != null) {
            	$status_type = $status_details[0]['status_type'];
            	$status_title = $status_details[0]['status_title'];	
            	if($status_type == '1') {
	            	$class = "btn-danger white";
	            } elseif($status == '2') {
	            	$class = "btn-success white";
	            } 

	            $status ='<a type="button" data-toggle="modal"  data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" class="status_button btn btn-sm btn-danger white">'.$status_title.'</a>';
            } else {
            	$status ='<a type="button" data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" class="btn btn-danger btn-sm status_button white">Awaiting</a>';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';

            $action = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$clinic['ownership_agreement'].','.$clinic['tax_receipt'].','.$clinic['doc_certificate'].','.$clinic['bio_medical_certificate'].','.$clinic['doc_certificate'].'" data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'"  data-target="#modal-doc" class="doc_button anchor "><i class=" nav-icon fas fa-file"></i></a>
            	<a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'clinic/edit/'.base64_encode($id).'" class="anchor nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>'.$inspection_form_btn.' '.$payment_btn;
            $documents = '';

            $applicant_date = date("d-m-Y", strtotime($clinic['created_at']));

            $data[] = array($i,$application_no, $applicant_name, $applicant_email_id, $applicant_mobile_no,$clinic_name,$remarks,$status,$inspection_form,$applicant_date,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $clinicCount,
            "recordsFiltered" => $clinicCount,
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function add_remarks() {
		extract($_POST);
		$remarks_check = $this->form_validation
                            ->set_rules('remarks','remarks','required')->run();
        $status_check = $this->form_validation
                            ->set_rules('status','status','required')->run();
        if(!$remarks_check) {
        	$data['status'] = '2';
        	$data['messg'] = 'Please provide the remarks.';
        } else {

    	 	$session_userdata = $this->session->userdata('user_session');
	        $user_id = $session_userdata[0]['user_id'];
	        $role_id = $session_userdata[0]['role_id'];
        	$insert_data = array(
        		'app_id' => $app_id,
        		'dept_id' => $dept_id,
        		'sub_dept_id' => $sub_dept_id,
        		'user_id' => $user_id,
                'role_id' => $role_id,
        		'remarks'=> $remarks, 
                'status_id' => $status,
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            );
			$result = $this->application_remarks_table->insert($insert_data);
			if($result != null) {

				$update_data = array(
					'status' => $status,
					'updated_at' => date('Y-m-d H:i:s')
				);

				$roleStack = $this->clinic_applications_table->getRoleByName('Clerk');
				$roleSeniorDoctorStack = $this->clinic_applications_table->getRoleByName('senior doctor');

				if (!empty($this->input->post('health_officers')) && $this->authorised_user['role_id'] == $roleStack->role_id) {
					$update_data['health_officer'] = $this->input->post('health_officers');
				}

				if ($roleSeniorDoctorStack->role_id == $this->authorised_user['role_id']) {
					$this->data['application'] = $this->clinic_applications_table->getApplicationByAppID($app_id);
					$this->data['certificate_url'] = base_url('letters/clinic_medical_certificate?app_id='.base64_encode($this->data['application']->app_id));
					$html_str = $this->load->view('applications/clinic/email_templates/medical_cirtificate_email',$this->data,TRUE);
					$email_stack = array(
						'to' => $this->data['application']->applicant_email_id,'body' => $html_str,'subject' => "Application MBMC-00000".$this->data['application']->app_id." medical cirtificate.",
					);
					$this->email_trigger->codeigniter_mail($email_stack);
				}


				$final_result = $this->clinic_applications_table->update($update_data,$app_id);

				if($final_result != null) {
					$data['status'] = '1';
        			$data['messg'] = 'Remark updated successfully.';

				} else {
					$data['status'] = '2';
        			$data['messg'] = 'Oops! Something went wrong.';
				}
			} else {
				$data['status'] = '2';
        		$data['messg'] = 'Oops! Something went wrong.';

			}
        }
        echo json_encode($data);
	}

	public function staff_delete() {
		extract($_POST);
		if($staff_id != '') {
			$update = array(
				'is_deleted' => '1',
				'updated_at' => date('Y-m-s H:i:s')
			);
			$result = $this->clinic_staff_details_table->update($update,$staff_id);
			if($result) {
				$data['status'] = '1';
				$data['messg'] = "Staff detail deleted successfully.";
			} else {
				$data['status'] = '2';
				$data['messg'] = "opps! Something went wrong.";
			}
		} else {
			$data['status'] = '2';
			$data['messg'] = "opps! Something went wrong.";
		}

		echo json_encode($data);
	}
	public function save()
	{
		$app_id = $this->get_last_app_id();
		$inertStack = array(
			'app_id' => ++$app_id,
			'clinic_name' => $this->security->xss_clean($this->input->post('clinic_name')),
			'clinic_address' => $this->security->xss_clean($this->input->post('clinic_address')),
			'applicant_name' => $this->security->xss_clean($this->input->post('applicant_name')),
			'applicant_address' => $this->security->xss_clean($this->input->post('applicant_address')),
			'applicant_email_id' => $this->security->xss_clean($this->input->post('applicant_email_id')),
			'applicant_mobile_no' => $this->security->xss_clean($this->input->post('applicant_mobile_no')),
			'applicant_alternate_no' => $this->security->xss_clean($this->input->post('applicant_alternate_no')),
			'clinic_telephone_no' => $this->security->xss_clean($this->input->post('clinic_telephone_no')),
			'applicant_qualification' => $this->security->xss_clean($this->input->post('applicant_qualification')),
			'bio_medical_valid_date' => $this->security->xss_clean($this->input->post('bio_medical_valid_date')),
			'cold_chain_facilities' => $this->security->xss_clean($this->input->post('cold_chain_facilities')),
			'created_at' => date('Y-m-d H:i:s'),
			'application_type' => $this->security->xss_clean($this->input->post('application_type')),
			'user_id' => $this->authorised_user['user_id'],
		);
		if ($inertStack['application_type'] == 2) {
			$inertStack['no_of_expiry_certificate'] = $this->security->xss_clean($this->input->post('no_of_expiry_certificate'));
			$inertStack['date_of_expiry_certificate'] = $this->security->xss_clean($this->input->post('date_of_expiry_certificate'));
		}
		$image_upload_error = '';$document_data_stack = [];
		foreach ($_FILES as $key => $oneImage) {
			if (!empty($_FILES[$key]['name']) && $_FILES[$key]['error'] == 0) {
				$this->upload->initialize(clinic_document_config());
	            $_FILES['fileInput']['name'] = $oneImage['name'];
	            $_FILES['fileInput']['type'] = $oneImage['type'];
	            $_FILES['fileInput']['tmp_name'] = $oneImage['tmp_name'];
	            $_FILES['fileInput']['error'] = $oneImage['error'];
	            $_FILES['fileInput']['size'] = $oneImage['size'];
	            if ($this->upload->do_upload('fileInput')) {
	            	$one_document_document = array(
	            		'image_name' => $oneImage['name'],
	            		'image_enc_name' => $this->upload->data('file_name'),
	            		'image_path' => base_url('uploads/clinic/').$this->upload->data('file_name'),
	            		'image_size' => $this->upload->data('file_size'),
	            		'status' => 1,
	            		'is_deleted' => 0,
	            		'created_at' => date('Y-m-d H:i:s'),
	            		'updated_at' => date('Y-m-d H:i:s'),
	            		'file_field' => $key,
	            	);
	            	array_push($document_data_stack,$one_document_document);
	            } else { 
	            	$image_upload_error .= $this->upload->display_errors(); 
	            }
			} 
        }
        if ($image_upload_error != "") {
        	$this->response['status'] = FALSE;
			$this->response['message'] = $image_upload_error;
			return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));exit;
        }
        if (!empty($this->input->post('id')) && $this->input->post('id') != '') {
        	$clinic_id = $this->input->post('id');
        	unset($inertStack['app_id']);unset($inertStack['created_at']);unset($inertStack['application_type']);unset($inertStack['user_id']);
        	$this->clinic_applications_table->clinic_revolution($inertStack,$clinic_id);
        } else {
        	$clinic_id = $this->clinic_applications_table->clinic_revolution($inertStack);
        	$applications_details = array(
    			'application_id' => $inertStack['app_id'],
    			'dept_id' => $this->department_table->getDepartmentByName('Medical'),
    			'status' => 1,
    			'sub_dept_id' => $this->sub_department_table->getSubDepartmentByName('Clinic'),
    			'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
    		);
    		$app_status_remark = $this->clinic_applications_table->insert_application_details($applications_details);
        }
        $this->clinic_applications_table->storeclinicDocument($document_data_stack,$clinic_id);
        $staffStackPayload = [];$postdata = $this->input->post();
		foreach ($postdata['sr_number_clinic_Staff'] as $key => $oneStaffsrno) {
			$stafInfoStack['app_id'] = $clinic_id;
			$stafInfoStack['sr_number_clinic_Staff'] = $oneStaffsrno;
			$stafInfoStack['name_clinic_Staff'] = $postdata['name_clinic_Staff'][$key];
			$stafInfoStack['designation_clinic_Staff'] = $postdata['designation_clinic_Staff'][$key];
			$stafInfoStack['qualification_clinic_Staff'] = $postdata['qualification_clinic_Staff'][$key];
			array_push($staffStackPayload,$stafInfoStack);
		}
		if ($this->clinic_applications_table->reCreationclinicStaff($staffStackPayload,$clinic_id)) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "clinic has been created successfully...!!!";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function get_application_status()
	{
		$statusData = $this->App_status_level_table->getAllStatusByDeptRole($this->input->post('dept_id'),$this->input->post('role_id'));
		if (!empty($statusData)) {

			$roleStack = $this->clinic_applications_table->getRoleByName('Clerk');

			if ($this->authorised_user['role_id'] == $roleStack->role_id) {
				$this->data['health_officers'] = $this->clinic_applications_table->getAllHelthOfficers();
			} else {
				$this->data['health_officers'] = '';
			}

			$this->data['approvel_status'] = $statusData;
			$this->data['postdata'] = $this->input->post();
			$this->response['status'] = TRUE;
			$this->response['html_string'] = $this->load->view('applications/clinic/modal/add_remark',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function application_type_process()
	{
		if (!empty($this->input->post('application_type'))) {
			$this->session->set_flashdata('applications_type',$this->input->post('application_type'));
		} else {
			$this->session->set_flashdata('error','Please select application type');
		}
		return redirect('clinic/create');
	}
	public function inspection_form_display()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['insection_form'] = $this->clinic_applications_table->getInspectionDataByAppID($application->app_id);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/clinic/modal/inspection_form',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function inspection_form_process()
	{
		$poststack = $this->input->post();
		$inspection_form_array = array(
			'app_id' => $poststack['app_id'],
			'bio_medical_valid_date' => $poststack['bio_medical_valid_date'],
			'doc_degree_certificate' => !empty($poststack['doc_degree_certificate']) ? 1 : 0,
			'doc_reg_mmc' => !empty($poststack['doc_reg_mmc']) ? 1 : 0,
			'agreement_copy' => !empty($poststack['agreement_copy']) ? 1 : 0,
			'tax_recipes' => !empty($poststack['tax_recipes']) ? 1 : 0,



			
			// 'mpcb_certificate_valid_date' => $poststack['mpcb_certificate_valid_date'],
			// 'no_of_beds' => !empty($poststack['no_of_beds']) ? $poststack['no_of_beds'] : 0,
			// 'no_of_toilets' => !empty($poststack['no_of_toilets']) ? $poststack['no_of_toilets'] : 0,
			// 'nursing_certificate' => !empty($poststack['nursing_certificate']) ? 1 : 0,
			// 'noc_from_society' => !empty($poststack['noc_from_society']) ? 1 : 0,
			// 'noc_from_town_planning_mbmc' => !empty($poststack['noc_from_town_planning_mbmc']) ? 1 : 0,
			// 'noc_from_fire_dept' => !empty($poststack['noc_from_fire_dept']) ? 1 : 0,
			// 'general_observation' => !empty($poststack['general_observation']) ? 1 : 0,
			// 'labour_room_availability' => !empty($poststack['labour_room_availability']) ? 1 : 0,



			'status' => 1,
			'sub_dept_id' => 2,
			'created_by' => $this->authorised_user['user_id'],
		);
		if ($this->clinic_applications_table->create_inspection_form($inspection_form_array)) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "inspection report has been created successfully.";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function display_inspection_report()
	{
		$app_id = base64_decode($this->input->get('id'));
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['inspection'] = $this->clinic_applications_table->getInspectionDataByAppID($app_id);
			$this->load->view('applications/clinic/inspection_report',$this->data);
		} else {
			return redirect('clinic');
		}
	}
	public function payment_reqeust_popup()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['inspection'] = $this->clinic_applications_table->getInspectionDataByAppID($app_id);
			$this->data['payment'] = clinic_payment_calculation($application);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/clinic/modal/payment_reqeust_popup',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function payment_request_process()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['postdata'] = $this->input->post();
			$html_str = $this->load->view('applications/clinic/email_templates/payment_reqeust',$this->data,TRUE);
			$email_stack = array(
				'to' => $application->applicant_email_id,'body' => $html_str,'subject' => "Application MBMC-00000".$application->app_id." payment Reqeusted.",
			);
			$this->email_trigger->codeigniter_mail($email_stack);
			$paymentStack = array(
				'app_id' => $application->app_id,
				'dept_id' => $this->authorised_user['dept_id'],
				'amount' => $this->input->post('amount'),
				'status' => 1,
			);
			if ($this->clinic_applications_table->create_payment_reqeust($paymentStack)) {
				$this->response['status'] = TRUE;
				$this->response['message'] = "Payment reqeust has been created successfully.";
			} else {
				$this->response['status'] = FALSE;
				$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
			}
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function user_payment_form()
	{
		$app_id = base64_decode($this->input->get('app_id'));
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['payment_stack'] = clinic_payment_calculation($application);
			$this->load->view('applications/clinic/user_payment_page',$this->data);
		} else {
			return redirect('');
		}
	}
	public function user_payment_process()
    {
        $this->form_validation->set_rules('payment_method','Payment method','required|integer');
        $this->form_validation->set_rules('payment_amount','Payment amount','required');
        $document_response = json_decode($this->file_upload($_FILES['payment_document'],FALSE,clinic_payment_document_config()));
        if ($this->form_validation->run() && $document_response->status == TRUE) {
        	$updatePaymnetStack = array(
        		'payment_selected' => $this->security->xss_clean($this->input->post('payment_method')),
        		'amount' => $this->security->xss_clean($this->input->post('payment_amount')),
        		'document_path' => $document_response->file_data->file_name,
        		'status' => 4,
        	);
            if ($this->clinic_applications_table->update_payment_by_appID($updatePaymnetStack,$this->input->post('app_id'))) {
                $this->response['status'] = TRUE; 
                $this->response['message'] = 'Thank you for using the services of MBMC !';
                $this->response['redirect_url'] = base_url('login');
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = 'Sorry, we have to face some technical issues please try again later.';
            }
        } else {
            $this->response['status'] = FALSE;$message = '';
            if (validation_errors() != '') {
                $message .= strip_tags(validation_errors());
            } else if ($document_response->status == FALSE) {
                $message .= strip_tags($document_response->error);
            } else {
                $message .= 'Sorry, we have to face some technical issues please try again later.';
            }
            $this->response['message'] = $message;
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function payment_approvel_modal()
    {
    	$app_id = $this->input->post('app_id');
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['payment'] = $this->clinic_applications_table->getActivePaymentByAppID($app_id);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/clinic/modal/payment_approvel_popup',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function payment_approvel_process()
    {
    	$app_id = $this->input->post('app_id');
    	$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
    	if (!empty($application)) {
    		if ($this->clinic_applications_table->update_payment_by_appID(['status'=>2],$app_id)) {
                $this->response['status'] = TRUE; 
                $this->response['message'] = 'Payment has been approved.';
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = 'Sorry, we have to face some technical issues please try again later.';
            }
    	} else {
    		$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
    	}
    	return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function user_apps_list()
    {
    	$this->load->view('applications/clinic/user_apps_list');
    }
    public function datatable_userapplist()
    {
    	$applications = $this->clinic_applications_table->getApplicationForUsers(FALSE,$this->input->post());
    	$applications_count = $this->clinic_applications_table->getApplicationForUsers(TRUE,$this->input->post());
    	$finalDatatableStack = [];
    	foreach ($applications as $key => $oneApp) :
    		$tempArray = array();
    		$tempArray[] = $key + 1;
    		$tempArray[] = 'MBMC-00000'.$oneApp->app_id;
    		$tempArray[] = $oneApp->applicant_name;
    		$tempArray[] = $oneApp->applicant_email_id;
    		$tempArray[] = $oneApp->applicant_mobile_no;
    		$tempArray[] = $oneApp->clinic_name;
    		$tempArray[] = $oneApp->application_status;
    		$tempArray[] = date('Y-m-d',strtotime($oneApp->created_at));
    		$tempArray[] = '<a href="'.base_url('clinic/edit/'.base64_encode($oneApp->id)).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>
    						<a class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
    		array_push($finalDatatableStack,$tempArray);
    	endforeach ;
    	$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $applications_count,
            "recordsFiltered" => $applications_count,
            "data" => $finalDatatableStack,
        );
    	echo json_encode($output);
    }
}
