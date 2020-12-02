<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'controllers/Common.php';

class UsersController extends Common {

	/**
	 
	 */
	public function index() {
		$data['roles'] = $this->get_roles();
		$data['department'] = $this->get_all_dept();
		// echo'<pre>';print_r($data);exit;
 		$this->load->view("users/index",$data);
	}

	public function add() {
        $data['roles'] = $this->get_roles();
        $data['department'] = $this->get_all_dept();
        // /echo'ggg<pre>';print_r($data);exit;
        $this->load->view('users/add',$data);
    }

    public function edit() {
        $data['department'] = $this->get_all_dept();
    	$user_id = base64_decode($this->uri->segment(3));
    	$data['user'] = $this->users_table->getUserdetailsById($user_id);

        $data['roles'] = $this->wardmodel->getRolesByDeptID($data['user']['dept_id']);

        // echo "<pre>";print_r($data['roles']);exit();

        $condition_payload = array(
            'dept_id' => $data['user']['dept_id'],
            'role_id' => $data['user']['role_id'],
            'is_deleted' => 0
        );
        $data['user_ward'] = $this->users_table->getWordForUsers($condition_payload);


        $this->load->view('users/edit',$data);
    }

    public function save() {
        
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $username_check = $this->form_validation
                            ->set_rules('user_name','user_name','required')->run();
         $role_check = $this->form_validation
                    ->set_rules('role_id','role_id','required')->run();
        $dept_check = $this->form_validation
                    ->set_rules('dept_id','dept_id','required')->run();

        $pass_check = $this->form_validation
                    ->set_rules('user_mobile','user_mobile','required')->run();

        if($user_id != '') {
        	$email_check = $this->form_validation
                        ->set_rules('email_id','email_id','required|valid_email')->run();
            $mobile_check = $this->form_validation
                    ->set_rules('user_mobile','user_mobile','required|regex_match[/^[0-9]{10}$/]')->run();
        } else  {
        	$email_check = $this->form_validation
                        ->set_rules('email_id','email_id','required|valid_email|is_unique[users_table.email_id]')->run();
            $mobile_check = $this->form_validation
                    ->set_rules('user_mobile','user_mobile','required|regex_match[/^[0-9]{10}$/]|is_unique[users_table.user_mobile]')->run();
        }
        
        $data['messg'] = '';

        if(!$username_check || !$email_check || !$mobile_check || !$role_check || !$pass_check || !$dept_check) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            
            if($user_id !='') {
            	$extra = array(
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                // 'password' => $this->hash_password($password)
            	);
            	$data = array_merge($_POST,$extra);
            	unset($data['user_id']);
            	$result = $this->users_table->update($data,$user_id);
            	if($result == true) {
	                $data['status'] = '1';
	                $data['messg'] = 'User updated successfully.';
	            } else {
	                $messg = 'Oops! Something went wrong.';
	                $data['status'] = '2';
	                $data['messg'] = $messg;
	            }

            } else {
            	$extra = array(
                'status' => '1',
                'created_at' => date('Y-m-d H:i:s'),
                'password' => $this->hash_password($password));
                $data = array_merge($_POST,$extra);
            	$result = $this->users_table->insert($data);
            	if($result == true) {
	                $data['status'] = '1';
	                $data['messg'] = 'User Registered successfully.';
	            } else {
	                $messg = 'Oops! Something went wrong.';
	                $data['status'] = '2';
	                $data['messg'] = $messg;
	            }
            }
        }

