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
				$tableArrayMedical = array("hospital_applications", "clinic_applications", "lab_applications");
			}
			}
		}
	} 
?>
