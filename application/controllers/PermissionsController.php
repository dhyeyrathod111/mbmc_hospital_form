<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class PermissionsController extends Common {

	/**
	 
	 */
	public function __construct(){
	   parent::__construct();
	   $this->load->model('Permission_table');
	}

	public function index()
	{
		//get users
		$data['usersDepartment'] = $this->Permission_table->getUserDeparment();
		// $data['userRole'] = $this->Permission_table->getUserRoles();
		// echo "<pre>";print_r($data);exit;
		$this->load->view('permissions/index', $data);
	}


	public function getUserData(){
		// $user_id = $this->input->post("user_id");
		$role_id = $this->input->post("role_id");
		$dept_id = $this->input->post("dept_id");

		$res = $this->Permission_table->getUserData($role_id, $dept_id);
		$resData = $this->Permission_table->getRolesByDept($dept_id);

		$data['res'] = $res;

		if($res){
			$data['success'] = 1;
			$data['resData'] = $resData;
		}else{
			$data['success'] = 2;
		}
		
		echo json_encode($data);
	}

	public function addPermission(){
		extract($_POST);
		$array = array('index', 'create', 'edit', 'delete');

		foreach($array as $key => $val){
			switch ($val) {
				case 'index':
					$permissionArray = array(
						'user_id' => '',
						'role_id' => $userRole,
						'dept_id' => $userDepartment,
						'route_status' => '1',
						'category_id' => '1',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->Permission_table->insertArray($permissionArray, 'index', $userRole, $userDepartment);
					break;

				case 'create': 
					$permissionArray = array(
						'user_id' => '',
						'role_id' => $userRole,
						'dept_id' => $userDepartment,
						'route_status' => $create,
						'category_id' => '2',
						'date_added' => date("Y-m-d H:i:s")
					);	
					$this->Permission_table->insertArray($permissionArray, 'create', $userRole, $userDepartment);
					break;
				case 'edit': 
					$permissionArray = array(
						'user_id' => '',
						'role_id' => $userRole,
						'dept_id' => $userDepartment,
						'route_status' => $edit,
						'category_id' => '3',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->Permission_table->insertArray($permissionArray, 'edit', $userRole, $userDepartment);
					break;
				case 'delete':
					$permissionArray = array(
						'user_id' => '',
						'role_id' => $userRole,
						'dept_id' => $userDepartment,
						'route_status' => '1',
						'category_id' => '4',
						'date_added' => date("Y-m-d H:i:s")
					);
					$this->Permission_table->insertArray($permissionArray, 'delete', $userRole, $userDepartment);
					break;	
			}
			
		}

		$data['success'] = 1;
		echo json_encode($data);
	}

}
