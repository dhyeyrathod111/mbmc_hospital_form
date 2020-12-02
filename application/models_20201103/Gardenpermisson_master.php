<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
/**
 * @author Dhyey Rathod
 */
class Gardenpermisson_master extends CI_Model
{	
	public function setFardenPermission($input_Stack)
	{
		return $this->db->insert('garden_permission',$input_Stack);
	}
	public function get_datatable_for_gardenpermission($count=FALSE,$search_value=FALSE,$order=FALSE,$limit=FALSE,$offset=FALSE)
	{
		$this->db->select('*');
		$this->db->from('garden_permission');
		if($limit != FALSE && $offset != FALSE) $this->db->limit($limit,$offset);

		if( $search_value != FALSE ) $this->db->like('permission_type',$search_value,'after');

        if($order != NULL){
            $colum_name['1'] = 'permission_type';
            $colum_name['2'] = 'is_blueprint';
            $colum_name['3'] = 'created_at';
            $colum_name['4'] = 'status';
            $key = $order['column'];    
            $this->db->order_by($colum_name[$key],$order['dir']);
        } else {
            $this->db->order_by('garper_id','asc');
        }
		if ($count == TRUE) {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
	}
	public function get_garden_permission_data($permission_id = FALSE)
	{
		$this->db->select('*');
		$this->db->from('garden_permission');
		if ($permission_id != FALSE) {
			$this->db->where('garper_id',$permission_id);
			return $this->db->get()->row();
		} else {
			return $this->db->get()->result();
		}
	}
	public function update_gardenpermisson($update_stack,$permission_id)
	{
		$this->db->where('garper_id', $permission_id);
		return $this->db->update('garden_permission', $update_stack);
	}
}