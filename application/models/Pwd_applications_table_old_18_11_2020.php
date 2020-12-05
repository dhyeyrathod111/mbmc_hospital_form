<?php 
	class Pwd_applications_table extends CI_Model {
		
		public $table = 'pwd_applications';

		//set column field database for datatable orderable
	    var $column_order = array(null,'app_id','applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','letter_no','letter_date','company_name','landline_no','contact_person','days_of_work',null,null,null,null);

		//set column field database for datatable searchable 
	    var $column_search = array('app_id','applicant_name','applicant_email_id','applicant_mobile_no','applicant_address','letter_no','letter_date','company_name','landline_no','contact_person','days_of_work');

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

			$select_str = "pwd.*,(SELECT permission_access.role_id FROM permission_access WHERE permission_access.dept_id = 1 AND permission_access.status = 1 AND permission_access.role_id > (SELECT CASE WHEN COUNT(role_id) = 0 THEN COUNT(role_id) ELSE role_id END AS role_id FROM app_status_level WHERE app_status_level.status_id = pwd.status LIMIT 1) ORDER BY access_id ASC LIMIT 1) AS authorised_access,(SELECT role_id FROM `application_remarks` WHERE app_id = pwd.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id";

			$query = $this->db->select($select_str)
							->from("$this->table as pwd")
							->where(array('pwd.id'=>$id))
							->get()
							->row_array();
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

	    public function getAuthorityData($count=FALSE,$postData)
	    {
	    	$approval_status = $postData['approval_status'];

	    	$condition = '';

	    	$previous_role_id = $this->db->query("SELECT role_id FROM mbmc.permission_access where dept_id = 1 AND status = 1 AND role_id < ".$this->authorised_user['role_id']." ORDER BY access_id DESC LIMIT 1")->result_array();

	    	$final_role_id = $this->db->query("SELECT role_id FROM mbmc.permission_access where dept_id = 1 AND status = 1 ORDER BY access_id DESC LIMIT 1")->result_array();

	    	switch ($approval_status) {
	    		case 'new':
	    			$condition .= ' AND pwd_data.acessable_role_id <= '.$this->authorised_user['role_id'];
			    	if (!empty($previous_role_id)) {
			    		$condition .= " AND  pwd_data.last_approved_role_id = ".$previous_role_id[0]['role_id']; 
			    	} else {
			    		$condition .= " AND pwd_data.last_approved_role_id IS NULL";
			    	}
			    	$condition .= " AND pwd_data.is_deleted = 0 ";
	    			break;
	    		case '0':
    			$condition .= ' AND (pwd_data.acessable_role_id <= '.$this->authorised_user['role_id'].' OR pwd_data.last_approved_role_id >= '.$this->authorised_user['role_id'].')';
    			$condition .= " AND pwd_data.is_deleted = 0";
	    			break;
	    		case '2':
	    			if ($final_role_id[0]['role_id'] == $this->authorised_user['role_id']) {
	    				$condition .= ' AND pwd_data.acessable_role_id IS NULL ';
	    			} else {
	    				$condition .= ' AND (pwd_data.acessable_role_id > '.$this->authorised_user['role_id']." OR pwd_data.acessable_role_id IS NULL) AND is_deleted = '0'";
	    			}
	    			break;
	    		case '3':
	    			$condition .= ' AND pwd_data.is_deleted = 1 AND pwd_data.last_approved_role_id = '.$this->authorised_user['role_id'];
	    			break;
	    		default:
	    			break;
	    	}
	    	//start date to end date condition.
	    	if ($postData['fromDate'] !='' || $postData['toDate'] !='') {
	    		$condition .= " AND DATE(pwd_data.created_at)  >= '".DATE($postData['fromDate'])."' AND DATE(pwd_data.created_at) <= '".DATE($postData['toDate'])."'";
	    	}
	    	// ward is selected 
	    	if ($this->authorised_user['ward_id'] != '0') {
	    		$condition .= " AND pwd_data.fk_ward_id = ".$this->authorised_user['ward_id'];
	    	}

	    	if (!empty($postData['search']) && $postData['search']['value'] != '') {
	    		$condition .= " AND (pwd_data.app_id LIKE '%".$postData['search']['value']."%' OR pwd_data.applicant_name LIKE '%".$postData['search']['value']."%' OR pwd_data.applicant_email_id LIKE '%".$postData['search']['value']."%' OR pwd_data.applicant_mobile_no LIKE '%".$postData['search']['value']."%' OR pwd_data.reference_no LIKE '%".$postData['search']['value']."%' OR pwd_data.one_company_name LIKE '%".$postData['search']['value']."%' OR pwd_data.created_at LIKE '%".$postData['search']['value']."%')";
	    	}
	    	if (!empty($postData['order'])) {
	    		$colum_name['0'] = 'id';
	            $colum_name['1'] = 'app_id';
	            $colum_name['2'] = 'pwd_data.applicant_name';
	            $colum_name['3'] = 'pwd_data.applicant_email_id';
	            $colum_name['4'] = 'pwd_data.applicant_mobile_no';
	            $colum_name['5'] = 'pwd_data.reference_no';
	            $colum_name['6'] = 'pwd_data.one_company_name';
	            $colum_name['7'] = 'pwd_data.days_of_work';
	            $key = $postData['order'][0]['column'];
	            $condition .= " ORDER BY ".$colum_name[$key]." ".$postData['order'][0]['dir'];
	    	} 
	    	$sql_string = "SELECT pwd_data.* FROM (SELECT `pwd_applications`.*, (SELECT company_name FROM company_details WHERE company_id = pwd_applications.company_name) one_company_name, (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = pwd_applications.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id, (SELECT role_id FROM permission_access pa WHERE pa.dept_id = 1 AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL, '0', last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id, (SELECT COUNT(*) FROM latter_generation lg WHERE lg.app_id = pwd_applications.app_id AND lg.status = 1 AND lg.latter_type_id = 2) AS permition_latter_count, (SELECT COUNT(*) FROM payment py WHERE py.dept_id = 1 AND py.app_id = pwd_applications.id AND py.approved_by = 0 AND py.is_deleted = 0 AND py.status = 1) AS payment_done , (SELECT COUNT(*) FROM joint_visit_extentions jve WHERE jve.app_id = pwd_applications.app_id AND jve.type = 1 AND jve.status = 2 AND jve.is_deleted = 0) AS dispatched_refrence_number , (SELECT jve.approved_by FROM joint_visit_extentions jve WHERE jve.type = 1 AND jve.app_id = pwd_applications.app_id AND jve.status = 1 AND jve.is_deleted = 0) AS joint_visit_creater_id , (SELECT COUNT(*) FROM payment pmt WHERE pmt.app_id = pwd_applications.id AND pmt.status = 2 AND pmt.is_deleted = 0 AND pmt.approved_by = ".$this->authorised_user['user_id']." AND pwd_applications.file_closure_status = 0) AS file_ableto_close FROM `pwd_applications`) AS pwd_data WHERE 1 = 1".$condition;

	    	if ($count == TRUE) {
	    		return $this->db->query($sql_string)->num_rows();
	    	} else {
	    		$sql_string .= " LIMIT ".$postData['length']." OFFSET ".$postData['start'];
	    		
	    		return $this->db->query($sql_string)->result_array();
	    	}
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
	    	$this->db->select("pwd_applications.*, (SELECT company_name FROM company_details WHERE company_id = pwd_applications.company_name) company_name,
	    		(SELECT role_id FROM application_remarks WHERE application_remarks.app_id = pwd_applications.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id,
	    		(SELECT role_id FROM permission_access pa WHERE pa.dept_id = 1 AND pa.status = 1 AND pa.role_id > IF(last_approved_role_id IS NULL,'0',last_approved_role_id) ORDER BY access_id ASC LIMIT 1) AS acessable_role_id,(SELECT COUNT(*) FROM latter_generation lg WHERE lg.app_id = pwd_applications.app_id AND lg.status = 1 AND lg.latter_type_id = 2) AS permition_latter_count,(SELECT COUNT(*) FROM payment py WHERE py.dept_id = 1 AND py.app_id = pwd_applications.id AND py.approved_by = 0 AND py.is_deleted = 0 AND py.status = 1) AS payment_done");

	        $this->db->from($this->table);

         	if($_POST['fromDate'] !='' || $_POST['toDate'] !='') {
     			$this->db->where('created_at >=', DATE($_POST['fromDate']));
    			$this->db->where('created_at <=', DATE($_POST['toDate']));
	    	}

	    	$this->db->where('is_deleted',0);

	    	if($_POST['approval_status'] != '0') {
     			$this->db->where('status =', $_POST['approval_status']);
	    	}

	    	if ($this->authorised_user['ward_id'] != 0) {
	    		$this->db->where('pwd_applications.fk_ward_id',$this->authorised_user['ward_id']);	
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
		
		public function getUserApp($user_id = null){
			$getDataUser = $this->db->query("")->result_array();
		}

		public function getUserData($searchVal = null, $fromDate = null, $toDate  = null, $i = null, $rowperpage = null, $columnIndex = null, $columnName = null, $columnSortOrder = null, $user_id = null){

			if($columnName == 'sr_no'){
				$columnName = "t1.id";
			} else if ($columnName == 'application_no') {
				$columnName = "t1.app_id";
			}else if ($columnName == 'data_added') {
				$columnName = "t1.created_at";
			} else if($columnName == 'approval_status'){
				$columnName = "t1.approved_by";
			}else{
				$columnName = "t1.".$columnName;
			} 

			$dateCond = "";
			if($fromDate != '' && $toDate != ''){
				$dateCond = "WHERE date(t1.created_at) >= '".$fromDate."' AND date(t1.created_at) <= '".$toDate."'";
			}

			if($searchVal != ''){

				if($dateCond == ''){
					$dateCond = "WHERE";
				} else {
					$dateCond .= " AND ";
				}

				$totalCount = $this->db->query("SELECT COUNT(t1.id) cnt_id FROM (SELECT pd.*, IF(pd.file_status IS NULL, 'Awaiting', pd.file_status) approved_by, (SELECT company_name FROM company_details WHERE company_id = pd.company_name) companys_name FROM (SELECT pwd.*, (SELECT rt.role_title FROM application_remarks ar INNER JOIN roles_table rt ON ar.role_id = rt.role_id AND rt.status = '1' WHERE ar.app_id = pwd.app_id AND ar.status = '1' ORDER BY ar.id DESC LIMIT 1) file_status , (SELECT COUNT(*) FROM joint_visit_extentions jve WHERE jve.app_id = pwd.app_id AND jve.type = 1 AND jve.status = 2 AND jve.is_deleted = 0) AS dispatched_refrence_number FROM `pwd_applications` pwd WHERE pwd.user_id = '".$user_id."') pd ) t1 ".$dateCond." (t1.app_id like '%".$searchVal."%' OR t1.companys_name like '%".$searchVal."%' OR t1.road_name like '%".$searchVal."%' OR t1.approved_by like '%".$searchVal."%'  OR t1.created_at like '%".$searchVal."%')")->result_array();
				

				$result = $this->db->query("SELECT t1.* FROM (SELECT pd.*, IF(pd.file_status IS NULL, 'Awaiting', pd.file_status) approved_by, (SELECT company_name FROM company_details WHERE company_id = pd.company_name) companys_name FROM (SELECT pwd.*, (SELECT rt.role_title FROM application_remarks ar INNER JOIN roles_table rt ON ar.role_id = rt.role_id AND rt.status = '1' WHERE ar.app_id = pwd.app_id AND ar.status = '1' ORDER BY ar.id DESC LIMIT 1) file_status , (SELECT COUNT(*) FROM joint_visit_extentions jve WHERE jve.app_id = pwd.app_id AND jve.type = 1 AND jve.status = 2 AND jve.is_deleted = 0) AS dispatched_refrence_number FROM `pwd_applications` pwd WHERE pwd.user_id = '".$user_id."') pd ) t1 ".$dateCond." (t1.app_id like '%".$searchVal."%' OR t1.companys_name like '%".$searchVal."%' OR t1.road_name like '%".$searchVal."%' OR t1.approved_by like '%".$searchVal."%'  OR t1.created_at like '%".$searchVal."%')")->result_array();

			}else{
				
				$totalCount = $this->db->query("SELECT COUNT(t1.id) cnt_id FROM (SELECT pd.* FROM (SELECT pwd.*, (SELECT rt.role_title FROM application_remarks ar INNER JOIN roles_table rt ON ar.role_id = rt.role_id AND rt.status = '1' WHERE ar.app_id = pwd.app_id AND ar.status = '1' ORDER BY ar.id DESC LIMIT 1) file_status FROM `pwd_applications` pwd WHERE pwd.user_id = '".$user_id."') pd ) t1 ".$dateCond)->result_array();

				$result = $this->db->query("SELECT t1.* FROM (SELECT pd.*, IF(pd.file_status IS NULL, 'Awaiting', pd.file_status) approved_by, (SELECT company_name FROM company_details WHERE company_id = pd.company_name) companys_name FROM (SELECT pwd.*, (SELECT rt.role_title FROM application_remarks ar INNER JOIN roles_table rt ON ar.role_id = rt.role_id AND rt.status = '1' WHERE ar.app_id = pwd.app_id AND ar.status = '1' ORDER BY ar.id DESC LIMIT 1) file_status , (SELECT COUNT(*) FROM joint_visit_extentions jve WHERE jve.app_id = pwd.app_id AND jve.type = 1 AND jve.status = 2 AND jve.is_deleted = 0) AS dispatched_refrence_number FROM `pwd_applications` pwd WHERE pwd.user_id = '".$user_id."') pd ) t1 ".$dateCond." ORDER BY ".$columnName." ".$columnSortOrder." limit ".$i.",".$rowperpage)->result_array();
			}

			$data['cntData'] = $totalCount;
			$data['result'] = $result;

			if(!empty($result)){
				return $data;
			}else{
				return false;
			}
		}
		public function getAllCompanyData()
		{
			$this->db->select('*');
			$this->db->from('company_details')->where('status',1);
			return $this->db->get()->result();
		}
		public function getCompanyAddByCompID($company_id)
		{
			$this->db->select('*');
			$this->db->from('company_address')->where('company_id',$company_id);
			return $this->db->get()->result();
		}
		public function getAllRoadType($road_type_id = FALSE)
		{
			$this->db->select('*');
			$this->db->from('road_type')->where('status',1)->where('is_deleted',0);
			if ($road_type_id != FALSE) {
				$this->db->where('road_id',$road_type_id);
				return $this->db->get()->row();
			} else {
				return $this->db->get()->result();
			}
		}
		public function insertRoadInformation($insert_stack)
		{

			return $this->db->insert('road_information_pwd',$insert_stack);
		}
		public function storeMultipleDocument($document_array , $application_id)
		{
			// print_r($application_id);exit();
			foreach ($document_array as $key => $oneDocs_array) {
				if ($oneDocs_array['file_field'] == "geo_location_map") {
					unset($oneDocs_array['file_field']);
					$this->db->insert('image_details',$oneDocs_array);
					$this->db->set('geo_location_map_id',$this->db->insert_id())->where('id',$application_id)->update('pwd_applications');
 				} else if($oneDocs_array['file_field'] == "request_letter" || $oneDocs_array['file_field'] == "request_letter_name") {
 					unset($oneDocs_array['file_field']);
					$this->db->insert('image_details',$oneDocs_array);
					$this->db->set('request_letter_id',$this->db->insert_id())->where('id',$application_id)->update('pwd_applications');
 				} else {
 					return FALSE;
 				}
			}
		}
		public function getPwdRoadType($application_id)
		{
			$this->db->select('rid.*, (SELECT road_title FROM road_type WHERE road_id = rid.road_type_id) road_surface_Type');
			$this->db->from('road_information_pwd rid')->where('rid.status',1)->where('rid.pwd_app_id',$application_id);
			return $this->db->get()->result();
		}
		public function getCompnayAddressBYCompID($company_id)
		{
			$this->db->select('*');
			$this->db->from('company_address')->where('status',1)->where('company_id',$company_id);
			return $this->db->get()->result();
		}
		public function deletePwdRoadType($application_id)
		{
			return $this->db->delete('road_information_pwd',['pwd_app_id'=>$application_id]);
		}
		public function update_pwd_application($dataStack , $application_id)
		{
			return $this->db->where('id', $application_id)->update('pwd_applications', $dataStack);
		}
		public function deleteApplicationByID($application_id)
		{
			return $this->db->set('is_deleted',1)->where('id',$application_id)->update('pwd_applications');
		}
		public function get_defect_laiblity($defect_laiblity_id = FALSE)
		{
			$this->db->select('*');
			$this->db->from('defect_laiblity')->where('status',1);
			if($defect_laiblity_id != FALSE){
				$this->db->where('laib_id',$defect_laiblity_id);
				return $this->db->get()->row();
			} else {
				return $this->db->get()->result();
			}
		}
		public function get_permission_type()
		{
			$this->db->select('*');
			$this->db->from('permission_type_pwd')->where('status',1);
			return $this->db->get()->result();
		}
		public function insert_application_details($application_stack)
		{
			return $this->db->insert('applications_details',$application_stack);
		}
		public function checkDefectLaiblity($pwd_app_id)
		{
			$this->db->select('*');
			$this->db->from('road_information_pwd')->where('pwd_app_id',$pwd_app_id)->where('defectlaib_id',0);
			return $this->db->get()->num_rows();
		}
		public function getLastRoleApprovedByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('application_remarks')->where('app_id',$app_id);
			$this->db->order_by("id", "desc")->limit(1);
			return $this->db->get()->row();
		}
		public function getApplicationBYappID($app_id)
		{
			$this->db->select('pa.*, (SELECT company_name FROM company_details WHERE company_id = pa.company_name AND status = 1) company_name, (SELECT company_address FROM company_address WHERE address_id = pa.company_address) company_address');
			$this->db->from('pwd_applications pa')->where('pa.app_id',$app_id);
			return $this->db->get()->row();
		}
		public function insertLatterGenration($insertStack)
		{
			return $this->db->insert('latter_generation',$insertStack);
		}
		public function create_joint_visit($insert_stack = null, $appID = null)
		{
			if(!empty($insert_stack)){
				$updates = $this->db->where('app_id', $appID)->update("joint_visit_extentions", array('is_deleted' => '1'));
				if($updates){
					return $this->db->insert('joint_visit_extentions',$insert_stack);
				}else{
					return false;
				}
			}else{
				return false;
			}
			
		}
		public function getRoadTypeByAppid($app_id)
		{
			$this->db->select('SUM((total_ri_charges + security_deposit + total_gst)) AS grand_total');
			$this->db->from('road_information_pwd')->where('pwd_app_id',$app_id);
			return $this->db->get()->result_array();
		}
		public function getApplicationByID($id)
		{
			$this->db->select('*');
			$this->db->from('pwd_applications')->where('id',$id);
			return $this->db->get()->row();
		}
		public function getClearkByAppID($app_id)
		{
			$sql_string = "SELECT * FROM `users_table` WHERE user_id = (SELECT user_id FROM application_remarks WHERE app_id = ".$app_id." AND is_deleted = 0 AND role_id = 3 LIMIT 1)";
			return $this->db->query($sql_string)->row();
		}
		public function create_extention($insert_stack)
		{
			return $this->db->insert('joint_visit_extentions',$insert_stack);
		}
		public function getLatterByLtrKey($latter_key)
		{
			$this->db->select('*');
			$this->db->from('latter_table')->where('latter_key',$latter_key);
			return $this->db->get()->row();
		}
		public function checkLatterHasSendByAppID($app_id,$lattter_key)
		{
			$this->db->select('latter_generation.*,(SELECT latter_name FROM latter_table WHERE latter_generation.latter_type_id = latter_table.id) AS latter_name');
			$this->db->from('latter_generation')->where('app_id',$app_id)->order_by("latter_generation.letter_id","desc")->limit(1);
			return $this->db->get()->row();	
		}
		public function getExtentionInfoByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('joint_visit_extentions');
			$this->db->where(['app_id'=>$app_id,'type'=>2,'status'=>1]);
			$this->db->order_by("id","desc")->limit(1);
			return $this->db->get()->row();
		}
		public function updateJointVisitExtention($update_stack,$extention_id)
		{
			return $this->db->where('id',$extention_id)->update('joint_visit_extentions',$update_stack);
		}

		public function getRoadTotalLength($tableid = null){
			$res = $this->db->query("SELECT SUM(total_length) length FROM `road_information_pwd` WHERE pwd_app_id = '".$tableid."'")->result_array();
			if(!empty($res)){
				return $res;
			}else{
				return false;
			}
		}

		public function createPayment($insertStack)
		{
			return $this->db->insert('payment',$insertStack);
		}
		public function getPaymentInfoByAppID($app_ai_id)
		{
			$this->db->select('*');
			$this->db->from('payment')->where(['app_id' => $app_ai_id,'dept_id' => 1,'approved_by' => 0,'is_deleted' => 0,'status' => 1]);
			return $this->db->get()->row();
		}
		public function update_payment($update_stack,$payment_id)
		{
			return $this->db->where('pay_id',$payment_id)->update('payment',$update_stack);
		}

		public function getExtensionId($tableId = null){
			$this->db->select('*');
			$this->db->from('joint_visit_extentions');
			$this->db->where(['app_id'=>$tableId,'type'=>2,'status'=>2]);
			$this->db->order_by("id","desc")->limit(1);

			return $this->db->get()->row();
		}
		public function getLastRoleByDeptID($dept_id)
		{
			$this->db->select('*');
			$this->db->from('permission_access');
			$this->db->where(['dept_id'=>$dept_id,'status'=>1]);
			$this->db->order_by("access_id","desc")->limit(1);
			return $this->db->get()->row();
		}

		public function getApprovalRoleId(){
		    return $res = $this->db->query("SELECT * FROM `permission_access` WHERE dept_id = '1' and status = '1' AND payable_status = '1'")->result_array();
		}
		public function getaddlength($tableId = null){
			return $res = $this->db->query("SELECT * FROM `joint_visit_extentions` WHERE type = '1' AND app_id = '$tableId' AND is_deleted = '0'")->result_array();
		}
		public function getAppByRefnoAppID($postStack)
		{
			$this->db->select('*');
			$this->db->from('pwd_applications');
			$this->db->where(['id'=>$postStack['ref_no_application_id'],'reference_no'=>$postStack['js_ref_number']]);
			return $this->db->get()->row();
		}
		public function getJointVisitByRefrence($refrence_number)
		{
			$this->db->select('pa.*,(SELECT length FROM joint_visit_extentions jve WHERE jve.app_id = pa.app_id AND jve.is_deleted = 0 ORDER BY jve.id DESC LIMIT 1) AS joint_visit_length');
			$this->db->from('pwd_applications pa');
			$this->db->where('pa.reference_no',$refrence_number);
			$this->db->order_by("pa.id","desc")->limit(1);
			return $this->db->get()->row();	
		}
		public function getJointVisitByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('joint_visit_extentions');
			$this->db->where(['app_id'=>$app_id,'type'=>1,'status'=>1]);
			$this->db->order_by("id","desc")->limit(1);
			return $this->db->get()->row();
		}
		public function getApprovedJointVisitByAppID($app_id)
		{
			$this->db->select('*');
			$this->db->from('joint_visit_extentions');
			$this->db->where(['app_id'=>$app_id,'type'=>1,'status'=>2]);
			$this->db->order_by("id","desc")->limit(1);
			return $this->db->get()->row();
		}
	}
?>