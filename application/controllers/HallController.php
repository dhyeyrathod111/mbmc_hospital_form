<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class HallController extends Common {

	/**
 	* DepartmentsController.php
 	* @author Vikas Pandey
 	*/

	public function index() {
        $dept_id = $this->get_dept_id_by_name('hall');
        // echo'<pre>';print_r($dept_id);exit;
        $data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
        // echo'<pre>';print_r($data);exit;
		$this->load->view("applications/hall/index",$data);
	}
    
    public function get_booked_hall() {
        extract($_POST);

        $booking_details = $this->hall_applications_table->getHallbookingDetailsById($sku_price_id,date('Y-m-d'));

        if($booking_details != null) {
            foreach ($booking_details as $key => $book) {
                $booking_date[] = $book['booking_date'];
            }   

            $data['status'] = '1';
            $data['date'] = $booking_date;

        } else {
            $data['status'] = '2';
            $data['date'] = '';
        }
        // echo'<pre>';print_r($data);exit;
        echo  json_encode($data);
    }

    public function create() {

        $data['app_id'] = $this->get_last_app_id();
        $dept_id = $this->get_dept_id_by_name('hall');
        // echo'<pre>';print_r($dept);exit;
        $data['sku_price'] = $this->price_master_table->getAllPriceByDeptId($dept_id);
        // echo'<pre>';print_r($data);exit;
        $this->load->view('applications/hall/create',$data);
    }

   public function edit() {

        $hall_id = base64_decode($this->uri->segment(3));
        $result = $this->hall_applications_table->getAllApplicationsDetailsById($hall_id);
        // echo'<pre>';print_r($result);exit;
        $id_proof_image = $result['id_proof_id'];
        $get_attachments = $this->image_details_table->getImageDetailsById($id_proof_image);
        $id_proof_image_name = array('id_proof_name' => $get_attachments['image_name'], 'id_proof' => $get_attachments['image_path']);

        $address_proof_image = $result['address_proof_id'];
        $get_attachments = $this->image_details_table->getImageDetailsById($address_proof_image);
        $address_proof_image_name = array('address_proof_name' => $get_attachments['image_name'],'address_proof' => $get_attachments['image_path']);
        // $data['sku'] = $this->get_all_road_type(); 
        $data['users'] = array_merge($result,$id_proof_image_name,$address_proof_image_name);

        $dept_id = $this->applications_details_table->getDeptId($result['app_id'])['dept_id'];
        // echo'<pre>';print_r($dept_id);exit;
        $data['sku_price'] = $this->price_master_table->getAllPriceByDeptId($dept_id);
        // echo'ss<pre>';print_r($data['sku_price']);exit;
        $this->load->view('applications/hall/edit',$data);
    }


    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;

        $applicant_name_check = $this->form_validation
            ->set_rules('applicant_name','applicant name','required')->run();

        $booking_date_check = $this->form_validation
                            ->set_rules('booking_date','booking date','required')->run();

        if (!empty($_FILES)) {

            $id_proof_check = $this->form_validation->set_rules('id_proof_name', 'Document', 'required');
                                
            $address_proof_check = $this->form_validation->set_rules('address_proof_name', 'Document', 'required');
        } else {

            $data['status'] = '2';
            $data['messg'] = 'Please choose the documents.';
        }

        if($id =='') {
            $applicant_email_id_check = $this->form_validation
                ->set_rules('applicant_email_id','applicant email id','required|valid_email|is_unique[hall_applications.applicant_email_id]')->run();

            $applicant_mobile_no_check = $this->form_validation
                ->set_rules('applicant_mobile_no','applicant mobile no','required|regex_match[/^[0-9]{10}$/]|is_unique[hall_applications.applicant_mobile_no]')->run();

        } else {
            // echo'<pre>';print_r($id);exit;
            $applicant_email_id_check = $this->form_validation
                ->set_rules('applicant_email_id','applicant email id','required|valid_email')->run();

            $applicant_mobile_no_check = $this->form_validation
                ->set_rules('applicant_mobile_no','applicant mobile no','required|regex_match[/^[0-9]{10}$/]')->run();

            $applicant_alternate_no_check = $this->form_validation
                ->set_rules('applicant_alternate_no','applicant alternate no','required|regex_match[/^[0-9]{10}$/]')->run();


            // $id_proof_check = $this->form_validation->set_rules('id_proof_name', 'Document', 'required');
                                    
            // $address_proof_check = $this->form_validation->set_rules('address_proof_name', 'Document', 'required');
        }

        if(!$applicant_name_check || !$applicant_email_id_check || !$applicant_mobile_no_check || !$booking_date_check || !$id_proof_check || !$address_proof_check) {

            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {

            $config['upload_path']   = './uploads/hall';
            $config['allowed_types'] = 'pdf|jpg|png|docx';
            $config['max_size']      = '0';
            $config['encrypt_name'] = TRUE;

            $this->upload->initialize($config);

            // if(!$this->upload->do_upload("id_proof")) {
            //     $data['status'] = '2';
            //     $data['messg'] = $this->upload->display_errors();
            // } else {

            //     $id_proof_uploaded_data = $this->upload->data();

            //     $image_data = array(
            //         'image_name' => $id_proof_uploaded_data['orig_name'],
            //         'image_enc_name' => $id_proof_uploaded_data['file_name'],
            //         'image_path' => base_url().'uploads/hall/'.$id_proof_uploaded_data['file_name'],
            //         'image_size' => $id_proof_uploaded_data['file_size'],
            //         'status' => '1',
            //         'is_deleted' => '0',
            //         'created_at' => date('Y-m-s H:i:s'),
            //         'updated_at' => date('Y-m-s H:i:s'),
            //     ); 

            //     $result = $this->upload_files($image_data);

            //     if($result != null) {
            //         $id_proof_id = array('id_proof_id' => $result);
            //     } else {
            //         $id_proof_id = array('id_proof_id' => $result);
            //     }
            // }

            // if(!$this->upload->do_upload("address_proof")) {
            //     $data['status'] = '2';
            //     $data['messg'] = $this->upload->display_errors();
            // } else {

            //     $address_proof_uploaded_data = $this->upload->data();

            //     $image_data = array(
            //         'image_name' => $address_proof_uploaded_data['orig_name'],
            //         'image_enc_name' => $address_proof_uploaded_data['file_name'],
            //         'image_path' => base_url().'uploads/pwd/'.$address_proof_uploaded_data['file_name'],
            //         'image_size' => $address_proof_uploaded_data['file_size'],
            //         'status' => '1',
            //         'is_deleted' => '0',
            //         'created_at' => date('Y-m-s H:i:s'),
            //         'updated_at' => date('Y-m-s H:i:s'),
            //     ); 

            //     $result = $this->upload_files($image_data);
            //     if($result != null) {
            //         $address_proof_id = array('address_proof_id' => $result);
            //     } else {
            //         $address_proof_id = array('address_proof_id' => $result);
            //     }
            // }

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
                        'image_path' => base_url().'uploads/hall/'.$uploaded_data['file_name'],
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

            $dept_id = $this->department_table->getDepartmentByName('Hall');

            if(!empty($dept_id)) {

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
                    $result = $this->hall_applications_table->insert($insert_data);

                    if($result != null) {
                        $data['status'] = '1';
                        $data['messg'] = 'Applicantion added successfully.';
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
                        unset($update_data['id_proof_name']);
                        unset($update_data['address_proof_name']);
                        // echo'<pre>';print_r($update_data);exit;
                        // echo'<pre>';print_r($update_data);exit;
                        $result = $this->hall_applications_table->update($update_data,$app_id);

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

    // public function update() {
    //     extract($_POST);
    //     if($status == '1') {
    //         $update = array(
    //             'status' => '2',
    //             'updated_at' => date('Y-m-d H:i:s')
    //         );
    //     } else {
    //         $update = array(
    //             'status' => '1',
    //             'updated_at' => date('Y-m-d H:i:s')
    //         );
    //     }

    //     $result = $this->hall_applications_table->update($update,$hall_id);

    //     if($result == true) {
    //         $data['status'] = '1';
    //         $data['messg'] = 'Department updated successfully.';
    //     } else {
    //         $messg = 'Oops! Something went wrong.';
    //         $data['status'] = '2';
    //         $data['messg'] = $messg;
    //     }
    //     // echo'<pre>';print_r($data);exit;
    //     echo json_encode($data);
    // }

	public function get_list()    {

        $data = $row = array();
        $session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];

        // Fetch member's records
        $hallList = $this->hall_applications_table->getRows($_POST);
        // echo'<pre>';print_r($hallList);exit;
        $i = $_POST['start'];

        foreach($hallList as $hall){
            $i++;
            $id = $hall['id'];

            if($hall['app_id'] != null) {
                $app_no = 'MBMC-00000'.$hall['app_id'];
                // $app_no = ++$app_val;
            } else {
                $app_no = 'MBMC-000001';
            }

            $application_no = $app_no;
            $applicant_name = $hall['applicant_name'];
            $applicant_email_id = $hall['applicant_email_id'];
            $applicant_mobile_no = $hall['applicant_mobile_no'];
            $applicant_alternate_no = $hall['applicant_alternate_no'];
            $applicant_address = $hall['applicant_address'];
            $booking_date = $hall['booking_date'];
            $booking_reason = $hall['reason'];
            $security_amount = $hall['amount'];
            $status = $hall['status'];
            // echo'<pre>';print_r($hall['sku_price_id']);exit;
            $sku_details = $this->price_master_table->getAllPriceById($hall['sku_price_id']);
            // echo'<pre>';print_r($hall_details);exit;
            if($sku_details != null) {
                $hall_type = $sku_details[0]['sku_title'];
                $hall_id = $sku_details[0]['sku_id'];
            } else {
                $hall_type = 'NA';
            }

            // $id_proof_id = $hall['id_proof_id'];
            
            // $id_proof_image = $this->image_details_table->getImageDetailsById($hall['id_proof_id']);
            // $id_proof_button = '<a type="button" data-name="Id Proof" data-path="'.$id_proof_image['image_path'].'" data-toggle="modal" onclick="get_image(this)" data-target="#modal-image" class="btn btn-block btn-success white">'.$id_proof_image['image_name'].'</a>';

            // $address_proof_id_image = $this->image_details_table->getImageDetailsById($hall['address_proof_id']);
            // $address_proof_button = '<a type="button" target="_blank" data-name="Address Proof" data-toggle="modal" data-path="'.$address_proof_id_image['image_path'].'" onclick="get_image(this)" data-target="#modal-image" class="btn btn-block btn-danger white">'.$address_proof_id_image['image_name'].'</a>';

            $dept_id = $this->applications_details_table->getDeptId($hall['app_id'])['dept_id'];

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

                $status ='<a type="button" data-toggle="modal"  data-hall="'.$hall['id'].'" data-app="'.$hall['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-status="'.$status.'" data-target="#modal-status" class="status_button btn btn-sm btn-danger white">'.$status_title.'</a>';
            } else {
                $status ='NA';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-hall="'.$hall['id'].'" data-app="'.$hall['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';

            $action = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$hall['id_proof_id'].','.$hall['address_proof_id'].'" data-target="#modal-doc" class="doc_button anchor"><i class=" nav-icon fas fa-file"></i></a>

                <a aria-label="Edit" data-microtip-position="top" role="tooltip" href="'.base_url().'hall/edit/'.base64_encode($id).'" class="nav-link-icon">
                            <i class="anchor nav-icon fas fa-edit"></i>
                        </a>
                <a aria-label="Checklist" data-microtip-position="top" role="tooltip" href="'.base_url().'hall/checklist/'.base64_encode($id).'/'.base64_encode($hall_id).'" class="anchor nav-link-icon">
                            <i class="fas fa-list"></i>
                        </a>';

            // $documents = $id_proof_button .''.$address_proof_button;
            $documents = '';

            // $data[] = array($i,$application_no, $applicant_name, $applicant_email_id, $applicant_mobile_no,
            //  $applicant_alternate_no,$applicant_address,$letter_no,$letter_date,$company_name,$landline_no,$contact_person,$road_name,$road_type,$start_point,$end_point,$total_length,$days_of_work,$documents,$remarks,$status,$action);
            $data[] = array($i,$application_no, $applicant_name,$applicant_mobile_no,$hall_type,$booking_date,$remarks,$status,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->hall_applications_table->countAll(),
            "recordsFiltered" => $this->hall_applications_table->countFiltered($_POST),
            "data" => $data,
        );
        
        echo json_encode($output);
    }

    public function check_list() {

        $hall_id = base64_decode($this->uri->segment(3));
        $sku_id = base64_decode($this->uri->segment(4));
        // echo'sss<pre>';print_r($app_id);exit;
        // echo'sss<pre>';print_r($sku_price_id);exit;
        $data['app_details'] = $this->hall_applications_table->getAllApplicationsDetailsById($hall_id);
        // echo'<pre>';print_r($data);exit; 
        $data['asset'] = $this->hall_assets_table->getassetDetailsBySkuId($sku_id);
        // echo'<pre>';print_r($app_details);exit;
        $this->load->view('applications/hall/checklist',$data);
    }

    public function asset_save() {
        // echo '<pre>';print_r($_POST);exit;
        extract($_POST);
        if($app_id !='') {
            $insert_data = array();
            foreach ($asset_id as $key => $id) {
                $insert_data = array(
                    'hall_app_id' => $app_id,
                    'asset_id' => $asset_id[$key],
                    'consumed_unit' => $asset_used_unit[$key],
                    'consumed_unit_cost' => $asset_used_unit[$key] * $asset_unit_cost[$key],
                    'defected_unit' => $defected_services[$key],
                    'defected_unit_cost' => $defected_services[$key] * $penalty_charges[$key],
                    'total_cost'=>$cost[$key],
                    'status'=> '1',
                    'is_deleted'=> '0',
                    'created_at'=> date('Y-m-s H:i:s'),
                    'updated_at' => date('Y-m-s H:i:s'),
                );

                $result = $this->hall_checklist_details_table->insert($insert_data);
            }

            if($result != null) {
                $data['messg'] = 'Checklist saved successfully.';
                $data['status'] = '1';
            } else {
                $data['messg'] = 'Oops! Something went worng.';
                $data['status'] = '2';
            }
        } else {
            $data['messg'] = 'Oops! Something went worng.';
            $data['status'] = '2';
        }

        echo json_encode($data);
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
                $final_result = $this->hall_applications_table->update($update_data,$app_id);

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
