<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'controllers/Common.php';
class RoadController extends Common{

	/**
	 * 
	 */
	public function index() {
		// echo'<pre>';print_r('hihi');exit;
		$this->load->view('master/road/index');
	}

    public function create() {
        $this->load->view('master/road/create');
    }

    public function edit() {

        $road_id = base64_decode($this->uri->segment(3));
        $result = $this->road_type_table->getRoadDetailsById($road_id);

        $data['road'] = $result;
        // echo'<pre>';print_r($data);exit;
        $this->load->view('master/road/edit',$data);
    }

	public function save() {
        extract($_POST);
        // echo'<pre>';print_r($_POST);exit;
        if($road_id =='') {
            $road_title = $this->form_validation
                ->set_rules('road_title','road_title','required|is_unique[road_type.road_title]')->run();
        } else {
            $road_title = $this->form_validation
                        ->set_rules('road_title','road_title','required')->run();
        }

        $data['messg'] = '';

        if(!$road_title) {
            $data['status'] = '2';
            $data['messg'] = validation_errors();
        } else {
            if($road_id =='') {
                $extra = array(
                    'status' => '1',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                );
                $data = array_merge($_POST,$extra);

                $result = $this->road_type_table->insert($data);
                $messg = 'added';
            } else {
                $extra = array(
                    'updated_at' => date('Y-m-d H:i:s')
                );
                
                unset($_POST['road_id']);
                $update_data = array_merge($_POST,$extra);
				
                $messg = 'updated';
                $result = $this->road_type_table->update($update_data,$road_id);
            }
            
            if($result == true) {
                $data['status'] = '1';
                $data['messg'] = $_POST['road_title'].' '.$messg.' successfully.';
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
        $result = $this->road_type_table->update($update,$road_id);
        // echo'<pre>';print_r($result);exit;
        if($result == true) {
            $data['status'] = '1';
            $data['messg'] = 'Road updated successfully.';
        } else {
            $messg = 'Oops! Something went wrong.';
            $data['status'] = '2';
            $data['messg'] = $messg;
        }
        // echo'<pre>';print_r($data);exit;
        echo json_encode($data);
    }

	public function get_lists()	{

		$data = $row = array();

        // Fetch member's records
        $roadList = $this->road_type_table->getRows($_POST);

        $i = $_POST['start'];
		
        foreach($roadList as $road){
            $i++;
            $id = $road['road_id'];
            $title = $road['road_title'];
            $val = ($road['status'] == 1)? 'Active' : 'In active';
            $class = ($road['status'] == 1)? 'btn-success' : 'btn-danger';
            $status ='<a type="button" onclick="changeStatus('.$id.','.$road['status'].')" class=" white btn btn-block '.$class.'">'.$val.'</a>';

            $action = ($road['status'] == 1) ? '<a href="'.base_url().'road/edit/'.base64_encode($id).'" class="nav-link-icon">
              		        <i class="nav-icon fas fa-edit"></i>
                        </a>' : '';

            $data[] = array($i, $id, $title, $road['rate'], $road['date_from'], $road['date_till'], $status, $action);
        }
        
        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->road_type_table->countAll(),
            "recordsFiltered" => $this->road_type_table->countFiltered($_POST),
            "data" => $data,
        );
        
        // Output to JSON format
        echo json_encode($output);
	}
}
