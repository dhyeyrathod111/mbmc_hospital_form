<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class RemarksController extends Common {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function get_app_remarks_by_id() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $result = $this->application_remarks_table->getAllRemarksById($app_id);
        // echo'ss<pre>';print_r($result);exit;
        if($result != null) {
        	$data['remarks'] = $result;
        	$data['status'] = '1';
        } else {
        	$data['remarks'] = null;
        	$data['status'] = '2';
        }
        echo  json_encode($data);
        // echo'<pre>';print_r($result);exit;
    }
}
	