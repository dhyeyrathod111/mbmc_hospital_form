<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
Class Dashboard_data extends MY_Model{

	public $startDate, $endDate;
	
	function __construct(){
		if(date('m') > '03'){
			$endDate = date('Y').'-03-31';
		}else{
			$endDate = date('Y-m-d');
		}
		$startDate = (date("Y") - 1).'-04-01';
		$this->startDate = $startDate;
		$this->endDate = $endDate;
	}
	public function getDailyRequestCount($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$this->db->select('*')->from($table_name)->where('created_at > ',date('Y-m-d'));
		return $this->db->get()->num_rows();
	}
	public function getApprovalPendingCount($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$primery_key = $this->get_primery_column_by_tablename($table_name)->Column_name;
		$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT $primery_key) as totalFresh FROM $table_name pa WHERE pa.$primery_key NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$this->authorised_user['dept_id']."')")->result_array();
		$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '".$this->authorised_user['role_id']."' AND dept_id = '".$this->authorised_user['dept_id']."') AND dept_id = '".$this->authorised_user['dept_id']."'")->result_array();
		return $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];
	}
	public function  amountCollectedDaily($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$primery_key = $this->get_primery_column_by_tablename($table_name)->Column_name;
		$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$this->authorised_user['dept_id']."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();
		return ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
	}
	public function amountCollectedYearly($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$primery_key = $this->get_primery_column_by_tablename($table_name)->Column_name;
		$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$this->authorised_user['dept_id']."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();
		return ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];
	}
	public function yearlyRequestgraph($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$primery_key = $this->get_primery_column_by_tablename($table_name)->Column_name;

		$tRequestArray = array();
		for($i = 6; $i >= 0; $i--){
			$year = date("Y") - $i;
			$totalRequest = $this->db->query("SELECT COUNT(*) total FROM $table_name WHERE status = '1' AND is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();
			$tRequestArray['labels'][] = $year;
			$tRequestArray['data'][] = $totalRequest[0]['total'];
		}
		return $tRequestArray;
	}
	public function approvedAndUnapprovedData($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$primery_key = $this->get_primery_column_by_tablename($table_name)->Column_name;

		function getUpperData($role_id = null, $dept_id = null, $is_superadmin = null){
			//department wise tables
			$getTableName = "";
			// $totalDailyRequest = $
			if($is_superadmin == 1){
				//for superadmin
				//incomplete
			}else{
				//for particular role
				$searchKeyword = $this->db->query("SELECT dept_title FROM `department_table` WHERE dept_id = '".$dept_id."' AND status = '1' AND is_deleted = '0'")->result_array();

				if(strtolower($searchKeyword[0]['dept_title']) != 'medical'){
					//not medical
					switch(strtolower($searchKeyword[0]['dept_title'])){
						case "garden" : 
							$searchKeyword[0]['dept_title'] = "tree";
						break;
					}
	
					$tableArrayExceptMedical = array("pwd_applications", "treecuttingapplications", "trade_faclicapplication", "godownapplication", "filmdata", "temp_lic", "advertisement_applications", "mandap_applications", "marriage_application");
	
					$getTableName = preg_grep("/^".strtolower($searchKeyword[0]['dept_title'])."/i", $tableArrayExceptMedical);
	
				}else{
					//if medical
					$tableArrayMedical = array("hospital_applications", "clinic_applications", "lab_applications");
				}
			}
			
			$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

			$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.gardenId) as totalFresh FROM `gardendata` td WHERE td.gardenId NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$this->authorised_user['dept_id']."') AND MONTH(td.date_added) = '".$i."'")->result_array();

			$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$this->authorised_user['dept_id']."') AND dept_id = '".$this->authorised_user['dept_id']."' AND MONTH(created_at) = '".$i."'")->result_array();

			$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

			$aRequestArray['unapprovedArray'][] = $unApproved;
			$totalRequest = $this->db->query("SELECT COUNT(*) as totalGarden FROM `gardendata` WHERE MONTH(date_added) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();
			$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];
			$lineGraphArray['unapprovedArray'][] = $unApproved;
			$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalGarden'];
		}
		$data['ApprovedUnApproved'] = $aRequestArray;
		$data['lineGraphArray'] = $lineGraphArray;
		return $data; 
	}
	public function reqeustStatusForYear($user_details = [])
	{
		$all_dept_data = $this->db->get_where('department_table',['status' => '1','is_deleted'=>'0','dept_id'=>$user_details['dept_id']]);
		$table_name = get_tablename_by_deptid($all_dept_data->row(),$this->authorised_user);
		$primery_key = $this->get_primery_column_by_tablename($table_name)->Column_name;

		$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$this->authorised_user['dept_id']."') AND dept_id = '".$this->authorised_user['dept_id']."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

		$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.$primery_key) as totalFresh FROM $table_name td WHERE td.$primery_key NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$this->authorised_user['dept_id']."') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

		$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$this->authorised_user['dept_id']."') AND dept_id = '".$this->authorised_user['dept_id']."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

		$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];
		$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
		$data['unapprovedForYear'] = $unApprovedForYear;

		return $data;
	}

}
?>	
