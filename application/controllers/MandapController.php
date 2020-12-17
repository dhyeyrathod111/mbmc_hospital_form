<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class MandapController extends Common {

	/**
 	* DepartmentsController.php
 	* @author Vikas Pandey
 	*/

    protected $response ;

    protected $data ;

    protected $dept_id ;

    public function __construct()
    {
        parent::__construct();$this->data = array();$this->response = array();
        $this->dept_id = $this->get_dept_id_by_name('Mandap');
    }

	public function index() {
        $dept_id = $this->get_dept_id_by_name('Mandap');
        // echo'<pre>';print_r($dept_id);exit;
        $data['appStatus'] = $this->App_status_level_table->getAllStatusByDept();
        // echo'<pre>';print_r($data);exit;
		$this->load->view("applications/mandap/index",$data);
	}
    

    public function create() {
        $this->data['app_id'] = $this->get_last_app_id();
        $this->data['wards'] = $this->mandap_applications_table->getWardFromDeptID($this->dept_id);
        $this->data['allmandaptype'] = $this->mandap_applications_table->getAllMandapType();
        $this->data['roleStacClerk'] = $this->mandap_applications_table->getRoleByName('Clerk');
        $this->load->view('applications/mandap/create',$this->data);
    }

    public function edit()
    {
        $mandap_id = base64_decode($this->uri->segment(3));
        $application = $this->mandap_applications_table->getApplicationByID($mandap_id);
        if (!empty($application)) :
            $this->data['wards'] = $this->mandap_applications_table->getWardFromDeptID($this->dept_id);
            $this->data['allmandaptype'] = $this->mandap_applications_table->getAllMandapType();
            $application_images = $this->mandap_applications_table->getImageByApplication($application);
            $this->data['application'] = $application;
            $this->data['appimages'] = image_formate_in_array_mandap($application_images,$application);
            $this->data['roleStacClerk'] = $this->mandap_applications_table->getRoleByName('Clerk');
            $this->load->view('applications/mandap/edit',$this->data);
        else :
            return redirect('mandap/user_apps_list');
        endif ;     
    }
    public function save()
    {
        try {
            $app_id = $this->get_last_app_id();
            $inertStack = array(
                'app_id' => ++$app_id,
                'applicant_name' => $this->security->xss_clean($this->input->post('applicant_name')),
                'applicant_email_id' => $this->security->xss_clean($this->input->post('applicant_email_id')),
                'applicant_mobile_no' => $this->security->xss_clean($this->input->post('applicant_mobile_no')),
                'applicant_alternate_no' => $this->security->xss_clean($this->input->post('applicant_alternate_no')),
                'applicant_address' => $this->security->xss_clean($this->input->post('applicant_address')),
                'fk_ward_id' => $this->security->xss_clean($this->input->post('fk_ward_id')),
                'booking_address' => $this->security->xss_clean($this->input->post('booking_address')),
                'mandap_landmark' => $this->security->xss_clean($this->input->post('mandap_landmark')),
                'reason' => $this->security->xss_clean($this->input->post('reason')),
                'type' => $this->security->xss_clean($this->input->post('mandap_type')),
                'mandap_size' => $this->security->xss_clean($this->input->post('mandap_size')),
                'no_of_days' => $this->security->xss_clean($this->input->post('no_of_days')),
                'booking_date' => $this->security->xss_clean($this->input->post('booking_date')),
                'to_date' => $this->security->xss_clean($this->input->post('to_date')),
                'user_id' => $this->authorised_user['user_id'],
                'no_of_gates' => $this->security->xss_clean($this->input->post('no_of_gates')),
                'date_police_of_noc' => $this->security->xss_clean($this->input->post('date_police_of_noc')),
                'date_traffic_of_noc' => $this->security->xss_clean($this->input->post('date_traffic_of_noc')),
            );
            $image_upload_error = '';$document_data_stack = [];
            foreach ($_FILES as $key => $oneImage) {
                if (!empty($_FILES[$key]['name']) && $_FILES[$key]['error'] == 0) {
                    $this->upload->initialize(mandap_document_config());
                    $_FILES['fileInput']['name'] = $oneImage['name'];
                    $_FILES['fileInput']['type'] = $oneImage['type'];
                    $_FILES['fileInput']['tmp_name'] = $oneImage['tmp_name'];
                    $_FILES['fileInput']['error'] = $oneImage['error'];
                    $_FILES['fileInput']['size'] = $oneImage['size'];
                    if ($this->upload->do_upload('fileInput')) {
                        $one_document_document = array(
                            'image_name' => $oneImage['name'],
                            'image_enc_name' => $this->upload->data('file_name'),
                            'image_path' => base_url('uploads/mandap/').$this->upload->data('file_name'),
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
                $mandap_id = $this->input->post('id');unset($inertStack['app_id']);unset($inertStack['user_id']);
                $this->mandap_applications_table->mandap_revolution($inertStack,$mandap_id);
            } else {
                $mandap_id = $this->mandap_applications_table->mandap_revolution($inertStack);
                $applications_details = array(
                    'application_id' => $inertStack['app_id'],
                    'dept_id' => $this->department_table->getDepartmentByName('Mandap'),
                    'status' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                );
                $app_status_remark = $this->mandap_applications_table->insert_application_details($applications_details);
            }
            $this->mandap_applications_table->storeMandapDocument($document_data_stack,$mandap_id);
            $this->response['status'] = TRUE;
            if (!empty($this->input->post('id')) && $this->input->post('id') != '') {
                $this->response['message'] = "Mandap has been updated successfully...!!!";
            } else {
                $this->response['message'] = "Mandap has been created successfully...!!!";
            }
        } catch (Exception $e) {
            $this->response['status'] = FALSE;
            $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }


	public function get_list()    {

        $data = $row = array();
        $session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];

        // Fetch member's records
        $mandapList = $this->mandap_applications_table->getMandapDataForAuthorityDataTable(FALSE,$this->input->post(),$this->dept_id);

        $mandapListCount = $this->mandap_applications_table->getMandapDataForAuthorityDataTable(TRUE,$this->input->post(),$this->dept_id);

        $i = $_POST['start'];

        $roleStacClerk = $this->mandap_applications_table->getRoleByName('Clerk');

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


            if($status_details != null) {
                $status_type = $status_details[0]['status_type'];
                $status_title = $status_details[0]['status_title'];
                $status ='<a type="button" data-mandap="'.$mandap['id'].'" data-app="'.$mandap['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-status="'.$status.'" class="status_button btn btn-sm  btn-danger white">'.$status_title.'</a>';
            } else {
                $status ='<a type="button"  data-mandap="'.$mandap['id'].'" data-app="'.$mandap['app_id'].'" data-user="'.$user_id.'" data-role="'.$role_id.'" data-dept="'.$dept_id.'" data-status="'.$status.'" class="status_button btn btn-sm  btn-danger white">Awaiting</a>';
            }
            
            $remarks = '<a type="button" data-toggle="modal" data-mandap="'.$mandap['id'].'" data-app="'.$mandap['app_id'].'"  data-target="#modal-remarks" class="remarks_button btn btn-sm btn-primary white">Remarks</a>';

            if ($mandap['final_authority_approvel'] > 0 && $roleStacClerk->role_id == $this->authorised_user['role_id'] && $mandap['check_payment_status'] == '') {
                $payment_btn = '<a type="button" aria-label="Payment Request" data-microtip-position="top" role="tooltip" app_id="'.$mandap['app_id'].'" class="btn btn-warning payment_request_btn btn-sm ml-1"><i class="fas fa-money-bill-wave payment_request_btn" app_id="'.$mandap['app_id'].'"></i></a>';
            } else if ($roleStacClerk->role_id == $this->authorised_user['role_id'] && $mandap['check_payment_status'] == 4) {
                $payment_btn = '<a type="button" aria-label="Payment Approval" data-microtip-position="top" role="tooltip" app_id="'.$mandap['app_id'].'" class="btn btn-warning btn-sm ml-1 payment_approvel_btn"><i class="fas fa-check payment_approvel_btn" app_id="'.$mandap['app_id'].'"></i></a>';
            } else {
                $payment_btn = '';
            }

            $documents = '<a aria-label="View documents" data-microtip-position="top" role="tooltip" type="button" data-toggle="modal" data-image = "'.$mandap['id_proof'].','.$mandap['traffic_police_noc'].','.$mandap['police_noc'].'" data-target="#modal-doc" class="anchor nav-link-icon doc_button">
                        <i class=" nav-icon fas fa-file"></i>
                    </a>';


            $action = '<a aria-label="Edit" data-microtip-position="top" role="tooltip" type="button" href="'.base_url('mandap/edit/'.base64_encode($id)).'" class="btn btn-success btn-sm"><i class=" nav-icon fas fa-edit"></i></a>'.$payment_btn;


            $data[] = array($i,$application_no, $applicant_name,$applicant_mobile_no,$booking_address,$booking_date,$remarks,$status,$documents,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $mandapListCount,
            "recordsFiltered" => $mandapListCount,
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
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($data));
    }
    public function get_application_status()
    {
        $statusData = $this->App_status_level_table->getAllStatusByDeptRole($this->input->post('dept_id'),$this->input->post('role_id'));
        if (!empty($statusData)) {
            $this->data['approvel_status'] = $statusData;
            $this->data['postdata'] = $this->input->post();
            $this->response['status'] = TRUE;
            $this->response['html_string'] = $this->load->view('applications/mandap/modal/add_remark',$this->data,TRUE);
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = "Role status has not been created.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
        
    }
    public function payment_reqeust_popup()
    {
        $app_id = $this->input->post('app_id');
        $application = $this->mandap_applications_table->getApplicationByAppID($app_id);
        if (!empty($application)) {
            $this->data['application'] = $application;$mandapTypeData = $this->mandap_applications_table->getMandapTypeDataByID($application->type);    
            $this->data['payment'] = getNumdupCalculationByapplicationAndType($mandapTypeData,$application);
            $this->response['status'] = TRUE;
            $this->response['html_str'] = $this->load->view('applications/mandap/modal/payment_reqeust_popup',$this->data,TRUE);
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function payment_request_process()
    {
        $app_id = $this->input->post('app_id');
        $application = $this->mandap_applications_table->getApplicationByAppID($app_id);
        if (!empty($application)) {
            $this->data['application'] = $application;
            $this->data['postdata'] = $this->input->post();
            $html_str = $this->load->view('applications/mandap/email_templates/payment_reqeust',$this->data,TRUE);
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
            if ($this->mandap_applications_table->create_payment_reqeust($paymentStack)) {
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
        $application = $this->mandap_applications_table->getApplicationByAppID($app_id);
        if (!empty($application)) {
            $this->data['application'] = $application;
            $this->data['payment'] = $this->mandap_applications_table->getPaymentSackByAppID($application->app_id);
            $this->load->view('applications/mandap/user_payment_page',$this->data);
        } else {
            return redirect('');
        }
    }
    public function user_payment_process()
    {
        $this->form_validation->set_rules('payment_method','Payment method','required|integer');
        $this->form_validation->set_rules('payment_amount','Payment amount','required');
        $document_response = json_decode($this->file_upload($_FILES['payment_document'],FALSE,mandap_payment_document_config()));
        if ($this->form_validation->run() && $document_response->status == TRUE) {
            $updatePaymnetStack = array(
                'payment_selected' => $this->security->xss_clean($this->input->post('payment_method')),
                'amount' => $this->security->xss_clean($this->input->post('payment_amount')),
                'document_path' => $document_response->file_data->file_name,
                'status' => 4,
            );
            if ($this->mandap_applications_table->update_payment_by_appID($updatePaymnetStack,$this->input->post('app_id'))) {
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
        $application = $this->mandap_applications_table->getApplicationByAppID($app_id);
        if (!empty($application)) {
            $this->data['application'] = $application;
            $this->data['payment'] = $this->mandap_applications_table->getActivePaymentByAppID($app_id);
            $this->response['status'] = TRUE;
            $this->response['html_str'] = $this->load->view('applications/mandap/modal/payment_approvel_popup',$this->data,TRUE);
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function payment_approvel_process()
    {
        $app_id = $this->input->post('app_id');
        $application = $this->mandap_applications_table->getApplicationByAppID($app_id);
        if (!empty($application)) {
            if ($this->mandap_applications_table->update_payment_by_appID(['status'=>2],$app_id)) {
                $this->data['application'] = $application;
                $this->data['certificate_url'] = base_url('letters/madap_license?app_id='.base64_encode($this->data['application']->app_id));
                $email_stack = array(
                    'to' => $application->applicant_email_id,
                    'body' => $this->load->view('applications/mandap/email_templates/madap_license_email',$this->data,TRUE),
                    'subject' => "Application MBMC-00000".$application->app_id." license.",
                );
                if ($this->email_trigger->codeigniter_mail($email_stack) != TRUE) $this->email_trigger->sendMail($email_stack);
                $letterStack = $this->mandap_applications_table->getLettersByKey('mandap_permission');

                $latterGenrationStack = array(
                    'dept_id' => $this->dept_id,
                    'app_id' => $application->app_id,
                    'latter_type_id' => $letterStack->id,
                    'file_name' => base_url('letters/madap_license?app_id='.base64_encode($application->app_id)),
                    'status' => 1,
                    'is_deleted'=>0
                );
                $this->mandap_applications_table->createPermissionLetter($latterGenrationStack);
                $this->response['status'] = TRUE; 
                $this->response['message'] = 'Payment has been approved and licence has been send successfully.';
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
        $this->load->view('applications/mandap/user_apps_list');
    }
    public function datatable_userapplist()
    {
        $applications = $this->mandap_applications_table->getApplicationForUsers(FALSE,$this->input->post());
        $applications_count = $this->mandap_applications_table->getApplicationForUsers(TRUE,$this->input->post());
        $finalDatatableStack = [];
        foreach ($applications as $key => $oneApp) :
            $tempArray = array();
            $tempArray[] = $key + 1;
            $tempArray[] = 'MBMC-00000'.$oneApp->app_id;
            $tempArray[] = $oneApp->applicant_name;
            $tempArray[] = $oneApp->applicant_email_id;
            $tempArray[] = $oneApp->applicant_mobile_no;
            $tempArray[] = $oneApp->application_status;
            $tempArray[] = date('Y-m-d',strtotime($oneApp->created_at));
            $tempArray[] = '<a href="'.base_url('mandap/edit/'.base64_encode($oneApp->id)).'" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></a>';
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
