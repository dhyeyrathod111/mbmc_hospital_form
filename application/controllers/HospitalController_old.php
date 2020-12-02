<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class HospitalController extends Common {

	/**
 	*
 	*/
	public function index()	{
		$dept_id = $this->get_dept_id_by_name('Medical');
		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
        // echo'<pre>';print_r($data);exit;
		$this->load->view("applications/hospital/index",$data);
	}

	public function create() {
		$data['app_id'] = $this->get_last_app_id();
		$data['designation'] = $this->designation_master_table->getAllDesignation();

		$data['qualification'] = $this->qualification_master_table->getAllQualification();
		// echo'<pre>';print_r($data);exit;
		// $data['road'] = $this->get_all_road_type();	
		$this->load->view('applications/hospital/create',$data);
	}

	public function edit() {

		$hospital_id = base64_decode($this->uri->segment(3));
		$result = $this->hospital_applications_table->getAllApplicationsDetailsById($hospital_id);
		// echo'<pre>';print_r($result);exit;
		$get_staff_details = $this->hospital_staff_details_table->getStaffDetailsById($result['app_id']);
		// echo'<pre>';print_r($get_staff_details);exit;
		$file['ownership_agreement'] = $result['ownership_agreement_id'];
		$file['tax_receipt'] = $result['tax_receipt_id'];
		$file['doc_certificate'] = $result['doc_certificate_id'];
		$file['reg_certificate'] = $result['reg_certificate_id'];
		$file['staff_certificate'] = $result['staff_certificate_id'];
		$file['nursing_staff_deg_certificate'] = $result['nursing_staff_deg_certificate_id'];
		$file['nursing_staff_reg_certificate'] = $result['nursing_staff_reg_certificate_id'];
		$file['bio_des_certificate'] = $result['bio_des_certificate_id'];
		$file['society_noc'] = $result['society_noc_id'];
		$file['fire_noc'] = $result['fire_noc_id'];
		foreach ($file as $key => $value) {
			// echo'<pre>';print_r($array);exit;
			$get_attachments = $this->image_details_table->getImageDetailsById($value);
			// echo "<pre>";print_r($get_attachments);exit;
			$file_data[] = array('name' => $get_attachments['image_name'], 'path' => base_url().'uploads/hospital/'.$get_attachments['image_name']);
		}
		// echo'<pre>';print_r($file_data);exit;
		$data['designation'] = $this->designation_master_table->getAllDesignation();
		// echo "<pre>";print_r($file_data);exit;

		$data['qualification'] = $this->qualification_master_table->getAllQualification();
		$data['staff_details'] = $get_staff_details;
		$data['users'] = $result;
		$data['files'] = $file_data;
		// echo'<pre>';print_r($data['staff_details']);exit;
		$this->load->view('applications/hospital/edit',$data);
	}

	public function save() {

		extract($_POST);

		// echo'<pre>';print_r($_POST);exit;

		$applicant_name_check = $this->form_validation
            ->set_rules('applicant_name','applicant name','required')->run();

        $applicant_address_check = $this->form_validation
            ->set_rules('applicant_address','applicant address','required')->run();

		$applicant_nationality_check = $this->form_validation
            ->set_rules('applicant_nationality','applicant nationality','required')->run();

        $technical_qualification_check = $this->form_validation
            ->set_rules('technical_qualification','technical qualification','required')->run();

        // $un_reg_medical_practice_check = $this->form_validation
        //     ->set_rules('un_reg_medical_practice','un-register medical practice','required')->run();

        $hospital_name_check = $this->form_validation
                            ->set_rules('hospital_name','hospital name','required')->run();

        $hospital_address_check = $this->form_validation
                            ->set_rules('hospital_address','hospital address','required')->run();

    	$contact_no_check = $this->form_validation
                            ->set_rules('contact_no','contact no','required')->run();

    	$contact_person_check = $this->form_validation
                            ->set_rules('contact_person','contact person','required')->run();

        $floor_space_check = $this->form_validation
                            ->set_rules('floor_space','floor space','required')->run();

        $maternity_beds_check = $this->form_validation
                            ->set_rules('maternity_beds','No. of maternity beds','required')->run();

        $patient_beds_check = $this->form_validation
                            ->set_rules('patient_beds','No. of patient beds','required')->run();
        // echo'ss<pre>';print_r($id);exit;                    
		if($id =='') {

			$applicant_email_id_check = $this->form_validation
                ->set_rules('applicant_email_id','applicant email id','required|valid_email|is_unique[hospital_applications.applicant_email_id]')->run();

            $applicant_mobile_no_check = $this->form_validation
                ->set_rules('applicant_mobile_no','applicant mobile no','required|regex_match[/^[0-9]{10}$/]|is_unique[hospital_applications.applicant_mobile_no]')->run();

            if (!empty($_FILES)) {
            	// echo'<pre>';print_r($_FILES);exit;
				$ownership_agreement_check = $this->form_validation->set_rules('ownership_agreement_name', 'Document', 'required');
		                            
				$tax_receipt_check = $this->form_validation->set_rules('tax_receipt_name', 'Document', 'required');
				$doc_certificate_check = $this->form_validation->set_rules('doc_certificate_name', 'Document', 'required');

				$reg_certificate_check = $this->form_validation->set_rules('reg_certificate_name', 'Document', 'required');

				$staff_certificate_check = $this->form_validation->set_rules('staff_certificate_name', 'Document', 'required');

				$nursing_staff_deg_certificate_check = $this->form_validation->set_rules('nursing_staff_deg_certificate_name', 'Document', 'required');

				$nursing_staff_reg_certificate_check = $this->form_validation->set_rules('nursing_staff_reg_certificate_name', 'Document', 'required');

				$bio_des_certificate_check = $this->form_validation->set_rules('bio_des_certificate_name','Document', 'required');

				$society_noc_check = $this->form_validation->set_rules('society_noc_name', 'Document','required');

				$fire_noc_check = $this->form_validation->set_rules('fire_noc_name', 'Document', 'required');
			
			} else {
				// echo'sss<pre>';print_r($data);exit;
				$data['status'] = '2';
	            $data['messg'] = 'Please choose the documents.';
			}

		} else {

			$applicant_email_id_check = $this->form_validation
                ->set_rules('applicant_email_id','applicant email id','required|valid_email')->run();

            $applicant_mobile_no_check = $this->form_validation
                ->set_rules('applicant_mobile_no','applicant mobile no','required|regex_match[/^[0-9]{10}$/]')->run();


			$ownership_agreement_check = $this->form_validation->set_rules('ownership_agreement_name', 'Document', 'required');
		                            
			$tax_receipt_check = $this->form_validation->set_rules('tax_receipt_name', 'Document', 'required');
			$doc_certificate_check = $this->form_validation->set_rules('doc_certificate_name', 'Document', 'required');

			$reg_certificate_check = $this->form_validation->set_rules('reg_certificate_name', 'Document', 'required');

			$staff_certificate_check = $this->form_validation->set_rules('staff_certificate_name', 'Document', 'required');

			$nursing_staff_deg_certificate_check = $this->form_validation->set_rules('nursing_staff_deg_certificate_name', 'Document', 'required');

			$nursing_staff_reg_certificate_check = $this->form_validation->set_rules('nursing_staff_reg_certificate_name', 'Document', 'required');

			$bio_des_certificate_check = $this->form_validation->set_rules('bio_des_certificate_name','Document', 'required');

			$society_noc_check = $this->form_validation->set_rules('society_noc_name', 'Document','required');

			$fire_noc_check = $this->form_validation->set_rules('fire_noc_name', 'Document', 'required');
		}
		// echo'<pre>';var_dump(!$applicant_address_check);exit;
		if(	!$applicant_name_check || !$applicant_email_id_check || !$applicant_mobile_no_check || 
			!$applicant_address_check || !$applicant_nationality_check || !$technical_qualification_check || !$hospital_name_check || !$contact_no_check||
			!$contact_person_check || !$hospital_address_check || !$floor_space_check || 
			!$maternity_beds_check || !$tax_receipt_check||	!$patient_beds_check || 
			!$ownership_agreement_check || !$doc_certificate_check || !$reg_certificate_check ||
			!$staff_certificate_check || !$nursing_staff_deg_certificate_check ||
			!$nursing_staff_reg_certificate_check || !$bio_des_certificate_check || !$society_noc_check || 
			!$fire_noc_check
		) {

            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {
        	// echo'<pre>';print_r('ijij');exit;
        	$config['upload_path']   = './uploads/hospital';
	  		$config['allowed_types'] = 'pdf|jpg|png|docx';
	  		$config['max_size']      = '0';
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
					// echo'<pre>';print_r($uploaded_data);//exit;
					$image_data = array(
						'image_name' => $uploaded_data['orig_name'],
						'image_enc_name' => $uploaded_data['file_name'],
						'image_path' => base_url().'uploads/hospital/'.$uploaded_data['file_name'],
						'image_size' => $uploaded_data['file_size'],
						'status' => '1',
						'is_deleted' => '0',
						'created_at' => date('Y-m-s H:i:s'),
						'updated_at' => date('Y-m-s H:i:s'),
					); 

					$result = $this->upload_files($image_data);
					// echo'ss<pre>';print_r($result);exit;

					if($result != null) {
						$upload_array[$key.'_id'] = $result;
					} else {
						$upload_array[] = array($key => $result);
					}
				}
			}

			$dept_id = $this->department_table->getDepartmentByName('Medical');
			$sub_dept_id = $this->sub_department_table->getSubDepartmentByName('hospital');

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
				unset($_POST['reg_certificate_name']);
				unset($_POST['staff_certificate_name']);
				unset($_POST['nursing_staff_deg_certificate_name']);
				unset($_POST['nursing_staff_reg_certificate_name']);
				unset($_POST['bio_des_certificate_name']);
				unset($_POST['society_noc_name']);
				unset($_POST['fire_noc_name']);

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

					$result = $this->hospital_applications_table->insert($insert_data);
					
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

						$result = $this->hospital_applications_table->update($update_data,$app_id);
						
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
        $hospitalList = $this->hospital_applications_table->getRows($_POST);
        // echo'<pre>';print_r($hospitalList);exit;
        $i = $_POST['start'];

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
            $sub_dept_id = $this->sub_department_table->getSubDepartmentByName('hospital');
            $status_details = $this->App_status_level_table->getAllStatusById($status);
            // echo'ss<pre>';print_r($status_details);exit;
            if($status_details != null) {
            	$status_type = $status_details[0]['status_type'];
            	$status_title = $status_details[0]['status_title'];
            	if($status_type == '1') {
	            	$class = "btn-danger white";
	            } elseif($status == '2') {
	            	$class = "btn-success white";
	            } 

	            $status ='<a type="button" data-toggle="modal"  data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-sub-dept="'.$sub_dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-sm btn-danger white">'.$status_title.'</a>';
            } else {
            	$status ='NA';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';

            $action = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$hospital['ownership_agreement_id'].','.$hospital['tax_receipt_id'].','.$hospital['doc_certificate_id'].','.$hospital['reg_certificate_id'].','.$hospital['staff_certificate_id'].','.$hospital['nursing_staff_deg_certificate_id'].','.$hospital['nursing_staff_reg_certificate_id'].','.$hospital['bio_des_certificate_id'].','.$hospital['society_noc_id'].','.$hospital['fire_noc_id'].'" data-hospital="'.$hospital['id'].'" data-app="'.$hospital['app_id'].'"  data-target="#modal-doc" class="doc_button anchor "><i class=" nav-icon fas fa-file"></i></a>
            	<a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'hospital/edit/'.base64_encode($id).'" class="anchor nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';
            // echo'<pre>';print_r($request_letter_image);exit;
            $documents = '';

            $data[] = array($i,$application_no, $applicant_name, $applicant_email_id, $applicant_mobile_no,$hospital_name,$remarks,$status,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->hospital_applications_table->countAll(),
            "recordsFiltered" => $this->hospital_applications_table->countFiltered($_POST),
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function add_remarks() {
		extract($_POST);
		// echo'<pre>';print_r($_POST);exit;
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
			// echo'<pre>';print_r($status);exit;
			if($result != null) {

				$update_data = array(
					'status' => $status,
					'updated_at' => date('Y-m-d H:i:s')
				);
				// echo'<pre>';print_r($update_data);exit;
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
}
