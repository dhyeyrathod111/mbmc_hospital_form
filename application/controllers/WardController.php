<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
require APPPATH . 'controllers/Common.php';

class wardController extends Common
{
	protected $response ;

    protected $data ;

	public function __construct()
	{
		parent::__construct();$this->data = array();$this->response = array();
	}
	public function index()
	{
		$this->load->view('master/ward/index');
	}
	public function create()
	{
		$this->data['departments'] = $this->wardmodel->get_all_active_departments();
		$this->load->view('master/ward/create',$this->data);
	}
	public function get_roles_by_dept()
	{
		$department_id = $this->security->xss_clean($this->input->post('department_id'));
		$roles_list = $this->wardmodel->getRolesByDeptID($department_id);
		if (count($roles_list) != 0) {
			$this->response['status'] = TRUE;
			$this->response['roles_list'] = $roles_list;
		} else {
			$this->response['status'] = FALSE;
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function create_ward_process()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->form_validation->set_rules('department_id', 'Department', 'required|integer|trim');
			$this->form_validation->set_rules('role_id', 'Role', 'integer|trim');
			$this->form_validation->set_rules('status', 'Status', 'required|integer|trim');
			$this->form_validation->set_rules('ward_title', 'ward title', 'required|alpha_numeric_spaces|trim');
			if ($this->form_validation->run()) {
				$insert_ward_stack = array(
					'dept_id' => $this->security->xss_clean($this->input->post('department_id')),
					'role_id' => $this->security->xss_clean($this->input->post('role_id')),
					'ward_title' => $this->security->xss_clean($this->input->post('ward_title')),
					'status' => $this->security->xss_clean($this->input->post('status')),
				);
				if ($this->wardmodel->createWardModel($insert_ward_stack)) {
					$this->response['status'] = TRUE;
					$this->response['message'] = "ward has been created successfully.";
				} else {
					$this->response['status'] = FALSE;
					$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
				}
			} else {
				$this->response['status'] = FALSE;
				$this->response['message'] = strip_tags(validation_errors());	
			}
			return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
		} else {
			redirect('ward','GET',301);
		}
	}
	public function ward_datatable()
	{
		$start = $this->input->post('start');
        $length = $this->input->post('length');
		$search_value = $this->input->post('search')['value'];
		
		if (isset($this->input->post('order')[0])) {
            $order = $this->input->post('order')[0];
        } else {
            $order = '';
        }
		$ward_data = $this->wardmodel->getAllWardData(FALSE,$search_value,$order,$length,$start);
		$ward_data_count = $this->wardmodel->getAllWardData(TRUE,$search_value,$order);
		$data_table_array = array();
		foreach ($ward_data as $key => $oneWard) {
			$temp_array = array();
			$temp_array[] = $key + 1;
			$temp_array[] = $oneWard->ward_id;
			$temp_array[] = $oneWard->dept_title;
			$temp_array[] = !empty($oneWard->role_title) ? $oneWard->role_title : 'All authority';
			$temp_array[] = $oneWard->ward_title;
			$temp_array[] = ($oneWard->status == 1) ? "Active" : "Deactivate" ;
			$temp_array[] = $oneWard->created_at;
			$temp_array[] = '<a class="btn btn-warning" href="'. base_url('ward/edit/'.base64_encode($oneWard->ward_id)).'">Edit</a> <a  word_id="'.base64_encode($oneWard->ward_id).'" class="btn btn-danger delete_word">Delete</a>';
			array_push($data_table_array,$temp_array);
		}
		$dataTable_response = [
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $ward_data,
            "recordsFiltered"=> $ward_data_count,
            "data"=> $data_table_array
        ];
        echo json_encode($dataTable_response);
	}
	public function edit_ward_from()
	{
		if (!$this->form_validation->is_unique(base64_decode($this->uri->segment(3)),'ward.ward_id')) {
			$ward_id = $this->security->xss_clean( base64_decode($this->uri->segment(3)) );
			$this->data['ward'] = $this->wardmodel->getWardByID($ward_id);
			$this->data['roles'] = $this->wardmodel->getRolesByDeptID($this->data['ward']->dept_id);
			$this->data['departments'] = $this->wardmodel->get_all_active_departments();
			$this->load->view('master/ward/edit',$this->data);
		} else {
			redirect('ward','location',301);
		}
	}
	public function update_ward_process()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$this->form_validation->set_rules('department_id', 'Department', 'required|integer|trim');
			$this->form_validation->set_rules('role_id', 'Role', 'integer|trim');
			$this->form_validation->set_rules('status', 'Status', 'required|integer|trim');
			$this->form_validation->set_rules('ward_title', 'ward title', 'required|alpha_numeric_spaces|trim');
			if ($this->form_validation->run()) {
				$update_ward_stack = array(
					'dept_id' => $this->security->xss_clean($this->input->post('department_id')),
					'role_id' => $this->security->xss_clean($this->input->post('role_id')),
					'ward_title' => $this->security->xss_clean($this->input->post('ward_title')),
					'status' => $this->security->xss_clean($this->input->post('status')),
				);
				$ward_id = $this->security->xss_clean( base64_decode($this->input->post('ward_id')) );
				if ($this->wardmodel->updateWardModel($update_ward_stack,$ward_id)) {
					$this->response['status'] = TRUE;
					$this->response['message'] = "ward has been Updated successfully.";
				} else {
					$this->response['status'] = FALSE;
					$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
				}
			} else {
				$this->response['status'] = FALSE;
				$this->response['message'] = strip_tags(validation_errors());	
			}
			return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
		} else {
			redirect('ward','GET',301);
		}
	}
	public function delete_ward_process()
	{
		if (!$this->form_validation->is_unique(base64_decode($this->input->post('ward_id')),'ward.ward_id')) {
			$ward_id = $this->security->xss_clean(base64_decode( $this->input->post('ward_id') ));
			if ($this->wardmodel->updateWardModel(['is_deleted' => 1],$ward_id)) {
				$this->response['status'] = TRUE;
				$this->response['message'] = "Ward has been deleted successfully.";
			} else {
				$this->response['status'] = FALSE;
				$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
			}
			return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
		} else {
			redirect('ward','location',301);
		}
	}
	public function getAllWardActive()
	{
		$AllWardData = $this->wardmodel->getAllActiveWard();
		if (count($AllWardData) > 0) {
			$this->response['status'] = TRUE;
			$this->response['wardData'] = $AllWardData;
		} else {
			$this->response['status'] = FALSE;
			$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
}