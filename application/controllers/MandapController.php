<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class MandapController extends Common {

	/**
 	* DepartmentsController.php
 	* @author Vikas Pandey
 	*/

	public function index() {
        $dept_id = $this->get_dept_id_by_name('Mandap');
        // echo'<pre>';print_r($dept_id);exit;
        $data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
        // echo'<pre>';print_r($data);exit;
		$this->load->view("applications/mandap/index",$data);
	}
    

    public function create() {

        $data['app_id'] = $this->get_last_app_id();
        $this->load->view('applications/mandap/create',$data);
    }

   public function edit() {

        $mandap_id = base64_decode($this->uri->segment(3));
        $result = $this->mandap_applications_table->getAllApplicationsDetailsById($mandap_id);
        // echo'<pre>';print_r($result);exit;
        $id_proof_image = $result['id_proof_id'];
        $get_attachments = $this->image_details_table->getImageDetailsById($id_proof_image);
        $id_proof_image_name = array('id_proof_name' => $get_attachments['image_name'], 'id_proof' => $get_attachments['image_path']);

        $police_noc_image = $result['police_noc_id'];
        $get_attachments = $this->image_details_table->getImageDetailsById($police_noc_image);
        $police_noc_name = array('police_noc_name' => $get_attachments['image_name'],'police_noc' => $get_attachments['image_path']);


        $request_letter_image = $result['request_letter_id'];
        $get_attachments = $this->image_details_table->getImageDetailsById($request_letter_image);
        $request_letter_image_name = array('request_letter_name' => $get_attachments['image_name'], 'request_letter' => $get_attachments['image_path']);

        // $data['sku'] = $this->get_all_road_type(); 
        $data['users'] = array_merge($result,$id_proof_image_name,$request_letter_image_name,$request_letter_image_name,$police_noc_name);
        // echo'<pre>';print_r($data);exit;
        $this->load->view('applications/mandap/edit',$data);
    }


    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;

        $applicant_name_check = $this->form_validation
                ->set_rules('applicant_name','applicant name','required')->run();

        $applicant_address_check = $this->form_validation
                ->set_rules('applicant_address','applicant address','required')->run();

        $applicant_email_id_check = $this->form_validation
                ->set_rules('applicant_email_id','applicant email id','required|valid_email')->run();

        $applicant_mobile_no_check = $this->form_validation
                ->set_rules('applicant_mobile_no','applicant mobile no','required|regex_match[/^[0-9]{10}$/]')->run();

        $booking_date_check = $this->form_validation
                ->set_rules('booking_date','booking date','required')->run();

        $booking_reason_check = $this->form_validation
                ->set_rules('reason','booking reason','required')->run();

        $booking_address_check = $this->form_validation
                ->set_rules('booking_address','booking address','required')->run();

        // echo'ss<pre>';print_r($_FILES);exit;

        if (!empty($_FILES)) {

            $id_proof_check = $this->form_validation->set_rules('id_proof_name', 'Document', 'required');

            $request_letter_check = $this->form_validation->set_rules('request_letter_name', 'Document', 'required');

            $police_noc_check = $this->form_validation->set_rules('police_noc_name', 'Document', 'required');
                                
        } else {

            $data['status'] = '2';
            $data['messg'] = 'Please choose the documents.';
        }

        if(!$applicant_name_check || !$applicant_email_id_check || !$applicant_mobile_no_check 
            || !$booking_date_check || !$id_proof_check || !$police_noc_check || !$request_letter_check) {

            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {

            $config['upload_path']   = './uploads/mandap';
            $config['allowed_types'] = 'pdf|jpg|png|docx';
            $config['max_size']      = '0';
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            $upload_array = array();
            foreach ($_FILES as $key => $files) {
                if(!$this->upload->do_upload($key)) {
                    $data['status'] = '2';
                    $data['messg'] = $this->upload->display_errors();
                    // echo'<pre>';print_r($data);exit;
                    
                } else {
                    $uploaded_data = $this->upload->data();
                    // echo'<pre>';print_r($uploaded_data);//exit;
                    $image_data = array(
                        'image_name' => $uploaded_data['orig_name'],
                        'image_enc_name' => $uploaded_data['file_name'],
                        'image_path' => base_url().'uploads/mandap/'.$uploaded_data['file_name'],
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
            // echo'<pre>';print_r($upload_array);exit;
            $dept_id = $this->department_table->getDepartmentByName('Mandap');
            // echo'<pre>';print_r($dept_id);exit;
            if(!empty($dept_id)) {
                unset($_POST['request_letter_name']);
                unset($_POST['id_proof_name']);
                unset($_POST['police_noc_name']);

                if($id =='') {
                    // add form
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
                        'status' => '1',
                        'is_deleted' => '0',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    );

                    $insert_data = array_merge($_POST,$app_id_array,$upload_array,$extra);
                    // echo'<pre>';print_r($insert_data);exit;
                    $result = $this->mandap_applications_table->insert($insert_data);

                    if($result != null) {
                        $data['status'] = '1';
                        $data['messg'] = 'Application added successfully.';
                    } else {
                        $data['status'] = '2';
                        $data['messg'] = 'Oops! Something went wrong.';
                    }
                } else {
                    // echo'<pre>';print_r('hi');exit;
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
                        
                        $update_data['applicant_address'] = trim($update_data['applicant_address']);
                        unset($update_data['application_no']);
                        // unset($update_data['id_proof_name']);
                        // unset($update_data['address_proof_name']);
                        // echo'<pre>';print_r($update_data);exit;
                        // echo'<pre>';print_r($update_data);exit;
                        $result = $this->mandap_applications_table->update($update_data,$app_id);

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

	public function get_list()    {

        $data = $row = array();
        $session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];

        // Fetch member's records
        $mandapList = $this->mandap_applications_table->getRows($_POST);
        // echo'<pre>';print_r($mandapList);exit;
        $i = $_POST['start'];

        foreach($mandapList as $mandap){
            $i++;
            $id = $mandap['id'];

            if($mandap['app_id'] != null) {
                $app_no = 'MBMC-00000'.$mandap['app_id'];
                // $app_no = ++$app_val;
            } else {
                $app_no = 'MBMC-000001';
            }

            $application_no = $app_no;
            $applicant_name = $mandap['applicant_name'];
            $applicant_email_id = $mandap['applicant_email_id'];
            $applicant_mobile_no = $mandap['applicant_mobile_no'];
            $applicant_alternate_no = $mandap['applicant_alternate_no'];
            $applicant_address = $mandap['applicant_address'];
            $booking_date = $mandap['booking_date'];
            $booking_address = $mandap['booking_address'];
            $mandap_size = $mandap['mandap_size'];
            $status = $mandap['status'];


            $dept_id = $this->applications_details_table->getDeptId($mandap['app_id'])['dept_id'];

            $status_details = $this->App_status_level_table->getAllStatusById($status);
            // echo'<pre>';print_r($status_details);exit;
            if($status_details != null) {
                $status_type = $status_details[0]['status_type'];
                $status_title = $status_details[0]['status_title'];
                
                // if($status_type == '1') {
                //  $class = "btn-danger white";
                // } elseif($status == '2') {
                //  $class = "btn-success white";
                // } 

                $status ='<a type="button" data-toggle="modal"  data-mandap="'.$mandap['id'].'" data-app="'.$mandap['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-sm  btn-danger white">'.$status_title.'</a>';
            } else {
                $status ='NA';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-mandap="'.$mandap['id'].'" data-app="'.$mandap['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';

            $action = '
                    <a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$mandap['id_proof_id'].','.$mandap['request_letter_id'].','.$mandap['police_noc_id'].'" data-target="#modal-doc" class="anchor nav-link-icon doc_button">
                        <i class=" nav-icon fas fa-file"></i>
                    </a>
                    <a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'mandap/edit/'.base64_encode($id).'" class="anchor nav-link-icon">
                        <i class=" nav-icon fas fa-edit"></i>
                    </a>';

            // $documents = $id_proof_button .''.$address_proof_button;
            // $documents = '<a type="button" data-toggle="modal" data-image = "'.$mandap['id_proof_id'].','.$mandap['request_letter_id'].','.$mandap['police_noc_id'].'" data-target="#modal-doc" class="doc_button btn btn-block btn-info white">Docs</a>';

            $data[] = array($i,$application_no, $applicant_name,$applicant_mobile_no,$booking_address,$booking_date,$remarks,$status,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->mandap_applications_table->countAll(),
            "recordsFiltered" => $this->mandap_applications_table->countFiltered($_POST),
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
                    'updated_at' => date('Y-m-d H:i:s')
                );
                // echo'<pre>';print_r($update_data);exit;
                $final_result = $this->mandap_applications_table->update($update_data,$app_id);

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
}
