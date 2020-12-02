<?php

/*
Ankit Naik
Tree Cutting Section
*/
  
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class TradeFactLicController extends Common {
	public function index(){
		
		// $data['appData'] = $this->Lic_TradeFact_table->getAppData();
		$session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
		$role_id = $session_userdata[0]['role_id'];
		$dept_id = $session_userdata[0]['dept_id'];

		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);

		$this->load->view('applications/tradeFact/index', $data);
	}

	public function getData()
	{ 
		$searchVal = $_POST['search']['value'];
		$i = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir'];
		// echo $this->input->post("fromDate");
		if($this->input->post("fromDate") != ''){
			$fromDate = date("Y-m-d",strtotime($this->input->post("fromDate")));
		}else{
			$fromDate = "";
		}
		
		if($this->input->post("toDate") != ''){
			$toDate = date("Y-m-d", strtotime($this->input->post("toDate")));
		}else{
			$toDate = "";
		}
		
		$approval = $this->input->post("approval");
		$approvalStatus = $this->input->post("approval_status");

		// echo $fromDate."-".$toDate."-".$approval."-".$approvalStatus;exit;

		if($searchVal != ''){
			$appData = $this->Lic_TradeFact_table->getData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}else{

			$appData = $this->Lic_TradeFact_table->getData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}

		$style = "display:block";

		// if($_SESSION['delete_status'] == '0'){
		// 	$style = "display:none";
		// }
		
		$data = array();
		if($appData == ''){
			$data[] = array('', '', '', 'No Data Found', '', '', '', '');
		}else{

			foreach ($appData as $keyData => $valueData) {
				$i++;

				//get prop by id

				$propName = $this->Lic_TradeFact_table->propById($valueData['type_of_property']);
					
				if($valueData['remarks'] != ''){
					$statusRemarks = $valueData['remarks'];
				}else{
					$statusRemarks = "Unapproved";
				}	

				$delStatus = "";
				if($valueData['status'] == '1'){
					$delStatus = "<span aria-label = 'Delete' data-microtip-position='top' role='tooltip' for = 'delete' data-id = '".$valueData['tradefac_lic_id']."' data-act = '1' class = 'delete' style = 'cursor:pointer;color: blue;".$style."'><i class='fa fa-trash' aria-hidden='true'></i></span>";
					$editBtn = "<span>
	 <a for = 'edit' class = 'edit' style = 'cursor: pointer;' aria-label = 'Edit' href = '".base_url()."tradefactlic/edit/".base64_encode($valueData['tradefac_lic_id'])."' data-microtip-position='top' role='tooltip'>
	  <i class='fa fa-edit' aria-hidden='true'></i>
	 </a>
	</span>";
				}else{
					$delStatus = "<span for = 'delete' aria-label = 'Activate' data-microtip-position='top' role='tooltip' data-id = '".$valueData['tradefac_lic_id']."' class = 'delete' data-act = '2' style = 'cursor:pointer;color:blue;".$style."'><i class='fa fa-key' aria-hidden='true'></i></span>";
					$editBtn = "";
				}


				$statusBtn = "<span class = 'btn btn-danger btn-sm approvalStatus' style = 'cursor:pointer;' data-id = '".$valueData['tradefac_lic_id']."'>".$statusRemarks."</span>";
				$actionBtn = $editBtn."<span style = 'cursor: pointer;color: blue;' aria-label = 'Remarks' data-microtip-position='top' role='tooltip' class = 'remarks' data-id = '".$valueData['tradefac_lic_id']."'>
		<i class='fa fa-list' aria-hidden='true'></i>	
	</span>".$delStatus;

				// $data[] = array($i, $valueData['form_no'], $valueData['name'], $valueData['shop_no'], $valueData['address'], $valueData['property_no'], $valueData['shop_name'], $valueData['type_of_business'], $valueData['new_renewal'], $valueData['existing_no'], $propName[0]['prop_type_name'], $valueData['property_date'], $valueData['aadhar_no'], $valueData['pan_no'], $valueData['date_no_obj'], $valueData['date_food_lic'], $valueData['date_property_tax'], $valueData['date_establishment'], $valueData['date_assurance'], $statusBtn, $actionBtn);

				$data[] = array('sr_no' => $i,'form_no' => $valueData['form_no'],'name' => $valueData['name'],'shop_name' => $valueData['shop_name'],'type_of_business' => $valueData['type_of_business'],'new_renewal' => ($valueData['new_renewal'] == '1') ? 'New' : 'Renewal','status' => $statusBtn,'action' => $actionBtn);
			}
		}
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $this->Lic_TradeFact_table->countFiltered($_POST),
            "data" => $data,
        );

        echo json_encode($output);
	}

	public function getRemarks()
	{
		$facId = $this->input->post("facId");

		$data['result'] = $this->application_remarks_table->getAllRemarksById($facId);

		echo json_encode($data);
	}

	public function create(){
		
		$data['appId'] = $this->applications_details_table->getLastId();

		$data['property_type'] = $this->Lic_TradeFact_table->getPropType();

		$this->load->view('applications/tradeFact/createTFLic', $data);
	}

	public function getLicData()
	{
		$licNo = $this->input->post("licNo");

		$res = $this->Lic_TradeFact_table->getLicData($licNo);

		echo json_encode($res);
	}

	public function createFactLic(){
		extract($_POST);

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

		$insertArray = array(
			'form_no' => $form_no,
			'name' => $appName,
			'shop_no' => $shopNo,
			'address' => $Address,
			'property_no' => $propertyNo,
			'shop_name' => $shopName,
			'type_of_business' => $businessType,
			'new_renewal' => $licenseType,
			'existing_no' => $existingNo,
			'type_of_property' => $propertyType,
			'property_date' => date("Y-m-d",strtotime($propertyDate)),
			'aadhar_no' => $aadharNo,
			'pan_no' => $panNo,
			'date_no_obj' => date("Y-m-d",strtotime($noObjDate)),
			'date_food_lic' => date("Y-m-d",strtotime($foodLicDate)),
			'date_property_tax' => date("Y-m-d",strtotime($propTaxDate)),
			'date_establishment' => date("Y-m-d",strtotime($establishmentDate)),
			'date_assurance' => date("Y-m-d",strtotime($assuranceDate)),
			'application_date' => $applicationDate
		);

		$res = $this->Lic_TradeFact_table->createLic($insertArray);

		if($res){
			$data['success'] = 1;
			$data['messg'] = "Lic Created Successfully";
		}else{
			$date['success'] = 2;
			$data['messg'] = "Lic Creation Failed";
		}

		echo json_encode($data);
	}

	//edit
	public function editLic()
	{
		$tradeFacLic_id = base64_decode($this->uri->segment(3));

		$data['appData'] = $this->Lic_TradeFact_table->appDataById($tradeFacLic_id);

		$this->load->view('applications/tradeFact/editTFLic', $data);
	}

	public function editFactLic()
	{
		extract($_POST);

		$updateArray = array(
			'form_no' => $form_no,
			'name' => $appName,
			'shop_no' => $shopNo,
			'address' => $Address,
			'property_no' => $propertyNo,
			'shop_name' => $shopName,
			'type_of_business' => $businessType,
			'new_renewal' => $licenseType,
			'existing_no' => $existingNo,
			'type_of_property' => $propertyType,
			'property_date' => date("Y-m-d",strtotime($propertyDate)),
			'aadhar_no' => $aadharNo,
			'pan_no' => $panNo,
			'date_no_obj' => date("Y-m-d",strtotime($noObjDate)),
			'date_food_lic' => date("Y-m-d",strtotime($foodLicDate)),
			'date_property_tax' => date("Y-m-d",strtotime($propTaxDate)),
			'date_establishment' => date("Y-m-d",strtotime($establishmentDate)),
			'date_assurance' => date("Y-m-d",strtotime($assuranceDate)),
			'application_date' => $applicationDate
		);

		$res = $this->Lic_TradeFact_table->editLic($updateArray, $tradeFact_id);

		if($res){
			$data['success'] = 1;
			$data['messg'] = "Edited Successfully";
		}else{
			$date['success'] = 2;
			$data['messg'] = "Edition Failed";
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

	function delete(){
		$act = $this->input->post("act");
		$id = $this->input->post("id");

		$res = $this->Lic_TradeFact_table->delete($act, $id);

		if($res){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);exit;
	}
}//controller End	