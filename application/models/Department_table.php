<?php 
	class Department_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'department_table';

	        // Set orderable column fields
	        $this->column_order = array(null, 'dept_id','dept_title','status',null);

	        // Set searchable column fields
	        $this->column_search = array('dept_id','dept_title','status');

	        // Set default order
	        $this->order = array('dept_id' => 'desc');
	    }

	    public function getAllDepartments() {
	    	// echo'<pre>';print_r('hihi');exit;
			$query = $this->db->select('*')
							->from($this->table)
							->where(array('is_deleted' => '0','status' => '1'))
							->get()
							->result_array();

// 			echo'<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return false;
			}
		}

		public function getDepartmentByName($name = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($name != null) {
	    		$query = $this->db->select('dept_id')
					->from($this->table)
					->where(array('is_deleted'=>'0','dept_title' => $name))
					->get()
					->row_array();

				if($query) {
					return $query['dept_id'];
				} else {
					return null;
				}
	    	} else {
				return null;
	    	}
			
		}


		public function getDepartmentById($dept_id = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('dept_title,dept_id,dept_desc,department_mail_id')
					->from($this->table)
					->where(array('is_deleted'=>'0','dept_id' => $dept_id))
					->get()
					->row_array();
				// echo'<pre>';print_r($this->db->last_query());//exit;
				if($query) {
					return $query;
				} else {
					return null;
				}
	    	} else {
				return null;
	    	}
	    }
			
        public function insert($data = null) {
			// echo'ss<pre>';print_r($data);exit;
			if($data != null) {
				$result = $this->db->insert($this->table,$data);
				$lastId = $this->db->insert_id();
				if($result) {
					$dataReturn['success'] = true;
					$dataReturn['last_id'] = $lastId;
					return $dataReturn;
				} else {
					$dataReturn['success'] = false;
					return $dataReturn;
				}
			} else {
				$dataReturn['success'] = false;
				return $dataReturn;
			}
		}

		public function insertOld($data = null) {
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
		
		public function insertPermission($perArray = null){
			if(!empty($perArray)){
				$resPer = $this->db->insert('permission_access', $perArray);

				if($resPer){
					$data['success'] = true;
					$data['last'] = $this->db->insert_id();
					return $data;
				}else{
					$data['success'] = false; 
					return $data;
				}
			}else{
				$data['success'] = false;
				return $data;
			}
		}

		public function getPermissionData($dept_id){
			$perData = $this->db->select("*")
								->from('permission_access')
								->where(array('dept_id'=>$dept_id, 'status' => 1))
								->get()
								->result_array();

			if(!empty($perData)){
				return $perData;
			}else{
				return false;
			}
		}
		
		public function update($data = null, $dept_id = null, $role = null, $payableArray = null) {
			if($data != null) {
				$result = $this->db
							->where('dept_id', $dept_id)	
							->update($this->table, $data);
				// echo'<pre>';print_r($this->db->last_query());exit;
				if($result) {
					// return true;
					   
					//delete all access permission for department
					$deletePermission = $this->db->where('dept_id', $dept_id)
					                             ->update("permission_access", array('status' => '2'));
					$totalRoles = array();
					foreach($role as $krole => $vrole){
						$insertArray = array(
						        'dept_id' => $dept_id,
						        'role_id' => $vrole,
						        'payable_status' => $payableArray[$krole],
						    );
				// 		print_r($insertArray);    
				        $insResult = $this->db->insert("permission_access", $insertArray);
				        
				        if($insResult){
				            $totalRoles[] = 1;
				        }
					}
                    
					if(count($totalRoles) == count($role)){
						return true;
					}
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function updateOld($data = null, $dept_id = null) {
			if($data != null) {
				$result = $this->db
							->where('dept_id', $dept_id)	
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
	         
	        $this->db->from($this->table)->where(array('status' => '1', 'is_deleted' => '0'));
	 
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
	    
	    //changes dhey
	    public function get_user_by_dept($dept_id)
	    {
	    	$this->db->select('*');
	    	$this->db->from('users_table');
	    	$this->db->where('dept_id',$dept_id);
	    	$this->db->where('is_deleted',0);
	    	$this->db->where('status',1);
	    	return $this->db->get()->result();
	    }
	    
	    public function get_all_active_dept()
	    {
	    	$this->db->select('*');
	    	$this->db->from($this->table);
	    	$this->db->where('status',1);
	    	$this->db->where('is_deleted',0);
	    	return $this->db->get()->result();	
	    }
	    //End changes
	    
	    public function deleteRow($accessId){
			if($accessId != ''){
				$res = $this->db->where('access_id', $accessId)	
						->update('permission_access', array('status' => '2', 'updated_at' => date("Y-m-d H:i:s")));

				if($res){
					return true;
				}else{
					return false;
				}
			}
		}
	}

?>
