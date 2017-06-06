<?php
class Dashboard_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();  
		
    }
	
	//COUNT ASSIGNED PER CURRENCY
	public function countGroupAssigned_($activity="", $date_from="", $date_to="", $index="DateUpdatedInt") {
		//$index="GroupAssignee";
		$date_where  = ($date_from && $date_to)?" AND (a.{$index} BETWEEN $date_from AND $date_to) ":"";  
		
		$sql = "SELECT a.GroupID, a.Name, 
					   IfNull(b_count, 0) AS BankCount, 
					   IfNull(c_count, 0) AS PromotionCount, 
					   IfNull(d_count, 0) AS CasinoCount, 
					   IfNull(e_count, 0) AS AccountsCount, 	 	
					   IfNull(f_count, 0) AS SuggestionsCount, 	 
					   IfNull(g_count, 0) AS AccessCount, 
					   (IfNull(b_count, 0) + IfNull(c_count, 0) + IfNull(d_count, 0) + IfNull(e_count, 0) + IfNull(f_count, 0) + IfNull(g_count, 0) ) AS TotalCount 	 	
				FROM `csa_users_group` AS a
				LEFT JOIN
					(
						SELECT GroupAssignee, Count(ActivityID) AS b_count
						FROM csa_bank_activities USE INDEX (Dashboard)
						WHERE (Currency IN({$this->session->userdata(mb_currencies)}))
							  AND (Status NOT IN({$this->common->notcount_status}))
						GROUP BY GroupAssignee
					) b On b.GroupAssignee=a.GroupID   
				LEFT JOIN
					(
						SELECT GroupAssignee, Count(ActivityID) AS c_count
						FROM csa_promotion_activities USE INDEX (Dashboard) 
						WHERE  (Currency IN({$this->session->userdata(mb_currencies)})) 
							  	AND ((IsUpload='1' AND Status<>{$this->common->ids[new_status]}) OR (IsUpload='0') )   
								AND (Status NOT IN({$this->common->notcount_status}))
						GROUP BY GroupAssignee
					) c On c.GroupAssignee=a.GroupID  
				LEFT JOIN
					(
						SELECT GroupAssignee, Count(ActivityID) AS d_count
						FROM csa_casino_issues USE INDEX (Dashboard)
						WHERE  (Currency IN({$this->session->userdata(mb_currencies)}))
								AND (Status NOT IN({$this->common->notcount_status}))
						GROUP BY GroupAssignee
					) d On d.GroupAssignee=a.GroupID  
				LEFT JOIN
					(
						SELECT GroupAssignee, Count(ActivityID) AS e_count
						FROM csa_account_issues USE INDEX (Dashboard)  
						WHERE  (Currency IN({$this->session->userdata(mb_currencies)})) 
								AND (Status NOT IN({$this->common->notcount_status}))
						GROUP BY GroupAssignee
					) e On e.GroupAssignee=a.GroupID     
				LEFT JOIN
					(
						SELECT GroupAssignee, Count(ActivityID) AS f_count
						FROM csa_suggestions_complaints USE INDEX (Dashboard) 
						WHERE  (Currency IN({$this->session->userdata(mb_currencies)})) 
								AND (Status NOT IN({$this->common->notcount_status}))
						GROUP BY GroupAssignee
					) f On f.GroupAssignee=a.GroupID 
				LEFT JOIN
					(
						SELECT GroupAssignee, Count(ActivityID) AS g_count
						FROM csa_website_access USE INDEX (Dashboard) 
						WHERE  (Currency IN({$this->session->userdata(mb_currencies)}))
								AND (Status NOT IN({$this->common->notcount_status}))
						GROUP BY GroupAssignee
					) g On g.GroupAssignee=a.GroupID 
						
				WHERE a.Status='1' AND a.GroupID<>7 
				ORDER BY a.Name ASC 
				  ";    
		$result = $this->db->query($sql);    
		  
		return $result->result();
		/*if($result->num_rows() > 0){
			if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}
		}
		else {
			return array();
		} */
		
	}	
	 
	//COUNT ASSIGNED PER CURRENCY
	public function countAssignedActivity_($activity, $date_from="", $date_to="", $index="DateUpdatedInt") {
		//$date_where  = ($date_from && $date_to)?" AND (a.{$index} BETWEEN $date_from AND $date_to) ":"";  
		
		//super admin can view all
		//$restrict_where = (!admin_only())?" AND (a.GroupAssignee={$this->session->userdata(mb_usertype)}) ":""; 
		// -- USE INDEX (Status) 
		$restrict_where .= ($this->common->ids['super_admin_id'] != $this->session->userdata('mb_usertype'))?" AND (a.GroupAssignee={$this->session->userdata(mb_usertype)}) ":""; 
		
		$restrict_where .= ($activity == "promotion")?" AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0')  ) ":$restrict_where; 
	  	  
		$sql = "SELECT Count(a.ActivityID) AS CountRecord, 
					   IfNull(SUM(a.Status={$this->common->ids[new_status]}),0) AS TotalNew, 
	    			   IfNull(SUM(a.Status NOT IN({$this->common->ids[new_status]},{$this->common->ids[pending_status]},{$this->common->ids[web_pending_status]},{$this->common->ids[pending_ameyo]})),0) AS TotalInProgress, 
					   IfNull(SUM(a.Status IN({$this->common->ids[pending_status]},{$this->common->ids[web_pending_status]},{$this->common->ids[pending_ameyo]})),0) AS TotalPending 
				FROM {$this->common->activity_types[$activity]['table']} AS a USE INDEX (Dashboard)
				WHERE  (a.Currency IN({$this->session->userdata(mb_currencies)}))	 	
					   AND (a.Status NOT IN({$this->common->notcount_status})) 
					   $date_where $restrict_where 
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
	
	public function countProcessActivity_($activity, $date_from="", $date_to="", $index="DateUpdatedInt") {
		//$date_where  = ($date_from && $date_to)?" AND (a.{$index} BETWEEN $date_from AND $date_to) ":"";  
		//-- USE INDEX (Status)
		$restrict_where = ""; 
		$restrict_where .= ($activity == "promotion")?" AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ":"";  
		 
		$sql = "SELECT Count(a.ActivityID) AS CountRecord 
				FROM {$this->common->activity_types[$activity]['table']} AS a USE INDEX (Dashboard) 
				WHERE (Status NOT IN({$this->common->notcount_status}))
					   $date_where $restrict_where   
				  "; 
		//ORDER BY a.ActivityID ASC       
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
	 
	public function getActivityStatistic_($type, $date_from="", $date_to="", $index="DateAddedInt"){ 
	  	 
		$date_range = ($date_from=="" || $date_to=="")?date:""; 
		$restrict_where = ""; 
		$restrict_where .= ($type == "promotion")?" AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ":"";  
		 
		$sql = "SELECT Count(a.ActivityID) AS CountRecord, 
					   UNIX_TIMESTAMP(FROM_UNIXTIME(a.DateAddedInt,'%Y-%m-%d')) AS DateGroup, 
					   SUM(a.IsComplaint='1') AS TotalComplaint, SUM(a.Important='1') AS TotalImportant, 
					   (SUM(a.Status={$this->common->ids[close_status]}) + SUM(a.Status={$this->common->ids[crm_note_status]}) + SUM(a.Status={$this->common->ids[deposited_status]}) + SUM(a.Status={$this->common->ids[cashback_application_status]}) + SUM(a.Status={$this->common->ids[nondeposited_status]})) AS TotalCloseTicket 
				FROM {$this->common->activity_types[$type]['table']} AS a USE INDEX (DateAddedIntKey)  
				WHERE (a.{$index} BETWEEN $date_from AND $date_to) $restrict_where   
				GROUP BY DateGroup 
				  "; 
		//ORDER BY DateGroup ASC   
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