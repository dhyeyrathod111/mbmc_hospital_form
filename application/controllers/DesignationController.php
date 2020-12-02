<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';

class DesignationController extends Common {

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
		
		$this->load->view('master/designation/index');
	}

	public function create() {
        $this->load->view('master/designation/create');
    }

    public function edit() {

        $design_id = base64_decode($this->uri->segment(3));

        $result = $this->designation_master_table->getDesDetailsById($design_id);

        $data['design'] = $result;
        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/designation/edit',$data);
    }

	public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        if($design_id =='') {
            $design_title = $this->form_validation
                ->set_rules('design_title','designation title','required|is_unique[road_type.road_title]')->run();
            } else {
                $design_title = $this->form_validation
                            ->set_rules('design_title','designation title','required')->run();
            }
        $data['messg'] = '';

        if(!$design_title) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {
            if($design_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);
                // echo'<pre>';print_r($data);exit;
                $result = $this->designation_master_table->insert($data);
                $messg = 'added';
            } else {
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);
                $result = $this->designation_master_table->update($data,$design_id);
                $messg = 'updated';
            }
            
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['design_title'].' '.$messg.' successfully.';
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
        // echo'<pre>';print_r($update);exit;
        $result = $this->designation_master_table->update($update,$design_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'designation updated successfully.';
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
        $designList = $this->designation_master_table->getRows($_POST);

        $i = $_POST['start'];

        foreach($designList as $des){
            $i++;
            $id = $des['design_id'];
            $title = $des['design_title'];
            $val = ($des['status'] == 1)? 'Active' : 'In active';
            $class = ($des['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$des['status'].')" class=" white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'designation/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $id, $title, $status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->designation_master_table->countAll(),
            "recordsFiltered" => $this->designation_master_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}

	public function get_designation()
	{
		$data['designation'] = $this->designation_master_table->getAllDesignation();
		echo json_encode($data);
	}
}
