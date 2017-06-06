<?php
class Checking_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();    
						   
    } 
    
	 
	public function getCheckingCategory($where_arr) 
	{  
		$this->db->select("a.CategoryID, a.CategoryName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_checking_category AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.CategoryName', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}   
	
	
	public function getCheck12Bet_($where_str, $paging=array())
	{ 
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} "; 
		 
		$sql = "SELECT a.*, b.Abbreviation, c.CategoryName, d.mb_nick AS UpdatedByNickname, 
					   GROUP_CONCAT(DISTINCT e.UrlName SEPARATOR '\n' ) AS Checked, 
					   GROUP_CONCAT(DISTINCT f.UrlName SEPARATOR '\n') AS UnChecked
				FROM csa_12beturl_check AS a USE INDEX (DateCheckedInt)
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID  
				LEFT JOIN csa_checking_category AS c on a.Category=c.CategoryID  
				LEFT JOIN g4_member AS d on a.CheckedBy=d.mb_no  
				LEFT JOIN csa_12beturl AS e ON FIND_IN_SET(e.UrlID, a.Urls) 
				LEFT JOIN csa_12beturl AS f ON (f.Category=a.Category) AND (f.Currency=a.Currency) AND NOT FIND_IN_SET(f.UrlID, a.Urls)
				WHERE a.CheckID<>0 $where_str   
				GROUP BY a.CheckID
				HAVING a.CheckID >0
				ORDER BY a.DateCheckedInt Desc, a.CheckID DESC  
				{$limit_query}  
				"; 
		$result = $this->db->query($sql); 
		return $result->result();  
	}
	 
	 
	public function getCheck12BetById_($where_arr=array())
	{	 
		$this->db->select("a.*, b.Abbreviation, c.CategoryName, d.mb_nick AS UpdatedByNickname, 
						   GROUP_CONCAT(DISTINCT e.UrlName SEPARATOR ',' ) AS Checked, 
						   GROUP_CONCAT(DISTINCT f.UrlName SEPARATOR ',' ) AS UnChecked 
						 ",false);
		
		$this->db->from('csa_12beturl_check AS a');    
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');    
		$this->db->join('csa_checking_category AS c', 'a.Category=c.CategoryID', 'left');    
		$this->db->join('g4_member AS d', 'a.CheckedBy=d.mb_no', 'left');    
		$this->db->join("csa_12beturl AS e", " e.UrlID=e.UrlID  AND FIND_IN_SET(e.UrlID, a.Urls) ",  "left");   
		$this->db->join("csa_12beturl AS f", " f.Currency=a.Currency AND f.Category=a.Category  AND NOT FIND_IN_SET(f.UrlID, a.Urls) ",  "left");    
		
		 //$this->db->where("FIND_IN_SET(a.mb_usertype, '{$call_agents}' )");
		if(count($where_arr) > 0)$this->db->where($where_arr);   
		$this->db->group_by('a.CheckID');    
		$this->db->having('a.CheckID > 0');    
		
		$this->db->order_by("a.DateCheckedInt", "DESC"); 
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
	
	
	public function get12BetChecklist_($where_arr) 
	{  
		$this->db->select("a.UrlID, a.UrlName, a.Url, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_12beturl AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.UrlName', 'ASC'); 
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
		//return $result->result(); 
	}
	
	
	public function getShiftReport_($where_arr, $paging=array()) 
	{  
		$this->db->select("a.*,(CASE WHEN a.Status = '1' THEN 'Solved' ELSE 'Pending' END) StatusName,  
						   b.ShiftName, c.mb_nick AS AddedByNickname, c.mb_usertype AS AddedUserType, 
						   d.mb_nick AS UpdatedByNickname, d.mb_usertype AS UpdatedUserType, 
						   e.Abbreviation AS CurrencyName  
						  ", false);
		$this->db->from('csa_shift_report AS a USE INDEX (DateAddedInt)');  
		$this->db->join('csa_shifts AS b', 'a.Shift=b.ShiftID', 'left'); 
		$this->db->join('g4_member AS c', 'a.AddedBy=c.mb_no', 'left'); 
		$this->db->join('g4_member AS d', 'a.UpdatedBy=d.mb_no', 'left'); 
		$this->db->join('csa_currency AS e', 'a.Currency=e.CurrencyID', 'left'); 
		    
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.DateAddedInt', 'DESC'); 
		$result = $this->db->get();     
		return $result->result(); 
		
	}
	
	
	public function getShiftReportById_($where_arr) 
	{  
		$this->db->select("a.*,(CASE WHEN a.Status = '1' THEN 'Solved' ELSE 'Pending' END) StatusName,  
						   b.ShiftName, c.mb_nick AS AddedByNickname, c.mb_usertype AS AddedUserType, 
						   d.mb_nick AS UpdatedByNickname, d.mb_usertype AS UpdatedUserType, 
						   e.Abbreviation AS CurrencyName   
						  ", false);
		$this->db->from('csa_shift_report AS a');  
		$this->db->join('csa_shifts AS b', 'a.Shift=b.ShiftID', 'left');  
		$this->db->join('g4_member AS c', 'a.AddedBy=c.mb_no', 'left');
		$this->db->join('g4_member AS d', 'a.UpdatedBy=d.mb_no', 'left'); 
		$this->db->join('csa_currency AS e', 'a.Currency=e.CurrencyID', 'left'); 
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.DateAddedInt', 'DESC'); 
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
	
	
	public function manageChecking_($table, $datax, $action="add", $index="", $index_value=""){  
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