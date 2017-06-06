<?php
class Manage_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
		$this->callers = "agent,crm";
    } 
	
	public function manageSettings_($table, $datax, $action="add", $index="", $index_value=""){  
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
  
	public function getPromotionById_($where_arr=array(), $where_or=array()) 
	{  			   
		$this->db->select("a.*, b.Name AS ProductName, c.Abbreviation AS CurrencyName, d.Name AS CategoryName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_promotions a');  
		$this->db->join('csa_products AS b', 'a.ProductID=b.ProductID', 'left'); 
		$this->db->join('csa_currency AS c', 'a.CurrencyID=c.CurrencyID', 'left');
		$this->db->join('csa_promotion_categories AS d', 'a.CategoryID=d.CategoryID', 'left');
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		if(count($where_or) > 0)$this->db->or_where($where_or);  
		$this->db->order_by("a.Name", "ASC");
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
	
	
	public function getPromotionCategoryById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_promotion_categories a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");
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
	
	
	public function getCallOutcomesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";
		
		$sql = "SELECT a.*, b.result_name
				FROM call_outcome AS a	  
				LEFT JOIN call_result AS b ON a.result_id=b.result_id
				WHERE a.outcome_id <> 0 $where_str 
				ORDER BY a.outcome_status, a.outcome_name ASC
			    {$limit_query} "; 
		$result = $this->db->query($sql);  
		return $result->result();  
	}
	  
	public function getCallOutcomeById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*,  b.result_id, b.result_name , (CASE WHEN a.outcome_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('call_outcome a');    
		$this->db->join('call_result AS b', 'a.result_id=b.result_id', 'left'); 
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.outcome_name", "ASC");
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
	
 	
	public function getCallResultsList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";
		
		$sql = "SELECT a.* 
				FROM call_result AS a	   
				WHERE a.result_id <> 0 $where_str 
				ORDER BY a.result_status, a.result_name ASC
			    {$limit_query} "; 
		$result = $this->db->query($sql);  
		return $result->result();  
	}
	
	public function getCallResultById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.result_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('call_result a');     
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.result_id", "ASC");
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
	 
	
	public function getChatGroupsList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		$sql = "SELECT a.*, 
					   (CASE WHEN a.UserTypes IS NULL THEN '' ELSE GROUP_CONCAT(b.Name SEPARATOR ', ' ) END) AS UserGroupTypes, 
					   c.Abbreviation AS c 
				FROM csa_chat_group AS a	  
				LEFT JOIN csa_users_group AS b ON FIND_IN_SET(b.GroupID, a.UserTypes) 
				LEFT JOIN csa_currency AS c ON a.Currency=c.CurrencyID 
				WHERE a.GroupID <> 0 $where_str   
				GROUP BY a.GroupID  
				ORDER BY a.Name ASC   
			    {$limit_query}  
				"; 
		$result = $this->db->query($sql);   
		return $result->result();  
	}
	
	  
	public function getChatGroupById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, b.Abbreviation AS CurrencyName", false);
		$this->db->from('csa_chat_group a');    
		//$this->db->join("csa_users_group AS b", "FIND_IN_SET(`b`.`Value`, `a`.`UserTypes`) ", "left"); 
		$this->db->join("csa_currency AS b", "a.Currency=b.CurrencyID", "left"); 
		 
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");  
		
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
	
	
	public function createCustomChatGroup_($where_arr=array(), $excluded=array()) 
	{  			   
		$this->db->select("a.GroupID AS id, a.Name AS title, a.Icon AS icon, a.UserTypes AS users, a.Currency, a.SpecificUsers");
		$this->db->from('csa_chat_group a'); 
		
		if($excluded[allow_default]) //
		 {
			if(!in_array($this->session->userdata(mb_usertype), $excluded[user_types]) && !in_array($this->session->userdata(mb_no), $excluded[users]) ) 
			 {
				/*$this->db->where("FIND_IN_SET('{$this->session->userdata(mb_usertype)}', a.UserTypes) !=", 0);  
				$this->db->or_where("FIND_IN_SET('{$this->session->userdata(mb_no)}', a.SpecificUsers) !=", 0);*/	 
				$this->db->where("(FIND_IN_SET('{$this->session->userdata(mb_usertype)}', a.UserTypes) OR FIND_IN_SET('{$this->session->userdata(mb_no)}', a.SpecificUsers) )");  
			 }
			 
		 }
		else
		 {
			/*$this->db->where("FIND_IN_SET('{$this->session->userdata(mb_usertype)}', a.UserTypes) !=", 0);  
			$this->db->or_where("FIND_IN_SET('{$this->session->userdata(mb_no)}', a.SpecificUsers) !=", 0);	 */ 
			$this->db->where("(FIND_IN_SET('{$this->session->userdata(mb_usertype)}', a.UserTypes) OR FIND_IN_SET('{$this->session->userdata(mb_no)}', a.SpecificUsers) )"); 
							
		 }
		
		if(count($where_arr) > 0)$this->db->where($where_arr);   
		 
		$this->db->order_by("a.Name", "ASC");   
 		$result = $this->db->get();      
	 
		return $result->result();    
	}
	
	//USERS
	public function getUsersList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, GROUP_CONCAT(b.Abbreviation SEPARATOR ', ' ) AS Currencies, c.Name AS UserType, 
					   d.mb_nick AS UpdatedByNickname 
				FROM g4_member AS a	  
				LEFT JOIN csa_currency AS b ON FIND_IN_SET(b.CurrencyID, a.mb_currencies) 
				LEFT JOIN csa_users_group AS c ON a.mb_usertype=c.GroupID  
				LEFT JOIN g4_member AS d ON a.mb_updatedby=d.mb_no
				WHERE a.mb_no <> 0 AND a.mb_name='csa' $where_str   
				GROUP BY a.mb_no 
				HAVING a.mb_no > 0
				ORDER BY a.mb_status ASC, a.mb_today_login DESC, a.mb_id DESC 
			    {$limit_query}  
				"; 
		$result = $this->db->query($sql);     
		return $result->result();  
	}
	
	  
	public function getUserById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.mb_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, b.Name AS UserTypeName", false);
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
	
	
	//CURRENCIES
	public function getCurrencyAll_($where_arr) 
	{  
		$this->db->select("a.CurrencyID, a.Abbreviation, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_currency AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Abbreviation', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	} 
	
	public function getCurrenciesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName
				FROM csa_currency AS a	   
				WHERE a.CurrencyID <> 0 $where_str  
				ORDER BY a.Status DESC, a.Abbreviation DESC 
			    {$limit_query}  
				"; 
		$result = $this->db->query($sql);     
		return $result->result();  
	}
	
	public function getCurrencyById_($where_arr) 
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
	
	
	//STATUS
	public function getStatusList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, GROUP_CONCAT(b.Name SEPARATOR ', ' ) AS StatusUsers
				FROM csa_status AS a	  
				LEFT JOIN csa_users_group AS b ON FIND_IN_SET(b.GroupID, a.Users) 
				WHERE a.StatusID <> 0 $where_str   
				GROUP BY a.StatusID 
				HAVING StatusUsers<>''
				ORDER BY a.Status ASC, a.Name ASC   
			    {$limit_query}  
				"; 
		$result = $this->db->query($sql);    
		return $result->result();  
	}
	
	public function getStatusById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, 
								(CASE WHEN a.IsHighlight = '1' THEN 'Yes' ELSE 'No' END) IsHighlighText", false);
		$this->db->from('csa_status a');     
		//$this->db->join('csa_users_group AS b', 'a.mb_usertype=b.Value', 'left');  
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Status", "ASC");   
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
	
	//ACTIVITY SOURCE
	public function getActivitySourceList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName
				FROM csa_source AS a	   
				WHERE a.SourceID <> 0 $where_str    
				ORDER BY a.Status ASC, a.Source ASC   
			    {$limit_query}  
				"; 
		$result = $this->db->query($sql);    
		return $result->result();  
	}
	
	public function getActivitySourceById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_source a');       
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Status", "ASC");   
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
	
	//PAGES	
	public function getPagesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, 
						     GROUP_CONCAT(b.Name SEPARATOR ', ' ) AS StatusListName
				FROM csa_pages AS a	   
				LEFT JOIN csa_status AS b ON FIND_IN_SET(b.StatusID, a.StatusList) 
				WHERE a.PageID <> 0 $where_str     
				GROUP BY a.PageID 
				HAVING StatusListName<>''
				ORDER BY a.Status ASC, a.Name ASC   
			    {$limit_query}  
				";  	
		$result = $this->db->query($sql);    
		return $result->result();  
	}
	
	public function getPagesById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_pages a');       
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Status", "ASC");   
		$this->db->order_by("a.Name", "ASC");   
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
	
	//12bet checking category	
	public function getCheckingCategoriesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName 
				FROM csa_checking_category AS a	    
				WHERE a.CategoryID <> 0 $where_str   
				ORDER BY a.Status ASC, a.CategoryName ASC   
			    {$limit_query}  
				";  	
		$result = $this->db->query($sql);    
		return $result->result();  
	}
	
	public function getCheckingCategoriesById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_checking_category a');       
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Status", "ASC");   
		$this->db->order_by("a.CategoryName", "ASC");   
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
	
	//12bet checking	
	public function getCheckingList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, 
					   b.CategoryName, c.Abbreviation 
				FROM csa_12beturl AS a	     
				LEFT JOIN csa_checking_category AS b ON a.Category=b.CategoryID 
				LEFT JOIN csa_currency AS c ON a.Currency=c.CurrencyID 
				WHERE a.UrlID <> 0 $where_str      
				ORDER BY a.Status ASC, a.UrlName ASC   
			    {$limit_query}  
				";  	
		$result = $this->db->query($sql);    
		return $result->result();  
	}
	
	public function getCheckingById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, 
						   b.CategoryName, c.Abbreviation
						 ", false);
		$this->db->from('csa_12beturl a');       
		$this->db->join('csa_checking_category AS b', 'a.Category=b.CategoryID', 'left'); 
		$this->db->join('csa_currency AS c', 'a.Currency=c.CurrencyID', 'left'); 
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Status", "ASC");   
		$this->db->order_by("a.UrlName", "ASC");   
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
	
	
	//USER TYPES
	public function getUserTypesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";  
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName
				FROM csa_users_group AS a	   
				WHERE a.GroupID <> 0 $where_str    
				ORDER BY a.Status ASC, a.Name ASC   
			    {$limit_query}  
				"; 
		$result = $this->db->query($sql);     
		return $result->result();  
	}
					 
					 
	public function getUserTypeById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_users_group a');       
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Status", "ASC");   
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
	
	
	public function getResultCategoriesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";
		if($where_str)$where_query = " WHERE ".$where_str;
		$sql = "SELECT SQL_CALC_FOUND_ROWS a.*, b.result_name AS ResultName   
				FROM csa_result_categories AS a	    
				LEFT JOIN call_result AS b ON a.Result=b.result_id
				{$where_query}
				ORDER BY a.Status, a.Name ASC
			    {$limit_query} "; 
		 
		$result = $this->db->query($sql);   
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS CountRecords'); 
		return array("total_rows"=>$query->row()->CountRecords, 
					 "results"=>$result->result()
					);
					
		//return $result->result();  
	}
	
	public function getResultCategoryById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, b.result_name AS ResultName ");
		$this->db->from('csa_result_categories a');      
		$this->db->join('call_result AS b', 'a.Result=b.result_id', 'left'); 
		
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.CategoryID", "ASC");
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