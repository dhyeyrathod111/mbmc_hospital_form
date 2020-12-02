<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
class MY_Model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function getAccessLavelByDeptID($dept_id)
	{
		$this->db->select('permission_access.*, roles_table.role_title');
		$this->db->from('permission_access');
		$this->db->join('roles_table', 'permission_access.role_id = roles_table.role_id');
		$this->db->where(['permission_access.dept_id'=>$dept_id,'permission_access.status'=>1]);
		return $this->db->get()->result();
	}
	public function get_primery_column_by_tablename($table_name)
	{
		return $this->db->query("SHOW KEYS FROM ".$table_name." WHERE Key_name = 'PRIMARY'")->row();
	}
}