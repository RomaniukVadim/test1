<?php
class Api_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
    }
	
	 
	public function getCategoriesList_($where_arr, $paging=array())
	{  
		$this->db->select("CategoryID, Name AS CategoryName, Assignee, ShowInInternal, InternalStatus");
		$this->db->from('csa_bank_category');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("Name", "DESC");
		
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
	  
	  
	public function getUserById_($where_arr=array()) 
	{  			   
		$this->db->select("a.mb_no, a.mb_nick, (CASE WHEN a.mb_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, b.Name AS UserTypeName", false);
		$this->db->from('g4_member a');     
		$this->db->join('csa_users_group AS b', 'a.mb_usertype=b.GroupID', 'left'); 
		 
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.mb_status", "ASC");  
		
 		$result = $this->db->get();    
		   
		//return $result->result(); 
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
	
	public function getCurrencyById_($where_arr=array()) 
	{  
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, 
								(CASE WHEN a.IsChecking = '1' THEN 'Yes' ELSE 'No' END) IsCheckingName", false);
		$this->db->from('csa_currency AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Status', 'DESC'); 
		$this->db->order_by('a.Abbreviation', 'ASC'); 
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
	
	public function manageActivity_($table, $datax, $action="add", $index="", $index_value=""){  
		$data = array(); 
		
		foreach($datax as $field => $value){ 
			 $data[$field] = $value; 
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
		 
	/* Private Functions */
	/*private function select_strict ($where=array(),$table = "",$order=array(),$offset=0,$limit=0){
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
	}*/
	
	/* End of Private Functions */
}

?>