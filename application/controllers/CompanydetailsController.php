<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . 'controllers/Common.php';
	
	error_reporting(0);
	
	class CompanydetailsController extends Common{

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

		public function index(){
			$this->load->view("master/company_details/index");
		}

		public function getData(){
			$data = $row = array();
			$companyList = $this->Add_company_details->getRows($_POST);
			$i = $_POST['start'];
				
			
			foreach($companyList as $company){
				$i++;
				$val = ($company['status'] == 1) ? 'Active' : 'In active';
				$class = ($company['status'] == 1)? 'btn-success' : 'btn-danger';
				$status ='<a type="button" data-companyid = "'.$company['company_id'].'" data-companystatus = "'.$company['status'].'" class="white btn btn-block statusBtn '.$class.'">'.$val.'</a>';
				$action = ($company['status'] == 1) ? '<a aria-label = "Edit" data-microtip-position=\'top\' role="tooltip" href="'.base_url().'company_details/edit/'.base64_encode($company['company_id']).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i></a>&nbsp&nbsp&nbsp&nbsp<span aria-label = "Address List" data-companyid = "'.$company['company_id'].'" class = "addresslist" data-microtip-position=\'top\' role="tooltip" style = "cursor:pointer;"><i class="nav-icon fas fa-list"></i></span>' : '';
				$data[] = array($i, $company['company_name'], $company['date_added'], $status, $action);
			}

			$output = array(
				"draw" => $_POST['draw'],
				"recordsTotal" => $this->Add_company_details->countAll(),
				"recordsFiltered" => $this->Add_company_details->countFiltered($_POST),
				"data" => $data,
			);
			
			// Output to JSON format
			echo json_encode($output);
		}

		public function create(){
			$this->load->view("master/company_details/create");
		}

		public function save(){
			//add company details
			extract($_POST);
			$company_check = $this->form_validation
                        ->set_rules('company_name','company_name','required|is_unique[company_details.company_name]')->run();
			if(!$company_check){

				//already present
				$data['success'] = '2';
            	$data['message'] = "Company Name Already Exists";
			}else{
				//not present
				$res = $this->Add_company_details->insert($this->security->xss_clean($company_name), $this->security->xss_clean($company_address));
				if($res){
					$data['success'] = 1;
					$data['message'] = "Company Added Successfully";
				}else{
					$data['success'] = 2;
					$data['message'] = "Error Occured Please Try Again";
				}
			}
			echo json_encode($data);
		}

		public function edit(){
			$companyId = base64_decode($this->uri->segment(3));
			$company_details = $this->Add_company_details->getCompanyDetails($companyId);
			
			$this->load->view("master/company_details/edit", $company_details);
		}

		public function EditAddress(){
			$addressId = $this->input->post("address_id");
			$address = $this->input->post("address");
			$companyId = $this->input->post("company_id");

			$res = $this->Add_company_details->editAddress($addressId,$address,$companyId);

			if($res){
				$data['success'] = 1;
				$data['message'] = "Address Updated Successfully";
			}else{
				$data['success'] = 2;
				$data['message'] = "Error Occured";
			}
			echo json_encode($data);
		}

		//delete
		public function deactivate(){
			$companyId = $this->input->post("company_id");
			$companyStatus = $this->input->post("status");

			if($companyStatus == '1'){
				//delete
				$res = $this->Add_company_details->deactivate($companyId, $companyStatus);
				if($res){
					$response['success'] = 1;
					$response['message'] = "Company Deactivated Successfully";
				}else{
					$response['success'] = 2;
					$response['message'] = "Error Occured";
				}
			}else{
				//active
				$res = $this->Add_company_details->deactivate($companyId, $companyStatus);
				
				if($res){
					$response['success'] = 1;
					$response['message'] = "Company Activation Successfull";
				}else{
					$response['success'] = 2;
					$response['message'] = "Error Occured Please Try Again";
				}
			}
			echo json_encode($response);
		}

		public function addressList(){

			$companyId = $this->input->post("company_id");

			$res = $this->Add_company_details->getAddressList($companyId);

			if(!empty($res)){
				$data['success'] = 1;
				$data['res'] = $res;
			}else{
				$data['success'] = 2;
				$data['res'] = "No Data Found";
			}
			echo json_encode($data);
		}

		public function deleteAddress(){
			$addressId = $this->input->post("address_id");
			$res = $this->Add_company_details->deleteAddress($addressId);
			
			if($res){
				$data['success'] = 1;
				$data['message'] = "Address Deleted Successfully";
			}else{
				$data['success'] = 2;
				$data['message'] = "Error Occured";
			}
			echo json_encode($data);
		}
	}
?>	