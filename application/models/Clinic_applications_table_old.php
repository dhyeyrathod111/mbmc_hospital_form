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

		public function getClinicApllications($count,$postdata)
		{
			$condition = '';

			$previous_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND sub_dept_id = 1 AND status = 1 AND role_id < ".$this->authorised_user['role_id']." ORDER BY access_id DESC LIMIT 1")->result_array();
	    	$final_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND sub_dept_id = 1 AND status = 1 ORDER BY access_id DESC LIMIT 1")->result_array();

	    	$condition = '';$approval_status = $postdata['approval_status'];

	    	switch ($approval_status) {
	    		case '0':
	    			$condition .= ' AND clinic_data.acessable_role_id <= '.$this->authorised_user['role_id'];
			    	if (!empty($previous_role_id)) {
			    		$condition .= " AND  clinic_data.last_approved_role_id = ".$previous_role_id[0]['role_id']; 
			    	} else {
			    		$condition .= " AND clinic_data.last_approved_role_id IS NULL";
			    	}
	    			break;
	    		case '1':
	    			if (!empty($final_role_id[0]['role_id']) && $final_role_id[0]['role_id'] == $this->authorised_user['role_id']) {
	    				$condition .= ' AND clinic_data.acessable_role_id IS NULL';
	    			} else {
	    				$condition .= ' AND (clinic_data.acessable_role_id > '.$this->authorised_user['role_id']." OR clinic_data.acessable_role_id IS NULL) ";
	    			}
	    			break;
	    		case '2':
	    			$condition .= ' AND clinic_data.is_deleted = 1 AND clinic_data.last_approved_role_id = '.$this->authorised_user['role_id'];
	    			break;
	    		default:
	    			break;
	    	}
			if (!empty($postdata['order'])) {
	    		$colum_name['0'] = 'id';
	            $colum_name['1'] = 'clinic_data.app_id';
	            $colum_name['2'] = 'clinic_data.applicant_name';
	            $colum_name['3'] = 'clinic_data.applicant_email_id';
	            $colum_name['4'] = 'clinic_data.clinic_name';
	            $key = $postdata['order'][0]['column'];
	            $condition .= " ORDER BY ".$colum_name[$key]." ".$postdata['order'][0]['dir'];
	    	} 

			$sql_string = "SELECT clinic_data.* FROM (SELECT ca.*,(SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ca.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id,(SELECT role_id FROM permission_access pa WHERE pa.dept_id = 5 AND pa.sub_dept_id = 1 AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL, '0', last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id , (SELECT COUNT(*) FROM hospital_inspection_form hif WHERE hif.app_id = ca.app_id) AS clinic_inspection_done, (SELECT py.status FROM payment py WHERE ca.app_id = py.app_id AND py.is_deleted = 0 AND py.dept_id = 5) AS payment_status FROM clinic_applications ca) AS clinic_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result_array();
	    	}

		}
		public function create_payment_reqeust($insertStack)
		{
			return $this->db->insert('payment',$insertStack);
		}
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
	    public function getStaffByAppID($app_id)
	    {
	    	$this->db->select('*');
			$this->db->from('hospital_staff_details')->where('app_id',$app_id);
			return $this->db->get()->result_array();
	    }

	    public function getApplicationForUsers($count = FALSE , $postdata)
		{
			$condition = '';

			$condition .= ' AND clinic_data.user_id = '.$this->authorised_user['user_id'];

			// search conditions
			if (!empty($postdata['search']) && $postdata['search']['value'] != '') {
	    		if (substr($postdata['search']['value'], 0, 10) == 'MBMC-00000') : $postdata['search']['value'] = ltrim($postdata['search']['value'],"MBMC-00000"); endif;
	    		$condition .= " AND (clinic_data.app_id LIKE '%".$postdata['search']['value']."%' OR clinic_data.applicant_name LIKE '%".$postdata['search']['value']."%' OR clinic_data.applicant_email_id LIKE '%".$postdata['search']['value']."%' OR clinic_data.applicant_mobile_no LIKE '%".$postdata['search']['value']."%' OR clinic_data.clinic_name LIKE '%".$postdata['search']['value']."%')";
	    	}

	    	//start date to end date condition.
	    	if ($postdata['fromDate'] !='' || $postdata['toDate'] !='') {
	    		$condition .= " AND DATE(clinic_data.created_at)  >= '".DATE($postdata['fromDate'])."' AND DATE(clinic_data.created_at) <= '".DATE($postdata['toDate'])."'";
	    	}

			$sql_string = "SELECT clinic_data.* FROM (SELECT ca.*,(SELECT IF(ca.status = 0,'Awaiting',(SELECT status_title FROM app_status_level asl WHERE asl.status_id = ca.status))) AS application_status FROM clinic_applications ca) AS clinic_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result();
	    	}
		}
		public function getApplicationByAppID($app_id)
		{
			$this->db->select('ca.*,(SELECT no_of_beds FROM hospital_inspection_form hif WHERE ca.app_id = hif.app_id LIMIT 1) AS no_of_beds');
			$this->db->from('clinic_applications ca');
			$this->db->where('app_id',$app_id);	
			return $this->db->get()->row();
		}
		public function getInspectionDataByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_inspection_form');
			$this->db->where('app_id',$app_id);
			return $this->db->get()->row();
		}
		public function create_inspection_form($insertStack)
		{
			return $this->db->insert('hospital_inspection_form', $insertStack);
		}
		public function update_payment_by_appID($update_stack,$app_id)
		{
			$this->db->where('app_id', $app_id);
			return $this->db->update('payment', $update_stack);
		}
		public function getActivePaymentByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('payment');
			$this->db->where('app_id',$app_id);
			return $this->db->get()->row();
		}
	}

?>
