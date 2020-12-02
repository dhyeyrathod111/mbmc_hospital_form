<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class QualificationController extends Common {

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
	public function index()
	{
		
		$this->load->view('master/qualification/index');
	}

	public function create() {
        $this->load->view('master/qualification/create');
    }

    public function edit() {

        $qual_id = base64_decode($this->uri->segment(3));

        $result = $this->qualification_master_table->getQualDetailsById($qual_id);

        $data['qual'] = $result;
        // echo'<pre>';print_r($result);exit;
        $this->load->view('master/qualification/edit',$data);
    }

	public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        if($qual_id =='') {
            $qual_title = $this->form_validation
                ->set_rules('qual_title','qualification title','required|is_unique[qualification_master.qual_title]')->run();
            } else {
                $qual_title = $this->form_validation
                            ->set_rules('qual_title','qualification title','required')->run();
            }
        $data['messg'] = '';

        if(!$qual_title) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {
            if($qual_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);
                // echo'<pre>';print_r($data);exit;
                $result = $this->qualification_master_table->insert($data);
                $messg = 'added';
            } else {
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);
                $result = $this->qualification_master_table->update($data,$qual_id);
                $messg = 'updated';
            }
            
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['qual_title'].' '.$messg.' successfully.';
            } else {
                $data['status'] = '2';
                $data['messg'] = 'Oops! Something went wrong.';
            }
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

    public function update() {
        extract($_POST);

        if($status == '1') {
            $update = array(
                'status' => '2',
                'updated_at' => date('Y-m-d H:i:s')
            );
        } else {
            $update = array(
                'status' => '1',
                'updated_at' => date('Y-m-d H:i:s')
            );
        }
        // echo'<pre>';print_r($qual_id);exit;
        $result = $this->qualification_master_table->update($update,$qual_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'qualification updated successfully.';
        } else {
            $messg = 'Oops! Something went wrong.';
            $data['status'] = '2';
            $data['messg'] = $messg;
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

	public function get_list()	{

		$data = $row = array();

        // Fetch member's records
        $qualList = $this->qualification_master_table->getRows($_POST);

        $i = $_POST['start'];

        foreach($qualList as $qual){
            $i++;
            $id = $qual['qual_id'];
            $title = $qual['qual_title'];
            $val = ($qual['status'] == 1)? 'Active' : 'In active';
            $class = ($qual['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$qual['status'].')" class=" white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'qualification/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $id, $title, $status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->qualification_master_table->countAll(),
            "recordsFiltered" => $this->qualification_master_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}

	public function get_qualification()
	{
		$data['qualification'] = $this->qualification_master_table->getAllQualification();
		echo json_encode($data);
	}
}
