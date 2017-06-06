<?php
class Schedules_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "schedule"; 
    }
	 
	public function getSchedules_($where=null,$order=array(),$offset=0,$limit=0)
	{
		$query = "";
		
		if($where != null)
			$this->db->where($where);
		
		if(count($order)){
			foreach($order as $field=>$ord){
				$this->db->order_by($field,$ord);
			}
		}
		
		if($limit ==0)
			$query = $this->db->get($this->table_name);
		else
			$query = $this->db->get($this->table_name,$limit,$offset);
		
		return $query->result();
	}
	
	public function getUsers_($where=null,$order=array(),$offset=0,$limit=0)
	{
		$query = "";
		
		if($where != null)
			$this->db->where($where);
		
		if(count($order)){
			foreach($order as $field=>$ord){
				$this->db->order_by($field,$ord);
			}
		}
		
		if($limit ==0)
			$query = $this->db->get("g4_member");
		else
			$query = $this->db->get("g4_member",$limit,$offset);
		
		return $query->result();
	}
	
	public function setSchedule_($set,$where,$insert=null)
	{
		$this->db->where($where);
		$query = $this->db->get($this->table_name);
		
		if($query->num_rows()){
			$this->db->update($this->table_name,$set,$where); 
			return 1;
		}
		else if($insert != null) {
			$this->db->insert($this->table_name,$insert);
			return $this->db->affected_rows();
		}
		return 0;
	}
	
	/* Private Functions */
	
	private function select_strict ($where=array(),$table = "",$order=array(),$offset=0,$limit=0){
		if(empty($table))
			$table = $this->table_name;
		$where_str = $order_str = $limit_str = "";
		$where_arr = $order_arr = array();
		foreach($where as $field=>$value){
			$where_arr[] = " `".$field."` = ".$this->db->escape($value)." ";
		}
		
		foreach($order as $field=>$order){
			$order_arr[] = " `".$field."` ".$order." ";
		}
		
		$where_str = implode(" AND ",$where_arr);
		$where_str = ($where_str==""?"":"WHERE ".$where_str);
		$order_str = implode(" , ",$order_arr);
		$order_str = ($order_str==""?"":"ORDER BY ".$order_str);
		if(!empty($limit)){
			$limit_str = "LIMIT {$offset},{$limit}";
		}
		
		$result = $this->db->query("SELECT * FROM ".$table." ".$where_str." ".$order_str." ".$limit_str);
		
		return $result;
	}
	
	/* End of Private Functions */
	
}

?>