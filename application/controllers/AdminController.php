<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'controllers/Common.php';
class AdminController extends Common {

	/**
	 * Admin.php
     * @author Vikas Pandey
	 */

    public function __construct() {
        parent::__construct();
        ob_start();
        $this->load->helper(['jwt', 'authorization']);
        $this->load->helper("cookie");
        set_cookie('demo','1');
        set_cookie("email", "");
        set_cookie("password", "");
        set_cookie("user_id", "");
    }

    

    public function addUserDetails() {
        
        extract($_POST);
        $username_check = $this->form_validation
                            ->set_rules('user_name','user_name','required')->run();

        $email_check = $this->form_validation
                        ->set_rules('email_id','email_id','required|valid_email|is_unique[users_table.email_id]')->run();

        $mobile_check = $this->form_validation
                    ->set_rules('user_mobile','user_mobile','required|regex_match[/^[0-9]{10}$/]|is_unique[users_table.user_mobile]')->run();

        $role_check = $this->form_validation
                    ->set_rules('role_id','role_id','required')->run();
        $dept_check = $this->form_validation
                    ->set_rules('dept_id','dept_id','required')->run();

        $pass_check = $this->form_validation
                    ->set_rules('user_mobile','user_mobile','required')->run();
        $data['messg'] = '';

        if(!$username_check || !$email_check || !$mobile_check || !$role_check || !$pass_check || !$dept_check) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            $extra = array(
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'password' => $this->hash_password($password));

            $data = array_merge($_POST,$extra);

            unset($data['terms']);

            $result = $this->users_table->insert($data);

            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = 'User Registered successfully.';
            } else {
                $messg = 'Oops! Something went wrong.';
                $data['status'] = '2';
                $data['messg'] = $messg;
            }
        }

        echo json_encode($data);
    }
    
	public function login_view() { 
		$this->load->view('auth/login');
    }

    public function login_check() {
        // Extract user data from POST request
        extract($_POST);
        // print_r($_POST);exit;
        $remember = 0;
        
        if(array_key_exists('remember', $_POST)){
            $remember = 1;
        }
        
        // echo'<pre>';print_r($_POST);exit;
        if(!empty($email_id) && !empty($password)) {

            $userdata = $this->users_table->check_email($email_id, $loginType);

            if(!empty($userdata)) {

                $check_password = $this->check_password($password, $userdata[0]['password']);
                // echo'<pre>';var_dump($check);exit;
                if($check_password != null) {
                    // print_r($userdata);exit;
                    // set session
                    $this->session->set_userdata('user_session',$userdata);
                    
                    if($remember == 1){
                        set_cookie('email', $email_id, time()+ (10 * 365 * 24 * 60 * 60));
                        set_cookie("password", $password, time()+ (10 * 365 * 24 * 60 * 60));
                        set_cookie("user_id", $userdata[0]['user_id'], time()+ (10 * 365 * 24 * 60 * 60));
                    }else{
                        set_cookie("email", "");
                        set_cookie("password", "");
                        set_cookie("user_id", "");
                    }
                    // echo get_cookie('email');exit;

                    $token = AUTHORIZATION::generateToken($userdata);
                    $session_data = array_merge($userdata[0],array('token' => $token));
                    // echo'<pre>';print_r($session_data);exit;
                    // set log
                    $this->user_log($session_data);

                    $data['status'] = '1';
                    $data['messg'] = 'User Logged successfully.';
                    // $data['token'] = $token;
                } else {
                    $data['status'] = '2';
                    $data['messg'] = 'Incorrect password.';
                    // $data['token'] = '';
                }
            } else {
                $data['status'] = '3';
                $data['messg'] = 'Incorrect email address.';
                // $data['token'] = '';
            }

        } else {
            $data['status'] = '4';
            $data['messg'] = 'Incorrect email address .';
            // $data['token'] = '';

        }
        //echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

    public function validate_token() {
        extract($_POST);
        // echo'<pre>';print_r($token);exit;
        try {
            // Validate the token
            // Successfull validation will return the decoded user data else returns false
            $data = AUTHORIZATION::validateToken($token);
            if ($data !== false) {
                $response['status'] = '1';
                $response['messg'] = 'Authorized Access!.';
                $response['token'] = $token;
            } else {
                $response['status'] = '2';
                $response['messg'] = 'Unauthorized  Access!.';
                $response['token'] = '';
                $response['data'] = null;
            }

        } catch (Exception $e) {
            // Token is invalid
            // Send the unathorized access message
            $response['status'] = '1';
            $response['messg'] = 'Unauthorized Access!.';
            $response['token'] = '';
        }
        // echo'<pre>';print_r($response);exit;
        echo json_encode($response);
    }

    public function logout() {
        $this->session->unset_userdata('user_session');
        $this->session->unset_userdata('delete_status');
        redirect('login');
    }
    
    //register
	public function register(){
		// echo "kkk";exit;
		$this->load->view('auth/register');
	}
    
    // Dhyey rathod forgot password
    public function forgot_password_form()
    {
        $this->load->view('user_authentication/forgot_password_form');
    }
    public function forgot_password_process()
    {
        $this->form_validation->set_rules('email', 'Email','trim|required|valid_email');
        if ($this->form_validation->run()) {
            $email_id = $this->security->xss_clean($this->input->post('email'));
            $user_data = $this->users_table->getUserByEmailID($email_id);
            if (!empty($user_data)) {
                $this->data['user_info'] = $user_data;$user_keygen = random_string('alnum',20);
                $this->data['user_keygen'] = $user_keygen;
                $update_user_stack = array('user_keygen' => $user_keygen);
                $this->users_table->update_user($update_user_stack,$email_id);
                $html_str = $this->load->view('user_authentication/forgot_password_email',$this->data,TRUE);
                $email_stack = array(
                    'to' => $user_data->email_id,
                    'subject' => "forgot password link",
                    'body' => $html_str
                );
                $email_response = $this->email_trigger->codeigniter_mail($email_stack);
                if ($email_response->message == "success") {
                    $this->response['status'] = TRUE;
                    $this->response['message'] = "Email has been send on your email id. Please check your inbox";
                    $this->response['redirect_url'] = base_url('login');
                } else {
                    $this->response['status'] = FALSE;
                    $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
                }
            } else {    
                $this->response['status'] = FALSE;
                $this->response['message'] = "Email ID is not in use.";
            }
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = "Please enter email id in proper formate.";
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function reset_password_form()
    {
        $this->load->view('user_authentication/reset_password_form');
    }
    public function change_password_process()
    {
        $this->form_validation->set_rules('keygen', 'Access key', 'required|authenticate_keygen|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|password_format');
        $this->form_validation->set_rules('confirm_password', 'confirm password','required|matches[password]');
        if ($this->form_validation->run()) {
            $user_keygen = trim($this->input->post('keygen'));
            $update_stack = array(
                'password' => $this->hash_password($this->input->post('password')),
            );
            $userInfo = $this->users_table->get_user_by_keygen($user_keygen);
            if ($this->users_table->update_stack_by_keygen($update_stack,$user_keygen)) {
                $this->users_table->update(['user_keygen'=>''],$userInfo->user_id);
                $this->response['status'] = TRUE;
                $this->response['message'] = 'Your password has been updated successfully.';
                $this->response['redirect_url'] = base_url('login');
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = 'Sorry, we have to face some technical issues please try again later.';
            }
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = strip_tags(validation_errors());
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function validate_keygen()
    {
        $user_keygen = trim($this->input->post('keygen'));
        if (!$this->form_validation->is_unique($user_keygen,'users_table.user_keygen')) {
            echo 'true';
        } else {
            echo 'false';
        }
    }
    public function pwd_paymnet_getway()
    {
        // echo "<pre>";print_r($this->authorised_user);exit();

		$application = $this->pwd_applications_table->getApplicationBYappID($this->input->get('app_id'));
        $all_road_type = $this->pwd_applications_table->getRoadTypeByAppid($application->id);
        $data['pay_amount'] = $all_road_type;$data['application'] = $application;
        $this->load->view('applications/pwd/payment_page',$data);
    }
    public function process_payment()
    {
        $this->form_validation->set_rules('payment_method','Payment method','required|integer');
        $this->form_validation->set_rules('payment_amount','Payment amount','required');
        $document_response = json_decode($this->file_upload($_FILES['payment_document'],FALSE,pwd_payment_document_config()));
        if ($this->form_validation->run() && $document_response->status == TRUE) {
            $insertStack = array(
                'app_id' => $this->security->xss_clean($this->input->post('app_id')),
                'payment_selected' => $this->security->xss_clean($this->input->post('payment_method')),
                'amount' => $this->security->xss_clean($this->input->post('payment_amount')),
                'document_path' => $document_response->file_data->file_name,
                'status' => TRUE,'dept_id' => 1,'is_deleted'=>0,
            );
            if ($this->pwd_applications_table->createPayment($insertStack)) {
                $this->response['status'] = TRUE;
                $this->response['message'] = 'Thank you for using the services of MBMC !';
                $this->response['redirect_url'] = base_url();
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = 'Sorry, we have to face some technical issues please try again later.';
            }
        } else {
            $this->response['status'] = FALSE;$message = '';
            if (validation_errors() != '') {
                $message .= vstrip_tags(validation_errors());
            } else if ($document_response->status == FALSE) {
                $message .= strip_tags($document_response->error);
            } else {
                $message .= 'Sorry, we have to face some technical issues please try again later.';
            }
            $this->response['message'] = $message;
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
}
?>