<?php
	/*
	Ankit Naik
	Tree Cutting Section
	*/

	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . 'controllers/Common.php';

	class ReportsController extends Common{
		public function index() {
			//get approved data
			// $data['reportData'] = $this->reportData->getData($role_id, $dept_id);
			$this->load->view('applications/reports/index');
		}

		public function getData(){
			$searchVal = $_POST['search']['value'];
			$i = $_POST['start'];
			$rowperpage = $_POST['length']; // Rows display per page
			$columnIndex = $_POST['order'][0]['column']; // Column index
			$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
			$columnSortOrder = $_POST['order'][0]['dir'];

			if($this->input->post("fromDate") != ''){
				$fromDate = date("Y-m-d",strtotime($this->input->post("fromDate")));
			}else{
				$fromDate = "";
			}
			
			if($this->input->post("toDate") != ''){
				$toDate = date("Y-m-d", strtotime($this->input->post("toDate")));
			}else{
				$toDate = "";
			}

			$approval = $this->input->post("approval");

			if($searchVal != ''){
				$appData = $this->ReportData->getData($searchVal, $fromDate, $toDate, $approval, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
			}else{
				$appData = $this->ReportData->getData($searchVal, $fromDate, $toDate, $approval, $i, $rowperpage, $columnIndex, $columnName, $columnSortOrder);
			}

			$data = array();

			if($appData == ''){
				$data[] = array('sr_no' => '', 'application_id' => '', 'department' => '', 'final_approval' => 'No Data Found','remarks' => '','status' => '','approved_date' => '','action' => '');
			}else{
				$sr_no = 1;
				foreach($appData as $key => $valData){

					$action = "<span aria-label = 'Payment' data-microtip-position='top' role='tooltip' for = 'payment' class = 'payment' data-id = '".$valData['id']."' style = 'cursor: pointer; color:blue;'><i class='fa fa-credit-card' aria-hidden='true'></i></span>";

					$data[] = array('sr_no' => $sr_no, 'application_id' => $valData['app_id'], 'department' => $valData['dept_title'], 'final_approval' => $valData['role_title'], 'remarks' => $valData['remarks'], 'status' => ($valData['status']) ? 'Active' : 'Deleted', 'approved_date' => $valData['created_at'], 'action' => $action);
					$sr_no++;
				}
			}

			$output = array(
	            "draw" => $_POST['draw'],
	            "recordsTotal" => count($data),
	            "recordsFiltered" => $this->ReportData->countFiltered($_POST),
	            "data" => $data,
	        );

	        echo json_encode($output);	
		}

		public function payment(){
			$rowId = $this->input->post("rowId");
			$paymentSelect = $this->input->post("paymentSelect");
			$amount = $this->input->post("amount");

			$paymentArray = array(
				'remark_id' => $rowId,
				'payment_selected' => $paymentSelect,
				'amount' => $amount,
				'date_added' => date("Y-m-d H:i:s")
			);

			$res = $this->ReportData->paymentInsert($paymentArray, $rowId);
			if($res){
				$data['success'] = 1;
				$data['message'] = "Payment Success";
			}else{
				$data['success'] = 2;
				$data['message'] = "Payment Failed";
			}

			echo json_encode($data);
		}
	}
?>