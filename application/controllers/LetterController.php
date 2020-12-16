<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	require APPPATH . 'controllers/Common.php';
	class LetterController extends Common{

		public $user_id;
		public $dept_id;
		public $role_id;


		public function index(){
			echo "test";
		}

		public function translationMarathi($tranData){
			//api on my email
			//1500 per month for this api
			$transData = str_replace(" ", "%20", $tranData);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://google-translate1.p.rapidapi.com/language/translate/v2",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "source=en&q=".$tranData."&target=mr",
				CURLOPT_HTTPHEADER => array(
					"accept-encoding: application/gzip",
					"content-type: application/x-www-form-urlencoded",
					"x-rapidapi-host: google-translate1.p.rapidapi.com",
					"x-rapidapi-key: rjw4n4bBqnmshyTIQb9MgbpPYNZ2p1wLNSZjsndH8ApMmcsYCK"
				),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				echo "cURL Error #:" . $err;
			} else {
				// echo $response;
				$resData = json_decode($response, true);
				return $resData["data"]["translations"][0]['translatedText'];
			}
		}

		public function permission_letter(){
			$tableId = base64_decode($this->uri->segment(3));
			$appId = base64_decode($this->uri->segment(4));
			$getAppData = $this->pwd_applications_table->getApplicationBYappID($appId);
			$data['appData'] = $getAppData;
            $data['latter_generation'] = $this->pwd_applications_table->checkLatterHasSendByAppID($appId,'permission_letter');
			$data['company_name'] = $getAppData->company_name;
			$data['company_address'] = $getAppData->company_address;
			$data['road_data'] = $this->pwd_applications_table->getPwdRoadType($tableId);
			
			$this->load->view('letters/permission_letter', $data);
		}

		public function extension_letter(){
			
			 $tableId = base64_decode($this->uri->segment(3));
			 $appId = base64_decode($this->uri->segment(4));

			$getAppData = $this->pwd_applications_table->getApplicationBYappID($appId);
			$data['appData'] = $getAppData;
			$data['extensionData'] = $this->pwd_applications_table->getExtensionId($tableId);
			$this->load->view('letters/extension_letter', $data);
		}

		public function jointvisit_letter(){
			$tableId = base64_decode($this->uri->segment(3));
			$appId = base64_decode($this->uri->segment(4));

			$getAppData = $this->pwd_applications_table->getApplicationBYappID($appId);

			$data['joint_visit'] = $this->pwd_applications_table->getApprovedJointVisitByAppID($getAppData->app_id);
			
			$data['appData'] = $getAppData;

			$totalData = $this->pwd_applications_table->getRoadTotalLength($tableId);
			$data['totallength'] = $totalData;

			$addLength = $this->pwd_applications_table->getaddlength($appId);
			$data['addlength'] = $addLength;
			
			$this->load->view('letters/jointvisit_letter', $data);
		}
		public function medical_certificate()
		{
			$app_id = base64_decode($this->input->get('app_id'));
			$application = $this->hospital_applications_table->getApplicationByAppID($app_id);
			if (!empty($application)) {
				$this->data['application'] = $application;
				$this->data['finalApprovelDate'] = $this->hospital_applications_table->getFinalApprovelDate($application->app_id,5);
				$this->data['inspection'] = $this->hospital_applications_table->getInspectionDataByAppID($application->app_id);
				if($application->application_type == 1) :
				    $this->load->view('letters/medical_certificate',$this->data);
				else : 
				    $this->load->view('letters/medical_certificate_renewal',$this->data);
				endif ;
			} else {
				return redirect('login');
			}
		}
		public function medical_certificate_for_clinic()
		{
			$app_id = base64_decode($this->input->get('app_id'));
			$application = $this->clinic_applications_table->getApplicationByAppID($app_id);
			if (!empty($application)) {


				$application_images = $this->clinic_applications_table->getImageByApplication($application);
				$this->data['application'] = $application;
				$this->data['finalApprovelDate'] = $this->clinic_applications_table->getFinalApprovelDate($application->app_id,5);
				$this->data['appimages'] = image_formate_in_array_clinic($application_images,$application);	
				$this->data['inspection'] = $this->clinic_applications_table->getInspectionDataByAppID($application->app_id);



				if($application->application_type == 1) :
				    $this->load->view('letters/clinic_certificate',$this->data);
				else : 
				    $this->load->view('letters/clinic_certificate_renewal',$this->data);
				endif ;
			} else {
				return redirect('login');
			}
		}
		public function medical_certificate_for_lab()
		{
			$app_id = base64_decode($this->input->get('app_id'));
			$application = $this->lab_applications_table->getApplicationByAppID($app_id);
			if (!empty($application)) {
				$this->data['application'] = $application;

				$application_images = $this->lab_applications_table->getImageByApplication($application);
				$this->data['finalApprovelDate'] = $this->lab_applications_table->getFinalApprovelDate($application->app_id,5);
				$this->data['appimages'] = image_formate_in_array_clinic($application_images,$application);
				$this->data['inspection'] = $this->clinic_applications_table->getInspectionDataByAppID($application->app_id);

				if($application->application_type == 1) :
				    $this->load->view('letters/lab_certificate',$this->data);
				else : 
				    $this->load->view('letters/lab_certificate_renewal',$this->data);
				endif ;
			} else {
				return redirect('login');
			}
		}
		public function madap_license()
		{
			$app_id = base64_decode($this->input->get('app_id'));
			$application = $this->mandap_applications_table->getApplicationByAppID($app_id);
			if (!empty($application)) {
				$this->data['application'] = $application;
				$this->load->view('letters/madap_license',$this->data);
			} else {
				return redirect('login');
			}
			
		}
		
	}

?>
