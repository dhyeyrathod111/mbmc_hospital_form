<?php 
	class Mandap_applications_table extends CI_Model {
		
		public $table = 'mandap_applications';

		//set column field database for datatable orderable
	    var $column_order = array(null,'applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','booking_address','booking_date','reason','status',null,null,null,null);

		//set column field database for datatable searchable 
	    var $column_search = array('applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','booking_address','booking_date','reason','status');

	    // default order
	    var $order = array('id' => 'Desc');  
	 
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

		public function getAllApplicationsDetailsById($mandap_id = null) {

			$query = $this->db->select('*,Date(booking_date) as booking_date')
					->from($this->table)
					->where(array('is_deleted'=>'0','id'=>$mandap_id))
					->get()
					->row_array();
			if($query) {
				return $query;
			} else {
				return null;
			}
		}

		public function getMandapbookingDetailsByAppId($app_id = null) {
			// echo'<pre>';print_r($app_id);exit;
			$query = $this->db->select('*,Date(booking_date) as booking_date')
							->from($this->table)
							->where(array('is_deleted'=>'0','app_id'=>$app_id))
							->get()
							->row_array();
			// echo'<pre>';print_r($this->db->last_query());exit;
			if($query) {
				return $query;
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
	    public function countAll() {
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
         	$this->db->select('*,Date(booking_date) as booking_date')
				->from($this->table);

			if($_POST['fromDate'] !='' || $_POST['toDate'] !='') {
     			$this->db->where('created_at >=', DATE($_POST['fromDate']));
    			$this->db->where('created_at <=', DATE($_POST['toDate']));
	    	}

	    	if($_POST['approval_status'] !='0') {
     			$this->db->where('status =', $_POST['approval_status']);
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

	    public function getWardFromDeptID($deptid)
	    {
	    	return $this->db->get_where('ward',['dept_id'=>$deptid,'is_deleted'=>0])->result();
	    }

	    public function getMandapDataForAuthorityDataTable($count=FALSE,$postdata,$dept_id)
	    {
	    	$previous_role_stack = $this->db->query("SELECT role_id FROM permission_access where dept_id = ".$dept_id." AND status = 1 AND role_id < ".$this->authorised_user['role_id']." ORDER BY access_id DESC LIMIT 1")->row();

	    	$final_role_stack = $this->db->query("SELECT * FROM permission_access where dept_id = ".$dept_id." AND status = 1 ORDER BY access_id DESC LIMIT 1")->row();

	    	$condition = '';$approval_status = $postdata['approval_status'];

	    	switch ($approval_status) {
	    		case '0':
	    			$condition .= ' AND mandap_data.acessable_role_id <= '.$this->authorised_user['role_id'];
			    	if (!empty($previous_role_stack)) {
			    		$condition .= " AND  mandap_data.last_approved_role_id = ".$previous_role_stack->role_id; 
			    	} else {
			    		$condition .= " AND mandap_data.last_approved_role_id IS NULL";
			    	}
	    			break;
	    		case '1':
	    			if (!empty($final_role_stack) && $final_role_stack->role_id == $this->authorised_user['role_id']) {
	    				$condition .= ' AND mandap_data.acessable_role_id IS NULL';
	    			} else {
	    				$condition .= ' AND (mandap_data.acessable_role_id > '.$this->authorised_user['role_id']." OR mandap_data.acessable_role_id IS NULL) ";
	    			}
	    			break;
	    		case '2':
	    			$condition .= ' AND mandap_data.is_deleted = 1 AND mandap_data.last_approved_role_id = '.$this->authorised_user['role_id'];
	    			break;
	    		default:
	    			break;
	    	}

	    	$condition .= " AND mandap_data.fk_ward_id = ".$this->authorised_user['ward_id'];

	    	$sql_string = "SELECT mandap_data.* FROM (SELECT ma.*,(SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ma.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id,(SELECT role_id FROM permission_access pa WHERE pa.dept_id = $dept_id AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL, '0', last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id ,(SELECT COUNT(*) FROM application_remarks ar WHERE ar.app_id = ma.app_id AND ar.dept_id = $dept_id AND ar.role_id = $final_role_stack->role_id AND is_deleted = 0 ) AS final_authority_approvel FROM mandap_applications ma) AS mandap_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result_array();
	    	}
	    }
	    public function getApplicationByAppID($appID)
	    {
	    	return $this->db->get_where($this->table,['app_id'=>$appID])->row();
	    }
	}

?>
