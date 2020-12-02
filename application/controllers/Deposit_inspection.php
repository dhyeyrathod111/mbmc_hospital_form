<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
require APPPATH . 'controllers/Common.php';

class Deposit_inspection extends Common
{
	protected $data ;

	protected $response ;

	public function __construct()
	{
		parent::__construct();$this->data = array();$this->response = array();
		$this->load->model('DepositInspectionModel','deposit_inspection');
	}
	public function create()
	{	
		$this->data['departments'] = $this->deposit_inspection->get_all_active_departments();
		$this->load->view('master/depositinspection/create',$this->data);
	}
	public function depositinspection()
	{
		$this->data['departments'] = $this->deposit_inspection->get_all_active_departments();
		$this->load->view('master/depositinspection/index',$this->data);	
	}
	public function create_form_process()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$inspection_id = $this->security->xss_clean(base64_decode( $this->input->post('inspection_id') ));
			if (!empty($inspection_id) && is_numeric($inspection_id)) {
				$update_stack = array('status' =>2);
				$this->deposit_inspection->update_depositinspection($update_stack,$inspection_id);
			}
			$this->form_validation->set_rules('from_date', 'From date','required');
			$this->form_validation->set_rules('to_date', 'To date','required');
			$this->form_validation->set_rules('department_id', 'department','required|numeric');
			$this->form_validation->set_rules('inspection_fees', 'Inspection fees','required|numeric');
			$this->form_validation->set_rules('deposit_fees', 'Deposit fees','required|numeric');
			if ($this->form_validation->run()) {
				$input_stack = array(
					'dept_id' => $this->security->xss_clean($this->input->post('department_id')),
					'user_id' => $this->authorised_user['user_id'],
					'inspection_fee' => $this->security->xss_clean($this->input->post('inspection_fees')),
					'deposit' => $this->security->xss_clean($this->input->post('deposit_fees')),
					'from_date' => $this->security->xss_clean($this->input->post('from_date')),
					'to_date' => $this->security->xss_clean($this->input->post('to_date')),'status' => 1,
				);
				if ($this->deposit_inspection->store_depositinspection($input_stack)) {
					$this->response['status'] = TRUE;
					$this->response['message'] = "Fees created successfully.";
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
			redirect('depositinspection/create','GET',301);
		}
	}
	public function depositinspection_datatable()
	{
		$start = $this->input->post('start');
        $length = $this->input->post('length');
        $search_value = $this->input->post('search')['value'];
        if (isset($this->input->post('order')[0])) {
            $order = $this->input->post('order')[0];
        } else {
            $order = '';
        }
        $filters = array(
        	'filter_department_id' => $this->security->xss_clean($this->input->post('filter_department_id')),
        	'filter_version' => $this->security->xss_clean($this->input->post('filter_version')),
        );
		$depositinspection = $this->deposit_inspection->depositinspection(FALSE,$search_value,$order,$filters,$length,$start);
		$depositinspection_count = $this->deposit_inspection->depositinspection(TRUE,$search_value,$order,$filters);
		$data_table_array = array();
		foreach ($depositinspection as $key => $oneDepInsp) :
			$temp_array = array();
			$temp_array[] = $key + 1;
			$temp_array[] = $oneDepInsp->dept_title;
			$temp_array[] = $oneDepInsp->user_name;
			$temp_array[] = $oneDepInsp->inspection_fee;
			$temp_array[] = $oneDepInsp->deposit;
			$temp_array[] = $oneDepInsp->from_date;
			$temp_array[] = $oneDepInsp->to_date;
			$temp_array[] = '<a href="'.base_url('depositinspection/edit/'.base64_encode($oneDepInsp->id)).'" class="btn btn-primary">Edit</a>';
			array_push($data_table_array,$temp_array);
		endforeach ;
		$dataTable_response = [
            "draw" => intval($_POST["draw"]),
            "recordsTotal" => $depositinspection,
            "recordsFiltered"=> $depositinspection_count,
            "data"=> $data_table_array
        ];
        echo json_encode($dataTable_response);
	}
	public function edit()
	{
		$inspection = $this->security->xss_clean(base64_decode($this->uri->segment(3)));
		$check_insection_id_exist = !$this->form_validation->is_unique($inspection,'deposit_inspection_fees.id');
		if ($check_insection_id_exist) {
			$this->data['insection'] = $this->deposit_inspection->depositinspection_by_id($inspection);
			$this->data['departments'] = $this->deposit_inspection->get_all_active_departments();
			$this->load->view('master/depositinspection/edit',$this->data);
		} else {
			redirect('depositinspection','GET',301);
		}
	}
}