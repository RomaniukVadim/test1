<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search_Promotions_Activities extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/dashboard

	 *	- or -  
	 * 		http://example.com/index.php/dashboard/index
	 *	- or -
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/dashboard/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html 
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->model("promotions_model","promotions");  
		$this->load->model("common_model","common");  
		$this->activity_type = "promotion"; 
		 
	}
 	
	public function index()
	{    
		if(!admin_access() && !allow_search()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$categories_where = " AND a.Status='1' "; 
		
   		if(restriction_type())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 }
		 
		//$activities = $this->getActivities($this->input->post());						
		$data2 = array("main_page"=>"promotions",  
					   "currencies"=>$this->common->getCurrency_(), 
					   //"sources"=>$this->common->getSource_(), 
					   "categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where), 
					   "issues"=>$this->promotions->getPromotionIssues_(array("a.Status ="=>'1')),  
					   "status_list"=>$this->common->getStatusList_(3, "all"),//3 for promotion page  
					   "outcomes"=>$this->promotions->getCallOutcomeList_(array("a.outcome_status ="=>'1')), 
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')),//user types
					   "s_page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Search Promotional Activities", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('promotions/promotions_search_activities_tpl');
		$this->load->view('footer');   
	}
	
	public function searchActivities()
	{ 
		$this->index();
	}
	
	public function getSearchActivities($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }
		
		if(!admin_access() && !allow_search()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$data = $this->input->post();
		$view_statuslist = array();  
		$result = $this->common->getUserStatusViews(3);//3-promotions
		$view_statuslist = explode(',',$result->StatusList); 
		
		if(restriction_type())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 }
		$categories = $this->promotions->getPromotionsCategoriesList_($categories_where); 
		$cat_ids = implode(',', array_map(function($object){return $object->CategoryID;}, $categories));
		 
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
	    
		$search_string .= "(a.Activity='promotion') ";   
		$search_string2 = "";
		$allow_view = 0; 
		$allow_close = 0;
		   
		if(trim($data[s_categories]))
		 {
			$search_string2 .= " AND (b.Category='".$this->common->escapeString_($data[s_categories])."') "; 
			$search_url .= "&s_category=".$s_category; 
		 }  
		else
		 {
			if(restriction_type())
			 { 
				//$search_string .= " AND FIND_IN_SET(b.Category, '".$cat_ids."') ";  
				$search_string2 .= " AND b.Category IN({$cat_ids})";  
				$search_url .= "&s_category=".$data[s_categories];
			 }
		 } 
		
		if(trim($data[s_issue]))
		 {
			$search_string2 .= " AND (b.Issue='".$this->common->escapeString_($data[s_issue])."') "; 
			$search_url .= "&s_issue=".$data[s_issue];  
		 } 
		  
		if(trim($data[s_important])=='1')
		 {
			$search_string2 .= " AND (b.Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$data[s_important];  
		 }
		
		if(trim($data[s_iscomplaint])=='1')
		 {
			$search_string2 .= " AND (b.IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);  
		 }
		
		if(trim($data[s_uploadpm])==1)
		 {
			$search_string2 .= " AND (b.IsUploadPM='".$this->common->escapeString_($data[s_uploadpm])."') "; 
			$search_url .= "&s_uploadpm=".$$data[s_uploadpm];  
			$allow_close++;
		 }
		 
		if(trim($data[s_isuploaded])==1)
		 {
			$search_string2 .= " AND (b.IsUpload='".$this->common->escapeString_($data[s_isuploaded])."') "; 
			$search_url .= "&s_isuploaded=".$$data[s_isuploaded];  
		 }
		  
		if(trim($data[s_esupportid]))
		 {
			$search_string2 .= " AND (b.ESupportID LIKE '%".$this->common->escapeString_(trim($data[s_esupportid]))."%') "; 
			$search_url .= "&s_esupportid=".trim($data[s_esupportid]); 
		 }
		 
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (b.Currency='".$this->common->escapeString_(trim($data[s_currency]))."') "; 
			$search_url .= "&s_currency=".trim($data[s_currency]); 
		 }
		else
		 {     
			$search_string2 .= " AND b.Currency IN({$this->session->userdata(mb_currencies)}) "; 
		 }
		 
		if(trim($data[s_username]))
		 {
			$search_string2 .= " AND (b.Username LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_username=".trim($data[s_username]); 
		 } 
		
		if(trim($data[s_promotion]))
		 {
			$search_string2 .= " AND (b.Promotion='".$this->common->escapeString_(trim($data[s_promotion]))."') ";  
			$search_url .= "&s_promotion=".trim($data[s_promotion]);  
			$allow_view++;
		 } 
		 
		if(trim($data[s_transactionid]))
		 {
			$search_string2 .= " AND (b.TransactionID LIKE '%".$this->common->escapeString_(trim($data[s_transactionid]))."%') "; 
			$search_url .= "&s_transactionid=".trim($data[s_transactionid]); 
		 }  
		  
		if(trim($data[s_source]))
		 {
			$search_string2 .= " AND (b.Source='".$this->common->escapeString_(trim($data[s_source]))."') "; 
			$search_url .= "&s_source=".trim($data[s_source]); 
		 }
		 
		/*if(trim($data[s_problem]))
		 {
			$search_string2 .= " AND (b.Problem='".$this->common->escapeString_(trim($data[s_problem]))."') "; 
			$search_url .= "&s_problem=".trim($data[s_problem]); 
		 }*/
		  
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  "; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));   
		 } 
		
		if(trim($data[s_status]) != "")
		 {
			$search_string .= " AND (a.Status='".trim($data[s_status])."') ";  
			$search_url .= "&s_status=".trim($data[s_status]);  
			$allow_view++; 
		 }
		/*else
		 {
			if(restriction_type())
			 {     
				$search_string .= " AND FIND_IN_SET(a.Status, '".implode(',', $view_statuslist)."') ";   
				$search_url .= "&s_status=".trim($data[s_status]);	 
			 }
		 }*/ 
		   
		/*if(trim($data[s_agent]) != "")
		 {
			$search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";  
			$search_url .= "&s_agent=".trim($data[s_agent]); 
		 } */ 
	 	
		//for call search 
		if(trim($data[s_calloutcome]))
		 {
			$search_string2 .= " AND (b.CallOutcomeID='".$this->common->escapeString_($data[s_calloutcome])."') "; 
			$search_url .= "&s_calloutcome=".$s_calloutcome; 
		 }
		
		if(trim($data[s_callsendsms])==1)
		 {
			$search_string2 .= " AND (b.CallSendSMS='".$this->common->escapeString_($data[s_callsendsms])."') "; 
			$search_url .= "&s_callsendsms=".$s_callsendsms; 
		 } 
		
		if(trim($data[s_callsendemail])==1)
		 {
			$search_string2 .= " AND (b.CallSendEmail='".$this->common->escapeString_($data[s_callsendemail])."') "; 
			$search_url .= "&s_callsendemail=".$s_callsendemail; 
		 }   
		
		if(trim($data[s_callproblem]))
		 {
			$search_string2 .= " AND (b.CallProblem='".$this->common->escapeString_($data[s_callproblem])."') "; 
			$search_url .= "&s_callproblem=".$s_callproblem; 
		 }  
		//end for call search
		
		if(trim($data[s_assignee]) != "")
		 {
			$search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";  
			$search_url .= "&s_assignee=".trim($data[s_assignee]); 
			$allow_close++; 
			$allow_view++; 
		 } 
		
		if(restriction_type() && ($allow_view == 0) )
		 {   
			//$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (b.AddedBy='".trim($this->session->userdata("mb_no"))."') )";   
			//$search_url .= "&s_assignee=".trim($data[s_assignee]);	 
		 }
		 
		//$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string;
		
		$per_page = 20; 
		//$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
		$page = ($data['s_page'])? $data['s_page'] : 0;   
		//$total_rows = $this->common->countActivitiesSearch_($search_string, "csa_promotion_activities")->CountActivity; 
		//$activities = $this->promotions->getSearchActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page));
		
		$return = $this->promotions->getSearchActivities_($search_string, $search_string2, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page));  
		$total_rows = $return[total_rows]; 
		$activities = $return[result]; 
		 
		$pagination_options = array("link"=>"",//base_url()."promotions/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + 20) <= $total_rows)?$page + 20:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"activities":"activity";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
						   
		if($actual == 1)//
		 {  
		 	 $return = array("activities"=>$activities, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows 
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("activities"=>$this->generateHtmlList($activities), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows
					   );
			 echo json_encode($return); 
		 }
		
	}
	 
	
	  
	public function generateHtmlList($activities)
	{
		$return = ""; 
		if(count($activities))
		 { 
			foreach($activities as $row=>$activity){ 
				$is_important = ($activity->Important==1)?" act-danger ":""; 
				$current_stat = ($activity->CurrentStatus == $activity->SearchStatus)?"green":"act-danger";
				$return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" >  
							<td class=\"center\" >{$activity->Currency}</td>
							<td class=\"center {$is_important}\" >{$activity->Username}</td>
							<td >{$activity->PromotionName}</td>
							<td class=\"center\">{$activity->TransactionID}</td> 
							<td class=\"right\">{$activity->DepositAmount}</td>
							<td class=\"right\">{$activity->BonusAmount}</td>
							<td class=\"right\">{$activity->WageringAmount}</td>
							<td class=\"center {$current_stat}\" >".ucwords($activity->StatusName)."</td>
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\"  target=\"CsaContentDetails\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							"; 
				
				//check if usertype allowed to edit activity
				//if(allowEditMain())$return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
				
				//check if attachment
				if($activity->CountAttach > 0)$return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0 gap-right10\" ></i></a>";
				
				//check if call
				if(($activity->CallStart != "0000-00-00 00:00:00" && $activity->CallStart != "" && $activity->CallEnd != "0000-00-00 00:00:00" && $activity->CallEnd != "") )$return .= "<a href=\"#ActivityDetailsModal\" title=\"view call info\" alt=\"view call info\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"ViewCall{$activity->ActivityID}\"  data-toggle=\"modal\" call-info=\"1\" target=\"CrmContentDetails\" ><i class=\"icon16 i-phone-2 gap-left0\" ></i></a>";
			                 
                                			
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"10\" >No activity found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
 	 
	 
	public function exportSearchActivities($actual=0)
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !allow_export_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		/*error_reporting(E_ALL);
		ini_set('display_errors', '1');*/
		set_time_limit(0);
		 
		$data = $this->input->post();
		$view_statuslist = array();  
		$result = $this->common->getUserStatusViews(3);//3-promotions
		$view_statuslist = explode(',',$result->StatusList); 
		
		 
		if(restriction_type())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 }
		$categories = $this->promotions->getPromotionsCategoriesList_($categories_where); 
		$cat_ids = implode(',', array_map(function($object){return $object->CategoryID;}, $categories));
		 
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
	     
		$search_string .= "(a.Activity='promotion') ";   
		$search_string2 = "";
		$allow_view = 0; 
		$allow_close = 0;
		   
		if(trim($data[s_categories]))
		 {
			$search_string2 .= " AND (b.Category='".$this->common->escapeString_($data[s_categories])."') "; 
			$search_url .= "&s_category=".$s_category; 
		 }  
		else
		 {
			if(restriction_type())
			 { 
				//$search_string .= " AND FIND_IN_SET(b.Category, '".$cat_ids."') "; 
				$search_string2 .= " AND b.Category IN({$cat_ids})";  
				$search_url .= "&s_category=".$data[s_categories];
			 }
		 } 
		
		if(trim($data[s_issue]))
		 {
			$search_string2 .= " AND (b.Issue='".$this->common->escapeString_($data[s_issue])."') "; 
			$search_url .= "&s_issue=".$data[s_issue];  
		 } 
		  
		if(trim($data[s_important])=='1')
		 {
			$search_string2 .= " AND (b.Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$data[s_important];  
		 }
		
		if(trim($data[s_iscomplaint])=='1')
		 {
			$search_string2 .= " AND (b.IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);  
		 }
		
		if(trim($data[s_uploadpm])==1)
		 {
			$search_string2 .= " AND (b.IsUploadPM='".$this->common->escapeString_($data[s_uploadpm])."') "; 
			$search_url .= "&s_uploadpm=".$$data[s_uploadpm];  
			$allow_close++;
		 }
		 
		if(trim($data[s_isuploaded])==1)
		 {
			$search_string2 .= " AND (b.IsUpload='".$this->common->escapeString_($data[s_isuploaded])."') "; 
			$search_url .= "&s_isuploaded=".$$data[s_isuploaded];  
		 }
		  
		if(trim($data[s_esupportid]))
		 {
			$search_string2 .= " AND (b.ESupportID LIKE '%".$this->common->escapeString_(trim($data[s_esupportid]))."%') "; 
			$search_url .= "&s_esupportid=".trim($data[s_esupportid]); 
		 }
		 
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (b.Currency='".$this->common->escapeString_(trim($data[s_currency]))."') "; 
			$search_url .= "&s_currency=".trim($data[s_currency]); 
		 }
		else
		 {     
			$search_string2 .= " AND b.Currency IN({$this->session->userdata(mb_currencies)}) "; 
		 }
		 
		if(trim($data[s_username]))
		 {
			$search_string2 .= " AND (b.Username LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_username=".trim($data[s_username]); 
		 } 
		
		if(trim($data[s_promotion]))
		 {
			$search_string2 .= " AND (b.Promotion='".$this->common->escapeString_(trim($data[s_promotion]))."') ";  
			$search_url .= "&s_promotion=".trim($data[s_promotion]);  
			$allow_view++;
		 } 
		 
		if(trim($data[s_transactionid]))
		 {
			$search_string2 .= " AND (b.TransactionID LIKE '%".$this->common->escapeString_(trim($data[s_transactionid]))."%') "; 
			$search_url .= "&s_transactionid=".trim($data[s_transactionid]); 
		 }  
		  
		if(trim($data[s_source]))
		 {
			$search_string2 .= " AND (b.Source='".$this->common->escapeString_(trim($data[s_source]))."') "; 
			$search_url .= "&s_source=".trim($data[s_source]); 
		 }
		 
		/*if(trim($data[s_problem]))
		 {
			$search_string2 .= " AND (b.Problem='".$this->common->escapeString_(trim($data[s_problem]))."') "; 
			$search_url .= "&s_problem=".trim($data[s_problem]); 
		 }*/
		  
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  "; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));   
		 } 
		
		if(trim($data[s_status]) != "")
		 {
			$search_string .= " AND (a.Status='".trim($data[s_status])."') ";  
			$search_url .= "&s_status=".trim($data[s_status]);  
			$allow_view++; 
		 }
		/*else
		 {
			if(restriction_type())
			 {     
				$search_string .= " AND FIND_IN_SET(a.Status, '".implode(',', $view_statuslist)."') ";   
				$search_url .= "&s_status=".trim($data[s_status]);	 
			 }
		 }*/ 
		   
		/*if(trim($data[s_agent]) != "")
		 {
			$search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";  
			$search_url .= "&s_agent=".trim($data[s_agent]); 
		 } */ 
	 	
		//for call search 
		if(trim($data[s_calloutcome]))
		 {
			$search_string2 .= " AND (b.CallOutcomeID='".$this->common->escapeString_($data[s_calloutcome])."') "; 
			$search_url .= "&s_calloutcome=".$s_calloutcome; 
		 }
		
		if(trim($data[s_callsendsms])==1)
		 {
			$search_string2 .= " AND (b.CallSendSMS='".$this->common->escapeString_($data[s_callsendsms])."') "; 
			$search_url .= "&s_callsendsms=".$s_callsendsms; 
		 } 
		
		if(trim($data[s_callsendemail])==1)
		 {
			$search_string2 .= " AND (b.CallSendEmail='".$this->common->escapeString_($data[s_callsendemail])."') "; 
			$search_url .= "&s_callsendemail=".$s_callsendemail; 
		 }   
		
		if(trim($data[s_callproblem]))
		 {
			$search_string2 .= " AND (b.CallProblem='".$this->common->escapeString_($data[s_callproblem])."') "; 
			$search_url .= "&s_callproblem=".$s_callproblem; 
		 }  
		//end for call search
		
		if(trim($data[s_assignee]) != "")
		 {
			$search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";  
			$search_url .= "&s_assignee=".trim($data[s_assignee]); 
			$allow_close++; 
			$allow_view++; 
		 } 
		
		if(restriction_type() && ($allow_view == 0) )
		 {   
			//$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (b.AddedBy='".trim($this->session->userdata("mb_no"))."') )";   
			//$search_url .= "&s_assignee=".trim($data[s_assignee]);	 
		 }
		 
		//$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string;
		
		$per_page = 20; 
		//$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
		$page = ($data['s_page'])? $data['s_page'] : 0;   
		//$total_rows = $this->common->countSearchActivities_($search_string, "csa_promotion_activities")->CountActivity; 
		//$activities = $this->promotions->getSearchActivities_($search_string, $view_statuslist);
	 	//$total_rows = count($activities); 
		
		$return = $this->promotions->getSearchActivities_($search_string, $search_string2, $view_statuslist, array());  
		$total_rows = $return[total_rows]; 
		$activities = $return[result];
		  
						    
		/*$excel_data = array("DateAdded"=>"Date Added", 
							"SearchDateUpdated"=>"Date Updated", 
							"Currency"=>"Currency", 
							"Username"=>"Username", 
							"ProductName"=>"Product", 
							"CategoryName"=>"Category Name", 
							"PromotionName"=>"Promotion", 
							"SystemID"=>"System ID", 
							"ESupportID"=>"E-Support ID", 
							"ActivitySource"=>"Source",  
							"TransactionID"=>"Transaction ID", 
							"CurrentBalance"=>"Current Balance", 
							"OutstandingBets"=>"Outstanding Bets", 
							"DepositAmount"=>"Deposit Amount", 
							"BonusAmount"=>"Bonus Amount", 
							"WageringAmount"=>"Wagering Amount", 
							"TurnoverAmount"=>"Turnover Amount", 
							"CashbackAmount"=>"Cashback Amount",   
							"BonusDeduct"=>"Bonus Deduct",  
							"WinningsDeduct"=>"Winnings Deduct",
							"Important"=>"Important", 
							"IsComplaint"=>"Is Complaint",  
							"StatusName"=>"Status", 
							"CurrentStatusName"=>"Current Status", 
							"Remarks"=>"Remarks",  
							"GroupAssigneeName"=>"Current Assignee", 
							"mb_nick"=>"Updated By", 
							"CallDuration"=>"Call Duration", 
							"CallStart"=>"Call Started", 
							"CallEnd"=>"Call Ended", 
							"CallOutcomeName"=>"Call Outcome", 
							"CallResultName"=>"Call Result", 
							"CallSendSMS"=>"Send SMS", 
							"CallSendEmail"=>"Send Email", 
							"CallProblem"=>"Call Category"
						);*/
		
		$excel_data = array("DateAdded"=>"Date Added", 
						   "SearchDateUpdatedInt"=>"Date Updated", 
						   "Currency"=>"Currency", 
						   "Username"=>"Username", 
						   "ProductName"=>"Product", 
						   "CategoryName"=>"Category Name", 
						   "PromotionName"=>"Promotion", 
						   "SystemID"=>"System ID", 
						   "ESupportID"=>"E-Support ID", 
						   "ActivitySource"=>"Source",  
						   "TransactionID"=>"Transaction ID", 
						   "CurrentBalance"=>"Current Balance", 
						   "OutstandingBets"=>"Outstanding Bets", 
						   "DepositAmount"=>"Deposit Amount", 
						   "BonusAmount"=>"Bonus Amount", 
						   "CashbackAmount"=>"Cashback Amount",   
						   "BonusDeduct"=>"Bonus Deduct",  
						   "WinningsDeduct"=>"Winnings Deduct",
						   "StatusName"=>"Status", 
						   "CurrentStatusName"=>"Current Status", 
						   "Remarks"=>"Remarks",  
						   "CallOutcomeName"=>"Call Outcome", 
						   "CallResultName"=>"Call Result", 
						   "CallProblem"=>"Call Category" 
						   ); 
						   
		$force_str = array("Username, Remarks");
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "search_promotional_activities".'-'.date("Ymdhis").".xls"; 
		$title = "Search Promotional Activities";
		
		//load our new PHPExcel library
		$this->load->library('excel'); 
		 
		//activate worksheet number 1
		$activeSheet = $this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$activeSheet->setTitle($title);
		  
		$headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => '8DB4E2'),
										'font'=> array('bold'=>true)
									   ), 
							 'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 )), 
							
							); 
								  
		$reportStyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'f4ec12'),
										'font'=> array('bold'=>true)));  
										   
		$normalStyle = array('alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        )
							  );
							  
							  
		$y = 'A';
		$start = 1;
		 
		foreach($excel_data as $row=>$val){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,$val);
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
			$y++; 
		}//end foreach
		
		$ctr = $start + 1;
		$category_code = "";  
		$count_result = 0;   
	 
		$end_cell = '';
		$start_cell = 'A'.$ctr;  
		foreach($activities as $row=>$activity){    
			$x = 'A'; 
			//set format
			$activity->DateAdded = date("F d, Y H:i:s", strtotime($activity->DateAdded));  
			$activity->DateUpdated = date("F d, Y H:i:s", strtotime($activity->DateUpdated)); 
			$activity->SearchDateUpdatedInt = date("F d, Y H:i:s", $activity->SearchDateUpdatedInt);    
			$activity->DateLastUpdated = date("F d, Y H:i:s", strtotime($activity->DateLastUpdated));
			//$activity->Amount = number_format($activity->Amount, 2);
			  
		 
			$activity->Important = ($activity->Important==1)?"YES":"NO";
			$activity->IsComplaint = ($activity->IsComplaint==1)?"YES":"NO";
			
			$activity->CallStart = ($activity->CallStart!="0000-00-00 00:00:00")?date("F d, Y H:i:s", strtotime($activity->CallStart)):""; 
			$activity->CallEnd = ($activity->CallEnd!="0000-00-00 00:00:00")?date("F d, Y H:i:s", strtotime($activity->CallEnd)):""; 
			$activity->CallSendSMS = ($activity->CallSendSMS==1)?"YES":"NO";
			$activity->CallSendEmail = ($activity->CallSendEmail==1)?"YES":"NO";
			$activity->CallProblem = ucwords(str_replace("_", " ", $activity->CallProblem));  
			 
			$activity->Remarks = strip_symbols($activity->Remarks); 
			
			//$activity->CallDuration = gmdate("H:i:s", $activity->CallDuration);  
			
			foreach($excel_data as $index=>$field){ 
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				
				//$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle);  
				 
				$end_cell = $x.$ctr;
				$x++;   
			}
			
			$ctr++;
			 
		}//end foreach 
		
		$activeSheet->getStyle($start_cell.':'.$end_cell)->applyFromArray($normalStyle); 
		   
		//count reports	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Activities(s)"); 
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->setCellValue('B'.($ctr+2), count($activities));
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle);  
	
		//set auto width
		$x='A';
		$col = 0; 
		foreach($excel_data as $row=>$data){
			$activeSheet->getColumnDimension($x)->setAutoSize(true);     
			$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
			$x++;	
		}
		 
		
		//REMOVE COMMENT IF WANT TO DOWNLOAD DIRECTLY
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$file_name.'"');
		//header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
		
		//$objWriter->save('php://output'); 
		$filePath = $this->common->temp_file;
		$objWriter->save($filePath.$file_name);  
		$return = (file_exists($filePath.$file_name))?array("success"=>1, "message"=>"Downloading file.", "download_link"=>encode_string($filePath.$file_name)):array("success"=>0, "message"=>"Error downloading file.", "download_link"=>""); 
		echo json_encode($return); 
		
	}//end export activities
	 
	 
	 
	 
	 
}

/* End of file promotions.php */
/* Location: ./application/controllers/promotions.php */