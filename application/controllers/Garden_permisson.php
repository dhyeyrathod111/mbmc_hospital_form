<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * @author Dhyey Rathod
 */
require APPPATH . 'controllers/Common.php';

class Garden_permisson extends Common
{
	protected $response ;

	protected $data;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Gardenpermisson_master','gardenpermisson');
		$this->response = array();$this->data = array();
	}
	public function index()
	{
		$this->load->view('master/gardenpermisson/index');
	}
	public function create_new_permission()
	{
		$this->load->view('master/gardenpermisson/create');
	}
	public function process_add_permission()
	{
		$this->form_validation->set_rules('permition_type', 'Username', 'required|alpha_numeric_spaces|is_unique[garden_permission.permission_type]');
		if($this->form_validation->run()){
			$input_stack = array(
				'permission_type' => $this->security->xss_clean($this->input->post('permition_type')),
				'is_blueprint' => !empty($this->input->post('blueprint')) ? TRUE : FALSE ,
			);
			if ($this->gardenpermisson->setFardenPermission($input_stack)) {
				$this->response['status'] = TRUE;    
                $this->response['message'] = "Garden permisson created successfully.";
			} else {
				$this->response['status'] = FALSE;    
                $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
			}
		} else {
			$this->response['status'] = FALSE;    
            $this->response['message'] = strip_tags(validation_errors());
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function garden_permission_add_validation()
	{
		if ($this->form_validation->is_unique($this->input->post('permition_type'),'garden_permission.permission_type')) {
            echo 'true';
        } else {
            echo 'false';
        }
	}
	public function get_garden_permission_list()
	{
		$start = $this->input->post('start');
        $length = $this->input->post('length');
        $search_value = $this->input->post('search')['value'];

        if (isset($this->input->post('order')[0])) {
            $order = $this->input->post('order')[0];
        } else {
            $order = '';
        }

        $gardenpermisson_list = $this->gardenpermisson->get_datatable_for_gardenpermission(FALSE,$search_value,$order,$length,$start);

        $gardenpermisson_count = $this->gardenpermisson->get_datatable_for_gardenpermission(TRUE,$search_value,$order);

        $data_table_array = array();

        foreach ($gardenpermisson_list as $key => $gardenpermisson) {
        	$temp_array = array();
        	$temp_array[] = $key+1;
        	$temp_array[] = $gardenpermisson->permission_type;
        	$temp_array[] = $gardenpermisson->is_blueprint ? 'Enable' : 'Disabled';
        	$temp_array[] = $gardenpermisson->created_at;
        	if ($gardenpermisson->status) {
        		$temp_array[] = '<a href="javascript:void(0);" type="button" data_id="'.base64_encode($gardenpermisson->garper_id).'" class="btn btn-success white change_permission_stats">Active</a>';
        	} else {
        		$temp_array[] = '<a href="javascript:void(0);" type="button" data_id="'.base64_encode($gardenpermisson->garper_id).'" class="btn btn-danger white change_permission_stats">Inactive</a>';
        	}
        	$temp_array[] = '<a href="'.base_url('garden_permission/edit/'.base64_encode($gardenpermisson->garper_id)).'"><i class="fas fa-edit"></i></a>';
        	array_push($data_table_array,$temp_array);
        }
        
        $dataTable_response = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $gardenpermisson_list,
            "recordsFiltered"=> $gardenpermisson_count,
            "data"=> $data_table_array
        ];
        echo json_encode($dataTable_response);
	}
	public function update_permission_status()
	{
		$permission_id = $this->security->xss_clean(base64_decode($this->input->post('permission_id')));
		if (!empty($permission_id) && is_numeric($permission_id)) {
			$gardenpermisson = $this->gardenpermisson->get_garden_permission_data($permission_id);
			$update_stack = array('status' => $gardenpermisson->status ? FALSE : TRUE);
			if ($this->gardenpermisson->update_gardenpermisson($update_stack,$permission_id)) {
			 	$this->response['status'] = TRUE;    
                $this->response['message'] = "Garden permisson status updated successfully.";
			} else {
				$this->response['status'] = FALSE;    
            	$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";	
			}
		} else {
			$this->response['status'] = FALSE;    
            $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function edit_garden_permission()
	{
		$permission_id = $this->security->xss_clean(base64_decode( $this->uri->segment(3) ));
		$gardenpermisson = $this->gardenpermisson->get_garden_permission_data($permission_id);
		if ( is_numeric($permission_id) && !empty($gardenpermisson) && isset($gardenpermisson)) {
			$this->data['gardenpermisson'] = $gardenpermisson;
			$this->load->view('master/gardenpermisson/edit',$this->data);			
		} else {
			redirect('garden_permission');			
		}
	}
	public function process_edit_permission()
	{
		$this->form_validation->set_rules('permition_type', 'Username', 'required|alpha_numeric_spaces');
		$permission_id = $this->security->xss_clean(base64_decode( $this->input->post('permission_id') ));
		if (!empty($permission_id) && $this->form_validation->run() && is_numeric($permission_id)) {
			$update_stack = array(
				'permission_type' => $this->security->xss_clean($this->input->post('permition_type')),
				'is_blueprint' => !empty($this->input->post('blueprint')) ? TRUE : FALSE ,
			);
			if ($this->gardenpermisson->update_gardenpermisson($update_stack,$permission_id)) {
				$this->response['status'] = TRUE;    
                $this->response['message'] = "Garden permisson status updated successfully.";
			} else {
				$this->response['status'] = FALSE;    
            	$this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
			}
		} else {
			$this->response['status'] = FALSE;    
            $this->response['message'] = "Sorry, we have to face some technical issues please try again later.";
		}
		return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
	}
	public function gardenpermission_edit_validation()
	{
		$permission_id = $this->security->xss_clean(base64_decode($this->input->post('permisson_id')));
		$update_stack = array(
			'permission_type' => $this->security->xss_clean($this->input->post('permition_type'))
		);
		$gardenpermisson = $this->gardenpermisson->get_garden_permission_data($permission_id);
		if ($gardenpermisson->permission_type == $update_stack['permission_type']) {
			echo 'true';
		} else {
			if ($this->form_validation->is_unique($this->input->post('permition_type'),'garden_permission.permission_type')) {
	            echo 'true';
	        } else {
	            echo 'false';
	        }
		}
	}
}