<?php
class Access_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
    }
	 
	
	public function getActivityById_($where_arr=array())
	{	   
		$this->db->select("a.*, b.CurrencyID, b.Abbreviation AS CurrencyName, c.Name AS ProblemName, d.Source AS ActivitySource, 
						   (CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
						   f.mb_nick AS UserUpdated, g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
						   COUNT(DISTINCT i.AttachID) As CountAttach, 
						   j.Name AS GroupAssigneeName 
						   ");
		$this->db->from('csa_website_access AS a');  
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
		$this->db->join('csa_access_problems AS c', 'a.Problem=c.ProblemID', 'left');
		$this->db->join('csa_source AS d', 'a.Source=d.SourceID', 'left'); 
		$this->db->join('csa_status AS e', 'a.Status=e.StatusID', 'left'); 
		$this->db->join('g4_member AS f', 'a.UpdatedBy=f.mb_no', 'left');
		$this->db->join('g4_member AS g', 'a.AddedBy=g.mb_no', 'left'); 
		$this->db->join('csa_attach_file AS i', "a.ActivityID=i.ActivityID  AND i.Activity='website_mobile'", 'left');  
		$this->db->join('csa_users_group AS j', 'a.GroupAssignee=j.GroupID', 'left'); 
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
	
	
	public function getCountAccessActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;    
		 
		$sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				FROM csa_website_access as a USE INDEX ({$index})
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
	 
	public function getAccessActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	  
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']}":""; 
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;   
		
		$sql = "SELECT a.*, 
					 aa.*, 
					 b.Abbreviation AS Currency, 
					 c.Name AS ProblemName, 
					 d.Source AS ActivitySource, 
					 (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					 f.mb_nick, 
					 g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
					 i.CountAttach, 
					 j.Name AS GroupAssigneeName 
				 FROM  
					(
						SELECT a.* 
						FROM 
						 (
							SELECT *, @cnt := (@cnt + 1) as 'cnt'  
							FROM 
								(
								 SELECT a.ActivityID, a.DateUpdatedInt, a.DateAddedInt 
									FROM csa_website_access as a USE INDEX ({$index})
									{$search_string} 
								) AS a   
						 
						 )AS a
						ORDER BY {$order_by} DESC 
						{$limit_str} 
							
					)AS a
				LEFT JOIN csa_website_access aa on a.ActivityID = aa.ActivityID     
				LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
				LEFT JOIN csa_access_problems AS c ON aa.Problem=c.ProblemID 
				LEFT JOIN csa_source AS d ON aa.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON aa.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON aa.UpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON aa.AddedBy=g.mb_no    
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='website_mobile'
						GROUP BY ActivityID
					) i ON aa.ActivityID = i.ActivityID
				LEFT JOIN csa_users_group AS j ON aa.GroupAssignee=j.GroupID ";
			   
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
	 
	public function getProblemsList_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.* 
				FROM csa_access_problems AS a	  
				WHERE a.ProblemID <> 0 $where_str 
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql);   
		return $result->result();  
	}
	 
	public function getProblemById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_access_problems a');   
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
	
	
	//FOR SEARCH ACTIVITIES  
	public function countActivitiesSearch_($search_string="", $search_string2="", $table="csa_website_access")
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
				 INNER JOIN csa_website_access AS b ON a.ActivityID=b.ActivityID  
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
		
		$sql = "SELECT 	 a.*, 
						 b.*, b.DateUpdated AS DateLastUpdated, b.Status AS CurrentStatus,  
						 c.Name AS ProblemName, 
						 d.Source AS ActivitySource, 
						 (CASE WHEN a.SearchStatus = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
						 f.mb_nick, 
						 g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
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
						INNER JOIN csa_website_access AS b ON a.SearchActivityID=b.ActivityID 
							{$search_string2}  
							
					 ) AS a
					ORDER BY a.SearchDateUpdatedInt  
					{$limit_str }
				) AS a 
				LEFT JOIN csa_website_access AS b ON a.SearchActivityID=b.ActivityID 
				LEFT JOIN csa_access_problems AS c ON b.Problem=c.ProblemID 
				LEFT JOIN csa_source AS d ON b.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.SearchStatus=e.StatusID 
				LEFT JOIN g4_member AS f ON a.SearchUpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON b.AddedBy=g.mb_no    
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='website_mobile'
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