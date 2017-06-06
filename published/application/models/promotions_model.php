<?php

class Promotions_Model extends CI_Model {

    private $table_name;

    /* Public Functions */

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function getActivitiesPure($where_arr = array()) {
        $this->db->select("a.* ", false);
        $this->db->from('csa_promotion_activities AS a'); 
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
 
        $this->db->order_by("a.ActivityID", "DESC");
        $result = $this->db->get();

        return $result->result();
		
    }
	
    public function getActivityManagement_($where_arr = array()) {
        $this->db->select("a.ActivityID, a.Status, (CASE WHEN a.Status = '0' THEN 'New' ELSE b.Name END) AS StatusName, c.Name AS GroupAssigneeName", false);
        $this->db->from('csa_promotion_activities AS a');
        $this->db->join('csa_status AS b', 'a.Status=b.StatusID', 'left');
        $this->db->join('csa_users_group AS c', 'a.GroupAssignee=c.GroupID', 'left');

        if (count($where_arr) > 0)
            $this->db->where($where_arr);

        $this->db->order_by("a.DateUpdatedInt", "DESC");
        $result = $this->db->get();
        return $result->result();
        /*
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
          } */
    }

    public function getActivityById_($where_arr = array()) {
        $this->db->select("a.*, TIMEDIFF(a.CallEnd, a.CallStart) AS CallDuration, 
						   b.CurrencyID, b.Abbreviation AS CurrencyName, b.InternalAbbreviation, c.Name AS CategoryName, 
						   d.Source AS ActivitySource, 
						   (CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
						   f.mb_nick AS UserUpdated, g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
						   h.Name AS PromotionName, h.Type AS PromotionType, h.BonusCode, h.CategoryID PromotionCategoryID, 
						   COUNT(DISTINCT i.AttachID) As CountAttach, j.Name AS ProductName, 
						   k.outcome_name AS OutcomeName, l.result_name AS ResultName, 
						   m.Name AS GroupAssigneeName, 
						   n.Name AS PromotionCategoryName, 
						   o.Name AS IssueName, 
						   p.mb_nick AS OfferedByName, 
						   q.mb_nick AS UploadedUser, 
						   r.Name AS CallResultCategoryName 
						  ", false);
        $this->db->from('csa_promotion_activities AS a');
        $this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
        $this->db->join('csa_promotion_categories AS c', 'a.Category=c.CategoryID', 'left');
        $this->db->join('csa_source AS d', 'a.Source=d.SourceID', 'left');
        $this->db->join('csa_status AS e', 'a.Status=e.StatusID', 'left');
        $this->db->join('g4_member AS f', 'a.UpdatedBy=f.mb_no', 'left');
        $this->db->join('g4_member AS g', 'a.AddedBy=g.mb_no', 'left');
        $this->db->join('csa_promotions AS h', 'a.Promotion=h.PromotionID', 'left');
        $this->db->join('csa_attach_file AS i', "a.ActivityID=i.ActivityID  AND i.Activity='promotion'", 'left');
        $this->db->join('csa_products AS j', 'a.Product=j.ProductID', 'left');
        $this->db->join('call_outcome AS k', 'a.CallOutcomeID=k.outcome_id', 'left');
        $this->db->join('call_result AS l', 'a.CallResultID=l.result_id', 'left');
        $this->db->join('csa_users_group AS m', 'a.GroupAssignee=m.GroupID', 'left');
        $this->db->join('csa_promotion_categories AS n', 'h.CategoryID=n.CategoryID', 'left');
        $this->db->join('csa_promotion_issues AS o', 'a.Issue=o.IssueID', 'left');
        $this->db->join('g4_member AS p', 'a.OfferedBy=p.mb_no', 'left');
        $this->db->join('g4_member AS q', 'a.UploadedBy=q.mb_no', 'left');    
		$this->db->join('csa_result_categories AS r', 'a.CallResultCategoryID=r.CategoryID', 'left');
		
        if (count($where_arr) > 0)
            $this->db->where($where_arr);

        $this->db->group_by('a.ActivityID');
        $this->db->order_by("a.DateUpdatedInt", "DESC");
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

    public function getActivity_($table, $conditions_array = array(), $limit = 1) {
        $this->db->select('*');
        $this->db->from($table);
        if (count($conditions_array) > 0)
            $this->db->where($conditions_array);
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
	
	public function getPromotionActivities_($search_string, $allowed_status, $paging = array(), $index = "ActivitiesUpdatedKey", $order_by = "a.DateUpdatedInt", $na_string="") {
        $limit_str = (count($paging) > 0) ? " LIMIT {$paging['page']}, {$paging['limit']}" : "";
        $search_string = ($search_string) ? " WHERE {$search_string} " : $search_string;
		$inner_query = "";
		if($na_string)
		 {
			 
			$inner_query = "SELECT a.*, @cnt := (@cnt + 1) as 'cnt' 
							FROM
								(
									SELECT a.* 
									FROM 
										(
										 SELECT a.ActivityID, a.Promotion, {$order_by} 
											FROM csa_promotion_activities as a USE INDEX ({$index})
											{$search_string} 
										) AS a   
									LEFT JOIN csa_promotions AS b ON a.Promotion=b.PromotionID	
									WHERE {$na_string}
								) AS a  ";  
		 }
		else //DEFAULT
		 {
			$inner_query = "SELECT a.*, @cnt := (@cnt + 1) as 'cnt'   
							FROM 
								(
								 SELECT a.ActivityID, {$order_by} 
									FROM csa_promotion_activities as a USE INDEX ({$index})
									{$search_string} 
								) AS a"; 
		 }
		 
		
        $sql = "SELECT  a.*, 
					   aa.*,  
					   b.Abbreviation AS Currency, 
					   c.Name AS CategoryName, 
					   d.Source AS ActivitySource, 
					   (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					   f.mb_nick, 
					   g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
					   h.Name AS PromotionName, h.Status AS PromotionStatus, h.BonusCode,  
					   i.CountAttach, 
					   j.Name AS ProductName, 
					   k.outcome_name AS CallOutcomeName, 
					   l.result_name AS CallResultName, 
					   m.Name AS GroupAssigneeName, 
					   n.Name AS IssueName, 
					   o.mb_nick AS UploadedUser , 
					   p.mb_nick AS OfferedByName, 
					   r.Name AS CallResultCategoryName 
				 FROM  
					(
						SELECT a.*  
						FROM 
						 (	
						 	{$inner_query} 
						 )as a
						ORDER BY {$order_by} DESC 
						{$limit_str} 
							
					)AS a
				LEFT JOIN csa_promotion_activities aa on a.ActivityID = aa.ActivityID 
				LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
				LEFT JOIN csa_promotion_categories AS c ON aa.Category=c.CategoryID 
				LEFT JOIN csa_source AS d ON aa.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON aa.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON aa.UpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON aa.AddedBy=g.mb_no    
				LEFT JOIN csa_promotions AS h ON aa.Promotion=h.PromotionID   
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='promotion'
						GROUP BY ActivityID
					) i ON a.ActivityID = i.ActivityID
				LEFT JOIN csa_products AS j ON aa.Product=j.ProductID 
				LEFT JOIN call_outcome AS k ON aa.CallOutcomeID=k.outcome_id 
				LEFT JOIN call_result AS l ON aa.CallResultID=l.result_id 
				LEFT JOIN csa_users_group AS m ON aa.GroupAssignee=m.GroupID 
				LEFT JOIN csa_promotion_issues AS n ON aa.Issue=n.IssueID  
				LEFT JOIN g4_member AS o ON aa.UploadedBy=o.mb_no 
				LEFT JOIN g4_member AS p ON aa.OfferedBy=p.mb_no 
				LEFT JOIN csa_result_categories AS r ON aa.CallResultCategoryID=r.CategoryID  
				";

        $this->db->query("SET @cnt := 0");
        $result = $this->db->query($sql);
        //return $result->result();    
		
        $query = $this->db->query('SELECT @cnt AS CountActivity');
        return array("total_rows" => $query->row()->CountActivity,
            "result" => $result->result()
        );
    }
	
	
   public function getPromotionActivities_ORIGINAL($search_string, $allowed_status, $paging = array(), $index = "ActivitiesUpdatedKey", $order_by = "a.DateUpdatedInt") {
        $limit_str = (count($paging) > 0) ? " LIMIT {$paging['page']}, {$paging['limit']}" : "";
        $search_string = ($search_string) ? " WHERE {$search_string} " : $search_string;

        $sql = "SELECT  a.*, 
					   aa.*,  
					   b.Abbreviation AS Currency, 
					   c.Name AS CategoryName, 
					   d.Source AS ActivitySource, 
					   (CASE WHEN aa.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					   f.mb_nick, 
					   g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, 
					   h.Name AS PromotionName, h.Status AS PromotionStatus, h.BonusCode,  
					   i.CountAttach, 
					   j.Name AS ProductName, 
					   k.outcome_name AS CallOutcomeName, 
					   l.result_name AS CallResultName, 
					   m.Name AS GroupAssigneeName, 
					   n.Name AS IssueName, 
					   o.mb_nick AS UploadedUser , 
					   p.mb_nick AS OfferedByName, 
					   r.Name AS CallResultCategoryName 
				 FROM  
					(
						SELECT a.*  
						FROM 
						 (	
							SELECT a.*, @cnt := (@cnt + 1) as 'cnt'   
							FROM 
								(
								 SELECT a.ActivityID, {$order_by} 
									FROM csa_promotion_activities as a USE INDEX ({$index})
									{$search_string} 
								) AS a  
						 )as a
						ORDER BY {$order_by} DESC 
						{$limit_str} 
							
					)AS a
				LEFT JOIN csa_promotion_activities aa on a.ActivityID = aa.ActivityID 
				LEFT JOIN csa_currency AS b ON aa.Currency=b.CurrencyID 
				LEFT JOIN csa_promotion_categories AS c ON aa.Category=c.CategoryID 
				LEFT JOIN csa_source AS d ON aa.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON aa.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON aa.UpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON aa.AddedBy=g.mb_no    
				LEFT JOIN csa_promotions AS h ON aa.Promotion=h.PromotionID   
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='promotion'
						GROUP BY ActivityID
					) i ON a.ActivityID = i.ActivityID
				LEFT JOIN csa_products AS j ON aa.Product=j.ProductID 
				LEFT JOIN call_outcome AS k ON aa.CallOutcomeID=k.outcome_id 
				LEFT JOIN call_result AS l ON aa.CallResultID=l.result_id 
				LEFT JOIN csa_users_group AS m ON aa.GroupAssignee=m.GroupID 
				LEFT JOIN csa_promotion_issues AS n ON aa.Issue=n.IssueID  
				LEFT JOIN g4_member AS o ON aa.UploadedBy=o.mb_no 
				LEFT JOIN g4_member AS p ON aa.OfferedBy=p.mb_no 
				LEFT JOIN csa_result_categories AS r ON aa.CallResultCategoryID=r.CategoryID  
				";

        $this->db->query("SET @cnt := 0");
        $result = $this->db->query($sql);
        //return $result->result();    
		
        $query = $this->db->query('SELECT @cnt AS CountActivity');
        return array("total_rows" => $query->row()->CountActivity,
            "result" => $result->result()
        );
    }
	
    public function manageActivity_($table, $datax, $action = "add", $index = "", $index_value = "") {
        $data = array();

        foreach ($datax as $field => $value) {
            $data[$field] = $value;
        }

        if ($action == "add") {
            $x = $this->db->insert($table, $data);
            return $this->db->insert_id();
        } else {
            $this->db->where($index, $index_value);
            $x = $this->db->update($table, $data);
            return $x;
        }
    }

    public function getPromotionsList_($where_str, $paging = array()) {
        $sql = "SELECT a.*, (CASE 
								WHEN a.Status = '1' THEN 'Active' 
							 	WHEN a.Status = '9' THEN 'Deleted'
							 ELSE 'Inactive' END) StatusName, 
				b.Abbreviation AS CurrencyName, c.Name AS ProductName, d.Name AS CategoryName, 
				e.mb_nick AS UpdatedByNickname, f.Name AS SubProductName    
				FROM csa_promotions AS a USE INDEX ()	   
				LEFT JOIN csa_currency AS b ON a.CurrencyID=b.CurrencyID  
				LEFT JOIN csa_products AS c on c.ProductID=a.ProductID 
				LEFT JOIN csa_promotion_categories AS d on d.CategoryID=a.CategoryID   
				LEFT JOIN g4_member AS e ON a.UpdatedBy=e.mb_no 
				LEFT JOIN csa_sub_products AS f ON a.SubProductID=f.SubID 
				WHERE a.PromotionID <> 0 $where_str 
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
				LIMIT {$paging['page']}, {$paging['limit']} ";
        $result = $this->db->query($sql); 
        return $result->result(); 
    }

    public function getPromotionById_($where_arr = array(), $where_or = array()) {
        $this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' 
							     	  WHEN a.Status = '9' THEN 'Deleted'   
								 ELSE 'Inactive' END) StatusName, 
						   b.Name AS ProductName, c.Abbreviation AS CurrencyName, d.Name AS CategoryName, 
						   e.Name AS SubProductName  
						");
        $this->db->from('csa_promotions a');
        $this->db->join('csa_products AS b', 'a.ProductID=b.ProductID', 'left');
        $this->db->join('csa_currency AS c', 'a.CurrencyID=c.CurrencyID', 'left');
        $this->db->join('csa_promotion_categories AS d', 'a.CategoryID=d.CategoryID', 'left');
        $this->db->join('csa_sub_products AS e', 'a.SubProductID=e.SubID', 'left');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        if (count($where_or) > 0)
            $this->db->or_where($where_or);
        $this->db->order_by("a.Name", "ASC");
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

    public function getChangePromotionById_($where_arr = array(), $where_or = array(), $active = 1) {
        $this->db->select("a.PromotionID, a.CategoryID, a.Name, a.MinimumAmount, a.MaximumAmount, a.Formula, a.WageringFormula, a.Turnover, a.BonusRate, a.BonusCode, a.Type,
						   a.StartedDate, a.EndDate, a.ProductID, a.Status, (CASE WHEN DATE(a.EndDate) > DATE(NOW()) THEN '0' ELSE '1' END) AS IsExpire, 
						   b.Name AS ProductName, c.Abbreviation AS CurrencyName, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('csa_promotions a');
        $this->db->join('csa_products AS b', 'a.ProductID=b.ProductID', 'left');
        $this->db->join('csa_currency AS c', 'a.CurrencyID=c.CurrencyID', 'left');

        /*if($active == 1)
            $this->db->where("DATE(NOW()) BETWEEN a.StartedDate AND a.EndDate");*/
			
        if(count($where_arr) > 0)
            $this->db->where($where_arr);

        if(count($where_or) > 0)
            $this->db->or_where($where_or);

        $this->db->order_by("a.Name", "ASC");
        $result = $this->db->get(); 
		
        return $result->result();
    }

    public function getPromotionsCategoriesList_($where_str, $paging = array()) {
        if (count($paging) > 0)
            $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";

        $sql = "SELECT a.*  
				FROM csa_promotion_categories AS a	  
				WHERE a.CategoryID <> 0 $where_str 
				ORDER BY a.Name ASC, a.Status ASC, a.DateUpdated DESC, a.DateAdded DESC  
			    {$limit_query} ";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getPromotionCategoryById_($where_arr = array()) {
        $this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('csa_promotion_categories a');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.Name", "ASC");
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

    public function getCallOutcomeList_($where_arr = array()) {
        $this->db->select("a.*, a.outcome_id AS ID, a.outcome_name AS Name,   
						  b.result_id, b.result_name , (CASE WHEN a.outcome_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('call_outcome a');
        $this->db->join('call_result AS b', 'a.result_id=b.result_id', 'left');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.outcome_name", "ASC");
        $result = $this->db->get();
        return $result->result();
    }
	
	public function getResultCategoriesList_($where_arr = array()) {
        $this->db->select("a.*, a.CategoryID AS ID,   
						   b.result_id, b.result_name , (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('csa_result_categories a');
        $this->db->join('call_result AS b', 'a.Result=b.result_id', 'left');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.Name", "ASC");
        $result = $this->db->get();
        return $result->result();
    }

    /* public function getCallResultList_($where_arr=array()) 
      {
      $this->db->select("a.result_id, a.result_name , (CASE WHEN a.result_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
      $this->db->from('call_result a');
      if(count($where_arr) > 0)$this->db->where($where_arr);
      $this->db->order_by("a.result_name", "ASC");
      $result = $this->db->get();
      return $result->result();

      } */

    //agent summary report
    public function getResultList_($where_arr = array()) {
        $this->db->select("a.*, (CASE WHEN a.result_status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('call_result a');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.result_id", "ASC");
        $result = $this->db->get();
        return $result->result();
    }

    public function countAgentsCalls_($post, $where_arr = array()) {
        $join_where = "";
        if ($post['s_fromdate'] && $post['s_todate'])
            $join_where .= " AND (b.DateAddedInt BETWEEN " . strtotime($post['s_fromdate']) . " AND " . strtotime($post['s_todate']) . ") ";

        if ($post['s_currency']) {
            $join_where .= " AND b.Currency='{$post[s_currency]}' ";
            $this->db->where("FIND_IN_SET({$post[s_currency]}, a.mb_currencies) !=", 0);
        }

        $where = array("a.mb_status =" => '1');
        $this->db->select("SQL_CALC_FOUND_ROWS a.mb_no, SUM(IF((b.CallResultID<>0 && b.CallOutcomeID<>0), 1, 0)) AS TotalCall", false);
        $this->db->from("g4_member AS a");
        $this->db->join("csa_calls AS b", "a.mb_no=b.AddedBy AND (b.CallOutcomeID<>0) AND (b.CallResultID<>0) {$join_where} ", "left");

        $this->db->where($where);
        $this->db->where("FIND_IN_SET(a.mb_usertype, '{$this->callers}')");

        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->group_by("a.mb_no");
        $this->db->having("TotalCall > 0");
        $this->db->order_by("a.mb_nick", "ASC");
        $this->db->order_by("b.CallResultID", "ASC");

        $result = $this->db->get();
        return $result->result();
    }

    public function getCountCallsList_($search_string, $call_search_string, $sum_str, $paging = array(), $index = "AgentSummaryReport", $order_by = "DateAddedInt") {
        $limit_str = (count($paging) > 0) ? " LIMIT {$paging['page']}, {$paging['limit']}" : "";
        $search_string = ($search_string) ? " WHERE {$search_string} " : $search_string;
        $call_search_string = ($call_search_string) ? " WHERE {$call_search_string} " : $call_search_string;

        $sql = "SELECT SQL_CALC_FOUND_ROWS 
					   a.mb_no, a.mb_nick, 
					   b.* 
				FROM g4_member AS a  
				LEFT JOIN
					(
						SELECT AddedBy, 
							   SUM(IF((CallResultID<>0 && CallOutcomeID<>0), 1, 0)) AS TotalCall, 
						   	   SUM(IF(CallResultID = 1,TIME_TO_SEC(TIMEDIFF(CallEnd, CallStart)),0)) AS ReachDuration	
							  {$sum_str} 
						FROM csa_calls USE INDEX ($index) 
						{$call_search_string}
						GROUP BY AddedBy  
					) b ON a.mb_no = b.AddedBy  
				{$search_string}  
				GROUP BY a.mb_no
				HAVING b.TotalCall > 0
				ORDER BY a.mb_nick ASC
				{$limit_str}   
				";

        $result = $this->db->query($sql);
        //return $result->result();      
        // echo $this->db->last_query(); 
        $query = $this->db->query('SELECT FOUND_ROWS() AS CountActivity');
        return array("total_rows" => $query->row()->CountActivity,
            "result" => $result->result()
        );
    }

    //end agent summary report
    //AGENT CALL DETAILS 
    public function getPromotionCallDetails_XXX($search_string, $allowed_status, $paging = array()) {
        $limit_str = (count($paging) > 0) ? "LIMIT {$paging['page']}, {$paging['limit']}" : "";

        $sql = " SELECT x.CallID, x.ActivityID, x.CallStart AS CallStartDetail, x.CallEnd AS CallEndDetail,  
							 a.*, TIMEDIFF(a.CallEnd, a.CallStart) AS CallDuration, b.Abbreviation AS Currency, 
							 c.Name AS CategoryName, d.Source AS ActivitySource, 
							 (CASE WHEN a.Status = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
							 f.mb_nick, g.mb_nick AS CreatedByNickname, g.mb_usertype AS AddedUserType, h.Name AS PromotionName, 
							 j.Name AS ProductName, k.outcome_name AS CallOutcomeName, 
							 l.result_name AS CallResultName, 
							 m.Name AS GroupAssigneeName     
						 FROM csa_calls AS x USE INDEX (DateAddedInt)  
							LEFT JOIN csa_promotion_activities AS a ON x.ActivityID=a.ActivityID 
							LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
							LEFT JOIN csa_promotion_categories AS c ON a.Category=c.CategoryID 
							LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
							LEFT JOIN csa_status AS e ON a.Status=e.StatusID 
							LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no  
							LEFT JOIN g4_member AS g ON a.AddedBy=g.mb_no    
							LEFT JOIN csa_promotions AS h ON a.Promotion=h.PromotionID 
							LEFT JOIN csa_products AS j on a.Product=j.ProductID 
							LEFT JOIN call_outcome AS k on a.CallOutcomeID=k.outcome_id 
							LEFT JOIN call_result AS l on a.CallResultID=l.result_id 
							LEFT JOIN csa_users_group AS m on a.GroupAssignee=m.GroupID 
						 WHERE a.ActivityID <> 0 $search_string  
						 ORDER BY a.DateAddedInt DESC, a.Status ASC
						 $limit_str   
					   ";

        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getPromotionCallDetails_($search_string = "", $search_string2 = "", $allowed_status, $paging = array()) {
        $limit_str = (count($paging) > 0) ? "LIMIT {$paging['page']}, {$paging['limit']}" : "";
        $search_string = ($search_string) ? " WHERE {$search_string} " : "";
        //$search_string2 = ($search_string2)?" WHERE {$search_string2} ":"";

        $sql = "SELECT   
             a.*,
             b.CallStart AS CallStartDetail, b.CallEnd AS CallEndDetail, TIMEDIFF(b.CallEnd, b.CallStart) AS CallDuration,  
             c.*,  
             d.Abbreviation AS Currency, 
    		 e.Name AS CategoryName, 
             f.Source AS ActivitySource,    
             (CASE WHEN c.Status = '0' THEN 'New' ELSE g.Name END) AS StatusName,  
     		 h.mb_nick, 
             i.mb_nick AS CreatedByNickname, i.mb_usertype AS AddedUserType,   
             j.Name AS PromotionName, 
     		 k.Name AS ProductName, 
             l.outcome_name AS CallOutcomeName,  
             m.result_name AS CallResultName, 
     		 n.Name AS GroupAssigneeName
              
            FROM 
                (    
                    SELECT a.* 
					FROM 
					  ( 
						SELECT a.*, @cnt := (@cnt + 1) as 'cnt'   
						FROM 
						   (			
							   SELECT  CallID, ActivityID AS CallActivityID, AddedBy AS CallAddedBy, DateAddedInt AS CallDateAddedInt
							   FROM csa_calls USE INDEX (AgentCallDetails)   
							   {$search_string}  
							) AS a 
						LEFT JOIN csa_promotion_activities  ON 
								   a.CallActivityID=ActivityID 
								   {$search_string2}
						 
					 )AS a		
					ORDER BY a.CallDateAddedInt DESC  
					{$limit_str}
                ) AS a
            LEFT JOIN csa_calls AS b ON a.CallID=b.CallID          
            LEFT JOIN csa_promotion_activities AS c ON a.CallActivityID=c.ActivityID 
            LEFT JOIN csa_currency AS d ON c.Currency=d.CurrencyID  
            LEFT JOIN csa_promotion_categories AS e ON c.Category=e.CategoryID   
            LEFT JOIN csa_source AS f ON c.Source=f.SourceID      
            
            LEFT JOIN csa_status AS g ON c.Status=g.StatusID   
            LEFT JOIN g4_member AS h ON a.CallAddedBy=h.mb_no   
            LEFT JOIN g4_member AS i ON c.AddedBy=i.mb_no      
            LEFT JOIN csa_promotions AS j ON c.Promotion=j.PromotionID   
            LEFT JOIN csa_products AS k on c.Product=k.ProductID   
            LEFT JOIN call_outcome AS l on b.CallOutcomeID=l.outcome_id   
            LEFT JOIN call_result AS m on b.CallResultID=m.result_id    
            LEFT JOIN csa_users_group AS n on c.GroupAssignee=n.GroupID  
            ";

        $this->db->query("SET @cnt := 0");
        $result = $this->db->query($sql);
        //return $result->result();    
		
        $query = $this->db->query('SELECT @cnt AS CountActivity');
        return array("total_rows" => $query->row()->CountActivity,
            "result" => $result->result()
        );
    }

    //END AGENT CALL DETAILS 
    //FOR SEARCH ACTIVITIES  
    public function countActivitiesSearch_($search_string = "", $search_string2 = "", $table = "csa_promotion_activities") {
        $search_string = ($search_string) ? " WHERE {$search_string} " : "";
        //$search_string2 = ($search_string2)?" WHERE {$search_string2} ":"";
        $sql = "SELECT COUNT(a.ActivityID) AS CountActivity 
				 FROM 
				   (			
					   SELECT a.ActivityID 
					   FROM csa_activities_history AS a USE INDEX (SearchActivities)   
					   {$search_string}
					   GROUP BY a.ActivityID     
					) AS a 
				 INNER JOIN csa_promotion_activities AS b ON a.ActivityID=b.ActivityID  
					  {$search_string2}   
					";

        $result = $this->db->query($sql);

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

    //SEARCH ACTIVITIES
    public function getSearchActivities_($search_string = "", $search_string2 = "", $allowed_status, $paging = array()) {
        $limit_str = (count($paging) > 0) ? "LIMIT {$paging['page']}, {$paging['limit']}" : "";
        $search_string = ($search_string) ? " WHERE {$search_string} " : "";
        //$search_string2 = ($search_string2)?" WHERE {$search_string2} ":"";

        $sql = "SELECT a.*, 
					 b.*, b.DateUpdated AS DateLastUpdated, b.Status AS CurrentStatus, TIMEDIFF(b.CallEnd, b.CallStart) AS CallDuration,  
					 c.Name AS CategoryName, 
					 d.Source AS ActivitySource, 
					 (CASE WHEN a.SearchStatus = '0' THEN 'New' ELSE e.Name END) AS StatusName, 
					 f.mb_nick, g.mb_nick AS CreatedByNickname, 
					 g.mb_usertype AS AddedUserType, 
					 h.Name AS PromotionName, 
					 i.CountAttach, 
					 j.Name AS ProductName, 
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
							INNER JOIN csa_promotion_activities AS b ON a.SearchActivityID=b.ActivityID 
								{$search_string2} 
						
						 )AS a		
						ORDER BY a.SearchDateUpdatedInt  
						{$limit_str }  
						
					) AS a
				LEFT JOIN csa_promotion_activities AS b ON a.SearchActivityID=b.ActivityID  
				LEFT JOIN csa_promotion_categories AS c ON b.Category=c.CategoryID 
				LEFT JOIN csa_source AS d ON b.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.SearchStatus=e.StatusID 
				LEFT JOIN g4_member AS f ON a.SearchUpdatedBy=f.mb_no  
				LEFT JOIN g4_member AS g ON b.AddedBy=g.mb_no    
				LEFT JOIN csa_promotions AS h ON b.Promotion=h.PromotionID   
				LEFT JOIN
					(
						SELECT 
							ActivityID,
							count(ActivityID) 'CountAttach'
						FROM csa_attach_file
						WHERE Activity='promotion'
						GROUP BY ActivityID
					) i ON a.SearchActivityID = i.ActivityID
				LEFT JOIN csa_products AS j on b.Product=j.ProductID 
				LEFT JOIN call_outcome AS k on b.CallOutcomeID=k.outcome_id 
				LEFT JOIN call_result AS l on b.CallResultID=l.result_id  
				LEFT JOIN csa_currency AS m ON b.Currency=m.CurrencyID 	
				LEFT JOIN csa_status AS n ON b.Status=n.StatusID 
				LEFT JOIN csa_users_group AS o ON b.GroupAssignee=o.GroupID  
				";

        $this->db->query("SET @cnt := 0");
        $result = $this->db->query($sql);
        //return $result->result();   
        $query = $this->db->query('SELECT @cnt AS CountActivity');
        return array("total_rows" => $query->row()->CountActivity,
            "result" => $result->result()
        );
    }

    //END SEARCH ACTIVITIES 

    /* public function getPromotionIssuesList_($where_arr=array()) 
      {
      $this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
      $this->db->from('csa_promotion_issues a');
      if(count($where_arr) > 0)$this->db->where($where_arr);
      $this->db->order_by("a.Name", "ASC");
      $result = $this->db->get();
      return $result->result();
      } */

    public function getPromotionsIssuesList_($where_str, $paging = array()) {
        if (count($paging) > 0)
            $limit_query = "LIMIT {$paging['page']}, {$paging['limit']} ";

        $sql = "SELECT a.*  
				FROM csa_promotion_issues AS a	  
				WHERE a.IssueID <> 0 $where_str 
				ORDER BY a.Status ASC, a.Name ASC, a.DateUpdated DESC, a.DateAdded DESC  
			    {$limit_query} ";
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function getPromotionIssues_($where_arr = array()) {
        $this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('csa_promotion_issues a');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.Name", "ASC");
        $result = $this->db->get();
        return $result->result();
    }

    public function getPromotionIssuesById_($where_arr = array()) {
        $this->db->select("a.*, (CASE WHEN a.Status = '1' THEN 'Active' ELSE 'Inactive' END) StatusName");
        $this->db->from('csa_promotion_issues a');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by("a.Name", "ASC");
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

    //WEBSITE REGISTER PROMOTION
    public function getWebsiteRegisterById_($where_arr, $fields = "a.*", $index = "UniqueID") {
        $this->db->select("{$fields}");
        $this->db->from("csa_website_register AS a USE INDEX ({$index})");
        //$this->db->join('csa_currency AS b', 'a.Currency=b.CurrencyID', 'left');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);
        $this->db->order_by('a.DateAdded', 'DESC');
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

    public function checkWebsitePromotion_($where_arr = array()) {
        $this->db->select("a.ActivityID, a.Promotion, a.DateAdded,   
						   h.Name AS PromotionName, h.BonusCode, h.CategoryID PromotionCategoryID 
						  ", false);
        $this->db->from('csa_promotion_activities AS a');
        $this->db->join('csa_promotions AS h', 'a.Promotion=h.PromotionID', 'left');
        if (count($where_arr) > 0)
            $this->db->where($where_arr);

        $this->db->group_by('a.ActivityID');
        $this->db->order_by("a.DateUpdatedInt", "DESC");
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

    /* Private Functions */

    private function select_strict($where = array(), $table = "", $order = array(), $offset = 0, $limit = 0) {
        if (empty($table))
            $table = $this->table_name;
        $where_str = $order_str = $limit_str = "";
        $where_arr = $order_arr = array();
        foreach ($where as $field => $value) {
            $where_arr[] = " `" . $field . "` = " . $this->db->escape($value) . " ";
        }

        foreach ($order as $field => $order) {
            $order_arr[] = " `" . $field . "` " . $order . " ";
        }

        $where_str = implode(" AND ", $where_arr);
        $where_str = ($where_str == "" ? "" : "WHERE " . $where_str);
        $order_str = implode(" , ", $order_arr);
        $order_str = ($order_str == "" ? "" : "ORDER BY " . $order_str);
        if (!empty($limit)) {
            $limit_str = "LIMIT {$offset},{$limit}";
        }

        $result = $this->db->query("SELECT * FROM " . $table . " " . $where_str . " " . $order_str . " " . $limit_str);

        return $result;
    }

    public function updateActivitySystemID($table, $set, $token) {
        $this->db->trans_start();
        $this->db->update_batch($table, $set, $token);
        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
    }

    public function insertBatch($table, $data) {
        $this->db->trans_start();
        $this->db->insert_batch($table, $data);
        $this->db->trans_complete();
        return ($this->db->trans_status() === FALSE) ? FALSE : TRUE;
    }

    /* End of Private Functions */
}

?>