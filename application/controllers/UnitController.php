<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class UnitController extends Common {

	/**
 	* DepartmentsController.php
 	* @author Vikas Pandey
 	*/


	public function index() {
        // echo'<pre>';print_r('hihih');exit;
		$this->load->view("master/unit/index");
	}

    public function create() {
        $this->load->view('master/unit/create');
    }

    public function edit() {

        $unit_id = base64_decode($this->uri->segment(3));

        $result = $this->unit_master_table->getAllUnitDetailsById($unit_id);
        $data['unit'] = $result;
        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/unit/edit',$data);
    }

    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $unit_value_check = $this->form_validation
                ->set_rules('unit_value','Unit value','required')->run();

        $unit_label_check = $this->form_validation
                ->set_rules('unit_label','Unit label','required')->run(); 

        // $unit_cost_check = $this->form_validation
                // ->set_rules('unit_cost','Unit cost','required')->run();   

        // if($sku_id =='') {
        //     $unit_title_check = $this->form_validation
        //                     ->set_rules('sku_title','sku title','required|is_unique[sku_master.sku_title]')->run();
        //     $unit_title_check = $this->form_validation
        //             ->set_rules('sku_title','sku title','required|is_unique[sku_master.sku_title]')->run();  
        // } else {
        //     $unit_title_check = $this->form_validation
        //                ->set_rules('sku_title','sku title','required')->run();   
        // }
        
        $data['messg'] = '';
        // echo'<pre>';print_r($dept_title);exit;
        if(!$unit_value_check || !$unit_label_check) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            if($unit_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);

                // echo'<pre>';print_r($data);exit;

                $result = $this->unit_master_table->insert($data);
                $messg = 'added';
            } else {
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                $data = array_merge($_POST,$extra);
                $result = $this->unit_master_table->update($data,$unit_id);
                $messg = 'updated';
            }
            
            // echo'<pre>';print_r($result);exit;
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['unit_label'].' '.$messg.' successfully.';
            } else {
                $messg = 'Oops! Something went wrong.';
                $data['status'] = '2';
                $data['messg'] = $messg;
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
        $result = $this->unit_master_table->update($update,$unit_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'Unit updated successfully.';
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

        $unitList = $this->unit_master_table->getRows($_POST);
        // echo'ss<pre>';print_r($skuList);exit;
        // echo'<pre>';print_r($role_data);exit;
        $i = $_POST['start'];

        foreach($unitList as $unit){
            $i++;
            $id = $unit['unit_id'];
            $value = $unit['unit_value'];
            $label = $unit['unit_label'];
            $cost = $unit['unit_cost'];
            $val = ($unit['status'] == 1)? 'Active' : 'In active';
            $class = ($unit['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$unit['status'].')" class=" white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'unit/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>';

            // $data[] = array($i,$id,$value,$label,$cost,$status,$action);
                        $data[] = array($i,$id,$value,$label,$status,$action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->unit_master_table->countAll(),
            "recordsFiltered" => $this->unit_master_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}

    public function get_unit() {
        extract($_POST);
        $unit_details = $this->unit_master_table->getAllUnitDetailsById($unit_id);
        // echo'<pre>';print_r($unit_details);exit;
        if($unit_details != null) {
            $amount = $unit_details['unit_value'] * $unit_details['unit_cost'];
            $data['status'] = '1';
            $data['amount'] = $amount;
        } else {
             $data['status'] = '2';
             $data['amount'] = null;
        }
        echo json_encode($data);
    }
}
?>