<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @author Dhyey Rathod
 */
class DepositInspectionModel extends CI_Model
{
	protected $master_table ;
 	public function __construct()
	{
		parent::__construct();
	}
	public function get_all_active_departments()
	{
		$this->db->select('*');
		$this->db->from('department_table');
		$this->db->where('is_deleted',0);
		$this->db->where('status',1);
		return $this->db->get()->result();	
	}
	public function store_depositinspection($input_stack)
	{
		return $this->db->insert('deposit_inspection_fees',$input_stack);
	}
	public function depositinspection($count=FALSE,$search_value=FALSE,$order=FALSE,$filters=array(),$limit=FALSE,$offset=FALSE){
		$this->db->select('deposit_inspection_fees.*,department_table.dept_title AS dept_title , users_table.user_name AS user_name');
		$this->db->from('deposit_inspection_fees');
		$this->db->join('department_table','deposit_inspection_fees.dept_id = department_table.dept_id');
		$this->db->join('users_table','deposit_inspection_fees.user_id = users_table.user_id');
		if (!empty($filters['filter_department_id']) && $filters['filter_department_id'] != '') $this->db->where('department_table.dept_id',$filters['filter_department_id']);
		if (!empty($filters['filter_version']) && $filters['filter_version'] != '') $this->db->where('deposit_inspection_fees.status',(int)$filters['filter_version']);
		if($limit != FALSE && $offset != FALSE) $this->db->limit($limit,$offset);
		if( $search_value != FALSE ) {
			$this->db->like('department_table.dept_title',$search_value,'after');
		}
		if($order != NULL){
            $colum_name['1'] = 'department_table.dept_title';
            $colum_name['2'] = 'users_table.user_name';
            $colum_name['3'] = 'deposit_inspection_fees.inspection_fee';
            $colum_name['4'] = 'deposit_inspection_fees.deposit';
            $colum_name['5'] = 'deposit_inspection_fees.from_date';
            $colum_name['6'] = 'deposit_inspection_fees.to_date';
            $key = $order['column'];    
            $this->db->order_by($colum_name[$key],$order['dir']);
        } else {
            $this->db->order_by('deposit_inspection_fees.id','asc');
        }
		if ($count == TRUE) {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
	}
	public function depositinspection_by_id($inspection_id)
	{
		$this->db->select('*');
		$this->db->from('deposit_inspection_fees');
		$this->db->where('id',$inspection_id);
		return $this->db->get()->row();	
	}
	public function update_depositinspection($update_state,$inspection_id)
	{
		$this->db->where('id', $inspection_id);
		return $this->db->update('deposit_inspection_fees', $update_state);
	}
	public function get_deposit_by_dept_id($dept_id)
	{
		$this->db->select('*');
		$this->db->from('deposit_inspection_fees');
		$this->db->where('dept_id',$dept_id);
		$this->db->where('status',1);
		$this->db->where('DATE(from_date) <', date('Y-m-d'));
		$this->db->where('DATE(to_date) >', date('Y-m-d'));
		return $this->db->get()->row();
	}
	public function getTrecuttingAppBYID($treeCutting_id)
	{
// 		$this->db->select('*');
// 		$this->db->from('treecuttingapplications');
// 		$this->db->where('cutAppId',$treeCutting_id);
// 		return $this->db->get()->row();

        $res = $this->db->query("select SUM(IF(gd.no_of_trees = 0, 1, gd.no_of_trees)) totaltrees from gardendata gd where gd.complain_id = '".$treeCutting_id."' AND gd.status = '1'")->result_array();
		
		if($res){
			return $res;
		}else{
			return false;
		}
		
	}

	public function getPermissionType($complainId = null){
		$getPerType = $this->db->query("SELECT permission_type FROM `treecuttingapplications` WHERE cutAppId = '".$complainId."' AND status = '1'")->result_array();

		if(!empty($getPerType)){
			return $getPerType;
		}else{
			return false;
		}
	}
}
