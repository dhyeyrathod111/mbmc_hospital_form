<?php
	Class Add_company_details extends CI_Model{
		function __construct() {
	        // Set table name
	        $this->table = 'company_details';

	        // Set orderable column fields
	        $this->column_order = array(null, 'company_name', 'status', null);

	        // Set searchable column fields
	        $this->column_search = array('company_name', 'status');

	        // Set default order
	        $this->order = array('company_id' => 'DESC');
		}
		
		public function insert($company_name = null, $company_address = null){
			
			if($company_name != '' && $company_address != ''){
				$res = $this->db->insert($this->table, array("company_name" => $company_name));

				$last_id = $this->db->insert_id();

				if($last_id != ''){
					$res = $this->db->insert("company_address", array("company_id" => $last_id, "company_address" => $company_address));
					if($res){
						return true;
					}else{
						return false;
					}
				}else{
					return false;
				}
			}else{
				return false;
			}
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
	         
	        $this->db->from($this->table);
	 
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

		public function deactivate($companyId = null, $companyStatus = null){
			if($companyId != '' && $companyStatus != ''){
				if($companyStatus == '1'){
					$res = $this->db->where("company_id", $companyId)
									->update($this->table, array("status" => "2"));
					if($res){
						$this->db->where("company_id", $companyId)
								 ->update("company_address", array("status" => "2"));
						return true;
					}else{
						return false;
					}
				}else{
					$res = $this->db->where("company_id", $companyId)
									->update($this->table, array("status" => "1"));

					if($res){
						$this->db->where("company_id", $companyId)
								 ->update("company_address", array("status" => "1"));
						return true;
					}else{
						return false;
					}
				}
			}else{
				return false;
			}
		}

		public function getAddressList($companyId = null){
			if($companyId != ''){
				$res = $this->db->select("*")
								->from("company_address")
								->where(array("company_id" => $companyId, "status" => "1"))
								->get()
								->result();

				if(!empty($res)){
					return $res;
				}else{
					return false;
				}
			}else{
				return false;
			}
		}

		public function getCompanyDetails($companyId = null){
			$companyDetails = $this->db->query("SELECT * FROM company_details WHERE company_id = '".$companyId."'")->result_array();
			$company_address =$this->db->query("SELECT * FROM company_address WHERE company_id = '".$companyId."' AND status = '1'")->result_array();

			$data['company_details'] = $companyDetails;
			$data['company_address'] = $company_address;

			if(!empty($companyDetails)){
				return $data;
			}else{
				return false;
			}
		}

		public function deleteAddress($addressId = null){
			$res = $this->db->where("address_id", $addressId)
							->update("company_address", array("status" => "2"));
			if($res){
				return true;
			}else{
				return false;
			}
		}

		public function editAddress($addressId = null, $address = null, $companyId = null){
			// echo $addressId."-".$address."-".$companyId;exit;
			$res = $this->db->where("address_id", $addressId)
							->update("company_address", array("company_address" => $address, "company_id" => $companyId));
			
			if($res){
				return true;
			}else{
				return false;
			}			
		}
	}
?>
