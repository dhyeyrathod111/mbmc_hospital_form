<?php

/*
Ankit Naik
Tree Cutting Section
*/

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class TreeCuttingController extends Common {

	public $role_id;
	public $dept_id;
	public $vistor;
	public $user_id;

	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('DepositInspectionModel','deposit_inspection');

		$session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
		$this->role_id = $session_userdata[0]['role_id'];
		$this->dept_id = $session_userdata[0]['dept_id'];
		$this->visitor = $session_userdata[0]['is_visitor'];
		$this->user_id = $session_userdata[0]['user_id'];
	}

	public function index(){
 
		// $data['appData'] = $this->Add_complaint_table->getAppData();
		//approval status
		
		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($this->dept_id, $this->role_id);

		$data['is_payable'] = $this->Add_complaint_table->is_payable($this->dept_id, $this->role_id);
		

		$this->load->view('applications/treeCutting/index',$data);
	}

	public function getData()
	{
		$searchVal = $_POST['search']['value'];
		$i = $_POST['start'];
		$rowperpage = $_POST['length']; // Rows display per page
		$columnIndex = $_POST['order'][0]['column']; // Column index
		$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
		$columnSortOrder = $_POST['order'][0]['dir'];

		$session_userdata = $this->session->userdata('user_session');
		$role_id = $session_userdata[0]['role_id'];
		$dept_id = $session_userdata[0]['dept_id'];
		
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
			$appData = $this->Add_complaint_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}else{
			$appData = $this->Add_complaint_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);	
		}

		$style = "complainStatus";
		
