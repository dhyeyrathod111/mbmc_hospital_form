<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . 'controllers/Common.php';
	class DefectliabController extends Common{
		public $user_id;
		public $dept_id;
		public $role_id;

		public function __construct(){
			parent::__construct();
			$session_userdata = $this->session->userdata('user_session');
			
			$this->user_id = $session_userdata[0]['user_id'];
			$this->role_id = $session_userdata[0]['role_id'];
			$this->dept_id = $session_userdata[0]['dept_id'];
		}

		public function index() {
			$this->load->view('master/defect_laiblity/index');
		}

		public function getlist(){
			$data = $row = array();
			$liabList = $this->defectliab_table->getRows($_POST);

			$i = $_POST['start'];

			foreach($liabList as $liab){
				$i++;
				$val = ($liab['status'] == 1) ? 'Active' : 'In active';
				$class = ($liab['status'] == 1)? 'btn-success' : 'btn-danger';
				$status ='<a type="button" data-laibid = "'.$liab['laib_id'].'" data-laibstatus = "'.$liab['status'].'" class="white btn btn-block statusBtn '.$class.'">'.$val.'</a>';

				$action = ($liab['status'] == 1) ? '<a href="'.base_url().'defect_liab/edit/'.base64_encode($liab['laib_id']).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
						</a>' : '';
						
				$data[] = array($i, $liab['laib_name'], $liab['mul_factor'], $liab['date_from'], $liab['date_till'], $liab['date_added'], $status, $action);
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->defectliab_table->countAll(),
				"recordsFiltered" => $this->defectliab_table->countFiltered($_POST),
				"data" => $data,
			);
			
			// Output to JSON format
			echo json_encode($output);
		}

		public function create(){
			$this->load->view('master/defect_laiblity/create');
		}

		public function edit(){
			$liab_id = base64_decode($this->uri->segment(3));
			$result = $this->defectliab_table->getLiabDetailsById($liab_id);
			$data['defectliab'] = $result;
// 			echo $this->db->last_query();exit;
// 			echo "<pre>";print_r($data);exit;
			$this->load->view('master/defect_laiblity/edit', $data);
		}

		public function save(){
			extract($_POST);
			if(!empty($liab_id)){
				//edit
				$updateArray = array(
					'laib_name' => $this->security->xss_clean($liab_name),
					'user_id' => $this->security->xss_clean($this->user_id),
					'mul_factor' => $this->security->xss_clean($mul_factor),
					'date_from' => $this->security->xss_clean($date_from),
					'date_till' => $this->security->xss_clean($date_till)
				);

				$res = $this->defectliab_table->update($updateArray, $liab_id);

				if($res){
					$response['success'] = 1;
					$response['message'] = "Liablity Period Updated Successfully";
				}else{
					$response['success'] = 2;
					$response['message'] = "Error Occured";
				}

			}else{
				//insert
				$data = array(
					'laib_name' => $this->security->xss_clean($liab_name),
					'user_id' => $this->security->xss_clean($this->user_id),
					'mul_factor' => $this->security->xss_clean($mul_factor),
					'date_from' => $this->security->xss_clean($date_from),
					'date_till' => $this->security->xss_clean($date_till)
				);
				
				$res = $this->defectliab_table->insert($data);
				
				if($res){
					$response['success'] = 1;
					$response['message'] = "Liablity Period Added Successfully";
				}else{
					$response['success'] = 2;
					$response['message'] = "Error Occured";
				}
			}

			echo json_encode($response);
		}

		public function deactivate(){
			$laibId = $this->security->xss_clean($this->input->post("laib_id"));
			$laibStatus = $this->security->xss_clean($this->input->post("status"));

			if($laibStatus == '1'){
				//delete
				$res = $this->defectliab_table->deactivate($laibId, $laibStatus);
				if($res){
					$response['success'] = 1;
					$response['message'] = "Laiblity Period Deactivated For New Applications";
				}else{
					$response['success'] = 2;
					$response['message'] = "Error Occured";
				}
			}else{
				//active
				$res = $this->defectliab_table->deactivate($laibId, $laibStatus);
				
				if($res){
					$response['success'] = 1;
					$response['message'] = "Laiblity Period Activated For New Applications";
				}else{
					$response['success'] = 2;
					$response['message'] = "Error Occured Please Try Again";
				}
			}

			echo json_encode($response);
		}
	}
?>
