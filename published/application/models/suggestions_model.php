<?php
class Suggestions_Model extends CI_Model {
	
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
							f.Name AS DepositMethodName, g.mb_nick AS UserUpdated, 
							h.Name As GroupAssigneeName 
				 FROM csa_bank_activities AS a 
					LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
					LEFT JOIN csa_bank_category AS c ON a.CategoryID=c.CategoryID
					LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
					LEFT JOIN csa_status AS e ON a.Status=e.StatusID
					LEFT JOIN csa_bank_methods AS f ON a.DepositMethodID=f.MethodID   
					LEFT JOIN g4_member AS g ON a.UpdatedBy=g.mb_no
					LEFT JOIN csa_users_group AS h ON a.GroupAssignee=h.GroupID
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
		$this->db->select("a.*, b.CurrencyID, b.Abbreviation AS CurrencyName, c.Name AS ComplaintName, d.Source AS ActivitySource, 
					       (CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
						   f.mb_nick AS UserUpdated, g.Name AS ProductName, 
						   h.Name AS GroupAssigneeName, 
						   i.PeriodName AS SelfExclusionPeriodName 
						   ");
		$this->db->from('csa_suggestions_complaints AS a');  
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
		$this->db->join('csa_complaints_types AS c', 'a.ComplaintType=c.ComplaintID', 'left');
		$this->db->join('csa_source AS d', 'a.Source=d.SourceID', 'left'); 
		$this->db->join('csa_status AS e', 'a.Status=e.StatusID', 'left'); 
		$this->db->join('g4_member AS f', 'a.UpdatedBy=f.mb_no', 'left');
		$this->db->join('csa_products AS g', 'a.Product=g.ProductID', 'left'); 
		$this->db->join('csa_users_group AS h', 'a.GroupAssignee=h.GroupID', 'left'); 
		$this->db->join('csa_exclusion_periods AS i', 'a.SelfExclusionPeriod=i.PeriodID', 'left'); 
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
	
	
	public function getCountSuggestionsActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;    
		 
		$sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				FROM csa_suggestions_complaints as a USE INDEX ({$index})
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
	
	 
	public function getSuggestionsActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	      
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']}":""; 
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;   
		
		$sql = "SELECT  a.*, 
						aa.*, 
						b.Abbreviation AS Currency, 
						c.Name AS ComplaintName, 
						d.Source AS ActivitySource, 
					   (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					   f.mb_nick, 
					   g.mb_nick AS CreatedByNickname, 
					   g.mb_usertype AS AddedUserType, 
					   h.Name AS ProductName,
					   i.CountAttach, 
					   j.Name AS GroupAssigneeName, 
					   k.PeriodName AS SelfExclusionPriodName 
				 FROM  
					(
						SELECT a.*  
						FROM 
						 (	
							SELECT a.*, @cnt := (@cnt + 1) as 'cnt'   
							FROM 
								(
								 SELECT a.ActivityID, a.DateUpdatedInt, a.DateAddedInt 
									FROM csa_suggestions_complaints as a USE INDEX ({$index})
									{$search_string} 
								) AS a  
						 )as a
						ORDER BY {$order_by} DESC 
						{$limit_str} 
							
					)AS a
				LEFT JOIN csa_suggestions_complaints aa on a.ActivityID = aa.ActivityID     
				LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
				LEFT JOIN csa_complaints_types AS c ON aa.ComplaintType=c.ComplaintID 
				LEFT JOIN csa_source AS d ON aa.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON aa.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON aa.UpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON aa.AddedBy=g.mb_no  
				LEFT JOIN csa_products AS h ON aa.Product=h.ProductID  
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='suggestions_complaints'
						GROUP BY ActivityID
					) i ON a.ActivityID = i.ActivityID
				LEFT JOIN csa_users_group AS j ON aa.GroupAssignee=j.GroupID 
				LEFT JOIN csa_exclusion_periods AS k ON aa.SelfExclusionPeriod=k.PeriodID "; 
		 
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
	 
	public function getTypesList_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.* 
				FROM csa_complaints_types AS a	  
				WHERE a.ComplaintID <> 0 $where_str 
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql); 
		return $result->result();  
	}
	 
	public function getTypeById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_complaints_types a');   
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
	
	
	public function getProducts_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_products a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.Name", "ASC");
 		$result = $this->db->get();  
		    
		return $result->result();
		
	}  
	
	public function getExclusionPeriods_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_exclusion_periods a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.PeriodID", "ASC");
 		$result = $this->db->get();  
		    
		return $result->result();
		
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
				 INNER JOIN csa_suggestions_complaints AS b ON a.ActivityID=b.ActivityID  
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
		 
		$sql = " 
				SELECT 	 a.*, 
						 b.*, b.DateUpdated AS DateLastUpdated, b.Status AS CurrentStatus, 
						 c.Name AS ComplaintName, 
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
						INNER JOIN csa_suggestions_complaints AS b ON a.SearchActivityID=b.ActivityID 
							{$search_string2} 
					
					 )AS a
					ORDER BY a.SearchDateUpdatedInt  
					{$limit_str } 
					
				) AS a 
				LEFT JOIN csa_suggestions_complaints AS b ON a.SearchActivityID=b.ActivityID  
				LEFT JOIN csa_complaints_types AS c ON b.ComplaintType=c.ComplaintID 
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
						WHERE Activity='suggestions_complaints'
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