<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *@author Dhyey Rathod
 */
require APPPATH . 'controllers/Common.php';

class PwdController extends Common {

 	protected $response ;

    protected $data ;

	public $user_id, $dept_id, $is_user;

	public function __construct(){
		parent::__construct();
		$session_userdata = $this->session->userdata('user_session');
		// print_r($session_userdata);exit;
		$this->user_id = $session_userdata[0]['user_id'];
		$this->dept_id = $session_userdata[0]['dept_id'];
		$this->is_user = $session_userdata[0]['is_user'];

		$this->data = array();$this->response = array();
	}

	public function index()	{
		$dept_id = $this->get_dept_id_by_name('pwd');
		$data['permission_type'] = $this->pwd_applications_table->get_permission_type();
		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id,$this->authorised_user['role_id']);
		$this->load->view('applications/pwd/index',$data);
	}

	public function create() {
		$app_refrence = json_decode(base64_decode($this->input->get('app_refrence')));
		if (!empty($app_refrence)) {
			$data['application'] = $this->pwd_applications_table->getAllApplicationsDetailsById($app_refrence->id);
			$data['company_address'] = $this->pwd_applications_table->getCompnayAddressBYCompID($data['application']['company_name']);
			$data['request_letter'] = $this->image_details_table->getImageDetailsById($data['application']['request_letter_id']);
			$data['geo_location_docs'] = $this->image_details_table->getImageDetailsById($data['application']['geo_location_map_id']);
		}
		$data['app_id'] = $this->get_last_app_id();
		$data['dept_id'] = $this->get_dept_id_by_name('pwd');
		$data['company_names'] = $this->pwd_applications_table->getAllCompanyData();
		$data['road_type'] = $this->pwd_applications_table->getAllRoadType();
		$data['road'] = $this->get_all_road_type();	

		$data['permission_types'] = $this->pwd_applications_table->get_permission_type();

		$this->load->view('applications/pwd/create',$data);
	}

	public function edit() {

		$pwd_id = base64_decode($this->uri->segment(3));
		$result = $this->pwd_applications_table->getAllApplicationsDetailsById($pwd_id);

		$request_letter_image = $result['request_letter_id'];
		$geo_map_image = $result['geo_location_map_id'];
		$get_attachments = $this->image_details_table->getImageDetailsById($request_letter_image);
		$request_letter_image_name = array('request_letter_name' => $get_attachments['image_name'], 'request_letter' => $get_attachments['image_path']);

		$geo_map_image = $result['geo_location_map_id'];
		$get_attachments = $this->image_details_table->getImageDetailsById($geo_map_image);
		$geo_map_image_name = array('geo_name' => $get_attachments['image_name'],'geo_location_map' => $get_attachments['image_path']);


		$data['road_type'] = $this->pwd_applications_table->getAllRoadType();
		$data['application_roadtype'] = $this->pwd_applications_table->getPwdRoadType($pwd_id);
		$data['company_names'] = $this->pwd_applications_table->getAllCompanyData();
		$data['company_address'] = $this->pwd_applications_table->getCompnayAddressBYCompID($result['company_name']);

		$data['defect_laiblities'] = $this->pwd_applications_table->get_defect_laiblity();
 

		$data['permission_types'] = $this->pwd_applications_table->get_permission_type();

		$data['defect_laiblity'] = $this->pwd_applications_table->get_defect_laiblity();

		$data['users'] = array_merge($result,$request_letter_image_name,$geo_map_image_name);



		$this->load->view('applications/pwd/edit',$data);
	}

	public function save()
	{
		$app_id = $this->get_last_app_id();
		$is_refrence = ($this->input->post('reference_no') != 0) ? TRUE : FALSE ;
		if ($is_refrence) {
			$refrence_number = $this->input->post('reference_no');
			$refrence_app = $this->pwd_applications_table->getJointVisitByRefrence($refrence_number);
			if (array_sum($this->input->post('total_length')) != $refrence_app->joint_visit_length) {
				$data['status'] = '2';
				$data['messg'] = 'Expandend length missmatch.Kindly enter the length mentioned in the joint visit letter.';
				echo json_encode($data);exit();
			} 
		} 
		$datastack = array(
			'app_id' => ++$app_id,
			'user_id' => $this->authorised_user['user_id'],
			'applicant_name' => $this->security->xss_clean($this->input->post('applicant_name')),
			'applicant_email_id' => $this->security->xss_clean($this->input->post('applicant_email_id')),
			'applicant_mobile_no' => $this->security->xss_clean($this->input->post('applicant_mobile_no')),
			'applicant_alternate_no' => $this->security->xss_clean($this->input->post('applicant_alternate_no')),
			'applicant_address' => $this->security->xss_clean($this->input->post('applicant_address')),
			'company_name' => $this->security->xss_clean($this->input->post('company_name')),
			'company_address' => $this->security->xss_clean($this->input->post('company_address')),
			'landline_no' => $this->security->xss_clean($this->input->post('landline_no')),
			'contact_person' => $this->security->xss_clean($this->input->post('contact_person')),
			'name_company_head' => $this->security->xss_clean($this->input->post('name_company_head')),
			'company_head_number' => $this->security->xss_clean($this->input->post('company_head_number')),
			'company_head_designation' => $this->security->xss_clean($this->input->post('company_head_designation')),
			'assistant_name' => $this->security->xss_clean($this->input->post('assistant_name')),
			'assistant_number' => $this->security->xss_clean($this->input->post('assistant_number')),
			'assistant_designation' => $this->security->xss_clean($this->input->post('assistant_designation')),
			'letter_no' => $this->security->xss_clean($this->input->post('letter_no')),
			'letter_date' => $this->security->xss_clean($this->input->post('letter_date')),
			'road_name' => trim($this->security->xss_clean($this->input->post('road_name'))),
			'landmark' => $this->security->xss_clean($this->input->post('landmark')),
			'work_start_date' => date("Y/m/d",strtotime($this->input->post('work_start_date'))),
			'work_end_date' => date("Y/m/d",strtotime($this->input->post('work_end_date'))),
			'days_of_work' => $this->input->post('total_days_of_work'),
			'permission_type' => $this->input->post('permission_type'),
			'status' => 0,'reference_no' => $this->input->post('reference_no'),
		);
		$image_upload_error = '';$document_data_stack = [];
		foreach ($_FILES as $key => $oneImage) {		
			if (!empty($_FILES[$key]['name']) && $_FILES[$key]['error'] == 0) {	
				$this->upload->initialize(pwd_document_upload());
	            $_FILES['fileInput']['name'] = $oneImage['name'];
	            $_FILES['fileInput']['type'] = $oneImage['type'];
	            $_FILES['fileInput']['tmp_name'] = $oneImage['tmp_name'];
	            $_FILES['fileInput']['error'] = $oneImage['error'];
	            $_FILES['fileInput']['size'] = $oneImage['size'];
	            if ($this->upload->do_upload('fileInput')) {
	            	$one_document_document = array(
	            		'image_name' => $oneImage['name'],
	            		'image_enc_name' => $this->upload->data('file_name'),
	            		'image_path' => base_url('uploads/pwd/').$this->upload->data('file_name'),
	            		'image_size' => $this->upload->data('file_size'),
	            		'status' => 1,
	            		'is_deleted' => 0,
	            		'created_at' => date('Y-m-s H:i:s'),
	            		'updated_at' => date('Y-m-s H:i:s'),
	            		'file_field' => $key,
	            	);
	            	array_push($document_data_stack,$one_document_document);
	            } else { 
	            	$image_upload_error .= $this->upload->display_errors(); 
	            }
			} else if (empty($_FILES[$key]['name']) && $is_refrence){
				if ($key == "request_letter_name") $datastack['request_letter_id'] = $this->input->post('request_letter_id');
				if ($key == "geo_location_map") $datastack['geo_location_map_id'] = $this->input->post('geo_location_id'); 
			}
        }
        if ($image_upload_error == '') {
        	if (!empty($this->input->post('id')) && $this->input->post('id') != '') {
        		$pwd_application_id = $this->input->post('id');
        		unset($datastack['status']);unset($datastack['app_id']);unset($datastack['user_id']);unset($datastack['reference_no']);
        		$this->pwd_applications_table->update_pwd_application($datastack,$pwd_application_id);
        	} else {
        		if ($this->input->post('reference_no') != 0) $datastack['is_child_app'] = 1;
        		$pwd_application_id = $this->pwd_applications_table->insert($datastack);
        		$applications_details = array(
        			'application_id' => $datastack['app_id'],
        			'dept_id' => 1,
        			'status' => 1,
        			'created_at' => date('Y-m-s H:i:s'),
                    'updated_at' => date('Y-m-s H:i:s'),
        		);
        		$this->pwd_applications_table->insert_application_details($applications_details);
        	}
        	$road_information = array();$road_type = $this->input->post('road_type');
        	if (count($road_type) > 0) $this->pwd_applications_table->deletePwdRoadType($pwd_application_id);
			foreach ($road_type as $key => $oneRodeDetails) {
				$road_information = array();
				$road_information['pwd_app_id'] = $pwd_application_id;
				$road_information['surface_rate'] = $this->pwd_applications_table->getAllRoadType($oneRodeDetails)->rate;
				$road_information['road_type_id'] = $oneRodeDetails;
				$road_information['start_point'] = $this->input->post('start_point')[$key];
				$road_information['end_point'] = $this->input->post('end_point')[$key];
				$road_information['total_length'] = $this->input->post('total_length')[$key];
				$defectlaib_id = !empty($this->input->post('defect_laiblity')[$key]) ? $this->input->post('defect_laiblity')[$key] : 0 ;
				$road_information['defectlaib_id'] = $defectlaib_id;
				$road_information['mul_factor'] = ($defectlaib_id != 0) ? $this->pwd_applications_table->get_defect_laiblity($defectlaib_id)->mul_factor : 0 ;
				$road_information['ri_chargers'] = $road_information['surface_rate'] * $road_information['total_length'] * $road_information['mul_factor'];
				$road_information['ri_chargers'] = get_ri_chargers($road_information['surface_rate'],$road_information['total_length'],$road_information['mul_factor']);
				$road_information['supervision_charges'] = get_supervision_charges($road_information['ri_chargers']);
				$road_information['land_rant'] = get_land_rant($road_information['total_length']);
				$road_information['total_ri_charges'] = get_total_ri_charges( $road_information['ri_chargers'] , $road_information['supervision_charges'] , $road_information['land_rant'] );
				$road_information['security_deposit'] = get_security_deposit($road_information['ri_chargers']);
				$road_information['sgst'] = get_SGST($road_information['total_ri_charges']);
				$road_information['cgst'] = get_cgst($road_information['total_ri_charges']);
				$road_information['total_gst'] = get_total_gst($road_information['sgst'],$road_information['cgst']);
				$road_information['role_id'] = $this->authorised_user['role_id'];
				$this->pwd_applications_table->insertRoadInformation($road_information);
			}
        	$this->pwd_applications_table->storeMultipleDocument($document_data_stack,$pwd_application_id);
        	$data['status'] = '1';
			$data['messg'] = 'Application updated successfully.';
        } else {
        	$data['status'] = '2';
			$data['messg'] = $image_upload_error;
        }
		echo json_encode($data);
	}

	public function save_old() {

		extract($_POST);


        if (!empty($_FILES)) {

            $request_letter_check = $this->form_validation->set_rules('request_letter_name', 'Document', 'required');
		                            
			$geo_location_map_check = $this->form_validation->set_rules('geo_map_name', 'Document', 'required');
        } else {

            $data['status'] = '2';
            $data['messg'] = 'Please choose the documents.';
        }


		if(TRUE) {

            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {

        	echo'<pre>';print_r('hihi');exit;
        	$config['upload_path']   = './uploads/pwd';
	  		$config['allowed_types'] = 'pdf|docx';
	  		$config['max_size']      = '0';
	  		$config['encrypt_name'] = TRUE;

	  		$status = TRUE;

			$this->upload->initialize($config);

            $upload_array = array();
            // echo'<pre>';print_r($_FILES);exit;
            foreach ($_FILES as $key => $files) {
            	// echo'<pre>';print_r($key);exit;
                if(!$this->upload->do_upload($key)) {
                    $data['status'] = '2';$status = FALSE;
                    $data['messg'] = $this->upload->display_errors();
                    // echo'<pre>';print_r($data);exit;
                } else {
                    $uploaded_data = $this->upload->data();
                    // echo'ss<pre>';print_r($uploaded_data);//exit;
                    $image_data = array(
                        'image_name' => $uploaded_data['orig_name'],
                        'image_enc_name' => $uploaded_data['file_name'],
                        'image_path' => base_url().'uploads/pwd/'.$uploaded_data['file_name'],
                        'image_size' => $uploaded_data['file_size'],
                        'status' => '1',
                        'is_deleted' => '0',
                        'created_at' => date('Y-m-s H:i:s'),
                        'updated_at' => date('Y-m-s H:i:s'),
                    ); 
                    // echo'<pre>';print_r($image_data);exit;
                    $result = $this->upload_files($image_data);
                    // echo'ss<pre>';print_r($result);exit;

                    if($result != null) {
                        $upload_array[$key.'_id'] = $result;
                    } else {
                        $upload_array[] = array($key => $result);
                    }
                }
            }

			// if(!$this->upload->do_upload("request_letter")) {
			// 	$data['status'] = '2';
	  //           $data['messg'] = $this->upload->display_errors();
			// } else {

			// 	$letter_uploaded_data = $this->upload->data();
				
			// 	$image_data = array(
			// 		'image_name' => $letter_uploaded_data['orig_name'],
			// 		'image_enc_name' => $letter_uploaded_data['file_name'],
			// 		'image_path' => base_url().'uploads/pwd/'.$letter_uploaded_data['file_name'],
			// 		'image_size' => $letter_uploaded_data['file_size'],
			// 		'status' => '1',
			// 		'is_deleted' => '0',
			// 		'created_at' => date('Y-m-s H:i:s'),
			// 		'updated_at' => date('Y-m-s H:i:s'),
			// 	); 

			// 	$result = $this->upload_files($image_data);
			// 	if($result != null) {
			// 		$request_letter_id = array('request_letter_id' => $result);
			// 	} else {
			// 		$request_letter_id = array('request_letter_id' => $result);
			// 	}
			// }

			// if(!$this->upload->do_upload("geo_location_map")) {
			// 	$data['status'] = '2';
	  //           $data['messg'] = $this->upload->display_errors();
			// } else {
			// 	$geo_uploaded_data = $this->upload->data();

			// 	$image_data = array(
			// 		'image_name' => $geo_uploaded_data['orig_name'],
			// 		'image_enc_name' => $geo_uploaded_data['file_name'],
			// 		'image_path' => base_url().'uploads/pwd/'.$geo_uploaded_data['file_name'],
			// 		'image_size' => $geo_uploaded_data['file_size'],
			// 		'status' => '1',
			// 		'is_deleted' => '0',
			// 		'created_at' => date('Y-m-s H:i:s'),
			// 		'updated_at' => date('Y-m-s H:i:s'),
			// 	); 

			// 	$result = $this->upload_files($image_data);
			// 	if($result != null) {
			// 		$geo_map_id= array('geo_map_id' => $result);
			// 	} else {
			// 		$geo_map_id = array('geo_map_id' => $result);
			// 	}
			// }




		 	$dept_id = $this->department_table->getDepartmentByName('PWD');

			if(!empty($dept_id)) {

				if($id =='') {

					if ($status == TRUE) {
							$applications_details = array(
								'dept_id' => $dept_id,
								'status' => '1',
								'is_deleted' => '0',
								'created_at' => date('Y-m-s H:i:s'),
								'updated_at' => date('Y-m-s H:i:s'),
							);
						$inserted_app_id = $this->applications_details_table->insert($applications_details);





						$app_id_array = array('app_id' => $inserted_app_id); 
						// unset($_POST['dept_id']);
						unset($_POST['application_no']);

						$extra = array(
							'user_id' => $this->user_id,
			                'status' => '1',
			                'is_deleted' => '0',
			                'created_at' => date('Y-m-d H:i:s'),
			                'updated_at' => date('Y-m-d H:i:s'),
			            );

			            $insert_data = array_merge($_POST,$app_id_array,$upload_array,$extra);

			            unset($insert_data['request_letter_name']);

						unset($insert_data['geo_map_name']);

						if (!empty($insert_data['request_letter_name_id'])) {
							$insert_data['request_letter_id'] = $insert_data['request_letter_name_id'];
							unset($insert_data['request_letter_name_id']);
						}					

						$result = $this->pwd_applications_table->insert($insert_data);
						if($result !=null) {
							$data['status'] = '1';
							$data['messg'] = 'Application added successfully.';
		 				} else {
		 					$data['status'] = '2';
							$data['messg'] = 'Oops! Something went wrong.';
	 					}	
					}
				} else {

					// update form
					$applications_details = array(
						'updated_at' => date('Y-m-d H:i:s'),
					);

					$updated_app_result = $this->applications_details_table->update($applications_details,$app_id);

					if($updated_app_result) {
						$extra = array(
			                'updated_at' => date('Y-m-d H:i:s'),
			            );
			            $update_data = array_merge($_POST,$upload_array,$extra);
			            // echo'<pre>';print_r($update_data);exit;
			            unset($update_data['application_no']);
			            unset($update_data['request_letter_name']);
			            unset($update_data['geo_map_name']);
			            // echo'<pre>';print_r($update_data);exit;
						$result = $this->pwd_applications_table->update($update_data,$app_id);

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

    	$pwdList = $this->pwd_applications_table->getAuthorityData(FALSE,$_POST);

    	// echo "<pre>";echo $this->db->last_query();exit();

    	$pwd_list_count = $this->pwd_applications_table->getAuthorityData(TRUE,$_POST);
    	
        $i = $_POST['start'];

        foreach($pwdList as $pwd){

            $i++;
            $id = $pwd['id'];

            if($pwd['app_id'] != null) {
              	$app_val = 'MBMC-00000'.$pwd['app_id'];
              	$app_no = $app_val;
          	} else {
            	$app_no = 'MBMC-000001';
          	}

          	$file_closer_class = ($pwd['file_closure_status'] == 1) ? "file_close_class" : " ";

            $application_no = $app_no;
            $applicant_name = $pwd['applicant_name'];
            $applicant_email_id = $pwd['applicant_email_id'];
            $applicant_mobile_no = $pwd['applicant_mobile_no'];
            $applicant_alternate_no = $pwd['applicant_alternate_no'];
            $applicant_address = $pwd['applicant_address'];
            $company_name = $pwd['one_company_name'];
			$landline_no = $pwd['landline_no'];
            $contact_person = $pwd['contact_person'];
            $letter_no = $pwd['letter_no'];
            $letter_date = $pwd['letter_date'];
            $days_of_work = '<span class="'.$file_closer_class.'">'.$pwd['days_of_work'].'<span>';
            $status = $pwd['status'];
            $dept_id = $this->applications_details_table->getDeptId($pwd['app_id'])['dept_id'];
            $status_details = $this->App_status_level_table->getAllStatusById($status);
            if($status_details != null) {
            	$status_type = $status_details[0]['status_type'];
            	$status_title = $status_details[0]['status_title'];
            	if($status_type == '1') {
	            	$class = "btn-danger white";
	            } elseif($status == '2') {
	            	$class = "btn-success white";
	            } 

	            $status ='<a type="button" data-toggle="modal" ward_id = '.$pwd['fk_ward_id'].' data-remarkAccess="'.($pwd['last_approved_role_id'] <= $this->authorised_user['role_id']).'" data-pwd="'.$pwd['id'].'" data-app="'.$pwd['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-sm btn-danger white ">'.$status_title.'</a>';
            } else {
            	$status ='<a type="button" data-toggle="modal" ward_id = '.$pwd['fk_ward_id'].' data-remarkAccess="'.($pwd['last_approved_role_id'] <= $this->authorised_user['role_id']).'"  data-pwd="'.$pwd['id'].'" data-app="'.$pwd['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-sm btn-danger white">Awaiting</a>';
            }
            

            $remarks = '<a type="button" data-toggle="modal" data-dept="1" data-pwd="'.$pwd['id'].'" data-pwd="'.$pwd['id'].'" data-app="'.$pwd['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';


            if ( $pwd['permition_latter_count'] != 0 && empty($pwd['joint_visit_creater_id']) && $pwd['file_closure_status'] == 0 && $this->authorised_user['role_id'] > 3 && $pwd['is_child_app'] != 1) {
            	$joint_visit_action = '<a id="joint_visit_action" data-microtip-position="top" role="tooltip" aria-label="Joint visit" href="javaScript:void(0)"><i app_id="'.$pwd['app_id'].'" class="fas fa-handshake fa-lg"></i></a>';
            } else {
        		$joint_visit_action = '';
			}


			if (!empty($pwd['joint_visit_creater_id']) && $pwd['joint_visit_creater_id'] == $this->authorised_user['user_id'] && $pwd['file_closure_status'] == 0) { 
				$refrence_order = '<a id="refrence_order" data-microtip-position="top" role="tooltip" aria-label="Send refrence order" href="javaScript:void(0)"><i app_id="'.$pwd['app_id'].'" class="fas fa-sync-alt fa-lg"></i></a>';
			} else {
				$refrence_order = '';
			}
			

			if ($pwd['payment_done'] != 0) {
				if($pwd['file_closure_status'] == 0 && $role_id == '3'){
					$payment_reqeust_option = "<a data-microtip-position='top' role='tooltip' aria-label='Payment verification' class='payment_verification_selecter' href='javaScript:void(0)'><i class='fa fa-check fa-lg' application_id=".$pwd['id']."></i></a>";
				}else{
					$payment_reqeust_option = "";
				}
			} else {
				$payment_reqeust_option = "";
			}



			
			if ($pwd['extention_requested'] == 1 && $pwd['file_closure_status'] == 0) {
				$extentionOption = "<a data-microtip-position='top' role='tooltip' aria-label='Extention Request' application_id=".$pwd['id']." class='extention_approval_selecter' href='javaScript:void(0)'><i class='fas fa-arrow-alt-circle-right fa-lg' app_id=".$pwd['app_id']."></i></a>";
			} else {
				$extentionOption = "";
			}

			if ($pwd['file_ableto_close'] != 0 && $pwd['file_closure_status'] == 0) {
				$fileCloseOption = '<a aria-label="Close file" data-microtip-position="top" role="tooltip" href="javaScript:void(0)"><i application_id='.$pwd['id'].' class="fas fa-power-off fa-lg file_close_modal_selecter"></i></a>';
			} else {
				$fileCloseOption = '';
			}

			$permition_latter_url = base_url('letters/permission_letter/').base64_encode($pwd['id'])."/".base64_encode($pwd['app_id']);

			if ($pwd['permition_latter_count'] != 0) {
				$permition_latter = '<a href="'.$permition_latter_url.'" type="button" target="_blank" class="btn btn-primary btn-sm">Permission Letter</a>';
			} else {
				$permition_latter = '-';
			}

            $action = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal"
                        data-image = "'.$pwd['request_letter_id'].','.$pwd['geo_location_map_id'].'" data-geo = "'.$pwd['geo_location_map_id'].'" data-pwd="'.$pwd['id'].'" 
                        data-app="'.$pwd['app_id'].'"  
                        data-target="#modal-doc" 
                        class="anchor nav-link-icon doc_button">
                        <i class=" nav-icon fas fa-file"></i>
                        </a>
                        '.$joint_visit_action.'
                        '.$extentionOption.'
                        '.$payment_reqeust_option.'
                        '.$refrence_order.'
                        '.$fileCloseOption.'
                		<a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'pwd/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $documents = '';


            $documents = '<a type="button" data-toggle="modal" data-image = "'.$pwd['request_letter_id'].','.$pwd['geo_location_map_id'].'" data-geo = "'.$pwd['geo_location_map_id'].'" data-pwd="'.$pwd['id'].'" data-app="'.$pwd['app_id'].'"  data-target="#modal-doc" class="doc_button btn btn-block btn-info white">Docs</a>';

            $documents = '<a type="button" data-toggle="modal" data-image = "'.$pwd['request_letter_id'].','.$pwd['geo_location_map_id'].'" data-geo = "'.$pwd['geo_location_map_id'].'" data-pwd="'.$pwd['id'].'" data-app="'.$pwd['app_id'].'"  data-target="#modal-doc" class="doc_button btn btn-block btn-info white">Docs</a>';

            $refrence_column = ($pwd['dispatched_refrence_number'] != 0) ? $pwd['reference_no'] : "-" ;

            if ( file_exists(FCPATH.'uploads/demand_note/demand_note_'.$pwd['id'].'.pdf') ) {
            	$Generate_Letter = "<a target='_blank' class='btn btn-info btn-sm' href='".base_url('uploads/demand_note/demand_note_'.$pwd['id'].'.pdf')."' style='font-size: small'>Demand note</a>";
            } else {
            	$Generate_Letter = '<p>Not generated</p>';
            }            

            $data[] = array($i,$application_no, $applicant_name, $applicant_email_id, $applicant_mobile_no,$refrence_column,
            	$company_name, $days_of_work, $permition_latter ,$Generate_Letter ,$remarks,$status,$pwd['created_at']	,$action,$file_closer_class);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $pwd_list_count,
            "recordsFiltered" => $pwd_list_count,
            "data" => $data,
        );
        
        echo json_encode($output);
	}

	public function send_demandnote($app_remark_stack)
	{
		$this->data['application'] = $this->pwd_applications_table->getApplicationBYappID($app_remark_stack['app_id']);
		$file_name = "demand_note_".$this->data['application']->id.".pdf";
		$this->data['file_url'] = base_url().'uploads/demand_note/'.$file_name;
		$html_str = $this->load->view('email_templates/demad_note',$this->data,TRUE);
		$email_stack = array(
			'to' => $this->data['application']->applicant_email_id,
	        'subject' => "Demad note",
	        'body' => $html_str,
	    );
	    $this->email_trigger->codeigniter_mail($email_stack);
	    $latter_generation = array(
	    	'dept_id' =>  $this->authorised_user['dept_id'],
	    	'app_id' => $this->data['application']->app_id,
	    	'latter_type_id' => 1,
	    	'status' => 1,
	    	'file_name' => $file_name,
	    );
	    $this->pwd_applications_table->insertLatterGenration($latter_generation);
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
        		'user_id' => $user_id,
                'role_id' => $role_id,
        		'remarks'=> $remarks, 
                'status_id' => $status,
                'status' => '1',
                'is_deleted' => '0',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-s H:i:s'),
            );

			$result = $this->application_remarks_table->insert($insert_data);

			if($result != null) {

				$update_data = array(
					'status' => $status,
					'updated_at' => date('Y-m-d H:i:s'),
				);

				$url = base_url().'pdf/demand_note.php?id='.$pwd_id;
				$headers = array('Content-Type: application/json');
				$ch = curl_init();

				// Set the url, number of GET vars, GET data
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_POST, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

				// Execute request
				$result = curl_exec($ch);

				// Close connection
				curl_close($ch);
				$successStatus = json_decode($result);

				$last_role_of_dept = $this->pwd_applications_table->getLastRoleByDeptID($this->authorised_user['dept_id']);
	        	if ($this->authorised_user['role_id'] == $last_role_of_dept->role_id) {
					$this->send_demandnote($insert_data);
	        	}

	        	$application = $this->pwd_applications_table->getApplicationBYappID($app_id);


	        	while (true) { $pwd_reference_no = random_string('nozero', 5);
		    		if ($this->form_validation->is_unique($pwd_reference_no,'pwd_applications.reference_no')) break ;
		    	}
				if ($role_id == 3) {
					if ($application->reference_no == 0) $update_data['reference_no'] = $pwd_reference_no ;
					$update_data['clerk_approvel_date'] = date('Y-m-d H:i:s');
					$update_data['fk_ward_id'] = $this->input->post('ward_select');
				}

				if ($this->db->get_where('app_status_level',['status_id'=>$this->input->post('status')])->row()->is_rejected) {
					$update_data['is_deleted'] = '1';
					$html_str = $this->load->view('email_templates/application_rejected',['application'=>$application,'remark'=>$remarks],TRUE);
					$email_stack = array(
						'to' => $application->applicant_email_id,
						'body' => $html_str,
						'subject' => "Application MBMC-00000".$application->app_id." Rejected",
					);
					$this->email_trigger->codeigniter_mail($email_stack);
				}
				$final_result = $this->pwd_applications_table->update($update_data,$app_id);
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

	public function pwduserlist(){

		// $data['userApp'] = $this->pwd_applications_table->getUserApp($this->user_id);	
		$this->load->view('applications/pwd/pwduserlist');

	}

	public function getUserApplicationList(){
		$searchVal = $_POST['search']['value'];
		$i = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir'];

		// echo "<pre>";print_r($_POST);exit();
		
		if($this->input->post("fromDate") != ''){
			$fromDate = date("Y-m-d",strtotime($this->input->post("fromDate")));
		}else{
			$fromDate = "";
		}
		
		if($this->input->post("toDate") != ''){
			$toDate = date("Y-m-d", strtotime($this->input->post("toDate")));
		}else{
			$toDate = "";
		}

		
		$appData = $this->pwd_applications_table->getUserData($searchVal, $fromDate, $toDate, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder, $this->user_id);


		if(!empty($appData)){
			$srNo = 1;

			foreach($appData['result'] as $keyData => $valueData){


				$accessExtention = $this->pwd_applications_table->checkLatterHasSendByAppID($valueData['app_id'],'permission_letter');

				$dateDiffrent = strtotime($valueData['work_end_date']) >= strtotime(date("Y-m-d"));

				if (!empty($accessExtention) && $accessExtention->latter_type_id == 2 && $valueData['file_closure_status'] == 0 && $dateDiffrent) {
					$extentionOption = "<a class='extention_selecter' data-microtip-position='top' role='tooltip' aria-label='Create extension' href='javascript:void(0)'> <i class='fas fa-arrow-alt-circle-right fa-lg' application_id=".$valueData['app_id']."></i> </a>";
				} else {
					$extentionOption = "";
				}


				if ($valueData['is_deleted'] == 1) {
					$is_deleted = "deleted_application_class";
				} else {
					$is_deleted = '';
				}

				if ($valueData['file_closure_status'] == 1) {
					$file_close_class = "file_close_class";
				} else {
					$file_close_class = '';
				}


				if ($valueData['dispatched_refrence_number'] != 0 && $valueData['file_closure_status'] == 0) {
					$joint_visit_new_app = '<a href="javascript:void(0)" data-microtip-position="top" role="tooltip" aria-label="Create refrence application" class="jv_ref_selecter"><i application_id="'.$valueData['id'].'" class="fas fa-code-branch fa-lg"></i></a>';
				} else {
					$joint_visit_new_app = '';
				}

				// $data[] = array('sr_no' => $srNo,'application_no' => 'MBMC-00000'.$valueData['app_id'], 'company_name' => $valueData['companys_name'], 'road_name' => $valueData['road_name'],'data_added' => $valueData['created_at'], 'approval_status' => $valueData['approved_by'], 'action' => '<a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'pwd/edit/'.base64_encode($valueData['id']).'" class="nav-link-icon"><i class="nav-icon fas fa-edit"></i>
				// 	</a>
				// 	<a data-microtip-position="top" role="tooltip" aria-label="Delete" application_id="'.$valueData['id'].'" href="'.base_url().'pwd/delete/'.base64_encode($valueData['id']).'" class="delete_user_application '.$is_deleted.' '. $file_close_class .' "><i class="nav-icon fas fa-trash"></i></a> '.$extentionOption.$joint_visit_new_app,'file_close'=>$file_close_class);
				$data[] = array('sr_no' => $srNo,'application_no' => 'MBMC-00000'.$valueData['app_id'], 'company_name' => $valueData['companys_name'], 'road_name' => $valueData['road_name'],'data_added' => $valueData['created_at'], 'approval_status' => $valueData['approved_by'], 'action' => '<a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'pwd/edit/'.base64_encode($valueData['id']).'" class="nav-link-icon"><i class="nav-icon fas fa-edit"></i>
					</a>
					<a data-microtip-position="top" role="tooltip" aria-label="Delete" application_id="'.$valueData['id'].'" href="'.base_url().'pwd/delete/'.base64_encode($valueData['id']).'" class="delete_user_application '.$is_deleted.' '. $file_close_class .' "></a> '.$extentionOption.$joint_visit_new_app,'file_close'=>$file_close_class);
				$srNo++;
			}

		}else{
			$data[] = array('sr_no' => '','application_no' => '', 'company_name' => '', 'road_name' => 'No Data Found', 'data_added' => '', 'approval_status' => '', 'action' => '');
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $appData['cntData'][0]['cnt_id'],
            "data" => $data,
        );

        echo json_encode($output);
	}




	public function getCompanyAddressByCompID()
	{
		$company_id = $this->input->post('comapany_id');
		if (!empty($company_id) && is_numeric($company_id)) {
			$company_details = $this->pwd_applications_table->getCompanyAddByCompID($company_id);
			if (count($company_details) > 0) {
				$this->response['status'] = TRUE;
				$this->response['company_add'] = $company_details;
			} else {
				$this->response['status'] = FALSE;
			}
		} else {
			$this->response['status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function user_delete_app()
	{
		$application_id = $this->input->post('applicant_id');
		if ($this->pwd_applications_table->deleteApplicationByID($application_id)) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "Your application is deleted successfully.";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function check_defect_laiblity()
	{
		$defect_laiblity_status = $this->pwd_applications_table->checkDefectLaiblity($this->input->post('app_id'));
		if ($defect_laiblity_status != 0) {
			$this->response['laiblity_pending'] = $defect_laiblity_status;
		} else {
			$this->response['laiblity_pending'] = 0;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function check_approve_access()
	{
		$application_id = $this->input->post('app_id');
		$last_approved_remark = $this->pwd_applications_table->getLastRoleApprovedByAppID($application_id);
		if (!empty($last_approved_remark)) {
			$last_approved_remark_role_id = $last_approved_remark->role_id ; 
		} else {
			$last_approved_remark_role_id = 0;
		}
		if ($last_approved_remark_role_id <= $this->authorised_user['role_id']) {
			$this->response['access_status'] = TRUE;
		} else {
			$this->response['access_status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function joint_visit_process()
	{
		$appID = $this->input->post('app_id');
		$insert_array = array(
			'app_id' => $appID,
			'date' => $this->input->post('jv_date'),
			'length' => $this->input->post('jv_length'),
			'type' => 1,'status' => 1,
			'approved_by' => $this->authorised_user['user_id']
		);
		$this->data['application'] = $this->pwd_applications_table->getApplicationBYappID($insert_array['app_id']);
		$html_str = $this->load->view('email_templates/joint_visit',$this->data,TRUE);
		$email_stack = array(
	        'to' => $this->data['application']->applicant_email_id,
	        'subject' => "Joint Visit Letter",
	        'body' => $html_str,
		);
	    $this->email_trigger->codeigniter_mail($email_stack);
		if ($this->pwd_applications_table->create_joint_visit($insert_array, $appID)) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "Your Joint visit has been created successfully.";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function extention_process()
	{
		$app_id = base64_decode($this->input->post('app_id'));
		$this->data['application'] = $this->pwd_applications_table->getApplicationBYappID($app_id);
		$insert_array = array(
			'date' => $this->security->xss_clean($this->input->post('ext_date')),
			'to_date' => $this->security->xss_clean($this->input->post('ext_to_date')),
			'app_id' => $this->security->xss_clean($this->data['application']->app_id),
			'description' => $this->security->xss_clean($this->input->post('description')),
			'type' => 2,'status' => 1
		);
		$html_str = $this->load->view('email_templates/extentionRequest',$this->data,TRUE);
		$email_stack = array(
	        'to' => $this->data['application']->applicant_email_id,
	        'subject' => "Extention Letter Request.",
	        'body' => $html_str,
		);
		$this->email_trigger->codeigniter_mail($email_stack);
		$this->pwd_applications_table->update_pwd_application(['extention_requested'=>1],$this->data['application']->id);
		if ($this->pwd_applications_table->create_extention($insert_array)) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "Your request has been send successfully.";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function extention_approvel_datafetch()
	{
		$application = $this->pwd_applications_table->getApplicationBYappID($this->input->post('app_id'));
		if (!empty($application)) {
			$extention_data = $this->pwd_applications_table->getExtentionInfoByAppID($application->app_id);
			if (!empty($extention_data)) {
				$this->data['application'] = $application;
				$this->data['extention_data'] = $extention_data;
				$this->data['previous_all_extention'] = $this->pwd_applications_table->getPreviousAllExt($application->app_id);
				$this->response['html_str'] = $this->load->view('applications/pwd/modal/ext_apprv_memo',$this->data,TRUE);
				$this->response['status'] = TRUE;
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
	public function extention_approvel_process()
	{
		$extention_id = $this->security->xss_clean($this->input->post('extention_id'));
		$is_rejected = $this->input->post('is_rejected');
		$application = $this->pwd_applications_table->getApplicationBYappID($this->input->post('pwd_app_id'));
		$this->pwd_applications_table->updateJointVisitExtention([
				'status'=>(!empty($is_rejected)) ? 3 : 2,'approved_by'=>$this->authorised_user['user_id']
			],$extention_id);
		$this->pwd_applications_table->update_pwd_application(['extention_requested'=>0],$application->id);
		$file_url = base_url('letters/')."extension_letter/".base64_encode($application->id)."/".base64_encode($application->app_id);
		$email_content = array('application'=>$application,'file_url'=>$file_url,'is_rejected'=>(!empty($is_rejected)) ? TRUE : FALSE);
		$html_str = $this->load->view('email_templates/extention_latter',$email_content,TRUE);
		$latter_info = $this->pwd_applications_table->getLatterByLtrKey('extension_approvel');
		$email_stack = array('to'=>$application->applicant_email_id,'subject'=>$latter_info->latter_name,'body'=>$html_str);
        if ($this->email_trigger->codeigniter_mail($email_stack)) {
        	$this->response['status'] = TRUE;
        	if (!empty($is_rejected)) {
        		$this->response['message'] = "Extention Request for this application has been rejected.";
        	} else {
        		$this->response['message'] = "Reqeust has been approved and extention letter has been send to applicant.";
        	}
        } else {
        	$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function payment_verification()
	{
		$payment_information = $this->pwd_applications_table->getPaymentInfoByAppID($this->input->post('app_ai_id'));
		if (!empty($payment_information)) {
			$this->data['payment'] = $payment_information;
			$this->data['application'] = $this->pwd_applications_table->getApplicationByID($this->input->post('app_ai_id'));
			$this->response['html_str'] = $this->load->view('applications/pwd/modal/payment_verification',$this->data,TRUE);
			$this->response['status'] = TRUE;
			$this->response['payment'] = $payment_information;
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function payment_verification_process()
	{
		try {
			$data['application'] = $this->pwd_applications_table->getApplicationByID($this->input->post('app_id'));
	        $data['permition_latter_url'] = base_url('letters/')."permission_letter/".base64_encode($data['application']->id)."/".base64_encode($data['application']->app_id);
	        $data['is_approve'] = $this->input->post('is_approve');
	        $payment_id = $this->input->post('pay_id');
	        $html_str = $this->load->view('email_templates/permetion_latter',$data,TRUE);
	        $email_stack = array(
	            'to' => $data['application']->applicant_email_id,'subject' => "Permission Letter",'body' => $html_str,
	        );
	        $this->email_trigger->codeigniter_mail($email_stack);
	        if ($this->input->post('is_approve') == 1) {
		        $latter_info = $this->pwd_applications_table->getLatterByLtrKey('permission_letter');
		        $latter_generation = array(
		            'dept_id' => $this->authorised_user['dept_id'],'app_id' => $data['application']->app_id,
		            'latter_type_id' => $latter_info->id,'status' => 1,
		        );
		        $this->pwd_applications_table->insertLatterGenration($latter_generation);
	        } 
	        $update_payment = array(
	        	'approved_by' => $this->authorised_user['user_id'],'status' => ($data['is_approve'] == 1) ? 2 : 3,
	        );
		    $this->pwd_applications_table->update_payment($update_payment,$payment_id);	
	        $this->response['status'] = TRUE;
	        $this->response['message'] = ($data['is_approve'] == 1) ? "Permission letter has been send successfully." : "Payment verification for this application has been rejected.";
		} catch (Exception $e) {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function process_jv_refno()
	{
		$postStack = $this->input->post();
		$refAppStack = $this->pwd_applications_table->getAppByRefnoAppID($postStack);
		if (!empty($refAppStack)) {
			$sendUrlStack = json_encode(array(
				'id'=>$refAppStack->id,'reference_no'=>$refAppStack->reference_no
			));
			$this->response['status'] = TRUE;
			$this->response['redirect_url'] = base_url('pwd/create')."?app_refrence=".base64_encode($sendUrlStack);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = 'Please enter valid reference number.';
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function validate_jv_refno()
	{
		$application = $this->pwd_applications_table->getApplicationByID($this->input->post('app_id'));
		if ($application->reference_no == $this->input->post('js_ref_number')) {
			echo 'true';
		} else {
			echo 'false';
		}
	}
	public function get_old_joint_visit()
	{
		$app_id = $this->input->post('app_id');
		$joint_visit_data = $this->pwd_applications_table->getJointVisitByAppID($app_id);
		if (!empty($joint_visit_data)) {
			$this->response['status'] = TRUE;
			$this->response['joint_visit'] = $joint_visit_data;
		} else {
			$this->response['status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function refrence_order_by_appid()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->pwd_applications_table->getApplicationBYappID($app_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/pwd/modal/refrence_order_modal',$this->data,TRUE);			
		} else {
			$this->response['status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function create_refrence_number()
	{
		$postData = $this->input->post();
		$application = $this->pwd_applications_table->getApplicationBYappID($postData['app_id']);
		$getJointVisit = $this->pwd_applications_table->getJointVisitByAppID($application->app_id);
		$this->data['application'] = $application;
		$this->data['application_JointVisit'] = $getJointVisit;
		$this->data['post_data'] = $postData;
		$this->data['file_url'] = base_url('letters/')."jointvisit_letter/".base64_encode($application->id)."/".base64_encode($application->app_id);
		$html_str = $this->load->view('email_templates/create_refrence_number',$this->data,TRUE);
        $this->email_trigger->codeigniter_mail(['to' => $application->applicant_email_id,'subject' => "Create refrence number",'body' => $html_str]);
        if ($postData['is_close'] == 0) {
        	$latter_info = $this->pwd_applications_table->getLatterByLtrKey('joint_visit');
			$createLatter = array(
				'dept_id' => 1,'app_id' => $application->app_id,'latter_type_id' => $latter_info->id,'status' => TRUE,
			);
			$this->pwd_applications_table->insertLatterGenration($createLatter);
        	$this->pwd_applications_table->updateJointVisitExtention(['description'=>$postData['joint_visit_remark'],'status'=> 2],$getJointVisit->id);
        	$this->response['status'] = TRUE;
        	$this->response['message'] = "Reference number and joint visit letter has been send to applicant.";
        } else {
        	$this->pwd_applications_table->updateJointVisitExtention(['description'=>$postData['joint_visit_remark'],'status'=> 3,'is_deleted'=>1],$getJointVisit->id);
        	$this->response['status'] = FALSE;
        	$this->response['message'] = "No refrence application and letter genrated joint visit has been closed";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function rejected_application_test()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->pwd_applications_table->getApplicationBYappID($app_id);
		$application_status = $this->App_status_level_table->getAllStatusById($application->status);
		if ($application_status[0]['is_rejected'] == 1) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "Application has been rejected.";
		} else {
			$this->response['status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function file_close_process()
	{
		$application_id = $this->input->post('application_id');
		if ($this->pwd_applications_table->update_pwd_application(['file_closure_status'=>1],$application_id)) {
			$this->data['application'] = $this->pwd_applications_table->getApplicationByID($application_id);
			$email_stack = array(
				'to' => $this->data['application']->applicant_email_id,
		        'subject' => "Application number MBMC-00000".$this->data['application']->app_id." has been closed.",
		        'body' => $this->load->view('email_templates/file_close',$this->data,TRUE)
		    );
		    $this->email_trigger->codeigniter_mail($email_stack);
			$this->response['status'] = TRUE;
			$this->response['message'] = "Application has been close successfully!";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function check_file_close()
	{
		$app_id = $this->input->post('app_id');
		$application = $this->pwd_applications_table->getApplicationBYappID($app_id);
		if ($application->file_closure_status == 1) {
			$this->response['status'] = TRUE;
			$this->response['message'] = "This application has been close you can not do any action on this file.";
		} else {
			$this->response['status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function close_application_form()
	{
		$application_id = $this->input->post('application_id');
		$application = $this->pwd_applications_table->getApplicationByID($application_id);
		if (!empty($application)) {
			$this->data['application'] = $application;
			$this->data['security_deposit'] = $this->pwd_applications_table->getRefundableAmountByAppID($application_id);
			$this->data['childApplication'] = $this->pwd_applications_table->getChildAppsByRefrenceNumber($application->reference_no,$application->app_id);
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/pwd/modal/close_app_form',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function close_application_process()
	{
		$post_data = $this->input->post();
		$insertData = array(
			'app_id' => $this->input->post('app_id'), 
			'dept_id' => $this->authorised_user['dept_id'],'user_id' => $this->authorised_user['user_id'],
			'remark_note' => $this->input->post('remark_note'),
			'refundable_amount' => $this->input->post('refundable_amount'),'status' => 1,
			'role_id' => $this->authorised_user['role_id'],'payment_status' => $this->input->post('payment_status'),
		);
		$this->pwd_applications_table->create_application_close($insertData);
		$application = $this->pwd_applications_table->getApplicationBYappID($insertData['app_id']);
		if ($this->pwd_applications_table->update_pwd_application(['file_closure_status'=>1],$application->id)) {
			$this->data['application'] = $application;$this->data['refund_data'] = $insertData;
			$email_stack = array(
				'to' => $application->applicant_email_id,
		        'subject' => "Application number MBMC-00000".$application->app_id." has been closed.",
		        'body' => $this->load->view('email_templates/file_close',$this->data,TRUE)
		    );
		    $this->email_trigger->codeigniter_mail($email_stack);
			$this->response['status'] = TRUE;
			$this->response['message'] = "Application has been close successfully!";
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function validation_extention()
	{
		$postData = $this->input->post();
		$application = $this->pwd_applications_table->getApplicationBYappID(base64_decode($postData['application_id']));
		$application_extention = $this->pwd_applications_table->getApprovedExtention($application->app_id);



		(!empty($application_extention)) ? $last_start_date = $application_extention->date : $last_start_date = $application->work_start_date;
		$startDateValidation = (strtotime($postData['start_date']) > strtotime($last_start_date));
		$endDateValidation = (strtotime($postData['end_date']) > strtotime($last_start_date));
		if ($startDateValidation && $endDateValidation) {
			$this->response['status'] = TRUE;
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Your date range is invalid.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function get_user_extention_form()
	{
		$application = $this->pwd_applications_table->getApplicationBYappID($this->input->post('app_id'));
		if (!empty($application)) {
			$application_extention = $this->pwd_applications_table->getApprovedExtention($application->app_id);
			(!empty($application_extention)) ? $ExtStartDate = $application_extention->date : $ExtStartDate = $application->work_start_date;
			$this->data['application'] = $application;
			$this->data['extStartDate'] = $ExtStartDate;
			$this->data['extention_info'] = $application_extention;
			$this->response['status'] = TRUE;
			$this->response['html_str'] = $this->load->view('applications/pwd/modal/extention_form',$this->data,TRUE);
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function testingmailtest()
    {
    	// $word = "MBMC";
    	// echo substr('MBMC-00000237', 0, 5);
        // $email_stack = array(
        //     'to' => "ankitnaik248@gmail.com",
        //     'subject' => "Demad note",
        //     'body' => '<h1>test codeigniter email</h1>',
        // );
        // $this->email_trigger->codeigniter_mail($email_stack);
    }
}
