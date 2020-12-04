<?php 
	class Lab_applications_table extends CI_Model {
		
		public $table = 'lab_applications';

		//set column field database for datatable orderable
	    var $column_order = array(null,'applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','applicant_nationality','technical_qualification','lab_name','lab_address','maternity_beds','patient_beds','contact_no','contact_person','floor_space',null,null,null,null);

		//set column field database for datatable searchable 
	    var $column_search = array('applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','applicant_nationality','technical_qualification','lab_name','lab_address','maternity_beds','patient_beds','contact_no','contact_person','floor_space');

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

		public function getlabsDataForDatatable($count=FALSE,$postdata)
		{
			$previous_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND status = 1 AND role_id < ".$this->authorised_user['role_id']." ORDER BY access_id DESC LIMIT 1")->result_array();
	    	$final_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND status = 1 ORDER BY access_id DESC LIMIT 1")->result_array();
	    	$condition = '';$approval_status = $postdata['approval_status'];

	    	switch ($approval_status) {
	    		case '0':
	    			$condition .= ' AND lab_data.acessable_role_id <= '.$this->authorised_user['role_id'];
			    	if (!empty($previous_role_id)) {
			    		$condition .= " AND  lab_data.last_approved_role_id = ".$previous_role_id[0]['role_id']; 
			    	} else {
			    		$condition .= " AND lab_data.last_approved_role_id IS NULL";
			    	}
	    			break;
	    		case '1':
	    			if (!empty($final_role_id[0]['role_id']) && $final_role_id[0]['role_id'] == $this->authorised_user['role_id']) {
	    				$condition .= ' AND lab_data.acessable_role_id IS NULL';
	    			} else {
	    				$condition .= ' AND (lab_data.acessable_role_id > '.$this->authorised_user['role_id']." OR lab_data.acessable_role_id IS NULL) ";
	    			}
	    			break;
	    		case '2':
	    			$condition .= ' AND lab_data.is_deleted = 1 AND lab_data.last_approved_role_id = '.$this->authorised_user['role_id'];
	    			break;
	    		default:
	    			break;
	    	}

	    	$rolehealthOfficerStack = $this->getRoleByName('health officer');

	    	if ($rolehealthOfficerStack->role_id == $this->authorised_user['role_id']) {
	    		$condition .= " AND lab_data.health_officer = ".$this->authorised_user['ward_id'];
	    	}
	    	
	    	if ($rolehealthOfficerStack->role_id < $this->authorised_user['role_id']) {
	    		$condition .= " AND lab_data.payment_status = 2";
	    	}
	    	
			$sql_string = "SELECT lab_data.* FROM (SELECT ha.*,(SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ha.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id,(SELECT role_id FROM permission_access pa WHERE pa.dept_id = 5 AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL, '0', last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id , (SELECT py.status FROM payment py WHERE ha.app_id = py.app_id AND py.is_deleted = 0 AND py.dept_id = 5) AS payment_status , (SELECT COUNT(*) FROM hospital_inspection_form hif WHERE hif.app_id = ha.app_id) AS lab_inspection_done FROM lab_applications ha) AS lab_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result_array();
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



	    // Dhyey Rathod start

	    public function create_lab($insertStack)
    	{
    		if ($this->db->insert($this->table,$insertStack)) {
    			return $this->db->insert_id();
    		} else {
    			return 0;
    		}
    	}

    	public function lab_revolution($payload,$lab_id = 0)
    	{
    		if ($lab_id != 0) {
    			return $this->db->where('id', $lab_id)->update($this->table,$payload);
    		} else {
    			if ($this->db->insert($this->table,$payload)) {
	    			return $this->db->insert_id();
	    		} else {
	    			return 0;
	    		}
    		}
    	}

    	public function insert_application_details($application_stack)
		{
			return $this->db->insert('applications_details',$application_stack);
		}
		public function storelabDocument($documentArray,$lab_id)
		{
			foreach ($documentArray as $document) :
				$lab_field = $document['file_field'];unset($document['file_field']);
				if ($this->db->insert('image_details',$document)) :
					$this->db->set($lab_field,$this->db->insert_id())->where('id',$lab_id)->update('lab_applications');
				endif ;
			endforeach ;
		}
		public function reCreationlabStaff($staffStackPayload,$app_id)
		{
			$this->db->delete('lab_staff',['app_id' => $app_id]);
			return $this->db->insert_batch('lab_staff', $staffStackPayload);
		}
		public function getUserlabDataTable($count=FALSE,$postData)
		{
			$sql_string = "SELECT * FROM lab_applications";
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postData['length']." OFFSET ".$postData['start'];
	    		return $this->db->query($sql_string)->result();
	    	}
		}
		public function getRoleByName($role_name)
		{
			$this->db->select('*');
			$this->db->from('roles_table');
			$this->db->where('role_title',$role_name);
			return $this->db->get()->row();
		}
		public function getAllHelthOfficers()
		{
			$this->db->select('*');
			$this->db->from('ward');
			$this->db->where(['dept_id'=>5,'sub_dept_id'=>1,'is_deleted'=>0]);
			return $this->db->get()->result();
		}
		public function getApplicationByAppID($app_id)
		{
			$this->db->select('ha.*,(SELECT no_of_beds FROM hospital_inspection_form hif WHERE ha.app_id = hif.app_id LIMIT 1) AS no_of_beds');
			$this->db->from('lab_applications ha');
			$this->db->where('app_id',$app_id);
			return $this->db->get()->row();
		}
		public function create_inspection_form($insertStack)
		{
			return $this->db->insert('hospital_inspection_form', $insertStack);
		}
		public function getInspectionDataByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_inspection_form');
			$this->db->where('app_id',$app_id);
			return $this->db->get()->row();
		}
		public function create_payment_reqeust($insertStack)
		{
			return $this->db->insert('payment',$insertStack);
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
		public function getApplicationForUsers($count = FALSE , $postdata)
		{
			$condition = '';

			$condition .= ' AND lab_data.user_id = '.$this->authorised_user['user_id'];

			// search conditions
			if (!empty($postdata['search']) && $postdata['search']['value'] != '') {
	    		if (substr($postdata['search']['value'], 0, 10) == 'MBMC-00000') : $postdata['search']['value'] = ltrim($postdata['search']['value'],"MBMC-00000"); endif;
	    		$condition .= " AND (lab_data.app_id LIKE '%".$postdata['search']['value']."%' OR lab_data.applicant_name LIKE '%".$postdata['search']['value']."%' OR lab_data.applicant_email_id LIKE '%".$postdata['search']['value']."%' OR lab_data.applicant_mobile_no LIKE '%".$postdata['search']['value']."%' OR lab_data.lab_name LIKE '%".$postdata['search']['value']."%')";
	    	}

	    	//start date to end date condition.
	    	if ($postdata['fromDate'] !='' || $postdata['toDate'] !='') {
	    		$condition .= " AND DATE(lab_data.created_at)  >= '".DATE($postdata['fromDate'])."' AND DATE(lab_data.created_at) <= '".DATE($postdata['toDate'])."'";
	    	}

			$sql_string = "SELECT lab_data.* FROM (SELECT ha.*,(SELECT IF(ha.status = 0,'Awaiting',(SELECT status_title FROM app_status_level asl WHERE asl.status_id = ha.status))) AS application_status FROM lab_applications ha) AS lab_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result();
	    	}
		}




		public function getApplicationByID($application_id)
		{
			$this->db->select('*');
			$this->db->from('lab_applications');
			$this->db->where('id',$application_id);
			return $this->db->get()->row();
		}
		public function getImageByApplication($app)
		{
			$this->db->select('*');
			$this->db->from('image_details');
			$this->db->where_in('image_id',[
				$app->ownership_agreement,
				$app->tax_receipt,
				$app->doc_certificate,
				$app->bio_medical_certificate,
				$app->aadhaar_card
			]);
			return $this->db->get()->result();
		}
		public function getlabStaffByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('lab_staff');
			$this->db->where('app_id',$app_id);
			return $this->db->get()->result();
		}




	    // Dhyey rahtod end
	}

?>
