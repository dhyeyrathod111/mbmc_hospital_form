<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class DepartmentsController extends Common {

	/**
 	* DepartmentsController.php
 	* @author Vikas Pandey
 	*/

	public function index() {
		
		$this->load->view("settings/department/index");
	}

    public function create() {

        $data['roles'] = $this->get_roles();
        $data['department'] = $this->get_all_dept();
        $this->load->view('settings/department/create',$data);
    }
    
    public function getRoles() {
		$roles = $this->get_roles();
		echo  json_encode($roles);
	}

    public function edit() {

        $dept_id = base64_decode($this->uri->segment(3));

        $result = $this->department_table->getDepartmentById($dept_id);
        
        $perData = $this->department_table->getPermissionData($dept_id);

        $data['dept'] = $result;
		$data['perData'] = $perData;
		$data['roles'] = $this->get_roles();
		
        $this->load->view('settings/department/edit',$data);
    }
    
    public function save() {
		extract($_POST);
		
		$payableArray = json_decode($payableArray);
		// print_r($_POST);print_r($payableArray);exit;
		// if($dept_id =='') {
        //     $dept_title = $this->form_validation
        //                     ->set_rules('dept_title','dept_title','required|is_unique[department_table.dept_title]')->run();
        // } else {
        //     $dept_title = $this->form_validation
        //                     ->set_rules('dept_title','dept_title','required')->run();  
		// }

		$data['messg'] = '';

		if(!$dept_title) {
            $data['status'] = '2';
            $data['messg'] = "Title Field Is Required";
            // exit;
        } else {

			if($dept_id =='') {
				$insertArray = array(
					'dept_title' => $dept_title,
					'department_mail_id' => $dept_email,
					'dept_desc' => $dept_desc,
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
				);
				
				// $data = array_merge($_POST,$extra);
				// print_r($data);exit;
				$result = $this->department_table->insert($insertArray);
				
				if($result['success'] == 1){
					//add role options
					foreach($role as $krole => $vrole){
						$perArray = array(
							'dept_id' => $result['last_id'],
							'role_id' => $vrole,
							'payable_status' => $payableArray[$krole]
						);

						$perRes[] = $this->department_table->insertPermission($perArray);
					}
					if(count($role) == count($perRes)){
						$status = 1;
						$messg = 'added';
					}else{
						$status = 2;
						$messg = 'Failed';
					}
					
				}else{
					$status = 2;
					$messg = 'Failed';
				}

				$returnCode['status'] = $status;
				$returnCode['message'] = $messg;
				echo json_encode($returnCode);
				
			}else{

				$insertArray = array(
					'dept_title' => $dept_title,
					'department_mail_id' => $dept_mail,
					'dept_desc' => $dept_desc,
                    'updated_at' => date('Y-m-d H:i:s')
				);
				
				// $data = array_merge($_POST,$extra);
				// print_r($role);
				// print_r($payableArray);exit;
				$result = $this->department_table->update($insertArray, $dept_id, $role, $payableArray);
				
				if($result){
					$dataEdit['status'] = 1;
					$dataEdit['message'] = 'Updated Successfully';
				}else{
					$dataEdit['status'] = 2;
					$dataEdit['message'] = 'Updation Failed';
				}

				echo json_encode($dataEdit);
			}

		}
		
	}

    public function save_old() {
        extract($_POST);
        if($dept_id =='') {
            $dept_title = $this->form_validation
                            ->set_rules('dept_title','dept_title','required|is_unique[department_table.dept_title]')->run();
        } else {
            $dept_title = $this->form_validation
                            ->set_rules('dept_title','dept_title','required')->run();  
        }
        
        $data['messg'] = '';
        // echo'<pre>';print_r($dept_title);exit;
        if(!$dept_title) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            if($dept_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);
                $result = $this->department_table->insert($data);
                $messg = 'added';
            } else {
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                $data = array_merge($_POST,$extra);
                $result = $this->department_table->update($data,$dept_id);
                $messg = 'updated';
            }
            
            // echo'<pre>';print_r($result);exit;
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['dept_title'].' '.$messg.' successfully.';
            } else {
                $messg = 'Oops! Something went wrong.';
                $data['status'] = '2';
                $data['messg'] = $messg;
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
        $result = $this->department_table->update($update,$dept_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'Department updated successfully.';
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
        $deptsList = $this->department_table->getRows($_POST);
        // echo'ss<pre>';print_r($deptsList);exit;
        // echo'<pre>';print_r($role_data);exit;
        $i = $_POST['start'];

        foreach($deptsList as $dept){
            $i++;
            $id = $dept['dept_id'];
            $title = ucwords($dept['dept_title']);
            $val = ($dept['status'] == 1)? 'Active' : 'In active';
            $class = ($dept['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="check_user_present('.$id.','.$dept['status'].')" class=" white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'dept/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $title, $dept['dept_desc'], $status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->department_table->countAll(),
            "recordsFiltered" => $this->department_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}
    public function validate_edit_dept()
    {
        $post_stack = array(
            'dept_id' => $this->security->xss_clean($this->input->post('dept_id')),
            'dept_title' => $this->security->xss_clean($this->input->post('dept_title')) 
        ); 
        $dept_stack = $this->department_table->getDepartmentById($post_stack['dept_id']);
        if ($dept_stack['dept_title'] == $post_stack['dept_title']) {
            echo 'true';
        } else {
            if ($this->form_validation->is_unique($post_stack['dept_title'],'department_table.dept_title')) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
    public function user_exist_in_dept()
    {
        try {
            $dept_id = $this->security->xss_clean($this->input->post('dept_id'));
            $status = $this->security->xss_clean($this->input->post('status'));
            $this->data['user_list'] = $this->department_table->get_user_by_dept($dept_id);
            $this->data['dept_list'] = $this->department_table->get_all_active_dept();
            if (count($this->data['user_list'])) {
                $this->response['status'] = TRUE;
                $this->response['html_str'] = $this->load->view('settings/department/user_exist_list',$this->data,TRUE);
            } else {
                $this->response['status'] = FALSE;
                $this->response['perms'] = ['dept_id'=>$dept_id,'status'=>$status];
            }   
        } catch (Exception $e) {
            $this->response['status'] = FALSE;
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($this->response));
    }
    public function change_user_dept()
    {
        $new_dept_id = $this->security->xss_clean($this->input->post('new_dept_id'));
        $user_id = $this->security->xss_clean($this->input->post('user_id'));
        if ($this->users_table->updateUserDept($user_id,$new_dept_id)) {
            $this->response['status'] = TRUE;
            $this->response['message'] = 'Department has been change successfully..!!';
            $this->response['user_id'] = $user_id;
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = 'Sorry, we have to face some technical issues please try again later.';            
        }
        return $this->output->set_content_type('application/json')->set_output(json_encode($this->response));
    }
}
