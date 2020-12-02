<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class SkuController extends Common {

	/**
 	* DepartmentsController.php
 	* @author Vikas Pandey
 	*/


	public function index() {
		
		$this->load->view("master/sku/index");
	}

    public function create() {

        $data['department'] = $this->get_all_dept();
        $this->load->view('master/sku/create',$data);
    }

    public function edit() {

        $sku_id = base64_decode($this->uri->segment(3));
        $data['department'] = $this->get_all_dept();

        $result = $this->sku_master_table->getAllSkuDetailsById($sku_id);

        $data['sku'] = $result;

        $this->load->view('master/sku/edit',$data);
    }

    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $dept_id_check = $this->form_validation
                        ->set_rules('dept_id','department name','required')->run();

        $sku_title_check = $this->form_validation
                        ->set_rules('sku_title','sku title','required')->run();  
        
        $data['messg'] = '';
        // echo'<pre>';print_r($dept_title);exit;
        if(!$dept_id_check || !$sku_title_check) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            if($sku_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);

                $result = $this->sku_master_table->insert($data);
                $messg = 'added';

            } else {
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);
                $result = $this->sku_master_table->update($data,$sku_id);
                $messg = 'updated';
            }

            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['sku_title'].' '.$messg.' added successfully.';
            } else {
                $messg = 'Oops! Something went wrong.';
                $data['status'] = '2';
                $data['messg'] = $messg;
            }
        }

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
        $result = $this->sku_master_table->update($update,$sku_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'Sku updated successfully.';
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

        $skuList = $this->sku_master_table->getRows($_POST);
        // echo'ss<pre>';print_r($skuList);exit;
        // echo'<pre>';print_r($role_data);exit;
        $i = $_POST['start'];

        foreach($skuList as $sku){
            $i++;
            $id = $sku['sku_id'];
            $title = $sku['sku_title'];
            $dept_title = $sku['dept_title'];
            $val = ($sku['status'] == 1)? 'Active' : 'In active';
            $class = ($sku['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$sku['status'].')" class="white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'sku/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $id, $title, $dept_title, $status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->sku_master_table->countAll(),
            "recordsFiltered" => $this->sku_master_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}
}
?>