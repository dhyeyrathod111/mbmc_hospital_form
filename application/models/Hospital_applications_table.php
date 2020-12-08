<?php 
	class Hospital_applications_table extends CI_Model {
		
		public $table = 'hospital_applications';

		//set column field database for datatable orderable
	    var $column_order = array(null,'applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','applicant_nationality','technical_qualification','hospital_name','hospital_address','maternity_beds','patient_beds','contact_no','contact_person','floor_space',null,null,null,null);

		//set column field database for datatable searchable 
	    var $column_search = array('applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','applicant_nationality','technical_qualification','hospital_name','hospital_address','maternity_beds','patient_beds','contact_no','contact_person','floor_space');

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

		public function getHospitalsDataForDatatable($count=FALSE,$postdata)
		{
			$previous_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND status = 1 AND role_id < ".$this->authorised_user['role_id']." ORDER BY access_id DESC LIMIT 1")->result_array();
	    	$final_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND status = 1 ORDER BY access_id DESC LIMIT 1")->result_array();

	    	$condition = '';$approval_status = $postdata['approval_status'];

	    	switch ($approval_status) {
	    		case '0':
	    			$condition .= ' AND hospital_data.acessable_role_id <= '.$this->authorised_user['role_id'];
			    	if (!empty($previous_role_id)) {
			    		$condition .= " AND  hospital_data.last_approved_role_id = ".$previous_role_id[0]['role_id']; 
			    	} else {
			    		$condition .= " AND hospital_data.last_approved_role_id IS NULL";
			    	}
	    			break;
	    		case '1':
	    			if (!empty($final_role_id[0]['role_id']) && $final_role_id[0]['role_id'] == $this->authorised_user['role_id']) {
	    				$condition .= ' AND hospital_data.acessable_role_id IS NULL';
	    			} else {
	    				$condition .= ' AND (hospital_data.acessable_role_id > '.$this->authorised_user['role_id']." OR hospital_data.acessable_role_id IS NULL) ";
	    			}
	    			break;
	    		case '2':
	    			$condition .= ' AND hospital_data.is_deleted = 1 AND hospital_data.last_approved_role_id = '.$this->authorised_user['role_id'];
	    			break;
	    		default:
	    			break;
	    	}

	    	$rolehealthOfficerStack = $this->getRoleByName('health officer');

	    	if ($rolehealthOfficerStack->role_id == $this->authorised_user['role_id']) {
	    		$condition .= " AND hospital_data.health_officer = ".$this->authorised_user['ward_id'];
	    	}
	    	
	    	if ($rolehealthOfficerStack->role_id < $this->authorised_user['role_id']) {
	    		$condition .= " AND hospital_data.payment_status = 2";
	    	}


	    	if ($postdata['fromDate'] !='' || $postdata['toDate'] !='') {
	    		$condition .= " AND DATE(hospital_data.created_at)  >= '".DATE($postdata['fromDate'])."' AND DATE(hospital_data.created_at) <= '".DATE($postdata['toDate'])."'";
	    	}

	    	if (!empty($postdata['search']) && $postdata['search']['value'] != '') {

	    		if (substr($postdata['search']['value'], 0, 10) == 'MBMC-00000') : $postdata['search']['value'] = ltrim($postdata['search']['value'],"MBMC-00000"); endif;

	    		$condition .= " AND (hospital_data.app_id LIKE '%".$postdata['search']['value']."%' OR hospital_data.applicant_name LIKE '%".$postdata['search']['value']."%' OR hospital_data.applicant_email_id LIKE '%".$postdata['search']['value']."%' OR hospital_data.applicant_mobile_no LIKE '%".$postdata['search']['value']."%' OR hospital_data.hospital_name LIKE '%".$postdata['search']['value']."%')";
	    	}

	    	if ($postdata['application_type'] != 0) {
	    		$condition .= " AND hospital_data.application_type = ".$postdata['application_type'];
	    	} 

	    	if (!empty($postdata['order'])) {
	    		$colum_name['0'] = 'id';
	            $colum_name['1'] = 'app_id';
	            $colum_name['2'] = 'hospital_data.applicant_name';
	            $colum_name['3'] = 'hospital_data.applicant_email_id';
	            $colum_name['4'] = 'hospital_data.applicant_mobile_no';
	            $colum_name['5'] = 'hospital_data.hospital_name';
	            $colum_name['9'] = 'hospital_data.created_at';
	            $key = $postdata['order'][0]['column'];
	            $condition .= " ORDER BY ".$colum_name[$key]." ".$postdata['order'][0]['dir'];
	    	} else {
	    		$condition .= " ORDER BY app_id DESC";
	    	}
	    	
			$sql_string = "SELECT hospital_data.* FROM (SELECT ha.*,(SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ha.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id,(SELECT role_id FROM permission_access pa WHERE pa.dept_id = 5 AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL, '0', last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id , (SELECT py.status FROM payment py WHERE ha.app_id = py.app_id AND py.is_deleted = 0 AND py.dept_id = 5 ORDER BY pay_id DESC LIMIT 1) AS payment_status , (SELECT COUNT(*) FROM hospital_inspection_form hif WHERE hif.app_id = ha.app_id) AS hospital_inspection_done FROM hospital_applications ha) AS hospital_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result_array();
	    	}
		}

		public function getFinalApprovelDate($app_id,$dept_id)
		{
			$final_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND status = 1 ORDER BY access_id DESC LIMIT 1")->result_array();
			$this->db->select('*');
			$this->db->from('application_remarks');
			$this->db->where('app_id',$app_id)->where('dept_id',$dept_id)->where('role_id',$final_role_id[0]['role_id']);
			$this->db->order_by("id", "desc");
			return $this->db->get()->row();
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

	    public function hospital_revolution($payload,$hospital_id = 0)
    	{
    		if ($hospital_id != 0) {
    			return $this->db->where('id', $hospital_id)->update($this->table,$payload);
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
			if ($this->db->insert('applications_details',$application_stack)) {
				return $this->db->insert_id();
			} else {
				return 0;
			}
		}
		public function storeHospitalDocument($documentArray,$hospital_id)
		{
			foreach ($documentArray as $document) :
				$hospital_field = $document['file_field'];unset($document['file_field']);
				if ($this->db->insert('image_details',$document)) :
					$this->db->set($hospital_field,$this->db->insert_id())->where('id',$hospital_id)->update('hospital_applications');
				endif ;
			endforeach ;
		}
		public function reCreationHospitalStaff($staffStackPayload,$app_id)
		{
			$this->db->delete('hospital_staff_details',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_staff_details', $staffStackPayload);
		}
		public function reCreateFeesCharges($Payload,$app_id)
		{
			$this->db->delete('hospital_fee_charges',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_fee_charges', $Payload);
		}
		public function reCreationfloreSpaceForBedrooms($Payload,$app_id)
		{
			$this->db->delete('hospital_florespace_for_bedrooms',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_florespace_for_bedrooms', $Payload);
		}
		public function reCreateFSkitchen($Payload,$app_id)
		{
			$this->db->delete('hospital_florespace_for_kitchen',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_florespace_for_kitchen', $Payload);
		}
		public function reCreateSurgeon($Payload,$app_id)
		{
			$this->db->delete('hospital_surgeon_information',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_surgeon_information', $Payload);
		}
		public function reCreateMidwife($Payload,$app_id)
		{
			$this->db->delete('hospital_midwife',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_midwife', $Payload);
		}
		public function reCreateAlien($Payload,$app_id)
		{
			$this->db->delete('hospital_alien',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_alien', $Payload);
		}
		public function reCreateSupervision($Payload,$app_id)
		{
			$this->db->delete('hospital_supervision',['app_id' => $app_id]);
			return $this->db->insert_batch('hospital_supervision', $Payload);
		}
		public function getUserHospitalDataTable($count=FALSE,$postData)
		{
			$sql_string = "SELECT * FROM hospital_applications";
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
			$this->db->where(['dept_id'=>5,'is_deleted'=>0]);
			return $this->db->get()->result();
		}
		public function getApplicationByAppID($app_id)
		{
			$this->db->select('ha.*,(SELECT no_of_beds FROM hospital_inspection_form hif WHERE ha.app_id = hif.app_id LIMIT 1) AS no_of_beds');
			$this->db->from('hospital_applications ha');
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
			$condition .= ' AND hospital_data.user_id = '.$this->authorised_user['user_id'];
			// search conditions
			if (!empty($postdata['search']) && $postdata['search']['value'] != '') {
	    		if (substr($postdata['search']['value'], 0, 10) == 'MBMC-00000') : $postdata['search']['value'] = ltrim($postdata['search']['value'],"MBMC-00000"); endif;
	    		$condition .= " AND (hospital_data.app_id LIKE '%".$postdata['search']['value']."%' OR hospital_data.applicant_name LIKE '%".$postdata['search']['value']."%' OR hospital_data.applicant_email_id LIKE '%".$postdata['search']['value']."%' OR hospital_data.applicant_mobile_no LIKE '%".$postdata['search']['value']."%' OR hospital_data.hospital_name LIKE '%".$postdata['search']['value']."%')";
	    	}
	    	//start date to end date condition.
	    	if ($postdata['fromDate'] !='' || $postdata['toDate'] !='') {
	    		$condition .= " AND DATE(hospital_data.created_at)  >= '".DATE($postdata['fromDate'])."' AND DATE(hospital_data.created_at) <= '".DATE($postdata['toDate'])."'";
	    	}

			$sql_string = "SELECT hospital_data.* FROM (SELECT ha.*,(SELECT IF(ha.status = 0,'Awaiting',(SELECT status_title FROM app_status_level asl WHERE asl.status_id = ha.status))) AS application_status FROM hospital_applications ha) AS hospital_data WHERE 1 = 1".$condition;
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
			$this->db->from('hospital_applications');
			$this->db->where('id',$application_id);
			return $this->db->get()->row();
		}
		public function getFloreSpaceForBedroomsByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_florespace_for_bedrooms');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getFloreSpaceForKitchenByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_florespace_for_kitchen');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getNurcingStaffDetailsByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_staff_details');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getHospitalSurgeonsByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_surgeon_information');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getHospitalSupervisionByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_supervision');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getHospitalMidwifeByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_supervision');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getHospitalAlienByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_alien');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getHospitalFeeschargesByAppID($application_id)
		{
			$this->db->select('*');
			$this->db->from('hospital_fee_charges');
			$this->db->where('app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getImageByApplication($app)
		{
			$this->db->select('*');
			$this->db->from('image_details');
			$this->db->where_in('image_id',[
				$app->ownership_agreement,
				$app->tax_receipt,
				$app->doc_certificate,
				$app->reg_certificate,
				$app->staff_certificate,
				$app->nursing_staff_deg_certificate,
				$app->nursing_staff_reg_certificate,
				$app->bio_des_certificate,
				$app->society_noc,
				$app->fire_noc,
			]);
			return $this->db->get()->result();
		}
	    // Dhyey rahtod end
	}

?>
