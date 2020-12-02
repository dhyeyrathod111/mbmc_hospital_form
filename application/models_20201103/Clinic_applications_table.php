<?php 
	class Clinic_applications_table extends CI_Model {
		
		public $table = 'clinic_applications';

		//set column field database for datatable orderable
	    var $column_order = array(null,'applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','applicant_qualification','clinic_name','clinic_address','contact_no','contact_person',null,null,null,null);

		//set column field database for datatable searchable 
	    var $column_search = array('applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','applicant_qualification','clinic_name','clinic_address','contact_no','contact_person');

	    // default order
	    var $order = array('id' => 'desc');  
	 
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

		public function getAllApplicationsDetailsById($id = null) {
			// echo'<pre>';print_r($id);exit;
			if($id != null) {
				$query = $this->db->select('*')
					->from($this->table)
					->where(array('is_deleted'=>'0','id'=>$id))
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
	         
	        $this->db->from($this->table);
	 		if($_POST['fromDate'] !='' || $_POST['toDate'] !='') {
     			$this->db->where('created_at >=', DATE($_POST['fromDate']));
    			$this->db->where('created_at <=', DATE($_POST['toDate']));
	    	}

	    	if($_POST['approval_status'] !='0') {
     			$this->db->where('status =', $_POST['approval_status']);
	    	}
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