        echo json_encode($data);
    }

    public function update() {
    	// echo'<pre>';print_r($_POST);exit;
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
        $result = $this->users_table->update($update,$user_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'Users updated successfully.';
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

        $usersList = $this->users_table->getRows($_POST);
        $i = $_POST['start'];
        // echo'<pre>';print_r($usersList);exit;
        foreach($usersList as $user) {
            $i++;
            $user_id = $user['user_id'];
            $user_name = $user['user_name'];
            $user_mobile = $user['user_mobile'];
            $email_id = $user['email_id'];
            $role_title = $user['role_title'];
            $dept_title = $user['dept_title'];

            $val = ($user['status'] == 1)? 'Active' : 'In active';
            $class = ($user['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" data-user="'.$user_id.'" data-status="'.$user['status'].'" onclick="changeStatus(this)" class="white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'users/edit/'.base64_encode($user_id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $user_id,$user_name,  $email_id,$user_mobile,$role_title, $dept_title, $status,$action );
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->users_table->countAll(),
            "recordsFiltered" => $this->users_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}
	
	//changes dhey
	public function edit_validate_user_email()
    {
        $post_stack = array(
            'email_id' => $this->security->xss_clean($this->input->post('email_id')),
            'user_id' => $this->security->xss_clean($this->input->post('user_id')) 
        ); 
        $user_info = $this->users_table->getUserdetailsById($post_stack['user_id']);
        if ($user_info['email_id'] == $post_stack['email_id']) {
            echo 'true';
        } else {
            if ($this->form_validation->is_unique($post_stack['email_id'],'users_table.email_id')) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
    
    public function edit_validate_contact()
    {
        $post_stack = array(
            'user_mobile' => $this->input->post('user_mobile'),
            'user_id' => $this->security->xss_clean($this->input->post('user_id')) 
        );
        $user_info = $this->users_table->getUserdetailsById($post_stack['user_id']);
        if($user_info['user_mobile'] == $post_stack['user_mobile']){
            echo 'true';
        } else {
            if ($this->form_validation->is_unique($post_stack['user_mobile'],'users_table.user_mobile')) {
                echo 'true';
            } else {
                echo 'false';
            }
        }
    }
	//End changes
	
	public function register_save(){

	    extract($_POST);

        if(empty($is_visitor)){
			$is_visitor = 0;
		}

	   	$status = 0;
        if (!empty($user_id) && is_numeric($user_id)) {
            $update_stack = array(
                'user_name' => $this->input->post('user_name'),
                'role_id' => $this->input->post('role_id'),
                'ward_id' => !empty($this->input->post('ward_id')) ? $this->input->post('ward_id') : 0,
                'email_id' => $this->input->post('email_id'),
                'user_mobile' => $this->input->post('user_mobile'),
                'dept_id' => $this->input->post('dept_id'),
                'updated_at' => date('Y-m-d H:i:s')
            );
            if ($this->users_table->update($update_stack,$user_id)) {
                $res = TRUE;$status = 1;
            } else {
                $res = FALSE;
            }
        } else {
            if(empty($role_id)){
               $insertArray = array(
                'role_id' => 0,
                'email_id' => $email_id,
                'user_name' => $user_name,
                'user_mobile' => $user_mobile,
                'dept_id' => 0,
                'is_visitor' => 0,
                'is_user' => 1,
                'ward_id' => (!empty($word_id)) ? trim($word_id) : 0,
                'termsCond' => $terms,
                'status' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
               ); 
            } else {
               $insertArray = array(
                'role_id' => $role_id,
                'email_id' => $email_id,
                'user_name' => $user_name,
                'user_mobile' => $user_mobile,
                'dept_id' => $dept_id,
                'is_visitor' => $is_visitor,
                'is_user' => 0,
                'ward_id' => (!empty($word_id)) ? trim($word_id) : 0,
                'termsCond' => 1,
                'status' => 1,
                'is_deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
               ); 
               $status = 1;
            }
            if (!empty($password)) {
                $insertArray['password'] = $this->hash_password($password);
            }

           $res = $this->users_table->register_save($insertArray);
        }

	   
	   if($res){
		   $data['success'] = 1;
		   $data['status'] = $status;
	   }else{
	       $data['success'] = 2;
	   }
	   
	   echo json_encode($data);
	}

	public function getRoleByDept(){
		//INSERT INTO `app_routes` (`id`, `dept_id`, `grp_index`, `slug`, `sub_slug`, `controller`, `method`, `short_desc`, `created_at`, `updated_at`, `status`, `is_deleted`) VALUES (NULL, '0', '0', 'getRoleByDept', 'users', 'UsersController', 'getRoleByDept', 'Get Roles By Dept', '2020-09-10 17:39:10', '2020-09-10 17:39:10', '1', '0');
		$dept_id = $this->input->post("dept_id");
		$roleData = $this->users_table->getRolesByDept($dept_id);

		if($roleData){
			$data['success'] = 1;
			$data['roles'] = $roleData;
		}else{
			$data['success'] = 2;
			$data['roles'] = '';
		}

		echo json_encode($data);
	}
    public function get_ward_by_dept_role()
    {
        $this->form_validation->set_rules('role_id', 'Role id', 'required|integer|trim');
        $this->form_validation->set_rules('dept_id', 'Department id', 'required|integer|trim');
        if ($this->form_validation->run()) {
            $condition_payload = array(
                'dept_id' => $this->security->xss_clean($this->input->post('dept_id')),
                'role_id' => $this->security->xss_clean($this->input->post('role_id')),
                'is_deleted' => 0
            );
            $word_data_stack = $this->users_table->getWordForUsers($condition_payload);
            if (count($word_data_stack) > 0) {
                $this->response['status'] = TRUE;
                $this->response['role_data'] = $word_data_stack ;
            } else {
                $this->response['status'] = FALSE;
                $this->response['message'] = "Word is not created for this role.Please create word first.";
            }
        } else {
            $this->response['status'] = FALSE;
            $this->response['message'] = strip_tags(validation_errors());
        }
        return $this->output->set_status_header(200)->set_content_type('application/json')->set_output(json_encode($this->response));
    }
}
