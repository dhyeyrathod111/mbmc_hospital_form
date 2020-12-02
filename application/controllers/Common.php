<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'third_party/Format.php';
require APPPATH . 'libraries/PasswordHash.php';
require APPPATH . 'libraries/Dataencryption.php';
class Common extends MY_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

    public function __construct() {
        parent::__construct();
        ob_start();
        $this->load->model('users_table');
        $this->load->model('roles_table');
        $this->load->model('auth_sessions_table');
        $this->load->model('department_table');
        $this->load->model('sub_department_table');
		$this->load->model('road_type_table');
		$this->load->model('defectliab_table');
        $this->load->model('applications_details_table');
        $this->load->model('pwd_applications_table');
        $this->load->model('hall_applications_table');
        $this->load->model('mandap_applications_table');
        $this->load->model('image_details_table');
        $this->load->model('users_table');
        $this->load->model('application_remarks_table');
        $this->load->model('App_status_level_table');
        $this->load->model('sku_master_table');
        $this->load->model('unit_master_table');
        $this->load->model('price_master_table');
        $this->load->model('hall_assets_table');
        $this->load->model('Add_tree_table');
        $this->load->model('Add_process_table');
        $this->load->model('Add_complaint_table');
        $this->load->model('Lic_Renewal_table');
        $this->load->model('Lic_TradeFact_table');
        $this->load->library('user_agent');
        $this->load->model('hall_checklist_details_table');
        $this->load->model('qualification_master_table');
        $this->load->model('designation_master_table');
        $this->load->model('hospital_applications_table');
        $this->load->model('clinic_applications_table');
        $this->load->model('lab_applications_table');
        $this->load->model('hospital_staff_details_table');
        
        $this->load->model('Marriage_applications_table','marriage_table');
        $this->load->model('WardModel','wardmodel');
        


        $this->load->model('qualification_master_table');
        $this->load->model('Dashboard_data','dashboardData');
        $this->load->model('hospital_applications_table');
        $this->load->model('clinic_applications_table');
        $this->load->model('lab_applications_table');
        $this->load->model('hospital_staff_details_table');
        // $this->load->model('godownApp_table');

        $this->load->model('GodownApp_table');
        $this->load->helper('friend');
        $this->load->model('FilmData_table');
        // $this->load->helper(['jwt', 'authorization']);
        $this->load->model('Advertisement_table');
		$this->load->model('ReportData');
		$this->load->model('Add_company_details');
        $this->checkAuth();
    }
    
    public function checkAuth(){
        $openSubSlugInit = ['letters','payment'];
        if ($this->input->server('REQUEST_METHOD') !== 'POST' && !in_array($this->uri->segment(1),$openSubSlugInit)) {
            $current_user_id = !empty($this->session->userdata('user_session')[0]['user_id']) ? $this->session->userdata('user_session')[0]['user_id'] : 0;
            if ($this->uri->segment(1) !== 'login' && $this->uri->segment(1) != 'user_authentication') {
                if($this->uri->segment(1) !== 'register'){
					(empty($current_user_id) || $current_user_id == 0) ? redirect('/login', 'refresh') : '' ;
				}
            }
        } 
    }    

    public function hash_password($pass, $iterations = 0) {
        // The shortest valid hash phpass can currently return is 20 characters,
        // which would only happen with CRYPT_EXT_DES.
        $min_hash_len = 20;

      
        // Load the password hash library and hash the password.
        $hasher   = $this->getPasswordHasher($iterations);
        $password = $hasher->HashPassword($pass);

        unset($hasher);

        // If the password is shorter than the minimum hash length, something failed.
        if (strlen($password) < $min_hash_len) {
            return false;
        }

        return $password;//array('hash' => $password, 'iterations' => $iterations);
    }

    protected function getPasswordHasher($iterations) {
        return new PasswordHash((int) $iterations, false);
    }

    public function check_password($password, $hash) {
        // Load the password hash library
        $hasher = $this->getPasswordHasher(-1);
        
        return $hasher->CheckPassword($password, $hash);
    }

    public function get_roles() {
        $roles = $this->roles_table->getroles();
        if($roles) {
            return $roles;
        } else {
            return null;
        }
    }

    public function get_all_dept() {
        $dept = $this->department_table->getAllDepartments();
        if($dept) {
            return $dept;
        } else {
            return null;
        }
    }

    public function get_dept_id_by_name($name = null) {
        $dept = $this->department_table->getDepartmentByName($name);
        if($dept) {
            return $dept;
        } else {
            return null;
        }
    }

    public function get_last_app_id() {
        $road_id = $this->applications_details_table->getLastId()['application_id'];
        // echo'<pre>';print_r($road_id);exit;
        if($road_id) {
            return $road_id;
        } else {
            return null;
        }
    }

    public function get_all_road_type() {
        // echo'<pre>';print_r('hihih');exit;
        $road = $this->road_type_table->getAllRoads();
        // echo'xxx<pre>';print_r($road);exit;
        if($road) {
            return $road;
        } else {
            return null;
        }
    }

    public function user_log($userdata = null) {
    	// echo'<pre>';print_r($userdata);exit;
        if($userdata != null) {
        	$data['user_id'] = $userdata['user_id'];
        	$data['token'] = $userdata['token'];
        	$data['login_time'] = $userdata['created_at'];
    		$data['browser'] = $this->agent->browser();
		  	$data['browser_version'] = $this->agent->version();
		  	$data['os'] = $this->agent->platform();
		  	$data['ip_address'] = $this->input->ip_address();
		  	$data['created_at'] = date('y-m-d H:i:s');
        	$result = $this->auth_sessions_table->insert($data);
        }
    }

    public function upload_files($data = null) {
        
        $result = $this->image_details_table->insert($data);
        if($result !=null) {
            return $result;
        } else {
            return  null;
        }
    }

    public function get_app_remarks_by_id() {
        extract($_POST);
        $result = $this->application_remarks_table->getAllRemarksById($app_id);
        // echo'<pre>';print_r($result);exit;
    }

    public function uploadFiles($filename = null, $path = null, $inputName = null){

        if($filename != '' && $path != '')
        {
            $config = array(
                "upload_path" => $path,
                "allowed_types" => "pdf|jpg|png|docx|gif|jpeg",
                "max_size" => "5000",
                "file_name" => $filename,
                "encrypt_name" => TRUE,
            );

            $this->upload->initialize($config);

            if($this->upload->do_upload($inputName)){
                $uploadData = $this->upload->data();
                return $uploadData;
            }else{
                $error = array('error1' => $this->upload->display_errors());
                return $error;
            }

        }else{
            return false;
        }
    }

    
    
}
