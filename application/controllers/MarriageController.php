<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'controllers/Common.php';

class MarriageController extends Common {

    /**
    * marraigeController.php
    * @author Vikas Pandey
    */
    protected $response ;

    protected $data ;

    protected $error_message ;

    public function __construct(){
        parent::__construct();
        $this->response = array();
    }

    public function index() {
        
        $dept_id = $this->get_dept_id_by_name('marriage');

        $data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);

        // echo "<pre>";print_r($data);exit();

        $this->load->view("applications/marraige/index",$data);
    }
    
    public function get_booked_hall() {
        extract($_POST);

        $booking_details = $this->hall_applications_table->getMarriagebookingDetailsById($sku_price_id,date('Y-m-d'));

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
        echo  json_encode($data);
    }

    public function create() {

        $data['app_id'] = $this->get_last_app_id();
        $dept_id = $this->get_dept_id_by_name('hall');
        // echo'<pre>';print_r($dept);exit;
        $data['sku_price'] = $this->price_master_table->getAllPriceByDeptId($dept_id);
        // echo'<pre>';print_r($data);exit;
        $this->load->view('applications/marraige/create',$data);
    }
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



    private function filesValidations($_file)
    {
        $html_str = '';$file_type_array = array('image/jpeg','image/jpg','image/png','image/gif','image/webp','application/pdf','application/doc');
        foreach ($_file as $key => $one_file) {
            if (!in_array($one_file['type'],$file_type_array)) {
                $html_str .= str_replace('_',' ',$key)." is not proper file."."\n"; 
            }
        }
        return ($html_str != '') ? $html_str : FALSE;
    }
    public function save() 
    {
        $filesValidations_error = $this->filesValidations($_FILES);
        if ($this->form_validation->run('marriage') && !$filesValidations_error) {
            $inputStack = array(
                'application_no' => $this->security->xss_clean($this->input->post('application_no')),
                'marriage_date' => $this->security->xss_clean($this->input->post('marriage_date')),
                'husband_name' => $this->security->xss_clean($this->input->post('husband_name')),
                'husband_age' => $this->security->xss_clean($this->input->post('husband_age')),
                'husband_religious' => $this->security->xss_clean($this->input->post('husband_religious')),
                'husband_marriage_status' => $this->security->xss_clean($this->input->post('husband_marriage_status')),
                'husband_address' => $this->security->xss_clean($this->input->post('husband_address')),
                'wife_name' => $this->security->xss_clean($this->input->post('wife_name')),
                'wife_age' => $this->security->xss_clean($this->input->post('wife_age')),
                'wife_religious' => $this->security->xss_clean($this->input->post('wife_religious')),
                'wife_marriage_status' => $this->security->xss_clean($this->input->post('wife_marriage_status')),
                'wife_address' => $this->security->xss_clean($this->input->post('wife_address')),
                'priest_name' => $this->security->xss_clean($this->input->post('priest_name')),
                'priest_age' => $this->security->xss_clean($this->input->post('priest_age')),
                'priest_religious' => $this->security->xss_clean($this->input->post('priest_religious')),
                'priest_address' => $this->security->xss_clean($this->input->post('priest_address')),
            );
            $query_status = $this->marriage_table->store_marriage_application($inputStack);
            $applications_details_inputStack = array(
                'dept_id' => $this->session->userdata('user_session')[0]['dept_id'],
                'sub_dept_id' => 0,
                'status' => TRUE,
                'is_deleted' => FALSE,
            );
            $applications_details_query_status = $this->marriage_table->set_applications_details($applications_details_inputStack);
            if ($query_status->status == TRUE && $applications_details_query_status->status == TRUE) {
                foreach ($this->input->post('name') as $key => $witness_name) {
                    $witness_stack = array(
                        'marriage_id' => $query_status->last_marriage_id,
                        'name' => $this->security->xss_clean($witness_name),
                        'occupation' => $this->security->xss_clean($this->input->post('occupation')[$key]),
                        'relation' => $this->security->xss_clean($this->input->post('relation')[$key]),
                        'address' => $this->security->xss_clean($this->input->post('applicant_address')[$key]),
                    );
                    $this->marriage_table->store_marriage_witness($witness_stack);
                }
                try {   
                    foreach ($_FILES as $key => $oneImage) {
                        $this->upload->initialize(file_upload_config());
                        $_FILES['fileInput']['name'] = $oneImage['name'];
                        $_FILES['fileInput']['type'] = $oneImage['type'];
                        $_FILES['fileInput']['tmp_name'] = $oneImage['tmp_name'];
                        $_FILES['fileInput']['error'] = $oneImage['error'];
                        $_FILES['fileInput']['size'] = $oneImage['size'];
                        $this->upload->do_upload('fileInput');
                        $document_insert = array(
                            'marriage_id' => $query_status->last_marriage_id,
                            'file_name' => $this->upload->data('file_name'),
                            'file_title' => $key,
                        );
                        $this->marriage_table->store_marriage_document($document_insert);
                    }

                    $this->response['status'] = TRUE;  
                    $this->response['message'] = "Your application has been submited successfully."; 
                } catch (Exception $e) {
                    $this->response['status'] = FALSE;    
                    $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
                }
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
            }
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = validation_errors();
            $this->response['image_validation_message'] = $filesValidations_error;
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function get_marriage_data_table()
    {
        $start = $this->input->post('start');
        $length = $this->input->post('length');
        $search_value = $this->input->post('search')['value'];
        if (isset($this->input->post('order')[0])) {
            $order = $this->input->post('order')[0];
        } else {
            $order = '';
        }

        if (!empty($this->input->post('dateRange'))) {
            $dateRange = $this->input->post('dateRange');
        } else {
            $dateRange = FALSE;
        }

        if (!empty($this->input->post('active_status'))) {
            $active_status = $this->input->post('active_status');
        } else {
            $active_status = FALSE;
        }

        if (!empty($this->input->post('remark_id'))) {
            $remark_id = $this->input->post('remark_id');
        } else {
            $remark_id = FALSE;
        }

        $merrige_applications = $this->marriage_table->get_all_application_for_datatbl(FALSE,$search_value,$dateRange,$remark_id,$active_status,$order,$length,$start);

        $merrige_applications_count = $this->marriage_table->get_all_application_for_datatbl(TRUE,$search_value,$dateRange,$remark_id,$active_status,$order);

        $data_table_array = array();
        foreach ($merrige_applications as $one_application) {
            $approvel_remark_list = $this->marriage_table->get_last_dept_remark($one_application->id,$this->session->userdata('user_session')[0]['dept_id']);
            if (!empty($approvel_remark_list) && $approvel_remark_list) {
                $remark = $approvel_remark_list->status_title;
            } else {
                $remark = 'Awaiting'; 
            }
            $temp_array = array();
            $temp_array[] = $one_application->id;
            $temp_array[] = $one_application->application_no;
            $temp_array[] = $one_application->husband_name;
            $temp_array[] = $one_application->wife_name;
            $temp_array[] = $one_application->priest_name;
            $temp_array[] = $one_application->marriage_date;
            
            $temp_array[] = '<a application_id="'.base64_encode($one_application->id).'" class="add_remark_modal btn btn-sm btn-primary white">'.$remark.'</a>';

            if ($one_application->is_active) {
                $temp_array[] = '<a data_id="'.base64_encode($one_application->id).'" id="update_status" class="btn btn-success text-white">Active</a>';
            } else {
                $temp_array[] = '<a data_id="'.base64_encode($one_application->id).'" id="update_status" class="btn btn-danger text-white">Inactive</a>';
            }
            $temp_array[] = '<a href="'.base_url('/marriage/edit/'.base64_encode($one_application->id)).'"><i class="nav-icon fas fa-edit"></i></a> <a application_id="'.base64_encode($one_application->id).'" class="remarks_button_marriage"><i class="nav-icon fas fa-list"></i></a>';
            array_push($data_table_array,$temp_array);
        }
        $dataTable_response = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $merrige_applications_count,
            "recordsFiltered"=> $merrige_applications_count,
            "data"=> $data_table_array
        ];
        echo json_encode($dataTable_response);
    }
    public function edit() {
        $application_id = $this->security->xss_clean(base64_decode($this->uri->segment(3)));
        $check_app_is_present = $this->form_validation->is_unique($application_id,'marriage_application.id');
        if (is_numeric($application_id) && !$check_app_is_present) {
            $this->data['application'] = $this->marriage_table->getAppDetails($application_id);
            $this->data['witness'] = $this->marriage_table->getWitnessByAppID($application_id);
            $this->data['document'] = $this->marriage_table->getDocByAppID($application_id);
            $this->load->view('applications/marraige/edit',$this->data);
        } else {
            redirect('/marriage', 'refresh');
        }
    }
    public function edit_form_process()
    {
        if ($this->form_validation->run('marriage')) {
            $application_id = $this->security->xss_clean(base64_decode($this->input->post('marriedge_app_id')));
            $inputStack = array(
                'application_no' => $this->security->xss_clean($this->input->post('application_no')),
                'marriage_date' => $this->security->xss_clean($this->input->post('marriage_date')),
                'husband_name' => $this->security->xss_clean($this->input->post('husband_name')),
                'husband_age' => $this->security->xss_clean($this->input->post('husband_age')),
                'husband_religious' => $this->security->xss_clean($this->input->post('husband_religious')),
                'husband_marriage_status' => $this->security->xss_clean($this->input->post('husband_marriage_status')),
                'husband_address' => $this->security->xss_clean($this->input->post('husband_address')),
                'wife_name' => $this->security->xss_clean($this->input->post('wife_name')),
                'wife_age' => $this->security->xss_clean($this->input->post('wife_age')),
                'wife_religious' => $this->security->xss_clean($this->input->post('wife_religious')),
                'wife_marriage_status' => $this->security->xss_clean($this->input->post('wife_marriage_status')),
                'wife_address' => $this->security->xss_clean($this->input->post('wife_address')),
                'priest_name' => $this->security->xss_clean($this->input->post('priest_name')),
                'priest_age' => $this->security->xss_clean($this->input->post('priest_age')),
                'priest_religious' => $this->security->xss_clean($this->input->post('priest_religious')),
                'priest_address' => $this->security->xss_clean($this->input->post('priest_address')),
            );
            $query_status = $this->marriage_table->update_marriage_application($inputStack,$application_id);
            $this->marriage_table->delete_witness_beforeupdate($application_id);
            foreach ($this->input->post('name') as $key => $witness_name) {
                $witness_stack = array(
                    'marriage_id' => $application_id,
                    'name' => $this->security->xss_clean($witness_name),
                    'occupation' => $this->security->xss_clean($this->input->post('occupation')[$key]),
                    'relation' => $this->security->xss_clean($this->input->post('relation')[$key]),
                    'address' => $this->security->xss_clean($this->input->post('applicant_address')[$key]),
                );
                $this->marriage_table->store_marriage_witness($witness_stack);
            }
            foreach ($_FILES as $key => $oneImage) {
                if (!empty($oneImage['name']) && $oneImage['name'] != '') {
                    $this->upload->initialize(file_upload_config());
                    $_FILES['fileInput']['name'] = $oneImage['name'];
                    $_FILES['fileInput']['type'] = $oneImage['type'];
                    $_FILES['fileInput']['tmp_name'] = $oneImage['tmp_name'];
                    $_FILES['fileInput']['error'] = $oneImage['error'];
                    $_FILES['fileInput']['size'] = $oneImage['size'];
                    if ($this->upload->do_upload('fileInput')) {
                        $this->marriage_table->delete_image_beforupload($application_id,$key);
                        $document_insert = array(
                            'marriage_id' => $application_id,
                            'file_name' => $this->upload->data('file_name'),
                            'file_title' => $key,
                        );
                        $this->marriage_table->store_marriage_document($document_insert);
                        $this->response['status'] = TRUE;  
                        $this->response['message'] = "Update application successfully.";
                    } else {
                        $this->response['status'] = FALSE;  
                        $this->response['message'] = $this->upload->display_errors();
                    }
                } else {
                    $this->response['status'] = TRUE;  
                    $this->response['message'] = "Update application successfully.";
                }
            }
        } else {
            $this->response['status'] = FALSE;  
            $this->response['message'] = validation_errors();
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function update_marriage_application_status()
    {
        $application_id = $this->security->xss_clean(base64_decode($this->input->post('app_id')));
        if (is_numeric($application_id)) {
            $application_detais = $this->marriage_table->getAppDetails($application_id);
            $inputStack = array('is_active' => $application_detais->is_active ? FALSE : TRUE );
            $query_status = $this->marriage_table->update_marriage_application($inputStack,$application_id);
            if ($query_status == TRUE) {
                $this->response['status'] = TRUE;  
                $this->response['message'] = "Application has been updated successfully.";
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = "Something went wrong.";
            }
        } else {
            $this->response['status'] = FALSE;  
            $this->response['message'] = "Something went wrong.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function get_marriage_application_remark()
    {
        $application_id = $this->security->xss_clean(base64_decode($this->input->post('app_id')));
        if (is_numeric($application_id)) {
           $all_application_remark = $this->application_remarks_table->getAllRemarksById($application_id);
            $html_str = '';
            if(!empty($all_application_remark) && count($all_application_remark)){
                foreach ($all_application_remark as $oneRemark) {
                    $html_str .= '<tr><td>'.$oneRemark['id'].'</td><td>'.$oneRemark['remarks'].'</td><td>'.$oneRemark['user_name'].'</td><td>'.$oneRemark['created_at'].'</td></tr>';
                }    
            }  
            $this->response['status'] = TRUE;
            $this->response['html_str'] = $html_str;
        } else {
            $this->response['status'] = FALSE;  
            $this->response['message'] = "Something went wrong.";   
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function add_remark_modal()
    {
        $application_id = $this->security->xss_clean(base64_decode($this->input->post('app_id')));
        if (!empty($application_id) && is_numeric($application_id)) {

            $app_status_level = $this->marriage_table->get_app_status_lavel($this->session->userdata('user_session')[0]['dept_id'] , $this->session->userdata('user_session')[0]['role_id']);

            $html_str_select_dropdwon = '<option value="">---Select options---</option>';

            if (!empty($app_status_level) && count($app_status_level)) {

                foreach ($app_status_level as $one_status_lavel) {
                    $html_str_select_dropdwon .= '<option value="'.$one_status_lavel->status_id.'">'.$one_status_lavel->status_title.'</option>';
                }    
            } 
            $this->response['status'] = TRUE;
            $this->response['data_stack']['html_str'] = $html_str_select_dropdwon;
            $this->response['data_stack']['application_id'] = $application_id;
        } else {
            $this->response['status'] = FALSE;  
            $this->response['message'] = "Something went wrong.";   
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function update_status_remark()
    {
        $application_remarks_insert_stack = array(
            'app_id' => $this->security->xss_clean($this->input->post('app_id')),
            'dept_id' => $this->session->userdata('user_session')[0]['dept_id'],
            'remarks' => $this->security->xss_clean($this->input->post('remarks')),
            'sub_dept_id' => 0,
            'user_id' => $this->session->userdata('user_session')[0]['user_id'],
            'role_id' => $this->session->userdata('user_session')[0]['role_id'],
            'status_id' => $this->security->xss_clean($this->input->post('status')),
            'status' => TRUE,
            'is_deleted' => FALSE,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        );
        $update_marriage_application_stack = array(
            'status' => $this->security->xss_clean($this->input->post('status')),
        ); 
        $update_qeury_status = $this->marriage_table->update_marriage_application($update_marriage_application_stack,$this->security->xss_clean($this->input->post('app_id')));
        $application_remarks_query = $this->marriage_table->set_application_remark($application_remarks_insert_stack);
        if ($application_remarks_query && $update_qeury_status) {
            $this->response['status'] = TRUE;
            $this->response['message'] = "Remark status has been update successfully.";
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = "Something went wrong.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
}
