<?php

/*
Ankit Naik
Tree Cutting Section
*/
  
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class GodownController extends Common {
	public function index(){
        $session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
		$role_id = $session_userdata[0]['role_id'];
		$dept_id = $session_userdata[0]['dept_id'];

		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
		$this->load->view('applications/storage/index', $data);
	}

	public function create()
	{
		$data['appId'] = $this->applications_details_table->getLastId();
		$this->load->view('applications/storage/add', $data);
	}

	public function addStorage()
	{
		extract($_POST);
		$newLicNo = "";
		$oldLicNo = "";
		if($renewalLic == '2'){
			//new
			$newLicNo = $lic_no;
		}else{
			//old
			$oldLicNo = $old_lic_no;
		}

		$insertArray = array(
			'applicationDate' => date("Y-m-d H:i:s"),
			'name' => $name,
			'address_1' => $address_1,
			'address_2' => $address_2,
			'telephone' => $telephone,
			'mobileNo' => $mobile,
			'god_address1' => $godaddress_1,
			'god_address2' => $godaddress_2,
			'product_name' => $productName,
			'godown_area' => $godownArea,
			'type_of_godown' => $godownType,
			'start_date' => date('Y-m-d',strtotime($startDate)),
			'other_muncipal_lic' => $otherMunLic,
			'renewal_lic' => $renewalLic,
			'old_lic_no' => $oldLicNo,
			'explosive' => $explosive,
			'pending_dues' => $pendingDues,
			'disapprove_earlier' => $disapproveEarlier,
			'date_added' => date("Y-m-d H:i:s"),
			'form_no' => $form_no,
			'lic_no' => $newLicNo
		);

		$strId = $this->GodownApp_table->insert($insertArray);

		if($strId > 0){
			$session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
			$role_id = $session_userdata[0]['role_id'];
			$dept_id = $session_userdata[0]['dept_id'];
			$dataStatus = array(
				'dept_id' => $dept_id,
				'status' => '1',
				'is_deleted' => 0,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s')
			);

			$this->applications_details_table->insert($dataStatus);
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	//edit
	public function editApp()
	{
		$appid = base64_decode($this->uri->segment(3));
		$data['appData'] = $this->GodownApp_table->getDataById($appid);
		$this->load->view('applications/storage/edit', $data);
	}

	public function editStorage()
	{
		extract($_POST);

		$newLicNo = "";
		$oldLicNo = "";
		if($renewalLic == '2'){
			//new
			$newLicNo = $lic_no;
		}else{
			//old
			$oldLicNo = $old_lic_no;
		}

		$updateArray = array(
			'applicationDate' => date("Y-m-d H:i:s"),
			'name' => $name,
			'address_1' => $address_1,
			'address_2' => $address_2,
			'telephone' => $telephone,
			'mobileNo' => $mobile,
			'god_address1' => $godaddress_1,
			'god_address2' => $godaddress_2,
			'product_name' => $productName,
			'godown_area' => $godownArea,
			'type_of_godown' => $godownType,
			'start_date' => $startDate,
			'other_muncipal_lic' => $otherMunLic,
			'renewal_lic' => $renewalLic,
			'old_lic_no' => $oldLicNo,
			'explosive' => $explosive,
			'pending_dues' => $pendingDues,
			'disapprove_earlier' => $disapproveEarlier,
			'date_added' => date("Y-m-d H:i:s"),
			'form_no' => $form_no,
			'lic_no' => $newLicNo
		);

		$this->GodownApp_table->edit($updateArray, $appid);

		$data['success'] = 1;

		echo json_encode($data);
	}

	//getApp

	//get old lics
	public function getDataByLic()
	{
		$licNo = $this->input->post("licNo");

		$data = $this->GodownApp_table->getDataByLic($licNo);

		echo json_encode($data);
	}

	public function getData()
	{
		
		$searchVal = $_POST['search']['value'];
		$i = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir'];

		if($this->input->post("fromDate") != ''){
			$fromDate = date("Y-m-d".strtotime($this->input->post("fromDate")));
		}else{
			$fromDate = "";
		}

		if($this->input->post("toDate") != ''){
			$toDate = date("Y-m-d".strtotime($this->input->post("toDate")));
		}else{
			$toDate = "";
		}

		$approval = $this->input->post("approval");
		$approvalStatus = $this->input->post("approval_status");


		if($searchVal != ''){
			$appData = $this->GodownApp_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}else{
			$appData = $this->GodownApp_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);	
		}
		
		$data = array();

		$style = "display:block";

		// if($_SESSION['delete_status'] == '0'){
		// 	$style = "display:none";
		// }

		foreach($appData as $key => $val){
			$i++;

			if($val['remarks'] != ''){
				$statusRemarks = $val['remarks'];
			}else{
				$statusRemarks = "Unapproved";
			}

			$statusBtn = "<span class = 'btn btn-danger btn-sm approvalStatus' style = 'cursor:pointer;' data-id = '".$val['godown_id']."'>".$statusRemarks."</span>";

			if($val['status'] == '1')
			{
				$actionBtn = "<span>
 <a for = 'edit' class = 'edit' style = 'cursor: pointer; color: red' aria-label = 'Edit' data-microtip-position='top' role='tooltip' href = '".base_url()."godown/edit/".base64_encode($val['godown_id'])."' data-ids = '1'>
  <i class='fa fa-edit' aria-hidden='true'></i>
 </a>
</span><span style = 'cursor: pointer;color: blue;".$style."' aria-label = 'Delete' data-microtip-position='top' role='tooltip' class = 'delete' data-id = '".$val['godown_id']."' data-ids = '1' data-toggle='tooltip' data-placement = 'InActive'>
	<i class='fa fa-trash' aria-hidden='true'></i>	
</span><span aria-label = 'Remarks' data-microtip-position='top' role='tooltip' style = 'cursor:pointer;color:blue' data-appid = '".$val['godown_id']."' class = 'remarks_check'><i class='fa fa-list' aria-hidden='true'></i></span>";
			}else{
				$actionBtn = "<span style = 'cursor: pointer;color: blue;".$style."' aria-label = 'Activate' data-microtip-position='top' role='tooltip' class = 'delete' data-id = '".$val['godown_id']."' data-ids = '2'>
	<i class='fa fa-key' aria-hidden='true'></i>	
</span><span aria-label = 'Remarks' data-microtip-position='top' role='tooltip' style = 'cursor:pointer;color:blue' data-appid = '".$val['godown_id']."' class = 'remarks_check'><i class='fa fa-list' aria-hidden='true'></i></span>";
			}

			$data[] = array('sr_no' => $i,'form_no' => $val['form_no'],'name' => $val['name'],'address_1' => $val['address_1'],'mobileNo' => $val['mobileNo'],'god_address1' => $val['god_address1'],'product_name' => $val['product_name'],'godown_area' => $val['godown_area'],'type_of_godown' => $val['type_of_godown'],'start_date' => $val['start_date'],'other_muncipal_lic' => $val['other_muncipal_lic'],'renewal_lic' => ($val['renewal_lic']) ? 'Yes':'No','old_lic_no' => ($val['old_lic_no']) ? $val['old_lic_no'] : $val['lic_no'],'explosive' => ($val['explosive']) ? 'Yes':'No','pending_dues' => $val['pending_dues'],'disapprove_earlier' => ($val['disapprove_earlier']) ? 'Yes':'No','date_added' => $val['date_added'],'Approval Status' => $statusBtn,'Action' => $actionBtn);
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $this->GodownApp_table->countFiltered($_POST),
            "data" => $data,
        );

        echo json_encode($output);
	}

	public function remarksGet(){
		$godownId = $this->input->post("godownId");

		$data['result'] = $this->application_remarks_table->getAllRemarksById($godownId);

		echo json_encode($data);
	}

	//delete
	public function delStorage()
	{
		$appId = $this->input->post('appid');
		$ids = $this->input->post('ids');

		$this->GodownApp_table->delStorage($appId, $ids);

		$data['success'] = 1;

		if($ids == '2'){
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function getAppStatus()
	{
		$session_userdata = $this->session->userdata('user_session');
		// print_r($session_userdata);exit;
		$role_id = $session_userdata[0]['role_id'];
		$dept_id = $session_userdata[0]['dept_id'];

		$data['status'] = $this->App_status_level_table->getAllStatusByDeptRole($dept_id, $role_id);

		echo json_encode($data);
	}

	public function approveComplain()
	{
		extract($_POST);

		$session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];
        $dept_id = $session_userdata[0]['dept_id'];

		$remarkArray = array(
			'app_id' => $complain_id_app,
			'dept_id' => $dept_id,
			'user_id' => $user_id,
			'role_id' => $role_id,
			'remarks' => $remarks,
			'status_id' => $app_status,
			'status' => "1",
			'is_deleted' => "0",
			'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s")
		);
		// print_r($remarkArray);
		$result = $this->application_remarks_table->insert($remarkArray);
		if($result != null) {

			if($result != null) {
				$data['status'] = '1';
    			$data['messg'] = 'Remark updated successfully.';

			} else {
				$data['status'] = '2';
    			$data['messg'] = 'Oops! Something went wrong.';
			}

		}
		
		echo json_encode($data);
	}
}

?>