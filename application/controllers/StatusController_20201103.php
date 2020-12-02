<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class StatusController extends Common {

	/**
	 * 
	 *
	 *
	 */

	public function index() {
		$this->load->view('master/status/index');
	}

	public function create() {
		$data['roles'] = $this->get_roles();
        $data['department'] = $this->get_all_dept();
		$this->load->view('master/status/create',$data);
	}

	public function edit() {

		$status_id = base64_decode($this->uri->segment(3));
		$data['roles'] = $this->get_roles();
        $data['department'] = $this->get_all_dept();

		$result = $this->App_status_level_table->getAllStatusDetailsById($status_id);

		$data['status'] = $result;

		$this->load->view('master/status/edit',$data);
	}

	public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $status_title = $this->form_validation
        	->set_rules('status_title','status_title','required')->run();

    	$data = array();
        if(!$status_title) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();

        } else {

        	if($status_id =='') {
        		$extra = array(
	                'status' => '1',
	                'created_at' => date('Y-m-d H:i:s'),
	                'updated_at' => date('Y-m-d H:i:s')
	            );

	            $data = array_merge($_POST,$extra);

	            $result = $this->App_status_level_table->insert($data);
	            $messg = 'added';

        	} else {
        		$extra = array(
	                'updated_at' => date('Y-m-d H:i:s')
	            );
                $data = array_merge($_POST,$extra);
        		// echo'<pre>';print_r($extra);exit;
	            $result = $this->App_status_level_table->update($data,$status_id);

	            $messg = 'updated';
        	}
            // echo'<pre>';var_dump($result);exit;
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['status_title'].' '.$messg.' successfully.';
            } else {
                $data['status'] = '2';
                $data['messg'] = 'Oops! Something went wrong.';
            }
        }

        echo json_encode($data);
    }

    public function update() {
        extract($_POST);
        if($status == '1') {
            $update = array(
                'status' => '2',
                'updated_at' => date('Y-m-d H:i:s')
            );
        } else {
            $update = array(
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s')
            );
        }

        $result = $this->App_status_level_table->update($update,$status_id);

        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'status updated successfully.';
        } else {
            $messg = 'Oops! Something went wrong.';
            $data['status'] = '2';
            $data['messg'] = $messg;
        }
        
        echo json_encode($data);
    }

	public function get_lists()	{

		$data = $row = array();

        $statusList = $this->App_status_level_table->getRows($_POST);

        $i = $_POST['start'];
        foreach($statusList as $sat){
            $i++;
            $id = $sat['status_id'];
            $status_title = $sat['status_title'];
            $dept_title = $this->department_table->getDepartmentById($sat['dept_id'])['dept_title'];
            $role_title = $this->roles_table->getroleById($sat['role_id'])['role_title'];
            $val = ($sat['status'] == 1)? 'Active' : 'In active';
            $class = ($sat['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$sat['status'].')" class="white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'status/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $id, $status_title, $dept_title, $role_title, $status, $action);
        }	

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->App_status_level_table->countAll(),
            "recordsFiltered" => $this->App_status_level_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}

	public function get_status_by_dept_role(){

		extract($_POST);
		$result = $this->App_status_level_table->getAllStatusByDeptRole($dept_id, $role_id);
		
        // echo'<pre>';print_r($result);exit;
		if($result != null) {
			$data['status'] = '1';
			$data['result'] = $result;
 		} else {
 			$data['status'] = '2';
			$data['result'] = null;
 		}
 		echo json_encode($data);
	}
}
	