<?php
class Manage_Agents_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "agents";
		$this->table_name_status = "account_status";
		$this->table_name_mode = "agent_modes";
		$this->table_name_currency = "currency"; 
    }
	
	function checkAgentName_($agent_name, $agent_id=""){   
		$where = ($agent_id)?" AND AgentID<>".$agent_id." ":"";
		$sql = "SELECT  AgentID 
			  	FROM {$this->table_name} 
				WHERE LCASE(AgentName) =".strtolower($this->db->escape($agent_name)). $where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	}
	
	function getAgentById_($agent_id){  
		$sql = "SELECT a.AgentID, a.Currency, a.AgentName, a.Mode, a.ExcelPassword,a. ToEmail, a.CcEmail, a.Remarks, a.UpdatedBy, a.DateUpdated, a.Status,
					   b.Abbreviation, c.ModeName, d.mb_nick AS LastUpdatedBy
				FROM {$this->table_name} AS a
				LEFT JOIN {$this->table_name_currency} AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN {$this->table_name_mode} AS c ON a.Mode=c.ModeID       
				LEFT JOIN g4_member AS d ON a.UpdatedBy=d.mb_no      
				WHERE FIND_IN_SET(a.AgentID, ".$this->db->escape($agent_id).")";//
		$result = $this->db->query($sql); 
		if($result->num_rows() > 0){
			if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}
		}
		else {
			return array();
		} 
	 
	} 
	
	function getLanguages_($lang=""){ 
		 $sql = "SELECT LanguageID, Name, FolderName, SpecialCharacters FROM language  
				WHERE Status='1' 
				ORDER BY LanguageID ASC "; 
		 
		$result = $this->db->query($sql); 
		if($result->num_rows() > 0){
			if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}
		}
		else {
			return array();
		} 
	 
	} 
	
	 
	public function manageAgents_($table, $datax, $action="add", $index="", $index_value=""){ 
		$test="";
		$data = array(); 
		
		foreach($datax as $field => $value){ 
			 $data[$field] = $value;
			 $test .= $field.'-';
		}   
  
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$data);  
			return $x; 
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
		 }
		
	}
	  
	
	public function update_entry($data,$param){
		$this->db->update($this->table_name,$data,$param);
		return $this->db->affected_rows();
	}
	
	public function insert_table_entry($table,$data){
		$this->db->insert($table,$data);
		return $this->db->affected_rows();
	}
	
	public function update_table_entry($table,$data,$param){
		$this->db->update($table,$data,$param);
		return $this->db->affected_rows();
	}
	
	public function get_table_entry($table,$where=array()){
		$result = $this->select_strict($where,$table);
		if($result->num_rows() > 0){
			if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}
		}
		else {
			return array();
		}
	}
    
	public function create_thumbnail($config) { 
		$this->image_lib->initialize($config); 
		$this->image_lib->resize(); 
		
	}
     
	/* End of Public Functions */
	
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