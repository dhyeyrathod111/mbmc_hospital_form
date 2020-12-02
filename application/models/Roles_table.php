<?php 
	class Roles_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'roles_table';

	        // Set orderable column fields
	        $this->column_order = array(null, 'role_id','role_title','status');

	        // Set searchable column fields
	        $this->column_search = array('role_id','role_title','status');

	        // Set default order
	        $this->order = array('role_id' => 'asc');

	        $this->column_order_status = array(null, 'dept_id', 'role_id', 'status_title');

	        $this->column_search_status = array('dept_id', 'role_id', 'status_title');
	    }

	    public function getAllRoles() {
			$query = $this->db->select('*')
							->from($this->table)
							->where('is_deleted','0')
							->get()
							->result_array();

			// echo'<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return false;
			}
		}

		public function getroleById($role_id = null) {
	    	if($role_id != null) {
	    		$query = $this->db->select('role_title,role_id')
					->from($this->table)
					->where(array('is_deleted'=>'0','role_id' => $role_id))
					->get()
					->row_array();

				if($query) {
					return $query;
				} else {
					return null;
				}
	    	} else {
				return null;
	    	}
	    }

		public function getroles() {
			$query = $this->db->select('*')
							->from($this->table)
							->where('status','1')
							->where('is_deleted','0')
							->get()
							->result_array();

			// echo'<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return false;
			}
		}

		public function insert($data = null) {
			// echo'ss<pre>';print_r($data);exit;
			if($data != null) {
				$result = $this->db->insert($this->table,$data);
				
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function update($data = null,$role_id = null) {
			if($data != null) {
				$result = $this->db
							->where('role_id', $role_id)	
							->update($this->table, $data);
				// echo'<pre>';print_r($this->db->last_query());exit;
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		 /*
	     * Fetch members data from the database
	     * @param $_POST filter data based on the posted parameters
	     */
	    public function getRows($postData){
	        $this->_get_datatables_query($postData);
	        if($postData['length'] != -1){
	            $this->db->limit($postData['length'], $postData['start']);
	        }
	        $query = $this->db->get();
	        return $query->result_array();
	    }
	    
	    /*
	     * Count all records
	     */
	    public function countAll(){
	        $this->db->from($this->table);
	        return $this->db->count_all_results();
	    }
	    
	    /*
	     * Count records based on the filter params
	     * @param $_POST filter data based on the posted parameters
	     */
	    public function countFiltered($postData){
	        $this->_get_datatables_query($postData);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }
	    
	    /*
	     * Perform the SQL queries needed for an server-side processing requested
	     * @param $_POST filter data based on the posted parameters
	     */
	    private function _get_datatables_query($postData){
	         
	        $this->db->from($this->table);
	 
	        $i = 0;
	        // loop searchable columns 
	        foreach($this->column_search as $item){
	            // if datatable send POST for search
	            if($postData['search']['value']){
	                // first loop
	                if($i===0){
	                    // open bracket
	                    $this->db->group_start();
	                    $this->db->like($item, $postData['search']['value']);
	                }else{
	                    $this->db->or_like($item, $postData['search']['value']);
	                }
	                
	                // last loop
	                if(count($this->column_search) - 1 == $i){
	                    // close bracket
	                    $this->db->group_end();
	                }
	            }
	            $i++;
	        }
	         
	        if(isset($postData['order'])){
	            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
	        }else if(isset($this->order)){
	            $order = $this->order;
	            $this->db->order_by(key($order), $order[key($order)]);
	        }
	    }

	    public function insertRoleStatus($insertArray = null){
	    	foreach ($insertArray as $key => $value) {
	    		$result[] = $this->db->insert("app_status_level", $value);
	    	}

	    	if(count($insertArray) == count($result)){
	    		return true;
	    	}else{
	    		return false;
	    	}
	    }

	    public function getRolesAppData($searchVal = null, $department = null, $roles = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null){
            $deptCond = "";
            if($department != ''){
                $deptCond = " AND t1.dept_id = ".$department;
            }
            
            $roleCond = "";
            if($roles != ''){
                $roleCond = " AND t1.role_id = ".$roles;
            }
	    	
	    	if($columnName == 'sr_no'){
	            $columnName = "t1.status_id";
	        }else{
	            $columnName = "t1.".$columnName;
	        }

	        if($searchVal != ''){
	        	$query = $this->db->query("SELECT t1.* FROM (SELECT st.*, (SELECT dept_title FROM department_table WHERE dept_id = st.dept_id) department_name, (SELECT role_title FROM roles_table WHERE role_id = st.role_id) role_name FROM `app_status_level` st WHERE is_deleted = '0') t1 WHERE (t1.department_name like '%".$searchVal."%' OR t1.role_name LIKE '%".$searchVal."%' OR t1.status_title like '%".$searchVal."%') ".$deptCond." ".$roleCond." ORDER BY ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
	        }else{
	        	$query = $this->db->query("SELECT t1.*, (SELECT dept_title FROM department_table WHERE dept_id = t1.dept_id) department_name, (SELECT role_title FROM roles_table WHERE role_id = t1.role_id) role_name FROM `app_status_level` t1 WHERE t1.is_deleted = '0' ".$deptCond." ".$roleCond." ORDER BY ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
	        }

	        return $query;
	    }

	    public function countFilteredStatus($postData){
	        $this->_get_datatables_query_status($postData);
	        $query = $this->db->get();
	        return $query->num_rows();
	    }

	    private function _get_datatables_query_status($postData){
             
            $this->db->from("app_status_level");
     
            $i = 0;
            // loop searchable columns 
            foreach($this->column_search_status as $item){
                // if datatable send POST for search
                if($postData['search']['value']){
                    // first loop
                    if($i===0){
                        // open bracket
                        $this->db->group_start();
                        $this->db->like($item, $postData['search']['value']);
                    }else{
                        $this->db->or_like($item, $postData['search']['value']);
                    }
                    
                    // last loop
                    if(count($this->column_search_status) - 1 == $i){
                        // close bracket
                        $this->db->group_end();
                    }
                }
                
                $i++;
            }
             
            if(isset($postData['order'])){
                $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
            }else if(isset($this->order)){
                $order = $this->order;
                $this->db->order_by(key($order), $order[key($order)]);
            }
    	 }


    	 public function deleteStatus($statusId = null){
    	 	$upArray = array('is_deleted' => '1');

    	 	$result = $this->db
							->where('status_id', $statusId)	
							->update("app_status_level", $upArray);
			// echo'<pre>';print_r($this->db->last_query());exit;
			if($result) {
				return true;
			} else {
				return false;
			}
    	 }


    	 public function getStatusDataById($appId = null){
    	 	$query = $this->db->select('*')
    	 					  ->from("app_status_level")
							->where('status_id',$appId)
							->get()
							->result_array();

			
			return $query;
			
    	 }

    	 public function editSubmit($updateArray = null, $status_id = null){
    	 	$res = $this->db->where('status_id', $status_id)
    	 			 ->update("app_status_level", $updateArray);

    	 	if($res){
    	 		return true;
    	 	}else{
    	 		return false;
    	 	}
    	 }
	}

?>
