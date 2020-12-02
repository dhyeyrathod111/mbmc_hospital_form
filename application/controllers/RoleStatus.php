<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class RoleStatus extends Common {

	public function index(){
		$data['department'] = $this->department_table->getAllDepartments();
		$data['roles'] = $this->roles_table->getroles();

		$this->load->view("settings/rolestatus/index", $data);
	}

	public function create() {

		$data['department'] = $this->department_table->getAllDepartments();
		$data['roles'] = $this->roles_table->getroles();
		// echo "<pre>";print_r($data);

		$this->load->view("settings/rolestatus/create", $data);
	}

	public function getData(){
		$searchVal = $_POST['search']['value'];
		$i = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir'];

		if($this->input->post("department") != '0'){
			$department = $this->input->post("department");
		}else{
			$department = '';
		}

		if($this->input->post("roles") != '0'){
			$roles = $this->input->post("roles");
		}else{
			$roles = '';
		}

		if($searchVal != ''){
			$appData = $this->roles_table->getRolesAppData($searchVal, $department, $roles, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}else{
			$appData = $this->roles_table->getRolesAppData($searchVal, $department, $roles, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);	
		}

		$data = array();

		$style = "display:block";

		foreach($appData as $key => $val){
			$i++;

			$actionBtn = "";

			if($val['is_deleted'] == '0'){
				$actionBtn = "<span style = 'cursor: pointer;color: blue;".$style."' aria-label = 'Delete' data-microtip-position='top' role='tooltip' class = 'delete' data-id = '".$val['status_id']."'><i class='fa fa-trash' aria-hidden='true'></i></span><span><a for = 'edit' class = 'edit' style = 'cursor: pointer; color: red' aria-label = 'Edit' data-microtip-position='top' role='tooltip' href = '".base_url()."rolestatus/edit/".base64_encode($val['status_id'])."'><i class='fa fa-edit' aria-hidden='true'></i></a></span>";
			}

			$data[] = array('sr_no' => $i, 'department' => $val['department_name'], 'role' => $val['role_name'], 'title' => $val['status_title'], 'action' => $actionBtn);
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $this->roles_table->countFilteredStatus($_POST),
            "data" => $data,
        );

        echo json_encode($output);
	}

	public function submit(){
		extract($_POST);
		$is_rejected = explode(",", $this->input->post('is_rejected'));

		foreach ($status_title as $key => $value) {
			$status_approve = '0';
			if($roles == '13'){

				if(strpos("approved", $value) != false){
					$status_approve = '1';
				}elseif (strpos("Approved", $value)) {
					$status_approve = '1';
				}else{
					$status_approve = '2';
				}
			}

			$appStatus[] = array(
				"dept_id" => $department,
				"role_id" => $roles,
				"status_title" => $value,
				"status_type" => '1',
				"status" => '1',
				"status_approve" => $status_approve,
				"is_deleted" => '0',
				"is_rejected" => ($is_rejected[$key] == 'true') ? TRUE : FALSE,
				"created_at" => date("Y-m-d H:i:s"),
				"updated_at" => date("Y-m-d H:i:s")
			);	


		}

		$res = $this->roles_table->insertRoleStatus($appStatus);

		// echo "<pre>";print_r($appStatus);exit();

		if($res){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function delete(){
		$statusId = $this->input->post("status_id");

		$res = $this->roles_table->deletestatus($statusId);

		if($res){
			$data['success'] = 1;
		}else{
			$data['succes'] = 2;
		}

		echo json_encode($data);
	}

	public function editApp()
	{
		$appid = base64_decode($this->uri->segment(3));
		$data['appData'] = $this->roles_table->getStatusDataById($appid);
		$this->load->view('settings/rolestatus/edit', $data);
	}

	public function submitEdit(){
		extract($_POST);

		$updateArray = array(
			"status_title" => $status_title
		);

		$res = $this->roles_table->editSubmit($updateArray, $status_id);

		if($res){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

}

?>