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

	    	$sql_string = "SELECT mandap_data.* FROM (SELECT ma.*,(SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ma.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id,(SELECT role_id FROM permission_access pa WHERE pa.dept_id = $dept_id AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL, '0', last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id ,(SELECT COUNT(*) FROM application_remarks ar WHERE ar.app_id = ma.app_id AND ar.dept_id = $dept_id AND ar.role_id = $final_role_stack->role_id AND is_deleted = 0 ) AS final_authority_approvel,(SELECT py.status FROM payment py WHERE ma.app_id = py.app_id AND py.is_deleted = 0 AND py.dept_id = 12 ORDER BY pay_id DESC LIMIT 1) AS check_payment_status FROM mandap_applications ma) AS mandap_data WHERE 1 = 1".$condition;

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
	    public function create_payment_reqeust($insertStack)
		{
			$this->db->set('is_deleted',1)->where(['dept_id'=>$insertStack['dept_id'],'app_id'=>$insertStack['app_id']])->update('payment');
			return $this->db->insert('payment',$insertStack);
		}
		public function update_payment_by_appID($update_stack,$app_id)
		{
			$this->db->where(['app_id'=>$app_id,'is_deleted'=>0]);
			return $this->db->update('payment', $update_stack);
		}
		public function getRoleByName($role_name)
		{
			$this->db->select('*');
			$this->db->from('roles_table');
			$this->db->where('role_title',$role_name);
			return $this->db->get()->row();
		}
		public function getActivePaymentByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('payment');
			$this->db->where('app_id',$app_id);
			return $this->db->get()->row();
		}
		public function getAllMandapType()
		{
			$this->db->select('*');
			$this->db->from('mandap_types');
			return $this->db->get()->result();
		}
		public function mandap_revolution($payload,$mandap_id = 0)
    	{
    		if ($mandap_id != 0) {
    			return $this->db->where('id', $mandap_id)->update($this->table,$payload);
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
		public function storeMandapDocument($documentArray,$mandap_id)
		{
			foreach ($documentArray as $document) :
				$mandap_field = $document['file_field'];unset($document['file_field']);
				if ($this->db->insert('image_details',$document)) :
					$this->db->set($mandap_field,$this->db->insert_id())->where('id',$mandap_id)->update($this->table);
				endif ;
			endforeach ;
		}
		public function getApplicationByID($application_id)
		{
			$this->db->select('*');
			$this->db->from($this->table);
			$this->db->where('id',$application_id);
			return $this->db->get()->row();
		}
		public function getImageByApplication($app)
		{
			$this->db->select('*');
			$this->db->from('image_details');
			$this->db->where_in('image_id',[
				$app->id_proof,
				$app->traffic_police_noc,
				$app->police_noc,
			]);
			return $this->db->get()->result();
		}
		public function getApplicationForUsers($count = FALSE , $postdata)
		{
			$condition = '';
			$condition .= ' AND mandap_data.user_id = '.$this->authorised_user['user_id'];
			// search conditions
			if (!empty($postdata['search']) && $postdata['search']['value'] != '') {
	    		if (substr($postdata['search']['value'], 0, 10) == 'MBMC-00000') : $postdata['search']['value'] = ltrim($postdata['search']['value'],"MBMC-00000"); endif;
	    		$condition .= " AND (mandap_data.app_id LIKE '%".$postdata['search']['value']."%' OR mandap_data.applicant_name LIKE '%".$postdata['search']['value']."%' OR mandap_data.applicant_email_id LIKE '%".$postdata['search']['value']."%' OR mandap_data.applicant_mobile_no LIKE '%".$postdata['search']['value']."%')";
	    	}
	    	//start date to end date condition.
	    	if ($postdata['fromDate'] !='' || $postdata['toDate'] !='') {
	    		$condition .= " AND DATE(mandap_data.created_at)  >= '".DATE($postdata['fromDate'])."' AND DATE(mandap_data.created_at) <= '".DATE($postdata['toDate'])."'";
	    	}

			$sql_string = "SELECT mandap_data.* FROM (SELECT ma.*,(SELECT IF(ma.status = 0,'Awaiting',(SELECT status_title FROM app_status_level asl WHERE asl.status_id = ma.status))) AS application_status FROM mandap_applications ma) AS mandap_data WHERE 1 = 1".$condition;
			if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postdata['length']." OFFSET ".$postdata['start'];
	    		return $this->db->query($sql_string)->result();
	    	}
		}
		public function getLettersByKey($key)
		{
			$this->db->select('*');
			$this->db->from('latter_table');
			$this->db->where('latter_key',$key);
			return $this->db->get()->row();
		}
		public function createPermissionLetter($payload)
		{
			return $this->db->insert('latter_generation',$payload);
		}



		// Dashboard data


		public function deshboardData($role_id , $dept_id , $is_superadmin)
		{
			$prevrole = $this->db->query("SELECT * FROM `permission_access` WHERE access_id < (SELECT access_id from permission_access WHERE dept_id = '".$dept_id."' AND role_id = '".$role_id."' AND status = '1') AND dept_id = '".$dept_id."' AND status = '1' ORDER BY access_id DESC LIMIT 1")->result_array();
			$previousroleid = (!empty($prevrole[0])) ? $prevrole[0]['role_id'] : 0 ;

			$dailyRequest = $this->getDailyRequestCountOFMandap();
			$data['dailyRequest'] = $dailyRequest->total_dailyrequest;

			$approvalPending = $this->getApprovelPendingForMandap($previousroleid,$dept_id,$role_id);
			$data['approvalPending'] = $approvalPending->total_pendingrequest;

			$totalamountcollected = $this->totlaAmountCollectedforMandap($dept_id);
			$data['totalamountcollected'] = $totalamountcollected->TotalCollectedAmount;

			$totalamountpending = $this->totlaAmountPendingforMandap($dept_id);
			$data['totalamountpending'] = $totalamountpending->TotalPendingAmount;

			$tRequestArray = array();
			for($i = 6; $i >= 0; $i--){
				$year = date("Y") - $i;
				$tRequestArray['labels'][] = $year;
				$tRequestArray['data'][] = $this->getYearlyReqeustforMandap($year)->total_yearlyreqeust;
			}
			$data['yearlyRequest'] = $tRequestArray;

			$approvedReqeustFortheyear = $this->approvedReqeustForTheYear($dept_id,$role_id);
			$data['approvedForYear'] = $approvedReqeustFortheyear->total_approve_req_foryear;

			$unapprovedReqeustFortheyear = $this->unapprovedReqeustForTheYear($previousroleid,$dept_id,$role_id);
			$data['unapprovedForYear'] = $unapprovedReqeustFortheyear->total_unapprove_req_foryear;

			$finalRoleStack = $this->getRoleByName('ward officer');

			// echo "<pre>";print_r($roleStacWardOfficer);exit();

			$barGraphArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					if ($previousroleid != 0) {
						$totalReqeustForMonth_sql = "SELECT COUNT(*) as totalReqeustForMonth FROM mandap_applications WHERE fk_ward_id = ".$this->authorised_user['ward_id']." AND MONTH(created_at) = ".$i." AND app_id IN (SELECT app_id FROM application_remarks ar WHERE ar.dept_id = ".$dept_id." AND ar.role_id = ".$previousroleid.");";
						$gettotalrequestObj['mandap_gettotalrequestObj'] = $this->db->query($totalReqeustForMonth_sql)->row()->totalReqeustForMonth;
						$gettotalrequestObj['total_gettotalrequestObj'] = $gettotalrequestObj['mandap_gettotalrequestObj'];
					} else {
						$gettotalrequestObj['mandap_gettotalrequestObj'] = $this->db->query("CALL gettotalreqmonthForMandap('".$i."', 'mandap_applications','".$this->authorised_user['ward_id']."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
						$gettotalrequestObj['total_gettotalrequestObj'] = $gettotalrequestObj['mandap_gettotalrequestObj'];
					}
					



					if ($previousroleid != 0) {

						$totalApprovedReqeustForMonth_sql = "SELECT COUNT(*) as totalApprovedReqeustForMonth FROM mandap_applications WHERE fk_ward_id = ".$this->authorised_user['ward_id']." AND MONTH(created_at) = ".$i." AND app_id IN (SELECT app_id FROM application_remarks ar WHERE ar.dept_id = ".$dept_id." AND ar.role_id = ".$finalRoleStack->role_id.");";
						$gettotalapprovedObj['mandap_gettotalapprovedObj'] = $this->db->query($totalApprovedReqeustForMonth_sql)->row()->totalApprovedReqeustForMonth;
						$gettotalapprovedObj['total_gettotalapprovedObj'] = $gettotalapprovedObj['mandap_gettotalapprovedObj'];

					} else {
						$gettotalapprovedObj['mandap_gettotalapprovedObj'] = $this->db->query("CALL totalapprovedByroleinmonthForMandap('".$i."','mandap_applications', '".$role_id."', '".$dept_id."','".$this->authorised_user['ward_id']."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
						$gettotalapprovedObj['total_gettotalapprovedObj'] = $gettotalapprovedObj['mandap_gettotalapprovedObj'];
					}

					$previousroleid = (!empty($prevrole)) ? $prevrole[0]['role_id'] : '0';

					if ($previousroleid != 0) {
						 $gettotalunapprovedObj_sql = "SELECT COUNT(*) as totalunApprovedReqeustForMonth FROM mandap_applications WHERE fk_ward_id = ".$this->authorised_user['ward_id']." AND MONTH(created_at) = ".$i." AND status IN (SELECT status_id FROM app_status_level WHERE dept_id = ".$i." AND role_id = 3 AND is_deleted = 0);";
						 $gettotalunapprovedObj['total_gettotalunapprovedObj'] = $this->db->query($gettotalunapprovedObj_sql)->row()->totalunApprovedReqeustForMonth;
					} else {
						$gettotalunapprovedObj['mandap_gettotalunapprovedObj'] = $this->db->query("CALL totalunapprovedByroleinmonthForMandap('".$i."','mandap_applications', '".$role_id."', '".$dept_id."', '".$previousroleid."','".$this->authorised_user['ward_id']."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
						$gettotalunapprovedObj['total_gettotalunapprovedObj'] = $gettotalunapprovedObj['mandap_gettotalunapprovedObj'];
					}

					$lineGraphArray['approvedArray'][] = $gettotalapprovedObj['total_gettotalapprovedObj'];
					$lineGraphArray['unapprovedArray'][] = $gettotalunapprovedObj['total_gettotalunapprovedObj'];
					$lineGraphArray['totalRequest'][] = $gettotalrequestObj['total_gettotalrequestObj'];	
					$barGraphArray['unapproved'][] = $gettotalunapprovedObj['total_gettotalunapprovedObj'];
					$barGraphArray['approved'][] = $gettotalapprovedObj['total_gettotalapprovedObj'];
				}


				$data['ApprovedUnApproved'] = $barGraphArray;
				$data['lineGraphArray'] = $lineGraphArray;

			return $data;
		}
		private function unapprovedReqeustForTheYear($prevrole = 0 , $dept_id , $role_id)
		{
			$unapprovereqforyear['mandap_unapprove_req_foryear'] = $this->db->query("CALL unapprovedYearlyDataForMandap(".$prevrole.",'mandap_applications', '".$dept_id."', '".$role_id."', '".date('Y')."','". $this->authorised_user['ward_id'] ."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
			$unapprovereqforyear['total_unapprove_req_foryear'] = $unapprovereqforyear['mandap_unapprove_req_foryear'];
			return json_decode(json_encode($unapprovereqforyear));
		}

		private function approvedReqeustForTheYear($dept_id,$role_id)
		{
			$approvereqforyear['mandap_approve_req_foryear'] = $this->db->query("CALL approvedYearlyDataForMandap('mandap_applications', '".date('Y')."', '".$dept_id."', '".$role_id."','". $this->authorised_user['ward_id'] ."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
			$approvereqforyear['total_approve_req_foryear'] = $approvereqforyear['mandap_approve_req_foryear'];
			return json_decode(json_encode($approvereqforyear));
		}
		private function getDailyRequestCountOFMandap()
		{
			$sql_string = "SELECT COUNT(*) totalRequest FROM mandap_applications WHERE date(created_at) = '".date('Y-m-d')."' AND fk_ward_id = ".$this->authorised_user['ward_id'];
			$dailyrequest['mandap_dailyrequest'] = $this->db->query($sql_string)->row()->totalRequest;	
			$dailyrequest['total_dailyrequest'] = $dailyrequest['mandap_dailyrequest'];
			return json_decode(json_encode($dailyrequest));
		}
		private function getApprovelPendingForMandap($prevrole = 0 , $dept_id , $role_id)
		{
			$roleStacWardOfficer = $this->getRoleByName('ward officer');

			$pendingrequest['mandap_pendingrequest'] = $this->db->query("CALL pendingapprovalsforMandap(".$prevrole." , ".$dept_id.", ".$role_id.", '".date('Y-m-d')."', '".'mandap_applications'."','". $this->authorised_user['ward_id'] ."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
			$mandap_readyforPaymentapps = $this->db->query('SELECT COUNT(*) AS readyforPayment FROM mandap_applications WHERE app_id NOT IN (SELECT app_id FROM payment) AND status IN (100,101)')->row()->readyforPayment;

			if ($this->authorised_user['role_id'] == $roleStacWardOfficer->role_id) {

				$total_pendingrequest = $pendingrequest['mandap_pendingrequest'];

				$totalreadyforpaymentApps = $mandap_readyforPaymentapps;
				$this->db->select('COUNT(*) + '.$totalreadyforpaymentApps.' pendingPaymentReqeust')->from('payment');
				$this->db->where(['dept_id'=>$dept_id,'status'=>1,'is_deleted'=>0]);
				$unpaidApplication = $this->db->get()->row()->pendingPaymentReqeust;
				$pendingrequest['total_pendingrequest'] = $total_pendingrequest - $unpaidApplication;
			} else {
				$pendingrequest['total_pendingrequest'] = $pendingrequest['mandap_pendingrequest'];
			}
			return json_decode(json_encode($pendingrequest));
		}
		private function totlaAmountCollectedforMandap($dept_id)
		{
			$this->db->select('SUM(amount) AS TotalCollectedAmount');
			$this->db->from('payment');
			$this->db->where(['dept_id'=>$dept_id,'status'=>2,'is_deleted'=>0]);
			$this->db->where('DATE(created_at)',date('Y-m-d'));
			return $this->db->get()->row();
		}
		private function totlaAmountPendingforMandap($dept_id)
		{
			$this->db->select('SUM(amount) AS TotalPendingAmount');
			$this->db->from('payment');
			$this->db->where(['dept_id'=>$dept_id,'status'=>1,'is_deleted'=>0]);
			return $this->db->get()->row();
		}
		public function getYearlyReqeustforMandap($year)
		{
			$roleStacWardOfficer = $this->getRoleByName('ward officer');
			$final_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = ".$this->authorised_user['dept_id']." AND status = 1 ORDER BY access_id DESC LIMIT 1")->row()->role_id;
			$mandap_yearlyreqeust_sql = "SELECT COUNT(*) AS reqeusts_this_year FROM (SELECT ma.id ,ma.app_id , ma.applicant_name , (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ma.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id FROM mandap_applications ma WHERE is_deleted = '0' AND YEAR(ma.created_at) = ".$year.") AS mandap_data WHERE last_approved_role_id = ".$final_role_id;
			$yearlyreqeust['total_yearlyreqeust'] = $this->db->query($mandap_yearlyreqeust_sql)->row()->reqeusts_this_year;
			return json_decode(json_encode($yearlyreqeust));
		}
		public function getMandapTypeDataByID($mandap_type_id)
		{
			$this->db->select('*');
			$this->db->from('mandap_types');
			$this->db->where(['id'=>$mandap_type_id,'is_deleted'=>0]);
			return $this->db->get()->row();
		}
		public function getPaymentSackByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('payment');
			$this->db->where(['app_id'=>$app_id,'is_deleted'=>0]);
			return $this->db->get()->row();
		}
	}

?>
