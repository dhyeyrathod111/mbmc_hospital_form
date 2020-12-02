<?php 
	class Price_master_table extends CI_Model {
		//set column field database for datatable orderable
	    var $column_order = array(null,'price_id','sku_title','dept_title','amount','price.status',null);

		//set column field database for datatable searchable 
	    var $column_search = array('price_id','sku_title','dept_title','amount','price.status');


		function __construct() {
	        // Set table name
	        $this->table = 'price_master';

	    }

	    public function getAllPrice() {
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

		public function getAllPriceById($price_id = null) {
	    	// echo'<pre>';print_r($status_id);exit;
	    	if($price_id != null) {
	    		$query = $this->db->select('price_id,amount,sku.sku_title,sku.sku_id')
					->from("$this->table as price")
					->join('sku_master as sku','sku.sku_id = price.sku_id')
					->where(array('price_id'=> $price_id,'price.is_deleted'=>'0'))
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

		public function getAllPriceBySkuId($sku_id= null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('price_id,amount')
					->from($this->table)
					->where(array('sku_id'=> $sku_id,'is_deleted'=>'0'))
					->order_by('sku_id','asc')
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

		public function getAllPriceByDeptId($dept_id= null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($dept_id != null) {
	    		$query = $this->db->select('price.*,dept.dept_title,sku.sku_title')
						->from("$this->table as price")
						->join('sku_master as sku','sku.sku_id = price.sku_id')
						->join('department_table as dept','sku.dept_id = dept.dept_id')
						->where(array('price.status'=> '1','price.is_deleted'=>'0','price.dept_id'=>$dept_id))
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

		public function getpriceByskuId($sku_id= null) {
	    	// echo'<pre>';print_r('hihi');exit;
	    	if($sku_id != null) {
	    		$query = $this->db->select('amount')
						->from($this->table)
						->where(array('is_deleted'=>'0','sku_id'=>$sku_id))
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

		public function insert($data = null) {

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

		public function update($data = null,$price_id = null) {
			// echo'<pre>';print_r($price_id);exit;
			if($data != null) {
				$result = $this->db
						->where('price_id', $price_id)	
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

		public function getAllPriceDetailsById($price_id = null) {

			$query = $this->db->select('price.*,dept.dept_title,sku.sku_title')
						->from("$this->table as price")
						->join('sku_master as sku','sku.sku_id = price.sku_id')
						->join('department_table as dept','sku.dept_id = dept.dept_id')
						->where(array('price.is_deleted'=>'0','price.price_id'=>$price_id))
						->get()
						->row_array();
			// echo'<pre>';print_r($this->db->last_query());exit;
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
	        $this->db->select('price.*,dept.dept_title,sku.sku_title')
					->from("$this->table as price")
					->join('sku_master as sku','sku.sku_id = price.sku_id')
					->join('department_table as dept','sku.dept_id = dept.dept_id');
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
	        $this->db->select('price.*,dept.dept_title,sku.sku_title')
					->from("$this->table as price")
					->join('sku_master as sku','sku.sku_id = price.sku_id')
					->join('department_table as dept','sku.dept_id = dept.dept_id');
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
