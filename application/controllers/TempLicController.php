<?php
 
/*
Ankit Naik
Tree Cutting Section
*/
  
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class TempLicController extends Common {
	public function index(){
		
		// $data['appData'] = $this->Lic_Renewal_table->getAppData();
		$session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
		$role_id = $session_userdata[0]['role_id'];
		$dept_id = $session_userdata[0]['dept_id'];

		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);
		$this->load->view('applications/tempLic/index', $data);
	}

	public function createLic()
	{
		$data['licType'] = $this->Lic_Renewal_table->getLicType();

 		$data['appId'] = $this->applications_details_table->getLastId();
 		
 		// echo "<pre>";print_r($data);exit;
 		
 		$this->load->view('applications/tempLic/createLicense',$data);
	}

 	public function renewApp(){

 		$data['licType'] = $this->Lic_Renewal_table->getLicType();

 		$data['appId'] = $this->Lic_Renewal_table->LastAppId();
 		
 		$this->load->view('applications/tempLic/applyRenewal',$data);

 	}

 	public function addLicType()
 	{
 		// $data['licType'] = $this->Lic_Renewal_table->getLicType1();

 		$this->load->view('master/tempLic/addLicType');
 	}

 	public function deleteLic()
 	{
 		$lic_id = $this->input->post("lic_type_id");
 		$res = $this->Lic_Renewal_table->deleteLic($lic_id);

 		if($res){
 			$data['success'] = 1;
  		}else{
  			$data['success'] = 2;
 		}

 		echo json_encode($data);
 	}

 	//new
 	public function crLicType()
 	{
 		$this->load->view('master/tempLic/create');
 	}

 	//new
 	public function getlictype()
 	{
 		$licType = $this->Lic_Renewal_table->getLicType1();

 		// echo "<pre>";print_r($licType);exit;
 		$data = array();
 		$i = $_POST['start'];

 		foreach($licType as $ktype => $vtype){
 			$i++;

 			if($vtype['status'] == '1'){
 				$statusBtn = "<span class = 'btn btn-success btn-small licStatus' data-id = '".$vtype['lic_type_id']."' data-status = '1' style = 'cursor: pointer'>Active</span>";
 				$editBtn = '<a href="'.base_url().'templic/edits/'.base64_encode($vtype['lic_type_id']).'" style = "font-size: 25px;"><i class="fas fa-edit"></i></a>';
 			}else{
 				$statusBtn = "<span class = 'btn btn-danger btn-small licStatus' data-id = '".$vtype['lic_type_id']."' data-status = '2' style = 'cursor: pointer'>InActive</span>";
 				$editBtn = "";
 			}

 			$action = $editBtn;
 			//'<span style = "font-size: 25px; cursor: pointer" class = "deleteLic"><i class="fas fa-trash"></i>'
 			$data[] = array($i, $vtype['lic_name'], $statusBtn, $action);
 		}

 		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Lic_Renewal_table->countAll(),
            "recordsFiltered" => $this->Lic_Renewal_table->countFiltered($_POST),
            "data" => $data,
        );
 		echo json_encode($output);
 	}

 	public function licSubmit()
 	{
 		$licName = $this->input->post("licName");
 		$result = $this->Lic_Renewal_table->insertType($licName);

 		if($result){
 			$data['success'] = 1;
 		}else{
 			$data['success'] = 2;
 		}

 		echo json_encode($data);
 	}

 	public function deactivateLic()
 	{
 		$lic_type_id = $this->input->post("lic_type_Id");
 		$licStatus = $this->input->post("actualStatus");

 		$result = $this->Lic_Renewal_table->deactivateLic($lic_type_id, $licStatus);

 		if($result){
 			$data['success'] = 1;
 			if($licStatus == '1'){
 				$data['messg'] = "Deactivated SuccessFully";
 			}else{
 				$data['messg'] = "Activated SuccessFully";
 			}
 		}else{
 			$data['success'] = 2;
 		}

 		echo json_encode($data);
 	}

 	public function licEdit()
 	{
 		$licName = $this->input->post("licName");
 		$licId = $this->input->post("licId");

 		$res = $this->Lic_Renewal_table->updateLic($licName, $licId);

 		if($res){
 			$data['success'] = 1;
 			$data['messg'] = "Licence Type Edited Successfully";
 		}else{
 			$data['success'] = 2;
 			$data['messg'] = "Licence Type Edition failed";
 		}

 		echo json_encode($data);
 	}

 	//create license section
 	public function createApplication()
 	{
 		extract($_POST);

 		//expiry license
 		$expiryLic = $_FILES['licenseCopy']['name'];
 		$expData = $this->uploadFiles($expiryLic, './uploads/licImages', 'licenseCopy');

 		//aadhar
 		$aadhar = $_FILES['aadhar']['name'];
 		$aadData = $this->uploadFiles($aadhar, './uploads/aadharImages', 'aadhar');

 		//pan
		$pan = $_FILES['pan']['name'];
 		$panData = $this->uploadFiles($pan, './uploads/panImages', 'pan');

 		// if(!$expData || !$aadData || !$panData){
 			if(!array_key_exists('error1', $expData) && !array_key_exists('error1', $aadData) && !array_key_exists('error1', $panData)){

 				$licArray = array(
 					'form_no' => $form_no,
 					'lic_type' => $licenseType,
 					'name' => $appName,
 					'stall_address' => $stallAddress,
 					'created_date' => date("Y-m-d"),
 					'udated_date' => date("Y-m-d H:i:s")
 				);


 				$licId = $this->Lic_Renewal_table->createLic($licArray);

 				if($licId != ''){

 					//application_details insert
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

 					$dataInserted = array();
 					$arrayType = array('expData', 'aadData', 'panData');

	 				foreach($arrayType as $key => $value){
	 					$fileName = "";
	 					$filePath = "";
	 					$columnName = "";
	 					switch($value){
	 						case 'expData': 
	 							$fileName = $expData['file_name'];
	 							$filePath = $expData['file_path'];
	 							// $columnName = "expiryLicenseId";
	 						break;

	 						case 'aadData': 
	 							$fileName = $aadData['file_name'];
	 							$filePath = $aadData['file_path'];
	 							$columnName = "aadharId";
	 						break;	

	 						case 'panData': 
	 							$fileName = $panData['file_name'];
	 							$filePath = $panData['file_path'];
	 							$columnName = "panId";
	 						break;
	 					}


	 					$licData = array(
	 						'temp_lic_id' => $licId,
	 						'type' => $key + 1,
	 						'file_name' => $fileName,
	 						'path' => $filePath,
	 						'date_added' => date("Y-m-d H:i:s")
	 					);

	 					if($this->Lic_Renewal_table->insertLic($licData, $licId, $columnName) != null){
	 						$dataInserted[] = 1;
	 					}else{
	 						$data['success'] = 2;
	 					}

	 				}

	 				//license Date Save
	 				$dateArray = array(
	 					'lic_id' => $licId,
	 					'license_no' => $licenseNo,
	 					'renewalDate' => date("Y-m-d", strtotime($renewalDate)),
	 					'expiryDate' => date("Y-m-d", strtotime($expiryDate)),
	 					'date_added' => date("Y-m-d H:i:s")
	 				);

	 				$this->Lic_Renewal_table->insertDate($dateArray);

	 				if(count($dataInserted) == 3){
	 					$data['success'] = 1;
	 				}else{
 						$data['success'] = 2;
 						$data['error'] = "licData none";
 					}

 				}else{
 					$data['success'] = 2;
 					$data['error'] = "lic none";
 				}

 			}else{
 				$data['success'] = 2;
 				$data['error'] = "lic Key none";
 			}
 		// }else{
 		// 	$data['success'] = 2;
 		// 	$data['error'] = "licdata false";
 		// }
 		
 		echo json_encode($data);
 	}
 	//End create license section

 	//renew date
 	public function renewApplication()
 	{
 		extract($_POST);

 		$insArray = array(
 			'lic_id' => $lic_id,
 			'renewalDate' => date("Y-m-d", strtotime($renewal_dateEdit)),
 			'expiryDate' => date("Y-m-d", strtotime($expiry_dateEdit)),
 			'date_added' => date("Y-m-d H:i:s")
 		);

 		$res = $this->Lic_Renewal_table->renewDate($lic_id, $insArray);

 		if($res){
 			$data['success'] = 1;
 		}else{
 			$date['success'] = 2;
 		}

 		echo json_encode($data);
 	}

 	//delete app
 	public function deleteApplication(){
 		$lic_id = $this->input->post("lic_id");
 		$actk = $this->input->post("actk");

 		$this->Lic_Renewal_table->deleteApp($lic_id, $actk);

 		$data['success'] = 1;
 		echo json_encode($data);
 	}

 	public function editApplication(){
 		$appId = base64_decode($this->uri->segment(3));

 		$data['appData'] = $this->Lic_Renewal_table->AppDetailsById($appId);

 		$data['licType'] = $this->Lic_Renewal_table->getLicType();

 		// $data['appId'] = $this->Lic_Renewal_table->LastAppId();

 		// echo "<pre>";print_r($data);exit;

 		$this->load->view('applications/tempLic/editRenewal', $data);	
 	}

 	public function editApplicationRenew()
 	{
 		extract($_POST);


 		$expData = "";
 		$aadData = "";
 		$panData = "";

 		if($_FILES['licenseCopy']['name'] != ''){
 			$expData = $this->uploadFiles($_FILES['licenseCopy']['name'], './uploads/licImages', 'licenseCopy');
 		}

 		if($_FILES['pan']['name'] != ''){
 			$panData = $this->uploadFiles($_FILES['pan']['name'], './uploads/panImages', 'pan');
 		}

 		if($_FILES['aadhar']['name'] != ''){
 			$aadData = $this->uploadFiles($_FILES['aadhar']['name'], './uploads/aadharImages', 'aadhar');
 		}

 		//FOR IMAGES
 		$statusImages = 0;
 		$statusRen = 1;

 		if($_FILES['licenseCopy']['name'] == '' && $_FILES['pan']['name'] == '' && $_FILES['aadhar']['name'] == ''){

 			$statusImages = 1;
 			
 			//temp_lic
 			$data['tempArray'] = array(
				'lic_type' => $licenseType,
				'name' => $appName,
				'stall_address' => $stallAddress,
				// 'date_of_renewel' => date("Y-m-d", strtotime($renewalDate)),
				// 'expiry_date' => date("Y-m-d", strtotime($expiryDate)),
				'created_date' => date("Y-m-d", strtotime($applicationDate)),
				'udated_date' => date("Y-m-d H:i:s")
			);
 			

 			//renewal date table
 			if($prevRenDate != $renewalDate && $prevExpDate != $expiryDate){
 				//insert date
 				$newLicenseNo = rand(1000,9999);

 				$data['insertNew'] = array(
 					'lic_id' => $lic_id,
 					'license_no' => $newLicenseNo,
 					'renewalDate' => $renewalDate,
 					'expiryDate' => $expiryDate,
 					'date_added' => date("Y-m-d H:i:s")
 				);

 				$statusRen = 2;
 			}

 			$lastInsertId = $this->Lic_Renewal_table->editLic($data, $statusRen, $lic_id, $statusImages);

 			if($lastInsertId > 0){
 				$data['success'] = 1;
 			}else{
 				$data['success'] = 2;
 			}

 		}elseif ($_FILES['licenseCopy']['name'] != '' || $_FILES['pan']['name'] != '' || $_FILES['aadhar']['name'] == '') {
 			
 			$statusImages = 2;

 			//temp_lic
 			$data['tempArray'] = array(
				'lic_type' => $licenseType,
				'name' => $appName,
				'stall_address' => $stallAddress,
				// 'date_of_renewel' => date("Y-m-d", strtotime($renewalDate)),
				// 'expiry_date' => date("Y-m-d", strtotime($expiryDate)),
				'created_date' => date("Y-m-d H:i:s"),
				'udated_date' => date("Y-m-d H:i:s")
			);

 			//renewal table

 			if($prevRenDate != $renewalDate && $prevExpDate != $expiryDate){
 				//insert date
 				$newLicenseNo = rand(1000,9999);

 				$data['insertNew'] = array(
 					'lic_id' => $lic_id,
 					'license_no' => $newLicenseNo,
 					'renewalDate' => $renewalDate,
 					'expiryDate' => $expiryDate,
 					'date_added' => date("Y-m-d H:i:s")
 				);

 				$statusRen = 2;
 			}

 			//images table
 			$data['images'][] = $expData;
 			$data['images'][] = $aadData;
 			$data['images'][] = $panData;

 			$lastInsertId = $this->Lic_Renewal_table->editLic($data, $statusRen, $lic_id, $statusImages);

 			if($lastInsertId > 0){
 				$data['success'] = 1;
 			}else{
 				$data['success'] = 2;
 			}
 		}

 		echo json_encode($data);
 	}

 	//approval
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

	//get data
	public function getData()
	{
		$searchVal = $_POST['search']['value'];
		$i = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir'];

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

		if($searchVal != ''){
			$appData = $this->Lic_Renewal_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}else{
			$appData = $this->Lic_Renewal_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}
		
		$style = "display:block";

		if($_SESSION['delete_status'] == '0'){
			$style = "display:none";
		}
		
		$data = array();

		if($appData == ''){
			$data[] = array('sr_no' => '','form_no' =>  '','license_no' =>  '','licName' => '','name' => '','stall_address' => 'No Data Found','renewalDate' => '','expiryDate' => '','expiryLicenseId' => '','aadharId' => '','panId' => '','remarks' => '','created_date' => '','action' => '');
		}else{
			foreach($appData as $key => $valData){
				$i++;

				if($valData['status'] == '1'){
					$delBtn = '<span for = "delete" class = "delete" data-actk = "1" data-id = "'.$valData['lic_id'].'" style = "cursor: pointer; color: blue;'.$style.'">
	                                    <i class="fa fa-trash" aria-hidden="true"></i>
	                                  </span>';
				}else{
					$delBtn = '<span for = "activate" class = "delete" data-actk = "2" data-id = "'.$valData['lic_id'].'" style = "cursor: pointer; color:blue;'.$style.'">
	                                    <i class="fa fa-key" aria-hidden="true"></i>
	                                  </span>';
				}
				
				$actionBtn = '<span><a for = "edit" class = "edit" style = "cursor: pointer;" href = "'.base_url().'templic/edit/'.base64_encode($valData['lic_id']).'"><i class="fa fa-edit" aria-hidden="true"></i></a></span>'."<span style = 'cursor: pointer;color: blue;' class = 'remarks' data-id = '".$valData['lic_id']."'>
	<i class='fa fa-list' aria-hidden='true'></i>	
</span>".$delBtn;

				$data[] = array('sr_no' => $i,'form_no' => $valData['form_no'],'license_no' => $valData['license_no'],'licName' => $valData['licName'],'name' => $valData['name'],'stall_address' => $valData['stall_address'],'renewalDate' => ($valData['renewalDate'] != '') ? $valData['renewalDate'] : $valData['date_of_renewel'],'expiryDate' => ($valData['expiryDate'] != '') ? $valData['expiryDate'] : $valData['expiry_date'],'expiryLicenseId' => ($valData['expiryLicenseId'] > 0) ? 'Yes <br> <a href = "'.base_url().'uploads/licImages/'.$valData['expiryLicenseFile'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No','aadharId' => ($valData['aadharId'] > 0) ? 'Yes <br> <a href = "'.base_url().'uploads/aadharImages/'.$valData['aadharFile'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No','panId' => ($valData['panId'] > 0) ? 'Yes <br> <a href = "'.base_url().'uploads/panImages/'.$valData['panFile'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No','remarks' => ($valData['remarks'] != '') ? '<span class = "btn btn-danger btn-sm approvalStatus" style = "cursor:pointer" data-appid = "'.$valData['lic_id'].'">'.$valData['remarks'].'</span>' : '<span class = "btn btn-danger btn-sm approvalStatus" style = "cursor:pointer" data-appid = "'.$valData['lic_id'].'">Awaiting</span>','created_date' => $valData['created_date'],'action' => $actionBtn);
			}//end Foreach
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $this->Lic_TradeFact_table->countFiltered($_POST),
            "data" => $data,
        );

        echo json_encode($output);
	}

	//edit lic type
	public function editLic()
	{
		$appId = base64_decode($this->uri->segment(3));

		$data['licType'] = $this->Lic_Renewal_table->getLicTypeById($appId);

		$this->load->view('master/tempLic/editLicType', $data);
	}

	public function getRemarks(){
		$licId = $this->input->post("lic_id");

		$data['result'] = $this->application_remarks_table->getAllRemarksById($licId);

		echo json_encode($data);
	}
}

?>