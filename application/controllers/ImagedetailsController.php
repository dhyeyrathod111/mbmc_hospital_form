<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'controllers/Common.php';
class ImagedetailsController extends Common {

	/**
	 * Admin.php
     * @author Vikas Pandey
	 */

    public function __construct() {
        parent::__construct();
    }

    public function getimagedetails() {
        extract($_POST);
        //echo'<pre>';print_r($_POST  );exit;
        if($image_id) {
            $image_id = explode(',', $image_id);
            // $image_data = array();
            if(is_array($image_id)) {
                foreach ($image_id as $key => $value) {
                    // echo'<pre>';print_r($value);//exit;
                    $result = $this->image_details_table->getImageDetailsById($value);
                    // echo'<pre>';print_r($result);//  exit;
                    $image_data[] = array(
                        'image_id' => $result['image_id'],
                        'image_name' => $result['image_name'],
                        'image_path' => $result['image_path']
                    );
                } 
            } else {
                $result = $this->image_details_table->getImageDetailsById($value);
                    // echo'<pre>';print_r($result);//  exit;
                $image_data[] = array(
                    'image_id' => $result['image_id'],
                    'image_name' => $result['image_name'],
                    'image_path' => $result['image_path']
                );
            }

            $data['image_data'] = $image_data;
            $data['status'] = '1';
        } else {
            $data['image_data'] = null;
            $data['status'] = '1';
        }
        
        echo json_encode($data);
    }
    
}
