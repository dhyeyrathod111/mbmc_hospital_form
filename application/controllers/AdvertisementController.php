<?php
 
/*
Ankit Naik
Advertisement Section
*/

defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class AdvertisementController extends Common {

	public function __construct()
	{
	    parent::__construct();
	    $session_userdata = $this->session->userdata('user_session');
		$this->role_id = $session_userdata[0]['role_id'];
		$this->dept_id = $session_userdata[0]['dept_id'];
	}

	public function index()
	{
		
		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($this->dept_id);
		
		$this->load->view("applications/advertisement/index", $data);
	}

	public function create()
	{
		$data['adv_type'] = $this->Advertisement_table->adv_type();
		$data['illuminate'] = $this->Advertisement_table->illuminate();
		$data['req_type'] = $this->Advertisement_table->req_type();
		$data['appId'] = $this->applications_details_table->getLastId();
		
		$this->load->view("applications/advertisement/create", $data);
	}

	public function getData()
	{
		$searchVal = $_POST['search']['value'];

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
			$appData = $this->Advertisement_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus);
		}else{
			$appData = $this->Advertisement_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus);
		}

		$style = "display:block";
		// echo "delete_status: ".$_SESSION['delete_status'];exit;
		// if($_SESSION['delete_status'] == '0'){
		// 	$style = "display:none";
		// }
		
		$i = $_POST['start'];
		$data = array();

		// echo "<pre>";print_r($appData);exit;
		if($appData == ''){
			$data[] = array('', '', '', '', '', '', '', '', '', '', 'No Data Found', '', '', '', '', '', '', '', '', '', '', '', '', '');
		}else{
			foreach($appData as $kData => $vData){
				$i++;

				$pancard = ($vData['pancard'] != '') ? 'Yes<br><a href = "'.base_url().'uploads/advImages/'.$vData['pancard'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No';

				$aadhar = ($vData['aadhar_card'] != '') ? 'Yes<br><a href = "'.base_url().'uploads/advImages/'.$vData['aadhar_card'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No';

				$society = ($vData['society_notice_status'] == '2') ? 'Yes<br><a href = "'.base_url().'uploads/advImages/'.$vData['society_notice'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No';

				$noc = ($vData['owner_noc_status'] == '2') ? 'Yes<br><a href = "'.base_url().'uploads/advImages/'.$vData['owner_noc'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>' : 'No';

				if($vData['remarks'] != ''){
					$statusRemarks = $vData['remarks'];
				}else{
					$statusRemarks = "Awaiting";
				}

				$statusBtn = "<span class = 'btn btn-danger btn-sm approvalStatus' style = 'cursor:pointer;' data-id = '".$vData['adv_id']."'>".$statusRemarks."</span>";

				$delStatus = "";
				if($vData['status'] == '1'){
					$delStatus = "<span aria-label='Delete' href = '#' data-microtip-position='top' role='tooltip' for = 'delete' data-id = '".$vData['adv_id']."' data-act = '1' class = 'delete' style = 'cursor:pointer;color: blue;".$style."'><i class='fa fa-trash' aria-hidden='true'></i></span>";
				}else{
					$delStatus = "<span aria-label='Activate' href = '#' data-microtip-position='top' role='tooltip' for = 'delete' data-id = '".$vData['adv_id']."' class = 'delete' data-act = '2' style = 'cursor:pointer;color:blue;".$style."'><i class='fa fa-key' aria-hidden='true'></i></span>";
				}

				$action = "<span>
 <a for = 'edit' class = 'edit' style = 'cursor: pointer;' aria-label = 'Edit' href = '".base_url()."advertisement/edit/".base64_encode($vData['adv_id'])."' data-microtip-position = 'top' role = 'tooltip'>
  <i class='fa fa-edit' aria-hidden='true'></i>
 </a>
</span>".$delStatus;

				$data[] = array($i, $vData['form_no'], $vData['name'], $vData['hoarding_location_address'], $vData['adv_type'], $vData['ill_name'], $vData['hoarding_length'], $vData['hoarding_breadth'], $vData['height_of_road'], $vData['type_of_request'], $pancard, $aadhar, $society, $vData['owner_hoarding_name'], $noc, $vData['hoarding_location'], $vData['adv_start_date'], $vData['no_adv_days'], $vData['end_date'], $vData['rate'], $vData['amount'], $vData['application_date'], $statusBtn, $action);
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

	public function createApplications()
	{
		extract($_POST);
		if($society_notice == '2'){
			if($_FILES['soc_not']['name'] != ''){
				$soc_notUpload = $this->uploadFiles($_FILES['soc_not']['name'], './uploads/advImages', 'soc_not');
				$soc_notUploadName = $soc_notUpload['file_name'];
			}else{
				$soc_notUploadName = "";
			}
		}else{
			$soc_notUploadName = "";
		}

		if($noc == '2'){
			if($_FILES['upload_noc']['name'] != ''){
				$nocUpload = $this->uploadFiles($_FILES['upload_noc']['name'], './uploads/advImages', 'upload_noc');
				$nocUploadName = $nocUpload['file_name'];
			}else{
				$nocUploadName = "";
			}
		}else{
			$nocUploadName = "";
		}

		if($_FILES['aadhar']['name'] != ''){
			$aadhar = $this->uploadFiles($_FILES['aadhar']['name'], './uploads/advImages', 'aadhar');
			$aadharName = $aadhar['file_name'];
		}else{
			$aadharName = "";
		}

		if($_FILES['pancard']['name'] != ''){
			$pan = $this->uploadFiles($_FILES['pancard']['name'], './uploads/advImages', 'pancard');
			$panName = $pan['file_name'];
		}else{
			$panName = "";
		}

		$insertArray = array(
			'form_no' => $form_no,
			'name' => $name,
			'address' => $address,
			'hoarding_location_address' => $hoarding_address,
			'type_of_adv' => $adv_type,
			'illuminate' => $illuminate,
			'hoarding_length' => $hoarding_length,
			'hoarding_breadth' => $hoarding_breadth,
			'height_of_road' => $road_height,
			'serchena' => $serchana,
			'type_of_request' => $req_type,
			'comp_address1' => $comp_add1,
			'comp_address2' => $comp_add2,
			'pancard' => $panName,
			'aadhar_card' => $aadharName,
			'society_notice_status' => $society_notice,
			'society_notice' => $soc_notUploadName,
			'owner_hoarding_name' => $owner_hoarding_name,
			'owner_hoarding_address' => $owner_hoarding_add,
			'owner_noc_status' => $noc,
			'owner_noc' => $nocUploadName,
			'hoarding_location' => $hoarding_loc,
			'adv_start_date' => date("Y-m-d",strtotime($start_date)),
			'no_adv_days' => $no_of_days,
			'end_date' => date("Y-m-d",strtotime($end_date)),
			'rate' => $rate,
			'amount' => $amount,
			'application_date' => date("Y-m-d H:i:s")
		);

		// echo "<pre>";print_r($insertArray);exit;

		$res_id = $this->Advertisement_table->insert($insertArray);

		if($res_id != ''){
			$data['success'] = 1;
			$data['msg'] = "Application Registered Successfully";
		}else{
			$data['success'] = 2;
			$data['msg'] = "Application Registration Failed";
		}

		echo json_encode($data);
	}

	function getAppStatus(){
		$data['status'] = $this->App_status_level_table->getAllStatusByDeptRole($this->dept_id, $this->role_id);

		echo json_encode($data);
	}

	function approveComplain(){
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

	public function deleteApplication()
	{
		$adv_id = $this->input->post("adv_id");
 		$actk = $this->input->post("actk");

 		$this->Advertisement_table->deleteApp($adv_id, $actk);

 		$data['success'] = 1;
 		echo json_encode($data);
	}

	public function editAdv()
	{
		$appId = base64_decode($this->uri->segment(3));

		$data['adv'] = $this->Advertisement_table->getLicTypeById($appId);
		$data['adv_type'] = $this->Advertisement_table->adv_type();
		$data['illuminate'] = $this->Advertisement_table->illuminate();
		$data['req_type'] = $this->Advertisement_table->req_type();

		$this->load->view('applications/advertisement/edit', $data);
	}

	public function editApplications()
	{
		extract($_POST);
		if($society_notice == '2'){
			if($_FILES['soc_not']['name'] != ''){
				$soc_notUpload = $this->uploadFiles($_FILES['soc_not']['name'], './uploads/advImages', 'soc_not');
				$soc_notUploadName = $soc_notUpload['file_name'];
			}else{
				$soc_notUploadName = $societyOld;
			}
		}else{
			$soc_notUploadName = "";
		}

		if($noc == '2'){
			if($_FILES['upload_noc']['name'] != ''){
				$nocUpload = $this->uploadFiles($_FILES['upload_noc']['name'], './uploads/advImages', 'upload_noc');
				$nocUploadName = $nocUpload['file_name'];
			}else{
				$nocUploadName = $nocOld;
			}
		}else{
			$nocUploadName = "";
		}

		if($_FILES['aadhar']['name'] != ''){
			$aadhar = $this->uploadFiles($_FILES['aadhar']['name'], './uploads/advImages', 'aadhar');
			$aadharName = $aadhar['file_name'];
		}else{
			$aadharName = $aadharOld;
		}

		if($_FILES['pancard']['name'] != ''){
			$pan = $this->uploadFiles($_FILES['pancard']['name'], './uploads/advImages', 'pancard');
			$panName = $pan['file_name'];
		}else{
			$panName = $panold;
		}

		$updateArray = array(
			'form_no' => $form_no,
			'name' => $name,
			'address' => $address,
			'hoarding_location_address' => $hoarding_address,
			'type_of_adv' => $adv_type,
			'illuminate' => $illuminate,
			'hoarding_length' => $hoarding_length,
			'hoarding_breadth' => $hoarding_breadth,
			'height_of_road' => $road_height,
			'serchena' => $serchana,
			'type_of_request' => $req_type,
			'comp_address1' => $comp_add1,
			'comp_address2' => $comp_add2,
			'pancard' => $panName,
			'aadhar_card' => $aadharName,
			'society_notice_status' => $society_notice,
			'society_notice' => $soc_notUploadName,
			'owner_hoarding_name' => $owner_hoarding_name,
			'owner_hoarding_address' => $owner_hoarding_add,
			'owner_noc_status' => $noc,
			'owner_noc' => $nocUploadName,
			'hoarding_location' => $hoarding_loc,
			'adv_start_date' => date("Y-m-d",strtotime($start_date)),
			'no_adv_days' => $no_of_days,
			'end_date' => date("Y-m-d",strtotime($end_date)),
			'rate' => $rate,
			'amount' => $amount,
			'application_date' => date("Y-m-d H:i:s")
		);

		// echo "<pre>";print_r($updateArray);exit;

		$res = $this->Advertisement_table->updateAdv($updateArray, $adv_id);

		if($res){
			$data['success'] = 1;
			$data['msg'] = "Edited Successfully";
		}else{
			$data['success'] = 2;
			$data['msg'] = "Failed To Edit";
		}

		echo json_encode($data);
	}

	//adv_type
	public function adv_index()
	{
		$this->load->view('master/adv_type/index');
	}

	public function getadvtype()
	{
		$advType = $this->Advertisement_table->getAdvType();

		// echo "<pre>";print_r($advType);exit;

		$data = array();
 		$i = $_POST['start'];
 		foreach ($advType as $kadv => $vadv) {
 			$i++;

 			if($vadv['status'] == '1'){
 				$statusBtn = "<span class = 'btn btn-success btn-small advStatus' data-id = '".$vadv['adv_id']."' data-status = '1' style = 'cursor: pointer'>Active</span>";
 				$editBtn = '<a href="'.base_url().'advertisement/edits/'.base64_encode($vadv['adv_id']).'" style = "font-size: 25px;"><i class="fas fa-edit"></i></a>';
 			}else{
 				$statusBtn = "<span class = 'btn btn-danger btn-small advStatus' data-id = '".$vadv['adv_id']."' data-status = '2' style = 'cursor: pointer'>InActive</span>";
 				$editBtn = "";
 			}

 			$action = $editBtn;

 			$data[] = array($i, $vadv['name'], $statusBtn, $action);
 		}

 		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Advertisement_table->countAll(),
            "recordsFiltered" => $this->Advertisement_table->countFiltered1($_POST),
            "data" => $data,
        );
 		echo json_encode($output);

	}

	public function deactivateAdv()
	{
		$advId = $this->input->post("adv_type_Id");
		$status = $this->input->post("actualStatus");

		$result = $this->Advertisement_table->deactivateAdv($advId, $status);

		if($result){
 			$data['success'] = 1;
 			if($status == '1'){
 				$data['messg'] = "Deactivated Successfully";
 			}else{
 				$data['messg'] = "Activated Successfully";
 			}
 		}else{
 			$data['success'] = 2;
 		}

 		echo json_encode($data);
	}

	public function crAdvType()
	{
		$this->load->view('master/adv_type/create');
	}

	public function advSubmit()
	{
		extract($_POST);

		$insertArray = array(
			'name' => $advName,
			'date_added' => date("Y-m-d H:i:s")
		);

		$res = $this->Advertisement_table->insertAdv($insertArray);

		if($res > 0){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}


	public function advEdit()
	{
		$app_id = base64_decode($this->uri->segment(3));
		$data['adv_data'] = $this->Advertisement_table->getAdvTypeById($app_id);
		$this->load->view('master/adv_type/edit', $data);
	}

	public function advEditSubmit()
	{
		extract($_POST);

		$upArray = array(
			'name' => $advNameEdit
		);

		$res = $this->Advertisement_table->editData($upArray, $adv_id);

		if($res){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}
	//End adv_type

	//illuminate

	public function illuminate_index()
	{
		$this->load->view('master/illuminate/index');
	}

	public function getilluminate()
	{
		$illuminate = $this->Advertisement_table->getIlluminate();

		// echo "<pre>";print_r($advType);exit;

		$data = array();
 		$i = $_POST['start'];
 		foreach ($illuminate as $kill => $vill) {
 			$i++;

 			if($vill['status'] == '1'){
 				$statusBtn = "<span class = 'btn btn-success btn-small illStatus' data-id = '".$vill['ill_id']."' data-status = '1' style = 'cursor: pointer'>Active</span>";
 				$editBtn = '<a href="'.base_url().'advertisement/editss/'.base64_encode($vill['ill_id']).'" style = "font-size: 25px;"><i class="fas fa-edit"></i></a>';
 			}else{
 				$statusBtn = "<span class = 'btn btn-danger btn-small illStatus' data-id = '".$vill['ill_id']."' data-status = '2' style = 'cursor: pointer'>InActive</span>";
 				$editBtn = "";
 			}

 			$action = $editBtn;

 			$data[] = array($i, $vill['name'], $statusBtn, $action);
 		}

 		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Advertisement_table->countAllill(),
            "recordsFiltered" => $this->Advertisement_table->countFilteredill($_POST),
            "data" => $data,
        );
 		echo json_encode($output);	
	}

	public function deactivateill()
	{
		$illId = $this->input->post("ill_Id");
		$status = $this->input->post("actualStatus");

		$result = $this->Advertisement_table->deactivateill($illId, $status);

		if($result){
 			$data['success'] = 1;
 			if($status == '1'){
 				$data['messg'] = "Deactivated Successfully";
 			}else{
 				$data['messg'] = "Activated SuccessFully";
 			}
 		}else{
 			$data['success'] = 2;
 		}

 		echo json_encode($data);
	}

	public function crIlluminate()
	{
		$this->load->view('master/illuminate/create');
	}

	public function illSubmit(){
		extract($_POST);

		$insArray = array(
			'name' => $illName,
			'date_added' => date('Y-m-d H:i:s')
		);

		$res = $this->Advertisement_table->illInsert($insArray);

		if($res > 0){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}

	public function editIll(){
		$ill_id = base64_decode($this->uri->segment(3));
		$data['ill_data'] = $this->Advertisement_table->getillById($ill_id);
		$this->load->view('master/illuminate/edit', $data);
	}

	public function illEditSubmit(){
		extract($_POST);

		$upArray = array(
			'name' => $illNameEdit
		);

		$res = $this->Advertisement_table->editIll($upArray, $ill_id);

		if($res){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);
	}
	//End illuminate
}