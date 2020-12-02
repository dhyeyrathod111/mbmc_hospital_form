<?php 
	class  Hospital_staff_details_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'hospital_staff_details';

	        // // Set orderable column fields
	        // $this->column_order = array(null, 'qual_id','qual_title','status',null);

	        // // Set searchable column fields
	        // $this->column_search = array('qual_id','qual_id','status');

	        // // Set default order
	        // $this->order = array('qual_id' => 'asc');
	    }

	    public function getAllQaulification() {
	    	// echo'<pre>';print_r('hihihih');exit;
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

	 	public function getStaffDetailsById($app_id = null) {
	 		// echo'<pre>';print_r($road_id);exit;
			if($app_id != null){
				$query = $this->db->select('hos.id,hos.app_id,hos.staff_name,hos.age,des.design_title,qual.qual_title,hos.design_id,hos.qual_id')
					->from("$this->table as hos")
					->join('designation_master as des','des.design_id = hos.design_id')
					->join('qualification_master as qual','qual.qual_id = hos.qual_id')
					->where(array('hos.is_deleted'=>'0','hos.app_id'=>$app_id))
					->get()
					->result_array();

				// echo'<pre>';print_r($this->db->last_query());exit;
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

		public function update($data = null,$staff_id = null) {
			if($staff_id != null) {
				$result = $this->db
					->where('id', $staff_id)	
					->update($this->table, $data);
				
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
