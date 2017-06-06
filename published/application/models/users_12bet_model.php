<?php
class Users_12bet_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
		$this->callers = "agent,crm";
    }  
	
	public function getUsers12BetList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";
		
		$sql = "SELECT a.*, b.Abbreviation AS CurrencyName  
				FROM csa_12bet_users AS a	  
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID
				WHERE a.UserID <> 0 $where_str 
				ORDER BY a.DateUpdated DESC
			    {$limit_query} "; 
		$result = $this->db->query($sql);  
		return $result->result();  
	}
	  
	public function getUser12BetById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*,  b.Abbreviation AS CurrencyName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_12bet_users a');    
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left'); 
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Username", "ASC");
 		$result = $this->db->get();    
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
	        
	public function manageUser12Bet_($table, $datax, $action="add", $index="", $index_value=""){  
		$data = array(); 
		
		foreach($datax as $field => $value){ 
			 //$data[$field] = $value;   
			  
			 if($field == "mb_password") 
			 { 
				 $this->db->set('mb_password', 'PASSWORD("'.$value.'")', FALSE);
			 }
			else 
			 {
				$data[$field] = $value;//$this->db->escape($value)." "; 
			 }  
			  
		}   
  
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$data);  
			return $this->db->insert_id(); 
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);  
			 return $x;  
		 }
		
	} 
	  
	/* End of Private Functions */
}

?>