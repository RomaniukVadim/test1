<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotions_Uploaded extends MY_Controller {

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
		$this->import_types = array('csv');
		
		$this->upload_assignee = array(1,10);
	}
 	
	public function index()
	{   
	 
		if(!admin_access() && !can_upload_promotions()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$categories_where = " AND a.Status='1' "; 
		
   		if(restriction_type() && !can_upload_promotions())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 }
		 
		//$activities = $this->getActivities($this->input->post());						
		$data2 = array("main_page"=>"promotions",  
					   "currencies"=>$this->common->getCurrency_(), 
					   //"sources"=>$this->common->getSource_(), 
					   "categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where),
					   "status_list"=>$this->common->getStatusList_(3),//3 for promotion page  
					   "outcomes"=>$this->promotions->getCallOutcomeList_(array("a.outcome_status ="=>'1')),
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')),//user types
					   "s_page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Uploaded Promotional Activities", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('promotions/promotions_uploaded_activities_tpl');
		$this->load->view('footer');   
	}
	
	public function uploadedActivities()
	{ 
		$this->index();
	}
	
	public function getUploadedActivities($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!admin_access() && !can_upload_promotions()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post();
		$view_statuslist = array();  
		$result = $this->common->getUserStatusViews(3);//3-promotions
		$view_statuslist = explode(',',$result->StatusList);  
		
		 
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
	 
		$search_string = "";  
		$allow_close = 0;
		   
		//set to default
		$data[s_isuploaded] = 1; 
		if(trim($data[s_isuploaded])==1)
		 {
			$search_string .= " AND (a.IsUpload='".$this->common->escapeString_($data[s_isuploaded])."') "; 
			$search_url .= "&s_isuploaded=".$data[s_isuploaded];  
			$allow_close++;
		 }
		   
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (a.Currency='".$this->common->escapeString_(trim($data[s_currency]))."') "; 
			$search_url .= "&s_currency=".trim($data[s_currency]); 
			$allow_close++;
		 }
		else
		 {   
			$search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)})";  
		 }
		
		if(trim($data[s_assignee]) != "")
		 {
			$search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";  
			$search_url .= "&s_assignee=".trim($data[s_assignee]); 
			$allow_close++; 
			$allow_view++; 
		 } 
		    
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (a.Username LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_username=".trim($data[s_username]); 
			$allow_close++;
		 } 
		  
		if(trim($data[s_promotion]))
		 {
			if(trim($data[s_promotion]) == "N/A")
			 {
				 //$search_string .= " AND (h.Name LIKE '%".$this->common->escapeString_($data[s_promotion])."%') "; 
			 }
			else
			 {
				$search_string .= " AND (a.Promotion='".$this->common->escapeString_($data[s_promotion])."') ";  
			 }
			  
			//$search_string .= " AND (a.Promotion='".$this->common->escapeString_($data[s_promotion])."') "; 
			$search_url .= "&s_promotion=".trim($data[s_promotion]); 
			$allow_close++; 
			$allow_view++; 
		 }
		
		if(trim($data[s_uploadid]))
		 {
			$search_string .= " AND (a.UploadID='".$this->common->escapeString_(trim($data[s_uploadid]))."') "; 
			$search_url .= "&s_uploadid=".trim($data[s_uploadid]); 
			$allow_close++;
		 }  
		 
		  
		if(trim($data[s_source]))
		 {
			$search_string .= " AND (a.Source='".$this->common->escapeString_(trim($data[s_source]))."') "; 
			$search_url .= "&s_source=".trim($data[s_source]); 
			$allow_close++;
		 }
		 
		if(trim($data[s_problem]))
		 {
			$search_string .= " AND (a.Problem='".$this->common->escapeString_(trim($data[s_problem]))."') "; 
			$search_url .= "&s_problem=".trim($data[s_problem]); 
			$allow_close++;
		 }
		  
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  "; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));   
		 	$index = "PromotionUploadedKey";    
			$order_by = "a.DateUploadedInt";
		 } 
		else
		 {
			$index = "PromotionUploadedKey";   
			$order_by = "a.DateUploadedInt";		 
		 }
		 
		if(trim($data[s_status]) != "")
		 {
			$search_string .= " AND (a.Status='".trim($data[s_status])."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
			$allow_close++;
		 }
		else
		 {
			if(restriction_type() && !can_upload_promotions())
			 {     
				$search_string .= " AND FIND_IN_SET(a.Status, '".implode(',', $view_statuslist)."') ";   
				$search_url .= "&s_status=".trim($data[s_status]);	 
			 }
		 } 
		
		if(trim($data[s_assignee]) != "")
		 {
			$search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";  
			$search_url .= "&s_assignee=".trim($data[s_assignee]); 
			$allow_close++;
		 }
		else
		 {
			if(restriction_type() && !can_upload_promotions())
			 {     
				$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )";   
				$search_url .= "&s_assignee=".trim($data[s_assignee]);	 
			 }
		 } 
		 
		if(trim($data[s_agent]) != "")
		 {
			$search_string .= " AND (a.UpdatedBy='".$this->common->escapeString_($data[s_agent])."') ";  
			$search_url .= "&s_agent=".trim($data[s_agent]); 
			$allow_close++;
		 }  
	 	  
		//$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string; 
		
		$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status NOT IN ({$this->common->hide_status}) ) ":$search_string;
		$search_string = trim(trim($search_string), "AND");
		
		$per_page = 20;  
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countSearchActivities_($search_string, "csa_promotion_activities", "", "DateUploadedInt")->CountActivity; 
		//$activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), "DateUploadedInt");
		
		$return = $activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index, $order_by); 
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
				$status_checkbox = ($activity->Status <= 0)?"<input type=\"checkbox\" value=\"{$activity->ActivityID}\" name=\"check_activity[]\"  />":"";
				$return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" > 
							<td class=\"center\" >{$status_checkbox}</td>
							<td class=\"center\" >{$activity->Currency}</td>
							<td class=\"center {$is_important}\" >{$activity->Username}</td>
							<td >{$activity->PromotionName}</td>
							<td >{$activity->UploadID}</td>
							<td class=\"center\" >".strtolower($activity->UploadedUser)."</td>
							<td class=\"center\" >
								<div class=\"tip act-danger\" title=\"Date added\" >".date("Y-m-d H:i:s", strtotime($activity->DateAdded))."</div>
							</td>
							<td class=\"center\" >
								<div class=\"tip green\" title=\"Date last updated\" >".date("Y-m-d H:i:s", strtotime($activity->DateAdded))."</div>
							</td> 
							<td class=\"center\" >".ucwords($activity->StatusName)."</td>
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
							<td class=\"center\" colspan=\"20\" >No activity found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	          
	
	public function deleteUploadedPromotions($actual=0)
	{
		 if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!admin_access() && !can_upload_promotions()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		   
		$data = $this->input->post(); 
		
		$check = array(); 
		$xx = "";
		foreach($this->input->post("check_activity") as $selected){
			if($selected) array_push($check, $selected);   
		}
		$check_str = implode(',', $check);  
		
		
		if(count($check)<=0 || $check_str=="")
		 {
			 $error .= "Please select activity to delete!<br> ";
		 }  
		
		//COPY DELETED  
		/*$search_string = "";  
		$allow_close = 0; 
		//set to default
		$data[s_isuploaded] = 1; 
		if(trim($data[s_isuploaded])==1)
		 {
			$search_string .= " AND (a.IsUpload='".$this->common->escapeString_($data[s_isuploaded])."') "; 
			$search_url .= "&s_isuploaded=".$data[s_isuploaded];  
			$allow_close++;
		 }*/ 
		//END COPY DELETED 
		
		
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 { 
		 	//GET Activities
			$con_arr = array("a.ActivityID IN({$check_str}) !="=>0,
							 "a.Status <="=>0
							);
			$activities = $this->promotions->getActivitiesPure($con_arr); 
			$current_date = date("Y-m-d H:i:s");  
			
			if(count($activities) > 0)
			 {
				$rows = array();   
				 
				foreach($activities as $row => $activity)
				 {
					/*$record = array();  
					foreach($activity as $index => $value)
					 {
					 	echo $index.':'.$value;
						$record[$index] = $value; 
					 }*/  
					  
					/*$record = (array) $activity;  
					$record[DeletedBy] = $this->session->userdata("mb_no"); 
					$record[DateDeletedInt] = strtotime($current_date);  
					$rows[] = $record;*/  
					 
					$rows[] = array("ActivityID"=>$activity->ActivityID,
									  "Username"=>$activity->Username,
									  "Currency"=>$activity->Currency,
									  "Promotion"=>$activity->Promotion,
									  "Product"=>$activity->Product,
									  "Category"=>$activity->Category,
									  "MinimumAmount"=>$activity->MinimumAmount,
									  "MaximumAmount"=>$activity->MaximumAmount,
									  "TurnOver"=>$activity->TurnOver,
									  "BonusRate"=>$activity->BonusRate,
									  "Formula"=>$activity->Formula,
									  "WageringFormula"=>$activity->WageringFormula,
									  "AddedBy"=>$activity->AddedBy,
									  "DateAdded"=>$activity->DateAdded,
									  "UpdatedBy"=>$activity->UpdatedBy,
									  "DateUpdated"=>$activity->DateUpdated,
									  "DateAddedInt"=>$activity->DateAddedInt,
									  "DateUpdatedInt"=>$activity->DateUpdatedInt,
									  "UserIP"=>$this->input->ip_address(),
									  "IsUpload"=>$activity->IsUpload,
									  "UploadID"=>$activity->UploadID,
									  "DateUploaded"=>$activity->DateUploaded,
									  "DateUploadedInt"=>$activity->DateUploadedInt,
									  "UploadedBy"=>$activity->UploadedBy,
									  "ActualDateUploadedInt"=>$activity->ActualDateUploadedInt,
									  "PromotionStartDate"=>$activity->PromotionStartDate,
									  "PromotionEndDate"=>$activity->PromotionEndDate,
									  "GroupAssignee"=>$activity->GroupAssignee,
									  "Remarks"=>$activity->Remarks,
									  "DeletedBy"=> $this->session->userdata("mb_no"),
									  "DateDeletedInt"=>strtotime($current_date)
									  ); 
									    
					 			  
				 }//end foreach 
				 	
				 $count_rec = $this->common->batchInsert_("csa_deleted_activities", $rows);
				 if($count_rec > 0)
				  {
					 $x = $this->common->deleteRecords_("csa_promotion_activities", $check_str, "ActivityID", array("Status <="=>0));	  
					 $return = ($x == true)?array("success"=>1, "message"=>"Activities deleted successfully."):array("success"=>0, "message"=>"Error deleting activities. Please check!");
				  }
				 else
				  {
					  $return = array("success"=>0, "message"=>"Deleted activities not backup"); 
				  }
				   
			 }
			else
			 {
				$return = array("success"=>0, "message"=>"No activity found"); 
			 } 
			 
		 }
		
		
		echo json_encode($return);
		 
		
	}
	
	
	public function popupUploadPromotions()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access()  && !can_upload_promotions()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		if(restriction_type() && !can_upload_promotions())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 } 
		 
		
		  
		//for promotions
		if(count($activity) > 0)
		 {
			$where_arr = array("a.Status ="=>'1', 
							   "a.ProductID"=>$activity->Product, 
							   "a.CurrencyID"=>$activity->Currency 
							  );  
			$where_or = array("a.PromotionID"=>$activity->Promotion);  
		 } 
		 //$promotions = $this->promotions->getChangePromotionById_($where_arr, $where_or);   
		 //end for promotions
		
		
		
		$data2 = array("main_page"=>"promotions",  
					   "currencies"=>$this->common->getCurrency_(),  
					   "status_list"=>$this->common->getStatusList_(3),//3 promotion 
					   "products"=>$this->common->getProductsList_(array("a.Status ="=>1)),
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')), //user types
					   "categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where), 
					   "upload_assignee"=>$this->upload_assignee
					   //"promotions"=>$promotions  
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Upload Promotions ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('promotions/promotions_uploaded_popup_tpl',$data); 
		 
	} 
	
	
	public function uploadPromotionalActivities()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_upload_promotions()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s");
		
		$error =  ""; 
		$filename = $_FILES['act_attachfile']['name']; 
		$ext = pathinfo($filename, PATHINFO_EXTENSION); 
		$upload_id = $this->session->userdata("mb_no").'-'.uniqid();
		
		if (!isset($_FILES['act_attachfile']) && empty($_FILES['act_attachfile']['name']))
		 {  
			 $error .= "Please select file to upload!<br> "; 
		 } 
		 
		if(!in_array($ext,$this->import_types) ) 
		{
			$error .= "Please select a valid file type!<br> ";
		}  
		
		if(trim($data[act_uploadeddatadate]))
		 {
			$error .= "Select call date!<br> ";
		 }
		 
		if($data[act_currency] == "")
		 {
			 $error .= "Select currency!<br> ";
		 }
		
		/*if($data[act_product] == "")
		 {
			 $error .= "Select product!<br> ";
		 } */
		
		if($data[act_category] == "")
		 {
			 $error .= "Select category!<br> ";
		 } 
		 
		if($data[act_promotion] == "")
		 {
			 $error .= "Select promotion!<br> ";
		 } 
		 
		if($data[act_assignee] == "")
		 {
			 $error .= "Select assignee!<br> ";
		 }  
		
		$promotion = $this->promotions->getChangePromotionById_(array("a.PromotionID ="=>$data[act_promotion]));
		//$category = $this->promotions->getPromotionCategoryById_(array("a.CategoryID ="=>$data[act_category])); 
		 
		if(count($promotion) <= 0)
		 {
			$rror .= "Promotion not found!<br> ";   
		 }
		 
		 
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 {    
			 $rows = array();   
			 //Import uploaded file to Database
			 //INSERT INTO table (a,b) VALUES (1,2), (2,3), (3,4);
			 $handle = fopen($_FILES['act_attachfile']['tmp_name'], "r"); 
			 $x = 0;  
			 while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) { 
				$username = strtolower(trim($record[0])); 
				$remarks = trim($record[1]); 
				 
				if($username && $username!="")
				 { 
					$rows[$x] = array("Username"=>trim($username), 
									  "Currency"=>trim($data[act_currency]), 
									  "Promotion"=>trim($data[act_promotion]), 
									  "Product"=>trim($promotion[0]->ProductID), 
									  "Category"=>trim($data[act_category]), 
									  "MinimumAmount"=>trim($data[hidden_aminimum]),  
									  "MaximumAmount"=>trim($data[hidden_amaximum]),  
									  "TurnOver"=>trim($data[hidden_aturnover]), 
									  "BonusRate"=>trim($data[hidden_abonusrate]), 
									  "Formula"=>trim($data[hidden_aformula]), 
									  "WageringFormula"=>trim($data[hidden_awageringformula]), 
									  "AddedBy"=>$this->session->userdata("mb_no"),
									  "DateAdded"=>$current_date, 
									  "UpdatedBy"=>$this->session->userdata("mb_no"),
									  "DateUpdated"=>$current_date,
									  "DateAddedInt"=>strtotime($current_date), 
									  "DateUpdatedInt"=>strtotime($current_date), 
									  "UserIP"=>$this->input->ip_address(),
									  "IsUpload"=>'1', 
									  "UploadID"=>$upload_id,  
									  "DateUploaded"=>$current_date,
									  "DateUploadedInt"=>strtotime(trim($data[act_actualdateuploaded])." 00:00:01"),
									  "UploadedBy"=>$this->session->userdata("mb_no"),
									  "ActualDateUploadedInt"=>strtotime($current_date),  
									  "PromotionStartDate"=>trim($promotion[0]->StartedDate),
									  "PromotionEndDate"=>trim($promotion[0]->EndDate), 
									  "GroupAssignee"=>trim($data[act_assignee]),
									  "Remarks"=>$remarks
									  ); 
					$x++;   
					 
				 }  
				 
			}//end while
			 
			if(count($rows) > 0)
			 {
				$count_rec = $this->common->batchInsert_("csa_promotion_activities", $rows);
				$return = ($count_rec > 0)?array("success"=>1, "message"=>"File uploaded successfully. <br>Upload ID: <b>{$upload_id}</b><br>Total Uploaded: <b>{$count_rec}</b>", "records"=>$count_rec, "is_change"=>1):array("success"=>0, "message"=>"Error uploading promotions activities. Please check your uploaded file.! <br> "); 
			 }
			else
			 {
				 $return = array("success"=>0, "message"=>"No record to save. Please check your uploaded file.! <br> "); 
			 }
			 
		 }
		
		
		echo json_encode($return);
		 
		 
	} 
	
	
	 
}

/* End of file promotions.php */
/* Location: ./application/controllers/promotions.php */