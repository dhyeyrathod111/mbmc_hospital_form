<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
class WardModel extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function get_all_active_departments()
	{
		$this->db->select('*');
		$this->db->from('department_table')->where('is_deleted',0)->where('status',1);
		return $this->db->get()->result();	
	}
	public function getRolesByDeptID($department_id)
	{
		$this->db->select('permission_access.*,(SELECT role_title FROM roles_table WHERE permission_access.role_id = roles_table.role_id LIMIT 1) AS role_title');
		$this->db->from('permission_access')->where('dept_id',$department_id)->where('status',1);
		return $this->db->get()->result();	
	}
	public function createWardModel($insert_payload)
	{
		return $this->db->insert('ward',$insert_payload);
	}
	public function getAllWardData($count=FALSE,$search_value=FALSE,$order=FALSE,$limit=FALSE,$offset=FALSE)
	{
		$this->db->select('ward.*,department_table.dept_title AS dept_title,roles_table.role_title AS role_title');
		$this->db->join('department_table', 'ward.dept_id = department_table.dept_id');
		$this->db->join('roles_table', 'ward.role_id = roles_table.role_id','left');
		$this->db->from('ward')->where('ward.is_deleted',0);
		if($search_value != FALSE) $this->db->like('ward.ward_title',$search_value,'after');
		if($order != NULL){
            $colum_name['1'] = 'ward.ward_id';
            $colum_name['2'] = 'department_table.dept_title';
            $colum_name['3'] = 'roles_table.role_title';
            $colum_name['4'] = 'ward.ward_title';
            $colum_name['5'] = 'ward.status';
            $colum_name['6'] = 'ward.created_at';
            $key = $order['column'];    
            $this->db->order_by($colum_name[$key],$order['dir']);
        } else {
            $this->db->order_by('ward.ward_id','desc');
        }
		if($limit != FALSE && $offset != FALSE) $this->db->limit($limit,$offset);
		if ($count != FALSE) {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
	}
	public function getWardByID($ward_id)
	{
		return $this->db->get_where('ward',['ward_id' => $ward_id])->row();	
	}
	public function updateWardModel($update_payload , $ward_id)
	{
		$this->db->where('ward_id', $ward_id);
		return $this->db->update('ward', $update_payload);
	}
	public function deleteWardByID($ward_id)
	{
		$this->db->where('id', $id)->delete('mytable');
	}
	public function getAllActiveWard()
	{
		$this->db->select('*');
		$this->db->from('ward')->where('is_deleted',0)->where('status',1)->where('dept_id',1);
		return $this->db->get()->result();
	}
}