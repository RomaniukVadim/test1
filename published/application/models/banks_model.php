<?php
class Banks_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct(); 
    }
	
	 
	public function getActivityById_($activity_id)
	{	
		//$this->db->limit($paging['limit'], $paging['page']);
		$sql = " SELECT a.*, b.Abbreviation AS CurrencyName, c.Name AS Method, d.Source AS ActivitySource, 
							(CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
							 f.Name AS DepositMethodName, g.mb_nick AS UserUpdated, 
							h.ReasonName, i.Name AS GroupAssigneeName, 
							l.AdjustmentName 
				 FROM csa_bank_activities AS a 
					LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
					LEFT JOIN csa_bank_category AS c ON a.CategoryID=c.CategoryID
					LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
					LEFT JOIN csa_status AS e ON a.Status=e.StatusID
					LEFT JOIN csa_bank_methods AS f ON a.DepositMethodID=f.MethodID   
					LEFT JOIN g4_member AS g ON a.UpdatedBy=g.mb_no 
					LEFT JOIN csa_analysis_reasons AS h ON a.AnalysisReason=h.ReasonID
					LEFT JOIN csa_users_group AS i ON a.GroupAssignee=i.GroupID 
					LEFT JOIN csa_account_adjustments AS l ON a.AccountAdjustment=l.AdjustmentID 
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
	
	public function getCountBankActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;    
		 
		$sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				FROM csa_bank_activities as a USE INDEX ({$index})
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
	  
	public function getBankActivities_($search_string, $allowed_status, $paging=array(), $index="ActivitiesUpdatedKey", $order_by="a.DateUpdatedInt")
	{	
		//$this->db->limit($paging['limit'], $paging['page']);
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']}":"";
		$search_string = ($search_string)?" WHERE {$search_string} ":$search_string;    
		
		$sql = "SELECT a.*, aa.*,  
					   b.Abbreviation AS Currency, 
					   c.Name AS Method, 
					   d.Source AS ActivitySource, 
					   (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					   f.mb_nick AS UpdatedByNickname, 
					   g.mb_nick AS CreatedByNickname, 
					   g.mb_usertype AS AddedUserType, 
					   i.Name AS GroupAssigneeName, 
					   h.CountAttach,
					   j.ReasonName, 
					   k.Name AS DepositMethodName, 
					   l.AdjustmentName    
				 FROM  
					(
						SELECT *    
						FROM
						 ( 
							SELECT *, @cnt := (@cnt + 1) as 'cnt' 
							FROM 
							 (
								 SELECT a.ActivityID, a.DateUpdatedInt, a.DateAddedInt 
								 FROM csa_bank_activities as a USE INDEX ({$index})
								 {$search_string}
							 ) AS a   
						 )AS a
						ORDER BY {$order_by} DESC 
						{$limit_str}   
						 
					) AS a   
					LEFT JOIN csa_bank_activities aa on a.ActivityID = aa.ActivityID 
					LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
					LEFT JOIN csa_bank_category AS c ON aa.CategoryID=c.CategoryID
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
						WHERE Activity='deposit_withdrawal'
						GROUP BY ActivityID
					) h ON a.ActivityID = h.ActivityID 
					LEFT JOIN csa_users_group AS i ON aa.GroupAssignee=i.GroupID
					LEFT JOIN csa_analysis_reasons AS j ON aa.AnalysisReason=j.ReasonID 
					LEFT JOIN csa_bank_methods AS k ON aa.DepositMethodID=k.MethodID 
					LEFT JOIN csa_account_adjustments AS l ON aa.AccountAdjustment=l.AdjustmentID 
				"; 
		 
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
	
	public function getActivityMethodsList_($type)
	{  
		$this->db->select('CategoryID, Name');
		$this->db->where(array('Category = ' => $type, 'Status =' => '1')); 
		$this->db->order_by("Name", "ASC"); 
		$result = $this->db->get('csa_bank_category');  
		return $result->result();
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
	
	public function getDepositMethodsList_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.*, b.Abbreviation AS CurrencyName 
				FROM csa_bank_methods AS a	 
				LEFT JOIN csa_currency AS b ON a.CurrencyID=b.CurrencyID 
				WHERE a.MethodID<>0 $where_str   
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC 
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql);   
		 
		return $result->result();
	}
	 
	 
	public function getDepositMethodById_($where_arr) 
	{ 					   
		$this->db->select("a.*, b.Abbreviation, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_bank_methods a');  
		$this->db->join('csa_currency b', 'a.CurrencyID=b.CurrencyID', 'left');
		$this->db->where($where_arr);  
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
	
	public function getCategoriesList_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.* 
				FROM csa_bank_category AS a	  
				WHERE a.CategoryID <> 0 $where_str 
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql); 
		return $result->result();
	}
	 
	public function getCategoryById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_bank_category a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
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
	 
	//FOR SEARCH ACTIVITIES  
	public function countActivitiesSearch_($search_string="", $search_string2="", $table="csa_bank_activities")
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
				 INNER JOIN csa_bank_activities AS b ON a.ActivityID=b.ActivityID  
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
	
	public function getSearchActivities_($search_string="", $search_string2="", $allowed_status, $paging=array())
	{	 
		$limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":""; 
		$search_string = ($search_string)?" WHERE {$search_string} ":"";
		//$search_string2 = ($search_string2)?" WHERE {$search_string2} ":"";
		
		$sql = "SELECT a.*, b.*, b.DateUpdated AS DateLastUpdated, b.Status AS CurrentStatus,  
					   c.Name AS Method, d.Source AS ActivitySource, 
					   (CASE WHEN a.SearchStatus = '0' THEN 'New' ELSE e.Name END) AS StatusName,  
					   f.mb_nick AS UpdatedByNickname, g.mb_nick AS CreatedByNickname, 
					   g.mb_usertype AS AddedUserType, 
					   h.CountAttach, 
					   i.Abbreviation AS Currency, 
					   k.Name AS CurrentStatusName, 
					   l.Name AS GroupAssigneeName, 
					   IFNULL(m.Name, '') AS DepositMethodName 
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
							INNER JOIN csa_bank_activities AS b ON a.SearchActivityID=b.ActivityID 
								{$search_string2} 
						
						 )AS a		
						ORDER BY a.SearchDateUpdatedInt  
						{$limit_str }  
						
					) AS a
				LEFT JOIN csa_bank_activities AS b ON a.SearchActivityID=b.ActivityID
				LEFT JOIN csa_bank_category AS c ON b.CategoryID=c.CategoryID
				LEFT JOIN csa_source AS d ON b.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.SearchStatus=e.StatusID 
				LEFT JOIN g4_member AS f ON a.SearchUpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON b.AddedBy=g.mb_no     
				LEFT JOIN csa_currency AS i ON b.Currency=i.CurrencyID 
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='deposit_withdrawal'
						GROUP BY ActivityID
					) h ON a.SearchActivityID = h.ActivityID
				LEFT JOIN csa_status AS k ON b.Status=k.StatusID 	
				LEFT JOIN csa_users_group AS l ON b.GroupAssignee=l.GroupID
				LEFT JOIN csa_bank_methods AS m ON b.DepositMethodID=m.MethodID "; 
				
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
		//return $result->result();  
		 
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					); 
	 
	}  
	//END FOR SEARCH ACTIVITIES
	
	
	public function getAnalysisReasons_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.*, b.Name AS CategoryName  
				FROM csa_analysis_reasons AS a	  
				LEFT JOIN csa_analysis_category AS b ON a.CategoryID=b.CategoryID 
				WHERE a.CategoryID <> 0 $where_str 
				ORDER BY a.CategoryID ASC, a.Status ASC, a.ReasonName ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql); 
		return $result->result();
	}
	
	public function getAnalysisReasonsById_($where_arr=array(), $select="", $order="") 
	{  			   
		if($select)
		 { 
		 	$this->db->select($select);
		 }
		else
		 {
			 
			$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName,  
							   b.Name AS CategoryName
							  ");
		 }
		 
		$this->db->from('csa_analysis_reasons a');  
		$this->db->join('csa_analysis_category b', 'a.CategoryID=b.CategoryID', 'left');
		if(count($where_arr) > 0)$this->db->where($where_arr);  
	 
		$this->db->order_by("a.Status", "DESC");
		$this->db->order_by("a.ReasonID", "ASC");
		   
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
	
	public function getAnalysisReasonsList_($where_arr=array(), $select="", $order="") 
	{  			   
		if($select)
		 { 
		 	$this->db->select($select);
		 }
		else
		 {
			 
			$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName,  
							   b.Name AS CategoryName
							  ");
		 }
		 
		$this->db->from('csa_analysis_reasons a');  
		$this->db->join('csa_analysis_category b', 'a.CategoryID=b.CategoryID', 'left');
		if(count($where_arr) > 0)$this->db->where($where_arr);  
	 
		$this->db->order_by("a.Status", "DESC");
		$this->db->order_by("a.ReasonID", "ASC");
		   
 		$result = $this->db->get();    
		return $result->result();
	}  
	
	
	public function getAnalysisCategories_($where_str, $paging=array())
	{ 
		$sql = "SELECT a.* 
				FROM csa_analysis_category AS a	   
				WHERE a.CategoryID <> 0 $where_str 
				ORDER BY a.CategoryID ASC, a.Status ASC, a.Name ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} "; 
		$result = $this->db->query($sql);  
		return $result->result();
	} 
	
	public function getAnalysisCategoryById_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_analysis_category a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
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
	
	public function getAccountAdjustments_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_account_adjustments a');   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.AdjustmentID", "ASC");
 		$result = $this->db->get();  
		    
		return $result->result();
		
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