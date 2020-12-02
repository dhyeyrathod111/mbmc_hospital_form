<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class RolesController extends Common {

	/**
 	* RolesController.php
 	* @author Vikas Pandey
 	*/


	public function index() {
		
		$this->load->view("settings/roles/index");
	}

    public function create() {
        $this->load->view('settings/roles/create');
    }

    public function edit() {

        $role_id = base64_decode($this->uri->segment(3));

        $result = $this->roles_table->getroleById   ($role_id);

        $data['role'] = $result;

        $this->load->view('settings/roles/edit',$data);
    }

    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        if($role_id =='') {
            $role_title = $this->form_validation
                ->set_rules('role_title','role_title','required|is_unique[roles_table.role_title]')->run();
        } else {
            $role_title = $this->form_validation
                ->set_rules('role_title','role_title','required')->run();
        }
        
        $data['messg'] = '';

        if(!$role_title) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            if($role_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);
				
                $result = $this->roles_table->insert($data);
                $messg = 'added';
            } else {

                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                $data = array_merge($_POST,$extra);
                // echo'<pre>';print_r($data);exit;
                $result = $this->roles_table->update($data,$role_id);
                $messg = 'updated';
            }
            
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['role_title'].' '.$messg.' successfully.';
            } else {
                $data['status'] = '2';
                $data['messg'] = 'Oops! Something went wrong.';
            }
        }
        // echo'<pre>';print_r($data);exit;
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
        // echo'<pre>';print_r($update);exit;
        $result = $this->roles_table->update($update,$role_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'Role updated successfully.';
        } else {
            $messg = 'Oops! Something went wrong.';
            $data['status'] = '2';
            $data['messg'] = $messg;
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

	public function get_lists()	{

		$data = $row = array();

        // Fetch member's records
        $rolesList = $this->roles_table->getRows($_POST);
        // echo'<pre>';print_r($role_data);exit;
        $i = $_POST['start'];

        foreach($rolesList as $roles){
            $i++;
            $id = $roles['role_id'];
            $title = $roles['role_title'];
            $val = ($roles['status'] == 1)? 'Active' : 'In active';
            $class = ($roles['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$roles['status'].')" class="white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'role/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $id, $title, $status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->roles_table->countAll(),
            "recordsFiltered" => $this->roles_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}
    public function vlidate_rolename_create()
    {
        $postStack = $this->input->post();
        if ($postStack['role_id'] == '') {
            if ($this->form_validation->is_unique($this->input->post('role_title'),'roles_table.role_title')) {
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            $roleStack = $this->roles_table->getroleById($postStack['role_id']);
            if ($roleStack['role_id'] == $postStack['role_id']) {
                echo 'true';
            } else {
                if ($this->form_validation->is_unique($this->input->post('role_title'),'roles_table.role_title')) {
                    echo 'true';
                } else {
                    echo 'false';
                }
            }
        }
    }
    public function validate_rolename_edit()
    {
        
    }
}
