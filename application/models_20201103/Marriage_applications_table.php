<?php 
/**
 * @author Dhyey Rathod
 */
class Marriage_applications_table extends CI_Model
{
	protected $marriage_master_table ;
	protected $marriage_witness_table ;
	protected $marriage_document_table ;
	protected $response ;

	public function __construct(){
		parent::__construct();
		$this->marriage_master_table = 'marriage_application';
		$this->marriage_witness_table = 'marriage_witness';
		$this->marriage_document_table = 'marriage_document';
		$this->response = array();
	}
	public function store_marriage_application($input_stack)
	{
		if ($this->db->insert($this->marriage_master_table, $input_stack)) {
			$this->response['status'] = TRUE;
			$this->response['last_marriage_id'] = $this->db->insert_id();
		} else {
			$this->response['status'] = FALSE;
		}
		return json_decode(json_encode($this->response));
	}
	public function store_marriage_witness($witness_stack)
	{
		return $this->db->insert($this->marriage_witness_table,$witness_stack);
	}
	public function store_marriage_document($document_stack)
	{
		return $this->db->insert($this->marriage_document_table,$document_stack);
	}
	public function get_all_application_for_datatbl($count=FALSE,$search_value=FALSE,$dateRange=FALSE,$remark_id=FALSE,$active_status=FALSE,$order=FALSE,$limit=FALSE,$offset=FALSE)
	{
		$this->db->select('*');
		$this->db->from($this->marriage_master_table);
		$this->db->where('is_deleted',FALSE);
		if($limit != FALSE && $offset != FALSE) $this->db->limit($limit,$offset);

		if ($remark_id != FALSE) $this->db->where('status',$remark_id);

		if ($active_status != FALSE) $this->db->where('is_active',$active_status == 1 ? TRUE : FALSE);

		if( $search_value != FALSE ) {
            $this->db->like('application_no',$search_value,'after');
            $this->db->or_like('husband_name',$search_value,'after');
            $this->db->or_like('wife_name',$search_value,'after');
        }

        if ($dateRange != FALSE) {
        	if($dateRange['fromDate']) $this->db->where('DATE(marriage_date) >=', $dateRange['fromDate']); 
        	if ($dateRange['toDate']) $this->db->where('DATE(marriage_date) <=', $dateRange['toDate']); 
        }

        if($order != NULL){
            $colum_name['0'] = 'id';
            $colum_name['1'] = 'application_no';
            $colum_name['2'] = 'husband_name';
            $colum_name['3'] = 'wife_name';
            $colum_name['4'] = 'priest_name';
            $colum_name['5'] = 'marriage_date';
            $key = $order['column'];    
            $this->db->order_by($colum_name[$key],$order['dir']);
        } else {
            $this->db->order_by('id','asc');
        }
		if ($count == TRUE) {
			return $this->db->get()->num_rows();
		} else {
			return $this->db->get()->result();
		}
	}
	public function getAppDetails($application_id)
	{
		$this->db->select('*');
		$this->db->from($this->marriage_master_table);
		$this->db->where('id',$application_id);
		return $this->db->get()->row();
	}
	public function getWitnessByAppID($application_id)
	{
		$this->db->select('*');
		$this->db->from($this->marriage_witness_table);
		$this->db->where('marriage_id',$application_id);
		return $this->db->get()->result();
	}
	public function getDocByAppID($application_id)
	{
		$this->db->select('*');
		$this->db->from($this->marriage_document_table);
		$this->db->where('marriage_id',$application_id);
		$this->db->order_by('file_title','ASC');
		return $this->db->get()->result();
	}
	public function update_marriage_application($data_payload,$application_id)
	{
		$this->db->where('id', $application_id);
		return $this->db->update($this->marriage_master_table, $data_payload);
	}
	public function delete_witness_beforeupdate($application_id)
	{
		return $this->db->delete($this->marriage_witness_table,array('marriage_id' => $application_id));
	}
	public function delete_image_beforupload($application_id,$key)
	{
		return $this->db->delete($this->marriage_document_table,array(
			'marriage_id' => $application_id,
			'file_title' => $key
		));
	}
	public function set_applications_details($input_stack)
	{
		if ($this->db->insert('applications_details', $input_stack)) {
			$this->response['status'] = TRUE;
		} else {
			$this->response['status'] = FALSE;
		}
		return json_decode(json_encode($this->response));
	}
	public function get_last_dept_remark($application_id,$dept_id)
	{
		$this->db->select('*,(SELECT status_title FROM app_status_level WHERE status_id = application_remarks.status_id) AS status_title');
		$this->db->from('application_remarks');
		$this->db->where('app_id',$application_id);
		$this->db->where('dept_id',$dept_id);
		$this->db->order_by('application_remarks.id','DESC')->limit(1);
		return $this->db->get()->row();
	}
	public function get_app_status_lavel($dept_id , $role_id)
	{
		$this->db->select('*');
		$this->db->from('app_status_level');
		$this->db->where('role_id',$role_id);
		$this->db->where('dept_id',$dept_id);
		return $this->db->get()->result();	
	}
	public function set_application_remark($input_stack)
	{
		return $this->db->insert('application_remarks',$input_stack);
	}
}