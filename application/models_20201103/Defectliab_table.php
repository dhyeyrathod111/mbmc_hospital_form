<?php 
	class Defectliab_table extends CI_Model {
		function __construct() {
	        // Set table name
	        $this->table = 'defect_laiblity';

	        // Set orderable column fields
	        $this->column_order = array(null, 'laib_name','mul_factor','status',null);

	        // Set searchable column fields
	        $this->column_search = array('laib_name','mul_factor','status');

	        // Set default order
	        $this->order = array('laib_id' => 'DESC');
		}
		
		public function getRows($postData){
	        $this->_get_datatables_query($postData);
	        if($postData['length'] != -1){
	            $this->db->limit($postData['length'], $postData['start']);
	        }
	        $query = $this->db->get();
	        return $query->result_array();
		}
		
		public function countAll(){
	        $this->db->from($this->table);
	        return $this->db->count_all_results();
		}
		
		public function countFiltered($postData){
	        $this->_get_datatables_query($postData);
	        $query = $this->db->get();
	        return $query->num_rows();
		}
		
		private function _get_datatables_query($postData){
	         
	        $this->db->from($this->table)->where(array('status'=>'1'));
	 
	        $i = 0;
	        // loop searchable columns 
	        foreach($this->column_search as $item){
	            // if datatable send POST for search
	            if($postData['search']['value']){
	                // first loop
	                if($i===0){
	                    // open bracket
	                    $this->db->group_start();
	                    $this->db->like($item, $postData['search']['value']);
	                }else{
	                    $this->db->or_like($item, $postData['search']['value']);
	                }
	                
	                // last loop
	                if(count($this->column_search) - 1 == $i){
	                    // close bracket
	                    $this->db->group_end();
	                }
	            }
	            $i++;
	        }
	         
	        if(isset($postData['order'])){
	            $this->db->order_by($this->column_order[$postData['order']['0']['column']], $postData['order']['0']['dir']);
	        }else if(isset($this->order)){
	            $order = $this->order;
	            $this->db->order_by(key($order), $order[key($order)]);
	        }
		}
		
		public function insert($data = null){
			if(!empty($data)){
				$ins = $this->db->insert($this->table, $data);
				if($ins){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function update($updateArray = null, $laib_id = null){
			if(!empty($updateArray) && $laib_id != ''){
				$res = $this->db->where("laib_id", $laib_id)
								->update($this->table, $updateArray);
				if($res){
					return true;
				}else{
					return false;
				}

			}else{
				return false;
			}
		}

		public function getLiabDetailsById($liab_id){
			$res = $this->db->select("*")
							->from($this->table)
							->where(array("status" => '1',"laib_id" => $liab_id))
							->get()
							->result_array();
			
			if(!empty($res)){
				return $res;
			}else{
				return false;
			}
		}

		public function deactivate($laibId = null, $laibStatus = null){
			if($laibId != '' && $laibStatus != ''){
				if($laibStatus == '1'){
					$res = $this->db->where("laib_id", $laibId)
									->update($this->table, array("status" => "2"));
					if($res){
						return true;
					}else{
						return false;
					}
				}else{
					$res = $this->db->where("laib_id", $laibId)
									->update($this->table, array("status" => "1"));

					if($res){
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}
	}
?>
