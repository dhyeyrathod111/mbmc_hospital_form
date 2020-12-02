<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class PriceController extends Common {

    /**
    * DepartmentsController.php
    * @author Vikas Pandey
    */


    public function index() {
        
        $this->load->view("master/price/index");
    }

    public function create() {

        $data['department'] = $this->get_all_dept();
        $data['unit'] = $this->unit_master_table->getAllUnit();
        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/price/create',$data);
    }

    

    public function edit() {

        $price_id = base64_decode($this->uri->segment(3));

        $price = $this->price_master_table->getAllPriceDetailsById($price_id);
        // echo'<pre>';print_r($price);exit;
        $unit = $this->unit_master_table->getAllUnit();
        
        $data['unit'] = $unit;
        $data['price'] = $price;
        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/price/edit',$data);
    }

    public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        $dept_id_check = $this->form_validation
                            ->set_rules('dept_id','department name','required')->run();

        $sku_title_check = $this->form_validation
                            ->set_rules('sku_id','sku title','required')->run();

        $unit_check = $this->form_validation
                            ->set_rules('unit_id','unit','required')->run(); 
        

        $amount_check = $this->form_validation
                            ->set_rules('amount','amount','required')->run(); 
        
        $data['messg'] = '';
        // echo'<pre>';print_r($dept_title);exit;
        if(!$dept_id_check || !$sku_title_check || !$amount || !$unit_check) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
            // exit;
        } else {
            if($price_id =='') {

                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);

                // echo'<pre>';print_r($data);exit;

                $result = $this->price_master_table->insert($data);
                $messg = 'added';
            } else {
                
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );

                $data = array_merge($_POST,$extra);
                $result = $this->price_master_table->update($data,$price_id);
                $messg = 'updated';
            }
            
            // echo'<pre>';print_r($result);exit;
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = ' amount '.$messg.' successfully.';
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
        $result = $this->price_master_table->update($update,$price_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'amount updated successfully.';
        } else {
            $messg = 'Oops! Something went wrong.';
            $data['status'] = '2';
            $data['messg'] = $messg;
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

    public function sku_by_dept() {

        extract($_POST);
        if($dept_id !='') {
            $result = $this->sku_master_table->getAllSkuByDept($dept_id);
            // echo'<pre>';print_r($result);exit;
            if($result != null) {
                $data['status'] = '1';
                $data['result'] = $result;
            } else {
                $data['status'] = '2';
                $data['result'] = null;
            }
        } else {
            $data['status'] = '2';
                $data['result'] = null;
        }
        echo json_encode($data);
        
    }

    public function price_by_sku() {

        extract($_POST);
        if($sku_id !='') {
            $result = $this->price_master_table->getpriceByskuId($sku_id);
            // echo'<pre>';print_r($result);exit;
            if($result != null) {
                $data['status'] = '1';
                $data['result'] = $result;
            } else {
                $data['status'] = '2';
                $data['result'] = null;
            }
        } else {
            $data['status'] = '2';
                $data['result'] = null;
        }
        echo json_encode($data);
        
    }

    public function get_list()  {

        $data = $row = array();

        $priceList = $this->price_master_table->getRows($_POST);
        // echo'ss<pre>';print_r($skuList);exit;
        // echo'<pre>';print_r($role_data);exit;
        $i = $_POST['start'];

        foreach($priceList as $price){
            $i++;
            $id = $price['price_id'];
            $sku_title = $price['sku_title'];
            $dept_title = $price['dept_title'];
            $amount = $price['amount'];

            $val = ($price['status'] == 1)? 'Active' : 'In active';
            $class = ($price['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$price['status'].')" class="white btn btn-block '.$class.'">'.$val.'</a>';

            $action = '<a href="'.base_url().'price/edit/'.base64_encode($id).'" class="nav-link-icon">
                            <i class="nav-icon fas fa-edit"></i>
                        </a>';

            $data[] = array($i, $id, $sku_title, $dept_title,$amount,$status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->price_master_table->countAll(),
            "recordsFiltered" => $this->price_master_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
    }
}
