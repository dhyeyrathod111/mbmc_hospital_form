<?php

/*
Ankit Naik
Tree Cutting Section
*/
  
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class FilmController extends Common {
	function index(){

		$session_userdata = $this->session->userdata('user_session');
			// print_r($session_userdata);exit;
		$role_id = $session_userdata[0]['role_id'];
		$dept_id = $session_userdata[0]['dept_id'];

		$data['appStatus'] = $this->App_status_level_table->getAllStatusByDept($dept_id);

		$this->load->view('applications/film/index',$data);
	}

	function getData(){
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
			$toDate = date("Y-m-d",strtotime($this->input->post("toDate")));
		}else{
			$toDate = "";
		}

		//approval
		$approval = $this->input->post("approval");

		//approvalStatus
		$approvalStatus = $this->input->post("approval_status");

		// echo $fromDate." - ".$toDate." - ".$approval." - ".$approvalStatus;exit;

		if($searchVal != ''){
			$appData = $this->FilmData_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
		}else{
			$appData = $this->FilmData_table->getAppData($searchVal, $fromDate, $toDate, $approval, $approvalStatus, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);	
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
				$statusRemarks = "Awaiting";
			}

			$delStatus = "";
			if($val['status'] == '1'){
				$delStatus = "<span aria-label = 'Delete' data-microtip-position='top' role='tooltip' for = 'delete' data-id = '".$val['film_id']."' data-act = '1' class = 'delete' style = 'cursor:pointer;color: blue;".$style."'><i class='fa fa-trash' aria-hidden='true'></i></span>";
				$editBtn = "<span>
 <a for = 'edit' aria-label = 'Edit' data-microtip-position='top' role='tooltip' class = 'edit' style = 'cursor: pointer;' href = '".base_url()."film/edit/".base64_encode($val['film_id'])."'>
  <i class='fa fa-edit' aria-hidden='true'></i>
 </a>
</span>";
			}else{
				$delStatus = "<span aria-label = 'Activate' data-microtip-position='top' role='tooltip' for = 'delete' data-id = '".$val['film_id']."' class = 'delete' data-act = '2' style = 'cursor:pointer;color:blue;".$style."'><i class='fa fa-key' aria-hidden='true'></i></span>";
				$editBtn = "";
			}

			$statusBtn = "<span class = 'btn btn-danger btn-sm approvalStatus' style = 'cursor:pointer;' data-id = '".$val['film_id']."'>".$statusRemarks."</span>";

			$actionBtn = $editBtn."<span aria-label = 'Remarks' data-microtip-position='top' role='tooltip' style = 'cursor: pointer;color: blue;' class = 'remarks' data-id = '".$val['film_id']."'>
	<i class='fa fa-list' aria-hidden='true'></i>	
</span>".$delStatus;
			
			$data[] = array('sr_no' => $i,'form_no' => $val['form_no'],'contact_person' => $val['contact_person'],'place_of_shooting' => $val['place_of_shooting'],'police_noc' => '<a href = "'.base_url().'uploads/filmImages/'.$val['police_noc'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>','aadhar' => '<a href = "'.base_url().'uploads/filmImages/'.$val['aadhar'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>','pan' => '<a href = "'.base_url().'uploads/filmImages/'.$val['pan'].'" download><i class="fa fa-download" aria-hidden="true"></i></a>','period_from' => $val['period_from'],'period_to' => $val['period_to'],'status' => $statusBtn,'action' => $actionBtn);
		}

		$output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => count($data),
            "recordsFiltered" => $this->FilmData_table->countFiltered($_POST),
            "data" => $data,
        );

		echo json_encode($output);
	}

	function create(){
		$data['appId'] = $this->applications_details_table->getLastId();
		$this->load->view('applications/film/create', $data);
	}

	//submit create
	function createFilmLic(){
		extract($_POST);

		//police noc upload
		if($_FILES['policeNoc']['name'] != ''){
			$policeData = $this->uploadFiles($_FILES['policeNoc']['name'], './uploads/filmImages', 'policeNoc');
			
		}else{
			$data['success'] = 2;
		}
		//aadhar upload
		if($_FILES['aadhar']['name'] != ''){
			$aadharData = $this->uploadFiles($_FILES['aadhar']['name'], './uploads/filmImages', 'aadhar');
			
		}else{
			$data['success'] = 2;
		}
		//pan upload
		if($_FILES['pan']['name'] != ''){
			$panData = $this->uploadFiles($_FILES['pan']['name'], './uploads/filmImages', 'pan');	
			
		}else{
			$data['success'] = 2;
		}
		
		if(!array_key_exists('error1', $policeData) && !array_key_exists('error1', $panData) && !array_key_exists('error1', $aadharData)){
			
			$insertArray = array(
				'form_no' => $form_no,
				'contact_person' => $cntctPersonName,
				'reason_for_lic' => $reasonForLicense,
				'place_of_shooting' => $shootingPlace,
				'period_from' => date('Y-m-d',strtotime($periodFrom)),
				'period_to' => date('Y-m-d',strtotime($periodTo)),
				'police_noc' => $policeData['file_name'],
				'noc_path' => $policeData['file_path'],
				'aadhar' => $aadharData['file_name'],
				'aadhar_path' => $aadharData['file_path'],
				'pan' => $panData['file_name'],
				'pan_path' => $panData['file_path'],
 				'date_added' => date("Y-m-d H:i:s")
			);

			$res = $this->FilmData_table->insert($insertArray);

			if($res > 0){
				$sessionData = $this->session->userdata('user_session');
				$role_id = $sessionData[0]['role_id'];
				$dept_id = $sessionData[0]['dept_id'];
				$dataStatus = array(
					'dept_id' => $dept_id,
					'status' => '1',
					'is_deleted' => 0,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s')
				);

				$this->applications_details_table->insert($dataStatus);

				$data['success'] = 1;
				$data['msg'] = "Created Succcessfully";
			}else{
				$data['success'] = 2;
				$data['msg'] = "Insertion Failed";
 			}

		}else{
			$data['success'] = 2;
			$data['msg'] = "Error 1 Exists";
		}

		echo json_encode($data);
	}

	function edit(){
		$film_id = base64_decode($this->uri->segment(3));

		$data['appData'] = $this->FilmData_table->appDataById($film_id);
		// print_r($data);
		$this->load->view('applications/film/edit', $data);
	}

	function editFilmLic(){
		extract($_POST);

		if($_FILES['policeNoc']['name'] != ''){
			$policeData = $this->uploadFiles($_FILES['policeNoc']['name'], './uploads/filmImages', 'policeNoc');
			$policeDataName = $policeData['file_name'];
		}else{
			$policeDataName = $oldNoc;	
		}

		if($_FILES['aadhar']['name'] != ''){
			$aadharData = $this->uploadFiles($_FILES['policeNoc']['name'], './uploads/filmImages', 'policeNoc');
			$aadharDataName = $aadharData['file_name'];
		}else{
			$aadharDataName = $oldAadhar;	
		}

		if($_FILES['pan']['name'] != ''){
			$panData = $this->uploadFiles($_FILES['policeNoc']['name'], './uploads/filmImages', 'policeNoc');
			$panDataName = $panData['file_name'];
		}else{
			$panDataName = $oldPan;	
		}

		$updateArray = array(
			'form_no' => $form_no,
			'contact_person' => $cntctPersonName,
			'reason_for_lic' => $reasonForLicense,
			'place_of_shooting' => $shootingPlace,
			'police_noc' => $policeDataName,
			'aadhar' => $aadharDataName,
			'pan' => $panDataName,
			'period_from' => date('Y-m-d',strtotime($periodFrom)),
			'period_to' => date('Y-m-d',strtotime($periodTo))
		);

		$this->FilmData_table->updateFilm($updateArray, $film_id);

		$data['success'] = 1;

		echo json_encode($data);
	}

	function delete(){
		$act = $this->input->post("act");
		$id = $this->input->post("id");

		$res = $this->FilmData_table->delete($act, $id);

		if($res){
			$data['success'] = 1;
		}else{
			$data['success'] = 2;
		}

		echo json_encode($data);exit;
	}

	function getRemarks(){
		$filmId = $this->input->post("filmId");

		$data['result'] = $this->application_remarks_table->getAllRemarksById($filmId);

		echo json_encode($data);
	}

	function getAppStatus(){
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