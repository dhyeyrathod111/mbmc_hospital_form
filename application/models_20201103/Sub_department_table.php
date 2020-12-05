<?php 
	class Sub_department_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'sub_department_table';

	        // Set orderable column fields
	        $this->column_order = array(null,'sub_dept_id','dept_id','dept_title','status',null);

	        // Set searchable column fields
	        $this->column_search = array('dept_id','dept_title','status');

	        // Set default order
	        $this->order = array('dept_title' => 'asc');
	    }

	    public function getAllSubDepartments() {
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

		public function getSubDepartmentByName($name = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($name != null) {
	    		$query = $this->db->select('sub_dept_id')
					->from($this->table)
					->where(array('is_deleted'=>'0','dept_title' => $name))
					->get()
					->row_array();

				if($query) {
					return $query['sub_dept_id'];
				} else {
					return null;
				}
	    	} else {
				return null;
	    	}
			
		}

		public function getSubDepartmentByDeptId($dept_id = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('dept_id')
					->from($this->table)
					->where(array('is_deleted'=>'0','dept_id' => $dept_id))
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


		public function getDepartmentById($dept_id = null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('dept_title,dept_id,dept_desc')
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
				
				if($result) {
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		public function update($data = null,$dept_id = null) {
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

	}

?>
