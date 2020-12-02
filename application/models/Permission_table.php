<?php
	Class Permission_table extends CI_Model{
		function __construct() {
	        // Set table name
			$this->table = 'users_table';
			
	    }

	    function getUserDeparment(){
	    	// $data = $this->db->select('*')
	    	// 				 ->from('department_table')
	    	// 				 ->where(array('status' => '1', 'is_deleted' => '0'))
	    	// 				 ->get()
			// 				 ->result_array();
			
			$data = $this->db->query("SELECT td.* FROM (SELECT dt.*, (SELECT GROUP_CONCAT(role_id) FROM permission_access WHERE dept_id = dt.dept_id AND status = '1') roles FROM `department_table` dt WHERE dt.status = '1' AND dt.is_deleted = '0') td WHERE (FIND_IN_SET('1',td.roles) = 0 OR td.roles IS NULL)")->result_array();
			
	    	if($data){
	    		return $data;
	    	}else{
	    		return false;
	    	}
		}
		
		function getRolesByDept($dept_id = null){
			if($dept_id != ''){
				$res = $this->db->query("SELECT pa.role_id, (SELECT role_title FROM roles_table WHERE role_id = pa.role_id) title FROM `permission_access` pa WHERE pa.dept_id = '".$dept_id."' AND pa.status = '1'")->result_array();
				if(!empty($res)){
					return $res;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

	    function getUserRoles(){
	    	$data = $this->db->select('*')
	    					 ->from('roles_table')
	    					 ->where(array('status' => '1', 'is_deleted' => '0'))
	    					 ->get()
	    					 ->result_array();

	    	if($data){
	    		return $data;
	    	}else{
	    		return false;
	    	}
	    }

	    function getUserData($role_id = null, $dept_id = null){
	    	$query = $this->db->query("SELECT * FROM `user_permissions` up WHERE up.role_id = '".$role_id."' AND up.dept_id = '".$dept_id."' AND up.status = '1' AND up.is_deleted = '0'")->result_array();

	    	if($query){
	    		return $query;
	    	}else{
	    		return false;
	    	}
	    }

	    function insertArray($permissionArray = null, $status = null, $role_id = null, $dept_id = null)
	    {
	    	switch ($status) {
	    		case 'index':
	    			//get route id
	    		// echo "SELECT id FROM `app_routes` WHERE dept_id = '$dept_id' AND grp_index = '1' AND status = '1'";
	    			$routeId = $this->db->query("SELECT id FROM `app_routes` WHERE dept_id = '$dept_id' AND grp_index = '1' AND status = '1'")->result_array();

	    			$this->db->where(array('user_id'=>'', 'role_id'=>$role_id, 'dept_id'=>$dept_id, 'route_id' => $routeId[0]['id']))
	    					 ->update('user_permissions', array('status'=>'2', 'is_deleted'=>'1'));
	    			$permissionArray['route_id'] = $routeId[0]['id'];		 

	    			$this->db->insert('user_permissions', $permissionArray);
	    			break;
	    		case 'create':
	    			$routeId = $this->db->query("SELECT id FROM `app_routes` WHERE dept_id = '$dept_id' AND grp_index = '2' AND status = '1'")->result_array();
	    			$this->db->where(array('user_id'=>'', 'role_id'=>$role_id, 'dept_id'=>$dept_id, 'route_id' => $routeId[0]['id']))
	    					 ->update('user_permissions', array('status'=>'2', 'is_deleted'=>'1'));
	    			$permissionArray['route_id'] = $routeId[0]['id'];		 

	    			$this->db->insert('user_permissions', $permissionArray);
	    			break;
	    		case 'edit':
	    			$routeId = $this->db->query("SELECT id FROM `app_routes` WHERE dept_id = '$dept_id' AND grp_index = '3' AND status = '1'")->result_array();
	    			$this->db->where(array('user_id'=>'', 'role_id'=>$role_id, 'dept_id'=>$dept_id, 'route_id' => $routeId[0]['id']))
	    					 ->update('user_permissions', array('status'=>'2', 'is_deleted'=>'1'));
	    			$permissionArray['route_id'] = $routeId[0]['id'];		 

	    			$this->db->insert('user_permissions', $permissionArray);
	    			break;
	    		case 'delete':
	    			$routeId = $this->db->query("SELECT id FROM `app_routes` WHERE dept_id = '$dept_id' AND grp_index = '4' AND status = '1'")->result_array();
	    			$this->db->where(array('user_id'=>'', 'role_id'=>$role_id, 'dept_id'=>$dept_id, 'route_id' => $routeId[0]['id']))
	    					 ->update('user_permissions', array('status'=>'2', 'is_deleted'=>'1'));
	    			$permissionArray['route_id'] = $routeId[0]['id'];

	    			$this->db->insert('user_permissions', 
	    				$permissionArray);
	    			break;
	    	}
	    }
	}
?>
