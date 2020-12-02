<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class HospitalController extends Common {

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
		$this->load->view("applications/hospital/index",$this->data);
	}
	public function create() {

		$this->data['app_id'] = $this->get_last_app_id();
		$this->data['designation'] = $this->designation_master_table->getAllDesignation();
		$this->data['qualification'] = $this->qualification_master_table->getAllQualification();
		$this->data['application_type'] = $this->session->flashdata('applications_type');
		if ($this->session->flashdata('applications_type')) {
			$this->load->view('applications/hospital/create',$this->data);
		} else {
			$this->load->view('applications/hospital/hospital_landing_page',$this->data);
		}
	}
	public function edit() {

		$hospital_id = base64_decode($this->uri->segment(3));
		$hospital_details = $this->hospital_applications_table->getApplicationByID($hospital_id);
		if (!empty($hospital_details)) :
			$this->data['application'] = $hospital_details;
			$this->data['designation'] = $this->designation_master_table->getAllDesignation();
			$this->data['FS_bedrooms'] = $this->hospital_applications_table->getFloreSpaceForBedroomsByAppID($hospital_id);
			$this->data['FS_kitchen'] = $this->hospital_applications_table->getFloreSpaceForKitchenByAppID($hospital_id);
			$this->data['nurcing_staff_details'] = $this->hospital_applications_table->getNurcingStaffDetailsByAppID($hospital_id);
			$this->data['surgeons'] = $this->hospital_applications_table->getHospitalSurgeonsByAppID($hospital_id);
			$this->data['supervision'] = $this->hospital_applications_table->getHospitalSupervisionByAppID($hospital_id);
			$this->data['midwife'] = $this->hospital_applications_table->getHospitalMidwifeByAppID($hospital_id);
			$this->data['aliens'] = $this->hospital_applications_table->getHospitalAlienByAppID($hospital_id);
			$this->data['feescharges'] = $this->hospital_applications_table->getHospitalFeeschargesByAppID($hospital_id);
			$this->data['qualification'] = $this->qualification_master_table->getAllQualification();
			$this->load->view('applications/hospital/edit',$this->data);
		else :
			return redirect('hospital/user_apps_list');
		endif ;		
	}


	public function get_lists()	{

		$data = $row = array();
		$session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];
        $hospitalList = $this->hospital_applications_table->getHospitalsDataForDatatable(FALSE,$_POST);
        $hospitalCount = $this->hospital_applications_table->getHospitalsDataForDatatable(TRUE,$_POST);
        $i = $_POST['start'];


        $roleStackHealthOfficer = $this->hospital_applications_table->getRoleByName('health officer');
        $roleStacClerk = $this->hospital_applications_table->getRoleByName('Clerk');


        foreach($hospitalList as $hospital){
            $i++;
            $id = $hospital['id'];

            if($hospital['app_id'] != null) {
              	$app_val = 'MBMC-00000'.$hospital['app_id'];
              	$app_no = $app_val;
          	} else {
            	$app_no = 'MBMC-000001';
          	}

            $application_no = $app_no;
            $applicant_name = $hospital['applicant_name'];
            $applicant_email_id = $hospital['applicant_email_id'];
            $applicant_mobile_no = $hospital['applicant_mobile_no'];
            // $applicant_nationality = $hospital['applicant_nationality'];
            $technical_qualification = $hospital['technical_qualification'];
            $un_reg_medical_practice = ($hospital['un_reg_medical_practice'] == '1') ? 'Yes' : 'NO';
            // $applicant_address = $hospital['applicant_address'];
            $hospital_name = $hospital['hospital_name'];
            $hospital_address = $hospital['hospital_address'];
			$contact_no = $hospital['contact_no'];
            $contact_person = $hospital['contact_person'];
            $floor_space = $hospital['floor_space'];
            $maternity_beds = $hospital['maternity_beds'];
            $patient_beds = $hospital['patient_beds'];
            $status = $hospital['status'];

            $dept_id = $this->applications_details_table->getDeptId($hospital['app_id'])['dept_id'];
            if ($roleStackHealthOfficer->role_id == $this->authorised_user['role_id']) {
            	$inspection_form_btn = '<a href="javascript:void(0)" app_id="'.$hospital['app_id'].'" aria-label="Inspection form" data-microtip-position="top" role="tooltip" class="btn btn-danger inspection_form_btn"><i app_id="'.$hospital['app_id'].'" class="fa fa-indent inspection_form_btn" aria-hidden="true"></i></a>';
            } else {
            	$inspection_form_btn = '';
            }

        	if ($hospital['hospital_inspection_done'] != 0) {
        		$inspection_form = '<a target="_blank" href="'.base_url('hospital/inspection_report?id='.base64_encode($hospital['app_id'])).'" type="button" class="btn btn-primary btn-sm">Report</a>';
        	} else {
        		$inspection_form = '-';
        	}

        	if ($roleStacClerk->role_id == $this->authorised_user['role_id'] && $hospital['hospital_inspection_done'] != 0 && $hospital['payment_status'] == '') {
        		$payment_btn = '<a type="button" aria-label="Payment Request" data-microtip-position="top" role="tooltip" app_id="'.$hospital['app_id'].'" class="btn btn-warning payment_request_btn"><i class="fas fa-money-bill-wave payment_request_btn" app_id="'.$hospital['app_id'].'"></i></a>';
        	} else if($roleStacClerk->role_id == $this->authorised_user['role_id'] && $hospital['payment_status'] == 4) {	
        		$payment_btn = '<a type="button" aria-label="Payment Approval" data-microtip-position="top" role="tooltip" app_id="'.$hospital['app_id'].'" class="btn btn-warning payment_approvel_btn"><i class="fas fa-check payment_approvel_btn" app_id="'.$hospital['app_id'].'"></i></a>';
        	} else {
        		$payment_btn = '';
        	}

            $sub_dept_id = $this->sub_department_table->getSubDepartmentByName('hospital');
            $status_details = $this->App_status_level_table->getAllStatusById($status);
            if($status_details != null) {
            	$status_type = $status_details[0]['status_type'];
            	$status_title = $status_details[0]['status_title'];	
            	if($status_type == '1') {
	            	$class = "btn-danger white";
	            } elseif($status == '2') {
	            	$class = "btn-success white";
	            } 

	            $status ='<a type="button" data-toggle="modal"  data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" class="status_button btn btn-sm btn-danger white">'.$status_title.'</a>';
            } else {
            	$status ='<a type="button" data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" class="btn btn-danger btn-sm status_button white">Awaiting</a>';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';

            $action = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$hospital['ownership_agreement'].','.$hospital['tax_receipt'].','.$hospital['doc_certificate'].','.$hospital['reg_certificate'].','.$hospital['staff_certificate'].','.$hospital['nursing_staff_deg_certificate'].','.$hospital['nursing_staff_reg_certificate'].','.$hospital['bio_des_certificate'].','.$hospital['society_noc'].','.$hospital['fire_noc'].'" data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'"  data-target="#modal-doc" class="doc_button anchor "><i class=" nav-icon fas fa-file"></i></a>
            	<a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'hospital/edit/'.base64_encode($id).'" class="anchor nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>'.$inspection_form_btn.' '.$payment_btn;
            $documents = '';

            $data[] = array($i,$application_no, $applicant_name, $applicant_email_id, $applicant_mobile_no,$hospital_name,$remarks,$status,$inspection_form,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $hospitalCount,
            "recordsFiltered" => $hospitalCount,
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

				$roleStack = $this->hospital_applications_table->getRoleByName('Clerk');
				$roleSeniorDoctorStack = $this->hospital_applications_table->getRoleByName('senior doctor');

				if (!empty($this->input->post('health_officers')) && $this->authorised_user['role_id'] == $roleStack->role_id) {
					$update_data['health_officer'] = $this->input->post('health_officers');
				}

				if ($roleSeniorDoctorStack->role_id == $this->authorised_user['role_id']) {
					$this->data['application'] = $this->hospital_applications_table->getApplicationByAppID($app_id);
					$this->data['certificate_url'] = base_url('letter/medical_certificate?app_id='.base64_encode($this->data['application']->app_id));
					$html_str = $this->load->view('applications/hospital/email_templates/medical_cirtificate_email',$this->data,TRUE);
					$email_stack = array(
						'to' => $this->data['application']->applicant_email_id,'body' => $html_str,'subject' => "Application MBMC-00000".$this->data['application']->app_id." medical cirtificate.",
					);
					$this->email_trigger->codeigniter_mail($email_stack);
				}


				$final_result = $this->hospital_applications_table->update($update_data,$app_id);

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
			$result = $this->hospital_staff_details_table->update($update,$staff_id);
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
			'applicant_name' => $this->security->xss_clean($this->input->post('applicant_name')),
			'applicant_email_id' => $this->security->xss_clean($this->input->post('applicant_email_id')),
			'applicant_mobile_no' => $this->security->xss_clean($this->input->post('applicant_mobile_no')),
			'applicant_alternate_no' => $this->security->xss_clean($this->input->post('applicant_alternate_no')),
			'technical_qualification' => $this->security->xss_clean($this->input->post('technical_qualification')),
			'applicant_nationality' => $this->security->xss_clean($this->input->post('applicant_nationality')),
			'arrangement_for_checkup' => $this->security->xss_clean($this->input->post('arrangement_for_checkup')),
			'situation_registration' => $this->security->xss_clean($this->input->post('situation_of_registration')),
			'hospital_name' => $this->security->xss_clean($this->input->post('name_of_nursinghome')),
			'hospital_address' => $this->security->xss_clean($this->input->post('address_of_nursinghome')),
			'applicant_address' => $this->security->xss_clean($this->input->post('applicant_address')),
			'others' => $this->security->xss_clean($this->input->post('other_purpose')),
			'maternity_beds' => $this->security->xss_clean($this->input->post('maternity_beds_number')),
			'proportion_of_qualified' => $this->security->xss_clean($this->input->post('proportion_of_qualified')),
			'patient_beds' => $this->security->xss_clean($this->input->post('patients_beds_number')),
			'detail_arrange_sanitary_employee' => $this->security->xss_clean($this->input->post('detail_arrange_sanitary_employee')),
			'detail_arrange_sanitary_patients' => $this->security->xss_clean($this->input->post('detail_arrange_sanitary_patients')),
			'storage_arrangements' => $this->security->xss_clean($this->input->post('storage_arrangements')),
			'other_business_address' => $this->security->xss_clean($this->input->post('other_business_address')),
			'accomodation' => $this->security->xss_clean($this->input->post('Accomodation')),
			'created_at' => date('Y-m-d H:i:s'),
			'application_type' => $this->security->xss_clean($this->input->post('application_type')),
			'promise' => $this->security->xss_clean($this->input->post('promise')),
			'user_id' => $this->authorised_user['user_id'],
		);
		$image_upload_error = '';$document_data_stack = [];
		foreach ($_FILES as $key => $oneImage) {
			if (!empty($_FILES[$key]['name']) && $_FILES[$key]['error'] == 0) {
				$this->upload->initialize(hospital_document_config());
	            $_FILES['fileInput']['name'] = $oneImage['name'];
	            $_FILES['fileInput']['type'] = $oneImage['type'];
	            $_FILES['fileInput']['tmp_name'] = $oneImage['tmp_name'];
	            $_FILES['fileInput']['error'] = $oneImage['error'];
	            $_FILES['fileInput']['size'] = $oneImage['size'];
	            if ($this->upload->do_upload('fileInput')) {
	            	$one_document_document = array(
	            		'image_name' => $oneImage['name'],
	            		'image_enc_name' => $this->upload->data('file_name'),
	            		'image_path' => base_url('uploads/hospital/').$this->upload->data('file_name'),
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
        	$hospital_id = $this->input->post('id');
        	unset($inertStack['app_id']);unset($inertStack['created_at']);unset($inertStack['application_type']);unset($inertStack['user_id']);
        	$this->hospital_applications_table->hospital_revolution($inertStack,$hospital_id);
        } else {
        	$hospital_id = $this->hospital_applications_table->hospital_revolution($inertStack);
        	$applications_details = array(
    			'application_id' => $inertStack['app_id'],
    			'dept_id' => $this->department_table->getDepartmentByName('Medical'),
    			'status' => 1,
    			'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
    		);
    		$app_status_remark = $this->hospital_applications_table->insert_application_details($applications_details);
        }
        $this->hospital_applications_table->storeHospitalDocument($document_data_stack,$hospital_id);
        $staffStackPayload = [];$postData = $this->input->post();
		foreach ($postData['staff_name'] as $key => $oneStaff) {
			$stafInfoStack['app_id'] = $hospital_id;
			$stafInfoStack['staff_name'] = $oneStaff;
			$stafInfoStack['age'] = $postData['staff_age'][$key];
			$stafInfoStack['design_id'] = $postData['staff_designation'][$key];
			$stafInfoStack['qual_id'] = $postData['staff_qualification'][$key];
			$stafInfoStack['status'] = TRUE;
			$stafInfoStack['created_at'] = date('Y-m-d H:i:s');
			array_push($staffStackPayload,$stafInfoStack);
		}
		$floreSpaceforBedrooms = [];
		foreach ($postData['flore_number'] as $key => $oneFlore) {
			$temp['app_id'] = $hospital_id;
			$temp['floor_number'] = $oneFlore;
			$temp['total_bedrooms_on_flore'] = $postData['bedrooms_on_flore'][$key];
			$temp['total_number_of_beds'] = $postData['number_of_beds'][$key];
			array_push($floreSpaceforBedrooms,$temp);
		}
		$floreSpaceforkitchen = [];	
		foreach ($postData['fs_for_kitchen_room_name'] as $key => $oneRoom) {
			$temp_one['app_id'] = $hospital_id;
			$temp_one['room_name'] = $oneRoom;
			$temp_one['floor_name'] = $postData['fs_for_kitchen_floor_name'][$key];
			$temp_one['area'] = $postData['fs_for_kitchen_area'][$key];
			$temp_one['user'] = $postData['fs_for_kitchen_user_type'][$key];
			array_push($floreSpaceforkitchen,$temp_one);
		}
		$fee_charges_stack = [];	
		foreach ($postData['sr_number'] as $key => $srNo) {
			$temp_two['app_id'] = $hospital_id;
			$temp_two['sr_no'] = $srNo;
			$temp_two['service'] = $postData['fees_service'][$key];
			$temp_two['charges'] = $postData['charges'][$key];
			array_push($fee_charges_stack,$temp_two);
		}

		$surgeon_information_stack = [];	
		foreach ($postData['surgeon_name'] as $key => $surgeonname) {
			$temp_three['app_id'] = $hospital_id;
			$temp_three['name'] = $surgeonname;
			$temp_three['age'] = $postData['surgeon_age'][$key];
			$temp_three['qualification'] = $postData['surgeon_qualification'][$key];
			$temp_three['visiting'] = $postData['surgeon_is_visiting'][$key];
			array_push($surgeon_information_stack,$temp_three);
		}

		$supervision_stack = [];	
		foreach ($postData['supervision_name'] as $key => $supervisionname) {
			$temp_four['app_id'] = $hospital_id;
			$temp_four['name'] = $supervisionname;
			$temp_four['age'] = $postData['supervision_age'][$key];
			$temp_four['qualification'] = $postData['supervision_qualification'][$key];
			array_push($supervision_stack,$temp_four);
		}


		$midwife_stack = [];	
		foreach ($postData['midwife_name'] as $key => $midwifename) {
			$temp_five['app_id'] = $hospital_id;
			$temp_five['name'] = $midwifename;
			$temp_five['age'] = $postData['midwife_age'][$key];
			$temp_five['qualification'] = $postData['midwife_qualification'][$key];
			array_push($midwife_stack,$temp_five);
		}

		$alien_stack = [];	
		foreach ($postData['alien_name'] as $key => $alien_name) {
			$temp_six['app_id'] = $hospital_id;
			$temp_six['name'] = $alien_name;
			$temp_six['age'] = $postData['alien_age'][$key];
			$temp_six['qualification'] = $postData['alien_qualification'][$key];
			$temp_six['nationality'] = $postData['alien_nationality'][$key];
			array_push($alien_stack,$temp_six);
		}

		$alien_query_response = $this->hospital_applications_table->reCreateAlien($alien_stack,$hospital_id);
		$midwife_query_response = $this->hospital_applications_table->reCreateMidwife($midwife_stack,$hospital_id);
		$supervision_query_response = $this->hospital_applications_table->reCreateSupervision($supervision_stack,$hospital_id); 
		$surgeon_query_response = $this->hospital_applications_table->reCreateSurgeon($surgeon_information_stack,$hospital_id);
		$fee_charges_query_response = $this->hospital_applications_table->reCreateFeesCharges($fee_charges_stack,$hospital_id);
		$FSforkitchen = $this->hospital_applications_table->reCreateFSkitchen($floreSpaceforkitchen,$hospital_id);
		$FSBedroms = $this->hospital_applications_table->reCreationfloreSpaceForBedrooms($floreSpaceforBedrooms,$hospital_id);
		$staff = $this->hospital_applications_table->reCreationHospitalStaff($staffStackPayload,$hospital_id);

		if ($FSBedroms && $staff && $FSforkitchen && $fee_charges_query_response && $surgeon_query_response && $supervision_query_response) {
			$this->response['status'] = TRUE;
			if (!empty($this->input->post('id')) && $this->input->post('id') != '') {
				$this->response['message'] = "hospital has been updated successfully...!!!";
			} else {
				$this->response['message'] = "hospital has been created successfully...!!!";
			}
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

			$roleStack = $this->hospital_applications_table->getRoleByName('Clerk');

			if ($this->authorised_user['role_id'] == $roleStack->role_id) {
				$this->data['health_officers'] = $this->hospital_applications_table->getAllHelthOfficers();
			} else {
				$this->data['health_officers'] = '';
			}

			$this->data['approvel_status'] = $statusData;
			$this->data['postdata'] = $this->input->post();
			$this->response['status'] = TRUE;
			$this->response['html_string'] = $this->load->view('applications/hospital/modal/add_remark',$this->data,TRUE);
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
		return redirect('hospital/create');
	}
	public function inspection_form_display()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['insection_form'] = $this->hospital_applications_table->getInspectionDataByAppID($application->app_id);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/hospital/modal/inspection_form',$this->data,TRUE);
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
			'mpcb_certificate_valid_date' => $poststack['mpcb_certificate_valid_date'],
			'no_of_beds' => !empty($poststack['no_of_beds']) ? $poststack['no_of_beds'] : 0,
			'no_of_toilets' => !empty($poststack['no_of_toilets']) ? $poststack['no_of_toilets'] : 0,
			'doc_degree_certificate' => !empty($poststack['doc_degree_certificate']) ? 1 : 0,
			'doc_reg_mmc' => !empty($poststack['doc_reg_mmc']) ? 1 : 0,
			'agreement_copy' => !empty($poststack['agreement_copy']) ? 1 : 0,
			'tax_recipes' => !empty($poststack['tax_recipes']) ? 1 : 0,
			'nursing_certificate' => !empty($poststack['nursing_certificate']) ? 1 : 0,
			'noc_from_society' => !empty($poststack['noc_from_society']) ? 1 : 0,
			'noc_from_town_planning_mbmc' => !empty($poststack['noc_from_town_planning_mbmc']) ? 1 : 0,
			'noc_from_fire_dept' => !empty($poststack['noc_from_fire_dept']) ? 1 : 0,
			'general_observation' => !empty($poststack['general_observation']) ? 1 : 0,
			'labour_room_availability' => !empty($poststack['labour_room_availability']) ? 1 : 0,
			'status' => 1,
			'sub_dept_id' => $poststack['sub_dept_id'],
			'created_by' => $this->authorised_user['user_id'],
		);
		if ($this->hospital_applications_table->create_inspection_form($inspection_form_array)) {
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
		$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['inspection'] = $this->hospital_applications_table->getInspectionDataByAppID($app_id);
			$this->load->view('applications/hospital/inspection_report',$this->data);
		} else {
			return redirect('hospital');
		}
	}
	public function payment_reqeust_popup()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['inspection'] = $this->hospital_applications_table->getInspectionDataByAppID($app_id);
			$this->data['payment'] = hospital_payment_calculation($this->data['inspection']->no_of_beds,$application->application_type);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/hospital/modal/payment_reqeust_popup',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function payment_request_process()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['postdata'] = $this->input->post();
			$html_str = $this->load->view('applications/hospital/email_templates/payment_reqeust',$this->data,TRUE);
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
			if ($this->hospital_applications_table->create_payment_reqeust($paymentStack)) {
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
		$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['payment_stack'] = hospital_payment_calculation($application->no_of_beds,$application->application_type);
			$this->load->view('applications/hospital/user_payment_page',$this->data);
		} else {
			return redirect('');
		}
	}
	public function user_payment_process()
    {
        $this->form_validation->set_rules('payment_method','Payment method','required|integer');
        $this->form_validation->set_rules('payment_amount','Payment amount','required');
        $document_response = json_decode($this->file_upload($_FILES['payment_document'],FALSE,hospital_payment_document_config()));
        if ($this->form_validation->run() && $document_response->status == TRUE) {
        	$updatePaymnetStack = array(
        		'payment_selected' => $this->security->xss_clean($this->input->post('payment_method')),
        		'amount' => $this->security->xss_clean($this->input->post('payment_amount')),
        		'document_path' => $document_response->file_data->file_name,
        		'status' => 4,
        	);
            if ($this->hospital_applications_table->update_payment_by_appID($updatePaymnetStack,$this->input->post('app_id'))) {
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
		$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['payment'] = $this->hospital_applications_table->getActivePaymentByAppID($app_id);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/hospital/modal/payment_approvel_popup',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function payment_approvel_process()
    {
    	$app_id = $this->input->post('app_id');
    	$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
    	if (!empty($application)) {
    		if ($this->hospital_applications_table->update_payment_by_appID(['status'=>2],$app_id)) {
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
    	$this->load->view('applications/hospital/user_apps_list');
    }
    public function datatable_userapplist()
    {
    	$applications = $this->hospital_applications_table->getApplicationForUsers(FALSE,$this->input->post());
    	$applications_count = $this->hospital_applications_table->getApplicationForUsers(TRUE,$this->input->post());
    	$finalDatatableStack = [];
    	foreach ($applications as $key => $oneApp) :
    		$tempArray = array();
    		$tempArray[] = $key + 1;
    		$tempArray[] = 'MBMC-00000'.$oneApp->app_id;
    		$tempArray[] = $oneApp->applicant_name;
    		$tempArray[] = $oneApp->applicant_email_id;
    		$tempArray[] = $oneApp->applicant_mobile_no;
    		$tempArray[] = $oneApp->hospital_name;
    		$tempArray[] = $oneApp->application_status;
    		$tempArray[] = '<a href="'.base_url('hospital/edit/'.base64_encode($oneApp->id)).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a><a class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
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
