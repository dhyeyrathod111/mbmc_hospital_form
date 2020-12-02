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
		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
		$this->load->view("applications/clinic/index",$data);
	}

	public function create() {
		$data['app_id'] = $this->get_last_app_id();
		$data['designation'] = $this->designation_master_table->getAllDesignation();

		$data['qualification'] = $this->qualification_master_table->getAllQualification();
		// echo'<pre>';print_r($data);exit;
		// $data['road'] = $this->get_all_road_type();	
		$this->load->view('applications/clinic/create',$data);
	}

	public function edit() {

		$clinic_id = base64_decode($this->uri->segment(3));
		$result = $this->clinic_applications_table->getAllApplicationsDetailsById($clinic_id);
		$get_staff_details = $this->clinic_applications_table->getStaffByAppID($result['app_id']);
		$file['ownership_agreement'] = $result['ownership_agreement_id'];
		$file['tax_receipt'] = $result['tax_receipt_id'];
		$file['doc_certificate'] = $result['doc_certificate_id'];
		$file['bio_medical_certificate'] = $result['bio_medical_certificate_id'];
		$file['aadhaar_card'] = $result['aadhaar_card_id'];
		foreach ($file as $key => $value) {
			$get_attachments = $this->image_details_table->getImageDetailsById($value);
			$file_data[] = array('name' => $get_attachments['image_name'], 'path' => $get_attachments['image_path']);
		}
		$data['designation'] = $this->designation_master_table->getAllDesignation();
		$data['qualification'] = $this->qualification_master_table->getAllQualification();
		$data['staff_details'] = $get_staff_details;
		$data['users'] = $result;
		$data['files'] = $file_data;
		// echo'<pre>';print_r($data['staff_details']);exit;
		$this->load->view('applications/clinic/edit',$data);
	}

	public function save() {

		extract($_POST);

		// echo'<pre>';print_r($_POST);exit;

		$applicant_name_check = $this->form_validation
            ->set_rules('applicant_name','applicant name','required')->run();

        $applicant_address_check = $this->form_validation
            ->set_rules('applicant_address','applicant address','required')->run();

        $applicant_qualification_check = $this->form_validation
            ->set_rules('applicant_qualification','applicant qualification','required')->run();

    	$applicant_email_id_check = $this->form_validation
                ->set_rules('applicant_email_id','applicant email id','required|valid_email')->run();

        $applicant_mobile_no_check = $this->form_validation
                ->set_rules('applicant_mobile_no','applicant mobile no','required|regex_match[/^[0-9]{10}$/]')->run();

        $clinic_name_check = $this->form_validation
                            ->set_rules('clinic_name','clinic name','required')->run();

        $clinic_address_check = $this->form_validation
                            ->set_rules('clinic_address','clinic address','required')->run();

    	$contact_no_check = $this->form_validation
                            ->set_rules('contact_no','contact no','required')->run();

    	$contact_person_check = $this->form_validation
                            ->set_rules('contact_person','contact person','required')->run();
        // echo'<pre>';print_r($_FILES);exit;
        if (!empty($_FILES)) {
        	// echo'<pre>';print_r($_FILES);exit;
			$ownership_agreement_check = $this->form_validation->set_rules('ownership_agreement_name', 'Document', 'required');
	                            
			$tax_receipt_check = $this->form_validation->set_rules('tax_receipt_name', 'Document', 'required');

			$doc_certificate_check = $this->form_validation->set_rules('doc_certificate_name', 'Document', 'required');

			$bio_med_certificate_check = $this->form_validation->set_rules('bio_medical_certificate_name','Document', 'required');

			$aadhaar_card_check = $this->form_validation->set_rules('aadhaar_card_name', 'Document','required');

		} else {
			// echo'sss<pre>';print_r($data);exit;
			$data['status'] = '2';
            $data['messg'] = 'Please choose the documents.';
		}

        // echo'ss<pre>';print_r($id);exit;                    

		// echo'<pre>';var_dump(!$applicant_mobile_no_check);exit;
		if(	!$applicant_name_check || !$applicant_address_check || !$applicant_qualification_check  || !$clinic_name_check || !$contact_no_check||
			!$contact_person_check || !$clinic_address_check || !$tax_receipt_check || 
			!$ownership_agreement_check || !$doc_certificate_check || 
			!$aadhaar_card_check 
		) {
			// echo'ss<pre>';print_r($applicant_address_check);exit;
            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {

        	// echo'<pre>';print_r('ijij');exit;
        	$config['upload_path']   = './uploads/clinic/';
	  		$config['allowed_types'] = '*';
	  		$config['max_size']      = '5000';
	  		$config['encrypt_name'] = TRUE;

			$this->upload->initialize($config);
			// echo'<pre>';print_r($_FILES);exit;
			$upload_array = array();
			
			foreach ($_FILES as $key => $files) {



				if(!$this->upload->do_upload($key)) {
					$data['status'] = '2';
		            $data['messg'] = $this->upload->display_errors();
		            
				} else {
					$uploaded_data = $this->upload->data();
					$image_data = array(
						'image_name' => $uploaded_data['orig_name'],
						'image_enc_name' => $uploaded_data['file_name'],
						'image_path' => base_url().'uploads/clinic/'.$uploaded_data['file_name'],
						'image_size' => $uploaded_data['file_size'],
						'status' => '1',
						'is_deleted' => '0',
						'created_at' => date('Y-m-s H:i:s'),
						'updated_at' => date('Y-m-s H:i:s'),
					); 

					$result = $this->upload_files($image_data);

					if($result != null) {
						$upload_array[$key.'_id'] = $result;
					} else {
						$upload_array[] = array($key => $result);
					}
				}
			}

			$dept_id = $this->department_table->getDepartmentByName('Medical');
			$sub_dept_id = $this->sub_department_table->getSubDepartmentByName('Clinic');

			if(!empty($dept_id)) {
				$staff_name_array = $_POST['staff_name'];

				$designation_array = $_POST['designation'];
				$age_array = $_POST['age'];
				$qualification_array = $_POST['qualification'];

				unset($_POST['application_no']);
				unset($_POST['staff_name']);
				unset($_POST['designation']);
				unset($_POST['age']);
				unset($_POST['qualification']);
				unset($_POST['ownership_agreement_name']);
				unset($_POST['tax_receipt_name']);
				unset($_POST['doc_certificate_name']);
				unset($_POST['bio_medical_certificate_name']);
				unset($_POST['aadhaar_card_name']);

				if($id =='') {
					// add form
					$applications_details = array(
						'dept_id' => $dept_id,
						'sub_dept_id'=> $sub_dept_id,
						'status' => '1',
						'is_deleted' => '0',
						'created_at' => date('Y-m-s H:i:s'),
						'updated_at' => date('Y-m-s H:i:s'),
					);
					// echo'<pre>';print_r($applications_details);exit;
					$inserted_app_id = $this->applications_details_table->insert($applications_details);

					$app_id_array = array('app_id' => $inserted_app_id); 
					// unset($_POST['dept_id']);
					$extra = array(
		                'status' => '1',
		                'is_deleted' => '0',
		                'created_at' => date('Y-m-d H:i:s'),
		                'updated_at' => date('Y-m-d H:i:s'),
		            );

		            $insert_data = array_merge($_POST,$app_id_array,$upload_array,$extra);
		            




					$result = $this->clinic_applications_table->insert($insert_data);
					
					if($result != null) {
						foreach ($staff_name_array as $key => $value) {
							$staff_details = array(
								'app_id' =>$inserted_app_id,
								'staff_name' => $value,
								'age' => $age_array[$key],
								'design_id' => $designation_array[$key],
								'qual_id' => $qualification_array[$key],
								'status' => '1',
				                'is_deleted' => '0',
				                'created_at' => date('Y-m-d H:i:s'),
				                'updated_at' => date('Y-m-d H:i:s'),
							);

							$staff_insert_result = $this->hospital_staff_details_table->insert($staff_details);
						}
						$data['status'] = '1';
						$data['messg'] = 'Application added successfully.';
	 				} else {
	 					$data['status'] = '2';
						$data['messg'] = 'Oops! Something went wrong.';
	 				}
				} else {

					$staff_id_array = $_POST['staff_id'];
					unset($_POST['staff_id']);
					// update form
					$applications_details = array(
						'updated_at' => date('Y-m-d H:i:s'),
					);

					$updated_app_result = $this->applications_details_table->update($applications_details,$app_id);

					if($updated_app_result) {
						foreach ($staff_name_array as $key => $value) {
							$staff_details = array(
								'id' => @$staff_id_array[$key],
								'app_id' =>$app_id,
								'staff_name' => $value,
								'age' => $age_array[$key],
								'design_id' => $designation_array[$key],
								'qual_id' => $qualification_array[$key],
								'status' => '1',
				                'is_deleted' => '0',
				                'created_at' => date('Y-m-d H:i:s'),
				                'updated_at' => date('Y-m-d H:i:s'),
							);
							
							if(in_array($staff_details['id'], $staff_id_array)) {

								$staff_insert_result = $this->hospital_staff_details_table->update($staff_details,$staff_details['id']);
							} else {

								$staff_insert_result = $this->hospital_staff_details_table->insert($staff_details);
							}
						}

						$extra = array(
			                'updated_at' => date('Y-m-d H:i:s'),
			            );

			            $update_data = array_merge($_POST,$upload_array,$extra);


						$result = $this->clinic_applications_table->update($update_data,$app_id);
						
						if($result) {
							$data['status'] = '1';
							$data['messg'] = 'Application updated successfully.';
		 				} else {
		 					$data['status'] = '2';
							$data['messg'] = 'Oops! Something went wrong.';
		 				}

					} else {
						$data['status'] = '2';
						$data['messg'] = 'Oops! Something went wrong.';
					}
				}
			} else {
				$data['status'] = '2';
				$data['messg'] = 'Oops! Something went wrong.';
			}
        }
        echo json_encode($data);
	}

	public function get_lists()	{

		$data = $row = array();
		$session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];

        // Fetch member's records
        // $clinicList = $this->clinic_applications_table->getRows($_POST);

        $clinicList = $this->clinic_applications_table->getClinicApllications(FALSE,$_POST);

        $clinicCount = $this->clinic_applications_table->getClinicApllications(TRUE,$_POST);



        $i = $_POST['start'];

        $roleStackHealthOfficer = $this->hospital_applications_table->getRoleByName('health officer');
        $roleStacClerk = $this->hospital_applications_table->getRoleByName('Clerk');

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
            $applicant_qualification = $clinic['applicant_qualification'];
            // $applicant_address = $hospital['applicant_address'];
            $clinic_name = $clinic['clinic_name'];
            $clinic_address = $clinic['clinic_address'];
			$contact_no = $clinic['contact_no'];
            $contact_person = $clinic['contact_person'];
            $immunization = ($clinic['immunization'] == 1)  ? 'Yes' : 'No';
            $immunization_details = $clinic['immunization_details'];
            $bio_waste_valid = $clinic['bio_waste_valid'];
            $status = $clinic['status'];

            $dept_id = $this->applications_details_table->getDeptId($clinic['app_id'])['dept_id'];
            $sub_dept_id = $this->sub_department_table->getSubDepartmentByName('Clinic');
            $status_details = $this->App_status_level_table->getAllStatusById($status);

            if ($roleStackHealthOfficer->role_id == $this->authorised_user['role_id']) {
            	$inspection_form_btn = '<a href="javascript:void(0)" app_id="'.$clinic['app_id'].'" aria-label="Inspection form" data-microtip-position="top" role="tooltip" class="btn btn-danger btn-sm inspection_form_btn"><i app_id="'.$clinic['app_id'].'" class="fa fa-indent inspection_form_btn" aria-hidden="true"></i></a>';

            } else {
            	$inspection_form_btn = '';
            }


            if ($roleStacClerk->role_id == $this->authorised_user['role_id'] && $clinic['clinic_inspection_done'] != 0 && $clinic['payment_status'] == '') {
        		$payment_btn = '<a type="button" aria-label="Payment Request" data-microtip-position="top" role="tooltip" app_id="'.$clinic['app_id'].'" class="btn btn-warning btn-sm payment_request_btn"><i class="fas fa-money-bill-wave payment_request_btn" app_id="'.$clinic['app_id'].'"></i></a>';
        	} else if($roleStacClerk->role_id == $this->authorised_user['role_id'] && $clinic['payment_status'] == 4) {	
        		$payment_btn = '<a type="button" aria-label="Payment Approval" data-microtip-position="top" role="tooltip" app_id="'.$clinic['app_id'].'" class="btn btn-warning btn-sm payment_approvel_btn"><i class="fas fa-check payment_approvel_btn" app_id="'.$clinic['app_id'].'"></i></a>';
        	} else {
        		$payment_btn = '';
        	}



            $inspection_form = '<a target="_blank" href="'.base_url('clinic/inspection_report?id='.base64_encode($clinic['app_id'])).'" type="button" class="btn btn-primary btn-sm">Report</a>';


            if($status_details != null) {
            	$status_type = $status_details[0]['status_type'];
            	$status_title = $status_details[0]['status_title'];
            	if($status_type == '1') {
	            	$class = "btn-danger white";
	            } elseif($status == '2') {
	            	$class = "btn-success white";
	            } 

	            $status ='<a type="button" data-toggle="modal"  data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-block btn-danger white btn-sm">'.$status_title.'</a>';
            } else {
            	$status ='<a type="button" data-toggle="modal"  data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-block btn-danger white btn-sm">Awaiting</a>';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-block btn-primary white btn-sm">Remarks</a>';

            $action = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$clinic['ownership_agreement_id'].','.$clinic['tax_receipt_id'].','.$clinic['doc_certificate_id'].','.$clinic['bio_medical_certificate_id'].','.$clinic['aadhaar_card_id'].'" data-clinic="'.$clinic['id'].'" data-app="'.$clinic['app_id'].'"  data-target="#modal-doc" class="doc_button anchor"><i class=" nav-icon fas fa-file"></i></a><a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'clinic/edit/'.base64_encode($id).'" class="anchor nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>'.$inspection_form_btn . $payment_btn;

            // echo'<pre>';print_r($request_letter_image);exit;
            $data[] = array($i,$application_no, $applicant_name, $applicant_email_id,$clinic_name,$remarks,$status,$action);
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
                'updated_at' => date('Y-m-s H:i:s'),
            );
        	// echo'<pre>';print_r($insert_data);exit;
			$result = $this->application_remarks_table->insert($insert_data);
			if($result != null) {

				$update_data = array(
					'status' => $status,
					'updated_at' => date('Y-m-d H:i:s')
				);

				$roleStack = $this->hospital_applications_table->getRoleByName('Clerk');

				if (!empty($this->input->post('health_officers')) && $this->authorised_user['role_id'] == $roleStack->role_id) {
					$update_data['health_officer'] = $this->input->post('health_officers');
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
			$this->response['html_string'] = $this->load->view('applications/clinic/modal/add_remark',$this->data,TRUE);
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
	public function get_userClinic()
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
    		$tempArray[] = '<a class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a><a class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></a>';
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
	public function payment_reqeust_popup()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['inspection'] = $this->clinic_applications_table->getInspectionDataByAppID($app_id);
			$this->data['payment'] = clinic_payment_calculation($this->data['inspection']->no_of_beds,$application->application_type);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/clinic/modal/payment_reqeust_popup',$this->data,TRUE);
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
			return redirect('hospital');
		}
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
		if ($this->clinic_applications_table->create_inspection_form($inspection_form_array)) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "inspection report has been created successfully.";
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
			$this->data['payment_stack'] = clinic_payment_calculation($application->no_of_beds,$application->application_type);
			$this->load->view('applications/clinic/user_payment_page',$this->data);
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
    		if ($this->clinic_applications_table->update_payment_by_appID(['status'=>2,'approved_by'=>$this->authorised_user['user_id']],$app_id)) {
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
}
