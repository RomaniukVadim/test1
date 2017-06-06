<?php
class Reports_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();    
						   
    } 
 
	public function getCalStatusReports_($search_string, $search_string2, $status_list, $paging, $where_arr=array()) 
	{  
		 
		 $limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":""; 
		 
		 $str1 = "";
		 $str2 = "";
		  
		 if(count($status_list) > 0)
		 { 	 
			foreach($status_list as $row=>$stat) {  
				$str1 .= ", IfNull({$stat[CountName]}, 0) AS {$stat[CountName]} ";  
				$str2 .= ", SUM(if(Status={$stat[StatusID]}, 1, 0)) AS {$stat[CountName]} ";    
			}
			//$str1 = rtrim($str1, ","); 
			//$str2 = rtrim($str2, ","); 
			$status = implode(',', array_map(function ($c) { return $c[StatusID]; }, $status_list));
		 }
		
		$where_str = ($search_string)?" WHERE ".$search_string:""; 
		$where_history_stat = ($search_string2)?" AND Status IN({$status}) ":" Status IN({$status}) "; 
		  
		 $sql = "SELECT SQL_CALC_FOUND_ROWS a.mb_no, a.mb_nick $str1
			FROM `g4_member` AS a  USE INDEX (UserCommonKey)
			LEFT JOIN
				( 
					SELECT UpdatedBy $str2
					FROM csa_activities_history USE INDEX (CalReportsKey2)
					WHERE $search_string2 {$where_history_stat}
					GROUP BY UpdatedBy
				) b On b.UpdatedBy=a.mb_no     
			{$where_str}  
			ORDER BY a.mb_nick ASC    
			$limit_str    
			 ";  
		$result = $this->db->query($sql);   
		
		$query = $this->db->query('SELECT FOUND_ROWS() AS CountRecords'); 
		return array("total_rows"=>$query->row()->CountRecords, 
					 "result"=>$result->result()
					);
					  
		//return $result->result();
	}
	
	public function getCalStatusReportsDetails_($search_string, $search_string2, $paging, $where_arr=array()) 
	{  
		 
		 $limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":""; 
		 $where1 = (trim($search_string))?" WHERE ".$search_string:"";
		 $where2 = (trim($search_string2))?" WHERE ".$search_string2:"";
		  
		 $sql = "Select a.*, 
		 			    b.Abbreviation AS CurrencyName, 
						(CASE WHEN a.Status = '0' THEN 'New' ELSE c.Name END) AS StatusName, SUBSTRING_INDEX(Currency_Username,',',1) AS Currency, 		
						SUBSTRING_INDEX(Currency_Username,',',-1) AS Username, 
						d.Name AS GroupAssignee, 
						e.Remarks 
				FROM
				(
					SELECT a.*,  
						   (CASE a.Activity
							  WHEN 'deposit_withdrawal' THEN (SELECT Concat(Currency, ',', Username) FROM csa_bank_activities WHERE ActivityID=a.ActivityID )
							  WHEN 'promotion' THEN (SELECT Concat(Currency, ',', Username) FROM csa_promotion_activities WHERE ActivityID=a.ActivityID)
							  WHEN 'casino_issues' THEN (SELECT Concat(Currency, ',', Username) FROM csa_casino_issues WHERE ActivityID=a.ActivityID)
							  WHEN 'account_issues' THEN (SELECT Concat(Currency, ',', Username) FROM csa_account_issues WHERE ActivityID=a.ActivityID)
							  WHEN 'suggestions_complaints' THEN (SELECT Concat(Currency, ',', Username) FROM csa_suggestions_complaints WHERE ActivityID=a.ActivityID)
							  WHEN 'website_mobile' THEN (SELECT Concat(Currency, ',', Username) FROM csa_website_access WHERE ActivityID=a.ActivityID) 
							  ELSE 0
						   END) AS Currency_Username 
					FROM 
						(
							SELECT a.*	 
							FROM 
							 (
								SELECT HistoryID, ActivityID, Status, GroupAssignee, Activity, IsComplaint, Important, DateUpdatedInt, @cnt := (@cnt + 1) as 'cnt'    
								FROM csa_activities_history USE INDEX (CalReportsKey2) 
								{$where1} 
							  ) AS a   
							ORDER BY a.DateUpdatedInt DESC 
							{$limit_str} 
							
						) AS a  
					
				) AS a
				LEFT JOIN csa_currency AS b ON  find_in_set (b.CurrencyID, a.Currency_username)
				LEFT JOIN csa_status AS c ON a.Status=c.StatusID
				LEFT JOIN csa_users_group AS d ON a.GroupAssignee=d.GroupID    
				LEFT JOIN csa_activities_history AS e ON a.HistoryID=e.HistoryID      
			 ";  
		$this->db->query("SET @cnt := 0");
			 
		$result = $this->db->query($sql);  
		//echo $this->db->last_query(); 
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					); 
					
					
		
	}
	
	
	public function getCalSourceReports_($search_string, $search_string2, $source_list=array(), $custom_list=array(), $paging, $where_arr=array()) 
	{  
		 
		 $limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":""; 
		 
		 $str1 = "";
		 $str2 = "";
		 
		 $where_str1 = ($search_string)?" WHERE ".$search_string:""; 
		 $where_str2 = ($search_string2)?" WHERE ".$search_string2:""; 
		 	 
		 if(count($source_list) > 0)
		 { 	 
			foreach($source_list as $row=>$source) {  
				$str1 .= ", IfNull(SUM(Source_{$source->SourceID}), 0) AS Source_{$source->SourceID} ";  
				$str2 .= ", SUM(if(Source={$source->SourceID}, 1, 0)) AS Source_{$source->SourceID} ";    
			} 
			//$source = implode(',', array_map(function ($c) { return $c[StatusID]; }, $status_list));
		 }
		 
		 
		 if(count($custom_list) > 0)
		 { 	 
			/*foreach($custom_list as $row=>$custom) {  
				$str1 .= ", IfNull({$custom[CountName]}, 0) AS {$custom[CountName]} ";  
				$str2 .= ", SUM(if(IsComplaint='1', 1, 0)) AS {$custom[CountName]} ";    
			} */ 
			
			$str1 .= ", IfNull(SUM(ComplaintCount), 0) AS ComplaintCount ";  
			$str2 .= ", SUM(if(IsComplaint='1', 1, 0)) AS ComplaintCount "; 
			
			$str1 .= ", IfNull(GROUP_CONCAT(TRIM(ComplaintUsername)), '') AS ComplaintUsername ";  
			$str2 .= ", GROUP_CONCAT(IF(IsComplaint='1', TRIM(Username), NULL)) AS ComplaintUsername ";  
			 					
		 }
		 
		 $sql = "SELECT SQL_CALC_FOUND_ROWS a.mb_no, a.mb_nick $str1
				FROM `g4_member` AS a USE INDEX (UserCommonKey)
				LEFT JOIN 
					( 
						(
							SELECT AddedBy $str2
							FROM csa_bank_activities USE INDEX (CalReportsKey2)   
							{$where_str2}
							GROUP BY AddedBy 
						) 
						UNION ALL
						(
							SELECT AddedBy $str2
							FROM csa_promotion_activities USE INDEX (CalReportsKey2)       
							{$where_str2}
							GROUP BY AddedBy 
						) 
						UNION ALL
						(
							SELECT AddedBy $str2
							FROM csa_casino_issues USE INDEX (CalReportsKey2)       
							{$where_str2}
							GROUP BY AddedBy 
						) 
						UNION ALL
						(
							SELECT AddedBy  $str2
							FROM csa_account_issues USE INDEX (CalReportsKey2)       
							{$where_str2}
							GROUP BY AddedBy 
						) 
						UNION ALL
						(
							SELECT AddedBy  $str2
							FROM csa_suggestions_complaints USE INDEX (CalReportsKey2)       
							{$where_str2}
							GROUP BY AddedBy 
						) 
					    UNION ALL
						(
							SELECT AddedBy  $str2
							FROM csa_website_access USE INDEX (CalReportsKey2)       
							{$where_str2}
							GROUP BY AddedBy 
						) 
					) b On b.AddedBy=a.mb_no      
				{$where_str1}  
				GROUP BY a.mb_no  
				ORDER BY  a.mb_nick ASC    
				$limit_str    
				 ";  
		$result = $this->db->query($sql);  
	 	
		$query = $this->db->query('SELECT FOUND_ROWS() AS CountRecords'); 
		return array("total_rows"=>$query->row()->CountRecords, 
					 "result"=>$result->result()
					); 
					 
		//return $result->result();
	}
	//end agent summary report
	
	public function getCalSourceReportsDetails_($search_string, $search_string2, $activity="", $paging=array(), $index="CalReportsKey")
	{	 
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']} ":"";    
	 	$search_string = (trim($search_string))?" WHERE ".$search_string:"";
		$search_string2 = (trim($search_string2))?" WHERE ".$search_string2:"";
		
		$union_banks = "(SELECT 'banks' AS Activity, ActivityID, AddedBy, Source, Username, DateAddedInt, Status, Currency, GroupAssignee, IsComplaint, Important  
						FROM csa_bank_activities USE INDEX ({$index})    
						$search_string     
						)";
		
		$union_promotions = "(SELECT 'promotions' AS Activity, ActivityID, AddedBy, Source, Username, DateAddedInt, Status, Currency, GroupAssignee, IsComplaint, Important  
								FROM csa_promotion_activities USE INDEX ({$index})  
								 $search_string  
								)";
		
		$union_casino = "(SELECT 'casino' AS Activity, ActivityID, AddedBy, Source, Username, DateAddedInt, Status, Currency, GroupAssignee, IsComplaint, Important 
						  FROM csa_casino_issues USE INDEX ({$index}) 
						  $search_string   
						  )";
		
		$union_accounts = "(SELECT 'accounts' AS Activity, ActivityID, AddedBy, Source, Username, DateAddedInt, Status, Currency, GroupAssignee, IsComplaint, Important   
						    FROM csa_account_issues USE INDEX ({$index})  
							$search_string   
						)"; 
					
		$union_suggestions = "(SELECT 'suggestions' AS Activity, ActivityID, AddedBy, Source, Username, DateAddedInt, Status, Currency, GroupAssignee, IsComplaint, Important  
						 FROM csa_suggestions_complaints USE INDEX ({$index}) 
						 $search_string 	 
						)"; 
		
		$union_access = "(SELECT 'access' AS Activity, ActivityID, AddedBy, Source, Username, DateAddedInt, Status, Currency, GroupAssignee, IsComplaint, Important  
						 FROM csa_website_access USE INDEX ({$index})  
						 $search_string   		 
						)"; 
		
		if($activity)
		 {
			 $union_query = ${"union_".$activity}; 
		 }
		else
		 {
			 $union_query = $union_banks.
					" UNION ".$union_promotions.
					" UNION ".$union_casino.
					" UNION ".$union_accounts.
					" UNION ".$union_suggestions.
					" UNION ".$union_access; 
		 }
		
		$where_sql = ($search_string2 != "")?"{$sql} WHERE {$search_string2} ":$sql; 
		
		 
		$sql = "SELECT a.*, (CASE WHEN a.Status = '0' THEN 'New' ELSE status.Name END) AS StatusName,    
					   currencies.Abbreviation AS CurrencyName, type.Name AS GroupAssigneeName, source.Source AS SourceName 
				FROM 
					(
						SELECT results.*   
						FROM 
						 (
							SELECT results.*, @cnt := (@cnt + 1) as 'cnt'  
							FROM (
								{$union_query}	 	 
							) AS results   
							
						 ) AS results 	
						 ORDER BY results.DateAddedInt DESC 
						 $limit_str 
						 
					) AS a  
				LEFT JOIN csa_status AS status ON a.Status=status.StatusID 
				LEFT JOIN csa_currency AS currencies ON a.Currency=currencies.CurrencyID 
				LEFT JOIN csa_users_group AS type ON a.GroupAssignee=type.GroupID  
				LEFT JOIN csa_source AS source ON a.Source=source.SourceID 
				
				";
		 
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
	 	
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					);
	} 
	
	
	public function manageReports_($table, $datax, $action="add", $index="", $index_value=""){  
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
	 
   //GET CALL OUTCOME 	
   public function getCallOutcomeList_($where_arr=array()) 
	{  			   
		$this->db->select("a.*,  b.result_id, b.result_name , (CASE WHEN a.outcome_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('call_outcome a');    
		$this->db->join('call_result AS b', 'a.result_id=b.result_id', 'left'); 
		if(count($where_arr) > 0)$this->db->where($where_arr);   
		
		//if($order_by > 0)$this->db->order_by($order_by);  
		$this->db->order_by("a.result_id", "ASC"); 
		
 		$result = $this->db->get();  
		return $result->result();
		
	}   
    
   public function getCrmReports_($search_string, $search_string2,  $paging, $where_arr=array(), $index="DateUploadedInt") 
	{   
		 //$limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":"";   
		 //SUM(IF((a.ClaimedDeposited='2'), 1, 0)) AS ClaimedUsers
		 
		 $sql = "SELECT a.outcome_id, a.outcome_name, a.result_id, 
					    IfNull(CallCount, 0) AS CallCount, 
						IfNull(Callers, '') AS Callers, 
        				IfNull(ActivityIDList, '') AS ActivityIDList,
					    c.result_name 
				FROM `call_outcome` AS a
				LEFT JOIN
					( 
						SELECT x.CallOutcomeID, COUNT(DISTINCT x.ActivityID) AS CallCount, 
							   GROUP_CONCAT(DISTINCT y.UpdatedBy, '') AS Callers, 
              				   GROUP_CONCAT(DISTINCT y.ActivityID, '') AS ActivityIDList 
						FROM csa_promotion_activities AS x USE INDEX ({$index})
						LEFT JOIN csa_calls AS y ON x.ActivityID=y.ActivityID AND y.Activity='promotion' 
						WHERE (x.CallOutcomeID<>0 AND x.CallResultID<>0) $search_string2 
						GROUP BY x.CallOutcomeID   
					) b On b.CallOutcomeID=a.outcome_id       
				LEFT JOIN call_result AS c ON a.result_id=c.result_id 
				WHERE a.outcome_id<>0 $search_string
				ORDER BY a.result_id ASC, a.outcome_name ASC    
				$limit_str    
			 ";    
		$result = $this->db->query($sql);   
		return $result->result();   
	}
	
	
	public function getCrmReportsPromotions_($search_string, $search_string2, $paging, $where_arr=array(), $index="DateUploadedInt") 
	{   
		 $limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']}":"";   
		 $search_string = ($search_string) ? " WHERE {$search_string} " : $search_string;
		 $search_string2 = ($search_string2) ? " WHERE {$search_string2} " : $search_string2;
		 
		 $sql = "SELECT SQL_CALC_FOUND_ROWS a.*,  
					   b.Name AS PromotionName, b.CurrencyID As PromotionCurrency, 
					   c.Abbreviation As PromotionCurrencyName 
				 FROM (
						SELECT x.Promotion,   
								COUNT(DISTINCT x.ActivityID) AS PromotionCountBase, 
								SUM(IF((x.CallOutcomeID<>0 AND x.CallResultID<>0), 1, 0)) AS CallCount, 
								SUM(IF((x.CallResultID=1), 1, 0)) AS ReachedCount  
						FROM csa_promotion_activities AS x USE INDEX (DateUploadedInt)
						{$search_string2}
						GROUP BY x.Promotion 
					) a     		
				 LEFT JOIN csa_promotions AS b ON a.Promotion=b.PromotionID 
				 LEFT JOIN csa_currency AS c ON b.CurrencyID=c.CurrencyID  
				 ORDER BY b.CurrencyID ASC, b.Name  
				 {$limit_str}  
			 ";        
		$result = $this->db->query($sql);  
 
		$query = $this->db->query('SELECT FOUND_ROWS() AS `Count`');
		   
		return array("results"=>$result->result(), 
					 "total_rows"=>$query->row()->Count	
				    ); 
		
	}
	
	/*public function getCrmReports_($search_string, $search_string2,  $paging, $where_arr=array()) 
	{   
		 //$limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":"";  
		 
		 $sql = "SELECT a.outcome_id, a.outcome_name, a.result_id, 
					    IfNull(CallCount, 0) AS CallCount,
					    c.result_name 
				FROM `call_outcome` AS a
				LEFT JOIN
					( 
						SELECT x.CallOutcomeID, COUNT(DISTINCT x.ActivityID) AS CallCount
						FROM csa_promotion_activities AS x
						LEFT JOIN csa_calls AS y ON x.ActivityID=y.ActivityID AND y.Activity='promotion'
						WHERE (x.CallOutcomeID<>0 AND x.CallResultID<>0) $search_string2 
						GROUP BY CallOutcomeID 
					) b On b.CallOutcomeID=a.outcome_id       
				LEFT JOIN call_result AS c ON a.result_id=c.result_id 
				WHERE a.outcome_id<>0 $search_string
				ORDER BY a.result_id ASC, a.outcome_name ASC    
				$limit_str    
			 ";    
		$result = $this->db->query($sql);  
		//echo $this->db->last_query();      
		return $result->result();
	}*/
	
	public function getCrmReportsOld_($search_string, $search_string2,  $paging, $where_arr=array()) 
	{  
		 
		 //$limit_str = (count($paging) > 0)?"LIMIT {$paging['page']}, {$paging['limit']}":"";  
		 $sql = "SELECT a.outcome_id, a.outcome_name, a.result_id, 
					    IfNull(CallCount, 0) AS CallCount,
					    c.result_name 
				FROM `call_outcome` AS a
				LEFT JOIN
					( 
						SELECT CallOutcomeID, COUNT(CallID) AS CallCount
						FROM csa_calls 
						WHERE CallID<>0 $search_string2 
						GROUP BY CallOutcomeID 
					) b On b.CallOutcomeID=a.outcome_id       
				LEFT JOIN call_result AS c ON a.result_id=c.result_id 
				WHERE a.outcome_id<>0 $search_string
				ORDER BY a.result_id ASC, a.outcome_name ASC    
				$limit_str    
			 ";  
		$result = $this->db->query($sql);     
		return $result->result();
	}
	
    
	public function getCountCallResults_($search_string, $paging, $where_arr=array()) 
	{  			   
		$sql = "SELECT a.result_id, a.result_name, 
					   IfNull(b.CallCount, 0) AS CallCount
				FROM `call_result` AS a
				LEFT JOIN
					(  
						SELECT CallResultID, COUNT(CallID) AS CallCount
						FROM csa_calls 
						WHERE CallID<>0 $search_string 
						GROUP BY CallResultID 
					) b On b.CallResultID=a.result_id        
				WHERE a.result_status='1'  
				ORDER BY a.result_id ASC, a.result_name ASC    
				$limit_str    
			 ";  
		$result = $this->db->query($sql);    
		//echo $this->db->last_query(); 
		return $result->result();
		 
	} 
	
	
	public function getCallResultList_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.result_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('call_result a');     
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->order_by("a.result_id", "ASC");
 		$result = $this->db->get();  
		return $result->result();
	}
	
	public function getChangePromotion_($where_arr=array(), $where_or=array(), $active=1) 
	{  			   
		$this->db->select("a.PromotionID, a.CategoryID, a.Name, a.MinimumAmount, a.MaximumAmount, a.Formula, a.WageringFormula, a.Turnover, a.BonusRate, a.BonusCode, a.Type,
						   a.StartedDate, a.EndDate, 
						   b.Name AS ProductName, c.Abbreviation AS CurrencyName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
		$this->db->from('csa_promotions a');  
		$this->db->join('csa_products AS b', 'a.ProductID=b.ProductID', 'left'); 
		$this->db->join('csa_currency AS c', 'a.CurrencyID=c.CurrencyID', 'left'); 
		
		if($active==1)$this->db->where("DATE(NOW()) BETWEEN a.StartedDate AND a.EndDate");  
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		
		if(count($where_or) > 0)$this->db->or_where($where_or);  
		
		$this->db->order_by("a.Name", "ASC"); 
 		$result = $this->db->get();     
		return $result->result();
	}
	
	
	public function getCountUploadTotal_($where_arr=array(), $index="DateUploadedInt") 
	{  			   
		$this->db->select("COUNT(DISTINCT a.ActivityID) AS CountBase");
		$this->db->from("csa_promotion_activities a USE INDEX ({$index})");     
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		 
		//$this->db->order_by("a.ActivityID", "ASC");
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
	
	
	public function getPromotionById_($where_arr=array(), $where_or=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' 
							     	  WHEN a.Status = '9' THEN 'Deleted'   
								 ELSE 'Inactive' END) StatusName, 
						   b.Name AS ProductName, c.Abbreviation AS CurrencyName, d.Name AS CategoryName 
						");
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
	 
	public function getCrmConversions_($search_string, $search_string2, $search_string3, $search_string4, $paging ) 
	{  
		$search_string = ($search_string)?" AND {$search_string} ":"";	 
		$search_string2 = ($search_string2)?" AND {$search_string2} ":"";	  
		
		
		
		/*if($search_string3)
		 {
			$left_join = "LEFT JOIN 
							(
								SELECT b.ActivityID
								FROM csa_calls AS b  
								WHERE b.Activity='promotion' AND 
									  b.CallResultID=1 {$search_string3}   
								GROUP BY b.ActivityID    
							) b ON a.ActivityID=b.ActivityID";   
			$reach_count = " COUNT(b.ActivityID) ";     
			//$reach_count = "SUM(IF((a.CallResultID=1), 1, 0)) ";
			$total_reach_count = "SUM(a.ReachedCall) ";  
			
			$offered_count = "SUM(IF((a.OfferedBy = '0' {$search_string4}), 1, 0)) "; 
			$total_offered_count = "SUM(a.CountOffered)";
			  
			$claimed_personal_users = "SUM(IF((a.ClaimedDeposited='2' AND (IfNull(b.ActivityID, 0) > 0)), 1, 0))";
			$deposited_personal_users = "SUM(IF(((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2') AND (IfNull(b.ActivityID, 0) > 0) ), 1, 0)) ";
		 }
		else
		 {
			$left_join = ""; 
			$reach_count = "SUM(IF((a.CallResultID=1), 1, 0)) "; 
			$total_reach_count = "SUM(a.ReachedCall)";
			
			$offered_count = "SUM(IF((a.OfferedBy > 0), 1, 0)) "; 
			$total_offered_count = "SUM(a.CountOffered)";
			
			$claimed_personal_users = "SUM(IF((a.ClaimedDeposited='2' AND a.CallResultID=1), 1, 0))";
			$deposited_personal_users = "SUM(IF(((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2') AND a.CallResultID=1), 1, 0))";
		 }*/ 
		 
		$left_join = "LEFT JOIN 
							(
								SELECT b.ActivityID
								FROM csa_calls AS b  
								WHERE b.Activity='promotion' AND 
									  b.CallResultID=1 {$search_string3}   
								GROUP BY b.ActivityID    
							) b ON a.ActivityID=b.ActivityID";   
		$reach_count = " COUNT(b.ActivityID) ";     
		//$reach_count = "SUM(IF((a.CallResultID=1), 1, 0)) ";
		$total_reach_count = "SUM(a.ReachedCall) ";  
		
		$offered_count = "SUM(IF((a.OfferedBy != '0' {$search_string4}), 1, 0)) "; 
		$total_offered_count = "SUM(a.CountOffered)";
		  
		$claimed_personal_users = "SUM(IF((a.ClaimedDeposited='2' AND (IfNull(b.ActivityID, 0) > 0)), 1, 0))";
		$deposited_personal_users = "SUM(IF(((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2') AND (IfNull(b.ActivityID, 0) > 0) ), 1, 0)) ";
		 
		 
		$sql = "SELECT a.Promotion, Category, SUM(a.CountActivities) AS TotalLeads, {$total_reach_count} AS TotalReached, 
					    {$total_offered_count} AS TotalOffered, SUM(a.ClaimedUsers) AS TotalClaimed, SUM(a.DepositedUsers) AS TotalDeposited,   
					   SUM(a.ClaimedPersonalUsers) AS TotalPersonalClaimed, SUM(a.DepositedPersonalUsers) AS TotalPersonalDeposited,   
					   b.SubProductID, c.Name AS SubProductName, d.Name AS CategoryName, d.ClaimedOnly   
				FROM 
				 (
					SELECT a.Promotion, a.Category, COUNT(a.ActivityID) AS CountActivities, 
						   {$reach_count} AS ReachedCall, {$offered_count} AS CountOffered, 
						   SUM(IF((a.ClaimedDeposited='2'), 1, 0)) AS ClaimedUsers, 
						   SUM(IF((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2'), 1, 0)) AS DepositedUsers,     
						   {$claimed_personal_users} AS ClaimedPersonalUsers,  
						   {$deposited_personal_users} AS DepositedPersonalUsers    
						   
					FROM csa_promotion_activities AS a USE INDEX (ConversionCategory)    
					$left_join 
					WHERE a.IsUpload='1' {$search_string}
					GROUP BY a.Promotion, a.Category
				) AS a 
				LEFT JOIN csa_promotions AS b ON a.Promotion=b.PromotionID  
				LEFT JOIN csa_sub_products AS c ON b.SubProductID=c.SubID  
				LEFT JOIN csa_promotion_categories AS d ON a.Category=d.CategoryID 
				WHERE d.ForConversions='1' {$search_string2}
				GROUP BY a.Category, b.SubProductID 
				ORDER BY b.SubProductID ASC, a.Category ASC 
				  
			 ";  
		$result = $this->db->query($sql);   
		//echo $this->db->last_query(); 
		return $result->result();
		 
	} 
	
	public function batchUpdateCrmConversion_($data, $field, $where_arr=array(), $history_data=array()){    
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		/*if(isset($where_arr["c.CountStatusHistory >"])) 
		  {
			 $history_check = "LEFT JOIN 
								(
									SELECT c.ActivityID, COUNT(c.ActivityID) AS CountStatusHistory 
									FROM csa_activities_history AS c USE INDEX (ActivityStatusIndex)
									WHERE c.Activity='promotion' AND 
										  c.Status IN(".implode(',', $history_data[allowed_status]).")  
									GROUP BY c.ActivityID    
									HAVING CountStatusHistory > 0
								) c ON a.ActivityID=c.ActivityID " ;
		  }
		else
		 {
			 $history_check = "";  
		 }*/
		 
		
		$this->db->update_batch("csa_promotion_activities a 
								 LEFT JOIN csa_promotions b on a.Promotion = b.PromotionID 
								 {$history_check} ", $data, $field);    
		return $this->db->affected_rows();
	}
	  
		
	/*public function getCurrencyAllX_($where_arr) 
	{  
		$this->db->select("a.CurrencyID, a.Abbreviation, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_currency AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Abbreviation', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}  */
	 
	 
	 
	
	//CURRENCIES
	/*public function getCurrencyAll_($where_arr) 
	{  
		$this->db->select("a.CurrencyID, a.Abbreviation, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName", false);
		$this->db->from('csa_currency AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Abbreviation', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}  
 
	*/
	
	
	public function getCrmConversionsDetails_($search_string, $search_string2, $search_string3, $search_string4, $paging ) 
	{  
		$search_string = ($search_string)?" AND {$search_string} ":"";	 
		$search_string2 = ($search_string2)?" AND {$search_string2} ":""; 
		  
		
		/*if($search_string3)
		 {
			$left_join = "LEFT JOIN 
							(
								SELECT b.ActivityID
								FROM csa_calls AS b  
								WHERE b.Activity='promotion' AND 
									  b.CallResultID=1 {$search_string3}   
								GROUP BY b.ActivityID    
							) b ON a.ActivityID=b.ActivityID";   
			$reach_count = " COUNT(b.ActivityID) ";    
			$total_reach_count = "SUM(a.ReachedCall) ";  
			
			$offered_count = "SUM(IF((a.OfferedBy = '0' {$search_string4}), 1, 0)) "; 
			$total_offered_count = "SUM(a.CountOffered)";
			  
			$claimed_personal_users = "SUM(IF((a.ClaimedDeposited='2' AND (IfNull(b.ActivityID, 0) > 0)), 1, 0))";
			$deposited_personal_users = "SUM(IF(((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2') AND (IfNull(b.ActivityID, 0) > 0) ), 1, 0)) ";
		 }
		else
		 {
			$left_join = ""; 
			$reach_count = "SUM(IF((a.CallResultID=1), 1, 0)) "; 
			$total_reach_count = "SUM(a.ReachedCall)";
			
			$offered_count = "SUM(IF((a.OfferedBy > 0), 1, 0)) "; 
			$total_offered_count = "SUM(a.CountOffered)";
			
			$claimed_personal_users = "SUM(IF((a.ClaimedDeposited='2' AND a.CallResultID=1), 1, 0))";
			$deposited_personal_users = "SUM(IF(((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2') AND a.CallResultID=1), 1, 0))";
		 }*/
		
		$left_join = "LEFT JOIN 
						(
							SELECT b.ActivityID
							FROM csa_calls AS b  
							WHERE b.Activity='promotion' AND 
								  b.CallResultID=1 {$search_string3}   
							GROUP BY b.ActivityID    
						) b ON a.ActivityID=b.ActivityID";   
		$reach_count = " COUNT(b.ActivityID) ";    
		$total_reach_count = "SUM(a.ReachedCall) ";  
		
		$offered_count = "SUM(IF((a.OfferedBy != '0' {$search_string4}), 1, 0)) "; 
		$total_offered_count = "SUM(a.CountOffered)";
		  
		$claimed_personal_users = "SUM(IF((a.ClaimedDeposited='2' AND (IfNull(b.ActivityID, 0) > 0)), 1, 0))";
		$deposited_personal_users = "SUM(IF(((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2') AND (IfNull(b.ActivityID, 0) > 0) ), 1, 0)) ";
		 
		$sql = "SELECT a.Promotion, a.Category, a.Currency, SUM(a.CountActivities) AS TotalLeads, {$total_reach_count} AS TotalReached, 
					    {$total_offered_count} AS TotalOffered, SUM(a.ClaimedUsers) AS TotalClaimed, SUM(a.DepositedUsers) AS TotalDeposited,   
					   SUM(a.ClaimedPersonalUsers) AS TotalPersonalClaimed, SUM(a.DepositedPersonalUsers) AS TotalPersonalDeposited,   
					   b.Name AS PromotionName, b.SubProductID, c.Name AS SubProductName, d.Name AS CategoryName, d.ClaimedOnly, 
					   e.Abbreviation   
				FROM 
				 (
					SELECT a.Promotion, a.Category, a.Currency, COUNT(a.ActivityID) AS CountActivities, 
						   {$reach_count} AS ReachedCall, {$offered_count} AS CountOffered, 
						   SUM(IF((a.ClaimedDeposited='2'), 1, 0)) AS ClaimedUsers, 
						   SUM(IF((a.ClaimedDeposited='1' OR a.ClaimedDeposited='2'), 1, 0)) AS DepositedUsers,     
						   {$claimed_personal_users} AS ClaimedPersonalUsers,  
						   {$deposited_personal_users} AS DepositedPersonalUsers    
						   
					FROM csa_promotion_activities AS a USE INDEX (ConversionCategory)    
					$left_join 
					WHERE a.IsUpload='1' {$search_string}
					GROUP BY a.Promotion, a.Category
				) AS a 
				LEFT JOIN csa_promotions AS b ON a.Promotion=b.PromotionID  
				LEFT JOIN csa_sub_products AS c ON b.SubProductID=c.SubID  
				LEFT JOIN csa_promotion_categories AS d ON a.Category=d.CategoryID  
				LEFT JOIN csa_currency AS e ON a.Currency=e.CurrencyID
				WHERE d.ForConversions='1' {$search_string2}  
				GROUP BY a.Promotion, a.Category, b.SubProductID  
				ORDER BY b.SubProductID ASC, a.Category ASC, a.Promotion 
				  
			 ";  
		$result = $this->db->query($sql);   
		//echo $this->db->last_query(); 
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