<?php
class Import_Bonus_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "bonus"; 
    }
	
	public function checkBonus_($where=null,$order=array(),$offset=0,$limit=0)
	{
		$query = "";
		
		if($where != null)
			$this->db->where($where);
		echo $this->db->where($where); 
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
	
	public function insertBonus_($data)
	{
		$this->db->insert($this->table_name,$data);
		return $this->db->affected_rows();
	}
	
	public function updateBonus_($set,$where)
	{
		$this->db->update($this->table_name,$set,$where); 
		return 1;
	}
	
	public function clean_str($str)
	{
		return $this->db->escape($str);
	}
	
}

?>