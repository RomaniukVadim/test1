<?php
class Accounts_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
    }
	
	
	
	public function getActivityByIdX_($activity_id)
	{				   
		$sql = " SELECT a.*, b.CurrencyID, b.Abbreviation AS CurrencyName, c.Name AS Method, d.Source AS ActivitySource,  
							(CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
							f.Name AS DepositMethodName, g.mb_nick AS UserUpdated
				 FROM csa_bank_activities AS a 
					LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
					LEFT JOIN csa_bank_category AS c ON a.CategoryID=c.CategoryID
					LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
					LEFT JOIN csa_status AS e ON a.Status=e.StatusID
					LEFT JOIN csa_bank_methods AS f ON a.DepositMethodID=f.MethodID   
					LEFT JOIN g4_member AS g ON a.UpdatedBy=g.mb_no
				 WHERE a.ActivityID=".$this->db->escape($activity_id);     
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
	
	public function getActivityById_($where_arr=array())
	{	 	  
		$this->db->select("a.*, b.CurrencyID, b.Abbreviation AS CurrencyName, c.ProblemName, c.IsRegularizeAmount, 
						   d.Source AS ActivitySource, (CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
						   f.mb_nick AS UserUpdated, g.Name AS ProductName, 
						   h.Name AS GroupAssigneeName, i.Type AS IDTypeName, j.CountryName, 
						   (CASE WHEN a.ProblemCategory = 0 THEN '' ELSE k.Name END) AS ProblemCategoryName, 
						  ");
		$this->db->from('csa_account_issues AS a');  
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
		$this->db->join('csa_account_problems AS c', 'a.AccountProblem=c.ProblemID', 'left');
		$this->db->join('csa_source AS d', 'a.Source=d.SourceID', 'left'); 
		$this->db->join('csa_status AS e', 'a.Status=e.StatusID', 'left'); 
		$this->db->join('g4_member AS f', 'a.UpdatedBy=f.mb_no', 'left');
		$this->db->join('csa_products AS g', 'a.Product=g.ProductID', 'left'); 
		$this->db->join('csa_users_group AS h', 'a.GroupAssignee=h.GroupID', 'left'); 
		$this->db->join('csa_id_type AS i', 'a.IDType=i.TypeID', 'left'); 
		$this->db->join('csa_countries AS j', 'a.IssuedCountry=j.CountryID', 'left');  
		$this->db->join('csa_problem_category AS k', 'a.ProblemCategory=k.CategoryID', 'left');
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.DateUpdatedInt", "DESC"); 
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
	 
	 
	public function getActivity_($table, $conditions_array=array(), $limit=1)
	{	
		$this->db->select('*');
		$this->db->from($table);  
		if(count($conditions_array) > 0)$this->db->where($conditions_array);
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
	
	public function getCountAccountActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;    
		 
		$sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				FROM csa_account_issues as a USE INDEX ({$index})
				{$search_string} 
				";
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
	 
	public function getAccountActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	 
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']}":""; 
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;   
		
		$sql = "SELECT 	a.*, 
						aa.*, 
						b.Abbreviation AS Currency, 
						c.ProblemName, d.Source AS ActivitySource, 
						k.CountryName as CountryIssuedName, 
						l.Description as DescriptionID,
					    (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					    f.mb_nick,  
						g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
						h.Name AS ProductName, 
					    i.CountAttach, 
					    j.Name AS GroupAssigneeName, 
						(CASE WHEN aa.ProblemCategory = 0 THEN '' ELSE m.Name END) AS ProblemCategoryName
				FROM  
				(
					SELECT a.*  
					FROM 
					 (
						SELECT *, @cnt := (@cnt + 1) as 'cnt'    
						FROM 
							(
							 SELECT a.ActivityID, a.DateUpdatedInt, a.DateAddedInt   
								FROM csa_account_issues as a USE INDEX ({$index})
								{$search_string} 
							) AS a   
					 )AS a
					ORDER BY {$order_by} DESC 
					{$limit_str} 
						
				)AS a
				LEFT JOIN csa_account_issues aa on a.ActivityID = aa.ActivityID     
				LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
				LEFT JOIN csa_account_problems AS c ON aa.AccountProblem=c.ProblemID
				LEFT JOIN csa_source AS d ON aa.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON aa.Status=e.StatusID
				LEFT JOIN g4_member AS f ON aa.UpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON aa.AddedBy=g.mb_no  
				LEFT JOIN csa_products AS h ON aa.Product=h.ProductID  
				LEFT JOIN csa_countries AS k ON aa.IssuedCountry=k.CountryID
				LEFT JOIN csa_id_type AS l ON aa.IDType=l.TypeID  
				LEFT JOIN csa_problem_category AS m ON aa.ProblemCategory=m.CategoryID 
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='account_issues'
						GROUP BY ActivityID
					) i ON a.ActivityID = i.ActivityID
				LEFT JOIN csa_users_group AS j ON aa.GroupAssignee=j.GroupID ";
 
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
		//return $result->result();  
		 
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					);
	 
	} 
	 
	public function getBankCategory_()
	{ 
		$sql = "SELECT Category 
				FROM csa_bank_category	
				WHERE Status='1'  
				GROUP BY Category
				ORDER BY Category ASC  ";    		  
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
	 
	public function getActivityMethods_($type)
	{  
		$this->db->select('CategoryID, Name');
		$this->db->where(array('Category = ' => $type, 'Status =' => '1')); 
		$this->db->order_by("Name", "ASC"); 
		$result = $this->db->get('csa_bank_category'); 
		 
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
	
	public function getDepositMethods_($currency)
	{  
		$this->db->select('MethodID AS Value, Name');
		$this->db->where(array('CurrencyID = ' => $currency, 'Category = ' => 'deposit', 'Status =' => '1')); 
		$this->db->order_by("Name", "ASC"); 
		$result = $this->db->get('csa_bank_methods'); 
	 
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
	 
	public function getProblemsList_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.* 
				FROM csa_account_problems AS a	  
				WHERE a.ProblemID <> 0 $where_str 
				ORDER BY a.ProblemName ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql); 
		return $result->result();  
	}
	 
	public function getProblemById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_account_problems a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.ProblemName", "ASC");
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
	
	
	//FOR SEARCH ACTIVITIES  
	public function countActivitiesSearch_($search_string="", $search_string2="", $table="csa_account_issues")
	{ 
		 $search_string = ($search_string)?" WHERE {$search_string} ":"";
		//$search_string2 = ($search_string2)?" WHERE {$search_string2} ":"";
		 $sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				 FROM 
				   (			
					   SELECT a.ActivityID 
					   FROM csa_activities_history AS a USE INDEX (SearchActivities)   
					   {$search_string}
					   GROUP BY a.ActivityID     
					) AS a 
				 INNER JOIN csa_account_issues AS b ON a.ActivityID=b.ActivityID  
					  {$search_string2}   
					";  
  
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
	
	//SEARCH ACTIVITIES
	public function getSearchActivities_($search_string="", $search_string2="", $allowed_status, $paging=array()) 
	{	   
		$limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":""; 
		$search_string = ($search_string)?" WHERE {$search_string} ":"";
		//$search_string2 = ($search_string2)?" WHERE {$search_string2} ":"";
		
		$sql = "SELECT a.*, b.*, b.DateUpdated AS DateLastUpdated, b.Status AS CurrentStatus,  
					   c.ProblemName, 
					   d.Source AS ActivitySource, 
					   (CASE WHEN a.SearchStatus = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					   f.mb_nick,  
					   g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
					   h.Name AS ProductName, 
					   i.CountAttach, 
					   k.Abbreviation AS Currency,     
					   (CASE WHEN b.Status = '0' THEN 'New' ELSE m.Name END) AS CurrentStatusName, 
					   n.Name AS GroupAssigneeName
				FROM 
				(
					SELECT a.* 
					FROM 
					 (
						SELECT a.*, @cnt := (@cnt + 1) as 'cnt'    
						FROM 
						   (			
							   SELECT  a.ActivityID AS SearchActivityID, a.Status AS SearchStatus, a.DateUpdatedInt AS SearchDateUpdatedInt, 
									   a.GroupAssignee AS SearchGroupAssignee, a.UpdatedBy SearchUpdatedBy
							   FROM csa_activities_history AS a USE INDEX (SearchActivities)   
							   {$search_string}
							   GROUP BY a.ActivityID 
							   HAVING MAX( DateUpdatedInt )
									  
							) AS a 
						INNER JOIN csa_account_issues AS b ON a.SearchActivityID=b.ActivityID 
							{$search_string2} 
					 ) AS a
					ORDER BY a.SearchDateUpdatedInt  
					{$limit_str }
				) AS a 
				LEFT JOIN csa_account_issues AS b ON a.SearchActivityID=b.ActivityID   
				LEFT JOIN csa_account_problems AS c ON b.AccountProblem=c.ProblemID
				LEFT JOIN csa_source AS d ON b.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.SearchStatus=e.StatusID
				LEFT JOIN g4_member AS f ON a.SearchUpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON b.AddedBy=g.mb_no  
				LEFT JOIN csa_products AS h ON b.Product=h.ProductID  
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='account_issues'
						GROUP BY ActivityID
					) i ON a.SearchActivityID = i.ActivityID
				LEFT JOIN csa_currency AS k ON b.Currency=k.CurrencyID		
				LEFT JOIN csa_status AS m ON b.Status=m.StatusID 		
				LEFT JOIN csa_users_group AS n ON b.GroupAssignee=n.GroupID ";  
				 
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
		//return $result->result();  
		 
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					); 
	 
	} 
	//END SEARCH ACTIVITIES
	 
	public function getAccountProblemCategory_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, b.ProblemName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_problem_category a');    
		$this->db->join('csa_account_problems AS b', 'a.AccountProblem=b.ProblemID', 'left');  
		
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");   
		  
 		$result = $this->db->get();    
		return $result->result();  
	}
	
	public function getCategoriesList_($where_arr=array(), $paging=array())
	{   
		$this->db->select("SQL_CALC_FOUND_ROWS a.*, b.ProblemName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_problem_category a');    
		$this->db->join('csa_account_problems AS b', 'a.AccountProblem=b.ProblemID', 'left');  
		
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");  
		if(count($paging) > 0)$this->db->limit($paging['limit'], $paging['page']);
 		 
 		$result = $this->db->get();    
		   
		$query = $this->db->query("SELECT FOUND_ROWS() AS Count ");   
		 
		return array("results"=>$result->result(), 
					 "total_rows"=>$query->row()->Count
					);    
	}
	
	public function getProblemCategoryById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_problem_category a');   
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