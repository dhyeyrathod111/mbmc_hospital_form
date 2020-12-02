<?php 
	class Hall_checklist_details_table extends CI_Model {
		
		function __construct() {
	        // Set table name
	        $this->table = 'hall_checklist_details';
	    }

	 //    public function getAllHalls() {
	 //    	// echo'<pre>';print_r('hihihih');exit;
		// 	$query = $this->db->select('*')
		// 					->from($this->table)
		// 					->where('is_deleted','0')
		// 					->get()
		// 					->result_array();

		// 	// echo'<pre>';print_r($query);exit;
		// 		if($query) {
		// 		return $query;
		// 	} else {
		// 		return false;
		// 	}
		// }

	 	public function getassetDetailsBySkuId($sku_id = null) {
	 		// echo'<pre>';print_r($road_id);exit;
			$query = $this->db->select('asset.*,unit.*')
							->join('unit_master as unit','unit.unit_id = asset.asset_unit_id')
							->from("$this->table as asset")
							->where(array('asset.is_deleted'=>'0','asset.sku_id'=>$sku_id))
							->get()
							->result_array();
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function insert($data = null) {
			// echo'ss<pre>';print_r($data);//exit;
			if($data != null) {
				$result = $this->db->insert($this->table,$data);
				
				if($result) {
					return $this->db->insert_id();
				} else {
					return null;
				}
			} else {
				return false;
			}
		}

		public function update($data = null,$road_id = null) {
			if($data != null) {
				$result = $this->db
					->where('hall_id', $road_id)	
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
