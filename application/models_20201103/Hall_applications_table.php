<?php 
	class Hall_applications_table extends CI_Model {
		
		public $table = 'hall_applications';

		//set column field database for datatable orderable
	    var $column_order = array(null,'applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','sku.sku_title','booking_date','reason','hall.amount',null,null,null,null);

		//set column field database for datatable searchable 
	    var $column_search = array('applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','sku.sku_title','booking_date','reason','hall.amount');

	    // default order
	    var $order = array('id' => 'asc');  
	 
	    public function __construct() {
	        parent::__construct();
	    }

		public function getApplications() {
			$query = $this->db->select('*')
							->from($this->table)
							->where('is_deleted','0')
							->get()
							->result_array();

			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function getAllApplicationsDetailsById($hall_id = null) {

			$query = $this->db->select('hall.*,Date(booking_date) as booking_date,sku_title')
							->from("$this->table as hall")
							->join('price_master as sku_price','hall.sku_price_id = sku_price.sku_id')
							->join('sku_master as sku','sku.sku_id = sku_price.sku_id')
							->where(array('hall.is_deleted'=>'0','hall.id'=>$hall_id))
							->get()
							->row_array();
			// echo'<pre>';print_r($this->db->last_query());exit;
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function getHallbookingDetailsByAppId($app_id = null) {
			// echo'<pre>';print_r($app_id);exit;
			$query = $this->db->select('*,Date(booking_date) as booking_date')
							->from($this->table)
							->where(array('is_deleted'=>'0','app_id'=>$app_id))
							->get()
							->row_array();
			echo'<pre>';print_r($this->db->last_query());exit;
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function getHallbookingDetailsById($sku_price_id = null,$date = null) {

			$query = $this->db->select('*,Date(booking_date) as booking_date')
							->from($this->table)
							->where(array('sku_price_id'=>$sku_price_id, 'Date(booking_date)>='=>$date))
							->get()
							->result_array();

			// echo'<pre>';print_r($query);exit;
			if($query) {
				// echo'<pre>';print_r($query);exit;
				return $query;
			} else {
				// echo'<pre>';print_r('gi');exit;
				return null;
			}
		}

		public function getLastId() {

			$query = $this->db->select('id')
							->from($this->table)
							->where(array('status' => '1','is_deleted'=> '0'))
							->order_by("id", "desc")
							->limit('1')
							->get()
							->row_array();
			if($query) {
				return $query;
			} else {
				return false;
			}
		}

		public function insert($data = null) {
			
			if($data != null) {
				$result = $this->db->insert($this->table,$data);
				
				if($result) {
					return $this->db->insert_id();
				} else {
					return null;
				}
			} else {
				return null;
			}
		}

		public function update($data = null,$app_id = null) {
			// echo'<pre>';print_r($app_id);exit;
			if($data != null) {
				$this->db->where('app_id',$app_id);
				$result = $this->db->update($this->table,$data);
				
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
	    	// echo'<pre>';print_r($postData);exit;
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

         	// echo'<pre>';print_r($_POST['toDate']);exit;
         	$this->db->select('hall.*,Date(booking_date) as booking_date,sku_title')
				->from("$this->table as hall")
				->join('price_master as sku_price','hall.sku_price_id = sku_price.sku_id')
				->join('sku_master as sku','sku.sku_id = sku_price.sku_id')
				->where(array('sku_price.status'=> '1'));

			if($_POST['fromDate'] !='' || $_POST['toDate'] !='') {
     			$this->db->where('hall.created_at >=', DATE($_POST['fromDate']));
    			$this->db->where('hall.created_at <=', DATE($_POST['toDate']));
	    	}

	    	if($_POST['approval_status'] !='0') {
     			$this->db->where('hall.status =', $_POST['approval_status']);
	    	}

	 		// echo'<pre>';print_r($this->db->last_query());exit;
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
