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
			$data = array();
			if($dept_id == '1'){
				//pwd
				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalPwd FROM `pwd_applications` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalPwd'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT id) as totalFresh FROM `pwd_applications` pa WHERE pa.id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data
				
				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `pwd_applications` WHERE status = '1' AND is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.id) as totalFresh FROM `pwd_applications` td WHERE td.id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//totol request
					$totalRequest = $this->db->query("SELECT COUNT(*) as totalPwd FROM `pwd_applications` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];
					$lineGraphArray['unapprovedArray'][] = $unApproved;
					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalPwd'];
					//END total request

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.id) as totalFresh FROM `pwd_applications` td WHERE td.id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '3') {
				//Garden	
				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalGarden FROM `gardendata` WHERE date(date_added) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalGarden'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT gd.gardenId) as totalFresh FROM `gardendata` gd WHERE gd.gardenId NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data
				
				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `gardendata` WHERE status = '1' AND is_deleted = '0' AND YEAR(date_added) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.gardenId) as totalFresh FROM `gardendata` td WHERE td.gardenId NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.date_added) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//totol request
					$totalRequest = $this->db->query("SELECT COUNT(*) as totalGarden FROM `gardendata` WHERE MONTH(date_added) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];
					$lineGraphArray['unapprovedArray'][] = $unApproved;
					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalGarden'];
					//END total request

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.gardenId) as totalFresh FROM `gardendata` td WHERE td.gardenId NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.date_added) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '5') {
				//Medical
				$dailyRequestHos = $this->db->query("SELECT COUNT(*) as totalHos FROM `hospital_applications` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0'")->result_array();

				$dailyRequestClinic = $this->db->query("SELECT COUNT(*) as totalClinic FROM `clinic_applications` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0'")->result_array();

				$dailyRequestLabs = $this->db->query("SELECT COUNT(*) as totalLabs FROM `lab_applications` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0'")->result_array();

				$data['dailyRequest'] = $dailyRequestHos[0]['totalHos'] + $dailyRequestClinic[0]['totalClinic'] + $dailyRequestLabs[0]['totalLabs'];

				$approvalPendingFreshHos = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshHos FROM `hospital_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '1')")->result_array();

				$approvalPendingFreshClinic = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshClinic FROM `clinic_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '2')")->result_array();

				$approvalPendingFreshLabs = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshLabs FROM `lab_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '3')")->result_array();

				$approvalPendingFreshCnt = $approvalPendingFreshHos[0]['totalFreshHos'] + $approvalPendingFreshClinic[0]['totalFreshClinic'] + $approvalPendingFreshLabs[0]['totalFreshLabs'];

				$approvalPendingFromCommHos = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommHos FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '1') AND dept_id = '".$dept_id."' AND sub_dept_id = '1'")->result_array();

				$approvalPendingFromCommClinic = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommClinic FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '2') AND dept_id = '".$dept_id."' AND sub_dept_id = '2'")->result_array();

				$approvalPendingFromCommLabs = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommLabs FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '3') AND dept_id = '".$dept_id."' AND sub_dept_id = '3'")->result_array();

				$approvalPendingCommCnt = $approvalPendingFromCommHos[0]['totalCommHos'] + $approvalPendingFromCommClinic[0]['totalCommClinic'] + $approvalPendingFromCommLabs[0]['totalCommLabs'];

				$approvalPending = $approvalPendingFreshCnt + $approvalPendingCommCnt;

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];

				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequestHos = $this->db->query("SELECT COUNT(*) totalHos FROM `hospital_applications` WHERE is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();

					$totalRequestClinic = $this->db->query("SELECT COUNT(*) totalClinic FROM `clinic_applications` WHERE is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();

					$totalRequestLabs = $this->db->query("SELECT COUNT(*) totalLab FROM `lab_applications` WHERE is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();

					$totalReq = $totalRequestHos[0]['totalHos'] + $totalRequestClinic[0]['totalClinic'] + $totalRequestLabs[0]['totalLab'];

					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalReq;
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved

				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approvedHos = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommHos FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '1') AND dept_id = '".$dept_id."' AND sub_dept_id = '1' AND MONTH(created_at) = '".$i."'")->result_array();

					$approvedClinic = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommClinic FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '2') AND dept_id = '".$dept_id."' AND sub_dept_id = '3' AND MONTH(created_at) = '".$i."'")->result_array();

					$approvedLabs = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommLabs FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '3') AND dept_id = '".$dept_id."' AND sub_dept_id = '3' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approvedHos[0]['totalCommHos'] + $approvedClinic[0]['totalCommClinic'] + $approvedLabs[0]['totalCommLabs'];

					$approvalPending1Hos = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshHos FROM `hospital_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '1') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPending1Clinic = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshClinic FROM `clinic_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '2') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPending1Labs = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshLabs FROM `lab_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '3') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPendingFreshCount = $approvalPending1Hos[0]['totalFreshHos'] + $approvalPending1Clinic[0]['totalFreshClinic'] + $approvalPending1Labs[0]['totalFreshLabs'];

					$approvalPending2Hos = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommHos FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '1') AND dept_id = '".$dept_id."' AND sub_dept_id = '1' AND MONTH(created_at) = '".$i."'")->result_array();

					$approvalPending2Clinic = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommClinic FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '2') AND dept_id = '".$dept_id."' AND sub_dept_id = '2' AND MONTH(created_at) = '".$i."'")->result_array();

					$approvalPending2Labs = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommLabs FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '3') AND dept_id = '".$dept_id."' AND sub_dept_id = '3' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPendingFreshCount + $approvalPending2Hos[0]['totalCommHos'] + $approvalPending2Clinic[0]['totalCommClinic'] + $approvalPending2Labs[0]['totalCommLabs'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//total Request

					$dailyRequestHos = $this->db->query("SELECT COUNT(*) as totalHos FROM `hospital_applications` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0'")->result_array();

					$dailyRequestClinic = $this->db->query("SELECT COUNT(*) as totalClinic FROM `clinic_applications` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0'")->result_array();

					$dailyRequestLabs = $this->db->query("SELECT COUNT(*) as totalLabs FROM `lab_applications` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0'")->result_array();

					$lineGraphArray['approvedArray'][] = $approvedHos[0]['totalCommHos'] + $approvedClinic[0]['totalCommClinic'] + $approvedLabs[0]['totalCommLabs'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $dailyRequestHos[0]['totalHos'] + $dailyRequestClinic[0]['totalClinic'] + $dailyRequestLabs[0]['totalLabs'];

					//End total Request

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;

				//End Approved And Unapproved

				//request status for year
				$approvedForYearHos = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommHos FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '1') AND dept_id = '".$dept_id."' AND sub_dept_id = '1' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvedForYearClinic = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommClinic FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '2') AND dept_id = '".$dept_id."' AND sub_dept_id = '2' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvedForYearLabs = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommLabs FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '3') AND dept_id = '".$dept_id."' AND sub_dept_id = '3' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvedForYearCount = $approvedForYearHos[0]['totalCommHos'] + $approvedForYearClinic[0]['totalCommClinic'] + $approvedForYearLabs[0]['totalCommLabs'];

				$approvalPendingYear1Hos = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshHos FROM `hospital_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '1') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1Clinic = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshClinic FROM `clinic_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '2') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1Labs = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFreshLabs FROM `lab_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."' AND sub_dept_id = '3') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1Count = $approvalPendingYear1Hos[0]['totalFreshHos'] + $approvalPendingYear1Clinic[0]['totalFreshClinic'] + $approvalPendingYear1Labs[0]['totalFreshLabs'];

				$approvalPendingYear2Hos = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommHos FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '1') AND dept_id = '".$dept_id."' AND sub_dept_id = '1' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear2Clinic = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommClinic FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '2') AND dept_id = '".$dept_id."' AND sub_dept_id = '2' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear2Labs = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalCommLabs FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."' AND sub_dept_id = '3') AND dept_id = '".$dept_id."' AND sub_dept_id = '3' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1Count + $approvalPendingYear2Hos[0]['totalCommHos'] + $approvalPendingYear2Clinic[0]['totalCommClinic'] + $approvalPendingYear2Labs[0]['totalCommLabs'];

				$data['approvedForYear'] = $approvedForYearCount;
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif($dept_id == '6'){
				//Hall

				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalHall FROM `hall_applications` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalHall'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `hall_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `hall_applications` WHERE is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `hall_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//total request
					$totalRequest = $this->db->query("SELECT COUNT(*) as totalHall FROM `hall_applications` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalHall'];

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `hall_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '7') {
				//trade

				$dailyRequest = $this->db->query("SELECT COUNT(*) as totaltrade FROM `trade_faclicapplication` WHERE date(application_date) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totaltrade'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.tradefac_lic_id) as totalFresh FROM `trade_faclicapplication` td WHERE td.tradefac_lic_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `trade_faclicapplication` WHERE status = '1' AND is_deleted = '0' AND YEAR(application_date) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.tradefac_lic_id) as totalFresh FROM `trade_faclicapplication` td WHERE td.tradefac_lic_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.application_date) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//total request
					$totalRequest = $this->db->query("SELECT COUNT(*) as totaltrade FROM `trade_faclicapplication` WHERE MONTH(application_date) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totaltrade'];
					//end total request

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.tradefac_lic_id) as totalFresh FROM `trade_faclicapplication` td WHERE td.tradefac_lic_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.application_date) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '8') {
				//godown

				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalGodown FROM `godownapplication` WHERE date(date_added) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalGodown'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.godown_id) as totalFresh FROM `godownapplication` td WHERE td.godown_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `godownapplication` WHERE status = '1' AND is_deleted = '0' AND YEAR(date_added) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.godown_id) as totalFresh FROM `godownapplication` td WHERE td.godown_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.date_added) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//total request
					$totalRequest = $this->db->query("SELECT COUNT(*) as totalGodown FROM `godownapplication` WHERE MONTH(date_added) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalGodown'];
					//end total request

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.godown_id) as totalFresh FROM `godownapplication` td WHERE td.godown_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.date_added) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '9') {
				//film
				
				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalFilm FROM `filmdata` WHERE date(date_added) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalFilm'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.film_id) as totalFresh FROM `filmdata` td WHERE td.film_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `filmdata` WHERE status = '1' AND is_deleted = '0' AND YEAR(date_added) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.film_id) as totalFresh FROM `filmdata` td WHERE td.film_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.date_added) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					//total request
					$dailyRequest = $this->db->query("SELECT COUNT(*) as totalFilm FROM `filmdata` WHERE MONTH(date_added) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalFilm'];
					//total request

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.film_id) as totalFresh FROM `filmdata` td WHERE td.film_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.date_added) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '10') {
				//templic

				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalTemp FROM `temp_lic` WHERE date(udated_date) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalTemp'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.lic_id) as totalFresh FROM `temp_lic` td WHERE td.lic_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `temp_lic` WHERE status = '1' AND is_deleted = '0' AND YEAR(udated_date) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.lic_id) as totalFresh FROM `temp_lic` td WHERE td.lic_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.udated_date) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$aRequestArray['unapprovedArray'][] = $unApproved;

					$dailyRequest = $this->db->query("SELECT COUNT(*) as totalTemp FROM `temp_lic` WHERE MONTH(udated_date) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalTemp'];

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$datap['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.lic_id) as totalFresh FROM `temp_lic` td WHERE td.lic_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.udated_date) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '11') {
				//advertisement

				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalAdv FROM `advertisement_applications` WHERE date(application_date) = '".date('Y-m-d')."' AND is_deleted = '0' AND status = '1'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalAdv'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.adv_id) as totalFresh FROM `advertisement_applications` td WHERE td.adv_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `advertisement_applications` WHERE status = '1' AND is_deleted = '0' AND YEAR(application_date) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.adv_id) as totalFresh FROM `advertisement_applications` td WHERE td.adv_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.application_date) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$dailyRequest = $this->db->query("SELECT COUNT(*) as totalAdv FROM `advertisement_applications` WHERE MONTH(application_date) = '".$i."' AND is_deleted = '0' AND status = '1'")->result_array();

					$aRequestArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['total'];

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.adv_id) as totalFresh FROM `advertisement_applications` td WHERE td.adv_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.application_date) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year

			}elseif ($dept_id == '12') {
				//mandap

				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalMan FROM `mandap_applications` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalMan'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `mandap_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `mandap_applications` WHERE is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `mandap_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$dailyRequest = $this->db->query("SELECT COUNT(*) as totalAdv FROM `mandap_applications` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0'")->result_array();

					$aRequestArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['totalAdv'];

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `mandap_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year
			}elseif ($dept_id == '13') {
				//Marriage
				$dailyRequest = $this->db->query("SELECT COUNT(*) as totalMar FROM `marriage_application` WHERE date(created_at) = '".date('Y-m-d')."' AND is_deleted = '0'")->result_array();

				$data['dailyRequest'] = $dailyRequest[0]['totalMar'];

				$approvalPendingFresh = $this->db->query("SELECT COUNT(DISTINCT td.id) as totalFresh FROM `marriage_application` td WHERE td.id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."')")->result_array();

				$approvalPendingFromComm = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."'")->result_array();

				$approvalPending = $approvalPendingFresh[0]['totalFresh'] + $approvalPendingFromComm[0]['totalComm'];

				$data['approvalPending'] = $approvalPending;

				$dailyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) = '".date("Y-m-d")."'")->result_array();

				$data['dailyAmount'] = ($dailyAmount[0]['total'] == '') ? '0' : $dailyAmount[0]['total'];
				
				$yearlyAmount = $this->db->query("SELECT SUM(pay.amount) as total FROM `payment` pay INNER JOIN application_remarks ar ON pay.remark_id = ar.id WHERE ar.dept_id = '".$dept_id."' AND ar.is_deleted = '0' AND date(pay.date_added) >= '".$this->startDate."' AND date(pay.date_added) <= '".$this->endDate."'")->result_array();

				$data['yearlyAmount'] = ($yearlyAmount[0]['total'] == '') ? '0' : $yearlyAmount[0]['total'];

				//graph data

				//per year request
				
				$tRequestArray = array();
				for($i = 6; $i >= 0; $i--){
					$year = date("Y") - $i;
					$totalRequest = $this->db->query("SELECT COUNT(*) total FROM `marriage_application` WHERE is_deleted = '0' AND YEAR(created_at) = '".$year."'")->result_array();
					$tRequestArray['labels'][] = $year;
					$tRequestArray['data'][] = $totalRequest[0]['total'];
				}

				$data['yearlyRequest'] = $tRequestArray;

				//End Year Request

				//Approved and Unapproved
				$aRequestArray = array();
				$lineGraphArray = array();
				for($i = 1; $i <= 12; $i++){
					if(strlen((string)$i) == 1){
						$i = '0'.$i;
					}

					$approved = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();
					
					$aRequestArray['approvedArray'][] = $approved[0]['totalComm'];

					$approvalPending1 = $this->db->query("SELECT COUNT(DISTINCT td.app_id) as totalFresh FROM `hall_applications` td WHERE td.app_id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND MONTH(td.created_at) = '".$i."'")->result_array();

					$approvalPending2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND MONTH(created_at) = '".$i."'")->result_array();

					$unApproved = $approvalPending1[0]['totalFresh'] + $approvalPending2[0]['totalComm'];

					$dailyRequest = $this->db->query("SELECT COUNT(*) as totalAdv FROM `marriage_application` WHERE MONTH(created_at) = '".$i."' AND is_deleted = '0'")->result_array();

					$aRequestArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['approvedArray'][] = $approved[0]['totalComm'];

					$lineGraphArray['unapprovedArray'][] = $unApproved;

					$lineGraphArray['totalRequest'][] = $totalRequest[0]['total'];

				}

				$data['ApprovedUnApproved'] = $aRequestArray;
				$data['lineGraphArray'] = $lineGraphArray;
				//End Approved and Unapproved

				//request status for year
				$approvedForYear = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear1 = $this->db->query("SELECT COUNT(DISTINCT td.id) as totalFresh FROM `marriage_application` td WHERE td.id NOT IN (SELECT DISTINCT app_id FROM application_remarks WHERE dept_id = '".$dept_id."') AND YEAR(td.created_at) = '".date('Y')."'")->result_array();

				$approvalPendingYear2 = $this->db->query("SELECT COUNT(DISTINCT app_id) as totalComm FROM application_remarks WHERE app_id NOT IN (SELECT app_id FROM application_remarks WHERE role_id = '13' AND dept_id = '".$dept_id."') AND dept_id = '".$dept_id."' AND YEAR(created_at) = '".date('Y')."'")->result_array();

				$unApprovedForYear = $approvalPendingYear1[0]['totalFresh'] + $approvalPendingYear2[0]['totalComm'];

				$data['approvedForYear'] = $approvedForYear[0]['totalComm'];
				$data['unapprovedForYear'] = $unApprovedForYear;
				//End request status for year
			}

			return $data;
		}
	}
?>
