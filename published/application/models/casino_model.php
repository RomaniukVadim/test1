<?php
class Casino_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
    }
	 
	
	public function getActivityById_($where_arr=array())
	{	 
		$this->db->select("a.*, TIMEDIFF(a.CallEnd, a.CallStart) AS CallDuration, b.CurrencyID, b.Abbreviation AS CurrencyName, c.Name AS CategoryName, 
						   d.Source AS ActivitySource, 
						   (CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
						   f.mb_nick AS UserUpdated, g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, h.Name AS SubProductName, 
						   h.MainProductID, COUNT(DISTINCT i.AttachID) As CountAttach, 
						   k.outcome_name AS OutcomeName, l.result_name AS ResultName, 
						   m.Name AS GroupAssigneeName     
						  ",false);
		$this->db->from('csa_casino_issues AS a');  
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
		$this->db->join('csa_issues_category AS c', 'a.IssueCategory=c.CategoryID', 'left');
		$this->db->join('csa_source AS d', 'a.Source=d.SourceID', 'left'); 
		$this->db->join('csa_status AS e', 'a.Status=e.StatusID', 'left'); 
		$this->db->join('g4_member AS f', 'a.UpdatedBy=f.mb_no', 'left');
		$this->db->join('g4_member AS g', 'a.AddedBy=g.mb_no', 'left'); 
		$this->db->join('csa_sub_products AS h', 'a.SubProductID=h.SubID', 'left'); 
		$this->db->join('csa_attach_file AS i', "a.ActivityID=i.ActivityID  AND i.Activity='casino_issues'", 'left');   
		$this->db->join('call_outcome AS k', 'a.CallOutcomeID=k.outcome_id', 'left'); 
		$this->db->join('call_result AS l', 'a.CallResultID=l.result_id', 'left'); 
		$this->db->join('csa_users_group AS m', 'a.GroupAssignee=m.GroupID', 'left'); 
		if(count($where_arr) > 0)$this->db->where($where_arr);   
		$this->db->group_by('a.ActivityID');
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
	
	public function getCountCasinoActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;    
		 
		$sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				FROM csa_casino_issues as a USE INDEX ({$index})
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
	 
	public function getCasinoActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	  
		
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']}":""; 
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;   
		 
		$sql = "SELECT a.*, 
					 aa.*, 
					 b.Abbreviation AS Currency, 
					 c.Name AS CategoryName, 
					 d.Source AS ActivitySource,  
					 (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					 f.mb_nick, 
					 g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
					 h.Name AS SubProductName, 
					 i.CountAttach,  
					 m.Name AS GroupAssigneeName 
				 FROM  
					(
						SELECT a.* 
						FROM 
						 (
							SELECT a.*, @cnt := (@cnt + 1) as 'cnt'
							FROM 
							 (
								SELECT a.ActivityID, a.DateUpdatedInt, a.DateAddedInt 
								FROM csa_casino_issues as a USE INDEX ({$index})
								{$search_string} 
							 ) AS a    
							
						 )AS a
						ORDER BY {$order_by} DESC 
						{$limit_str} 
							
					)AS a
				LEFT JOIN csa_casino_issues aa on a.ActivityID = aa.ActivityID     
				LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
				LEFT JOIN csa_issues_category AS c ON aa.IssueCategory=c.CategoryID 
				LEFT JOIN csa_source AS d ON aa.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON aa.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON aa.UpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON aa.AddedBy=g.mb_no    
				LEFT JOIN csa_sub_products AS h ON aa.SubProductID=h.SubID   
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='casino_issues'
						GROUP BY ActivityID
					) i ON a.ActivityID = i.ActivityID    
				LEFT JOIN csa_users_group AS m on aa.GroupAssignee=m.GroupID ";
					   
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
		//return $result->result();  
		 
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					); 
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
	 
	  
	public function getCasinoCategoriesList_($where_str, $paging=array())
	{   
		if(count($paging) > 0) $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";
		
		$sql = "SELECT a.*  
				FROM csa_issues_category AS a	  
				WHERE a.CategoryID <> 0 $where_str 
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
			    {$limit_query} "; 
		$result = $this->db->query($sql); 
		return $result->result();  
	}
	
	public function getCasinoCategoryById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_issues_category a');   
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
	 
	public function getCallOutcomeList_($where_arr=array()) 
	{  			   
		$this->db->select("a.*,  b.result_id, b.result_name , (CASE WHEN a.outcome_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('call_outcome a');    
		$this->db->join('call_result AS b', 'a.result_id=b.result_id', 'left'); 
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.outcome_name", "ASC");
 		$result = $this->db->get();  
		return $result->result();
		
	} 
	
	public function getResultList_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.result_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('call_result a');     
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.result_id", "ASC");
 		$result = $this->db->get();  
		return $result->result();
	}	
	
	public function getCasinoProductsList_($where_arr=array()) 
	{  			   
		$this->db->select("a.SubID, a.Name, a.MainProductID, a.Status, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_sub_products a');     
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");
 		$result = $this->db->get();  
		return $result->result();
	}
	
	public function getIssueCategoriesList_($where_arr=array()) 
	{  			   
		$this->db->select("a.CategoryID, a.Name, a.Status, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_issues_category a');     
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");
 		$result = $this->db->get();  
		return $result->result();
	}
	
	//FOR SEARCH ACTIVITIES  
	public function countActivitiesSearch_($search_string="", $search_string2="", $table="csa_casino_issues")
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
				 INNER JOIN csa_casino_issues AS b ON a.ActivityID=b.ActivityID  
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
		
		$sql = "SELECT a.*, 
					 b.*, b.DateUpdated AS DateLastUpdated, b.Status AS CurrentStatus,  
					 c.Name AS CategoryName, 
					 d.Source AS ActivitySource, 
					 (CASE WHEN a.SearchStatus = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					 f.mb_nick, 
					 g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
					 h.Name AS SubProductName, 
					 i.CountAttach, 
					 k.outcome_name AS CallOutcomeName, 
					 l.result_name AS CallResultName, 
					 m.Abbreviation AS Currency,  
					 (CASE WHEN b.Status = '0' THEN 'New' ELSE n.Name END) AS CurrentStatusName, 
					 o.Name AS GroupAssigneeName
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
						INNER JOIN csa_casino_issues AS b ON a.SearchActivityID=b.ActivityID 
							{$search_string2} 
					 ) AS a
					ORDER BY a.SearchDateUpdatedInt  
					{$limit_str }
				) AS a 
				LEFT JOIN csa_casino_issues AS b ON a.SearchActivityID=b.ActivityID 
				LEFT JOIN csa_issues_category AS c ON b.IssueCategory=c.CategoryID 
				LEFT JOIN csa_source AS d ON b.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.SearchStatus=e.StatusID 
				LEFT JOIN g4_member AS f ON a.SearchUpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON b.AddedBy=g.mb_no    
				LEFT JOIN csa_sub_products AS h ON b.SubProductID=h.SubID   
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='casino_issues'
						GROUP BY ActivityID
					) i ON a.SearchActivityID = i.ActivityID  
				
				LEFT JOIN call_outcome AS k on b.CallOutcomeID=k.outcome_id 
				LEFT JOIN call_result AS l on b.CallResultID=l.result_id  
				LEFT JOIN csa_currency AS m ON b.Currency=m.CurrencyID 
				LEFT JOIN csa_status AS n ON b.Status=n.StatusID 
				LEFT JOIN csa_users_group AS o ON b.GroupAssignee=o.GroupID ";	 
						   
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
		//return $result->result();  
		 
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					); 
	} 
	//END SEARCH ACTIVITIES
	
	 
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