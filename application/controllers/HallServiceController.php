<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class HallServiceController extends Common {

    /**
    * HallServiceController.php
    * @author Vikas Pandey
    */


    public function index() {
        
        $this->load->view("master/hallservice/index");
    }

    public function create() {

        // $data['department'] = $this->get_all_dept();
        $data['unit'] = $this->unit_master_table->getAllUnit();
        $data['sku'] = $this->sku_master_table->getAllSku();

        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/hallservice/create',$data);
    }

    

    public function edit() {

        $asset_id = base64_decode($this->uri->segment(3));

        $asset = $this->hall_assets_table->getassetDetailsById($asset_id);
        // echo'<pre>';print_r($price);exit;
        $data['unit'] = $this->unit_master_table->getAllUnit();
        $data['sku'] = $this->sku_master_table->getAllSku();
        $data['asset'] = $asset;
        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/hallservice/edit',$data);
    }

    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $sku_id_check = $this->form_validation
                            ->set_rules('sku_id','sku','required')->run();

        $asset_name_check = $this->form_validation
                            ->set_rules('asset_name','Service title','required')->run();

        $asset_unit_id_check = $this->form_validation
                            ->set_rules('asset_unit_id','unit','required')->run(); 
        

        $asset_unit_cost_check = $this->form_validation
                            ->set_rules('asset_unit_cost','service unit cost','required')->run(); 

        $penalty_charges_check = $this->form_validation
                            ->set_rules('penalty_charges','penalty charges','required')->run(); 
        
        $data['messg'] = '';
        // echo'<pre>';print_r($dept_title);exit;
        if(!$sku_id_check || !$asset_name_check || !$asset_unit_id_check || !$asset_unit_cost_check || !$penalty_charges_check) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            if($asset_id =='') {

                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);

                // echo'<pre>';print_r($data);exit;

                $result = $this->hall_assets_table->insert($data);
                $messg = 'added';
            } else {
                
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);
                $result = $this->hall_assets_table->update($data,$asset_id);
                $messg = 'updated';
            }
            
            // echo'<pre>';print_r($result);exit;
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = ' hallservice '.$messg.' successfully.';
            } else {
                $messg = 'Oops! Something went wrong.';
                $data['status'] = '2';
                $data['messg'] = $messg;
            }
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

    
    public function get_list()  {

        $data = $row = array();

        $hallSeviceList = $this->hall_assets_table->getRows($_POST);
        // echo'ss<pre>';print_r($skuList);exit;
        // echo'<pre>';print_r($role_data);exit;
        $i = $_POST['start'];

        foreach($hallSeviceList as $hs){
            $i++;
            $asset_id = $hs['asset_id'];
            $service_name = $hs['asset_name'];
            $sku_title = $hs['sku_title'];
            $unit_title = $hs['unit_label'];
            $asset_unit_cost = $hs['asset_unit_cost'];
            $penalty_charges = $hs['penalty_charges'];

            $val = ($hs['status'] == 1)? 'Active' : 'In active';
            $class = ($hs['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$asset_id.','.$hs['status'].')" class="white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'hall-service/edit/'.base64_encode($asset_id).'" class="nav-link-icon">
                            <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $asset_id, $service_name,$sku_title, $unit_title,$asset_unit_cost,$penalty_charges,$status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->hall_assets_table->countAll(),
            "recordsFiltered" => $this->hall_assets_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
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
        $result = $this->hall_assets_table->update($update,$asset_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'hallservice updated successfully.';
        } else {
            $messg = 'Oops! Something went wrong.';
            $data['status'] = '2';
            $data['messg'] = $messg;
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }
}
