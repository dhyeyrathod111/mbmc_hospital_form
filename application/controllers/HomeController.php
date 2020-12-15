<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class HomeController extends Common {

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
	public function __construct()
	{
		parent::__construct();$this->load->model('Dashboard_data');
		// echo "<pre>";print_r($this->authorised_user);exit();
	}

	public function index()
	{
		ob_start();

		$mandapDeptStack = $this->Dashboard_data->getDepartmentByName('Mandap');

		$session_userdata = $this->session->userdata('user_session');
            // print_r($session_userdata);exit;
        $role_id = $session_userdata[0]['role_id'];
        $dept_id = $session_userdata[0]['dept_id'];

        if($role_id != '' && $dept_id != ''){
            if($session_userdata[0]['is_user'] == '0'){
            	if ($mandapDeptStack->dept_id == $this->authorised_user['dept_id'] && $role_id != '' && $dept_id != '') {
            		$dashboarData['data'] = $this->mandap_applications_table->deshboardData($role_id,$dept_id,$session_userdata[0]['is_superadmin']);
            	} else {
            		$dashboarData['data'] = $this->Dashboard_data->getUpperData($role_id, $dept_id, $session_userdata[0]['is_superadmin']);
            	}
        	    $this->load->view('Home', $dashboarData);   
            }else{
                $getAppRoutes = $this->db->query("SELECT dept_id, slug, controller, method, grp_index,sub_slug FROM `app_routes` WHERE slug LIKE 'create%' AND status = '1' AND dept_id != '0' AND grp_index != '0' ORDER BY dept_id asc limit 1")->result_array();
                redirect(base_url().$getAppRoutes[0]['sub_slug'].'/'.$getAppRoutes[0]['slug']);
            }
        }else{
        	$this->load->view('auth/login');
        }
	}

	public function get_designation()
	{
		$data['designation'] = $this->designation_master_table->getAllDesignation();
		echo json_encode($data);
	}
}
