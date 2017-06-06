<?php
class Common_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "language";  
		$this->restrict_viewcount = array(1,2); //not counted in views 
		$this->system_startdate = "2014-09-01";  
		$this->activity_types = array("deposit_withdrawal"=>array("color"=>"#063257", "label"=>"Bank", "table"=>"csa_bank_activities", "icon"=>"i-office"), 
									  "promotion"=>array("color"=>"#f96c1a", "label"=>"Promotion", "table"=>"csa_promotion_activities", "icon"=>"i-star-2"), 
									  "casino_issues"=>array("color"=>"#cb1112", "label"=>"Casino", "table"=>"csa_casino_issues", "icon"=>"i-dice"), 
									  "account_issues"=>array("color"=>"#77b94b", "label"=>"Account", "table"=>"csa_account_issues", "icon"=>"i-vcard"),
									  "suggestions_complaints"=>array("color"=>"#f59d00", "label"=>"Suggestions", "table"=>"csa_suggestions_complaints", "icon"=>"i-pencil-5"), 
									  "website_mobile"=>array("color"=>"#3a70da", "label"=>"Access", "table"=>"csa_website_access", "icon"=>"i-key-2")
								); 
								   
		$this->close_status = 5; 
		$this->inprogress_status = 5; 
		$this->display_close_ticket = 1;  
		
		$this->load->library('pagination');	 
		$this->ids = array("super_admin_id"=>7, 
						   "management_id"=>5, 
						   "crm_id"=>10, 
						   "csa_id"=>1, 
						   "new_status"=>0,
						   "close_status"=>5, 
						   "inprogress_status"=>61, 
						   "pending_status"=>62, 
						   "web_pending_status"=>65, 
						   "crm_note_status"=>43, 
						   "deposited_status"=>11, 
						   "nondeposited_status"=>9, 
						   "cancel_status"=>63,  
						   "rm_approve_sts_credit_status"=>57,
						   "approve_status"=>18, 
						   "cashback_application_status"=>24, 
						   "ameyo_call_request_status"=>1,
						   "pending_ameyo"=>71,
						   "cashback_application_category"=>5, 
						   "reached_result"=>1
						  ); 
		$this->notcount_status = '5,43,11,24,63,9,70';  
		$this->hide_status = '5,43,11,9';   			
		$this->date_index = "DateUpdatedInt";  
		
		$this->group_assigned_index = "SearchAllUpdatedKey";
		$this->group_activities_index = "ActivitiesUpdatedKey";
		
		$this->temp_file = file_exists("/tmp/ramdisk/")?"/tmp/ramdisk/":"media/temp/";
		//$this->temp_file = file_exists("F:/media/tmp/")?"F:/media/tmp/":"media/temp/";
		
		$this->start_date = "2013-09-01 00:00:00"; 
		
		$this->callers_usertype = "1,10"; 
		$this->days_notcount_status = array(5); //5 - Close Ticket
		$this->days_warning =  7; //days count to warning  
		$this->add_months =  3; //months added in calendar for promotion 
		 
		$this->chat_default = array("allow_default"=>true, 
									"user_types"=>array(5, 15, 14), //5-maggie, 15-vip team, -14-jeffrey  
									"users"=>array()
									);  
		$host = getenv("HTTP_HOST");
		$internal_api_link = (strpos($host,"12csd.com") > 0?"http://internalapi.12.com/api/CreditBonus":"http://internaltestapi.12.com/api/CreditBonus");
		// $this->internal_system_api = array("can_submit"=>true, 
										   // //"url"=>"http://testinternal-api.zzs33.com/api/CreditBonus" //local test
										   // //"url"=>"http://internaltestapi.12.com/api/CreditBonus", 
										   // "url"=>"http://internalapi.12.com/api/CreditBonus"
										   // ); 
		$this->internal_system_api = array("can_submit"=>true, 
										   "url"=>$internal_api_link
										   ); 
		$this->intranet_api = array("login_url"=>"http://122.53.154.212/intranet/api/checkCrossSession/cal-system"); 
    }
 	
	
	//escape string to be called in controller
	public function escapeString_($string, $is_like=0) { 
	  
		if($is_like == 0)
		 {
			$return = $this->db->escape_like_str($string); 
		 }
		else
		 {
			 $return = $this->db->escape_str($string); 
			 
			 $return = ltrim($return, "'");
			 $return = rtrim($return, "'");
		 }
		 
        return $return; 
    }
	
	
	//get call results
	public function getResultList_($where_arr = array()) {
        $this->db->select("a.*, (CASE WHEN a.result_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('call_result a');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.result_id", "ASC");
        $result = $this->db->get();
        return $result->result();
    }
	
	//get id types
	public function getIdTypes_($where_arr) 
	{  
		$this->db->select('a.TypeID, a.Type');
		$this->db->from('csa_id_type AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Type', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
	
	//get id types
	public function getCountries_($where_arr) 
	{  
		$this->db->select('a.CountryID, a.CountryCode, a.CountryName');
		$this->db->from('csa_countries AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.CountryName', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
	
	//batch insert
	public function batchInsert_($table,$data){
		$this->db->insert_batch($table, $data); 
		return $this->db->affected_rows();
	}
	
	//batch updadate
	public function batchUpdate_($table, $data, $field, $where_arr=array()){ 
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->update_batch($table, $data, $field);      
		return $this->db->affected_rows();
	}
	
	//set batch updadate
	public function setBatchUpdate_($table, $data, $field, $where_arr=array()){ 
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->set_update_batch($table, $data, $field);  
		return $this->db->affected_rows();
	}
	
	//DELETE RECORD(S)
	public function deleteRecords_($table, $selected_values , $index, $where_arr=array()){   
		if(count($where_arr) > 0)$this->db->where($where_arr);  
		$this->db->where("FIND_IN_SET({$index}, '".$selected_values."') "); 
		
		$x = $this->db->delete($table);
		return $x; 
		
	}
	
	//GET PRODUCTS
	public function getProductsList($where_arr) 
	{  
		$this->db->select('ProductID, Name AS ProductName');
		$this->db->from('csa_products');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('Name', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
	
	public function getProductsList_($where_arr) 
	{  
		$this->db->select('a.ProductID, a.Name AS ProductName');
		$this->db->from('csa_products AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Name', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
	
	public function getSubProductsList_($where_arr) 
	{  
		$this->db->select("a.SubID, a.Name, a.MainProductID, b.Name AS MainProductName", false);
		$this->db->from("csa_sub_products AS a");   
		$this->db->join('csa_products AS b', 'a.MainProductID=b.ProductID', 'left');   
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Name', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
	
	//GET PRODUCTS
	public function getUsersGroup_($where_arr) 
	{  	
		$this->db->select('GroupID, Name, Value, Level');
		$this->db->from('csa_users_group');   
		$this->db->where("GroupID <>", $this->ids["super_admin_id"]);  
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('Name', 'ASC'); 
		$result = $this->db->get();  
		return $result->result();  
	}
	
	//COUNT ACTIVITIES, USED IN PAGINATION
 	public function countSearchActivities_($where_str, $table="", $activity="", $index="DateAddedInt")
	{
		 $where_str = trim(trim($where_str), "AND");
		  	
		 $left_join = ($activity=="promotion")?"LEFT JOIN csa_promotions AS h ON a.Promotion=h.PromotionID":""; 
		
		 $where_test = ($table == "csa_bank_activities")?"":" a.ActivityID<>0 ";
		 $sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
					FROM {$table} AS a USE INDEX ({$index})  
					LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no  


					$left_join
					WHERE $where_str ";  
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
	
	
	//COUNT CALL ACTIVITIES, USED IN PAGINATION
 	public function countSearchCallActivities_($where_str, $table="")
	{
		 $sql = "SELECT COUNT(x.ActivityID) AS CountActivity, a.ActivityID 
				FROM {$table} AS x USE INDEX (DateAddedInt)
					LEFT JOIN csa_promotion_activities AS a ON x.ActivityID=a.ActivityID 
					LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
					LEFT JOIN csa_promotion_categories AS c ON a.Category=c.CategoryID 
					LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
					LEFT JOIN csa_status AS e ON a.Status=e.StatusID 
					LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no  
					LEFT JOIN g4_member AS g ON a.AddedBy=g.mb_no    
					LEFT JOIN csa_promotions AS h ON a.Promotion=h.PromotionID  
				
				WHERE x.ActivityID<>0 $where_str ";  
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
	
	
	public function getUserStatusViews($page_id=1)
	{
		if(restriction_type())
		 { 
			$usertype_status = " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', b.Viewers) || FIND_IN_SET('".$this->session->userdata('mb_usertype')."', b.Users) )";
		 }
		  
		$sql = "SELECT GROUP_CONCAT( b.StatusID SEPARATOR ',' ) AS StatusList 
		 FROM csa_pages AS a 
		 LEFT JOIN csa_status AS b ON FIND_IN_SeT(b.StatusID, a.StatusList) AND b.Status='1' 
		 WHERE a.Status='1' AND a.PageID=$page_id  $usertype_status  
		 ORDER BY b.Name ASC   
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
	
	
	public function getCurrency_()
	{ 
		$sql = "SELECT CurrencyID, Abbreviation 
				FROM csa_currency  	
				WHERE CurrencyID<>0 AND FIND_IN_SET(CurrencyID,'{$this->session->userdata(mb_currencies)}') AND Status='1'
				ORDER BY Abbreviation ASC";   
												  
		$result = $this->db->query($sql);   
		 
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
		return $result->result();
	}
	
	public function getSource_()
	{ 
		$sql = "SELECT SourceID, Source 
				FROM csa_source 	
				WHERE Status='1' 
				ORDER BY Source ASC";    		  
		$result = $this->db->query($sql);   
		 
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
		return $result->result();
		
	}
	
	public function getStatusList_($page_id='', $action="active", $default_stat)
	{
		if(restriction_type())
		 { 
		 	$usertype_status = " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', b.Viewers) || FIND_IN_SET('".$this->session->userdata('mb_usertype')."', b.Users) )";  		
		 }
		
		$page_where = ($page_id)?" AND a.PageID={$page_id} ":""; 
		
		$stat_where = ($action == "active")?" AND b.Status='1' ":" AND b.Status<>'9' "; 
		 
		$sql = "SELECT b.StatusID, b.Name, b.Viewers, b.Users, b.Status  
				FROM csa_pages AS a 
				LEFT JOIN csa_status AS b ON FIND_IN_SeT(b.StatusID, a.StatusList) {$stat_where}
				WHERE a.Status='1' $page_where $usertype_status  
				GROUP BY b.StatusID 
				ORDER BY b.Name ASC     
		   ";     
		$result = $this->db->query($sql);   
		 
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
		
		return $result->result();
	}  
	
	
	public function getStatusById($where_arr) 
	{  
		$this->db->select('a.StatusID, a.Name AS StatusName, a.Status');
		$this->db->from('csa_status AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Name', 'ASC'); 
		$result = $this->db->get();
		
		if ($result->num_rows() > 0) {
            if ($result->num_rows() == 1) {
                return $result->row();
            } else {
                return $result->result();
            }
        } else {
            return array();
        }
	}
	
	public function getGroupAssigneeById_($where_arr) 
	{  
		$this->db->select('a.GroupID, a.Name AS GroupAssigneeName, a.Status');
		$this->db->from('csa_users_group AS a');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Name', 'ASC'); 
		$result = $this->db->get(); 
		 
		if ($result->num_rows() > 0) {
            if ($result->num_rows() == 1) {
                return $result->row();
            } else {
                return $result->result();
            }
        } else {
            return array();
        }
		
	}
	
	
	//DISPLAY UPLOADED
	public function displayUploaded_($where_arr) 
	{  
		$this->db->select('AttachID, ActivityID, Path, FullPath, ClientFilename');
		$this->db->from('csa_attach_file');  
		$this->db->where($where_arr); 
 		$result = $this->db->get();
		
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
		}*/   
		
	}
	
	//DELETE ATTACHMENT
	public function deleteAttachment_($where_arr) 
	{  
		if(count($where_arr) <= 0) return false; 
		
		$this->db->where($where_arr);
		$this->db->delete('csa_attach_file'); 
		return $this->db->affected_rows();
	}
	
	
	public function getCallAgentList_($where_arr) 
	{    
		$this->db->select('a.mb_no, a.mb_id, a.mb_nick, a.mb_usertype');
		$this->db->from('g4_member AS a');  
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
 		
		$this->db->where("FIND_IN_SET(a.mb_usertype, '{$this->callers_usertype}' )");
		$this->db->order_by("a.mb_nick", "ASC");
		
		$result = $this->db->get(); 
		return $result->result();  
	}
	
	//SEARCH ACTIVITIES 
	public function countActivitiesSearch_($where_str, $table="")
	{ 
		 $sql = "SELECT COUNT(DISTINCT a.ActivityID) AS CountActivity 
					FROM csa_activities_history AS a USE INDEX (DateUpdatedInt)     
					LEFT JOIN {$table} AS b ON a.ActivityID=b.ActivityID  
					LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no 
					WHERE a.ActivityID<>0 $where_str  
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
	//END SEARCH ACTIVITIES
	
	public function getAnalysisCategories_($where_arr) 
	{  
		$this->db->select('CategoryID, Name AS CategoryName');
		$this->db->from('csa_analysis_category');   
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('CategoryID', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
	
	//get user types  
	public function getUserTypes_($where_arr=array()){ 
		$res_type = array($this->common->ids['super_admin_id']); 
		
		$this->db->select('GroupID, Value, Name AS UserTypeName, Status, CanOverride');
		$this->db->from('csa_users_group');   
		if(count($where_arr) > 0)$this->db->where($where_arr);    
		//if(!admin_only())$this->db->where("GroupID NOT IN ('".implode(',', $res_type)."') ");  
		
		if(!super_admin())$this->db->where("GroupID <>", $this->ids["super_admin_id"]);  
		 
		$this->db->order_by('Name', 'ASC'); 
		$result = $this->db->get(); 
		return $result->result();
		
	}
	
	
	//get assignees
	public function getAssignees_($group_id, $default=''){ 
		
		$where_str = ($default)?" OR (GroupID={$default} ) ":"";
		
		/*$sql = "SELECT GroupID, Name AS UserTypeName, Status
				FROM csa_users_group AS a 
				WHERE Status='1' AND (FIND_IN_SET(GroupID, (SELECT CanAssign FROM csa_users_group WHERE GroupID={$group_id}) ) $where_str ) 
				GROUP BY GroupID
					";*/
		$sql = "SELECT GroupID, Name AS UserTypeName, Status
				FROM csa_users_group AS a 
				WHERE (Status='1' AND FIND_IN_SET(GroupID, (SELECT CanAssign FROM csa_users_group WHERE GroupID={$group_id}) ) ) $where_str  
				GROUP BY GroupID
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
	
	
	//SEARCH  
	public function countSearchAll_($search_string, $search_string2, $t_search, $s_dashboard, $index="SearchAllUpdatedKey", $activity="")
	{ 	
		$for_promotion = ($s_dashboard == 1)?" AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ":""; 
		
		$union_banks = "(SELECT 'banks' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								a.Currency, CONCAT(a.Category,' - ',b.Name) AS Concern 
						FROM csa_bank_activities AS a USE INDEX ({$index})
							LEFT JOIN csa_bank_category AS b ON a.CategoryID=b.CategoryID 
						WHERE $search_string   
						) ";
		
		$union_promotions = "(SELECT 'promotions' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status,  
								a.Currency, b.Name AS Concern 
							 FROM csa_promotion_activities AS a USE INDEX ({$index}) 
								LEFT JOIN csa_promotions AS b ON a.Promotion=b.PromotionID   
							 WHERE $search_string  $for_promotion   
							)";
		
		$union_casino = "(SELECT 'casino' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								a.Currency, b.Name AS Concern 
						FROM csa_casino_issues AS a USE INDEX ({$index}) 
							LEFT JOIN csa_issues_category AS b ON a.IssueCategory=b.CategoryID 
						WHERE $search_string   
						)";
		
		$union_accounts = "(SELECT 'accounts' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								 a.Currency, b.ProblemName AS Concern
							 FROM csa_account_issues AS a USE INDEX ({$index})
							 LEFT JOIN csa_account_problems AS b ON a.AccountProblem=b.ProblemID 
							 WHERE $search_string    	 
							)"; 
					
		$union_suggestions = "(SELECT 'suggestions' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								 a.Currency, b.Name AS Concern
							 FROM csa_suggestions_complaints AS a USE INDEX ({$index})
							 LEFT JOIN csa_complaints_types AS b ON a.ComplaintType=b.ComplaintID 
							 WHERE $search_string   		 
							)"; 
		
		$union_access = "(SELECT 'access' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status,  
								 a.Currency, b.Name AS Concern 
						 FROM csa_website_access AS a USE INDEX ({$index}) 
						 LEFT JOIN csa_access_problems AS b ON a.Problem=b.ProblemID 
						 WHERE $search_string    	 
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
		  
		$sql = "SELECT COUNT(results.ActivityID) AS CountActivity 
				FROM (     
					{$union_query}		  
				) AS results 
				LEFT JOIN csa_status AS status ON results.Status=status.StatusID 
				LEFT JOIN csa_currency AS currencies ON results.Currency=currencies.CurrencyID 
				    
				 ";
		$sql = ($search_string2 != "")?"{$sql} WHERE {$search_string2} ":$sql; 
				 
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
	  
	public function getSearchAll_($search_string, $search_string2, $t_search, $paging=array(), $s_dashboard, $index="SearchAllUpdatedKey", $activity="", $order_by="DateAddedInt")
	{	 
		$limit_str = (count($paging) > 0)?" LIMIT {$paging['page']}, {$paging['limit']} ":"";  
		$for_promotion = ($s_dashboard == 1)?" AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ":"";
	 	
		$union_banks = "(SELECT 'banks' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status,  
								a.Currency, CONCAT(a.Category,' - ',b.Name) AS Concern, a.GroupAssignee, a.TransactionID, a.Amount
						FROM csa_bank_activities AS a USE INDEX ({$index}) 
							LEFT JOIN csa_bank_category AS b ON a.CategoryID=b.CategoryID 
						WHERE $search_string     
						)";
		
		$union_promotions = "(SELECT 'promotions' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status,  
								a.Currency, b.Name AS Concern, a.GroupAssignee, '' AS TransactionID, '' AS Amount  
								FROM csa_promotion_activities AS a USE INDEX ({$index}) 
									LEFT JOIN csa_promotions AS b ON a.Promotion=b.PromotionID   
								WHERE $search_string  $for_promotion  
								)";
		
		$union_casino = "(SELECT 'casino' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								a.Currency, b.Name AS Concern, a.GroupAssignee, '' AS TransactionID, '' AS Amount  
						FROM csa_casino_issues AS a USE INDEX ({$index})  
							LEFT JOIN csa_issues_category AS b ON a.IssueCategory=b.CategoryID 
						WHERE $search_string   
						)";
		
		$union_accounts = "(SELECT 'accounts' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status,  
								 a.Currency, b.ProblemName AS Concern, a.GroupAssignee, '' AS TransactionID, '' AS Amount 
						 FROM csa_account_issues AS a USE INDEX ({$index}) 
						 LEFT JOIN csa_account_problems AS b ON a.AccountProblem=b.ProblemID 
						 WHERE $search_string   
						)"; 
					
		$union_suggestions = "(SELECT 'suggestions' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								 a.Currency, b.Name AS Concern, a.GroupAssignee, '' AS TransactionID, '' AS Amount 
						 FROM csa_suggestions_complaints AS a USE INDEX ({$index}) 
						 LEFT JOIN csa_complaints_types AS b ON a.ComplaintType=b.ComplaintID 
						 WHERE $search_string 	 
						)"; 
		
		$union_access = "(SELECT 'access' AS Activity, a.ActivityID, a.Username, a.DateAddedInt, a.DateUpdatedInt, a.ESupportID, a.Status, 
								 a.Currency, b.Name AS Concern, a.GroupAssignee, '' AS TransactionID, '' AS Amount  
						 FROM csa_website_access AS a USE INDEX ({$index}) 
						 LEFT JOIN csa_access_problems AS b ON a.Problem=b.ProblemID 
						 WHERE $search_string   		 
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
		
		 
		$sql = "SELECT results.*, (CASE WHEN results.Status = '0' THEN 'New' ELSE status.Name END) AS StatusName,   
					   currencies.Abbreviation, type.Name AS GroupAssigneeName    
				FROM 
				 (
					SELECT results.*, @cnt := (@cnt + 1) as 'cnt'  
					FROM (
						{$union_query}	 	 
					) AS results  
				 	{$where_sql}
					
				 ) AS results   
				LEFT JOIN csa_status AS status ON results.Status=status.StatusID 
				LEFT JOIN csa_currency AS currencies ON results.Currency=currencies.CurrencyID 
				LEFT JOIN csa_users_group AS type ON results.GroupAssignee=type.GroupID  
				   
				ORDER BY results.{$order_by} DESC 
				$limit_str
				";
		 
		$this->db->query("SET @cnt := 0");   
		$result = $this->db->query($sql);   
		//return $result->result();   
		$query = $this->db->query('SELECT @cnt AS CountActivity'); 
		return array("total_rows"=>$query->row()->CountActivity, 
					 "result"=>$result->result()
					);
	} 
	
	
	public function getUserAll_($where_arr=array(), $order_by=array()) 
	{  			   
		$this->db->select("a.mb_no, a.mb_nick, a.mb_usertype, (CASE WHEN a.mb_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, b.Name AS UserTypeName", false);
		$this->db->from('g4_member a');     
		$this->db->join('csa_users_group AS b', 'a.mb_usertype=b.GroupID', 'left'); 
		 
		if(count($where_arr) > 0)$this->db->where($where_arr);    
		
		$this->db->where("a.mb_name =", 'csa');   
		if(count($order_by) > 0)
		 {
			foreach($order_by as $row=>$order) {
				$this->db->order_by($row, $order);  	  
			}//end foreach
			
		 }
		else
		 {
			$this->db->order_by("a.mb_status", "ASC");  	 
		 }
		
		
 		$result = $this->db->get();      
	 
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
		}*/ 
		
	}
	
	  
	public function getUserByIdX_($where_arr=array()) 
	{  			   
		$this->db->select("a.*, (CASE WHEN a.mb_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName, b.Name AS UserTypeName", false);
		$this->db->from('g4_member a');     
		$this->db->join('csa_users_group AS b', 'a.mb_usertype=b.GroupID', 'left'); 
		 
		if(count($where_arr) > 0)$this->db->where($where_arr);    
		
		$this->db->where("a.mb_name =", 'csa');  
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
	
	
   //GET SHIFTS
   public function getShifts_($where_arr) 
	{  	
		$this->db->select('a.ShiftID, a.ShiftName, a.TimeStart, a.TimeEnd');
		$this->db->from('csa_shifts AS a');      
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.ShiftID', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}	
	
   //GET SOURCE ALL
   public function getSourceAll_($where_arr) 
	{  	
		$this->db->select('SourceID, Source');
		$this->db->from('csa_source');     
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('Source', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}	 
    
	
	
	
	
	
	 
	public function accessLanguages_($lang=""){ 
		 $sql = "SELECT LanguageID, Name, FolderName, SpecialCharacters FROM language  
				WHERE Status='1' AND FIND_IN_SET(LanguageID, '".$this->session->userdata('mb_access')."')
				ORDER BY LanguageID ASC "; 
		 
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
	
	//accounts status	
	public function accountStatusList_(){ 
		 $sql = "SELECT StatusID, Name FROM account_status  
				WHERE Status='1'  
				ORDER BY Name ASC "; 
		 
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
	
	
	//topic list	
	public function topicList_(){ 
		$sql = "SELECT a.TopicID, a.Name, a.Icon, COUNT(DISTINCT b.PageID) AS CountPage 
				FROM topics AS a 
				LEFT JOIN pages AS b ON a.TopicID=b.TopicID 
				WHERE a.Status='1'   
				GROUP BY a.TopicID 
				ORDER BY a.Name ASC 
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
		

 	//count page 
	public function countInfo_($page_id, $type="tutorial"){
		/*$sql = "SELECT SUM(ViewCount) AS TotalCount FROM page_view  
				WHERE PageID=".$page_id." 
				GROUP BY PageID "; */
		$sql = "SELECT a.PageID, SUM(DISTINCT c.ViewCount) AS TotalCount, COUNT(DISTINCT b.FeedbackID) AS CountFeedback, 
					   COUNT(DISTINCT d.PageID) AS CountRelatedPage  
				FROM pages AS a  
				LEFT JOIN feedback AS b ON b.PageID=a.PageID AND b.Status='1' 
				LEFT JOIN page_view AS c ON c.PageID=a.PageID  
				LEFT JOIN pages AS d ON d.TopicID=a.TopicID AND d.PageID<>".$page_id."
				WHERE a.PageID=".$page_id." 
				GROUP BY c.PageID "; 
		 
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
	
	//overpay status	
	public function overpayStatusList_(){ 
		
		$sql = "SELECT StatusID, Name FROM overpay_status  
				WHERE Status='1'  
				ORDER BY Name ASC "; 
		 
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
	
	 
	//get active currency  
	public function getActiveEmployee_(){ 
		//admin not included in the list 
		/*$sql = "SELECT mb_no, mb_nick, CONCAT(mb_fname, ' ', mb_lname) AS Name 
				FROM g4_member   
				WHERE mb_status='1' AND mb_usertype<>1 AND mb_usertype<>2
				ORDER BY mb_fname ASC, mb_lname ASC "; */
		$sql = "SELECT mb_no, mb_nick, CONCAT(mb_fname, ' ', mb_lname) AS Name 
				FROM g4_member   
				WHERE mb_status='1' AND mb_usertype<>1 
				ORDER BY mb_fname ASC, mb_lname ASC "; 
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
	 
	
	//get employee by type
	public function getActiveEmployeeByType_($types=array()){   
		$types_str = implode(',', $types);
		$sql = "SELECT mb_no, mb_nick, CONCAT(mb_fname, ' ', mb_lname) AS Name 
				FROM g4_member   
				WHERE mb_status='1'  AND FIND_IN_SET(mb_usertype,'".$types_str."')
				ORDER BY mb_fname ASC, mb_lname ASC "; 
		$result = $this->db->query($sql); 
		return $result->result(); 
	}
	
	
	//get active currency  
	public function getOffenses_(){ 
		$sql = "SELECT OffenseID, Name 
				FROM offenses  
				WHERE Status='1' 
				ORDER BY SortOrder DESC, Name ASC ";
		 
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
	
	//get active currency  
	public function getAdjustmentProcess_(){ 
		$sql = "SELECT ProcessID, ProcessName
				FROM adjustment_process  
				WHERE Status='1' 
				ORDER BY ProcessName ASC ";
		 
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
	
	//get active currency  
	public function getAgentModes_(){ 
		$sql = "SELECT ModeID, ModeName 
				FROM agent_modes  
				WHERE Status='1' 
				ORDER BY ModeName ASC ";
		 
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
	
	//get meeting types  
	public function meetingTypes_(){ 
		$usertype_sql = (!admin_access())?" AND TypeID<>'2' ":" "; 
		
		$sql = "SELECT TypeID, TypeName  
				FROM meeting_types  
				WHERE Status='1' $usertype_sql 
				ORDER BY TypeName ASC "; 
		 
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
		}*/ 
	 
	}
	
	
	//bonus status	
	public function bonusStatusList_(){ 
		 $sql = "SELECT StatusID, StatusName 
		 		 FROM bonus_status  
				 WHERE Status='1'  
				 ORDER BY StatusID ASC, StatusName ASC "; 
		 
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
	 
	//shift size	
	public function shiftList_(){ 
		 $sql = "SELECT ShiftID, ShiftName 
		 		 FROM shift  
				 WHERE Status='1'  
				 ORDER BY ShiftID ASC, ShiftName ASC "; 
		 
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
	
	
	//get banks
	public function getReconBanks_(){ 
		$usertype_sql = ""; 
		
		$sql = "SELECT BankID, BankName 
				FROM banks   
				WHERE Status='1' $usertype_sql 
				ORDER BY BankName ASC "; 
		 
		$result = $this->db->query($sql);  
		return $result->result(); 
	}
	
	//get active banks
	public function getBanks_(){ 
		$sql = "SELECT BankID, BankName  
				FROM banks_2  
				WHERE Status='1'  
				ORDER BY BankName ASC "; 
		 
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
	
	//get active banks
	public function getPaymentModes_(){ 
		$sql = "SELECT ModeID, ModeName  
				FROM payment_modes  
				WHERE Status='1'  
				ORDER BY ModeName ASC "; 
		 
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
	
	//unread notifications	
	public function getUnreadNotification_(){ 
		 $sql = "SELECT a.PageID, a.Title, COUNT(DISTINCT b.ViewID) AS CountPage, a.DateUpdated, c.mb_nick AS UpdatedByName, 
		 				(SELECT MAX(f.DateLastViewed) FROM page_view AS f WHERE f.PageID = a.PageID AND f.MemberNo = ".$this->session->userdata('mb_no')." ) AS DateLastViewed,
						 CASE  
							WHEN a.DateUpdated > (SELECT MAX(f.DateLastViewed) FROM page_view AS f 
												 WHERE f.PageID = a.PageID AND f.MemberNo = ".$this->session->userdata('mb_no')." ) 
							THEN 'updated' 
							ELSE 'created'
						 END NoticeAction
						 
				 FROM pages AS a 
				 LEFT JOIN page_view AS b ON ((a.PageID=b.PageID)  AND (b.MemberNo<>".$this->session->userdata('mb_no')."))
				 							  OR ((a.PageID=b.PageID)) 
				 LEFT JOIN g4_member AS c ON a.UpdatedBy=c.mb_no 
				 WHERE a.Status='1' AND a.Type='notice' AND (a.DateUpdated>=DATE_ADD(c.mb_datetime,INTERVAL 3 DAY) )
				 GROUP BY a.PageID  
				 HAVING (CountPage=0) OR (a.DateUpdated > DateLastViewed) OR (DateLastViewed IS NULL)
				 ORDER BY b.DateLastViewed DESC, a.DateUpdated DESC  ";
	  
		$result = $this->db->query($sql);  
		$return = array(); 
		
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
	
	
	public function getUnreadNotification_XX(){ 
		 $sql = "SELECT a.PageID, a.Title, COUNT(DISTINCT b.ViewID) AS CountPage, a.DateUpdated, b.DateLastViewed   
		 		 FROM pages AS a 
				 LEFT JOIN page_view AS b ON ((a.PageID=b.PageID)  AND (b.MemberNo<>".$this->session->userdata('mb_no')."))
				 WHERE a.Status='1' AND a.Type='notice'  
				 GROUP BY a.PageID 
				 HAVING (CountPage=0) OR (a.DateUpdated>b.DateLastViewed)
				 ORDER BY b.DateLastViewed DESC, a.DateUpdated DESC  "; 
		 
		 return $sql.'--------';
		 
	} 
	
	
	//notice list
	public function getNotice_($limit=4){  
		$sql = "SELECT  a.PageID, a.Title, a.DateUpdated, COUNT(DISTINCT b.MemberNo) AS CountViews 
				FROM pages AS a 
				LEFT JOIN page_view AS b ON (a.PageID=b.PageID)     
				INNER JOIN g4_member AS c ON b.MemberNo=c.mb_no AND  c.mb_usertype NOT IN(".implode(',', $this->restrict_viewcount).")
				WHERE a.Type='notice' AND Status='1' 
				GROUP BY a.PageID 
				LIMIT 0, $limit
				";//
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
	
	
	
	
	 
	/** Export Function  **/
	public function exportData_($resource, $header_list=array(), $data_list=array(), $file_name="export_data.xls", $checking=0){   
 
		//load our new PHPExcel library
		$this->load->library('excel'); 
		
		//activate worksheet number 1
		$activeSheet = $this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$activeSheet->setTitle($file_name);
		  
		$headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => '8DB4E2'),
										'font'=> array('bold'=>true)));
		$categoryStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'E4E4E4'),
										'font'=> array('bold'=>true)));
		$reportStyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'f4ec12'),
										'font'=> array('bold'=>true)));
		 
		
		$y = 'A';
		$start = 1; 
		foreach($header_list as $row=>$val){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,$val);
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle); 
			$y++; 
		}//end foreach
		
		$ctr = $start + 1;
		$category_code = "";  
		$count_result = 0;   
		foreach($resource as $row){ 	 
			$row->mb_datastarted = date("F d, Y H:i:s", strtotime($row->mb_datastarted));  
			$row->DateAdded = date("F d, Y H:i:s", strtotime($row->DateAdded));  
			$row->DateUpdated = date("F d, Y H:i:s", strtotime($row->DateUpdated));  
			$row->TransactionDate = date("F d, Y H:i:s", strtotime($row->TransactionDate));  
			
			$row->Amount = number_format($row->Amount, 2);
			
			//$row->AmountOverpaid = number_format($row->AmountOverpaid, 2); 
			//$row->RecoveredAmount = number_format($row->RecoveredAmount, 2); 
			//$row->AmountInMyr = ($row->AmountInMyr!="" && $row->AmountInMyr!=0 || $row->AmountInMyr!= "0.00")?number_format($row->AmountInMyr, 2):""; 
			
			$row->AccountClosed = ($row->AccountClosed==1)?"YES":"NO";
			
			//if(!empty($row->DateUpdated)){  
				$x = 'A';
				for($i=0; $i<count($data_list);$i++)
				 {	 
					$activeSheet->setCellValue($x.$ctr,$row->{$data_list[$i]},PHPExcel_Cell_DataType::TYPE_STRING);  
					$x++;  
				 }  
				$ctr++;
			//}  
			$count_result++; 
			
		}//end foreach
		 
		//count reports	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Record(s)");
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->setCellValue('B'.($ctr+2), $count_result);
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle); 
		
		$x='A';
		for ($col = 0; $col<count($data_list); $col++) {
			$activeSheet->getColumnDimension($x)->setAutoSize(true); 
			$x++; 
		} 
		
		for ($col = 2; $col<count($data_list); $col++) { 
			$activeSheet->getRowDimension($col)->setRowHeight(-1); 
			$activeSheet->getStyle('E'.$col)->getAlignment()->setWrapText(true);
		} 
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$file_name.'"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
		$objWriter->save('php://output');
	} 
	
	public function getHistoryRemarks($where_arr) 
	{ 					   
		//$this->db->select('a.*, b.mb_nick AS UpdatedUser, c.Name AS StatusName, d.ESupportID'); 
		//(CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName	
		$this->db->select("a.*, b.mb_nick AS UpdatedUser, 
						   c.Name AS StatusName, 
						   (CASE WHEN a.Status = '0' THEN 'New' ELSE c.Name END) AS StatusName,
						   d.Name AS GroupAssigneeName");
		$this->db->from('csa_activities_history AS a');  
		$this->db->join('g4_member AS b', 'a.UpdatedBy=b.mb_no', 'left');
		$this->db->join('csa_status AS c', 'a.Status=c.StatusID', 'left');
		$this->db->join('csa_users_group AS d', 'a.GroupAssignee=d.GroupID', 'left'); 
		//$this->db->join('csa_bank_activities AS d', 'a.ActivityID=d.ActivityID', 'left'); 
		$this->db->where($where_arr);  
		$this->db->order_by("a.DateUpdated", "DESC");
 		$result = $this->db->get();
		
		return $result->result(); 
	}  
	
	public function countRecords_($where_str, $table, $index)
	{
		$sql = "SELECT COUNT(DISTINCT {$index}) AS TotalCount 
					FROM {$table}   
					WHERE {$index}<>0 $where_str ";  
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
	 
	
	public function getPromotionCategoriesAll_($where_arr) 
	{  	
		$this->db->select('a.*');
		$this->db->from('csa_promotion_categories AS a');      
		
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.Name', 'ASC'); 
		$result = $this->db->get();
		
		return $result->result(); 
	}
		 
	public function get12betUserById_($where_arr, $fields="a.UserID, a.Username, a.SystemID, a.Currency, b.Abbreviation" , $index="Username") 
	{  
		$this->db->select("{$fields}");
		$this->db->from("csa_12bet_users AS a USE INDEX ({$index})");    
		$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
		if(count($where_arr) > 0)$this->db->where($where_arr); 
		$this->db->order_by('a.DateUpdated', 'DESC'); 
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
	
	public function insertUpdate12betUser_($table, $data=array(), $data_update=array()) 
	{   
		$updates = "";
		foreach($data_update as $update => $val){
			$updates .= $update."="."'".$val."'".", ";
		}
		$updates = trim(trim($updates),',');
		
		$sql = $this->db->insert_string($table, $data) . " ON DUPLICATE KEY UPDATE {$updates} ";
		$this->db->query($sql);
		$id = $this->db->insert_id();   
	 	return $id; 
	} 
	
	public function insert12betUser_($table, $datax, $action="add", $index="UserID", $index_value=""){  
		$data = array(); 
		 
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$datax);  
			return $this->db->insert_id(); 
		 }
		else
		 {
			 foreach($datax as $field => $value){ 
				 $data[$field] = $value; 
			 }  
				
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);  
			 return $x;  
		 }
		 
	}
	
	public function getGenerateActivities_($table, $conditions_array=array(), $fields="*", $limit="" )
	{	
		$this->db->select(" {$fields} ");
		$this->db->from($table." USE INDEX (Username)");  
		if(count($conditions_array) > 0)$this->db->where($conditions_array); 
		//$this->db->group_by("Username");  
		//$this->db->order_by('DateUpdated', 'DESC');  
		//$this->db->limit(30000, 330000); 
		$result = $this->db->get();    
		return $result->result();  
	 
	}
	
	/* End of Public Functions */ 
	
	
}

?>