// 		echo "<pre>";print_r($appData);exit;

		// if($_SESSION['delete_status'] == '0'){
		// 	$style = "complainStatus1";
		// }
		
		if($appData == ''){
			$data[] = array('sr_no' => '','formNo' => '','applicantName' => '','mobile' => '','email' => '','wardNo' => 'No Data Found','permission_type' => '', 'ownership_permission' => '','blueprint' => '','docs' => '','rem' => '','app' => '', 'refunds' => '','status' => '','action' => '');
			$appData['totalCount'][0]['Totalrows'] = 1;
		}else{
			
			// echo "<pre>";print_r($appData);exit();

			foreach($appData['query'] as $key => $val){
				$i++;
				//docs button
				$docs = "<span class = 'btn btn-info btn-sm docs' style = 'cursor:pointer' data-id = '".$val['cutAppId']."'>Docs</span>";

				//remarks button
				$remBtn = "<span class = 'btn btn-primary btn-sm remarkscheck' data-id = '".$val['cutAppId']."' style = 'cursor:pointer'>Remarks</span>";

				$modalOpenStatus = true;

				if($val['last_role_id'] != ''){
					$getnextroleid = $this->Add_complaint_table->getRoleId($val['last_role_id']);
				}else{
					$modalOpenStatus = false;
				}

				$nxtrolid = !empty($getnextroleid) ? $getnextroleid[0]['role_id'] : 0; 

				if ($val['last_role_id'] != '') {
					$appBtn = "<span class = 'btn btn-danger btn-sm approvalStatus' data-id = '1' data-appid = '".$val['cutAppId']."' data-deptId = '".$dept_id."' data-nextRoleId = '".$nxtrolid."' data-modalopenstatus = '".$modalOpenStatus."' data-loginRole = '".$role_id."' data-lastroleid = '".$val['last_role_id']."' style = 'cursor: pointer;'>".$val['remarks']."</span>";
				} else {
					$appBtn = "<span class = 'btn btn-danger btn-sm approvalStatus' data-id = '1' data-appid = '".$val['cutAppId']."' data-deptId = '".$dept_id."'  data-modalopenstatus = '".$modalOpenStatus."' data-loginRole = '".$role_id."' data-lastroleid = '".$val['last_role_id']."' style = 'cursor: pointer;'>".'Awaiting'."</span>";
				}

				

				//Active Button
				$status = ($val['is_deleted'] == '0') ? "<span class = 'btn btn-success btn-sm ".$style."' data-id = '1' data-appId = '".$val['cutAppId']."'>Active</span>" : "<span class = 'btn btn-danger btn-sm ".$style."' data-id = '2' data-appId = '".$val['cutAppId']."'>InActive</span>";

				//action Button
				$action = ($val['is_deleted'] == '0') ? "<a aria-label='Edit' href = '".base_url()."garden/edits/".base64_encode($val['cutAppId'])."' data-microtip-position='top' role='tooltip' style = 'cursor:pointer; font-size: 25px; color: black;'><i class = 'fas fa-edit'></i></a>" : "";

				$refundsBtn = "<span class = 'btn btn-info btn-sm refunds' style = 'cursor:pointer' data-appid = '".$val['cutAppId']."' data-loginrole = '".$role_id."'>Refunds</span>";

				
				$payment = "";
				

				$data[] = array('sr_no' => $i, 'formNo' => $val['formNo'],'applicantName' => $val['applicantName'],'mobile' => $val['mobile'],'email' => $val['email'],'wardNo' => $val['wardNo'],'permission_type' => ($val['permission_type'] != '0') ? $val['permission_type_name'] : "", "ownership_permission" => "<a aria-label = 'Ownership Document' href = '".base_url()."uploads/gardenImages/ownership/".$val['ownership_property_pdf']."' data-microtip-position='top' role='tooltip' download><i class = 'fa fa-download' aria-hidden='true'></i></a>", 'blueprint' => ($val['blueprint'] != '') ? "<a aria-label = 'Blueprint' href = '".base_url()."uploads/gardenImages/".$val['blueprint']."' data-microtip-position='top' role='tooltip' download><i class = 'fa fa-download' aria-hidden='true'></i></a>" : "",'docs' => $docs,'rem' => $remBtn, 'app' => $appBtn, 'refunds' => $refundsBtn, 'status' => $status, 'payment' => $payment,'action' => $action );

			}
		}
		// echo "<pre>";print_r($appData);exit;	
		
		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $appData['totalCount'][0]['Totalrows'],
            "data" => $data,
        );

        echo json_encode($output);
	}

	// public function getGardenlist(){

	// }
 
	//add complaint
	public function createComplaint(){
		//get tree name
		$treeData = $this->Add_tree_table->getAllData();
		$data['treeData'] = $treeData;
		$processData = $this->Add_process_table->getAllData();
		$data['processData'] = $processData;
		$data['appId'] = $this->applications_details_table->getLastId();
		$data['permission_type'] = $this->Add_complaint_table->get_permission_type();
		//new code
		$data['tree_number'] = $this->Add_complaint_table->getTreeNo();
		$data['role_id'] = $this->role_id;
		$data['dept_id'] = $this->dept_id;
		$data['is_visitor'] = $this->visitor;
		
		$this->load->view('applications/treeCutting/createComplaint',$data);
	}

	//add tree
	public function addTree(){
		// $treeData = $this->Add_tree_table->getAllData();
		// $data['treeData'] = $treeData;
		$this->load->view('master/garden/index');
	}

	public function getTreeName(){
		//$this->Add_tree_table->deleteTree($treeId, 1);
		$treeData = $this->Add_tree_table->getAllData();
		// echo "<pre>";print_r($treeData);exit;
		$data = array();
		$i = $_POST['start'];

		foreach ($treeData as $kData => $vData) {
			$i++;

			if($vData['status'] == '1'){
				$status = "<span class = 'btn btn-success btn-small treeStatus' data-id = '".$vData['tree_id']."' data-status = '1' style = 'cursor: pointer'>Active</span>";
				$edit = '<a href="'.base_url().'garden/editss/'.base64_encode($vData['tree_id']).'" style = "font-size: 25px;"><i class="fas fa-edit"></i></a>';
			}else{
				$status = "<span class = 'btn btn-danger btn-small treeStatus' data-id = '".$vData['tree_id']."' data-status = '2' style = 'cursor: pointer'>InActive</span>";
				$edit = "";
			}

			$data[] = array($i, $vData['treeName'], $status, $edit);
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Add_complaint_table->countAll(),
            "recordsFiltered" => $this->Add_complaint_table->countFiltered($_POST),
            "data" => $data,
        );
        // echo "<pre>";print_r($output);exit;
 		echo json_encode($output);
	}

	public function crTreeType()
	{
		$this->load->view('master/garden/create');
	}

	public function treeNameSubmit()
	{
		extract($_POST);

		$insertArray = array(
			'treeName' => $treeName,
			'added_by' => '1',
			'date_added' => date("Y-m-d H:i:s")
		);

		$res = $this->Add_tree_table->insertTree($insertArray);

		if($res != ''){
			$data['success'] = 1;
			$data['msg'] = "Registered SuccessFully";
		}else{
			$data['success'] = 2;
			$data['msg'] = "Registeration Failed";
		}

		echo json_encode($data);
	}

	public function treeNameEdit()
	{
		$appId = base64_decode($this->uri->segment(3));

		$data['treeName'] = $this->Add_tree_table->getTreeById($appId);

		$this->load->view('master/garden/edit', $data);
	}

	public function processNameEdit()
	{
		$appId = base64_decode($this->uri->segment(3));

		$data['processName'] = $this->Add_process_table->getProcessById($appId);

		$this->load->view('master/garden/processEdit', $data);
	}

	//add Process
	public function addProcess(){
		// $processData = $this->Add_process_table->getAllData();
		// $data['processData'] = $processData;
		$this->load->view('master/garden/processIndex');	
	}

	public function getProcessName(){
		$processData = $this->Add_process_table->getAllData();
		
		$data = array();
		$i = $_POST['start'];

		foreach ($processData as $kDat => $vDat) {
			$i++;

			if($vDat['status'] == '1'){
				$status = "<span class = 'btn btn-success btn-small processStatus' data-id = '".$vDat['processId']."' data-status = '1' style = 'cursor: pointer'>Active</span>";
				$edit = '<a href="'.base_url().'garden/edit/'.base64_encode($vDat['processId']).'" style = "font-size: 25px;"><i class="fas fa-edit"></i></a>';
			}else{
				$status = "<span class = 'btn btn-danger btn-small processStatus' data-id = '".$vDat['processId']."' data-status = '2' style = 'cursor: pointer'>InActive</span>";
				$edit = "";
			}

			$data[] = array($i, $vDat['processName'], $status, $edit);
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Add_complaint_table->countAll1(),
            "recordsFiltered" => $this->Add_complaint_table->countFiltered2($_POST),
            "data" => $data,
        );
        // echo "<pre>";print_r($output);exit;
 		echo json_encode($output);
	}

	public function crProcessType()
	{
		$this->load->view('master/garden/createProcess');
	}

	//tree submit
	public function treeSubmit(){

		$treeName = $this->input->post("treeName");

		if($treeName != ""){
			$treeData = array(
				'treeName' => $treeName,
				'added_by' => 1,
				'date_added' => date("Y-m-d H:i:s")
			);

			$treeID = $this->Add_tree_table->insertTree($treeData);
			$data['treeId'] = $treeID;
			$data['success'] = "1";
		}else{
			$data['success'] = "2";
		}

		echo json_encode($data);
	}

	//tree delete
	public function deleteTree(){

		$treeId = $this->input->post("treeId");

		if($treeId != ''){
			$res = $this->Add_tree_table->deleteTree($treeId, 1);
			if($res != ''){
				$data['success'] = 1;
			}

		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	//change Tree status
	public function deactivateTree(){

		$treeId = $this->input->post("treeId");
		$actualStatus = $this->input->post("actualStatus");	
		if($treeId != ''){
			$res = $this->Add_tree_table->deleteTree($treeId, 2, $actualStatus);
			if($res != ''){
				$data['success'] = 1;
				if($actualStatus == '1'){
					$data['messg'] = "Deactivated SuccessFully";
				}else{
					$data['messg'] = "Activated SuccessFully";
				}
			}

		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	//edit Tree data
	public function treeEdit(){
		$treeId = $this->input->post("treeId");
		$treeName = $this->input->post("treeName");

		if($treeId != '' && $treeName != ''){
			$editData = array(
				'treeName' => $treeName,
				'date_added' => date("Y-m-d H:i:s")
			);

			$this->Add_tree_table->treeEdit($editData, $treeId);
			$data['success'] = 1;

		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	//End Tree Section

	//Process Secition 
	public function processSubmit(){
		// $processName = $this->input->post("processName");
		extract($_POST);
		if($processName != ""){
			$processData = array(
				'processName' => $processName,
				'added_by' => 1,
				'date_added' => date("Y-m-d H:i:s")
			);

			$processID = $this->Add_process_table->insertProcess($processData);
			$data['processId'] = $processID;
			$data['success'] = "1";
		}else{
			$data['success'] = "2";
		}

		echo json_encode($data);
	}

	//process delete
	public function processDelete(){

		$processId = $this->input->post("processId");

		if($processId != ''){
			$res = $this->Add_process_table->deleteProcess($processId, 1);
			if($res != ''){
				$data['success'] = 1;
			}

		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	//status deactivate
	public function processdeactivate(){
		$processId = $this->input->post("processId");
		$actualStatus = $this->input->post("actualStatus");

		if($processId != ''){
			$res = $this->Add_process_table->deleteProcess($processId, 2, $actualStatus);
			if($res != ''){
				$data['success'] = 1;
				if($actualStatus == '1'){
					$data['messg'] = "Deactivated SuccessFully";
				}else{
					$data['messg'] = "Activated SuccessFully";
				}
			}

		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function	processEdits(){
		$processId = $this->input->post("processId");
		$processName = $this->input->post("processName");

		if($processId != '' && $processName != ''){
			$editData = array(
				'processName' => $processName,
				'date_added' => date("Y-m-d H:i:s")
			);

			$this->Add_process_table->processEdits($editData, $processId);
			$data['success'] = 1;

		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function submitComplaint(){
		$formData = $this->input->post();
		// echo "<pre>";print_r($formData);exit;
		//harcoded rows
		$totalImages = count($_FILES['files']['name']);
		
		//dynamic rows
		$filesNewStatus = false;
		if(array_key_exists("filesNew", $_FILES)){
			$imagesToInsert = count($_FILES['filesNew']['name']); 
			$filesNewStatus = true;
		}

		$fileStatus = true;
		// echo preg_replace('/\s/', '', $_FILES['ownership_file']['name']);exit;
		if($_FILES['ownership_file']['name'] != ''){
			$ownershipData = $this->uploadFiles(preg_replace('/\s/', '', $_FILES['ownership_file']['name']), './uploads/gardenImages/ownership', 'ownership_file');
			
			if(!array_key_exists('error1', $ownershipData)){

				if($_FILES['blueprint']['name'] != ''){
					$blueprintData = $this->uploadFiles($_FILES['blueprint']['name'], './uploads/gardenImages', 'blueprint');
					// print_r($nocData);
					if(!array_key_exists('error1', $blueprintData)){
						$appArray = array(
							'formNo' => $formData['form_no'],
							'applicantName' => $formData["applicant_name"],
							'mobile' => $formData['applicant_mobile_no'],
							'email' => $formData['applicant_email'],
							'address' => $formData['applicant_address'],
							'surveyNo' => $formData['survey_no'],
							'citySurveyNo' => $formData['city_survey_no'],
							'wardNo' => $formData['ward_no'],
							'plotNo' => $formData['plot_no'],
							'noOfTrees' => !empty($formData['no_of_trees']) ? $formData['no_of_trees'] : 0, 
							'permission_type' => $formData['perType'],
							'ownership_property_pdf' => $ownershipData['file_name'],
							'blueprint' => $blueprintData['file_name'],
							'added_by' => $this->user_id,
							'applicantDate' => date("Y-m-d H:i:s"),
							'date_added' => date('Y-m-d H:i:s')
						);
					}else{
						$data['success'] = 2;
						$data['message'] = "Blueprint File Upload Error";
						$fileStatus = false;
						unlink("./uploads/gardenImages/ownership/".$ownershipData['file_name']);
					}
					
				}else{
					$appArray = array(
						'formNo' => $formData['form_no'],
						'applicantName' => $formData["applicant_name"],
						'mobile' => $formData['applicant_mobile_no'],
						'email' => $formData['applicant_email'],
						'address' => $formData['applicant_address'],
						'surveyNo' => $formData['survey_no'],
						'citySurveyNo' => $formData['city_survey_no'],
						'wardNo' => $formData['ward_no'],
						'plotNo' => $formData['plot_no'],
						'noOfTrees' => !empty($formData['no_of_trees']) ? $formData['no_of_trees'] : 0, 
						'permission_type' => $formData['perType'],
						'ownership_property_pdf' => $ownershipData['file_name'],
						'added_by' => $this->user_id,
						'applicantDate' => date("Y-m-d H:i:s"),
						'date_added' => date('Y-m-d H:i:s')
					);
				}

			}else{
				$data['success'] = 2;
				$data['message'] = "Ownership File Error";
				$fileStatus = false;
			}

		}else{
			$data['success'] = 2;
			$data['message'] = "Ownership File Not Present";
			$fileStatus = false;
		}

		if($fileStatus){
			//insert complain id
			$complaintId = $this->Add_complaint_table->insertApplication($appArray);

			if($complaintId != ''){
				$dataStatus = array(
					'dept_id' => $this->dept_id,
					'status' => '1',
					'is_deleted' => 0,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);

				$this->applications_details_table->insert($dataStatus);

				$treeName = $formData["treeName"];
				$processName = $formData['processName'];
				
				for($i = 0; $i <= $totalImages - 1; $i++){
					
					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i]; 

					$imageData = $this->uploadFiles($_FILES['file']['name'], './uploads/gardenImages', 'file');

					if(!array_key_exists('error1', $imageData)){
						//gardendata insert
						$gardenData = array(
							'complain_id' => $complaintId,
							'permission_id' => $processName[$i],
							'tree_no' => $formData["tree_no"][$i],
							'tree_id' => $treeName[$i],
							'no_of_trees' => $formData['total_trees'][$i],
							'conditionStatus' => $formData['condition'][$i],
							'reason_permission' => $formData['reason_for_permission'][$i],
							'orig_image' => $imageData['orig_name'],
							'enc_image' => $imageData['file_name'],
							'image_path' => base_url().'uploads/gardenImages/'.$imageData['file_name'],
							'image_size' => $imageData['file_size'],
							'date_added' => date("Y-m-d H:i:s")
						);

						$this->Add_complaint_table->insertTree($gardenData);

						unset($treeName[$i]);
						unset($processName[$i]);
						unset($formData['total_trees'][$i]);
						unset($formData['reason_for_permission'][$i]);
						unset($formData['condition'][$i]);
						unset($formData['tree_no'][$i]);

						$data['success'] = 1;
						$data['message'] = "File upload";

					}else{
						$data['success'] = 2;
						$data['message'] = "Upload File";
					}
				}//End for hardcoded

				//for dynamic inserted files
				$treeName = array_values($treeName);
				$processName = array_values($processName);
				$total_trees = array_values($formData['total_trees']);
				$reason_permission = array_values($formData['reason_for_permission']);
				$conditionStatus = array_values($formData['condition']);
				$treeNo = array_values($formData["tree_no"]);


				if($filesNewStatus){
					for($j = 0; $j <= $imagesToInsert - 1; $j++){
						$_FILES['filenew']['name'] = $_FILES['filesNew']['name'][$j];
						$_FILES['filenew']['type'] = $_FILES['filesNew']['type'][$j];
						$_FILES['filenew']['tmp_name'] = $_FILES['filesNew']['tmp_name'][$j];
						$_FILES['filenew']['error'] = $_FILES['filesNew']['error'][$j];
						$_FILES['filenew']['size'] = $_FILES['filesNew']['size'][$j];

						$imageDataNew = $this->uploadFiles($_FILES['filenew']['name'], './uploads/gardenImages', 'filenew');

						if(!array_key_exists('error1', $imageDataNew)){

							$gardenDataNew = array(
								'complain_id' => $complaintId,
								'permission_id' => $processName[$j],
								'tree_no' => $treeNo[$j],
								'tree_id' => $treeName[$j],
								'no_of_trees' => $total_trees[$j],
								'conditionStatus' => $conditionStatus[$j],
								'reason_permission' => $reason_permission[$j],
								'orig_image' => $imageDataNew['orig_name'],
								'enc_image' => $imageDataNew['file_name'],
								'image_path' => base_url().'uploads/gardenImages/'.$imageDataNew['file_name'],
								'image_size' => $imageDataNew['file_size'],
								'date_added' => date("Y-m-d H:i:s")
							);

							$this->Add_complaint_table->insertTree($gardenDataNew);
							$data['success'] = 1;
							$data['message'] = "Insert Tree";

						}else{
							$data['success'] = 2;
							$data['message'] = "Upload Extra File";
						}
					}
				}

			}else{
				$data['success'] = 2;
				$data['message'] = "Complaint Error";
			}
		}

		echo json_encode($data);
	}

	public function submitComplaint_old(){
		$formData = $this->input->post();
		// echo "<pre>";print_r($formData);exit;
		//files images
		$totalImages = count($_FILES['files']['name']);
		$session_userdata = $this->session->userdata('user_session');
		//files new
		$filesNewStatus = false;
		if(array_key_exists("filesNew", $_FILES)){
			$imagesToInsert = count($_FILES['filesNew']['name']); 
			$filesNewStatus = true;
		}

		$fileStatus = true;

		//ownership pdf upload
		if($_FILES['ownership_file']['name'] != ''){
			$ownershipData = $this->uploadFiles($_FILES['ownership_file']['name'], './uploads/gardenImages/ownership', 'ownership_file');

			if(!array_key_exists('error1', $ownershipData)){

				if($_FILES['blueprint']['name'] != ''){
					$blueprintData = $this->uploadFiles($_FILES['blueprint']['name'], './uploads/gardenImages', 'blueprint');
					// print_r($nocData);
					if(!array_key_exists('error1', $blueprintData)){
						$appArray = array(
							'formNo' => $formData['form_no'],
							'applicantName' => $formData["applicant_name"],
							'mobile' => $formData['applicant_mobile_no'],
							'email' => $formData['applicant_email'],
							'address' => $formData['applicant_address'],
							'surveyNo' => $formData['survey_no'],
							'citySurveyNo' => $formData['city_survey_no'],
							'wardNo' => $formData['ward_no'],
							'plotNo' => $formData['plot_no'],
							'noOfTrees' => $formData['no_of_trees'], 
							'permission_type' => $formData['perType'],
							'blueprint' => $blueprintData['file_name'],
							'declarationGardenSuprintendent' => $formData['declaration'],
							'added_by' => $session_userdata[0]['user_id'],
							'applicantDate' => date("Y-m-d H:i:s"),
							'date_added' => date('Y-m-d H:i:s')
						);
					}else{
						$data['success'] = 2;
						$data['message'] = "Blueprint File Upload Error";
						$fileStatus = false;
						unlink("./uploads/gardenImages/ownership/".$ownershipData['file_name']);
					}
					
				}else{
					$appArray = array(
						'formNo' => $formData['form_no'],
						'applicantName' => $formData["applicant_name"],
						'mobile' => $formData['applicant_mobile_no'],
						'email' => $formData['applicant_email'],
						'address' => $formData['applicant_address'],
						'surveyNo' => $formData['survey_no'],
						'citySurveyNo' => $formData['city_survey_no'],
						'wardNo' => $formData['ward_no'],
						'plotNo' => $formData['plot_no'],
						'noOfTrees' => $formData['no_of_trees'], 
						'permission_type' => $formData['perType'],
						'ownership_property_pdf' => $ownershipData['file_name'],
						'declarationGardenSuprintendent' => $formData['declaration'],
						'added_by' => $session_userdata[0]['user_id'],
						'applicantDate' => date("Y-m-d H:i:s"),
						'date_added' => date('Y-m-d H:i:s')
					);
				}

			}else{
				$data['success'] = 2;
				$data['message'] = "Ownership File Error";
				$fileStatus = false;
			}

		}else{
			$data['success'] = 2;
			$data['message'] = "Ownership File Not Present";
			$fileStatus = false;
		}
		//end ownerhip pdf upload
		if($fileStatus){
			//insert complain
			$complaintId = $this->Add_complaint_table->insertApplication($appArray);

			if($complaintId != ''){
				// $session_userdata = $this->session->userdata('user_session');
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

				$treeName = $formData["treeName"];
				$processName = $formData['processName'];

				for($i = 0; $i <= $totalImages - 1; $i++){
				$_FILES['file']['name'] = $_FILES['files']['name'][$i];
				$_FILES['file']['type'] = $_FILES['files']['type'][$i];
				$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
				$_FILES['file']['error'] = $_FILES['files']['error'][$i];
				$_FILES['file']['size'] = $_FILES['files']['size'][$i]; 

				$imageData = $this->uploadFiles($_FILES['file']['name'], './uploads/gardenImages', 'file');
					
				if(!array_key_exists('error1', $imageData)){
					$treeArray = array(
						'complain_id' => $complaintId,
						'tree_id' => $treeName[$i],
						'process_id' => $processName[$i],
						'no_of_trees' => $formData['total_trees'][$i],
						'reason_permission' => $formData['reason_for_permission'][$i],
						'orig_image' => $imageData['orig_name'],
						'enc_image' => $imageData['file_name'],
						'image_path' => base_url().'uploads/gardenImages/'.$imageData['file_name'],
						'image_size' => $imageData['file_size'],
						'date_added' => date("Y-m-d H:i:s")
					);

					$this->Add_complaint_table->insertTree($treeArray);

					unset($treeName[$i]);
					unset($processName[$i]);
					unset($formData['total_trees'][$i]);
					unset($formData['reason_for_permission'][$i]);

					$data['success'] = 1;
					$data['message'] = "File upload";

				}else{
					$data['success'] = 2;
					$data['message'] = "Upload File";
				}
				}//End For

				$treeName = array_values($treeName);
				$processName = array_values($processName);
				$total_trees = array_values($formData['reason_for_permission']);
				$reason_permission = array_values($formData['reason_for_permission']);

				if($filesNewStatus){
					//check index treeName to be cont
					for($j = 0; $j <= $imagesToInsert - 1; $j++){
						
						$_FILES['filenew']['name'] = $_FILES['filesNew']['name'][$j];
						$_FILES['filenew']['type'] = $_FILES['filesNew']['type'][$j];
						$_FILES['filenew']['tmp_name'] = $_FILES['filesNew']['tmp_name'][$j];
						$_FILES['filenew']['error'] = $_FILES['filesNew']['error'][$j];
						$_FILES['filenew']['size'] = $_FILES['filesNew']['size'][$j];
						
						$imageDataNew = $this->uploadFiles($_FILES['filenew']['name'], './uploads/gardenImages', 'filenew');
						
						if(!array_key_exists('error1', $imageDataNew)){
							$treeArray = array(
								'complain_id' => $complaintId,
								'tree_id' => $treeName[$j],
								'process_id' => $processName[$j],
								'no_of_trees' => $total_trees[$j],
								'reason_permission' => $reason_permission[$j],
								'orig_image' => $imageDataNew['orig_name'],
								'enc_image' => $imageDataNew['file_name'],
								'image_path' => base_url().'uploads/gardenImages/'.$imageDataNew['file_name'],
								'image_size' => $imageDataNew['file_size'],
								'date_added' => date("Y-m-d H:i:s")
							);

							$this->Add_complaint_table->insertTree($treeArray);
							$data['success'] = 1;
							$data['message'] = "Insert Tree";
						}else{
							$data['success'] = 2;
							$data['message'] = "Upload Extra File";
						}
					}
				}
			}else{
				$data['success'] = 2;
				$data['message'] = "Complaint Error";
			}
		}

		echo json_encode($data);
	}

	public function submitComplaint1(){
		$formData = $this->input->post();
		
		$totalImages = count($_FILES['files']['name']);
		// print_r($_FILES['files']['name']);exit;
		// echo $_FILES['noc']['name'];exit;
		if($_FILES['noc']['name'] != ''){
			//submit form treecuttingapplications
			  $_FILES['filenoc']['name'] = $_FILES['noc']['name'];
	          $_FILES['filenoc']['type'] = $_FILES['noc']['type'];
	          $_FILES['filenoc']['tmp_name'] = $_FILES['noc']['tmp_name'];
	          $_FILES['filenoc']['error'] = $_FILES['noc']['error'];
	          $_FILES['filenoc']['size'] = $_FILES['noc']['size'];

	          $config['upload_path'] = './uploads/gardenImages'; 
	          $config['allowed_types'] = 'pdf|jpg|png|docx';
	          $config['max_size'] = '5000'; // max_size in kb
	          $config['file_name'] = $_FILES['noc']['name'];	
			  $config['encrypt_name'] = TRUE;
			  // print_r($config);exit;
			  $this->upload->initialize($config);

		          if($this->upload->do_upload('filenoc')){
		            // Get data about the file
		            $uploadData = $this->upload->data();
		            $fileNoc = $uploadData['file_name'];

		            $appArray = array(
						'formNo' => $formData['form_no'],
						'applicantName' => $formData["applicant_name"],
						'mobile' => $formData['applicant_mobile_no'],
						'email' => $formData['applicant_email'],
						'address' => $formData['applicant_address'],
						'surveyNo' => $formData['survey_no'],
						'citySurveyNo' => $formData['city_survey_no'],
						'wardNo' => $formData['ward_no'],
						'plotNo' => $formData['plot_no'],
						'noOfTrees' => $formData['no_of_trees'], 
						'nocStatus' => $formData['nocStatus'],
						'noc' => $fileNoc,
						'declarationGardenSuprintendent' => $formData['declaration'],
						'added_by' => '1',
						'applicantDate' => $formData['application_date'],
						'date_added' => date('Y-m-d H:i:s')
					);

		          }else{

		          	$error = array('error1' => $this->upload->display_errors());
		          	print_r($error);

		          }  
		}else{
			$appArray = array(
				'formNo' => $formData['form_no'],
				'applicantName' => $formData["applicant_name"],
				'mobile' => $formData['applicant_mobile_no'],
				'email' => $formData['applicant_email'],
				'address' => $formData['applicant_address'],
				'surveyNo' => $formData['survey_no'],
				'citySurveyNo' => $formData['city_survey_no'],
				'wardNo' => $formData['ward_no'],
				'plotNo' => $formData['plot_no'],
				'noOfTrees' => $formData['no_of_trees'], 
				'nocStatus' => $formData['nocStatus'],
				// 'noc' => $fileNoc,
				'declarationGardenSuprintendent' => $formData['declaration'],
				'added_by' => '1',
				'applicantDate' => $formData['application_date'],
				'date_added' => date('Y-m-d H:i:s')
			);
		}

		// print_r($appArray);gardenImages

		$complaintId = $this->Add_complaint_table->insertApplication($appArray);

		if($complaintId != ''){

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

			$treeName = $formData["treeName"];
			$processName = $formData['processName'];

			for ($i=0; $i < $totalImages; $i++) { 
				  $_FILES['file']['name'] = $_FILES['files']['name'][$i];
		          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
		          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
		          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
		          $_FILES['file']['size'] = $_FILES['files']['size'][$i];

		          $config['upload_path'] = './uploads/gardenImages'; 
		          $config['allowed_types'] = 'pdf|jpg|png|docx';
		          $config['max_size'] = '5000'; // max_size in kb
		          $config['file_name'] = $_FILES['files']['name'][$i];	
	  			  $config['encrypt_name'] = TRUE;

		          $this->upload->initialize($config);

		          if($this->upload->do_upload('file')){
		            // Get data about the file
		            $uploadData = $this->upload->data();
		            $filename = $uploadData['file_name'];
		            // print_r($uploadData);

		            $treeArray = array(
		            	'complain_id' => $complaintId,
		            	'tree_id' => $treeName[$i],
		            	'process_id' => $processName[$i],
		            	'no_of_trees' => $formData['total_trees'][$i],
		            	'reason_permission' => $formData['reason_for_permission'][$i],
		            	'orig_image' => $uploadData['orig_name'],
		            	'enc_image' => $filename,
		            	'image_path' => base_url().'uploads/gardenImages/'.$filename,
		            	'image_size' => $uploadData['file_size'],
		            	'date_added' => date("Y-m-d H:i:s")
		            );

		            $insertArray[] = $this->Add_complaint_table->insertTree($treeArray);

		            //insert file data

		          }else{
		          	$error = array('error' => $this->upload->display_errors());
		          	print_r($error);
		          }

			}//End Loop	

			if($totalImages == count($insertArray)){
				$dataSuccess['success'] = 1;
			}else{
				$dataSuccess['success'] = 2;
			}

		}else{

			$dataSuccess['success'] = 2;

		}

		echo json_encode($dataSuccess);
	}

	public function editApps(){
		$appId = base64_decode($this->uri->segment(3));

		//get app data

		$complainData = $this->Add_complaint_table->getAppDataById($appId);

		//get document data

		$documentData = $this->Add_complaint_table->getDocumentData($appId);

		//tree Data
		$data['treeData'] = $this->Add_tree_table->getAllData();

		//process data
		$data['processData'] = $this->Add_process_table->getAllData();

		//permission data
		$data['permissionTypes'] = $this->Add_complaint_table->get_permission_type();

		$data['complainData'] = $complainData[0];
		$data['gardenData'] = $documentData;
		
		$data['process_status'] = $this->Add_complaint_table->check_approve_by_comm($appId,$this->authorised_user['dept_id']);


		// echo "<pre>";print_r($this->authorised_user['dept_id']);exit();		

		$this->load->view('applications/treeCutting/edit', $data);
	}

	//delete garden
	public function gardenDelete(){

		$gardenId = $this->input->post('gardenId');

		if($this->Add_complaint_table->deleteGarden($gardenId)){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function complainEdit(){

		$formData = $this->input->post();

		$imagesToUpdate = count($_FILES['files']['name']);
		$imagesToInsert = "";

		if(array_key_exists("filesNew", $_FILES)){
			$imagesToInsert = count($_FILES['filesNew']['name']); 
		}

		// print_r($formData);exit;

		$complainId = $formData['complainId'];

		$session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];

		$appArray = array(
			'formNo' => $formData['form_no'],
			'applicantName' => $formData["applicant_name"],
			'mobile' => $formData['applicant_mobile_no'],
			'email' => $formData['applicant_email'],
			'address' => $formData['applicant_address'],
			'surveyNo' => $formData['survey_no'],
			'citySurveyNo' => $formData['city_survey_no'],
			'wardNo' => $formData['ward_no'],
			'plotNo' => $formData['plot_no'],
			'noOfTrees' => $formData['no_of_trees'],
			'permission_type' => $formData['perType'],
			'declarationGardenSuprintendent' => $formData['declaration'],
			'added_by' => $user_id,
			'applicantDate' => date("Y-m-d H:i:s"),
			'date_added' => date('Y-m-d H:i:s')
		);

		//check permission status

		if($formData['is_blueprint'] == '1'){
			//noc section
			if($_FILES['blueprint']['name'] != ''){
				//file present upload
				$blueprintData = $this->uploadFiles($_FILES['blueprint']['name'], './uploads/gardenImages', 'blueprint');
				if(!array_key_exists('error1', $blueprintData)){
					$appArray['blueprint'] = $blueprintData['file_name'];
				}else{
					$data['success'] = 2;
					echo "blueprint";
					echo json_encode($data);
					exit;
				}
			}else{
				//file not present upload data
				$appArray['blueprint'] = '';
			}
		}

		//check for ownership
		if($_FILES['ownership_file']['name'] != ''){
			//edit ownership document
			$ownershipData = $this->uploadFiles($_FILES['ownership_file']['name'], './uploads/gardenImages/ownership', 'ownership_file');

			if(!array_key_exists('error1', $ownershipData)){
				$appArray['ownership_property_pdf'] = $ownershipData['file_name'];
			}else{
				$data['success'] = 2;
				print_r($ownershipData);
				echo "ownership";
				echo json_encode($data);
				exit;
			}
		}

		// if($_FILES['noc']['name'] != '')
		// {
		// 	  $_FILES['filenoc']['name'] = $_FILES['noc']['name'];
	    //       $_FILES['filenoc']['type'] = $_FILES['noc']['type'];
	    //       $_FILES['filenoc']['tmp_name'] = $_FILES['noc']['tmp_name'];
	    //       $_FILES['filenoc']['error'] = $_FILES['noc']['error'];
	    //       $_FILES['filenoc']['size'] = $_FILES['noc']['size'];

	    //       $config['upload_path'] = './uploads/gardenImages'; 
	    //       $config['allowed_types'] = 'pdf|jpg|png|docx';
	    //       $config['max_size'] = '5000'; // max_size in kb
	    //       $config['file_name'] = $_FILES['noc']['name'];	
		// 	  $config['encrypt_name'] = TRUE;
			  
		// 	  $this->upload->initialize($config);

	    //       if($this->upload->do_upload('filenoc')){
	    //       	$uploadData = $this->upload->data();
		//         $nocFilename = $uploadData['file_name'];

		//         $appArray = array(
		// 			'formNo' => $formData['form_no'],
		// 			'applicantName' => $formData["applicant_name"],
		// 			'mobile' => $formData['applicant_mobile_no'],
		// 			'email' => $formData['applicant_email'],
		// 			'address' => $formData['applicant_address'],
		// 			'surveyNo' => $formData['survey_no'],
		// 			'citySurveyNo' => $formData['city_survey_no'],
		// 			'wardNo' => $formData['ward_no'],
		// 			'plotNo' => $formData['plot_no'],
		// 			'noOfTrees' => $formData['no_of_trees'], 
		// 			'nocStatus' => $formData['nocStatus'],
		// 			'noc' => $nocFilename,
		// 			'declarationGardenSuprintendent' => $formData['declaration'],
		// 			'added_by' => '1',
		// 			'applicantDate' => $formData['application_date'],
		// 			'date_added' => date('Y-m-d H:i:s')
		// 		);

	    //       }else{
	    //       	$error = array('error' => $this->upload->display_errors());
		//         print_r($error);
		//         exit;
	    //       }

		// }else{

		// 	$appArray = array(
		// 		'formNo' => $formData['form_no'],
		// 		'applicantName' => $formData["applicant_name"],
		// 		'mobile' => $formData['applicant_mobile_no'],
		// 		'email' => $formData['applicant_email'],
		// 		'address' => $formData['applicant_address'],
		// 		'surveyNo' => $formData['survey_no'],
		// 		'citySurveyNo' => $formData['city_survey_no'],
		// 		'wardNo' => $formData['ward_no'],
		// 		'plotNo' => $formData['plot_no'],
		// 		'noOfTrees' => $formData['no_of_trees'],
		// 		'declarationGardenSuprintendent' => $formData['declaration'],
		// 		'added_by' => '1',
		// 		'applicantDate' => $formData['application_date'],
		// 		'date_added' => date('Y-m-d H:i:s')
		// 	);

		// }

		if($this->Add_complaint_table->complainEdit($appArray, $complainId)){
			//update existing files
			$treeNameEdit = $formData["treeNameEdit"];
			$processNameEdit = $formData['processNameEdit'];
			for ($i=0; $i < $imagesToUpdate; $i++) { 
				$gardenId = $formData['gardenId'][$i];
				if($_FILES['files']['name'][$i] != '')
				{
					//image upload
				  $_FILES['file']['name'] = $_FILES['files']['name'][$i];
		          $_FILES['file']['type'] = $_FILES['files']['type'][$i];
		          $_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
		          $_FILES['file']['error'] = $_FILES['files']['error'][$i];
		          $_FILES['file']['size'] = $_FILES['files']['size'][$i];

		          $config['upload_path'] = './uploads/gardenImages'; 
		          $config['allowed_types'] = 'pdf|jpg|png|docx';
		          $config['max_size'] = '5000'; // max_size in kb
		          $config['file_name'] = $_FILES['files']['name'][$i];	
	  			  $config['encrypt_name'] = TRUE;

	  			  $this->upload->initialize($config);

		          if($this->upload->do_upload('file')){
		            // Get data about the file
		            $uploadData = $this->upload->data();
		            $filename = $uploadData['file_name'];
		            // print_r($uploadData);

		            $updateArray = array(
		            	'complain_id' => $complainId,
		            	'tree_id' => $treeNameEdit[$i],
		            	'process_id' => $processNameEdit[$i],
		            	'no_of_trees' => $formData['total_trees_edit'][$i],
		            	'reason_permission' => $formData['reason_for_permission_edit'][$i],
		            	'orig_image' => $uploadData['orig_name'],
		            	'enc_image' => $filename,
		            	'image_path' => base_url().'uploads/gardenImages/'.$filename,
		            	'image_size' => $uploadData['file_size'],
		            	'date_added' => date("Y-m-d H:i:s")
		            );

		            if($this->Add_complaint_table->gardenDataUpdate($updateArray, $complainId, $gardenId))
		            {
		            	$data['success'] = 1;
		            }else{
		            	$data['success'] = 2;
		            }

		            //insert file data

		          }else{
		          	$error = array('error' => $this->upload->display_errors());
		          	print_r($error);
		          }

	  			}else{
	  				//only data
	  				$updateArray = array(
	  					'complain_id' => $complainId,
	  					'tree_id' => $treeNameEdit[$i],
	  					'process_id' => $processNameEdit[$i],
	  					'no_of_trees' => $formData['total_trees_edit'][$i],
	  					'reason_permission' => $formData['reason_for_permission_edit'][$i],
	  					'date_added' => date('Y-m-d H:i:s')
	  				);

	  				if($this->Add_complaint_table->gardenDataUpdate($updateArray, $complainId, $gardenId)){
	  					$data['success'] = 1;
	  				}else{
	  					$data['success'] = 2;
	  				}

	  			}
			}//End For Loop

			//insert new files
			if(array_key_exists('treeName', $formData)){
				$treeName = $formData["treeName"];
				$processName = $formData['processName'];
				
				for ($i=0; $i < $imagesToInsert; $i++) { 
					
				  $_FILES['fileNew']['name'] = $_FILES['filesNew']['name'][$i];
		          $_FILES['fileNew']['type'] = $_FILES['filesNew']['type'][$i];
		          $_FILES['fileNew']['tmp_name'] = $_FILES['filesNew']['tmp_name'][$i];
		          $_FILES['fileNew']['error'] = $_FILES['filesNew']['error'][$i];
		          $_FILES['fileNew']['size'] = $_FILES['filesNew']['size'][$i];

		          $config['upload_path'] = './uploads/gardenImages'; 
		          $config['allowed_types'] = 'pdf|jpg|png|docx';
		          $config['max_size'] = '5000'; // max_size in kb
		          $config['file_name'] = $_FILES['files']['name'][$i];	
	  			  $config['encrypt_name'] = TRUE;			

		          $this->upload->initialize($config);

		          if($this->upload->do_upload('fileNew')){
		            // Get data about the file
		            $uploadData = $this->upload->data();
		            $filename = $uploadData['file_name'];
		            // print_r($uploadData);

		            $updateArray = array(
		            	'complain_id' => $complainId,
		            	'tree_id' => $treeName[$i],
		            	'process_id' => $processName[$i],
		            	'no_of_trees' => $formData['total_trees'][$i],
		            	'reason_permission' => $formData['reason_for_permission'][$i],
		            	'orig_image' => $uploadData['orig_name'],
		            	'enc_image' => $filename,
		            	'image_path' => base_url().'uploads/gardenImages/'.$filename,
		            	'image_size' => $uploadData['file_size'],
		            	'date_added' => date("Y-m-d H:i:s")
		            );

		            $insertArray[] = $this->Add_complaint_table->insertTree($updateArray);

		            //insert file data

		          }else{
		          	$error = array('error' => $this->upload->display_errors());
		          	print_r($error);
		          }
		          	
				}//End Loop

			if(count($insertArray) == $imagesToInsert){
				$data['success'] = 1;
			}else{
				$data['success'] = 2;
			}
			}//end key
			
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function complainDelete()
	{
		$complainId = $this->input->post("complainId");
		$appId = $this->input->post("appId");

		if($this->Add_complaint_table->complainDelete($complainId, $appId)){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
 		}
 		echo json_encode($data);
	}

	// public function getAppStatus()
	// {
	// 	$approvalId = $this->input->post("approvalId");
	// 	$session_userdata = $this->session->userdata('user_session');
	// 	$role_id = $session_userdata[0]['role_id'];
	// 	$dept_id = $session_userdata[0]['dept_id'];
	// 	$data['status'] = $this->App_status_level_table->getAllStatusByDeptRole($dept_id, $role_id);
	// 	echo json_encode($data);
	// }

	public function getAppStatus()
	{
		$approvalId = $this->input->post("approvalId"); //appId
		
		$deposit_inspection_data = $this->deposit_inspection->get_deposit_by_dept_id($this->dept_id);
		
		$garden_application = $this->deposit_inspection->getTrecuttingAppBYID($approvalId);

		$getPermissionType = $this->deposit_inspection->getPermissionType($approvalId);
// 		print_r($garden_application);exit;
		
		$data['user_details'] = $this->authorised_user; 

		if (!empty($deposit_inspection_data) && isset($deposit_inspection_data)) {

			$deposit_stack = array(
				'current_deposit_amt' => ($getPermissionType) ? ($getPermissionType[0]['permission_type'] == '3') ? '0' : $deposit_inspection_data->deposit : $deposit_inspection_data->deposit, 
				'current_inspection_fees' => $deposit_inspection_data->inspection_fee,
				'Tree_count' => $garden_application[0]['totaltrees'],
				'Total_deposit' => ($getPermissionType) ? ($getPermissionType[0]['permission_type'] == '3') ? '0' : ($garden_application[0]['totaltrees'] * $deposit_inspection_data->deposit) : ($garden_application[0]['totaltrees'] * $deposit_inspection_data->deposit),
				'total_inspection_amt' => $garden_application[0]['totaltrees'] * $deposit_inspection_data->inspection_fee,
				'netTotal' => ($getPermissionType) ? ($getPermissionType[0]['permission_type'] == '3') ? ($garden_application[0]['totaltrees'] * $deposit_inspection_data->inspection_fee) : ($garden_application[0]['totaltrees'] * $deposit_inspection_data->deposit) + ($garden_application[0]['totaltrees'] * $deposit_inspection_data->inspection_fee) :($garden_application[0]['totaltrees'] * $deposit_inspection_data->deposit) + ($garden_application[0]['totaltrees'] * $deposit_inspection_data->inspection_fee)
			);

			$data['status'] = $this->App_status_level_table->getAllStatusByDeptRole($this->dept_id, $this->role_id);
			$data['response_status'] = TRUE;
			$data['deposit_stack'] = $deposit_stack; 
			
		} else {

			$data['response_status'] = FALSE;
			$data['message'] = '';
		}	
		echo json_encode($data);
	}

	public function getGardenDataById(){
		$complainId = $this->input->post("complainId");

		$data['gardenData'] = $this->Add_complaint_table->getGardenData($complainId);
		
		echo json_encode($data);
	}
	
	public function complainApprove(){
		extract($_POST);
		// print_r($_POST);exit;

		$session_userdata = $this->session->userdata('user_session');
        $user_id = $session_userdata[0]['user_id'];
        $role_id = $session_userdata[0]['role_id'];
        $dept_id = $session_userdata[0]['dept_id'];

		$remarkArray = array(
			'app_id' => $complain_id_app,
			'dept_id' => $dept_id,
			'sub_dept_id' => '3',
			'user_id' => $user_id,
			'role_id' => $role_id,
			'remarks' => $remarks,
			'commissionerApproval' => $optradio,
			'status_id' => $app_status,
			'status' => "1",
			'is_deleted' => "0",
			'created_at' => date("Y-m-d H:i:s"),
			'updated_at' => date("Y-m-d H:i:s")
		);
		// print_r($remarkArray);
		//delete existing remarks
		$delRemarks = $this->application_remarks_table->updateAllPrevious($dept_id, $role_id);

		$result = $this->application_remarks_table->insert($remarkArray);
		if($result != null) {
//ALTER TABLE `application_remarks`  ADD `commissionerApproval` INT(11) NOT NULL COMMENT '0:Not Approval, 1:Approval'  AFTER `remarks`;
			// $update_data = array(
			// 	'status' => $status,
			// 	'updated_at' => date('Y-m-d H:i:s')
			// );

			// $final_result = $this->pwd_applications_table->update($update_data,$app_id);

			if($result != null) {
				$data['status'] = '1';
    			$data['messg'] = 'Approved successfully.';

			} else {
				$data['status'] = '2';
    			$data['messg'] = 'Oops! Something went wrong.';
			}
		}
		
			echo json_encode($data);
	}

	public function remarksGet(){
		$complainId = $this->input->post("complainId");

		$data['result'] = $this->application_remarks_table->getAllRemarksById($complainId);

		echo json_encode($data);
	}

	function getonlyTreeName(){
		$treeData = $this->Add_tree_table->getAllData();
		echo json_encode($treeData);
	}

	function getonlyProcessName(){
		// $processData = $this->Add_process_table->getAllData();
		$processData = $this->Add_complaint_table->get_permission_type();
		echo json_encode($processData);
	}
	

	//get tree no
	public function getTreeNo(){
		$treeNo = $this->Add_complaint_table->getTreeNo();
		// print_r($treeNo);
		echo $treeNo[0]['random'];
	}

	public function getRefundableData(){
		$complainId = $this->input->post("complainId");

		$refundableData = $this->Add_complaint_table->getRefundableData($complainId, $this->dept_id);
		
		if(!empty($refundableData)){
			$data['refundableData'] = $refundableData['res'];
			$data['show'] = $refundableData['show'];
		}else{
			$data['refundableData'] = '';
			$data['show'] = 2;
		}

		echo json_encode($data);
	}

	public function approveRefund(){
		$complainId = $this->input->post("complainId");
		$gardenId = $this->input->post("gardenId");
		if($gardenId != '' && $complainId != ''){
			$res = $this->Add_complaint_table->approveRefund($gardenId, $complainId, $this->user_id);
			if($res){
				$data['success'] = '1';
			}else{
				$data['success'] = '2';
			}
			
		}else{
			$data['success'] = '2';
		}

		echo json_encode($data);
	}

	public function approveRefundCancel(){
		$complainId = $this->input->post("complainId");
		$gardenId = $this->input->post("gardenId");

		if($complainId != '' && $gardenId != ''){
			$res = $this->Add_complaint_table->approveRefundCancel($complainId, $gardenId);

			if($res){
				$data['success'] = 1;
			}else{
				$data['success'] = 2;
			}
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}
	
	public function update_treecutting_application()
	{
		$formData = $this->input->post();$app_id = $formData['complainId'];$image_path = './uploads/gardenImages';
		$inputStack = array(
			'formNo' => $formData['form_no'],
			'applicantName' => $formData["applicant_name"],
			'mobile' => $formData['applicant_mobile_no'],
			'email' => $formData['applicant_email'],
			'address' => $formData['applicant_address'],
			'surveyNo' => $formData['survey_no'],
			'citySurveyNo' => $formData['city_survey_no'],
			'wardNo' => $formData['ward_no'],
			'plotNo' => $formData['plot_no'],
			'noOfTrees' => !empty($formData['no_of_trees']) ? $formData['no_of_trees'] : 0 ,
			'declarationGardenSuprintendent' => $formData['declaration'],
			'added_by' => $this->authorised_user['user_id'],
		);
		$qeury_response = $this->Add_complaint_table->update_treecutting_master($inputStack,$app_id);

		try {

			if (!empty($_FILES['ownership_file']['name'])) {
				$ownership_file_upload = json_decode($this->file_upload($_FILES['ownership_file'],$image_path));
				if ($ownership_file_upload->status === TRUE) {
					$ownership_file_update = array(
						'ownership_property_pdf' => $ownership_file_upload->file_data->file_name,
					);
					$qeury_response = $this->Add_complaint_table->update_treecutting_master($ownership_file_update,$app_id);
				}
			}

			foreach ($formData['tree_no'] as $key => $oneTreePost) :
				$garden_image = array();
				if ( !empty($formData['gardenId'][$key]) ) : 
					$garden_id = $formData['gardenId'][$key];
				else : 
					$garden_id = FALSE;
				endif ;
				$singelTreeData = json_decode($this->file_upload(reconfig_file_structure($_FILES['filesNew'],$key),$image_path));
				if ($singelTreeData->status === TRUE) {
					$tree_post_data['orig_image'] = $singelTreeData->file_data->orig_name;
					$tree_post_data['enc_image'] = $singelTreeData->file_data->file_name;
					$tree_post_data['image_path'] = base_url('uploads/gardenImages/'.$singelTreeData->file_data->file_name);
					$tree_post_data['image_size'] = $singelTreeData->file_data->file_size;
				} 

				$tree_post_data['complain_Id'] = $app_id;
				$tree_post_data['permission_id'] = $formData['processName'][$key];
				$tree_post_data['tree_no'] = $oneTreePost;
				$tree_post_data['tree_id'] = $formData['treeName'][$key];
				$tree_post_data['no_of_trees'] = $formData['total_trees'][$key];
				$tree_post_data['conditionStatus'] = $formData['condition'][$key];
				$tree_post_data['reason_permission'] = $formData['reason_for_permission'][$key];
				$tree_post_data['date_added'] = date("Y-m-d H:i:s");
				$tree_post_data['refundable'] = explode(",",$formData['refund'])[$key];
				$gardendata_update_insert = $this->Add_complaint_table->manage_garden_data($tree_post_data,$garden_id);
			endforeach ; 
			
			$this->response['status'] = TRUE;
			$this->response['message'] = "SuccessFully updated";
		} catch (Exception $e) {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Something went wrong";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}

}//End Controller
?>
