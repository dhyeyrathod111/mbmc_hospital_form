<?php

	Class Dashboard_data extends CI_Model{
		
		function __construct(){
			if(date('m') > '3'){
				$endDate = date('Y').'-03-31';
			}else{
				$endDate = date('Y-m-d');
			}

			$startDate = (date("Y") - 1).'-04-01';

			$this->startDate = $startDate;
			$this->endDate = $endDate;
		}

		function getUpperData($role_id = null, $dept_id = null){
			if($this->authorised_user['is_superadmin'] == '1'){
				//for superadmin
				$data['dailyRequest'] = 0;
				$data['approvalPending'] = 0;
				$data['approvedForYear'] = 0;
				$data['unapprovedForYear'] = 0;
				$data['totalamountpending'] = 0;
				$data['totalamountcollected'] = 0;

				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = 0;
				}	
				$data['yearlyRequest'] = 0;

				$barGraphArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}
					$lineGraphArray['approvedArray'][] = 0;
					$lineGraphArray['unapprovedArray'][] = 0;
					$lineGraphArray['totalRequest'][] = 0;	
					$barGraphArray['unapproved'][] = 0;
					$barGraphArray['approved'][] = 0;
				}	

				$data['ApprovedUnApproved'] = $barGraphArray;
				$data['lineGraphArray'] = $lineGraphArray;

			}else{
				//not superadmin
				$searchKeyword = $this->db->query("SELECT dept_title FROM `department_table` WHERE dept_id = '".$dept_id."' AND status = '1' AND is_deleted = '0'")->result_array();

				$getTableName = "";
				
				if(strtolower($searchKeyword[0]['dept_title']) != 'medical'){
					//not medical
					switch(strtolower($searchKeyword[0]['dept_title'])){
						case "garden" : 
							$searchKeyword[0]['dept_title'] = "treecuttingapplications";
						break;
					}

					$tableArrayExceptMedical = array("pwd_applications", "treecuttingapplications", "trade_faclicapplication", "godownapplication", "filmdata", "temp_lic", "advertisement_applications", "mandap_applications", "marriage_application");
					
					$getTableName = array_values(preg_grep("/^".strtolower($searchKeyword[0]['dept_title'])."/i", $tableArrayExceptMedical));
					
					$prevrole = $this->db->query("SELECT * FROM `permission_access` WHERE access_id < (SELECT access_id from permission_access WHERE dept_id = '".$dept_id."' AND role_id = '".$role_id."' AND status = '1') AND dept_id = '".$dept_id."' AND status = '1' ORDER BY access_id DESC LIMIT 1")->result_array();

					$finalApprovalRole = $this->db->query("SELECT GROUP_CONCAT(role_id) as role_ids FROM `permission_access` WHERE dept_id = '".$dept_id."' AND payable_status = '1' AND status = '1'")->result_array();

					//get daily request (Total request as per department of todays date)
					$dailyRequestObj = $this->db->query("CALL dailyrequest('".$getTableName[0]."', '".date('Y-m-d')."')");

					mysqli_next_result($this->db->conn_id);

					$dailyRequest = $dailyRequestObj->result_array();
					$data['dailyRequest'] = $dailyRequest[0]['totalRequest'];
					
					//get approval pending (Total pending request as per role id till date)
					$pendingRequestData = "";
					if(!empty($prevrole)){
						$pendingRequestData = $this->db->query("CALL pendingapprovals(".$prevrole[0]['role_id'].", ".$dept_id.", ".$role_id.", '".date('Y-m-d')."', '".$getTableName[0]."')");
					}else{
						$pendingRequestData = $this->db->query("CALL pendingapprovals(0, ".$dept_id.", ".$role_id.", '".date('Y-m-d')."', '".$getTableName[0]."')");
					}

					//select app_id, role_id from application_remarks where id IN (select max(id) from application_remarks where dept_id = '1' and MONTH(created_at) = '1' group by app_id) and role_id = 'prev' and dept_id = '';

					mysqli_next_result($this->db->conn_id);

					$pendingRequest = $pendingRequestData->result_array();

					$data['approvalPending'] = $pendingRequest[0]['total_count'];

					//Graph data file closed count in last 6 year
					$tRequestArray = array();
					for($i = 6; $i >= 0; $i--){
						$year = date("Y") - $i;
						//SELECT COUNT(*) total FROM `pwd_applications` WHERE status = '1' AND is_deleted = '0' AND YEAR(created_at) = '".$year."'
						$totalRequestObj = $this->db->query("CALL getyearlydata('".$getTableName[0]."', '".$year."')");
						mysqli_next_result($this->db->conn_id);
						$totalRequest = $totalRequestObj->result_array();

						$tRequestArray['labels'][] = $year;
						$tRequestArray['data'][] = $totalRequest[0]['total_count'];
					}

					$data['yearlyRequest'] = $tRequestArray;
					//END Graph data for last 6 year

					//Graph Data for approved and unapproved yearly pie chart
					//Approved
					$totalApprovedPieDataObj = $this->db->query("CALL approvedYearlyData('".$getTableName[0]."', '".date('Y')."', '".$dept_id."', '".$role_id."')");
					mysqli_next_result($this->db->conn_id);
					$totalApprovedPieData = $totalApprovedPieDataObj->result_array();
					//END Approved

					//Unapproved
					if(!empty($prevrole)){
					
						$totalUnApprovedPieDataObj = $this->db->query("CALL unapprovedYearlyData('".$prevrole[0]['role_id']."','".$getTableName[0]."', '".$dept_id."', '".$role_id."', '".date('Y')."')");
						mysqli_next_result($this->db->conn_id);
						$totalUnApprovedPieData = $totalUnApprovedPieDataObj->result_array();

					}else{
						
						$totalUnApprovedPieDataObj = $this->db->query("CALL unapprovedYearlyData('0','".$getTableName[0]."', '".$dept_id."', '".$role_id."', '".date('Y')."')");
						mysqli_next_result($this->db->conn_id);
						$totalUnApprovedPieData = $totalUnApprovedPieDataObj->result_array();

					}

					$data['approvedForYear'] = $totalApprovedPieData[0]['total_count'];
					$data['unapprovedForYear'] = $totalUnApprovedPieData[0]['total_count'];
					//END Unapproved

					//END approved and unapproved pie chart

					//Get approved and unapproved  

					//end get approval pending (Total pending request as per role id till date)
					if(strtolower($searchKeyword[0]['dept_title']) == 'pwd'){
						//pwd amount
						//total amount pending (till date)
						$getPendingAmountObj = $this->db->query("CALL totalpendingamountpwd(".$finalApprovalRole[0]['role_ids'].")");

						mysqli_next_result($this->db->conn_id);
						
						$getPendingAmount = $getPendingAmountObj->result_array();

						$data['totalamountpending'] = $getPendingAmount[0]['ttl_amnt'];
						
						//end total amount pending

						//total amount collected
						$getTotalAmountCollectedObj = $this->db->query("CALL totalamountcollectedpwd(".$finalApprovalRole[0]['role_ids'].", '".date("Y-m-d")."')");

						mysqli_next_result($this->db->conn_id);

						$getAmountCollected = $getTotalAmountCollectedObj->result_array();

						$data['totalamountcollected'] = $getAmountCollected[0]['ttl_amont'];
						//end total amount colleted
						//end pwd amount

						//graph Data
							//Monthwise yearly data
							$barGraphArray = array();
							$lineGraphArray = array();
							for($i = 1; $i <= 12; $i++){
								if(strlen((string)$i) == 1){
									$i = '0'.$i;
								}

								//total request
								$gettotalrequestObj = $this->db->query("CALL gettotalreqmonth('".$i."', '".$getTableName[0]."')");

								mysqli_next_result($this->db->conn_id);

								$gettotalmonthrequest = $gettotalrequestObj->result_array();
								//End total request

								//total approved by particular role
								$gettotalapprovedObj = $this->db->query("CALL totalapprovedByroleinmonth('".$i."','".$getTableName[0]."', '".$role_id."', '".$dept_id."')");

								mysqli_next_result($this->db->conn_id);

								$gettotalapprovedreq = $gettotalapprovedObj->result_array();
								//end total approved

								//total unapproved
								
								$previousroleid = (!empty($prevrole)) ? $prevrole[0]['role_id'] : '0';
								$gettotalunapprovedObj = $this->db->query("CALL totalunapprovedByroleinmonth('".$i."','".$getTableName[0]."', '".$role_id."', '".$dept_id."', '".$previousroleid."')");

								mysqli_next_result($this->db->conn_id);

								$gettotalunapprovedreq = $gettotalunapprovedObj->result_array();
								//end total unapproved

								$lineGraphArray['approvedArray'][] = $gettotalapprovedreq[0]['total_count'];
								$lineGraphArray['unapprovedArray'][] = $gettotalunapprovedreq[0]['total_count'];
								$lineGraphArray['totalRequest'][] = $gettotalmonthrequest[0]['total_count'];	
								$barGraphArray['unapproved'][] = $gettotalunapprovedreq[0]['total_count'];
								$barGraphArray['approved'][] = $gettotalapprovedreq[0]['total_count'];
							}

							$data['ApprovedUnApproved'] = $barGraphArray;
							$data['lineGraphArray'] = $lineGraphArray;
							//END MOnthwise yearly data
							
						//END Graph Data

						return $data;
				}
			}else{
				//if medical

				$prevrole = $this->db->query("SELECT * FROM `permission_access` WHERE access_id < (SELECT access_id from permission_access WHERE dept_id = '".$dept_id."' AND role_id = '".$role_id."' AND status = '1') AND dept_id = '".$dept_id."' AND status = '1' ORDER BY access_id DESC LIMIT 1")->result_array();

				$previousroleid = (!empty($prevrole[0])) ? $prevrole[0]['role_id'] : 0 ;


				$dailyRequest = $this->getDailyRequestCountOFMedical();
				$data['dailyRequest'] = $dailyRequest->total_dailyrequest;

				$approvalPending = $this->getApprovelPendingForMedical($previousroleid,$dept_id,$role_id);
				$data['approvalPending'] = $approvalPending->total_pendingrequest;

				$totalamountcollected = $this->totlaAmountCollectedforMedical($dept_id);
				$data['totalamountcollected'] = $totalamountcollected->TotalCollectedAmount;

				$totalamountpending = $this->totlaAmountPendingforMedical($dept_id);
				$data['totalamountpending'] = $totalamountpending->TotalPendingAmount;

				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $this->getYearlyReqeustforMedical($year)->total_yearlyreqeust;
				}
				$data['yearlyRequest'] = $tRequestArray;

				$approvedReqeustFortheyear = $this->approvedReqeustForTheYear($dept_id,$role_id);
				$data['approvedForYear'] = $approvedReqeustFortheyear->total_approve_req_foryear;


				$unapprovedReqeustFortheyear = $this->unapprovedReqeustForTheYear($previousroleid,$dept_id,$role_id);
				$data['unapprovedForYear'] = $unapprovedReqeustFortheyear->total_unapprove_req_foryear;




				$barGraphArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					//total request
					$gettotalrequestObj['hospital_gettotalrequestObj'] = $this->db->query("CALL gettotalreqmonth('".$i."', 'hospital_applications')")->row()->total_count;mysqli_next_result($this->db->conn_id);
					$gettotalrequestObj['clinic_gettotalrequestObj'] = $this->db->query("CALL gettotalreqmonth('".$i."', 'clinic_applications')")->row()->total_count;mysqli_next_result($this->db->conn_id);
					$gettotalrequestObj['lab_gettotalrequestObj'] = $this->db->query("CALL gettotalreqmonth('".$i."', 'lab_applications')")->row()->total_count;mysqli_next_result($this->db->conn_id);
					$gettotalrequestObj['total_gettotalrequestObj'] = $gettotalrequestObj['hospital_gettotalrequestObj'] + $gettotalrequestObj['clinic_gettotalrequestObj'] + $gettotalrequestObj['lab_gettotalrequestObj'];


					//End total request

					//total approved by particular role

					$gettotalapprovedObj['hospital_gettotalapprovedObj'] = $this->db->query("CALL totalapprovedByroleinmonth('".$i."','hospital_applications', '".$role_id."', '".$dept_id."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

					$gettotalapprovedObj['clinic_gettotalapprovedObj'] = $this->db->query("CALL totalapprovedByroleinmonth('".$i."','clinic_applications', '".$role_id."', '".$dept_id."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

					$gettotalapprovedObj['lab_gettotalapprovedObj'] = $this->db->query("CALL totalapprovedByroleinmonth('".$i."','lab_applications', '".$role_id."', '".$dept_id."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

					$gettotalapprovedObj['total_gettotalapprovedObj'] = $gettotalapprovedObj['hospital_gettotalapprovedObj'] + $gettotalapprovedObj['clinic_gettotalapprovedObj'] + $gettotalapprovedObj['lab_gettotalapprovedObj'];



					$previousroleid = (!empty($prevrole)) ? $prevrole[0]['role_id'] : '0';


					$gettotalunapprovedObj['hospital_gettotalunapprovedObj'] = $this->db->query("CALL totalunapprovedByroleinmonth('".$i."','hospital_applications', '".$role_id."', '".$dept_id."', '".$previousroleid."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

					$gettotalunapprovedObj['clinic_gettotalunapprovedObj'] = $this->db->query("CALL totalunapprovedByroleinmonth('".$i."','clinic_applications', '".$role_id."', '".$dept_id."', '".$previousroleid."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

					$gettotalunapprovedObj['lab_gettotalunapprovedObj'] = $this->db->query("CALL totalunapprovedByroleinmonth('".$i."','lab_applications', '".$role_id."', '".$dept_id."', '".$previousroleid."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

					$gettotalunapprovedObj['total_gettotalunapprovedObj'] = $gettotalunapprovedObj['hospital_gettotalunapprovedObj'] + $gettotalunapprovedObj['clinic_gettotalunapprovedObj'] + $gettotalunapprovedObj['lab_gettotalunapprovedObj'];


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
			}
		}
		public function getDailyRequestCountOFMedical()
		{
			$dailyrequest['hospital_dailyrequest'] = $this->db->query("CALL dailyrequest('".'hospital_applications'."', '".date('Y-m-d')."')")->row()->totalRequest;
			mysqli_next_result($this->db->conn_id);
			$dailyrequest['clinic_dailyrequest'] = $this->db->query("CALL dailyrequest('".'clinic_applications'."', '".date('Y-m-d')."')")->row()->totalRequest;
			mysqli_next_result($this->db->conn_id);
			$dailyrequest['lab_dailyrequest'] = $this->db->query("CALL dailyrequest('".'lab_applications'."', '".date('Y-m-d')."')")->row()->totalRequest;
			mysqli_next_result($this->db->conn_id);

			// echo "<pre>";print_r($dailyrequest);exit();


			$dailyrequest['total_dailyrequest'] = $dailyrequest['hospital_dailyrequest'] + $dailyrequest['clinic_dailyrequest'] + $dailyrequest['lab_dailyrequest'];
			return json_decode(json_encode($dailyrequest));
		}
		public function getApprovelPendingForMedical($prevrole = 0 , $dept_id , $role_id)
		{
			$roleStacJuniorDoctor = $this->hospital_applications_table->getRoleByName('junior doctor');

			// echo "<pre>";print_r($roleStacJuniorDoctor);exit();

			$pendingrequest['hospital_pendingrequest'] = $this->db->query("CALL pendingapprovals(".$prevrole.", ".$dept_id.", ".$role_id.", '".date('Y-m-d')."', '".'hospital_applications'."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
			$hospital_readyforPaymentapps = $this->db->query('SELECT COUNT(*) AS readyforPayment FROM `hospital_applications` WHERE app_id NOT IN (SELECT app_id FROM payment) AND status IN (81,82)')->row()->readyforPayment;


			$pendingrequest['clinic_pendingrequest'] = $this->db->query("CALL pendingapprovals(".$prevrole.", ".$dept_id.", ".$role_id.", '".date('Y-m-d')."', '".'clinic_applications'."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
			$clinic_readyforPaymentapps = $this->db->query('SELECT COUNT(*) AS readyforPayment FROM `clinic_applications` WHERE app_id NOT IN (SELECT app_id FROM payment) AND status IN (81,82)')->row()->readyforPayment;


			$pendingrequest['lab_pendingrequest'] = $this->db->query("CALL pendingapprovals(".$prevrole.", ".$dept_id.", ".$role_id.", '".date('Y-m-d')."', '".'lab_applications'."')")->row()->total_count;mysqli_next_result($this->db->conn_id);
			$lab_readyforPaymentapps = $this->db->query('SELECT COUNT(*) AS readyforPayment FROM `lab_applications` WHERE app_id NOT IN (SELECT app_id FROM payment) AND status IN (81,82)')->row()->readyforPayment;


			if ($this->authorised_user['role_id'] == $roleStacJuniorDoctor->role_id) {
				$total_pendingrequest = $pendingrequest['hospital_pendingrequest'] + $pendingrequest['clinic_pendingrequest'] + $pendingrequest['lab_pendingrequest'];

				$totalreadyforpaymentApps = $hospital_readyforPaymentapps + $clinic_readyforPaymentapps + $lab_readyforPaymentapps;

				$this->db->select('COUNT(*) + '.$totalreadyforpaymentApps.' pendingPaymentReqeust')->from('payment');
				$this->db->where(['dept_id'=>$dept_id,'status'=>1,'is_deleted'=>0]);

				$unpaidApplication = $this->db->get()->row()->pendingPaymentReqeust;

				$pendingrequest['total_pendingrequest'] = $total_pendingrequest - $unpaidApplication;

			} else {
				$pendingrequest['total_pendingrequest'] = $pendingrequest['hospital_pendingrequest'] + $pendingrequest['clinic_pendingrequest'] + $pendingrequest['lab_pendingrequest'];
			}

			return json_decode(json_encode($pendingrequest));
		}
		public function totlaAmountCollectedforMedical($dept_id)
		{
			$this->db->select('SUM(amount) AS TotalCollectedAmount');
			$this->db->from('payment');
			$this->db->where(['dept_id'=>$dept_id,'status'=>2,'is_deleted'=>0]);
			// $this->db->where_in('status',['2','4']);
			$this->db->where('DATE(created_at)',date('Y-m-d'));
			return $this->db->get()->row();
		}
		public function totlaAmountPendingforMedical($dept_id)
		{
			$this->db->select('SUM(amount) AS TotalPendingAmount');
			$this->db->from('payment');
			$this->db->where(['dept_id'=>$dept_id,'status'=>1,'is_deleted'=>0]);

			return $this->db->get()->row();
		}
		public function getYearlyReqeustforMedical($year)
		{

			$roleStacJuniorDoctor = $this->hospital_applications_table->getRoleByName('junior doctor');

			$final_role_id = $this->db->query("SELECT role_id FROM permission_access where dept_id = 5 AND status = 1 ORDER BY access_id DESC LIMIT 1")->row()->role_id;


			$hospital_yearlyreqeust_sql = "SELECT COUNT(*) AS reqeusts_this_year FROM (SELECT ha.id ,ha.app_id , ha.applicant_name , (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ha.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id FROM hospital_applications ha WHERE is_deleted = '0' AND YEAR(ha.created_at) = ".$year.") AS hospital_data WHERE last_approved_role_id = ".$final_role_id;

			$yearlyreqeust['hospital_yearlyreqeust'] = $this->db->query($hospital_yearlyreqeust_sql)->row()->reqeusts_this_year;

			$clinic_yearlyreqeust_sql = "SELECT COUNT(*) AS reqeusts_this_year FROM (SELECT ca.id ,ca.app_id , ca.applicant_name , (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = ca.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id FROM clinic_applications ca WHERE is_deleted = '0' AND YEAR(ca.created_at) = $year) AS clinic_data WHERE last_approved_role_id = ".$final_role_id;

			$yearlyreqeust['clinic_yearlyreqeust'] = $this->db->query($clinic_yearlyreqeust_sql)->row()->reqeusts_this_year;

			$lab_yearlyreqeust_sql = "SELECT COUNT(*) AS reqeusts_this_year FROM (SELECT la.id ,la.app_id , la.applicant_name ,  (SELECT role_id FROM application_remarks WHERE application_remarks.app_id = la.app_id ORDER BY id DESC LIMIT 1) AS last_approved_role_id FROM lab_applications la WHERE is_deleted = '0' AND YEAR(la.created_at) = $year) AS lab_data WHERE last_approved_role_id = ".$final_role_id;


			$yearlyreqeust['lab_yearlyreqeust'] = $this->db->query($lab_yearlyreqeust_sql)->row()->reqeusts_this_year;
			$yearlyreqeust['total_yearlyreqeust'] = $yearlyreqeust['hospital_yearlyreqeust'] + $yearlyreqeust['clinic_yearlyreqeust'] + $yearlyreqeust['lab_yearlyreqeust'];
			return json_decode(json_encode($yearlyreqeust));
		}
		public function approvedReqeustForTheYear($dept_id,$role_id)
		{
			$approvereqforyear['hospital_approve_req_foryear'] = $this->db->query("CALL approvedYearlyData('hospital_applications', '".date('Y')."', '".$dept_id."', '".$role_id."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

			$approvereqforyear['clinic_approve_req_foryear'] = $this->db->query("CALL approvedYearlyData('clinic_applications', '".date('Y')."', '".$dept_id."', '".$role_id."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

			$approvereqforyear['lab_approve_req_foryear'] = $this->db->query("CALL approvedYearlyData('lab_applications', '".date('Y')."', '".$dept_id."', '".$role_id."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

			$approvereqforyear['total_approve_req_foryear'] = $approvereqforyear['hospital_approve_req_foryear'] + $approvereqforyear['clinic_approve_req_foryear'] + $approvereqforyear['lab_approve_req_foryear'];

			return json_decode(json_encode($approvereqforyear));
		}
		public function unapprovedReqeustForTheYear($prevrole = 0 , $dept_id , $role_id)
		{
			$unapprovereqforyear['hospital_unapprove_req_foryear'] = $this->db->query("CALL unapprovedYearlyData(".$prevrole.",'hospital_applications', '".$dept_id."', '".$role_id."', '".date('Y')."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

			$unapprovereqforyear['clinic_unapprove_req_foryear'] = $this->db->query("CALL unapprovedYearlyData(".$prevrole.",'clinic_applications', '".$dept_id."', '".$role_id."', '".date('Y')."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

			$unapprovereqforyear['lab_unapprove_req_foryear'] = $this->db->query("CALL unapprovedYearlyData(".$prevrole.",'lab_applications', '".$dept_id."', '".$role_id."', '".date('Y')."')")->row()->total_count;mysqli_next_result($this->db->conn_id);

			$unapprovereqforyear['total_unapprove_req_foryear'] = $unapprovereqforyear['hospital_unapprove_req_foryear'] + $unapprovereqforyear['clinic_unapprove_req_foryear'] + $unapprovereqforyear['lab_unapprove_req_foryear'];

			return json_decode(json_encode($unapprovereqforyear));
		}
	} 
?>
