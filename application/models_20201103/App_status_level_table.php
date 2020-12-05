<?php 
	class App_status_level_table extends CI_Model {
		//set column field database for datatable orderable
	    var $column_order = array(null,'status_id','status_title','dept_title','role_title','status.status',null);

		//set column field database for datatable searchable 
	    var $column_search = array('status_id','status_title','dept_title','role_title');


		function __construct() {
	        // Set table name
	        $this->table = 'app_status_level';

	    }

	    public function getAllStatus() {
	    	// echo'<pre>';print_r('hihi');exit;
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

		public function getAllStatusById($status_id = null) {
	    	// echo'<pre>';print_r($status_id);exit;
	    	if($status_id != null) {
	    		$query = $this->db->select('status_id,status_title,status_type')
					->from($this->table)
					->where(array('status_id'=> $status_id,'is_deleted'=>'0'))
					->get()
					->result_array();

				if($query) {
					return $query;
				} else {
					return null;
				}
	    	} else {
				return null;
	    	}
			
		}

		public function getAllStatusByDept($dept_id= null, $role_id = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('status_id,status_title')
					->from($this->table)
					->where(array('dept_id'=> $dept_id,'is_deleted'=>'0','role_id'=>$role_id))
					->order_by('role_id','asc')
					->get()
					->result_array();

				if($query) {
					return $query;
				} else {
					return null;
				}
	    	} else {
				return null;
	    	}
			
		}

		public function getAllStatusByDeptRole($dept_id= null, $role_id = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('status_id,status_title')
					->from($this->table)
					->where(array('role_id'=>$role_id,'dept_id'=> $dept_id,'is_deleted'=>'0'))
					->get()
					->result_array();
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
				
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function update($data = null,$status_id = null) {
			// echo'<pre>';print_r($data);exit;
			if($data != null) {
				$result = $this->db
						->where('status_id', $status_id)	
						->update($this->table, $data);
				// echo'ss<pre>';var_dump($result);exit;
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function getAllStatusDetailsById($status_id = null) {

			$query = $this->db->select('status.*,dept.dept_title,role.role_title')
							->from("$this->table as status")
							->join('department_table as dept','dept.dept_id = status.dept_id')
							->join('roles_table as role','role.role_id = status.role_id')
							->where(array('status.is_deleted'=>'0','status.status_id'=>$status_id))
							->get()
							->row_array();
			// echo'<pre>';print_r($query);exit;
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		 /*
	     * Fetch members data from the database
	     * @param $_POST filter data based on the posted parameters
	     */
	    public function getRows($postData){
	    	// echo'<pre>';print_r($postData);exit;
	        $this->_get_datatables_query($postData);
	        if($postData['length'] != -1){
	            $this->db->limit($postData['length'], $postData['start']);
	        }
	        $query = $this->db->get();
	        // echo'sss<pre>';print_r($query);exit;
	        return $query->result_array();
	    }
	    
	    /*
	     * Count all records
	     */
	    public function countAll(){
	        $this->db->select('status.*,dept.dept_title,role.role_title')
					->from("$this->table as status")
					->join('department_table as dept','dept.dept_id = status.dept_id')
					->join('roles_table as role','role.role_id = status.role_id');
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
	        $this->db->select('status.*,dept.dept_title,role.role_title')
					->from("$this->table as status")
					->join('department_table as dept','dept.dept_id = status.dept_id')
					->join('roles_table as role','role.role_id = status.role_id');
	        // $this->db->from($this->table);
	 		// echo'dd<pre>';print_r($this->db->last_query());exit;
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
         	// echo'<pre>';print_r($this->column_order[3]);exit;
         	// echo'<pre>';print_r($this->db->last_query());exit;
	        if(isset($postData['order'])){
	            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
	        }else if(isset($this->order)){
	            $order = $this->order;
	            $this->db->order_by(key($order), $order[key($order)]);
	        }


	    }

	}

?>
