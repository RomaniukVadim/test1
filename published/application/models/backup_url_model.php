<?php
class Backup_Url_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
		$this->callers = "agent,crm";
    }  
	
	public function getBackupUrlList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";
		
		$sql = "SELECT a.*, (CASE WHEN a.Currencies IS NULL THEN '' ELSE GROUP_CONCAT(DISTINCT b.Abbreviation SEPARATOR ', ' ) END) AS CurrencyNames ,
							(CASE WHEN a.BlockedCurrencies IS NULL THEN '' ELSE GROUP_CONCAT(DISTINCT c.Abbreviation SEPARATOR ', ' ) END) AS BlockedCurrencyNames, 
							(CASE WHEN a.Status = '1' THEN 'Active' WHEN a.Status = '2' THEN 'Release' ELSE 'Inactive' END) StatusName  
				FROM csa_backup_url AS a	  
				LEFT JOIN csa_currency AS b ON FIND_IN_SET(b.CurrencyID, a.Currencies) 
				LEFT JOIN csa_currency AS c ON FIND_IN_SET(c.CurrencyID, a.BlockedCurrencies) 
				WHERE a.UrlID <> 0 $where_str 
				GROUP BY a.UrlID 
				HAVING a.UrlID > 0 
				ORDER BY a.DateUpdated DESC
			    {$limit_query} ";  
		$result = $this->db->query($sql); 
		return $result->result();  
	}
	  
	public function getBackupUrlById_($where_arr=array(), $like_arr=array()) 
	{  			   
		$x = 0; 
		$this->db->select("a.*, (CASE WHEN a.Currencies IS NULL THEN '' ELSE GROUP_CONCAT(DISTINCT b.Abbreviation SEPARATOR ', ' ) END) AS CurrencyNames,  
								(CASE WHEN a.BlockedCurrencies IS NULL THEN '' ELSE GROUP_CONCAT(DISTINCT c.Abbreviation SEPARATOR ', ' ) END) AS BlockedCurrencyNames,
							    (CASE WHEN a.Status = '1' THEN 'Active' WHEN a.Status = '2' THEN 'Release' ELSE 'Inactive' END) StatusName 
						  " 
								, false);
		$this->db->from('csa_backup_url a');    
		$this->db->join("csa_currency AS b", " b.CurrencyID=b.CurrencyID AND FIND_IN_SET(b.CurrencyID, a.Currencies) ", "left"); 
		$this->db->join("csa_currency AS c", " c.CurrencyID=c.CurrencyID AND FIND_IN_SET(c.CurrencyID, a.BlockedCurrencies) ", "left");   
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		if(count($like_arr) > 0)$this->db->like($like_arr, "before");   
		$this->db->having("a.UrlID > 0");
		$this->db->order_by("a.DateUpdated", "DESC");
 	 
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
	        
	public function manageUrl_($table, $datax, $action="add", $index="", $index_value=""){  
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