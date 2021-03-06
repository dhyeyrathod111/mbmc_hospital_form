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

			// $data['company_name'] = $this->translationMarathi($getAppData->company_name);
			// $data['company_address'] = $this->translationMarathi($getAppData->company_address);
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
			
			$getExtensionData = $this->pwd_applications_table->getExtensionId($appId);
			$data['extensionData'] = $getExtensionData;
			
			
			$this->load->view('letters/extension_letter', $data);
		}

		public function jointvisit_letter(){
			$tableId = base64_decode($this->uri->segment(3));
			$appId = base64_decode($this->uri->segment(4));

			$getAppData = $this->pwd_applications_table->getApplicationBYappID($appId);
			$data['appData'] = $getAppData;

			$totalData = $this->pwd_applications_table->getRoadTotalLength($tableId);
			$data['totallength'] = $totalData;

			$addLength = $this->pwd_applications_table->getaddlength($appId);
			$data['addlength'] = $addLength;
			
			$this->load->view('letters/jointvisit_letter', $data);
		}
	}

?>
