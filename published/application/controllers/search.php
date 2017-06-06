<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Search extends MY_Controller {

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
		$this->load->model("banks_model","banks");  
		$this->load->model("common_model","common"); 
	}
 	
	public function index()
	{   
	 	
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$skeywords = trim($this->uri->segment(2));  
		
		$params = str_replace("amp;", "", decode_string($this->uri->segment(2)));    
		 
		parse_str($params, $sdata);  
		$skeywords = ($sdata[s_dashboard] == 1)?"":$skeywords;					
		$data2 = array("main_page"=>"search", 
					   //"view_statuslist"=>$view_statuslist, 
					   //"activities"=>$activities, 
					   //"pagination"=>create_pagination($pagination_options), 
					   "currencies"=>$this->common->getCurrency_(), 
					   //"sources"=>$this->common->getSource_(), 
					   //"types"=>$this->banks->getBankCategory_(),
					   "status_list"=>$this->common->getStatusList_(),//1 for bank page
					   "s_page"=>$page, 
					   "skeywords"=>$skeywords, 
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')),//user types
					   "sdata"=>$sdata, 
					   "date_index"=>$this->common->date_index
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Search", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('common/search_activities_tpl');
		$this->load->view('footer');   
	}
	
	public function results()
	{ 	
		$skeywords = trim($this->uri->segment(2));
		$this->index($skeywords);
	}
	
	public function getSearch($actual=0)
	{
		$data = $this->input->post();
		//$view_statuslist = array();  
		//$result = $this->common->getUserStatusViews(1);//1-bank
		//$view_statuslist = explode(',',$result->StatusList); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		 
		$search_string .= ""; 
		$search_string2 = "";
		
		$allow_close = 0;  
		$activity = "";
		if(trim($data[s_important])==1)
		 {
			$search_string .= " AND (a.Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$$data[s_important];  
			$allow_close++;
		 }
		
		if(trim($data[s_iscomplaint])==1)
		 {
			$search_string .= " AND (a.IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);  
			$allow_close++;
		 }
		 
		/*if(trim($data[s_esupportid]))
		 {
			$search_string .= " AND (a.ESupportID LIKE '%".$this->common->escapeString_(trim($data[s_esupportid]))."%') "; 
			$search_url .= "&s_esupportid=".trim($data[s_esupportid]); 
			$allow_close++;
		 }*/
		 
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (a.Currency='".$this->common->escapeString_(trim($data[s_currency]))."') "; 
			$search_url .= "&s_currency=".trim($data[s_currency]); 
			$allow_close++;
		 }
		else
		 {     
			//$search_string .= " AND FIND_IN_SET(a.Currency, '{$this->session->userdata(mb_currencies)}') "; 
			$search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)}) "; 
		 }
		   
		if(trim($data[s_concern]) != "")
		 {
			$search_string2 .= " AND (results.Concern LIKE '%".trim($data[s_concern])."%') ";  
			$search_url .= "&s_concern=".trim($data[s_concern]); 
			$allow_close++;
		 }  
		
		if(trim($data[s_activity]) != "")
		 { 
		 	$activity = trim($data[s_activity]); 
			$search_string2 .= " AND (results.Activity ='".trim($data[s_activity])."') ";  
			$search_url .= "&s_activity=".trim($data[s_activity]);  
			$allow_close++;
		 }    
		 
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (a.Username LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_username=".trim($data[s_username]); 
			$allow_close++;
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
		 	if($data[s_dateindex] == 'uploaded')
			 {
				$search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "SearchAllUploadedKey";  
				$order_by = "DateUploadedInt";
			 }
			elseif($data[s_dateindex] == 'added')
			 {
				$search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "SearchAllAddedKey";   
				$order_by = "DateAddedInt";
			 }
			elseif($data[s_dateindex] == 'updated')
			 {
				$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "SearchAllUpdatedKey";  
				$order_by = "DateUpdatedInt"; 
			 }
			else
			 {
				$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "SearchAllUpdatedKey";    
				$order_by = "DateUpdatedInt";
			 }
			
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]))."&s_dateindex=".urlencode(trim($data[s_dateindex]));   
		 } 
		else
		 { 
			 if($data[s_dateindex] == 'uploaded')
			 {   
				$index = "SearchAllUploadedKey";  
				$order_by = "DateUploadedInt";
			 }
			elseif($data[s_dateindex] == 'added')
			 { 
				$index = "SearchAllAddedKey";   
				$order_by = "DateAddedInt";
			 }
			elseif($data[s_dateindex] == 'updated')
			 {   
				$index = "SearchAllUpdatedKey";   
				$order_by = "DateUpdatedInt";
			 }
			else
			 {  
				$index = $this->common->group_assigned_index; //$data[s_dateindex];  
				$order_by = "DateAddedInt"; 
			 }
		 } 
	 
		if(trim($data[s_status]) != "")
		 {
			$search_string .= " AND (a.Status='".trim($data[s_status])."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
			$allow_close++;
		 } 
		 
		if(trim($data[s_assignee]) != "")
		 {
			$search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";  
			$search_url .= "&s_assignee=".trim($data[s_assignee]); 
			$allow_close++;
		 }  
		 
		if(trim($data[s_agent]) != "")
		 {
			$search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";  
			$search_url .= "&s_agent=".trim($data[s_agent]); 
			$allow_close++;
		 }  
 
		if(trim($data[s_dashboard]))
		 { 
			/*$search_string .= " AND ((a.Status<>{$this->common->ids[close_status]}) AND (a.Status<>{$this->common->ids[crm_note_status]}) 
									  AND (a.Status<>{$this->common->ids[deposited_status]}) 
									)"; */
			$search_string .= " AND (a.Status NOT IN({$this->common->notcount_status}))  ";     
			if(!super_admin())
			 {
				//$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') )";    
			 }
			   
		 }
		else
		 {
			if(restriction_type() && ($allow_view == 0) )
			 {   
				//$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )"; 
			 } 
		 }
		 
		 $search_string = trim(trim($search_string), "AND"); 
		 $search_string2 = trim(trim($search_string2), "AND"); 
		//$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string;
		 
		$per_page = 20; 
		//$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countSearchAll_($search_string, $search_string2, trim(urldecode($data[t_search])), $data[s_dashboard], $index, $activity)->CountActivity; 
		//$activities = $this->common->getSearchAll_($search_string, $search_string2, trim(urldecode($data[t_search]), $index), $paging=array("limit"=>$per_page, "page"=>$page), $data[s_dashboard], $index, $activity); 
		
		
		$return = $activities = $this->common->getSearchAll_($search_string, $search_string2, trim(urldecode($data[t_search]), $index), $paging=array("limit"=>$per_page, "page"=>$page), $data[s_dashboard], $index, $activity, $order_by); 
		$total_rows = $return[total_rows]; 
		$activities = $return[result];
		
		
		$pagination_options = array("link"=>"",//base_url()."banks/activities", 
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
	
	public function getActivityMethods()
	{
		$type = $this->input->post('type'); 
		$x = $this->banks->getActivityMethods_($type);   
		
		echo  json_encode($x); 
	} 
	  
	public function generateHtmlList($activities)
	{
		$return = ""; 
		/*<!--<div class=\"red tip\" title=\"Date added\" >".date("Y-m-d H:i:s", strtotime($activity->DateAdded))."</div>-->
		<div class=\"green tip\" title=\"Date last updated\" >".date("Y-m-d H:i:s", strtotime($activity->DateUpdated))."</div>*/
		if(count($activities))
		 {  
			foreach($activities as $row=>$activity){ 
				$is_important = ($activity->Important==1)?" act-danger ":"";   
				$can_edit = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy==$this->session->userdata('mb_no')) )?1:0;
				$amount = ($activity->Activity=="banks")?number_format($activity->Amount, 2, '.', ','):""; 
				$return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" >  
							<td class=\"center\" >{$activity->Abbreviation}</td> 
							<td class=\"center\" >{$activity->Activity}</td>
							<td >".ucfirst($activity->Concern)."</td>
							<td class=\"center {$is_important}\" >{$activity->Username}</td>  
							<td class=\"center\" >{$activity->TransactionID}</td>
							<td class=\"right\" >{$amount}</td>
							<td class=\"center green\" >".ucwords($activity->StatusName)."</td> 
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<!--<td class=\"center\" >{$activity->UpdatedByNickname}</td>-->
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity=\"{$activity->Activity}\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}{$activity->Activity}\"  data-toggle=\"modal\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a> 
							"; 
				
				//check if usertype allowed to edit activity
				if($can_edit > 0)$return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity=\"{$activity->Activity}\"  activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
				
				/*if($activity->CountAttach > 0)$return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0\" ></i></a>";*/
							
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
	
	
	public function getDepositMethods()
	{
		$currency = $this->input->post('currency'); 
		$x = $this->banks->getDepositMethods_($currency);   
		
		echo  json_encode($x); 
	}  
	
	
	public function popupManageActivity()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$activity_id = trim($this->uri->segment(3));
		
		$conditions_array = array('ActivityID =' => $activity_id); 
		$activity = ($activity_id)?$this->banks->getActivity_("csa_bank_activities", $conditions_array):""; 
		
		$type_where_arr = array("Status ="=>1, "GroupID <>"=>$this->common->ids['super_admin_id']);
		
		$data2 = array("main_page"=>"banks",  
					   "currencies"=>$this->common->getCurrency_(), 
					   "sources"=>$this->common->getSource_(), 
					   "types"=>$this->banks->getBankCategory_(),
					   "status_list"=>$this->common->getStatusList_(1),//1 for bank page, 
					   "assignees"=>$this->common->getAssignees_($this->session->userdata('mb_usertype'), $activity->GroupAssignee),
					   "activity"=>$activity,
					   "s_page"=>$page, 
					   "activity_id"=>$activity_id, 
					   "settings_ids"=>$this->common->ids
					   //"attachments"=>$this->common->displayUploaded_(array("ActivityID ="=>$activity_id, "Activity ="=>"deposit_withdrawal", "Status ="=>'1'))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Bank Activity - ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('banks/bank_popup_tpl',$data); 
		 
	} 
	
	
	public function manageActivity()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		$data = $this->input->post();   
		$activity_type = "deposit_withdrawal"; 
		$data['act_important'] = ($data['act_important'] == "")?0:$data['act_important'];   
		$data['act_iscomplaint'] = ($data['act_iscomplaint'] == "")?0:$data['act_iscomplaint'];   
		$data['act_amount'] = clean_currency($data['act_amount']); 
		
		$error = ""; 
		
		if($data[act_assignee] == "")
		 {
			 $error .= "Select group assignee!<br> ";
		 }
		  
		if($data[act_currency] == "")
		 {
			 $error .= "Select currency!<br> ";
		 }
		 
		if($data[act_username] == "")
		 {
			 $error .= "Enter username!<br> ";
		 }
		
		if($data[act_source] == "")
		 {
			 $error .= "Select source!<br> ";
		 }
		
		if($data[act_methodtype] == "")
		 {
			 $error .= "Select method type!<br> ";
		 }
		
		if($data[act_method] == "")
		 {
			 $error .= "Select method!<br> ";
		 }
		
		if($data[act_amount] == "")
		 {
			 $error .= "Enter amount!<br> ";
		 }
		  
		if($data[act_status] === "")
		 {
			 $error .= "Select status!<br> ";
		 }
		
		if($data[act_idreceived] == "")
		 {
			 $error .= "Please verify if ID received or not!<br> ";
		 }
		         
		if($data[act_remarks] == "")
		 {
			 $error .= "Enter remarks!<br> ";
		 }
		  
		if (isset($_FILES['act_attachfile']) && !empty($_FILES['act_attachfile']['name'][0]))
		 {  
			 $config=array("input_file"=>"act_attachfile",
			 			   "upload_path"=>"deposit_withdrawal/"
			 			  );
			 
			 $upload = upload_file($config);   
			 //print_r($upload['upload_data']); 
			 $upload_data = array();  
			 if($upload['success'] <= 0)
			  {
				 $error .= strip_tags($upload['error']);
			  }
			 else
			  {
				  $upload_data = $upload['upload_data'];   
			  } 
			  
		 } 
		 
		  
		if($error)
		 {
			 	
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 {
			
			$balance = str_replace(',','', $data[account_balance]);  
			$action = ($this->input->post('hidden_action')=="add")?"add":"update";
			$current_date = date("Y-m-d H:i:s");
			 
			if($action == "add")
			 {	 
			 	 
				$post_data = array(  
				  	'Username'=>$data['act_username'],
					'Currency'=>$data['act_currency'],
					'ESupportID'=>$data['act_esupportid'],
					'Source'=>$data['act_source'],   
					'Category'=>$data['act_methodtype'],    
					'CategoryID'=>$data['act_method'],
					'TransactionID'=>$data['act_transactionid'],
					'DepositMethodID'=>$data['act_depmethod'],
					'IdReceived'=>$data['act_idreceived'],
					'Amount'=>$data['act_amount'],
					'AddedBy'=>$this->session->userdata("mb_no"),
					'DateAdded'=>$current_date,
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date,
					'DateAddedInt'=>strtotime($current_date),
					'DateUpdatedInt'=>strtotime($current_date), 
					'ClientIP'=>$this->input->ip_address(), 
					'Remarks'=>$data['act_remarks'], 
					'Important'=>$data['act_important'], 
					'IsComplaint'=>$data['act_iscomplaint'], 
					'AttachFilename'=>'',//$data['act_newfile'], 
					'OldFilename'=>'',//$data['act_oldfile'], 
					'AnalysisReason'=>$data['act_reason'],
					'ReasonSpecify'=>$data['act_reasonspecify'],
					'GroupAssignee'=>$data['act_assignee'],
					'Status'=>$data['act_status']  
				);     
				$last_id = $this->banks->manageActivity_("csa_bank_activities", $post_data, $action, '', '');   
				if($last_id > 0)
				 { 
					 //save attacment; 
					 $attach = $this->saveAttachment($upload_data, array("last_id"=>$last_id, "current_date"=>$current_date, "activity_type"=>$activity_type, "caption"=>""), "csa_attach_file");
					 
					 $return = array("success"=>1, "message"=>"Bank activity added successfully.", "upload_data"=>json_encode($upload_data), "is_change"=>1);   
					 
					 $history_data = array( 
					  'ActivityID'=>$last_id,       
					  'Activity'=>"deposit_withdrawal",
					  'Status'=>$data['act_status'], 
					  'Remarks'=>$data['act_remarks'],
					  'UpdatedBy'=>$this->session->userdata("mb_no"), 
					  'Important'=>$data['act_important'], 
					  'IsComplaint '=>$data['act_iscomplaint'], 
					  'DateUpdated'=>$current_date, 
					  'DateUpdatedInt'=>strtotime($current_date), 
					  'GroupAssignee'=>$data['act_assignee']
					);   
					
					$y = $this->banks->manageActivity_("csa_activities_history", $history_data, "add", '', '');  
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding activity!");
				 }
				
			 }
			else
			 {   
				 $post_data = array(  
				  	'Username'=>$data['act_username'],
					'Currency'=>$data['act_currency'],
					'ESupportID'=>$data['act_esupportid'],
					'Source'=>$data['act_source'],   
					'Category'=>$data['act_methodtype'],    
					'CategoryID'=>$data['act_method'],
					'TransactionID'=>$data['act_transactionid'],
					'DepositMethodID'=>$data['act_depmethod'],
					'IdReceived'=>$data['act_idreceived'],
					'Amount'=>$data['act_amount'], 
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date, 
					'DateUpdatedInt'=>strtotime($current_date),  
					'Important'=>$data['act_important'], 
					'IsComplaint'=>$data['act_iscomplaint'],   
					'AnalysisReason'=>$data['act_reason'],
					'ReasonSpecify'=>$data['act_reasonspecify'], 
					'GroupAssignee'=>$data['act_assignee'],
					'Status'=>$data['act_status']  
				);
				   		
				 $old = $this->banks->getActivityById_($this->input->post("hidden_activityid"));	
				 
				 $new_imptxt = ($data['act_important'] == 1)?"Important":"Not Important";
				 $old_imptxt = ($old->Important == 1)?"Important":"Not Important"; 
				
				 $new_idreceived_txt = ($data['act_idreceived'] == 1)?"Yes":"No";
				 $old_idreceived_txt = ($old->IdReceived==1)?"Yes":"No"; 
				
				 $new_iscomplainttxt = ($data['act_iscomplaint'] == 1)?"Complain":"Not Complain";
				 $old_iscomplainttxt = ($old->IsComplaint == 1)?"Complain":"Not Complain";
				 
				 $data['act_reason'] = ($data['act_reason']=="")?0:$data['act_reason']; 
				 $old->ReasonName = ($old->ReasonNamE)?$old->ReasonName:"---------";
				 
				 $changes = ""; 
				 $changes .= ($data['act_assignee'] != $old->GroupAssignee)?"Group Assignee changed to ".$data['hidden_aassignee']." from ".$old->GroupAssigneeName."|||":"";   
				 $changes .= ($data['act_username'] != $old->Username)?"Username changed to ".$data['act_username']." from ".$old->Username."|||":"";  
				 $changes .= ($data['act_currency'] != $old->Currency)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->CurrencyName."|||":"";  
				 $changes .= ($data['act_esupportid'] != $old->ESupportID)?"E-Support ID changed to ".$data['act_esupportid']." from ".$old->ESupportID."|||":"";   
				 $changes .= ($data['act_source'] != $old->Source)?"Source changed to ".$data['hidden_asource']." from ".$old->ActivitySource."|||":"";  
				 $changes .= ($data['act_methodtype'] != $old->Category)?"Method Type changed to ".$data['act_methodtype']." from ".$old->Category."|||":"";   
				 $changes .= ($data['act_method'] != $old->CategoryID)?"Method changed to ".$data['hidden_amethod']." from ".$old->Method."|||":""; 
				 $changes .= ($data['act_transactionid'] != $old->TransactionID)?"Transaction ID changed to ".$data['act_transactionid']." from ".$old->TransactionID."|||":"";
				 $changes .= (($data['act_depmethod'] != $old->DepositMethodID) && $data['act_depmethod']!='')?"Deposit Method changed to ".$data['hidden_adepmethod']." from ".$old->DepositMethodName."|||":"";
				 $changes .= ($data['act_idreceived'] != $old->IdReceived)?"ID Received changed to ".$new_idreceived_txt." from ".$old_idreceived_txt."|||":"";   
				 $changes .= ($data['act_amount'] != $old->Amount)?"Amount changed to ".$data['act_amount']." from ".$old->Amount."|||":"";   
				 $changes .= ($data['act_important'] != $old->Important)?"Activity changed to ".$new_imptxt." from ".$old_imptxt."|||":"";  
				 $changes .= ($data['act_iscomplaint'] != $old->IsComplaint)?"Activity changed to ".$new_iscomplainttxt." from ".$old_iscomplainttxt."|||":"";  
				 $changes .= (($data['act_reason'] != $old->AnalysisReason) )?"Analysis Reason changed to ".$data['hidden_areason']." from ".$old->ReasonName."|||":"";   
				 $changes .= ($data['act_reasonspecify'] != $old->ReasonSpecify)?"Analysis Reason Specify changed to ".$data['act_reasonspecify']." from ".$old->ReasonSpecify."|||":"";   
				 $main_updated = ($changes)?'1':'0';  
				 $changes .= ($data['act_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";    
				 
				 //add attachment to changes 
				 if(count($upload_data) > 0)
				  {
					 $attach_txt = (count($upload_data) > 1)?"Attached files: ":"Attached file: ";  
					 foreach ($upload_data as $key => $file) { 
						$attach_txt .= $file['client_name'].', ';  
					 }//end foreach 
					 $attach_txt = trim(trim($attach_txt), ",")."|||"; 
					 $changes .= $attach_txt; 
				  }
				  
				 $history_data = array( 
					  'ActivityID'=>$old->ActivityID,       
					  'Activity'=>"deposit_withdrawal",
					  'Status'=>$data['act_status'],  
					  'Changes'=>$changes,
					  'UpdatedBy'=>$this->session->userdata("mb_no"), 
					  'Important'=>$data['act_important'], 
					  'IsComplaint'=>$data['act_iscomplaint'], 
					  'MainUpdated'=>$main_updated, 
					  'DateUpdated'=>$current_date, 
					  'DateUpdatedInt'=>strtotime($current_date), 
					  'GroupAssignee'=>$data['act_assignee'],
					);
				 
				 if($data['act_remarks'] != $old->Remarks)
				  {
					//$changes .= ($data['act_remarks'] != $old->Remarks)?"Remarks changed to ".$data['act_remarks']." from ".$old->Remarks."|||":"";	 
					$changes .= "Remarks changed to ".$data['act_remarks']." from ".$old->Remarks."|||";	  
					
					$post_data['Remarks'] =  trim($data['act_remarks']);
					$history_data['Remarks'] =  trim($data['act_remarks']); 
				  }
				  
				 //save attacment; 
				 $attach = $this->saveAttachment($upload_data, array("last_id"=>$old->ActivityID, "current_date"=>$current_date, "activity_type"=>$activity_type, "caption"=>""), "csa_attach_file");
				 
				 $uploaded_id = ($attach > 0)?$old->ActivityID:""; 
				 	 	   
				 if($changes != "")
				  {
					$x = $this->banks->manageActivity_("csa_bank_activities", $post_data, $action, "ActivityID", $this->input->post("hidden_activityid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Bank activity updated successfully.", "uploaded_id"=>$uploaded_id, "is_change"=>1);    
						$y = $this->banks->manageActivity_("csa_activities_history", $history_data, "add", '', '');  
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating activity!");
					 } 
				  }
				 else
				  {
					
					//no changes   
					$message = ($attach > 0)?"File uploaded successfully.":"No changes made.";
					$return = array("success"=>1, "message"=>$message, "uploaded_id"=>$uploaded_id);
				  }
				 
				 
			 }//end else UPDATE
			  
		 }//end else NO ERROR
		 
		 echo json_encode($return);
		
	 } 
	 
	 
	//POPUP CHANGE STATUS
	public function popupManageStatusActivity()
	{    
		
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!admin_access() && !view_access() ) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$activity_id = trim($this->uri->segment(3));
		
		if(!$activity_id || $activity_id == "") 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 }
		 
		
		$conditions_array = array('ActivityID =' => $activity_id); 
		$activity = ($activity_id)?$this->banks->getActivity_("csa_bank_activities", $conditions_array):""; 
		
		$data2 = array("main_page"=>"banks",     
					   "status_list"=>$this->common->getStatusList_(1),//1 for bank page
					   "activity"=>$activity, 
					   "activity_id"=>$activity_id 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Bank Activity Status", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('banks/bank_status_popup_tpl',$data); 
		 
	} 
	
	
	public function manageActivityStatus()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		$data = $this->input->post();   
		$activity_type = "deposit_withdrawal"; 
		 
		$error = "";   
		 
		if($data[act_assignee] === "")
		 {
			 $error .= "Select assignee!<br> ";
		 }
		 
		if($data[act_status] === "")
		 {
			 $error .= "Select status!<br> ";
		 }
		 
		if($data[act_remarks] == "")
		 {
			 $error .= "Enter remarks!<br> ";
		 }
		 
		 
		  
		if($error)
		 {
			 	
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 { 
			$action = ($this->input->post('hidden_action')=="add")?"add":"update";
			$current_date = date("Y-m-d H:i:s");
			 
			if($action == "add")
			 {	 
			 	  
			 }
			else
			 {   
				 $post_data = array(      
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date, 
					'DateUpdatedInt'=>strtotime($current_date),  
					'Remarks'=>$data['act_remarks'], 
					'GroupAssignee'=>$data['act_assignee'],   
					'Status'=>$data['act_status'],   
				);
				 	
				 $old = $this->banks->getActivityById_($this->input->post("hidden_activityid"));	
				  
				 $changes = "";
				 $changes .= ($data['act_assignee'] != $old->GroupAssignee)?"Group Assignee changed to ".$data['hidden_aassignee']." from ".$old->GroupAssigneeName."|||":"";    
				 $changes .= ($data['act_remarks'] != $old->Remarks)?"Remarks changed to ".$data['act_remarks']." from ".$old->Remarks."|||":"";    
				 $changes .= ($data['act_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";    
				  
				 $history_data = array( 
					  'ActivityID'=>$old->ActivityID,       
					  'Activity'=>"deposit_withdrawal",
					  'Status'=>$data['act_status'], 
					  'Remarks'=>$data['act_remarks'], 
					  'Changes'=>$changes,
					  'UpdatedBy'=>$this->session->userdata("mb_no"),  
					  'DateUpdated'=>$current_date, 
					  'DateUpdatedInt'=>strtotime($current_date), 
					  'GroupAssignee'=>$data['act_assignee'], 
					);
				     
				 if($changes != "")
				  {
					$x = $this->banks->manageActivity_("csa_bank_activities", $post_data, $action, "ActivityID", $this->input->post("hidden_activityid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Bank activity updated successfully.", "is_change"=>1);    
						$y = $this->banks->manageActivity_("csa_activities_history", $history_data, "add", '', '');  
					 }
					else
					 { 
						$return = array("success"=>0, "message"=>"Error updating activity!");
					 } 
				  }
				 else
				  {
					$return = array("success"=>1, "message"=>"No changes made.");
				  }
				 
				 
			 }//end else UPDATE
			  
		 }//end else NO ERROR
		 
		 echo json_encode($return);
		
	} 
	
	
	//POPUP CHANGE STATUS
	public function viewActivityDetails()
	{    
		
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!admin_access() && !view_access() ) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$activity_id = trim($this->uri->segment(3));
		$view_only = trim($this->uri->segment(4));
		 
		if(!$activity_id || $activity_id == "") 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		$conditions_array = array('ActivityID =' => $activity_id); 
		$activity = $this->banks->getActivityById_($activity_id);  
		
		if(count($activity) <= 0)
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false;  
		 } 
		 
		 
		$data2 = array("main_page"=>"banks",     
					   "status_list"=>$this->common->getStatusList_(1),//1 for bank page
					   "activity"=>$activity, 
					   "activity_id"=>$activity_id, 
					   "attachments"=>$this->common->displayUploaded_(array("ActivityID ="=>$activity_id, "Activity ="=>$this->input->post('activity'), "Status ="=>'1')), 
					   "histories"=>$this->common->getHistoryRemarks(array("a.ActivityID ="=>$activity_id, "a.Activity ="=>"deposit_withdrawal" )), 
					   "view_only"=>$view_only, 
					   "assignees"=>$this->common->getAssignees_($this->session->userdata('mb_usertype'), $activity->GroupAssignee),
					   "settings_ids"=>$this->common->ids
					   
					   
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Bank Activity Details", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('banks/bank_activity_details_popup_tpl',$data); 
		 
	}
	 
	  
	
	public function exportActivities($actual=0)
	 {	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post();
		//$view_statuslist = array();  
		//$result = $this->common->getUserStatusViews(1);//1-banks
		//$view_statuslist = explode(',',$result->StatusList); 
		 
		 
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		
		$search_string .= " (a.ActivityID <> 0) "; 
		$search_string2 = " (results.ActivityID<>0) ";
		
		$allow_close = 0; 
		 
		
		if(trim($data[s_important])==1)
		 {
			$search_string .= " AND (a.Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$$data[s_important];  
			$allow_close++;
		 }
		
		if(trim($data[s_iscomplaint])==1)
		 {
			$search_string .= " AND (a.IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);  
			$allow_close++;
		 }
		 
		if(trim($data[s_esupportid]))
		 {
			$search_string .= " AND (a.ESupportID LIKE '%".$this->common->escapeString_(trim($data[s_esupportid]))."%') "; 
			$search_url .= "&s_esupportid=".trim($data[s_esupportid]); 
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
			$search_string .= " AND FIND_IN_SET(a.Currency, '{$this->session->userdata(mb_currencies)}') ";  
		 }
		 
 
		if(trim($data[s_concern]) != "")
		 {
			$search_string2 .= " AND (results.Concern LIKE '%".trim($data[s_concern])."%') ";  
			$search_url .= "&s_concern=".trim($data[s_concern]); 
			$allow_close++;
		 }  
		
		if(trim($data[s_activity]) != "")
		 {
			$search_string2 .= " AND (results.Activity ='".trim($data[s_activity])."') ";  
			$search_url .= "&s_activity=".trim($data[s_activity]); 
			$allow_close++;
		 }   
		 
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (a.Username LIKE '%".$this->common->escapeString_(trim($data[s_username]))."%') "; 
			$search_url .= "&s_username=".trim($data[s_username]); 
			$allow_close++;
		 } 
		   
		/*if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  "; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));   
		 } */
		
		if($s_fromdate && $s_todate)
		 {  
		 	if($data[s_dateindex] == 'uploaded')
			 {
				$search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "DateUploadedInt"; 
			 }
			elseif($data[s_dateindex] == 'added')
			 {
				$search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "DateAddedInt";  
			 }
			elseif($data[s_dateindex] == 'updated')
			 {
				$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "DateUpdatedInt";  
			 }
			else
			 {
				$search_string .= " AND (a.{$this->common->date_index} BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = $this->common->date_index;   
			 }
			
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]))."&s_dateindex=".urlencode(trim($data[s_dateindex]));   
		 } 
		else
		 {
			 $index = $this->common->date_index; 
		 } 
		 
		if(trim($data[s_status]) != "")
		 {
			$search_string .= " AND (a.Status='".trim($data[s_status])."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
			$allow_close++;
		 } 
		 
		if(trim($data[s_assignee]) != "")
		 {
			$search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";  
			$search_url .= "&s_assignee=".trim($data[s_assignee]); 
			$allow_close++;
		 }  
		 
		if(trim($data[s_agent]) != "")
		 {
			$search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";  
			$search_url .= "&s_agent=".trim($data[s_agent]); 
			$allow_close++;
		 }  
		
		if(trim($data[s_dashboard]))
		 { 
			/*$search_string .= " AND ((a.Status<>{$this->common->ids[close_status]}) AND (a.Status<>{$this->common->ids[crm_note_status]}) 
									  AND (a.Status<>{$this->common->ids[deposited_status]}) 
									)"; */
			$search_string .= " AND (a.Status NOT IN({$this->common->notcount_status}))  ";     
			if(!super_admin())
			 {
				//$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') )";    
			 }
			   
		 }
		else
		 {
			if(restriction_type() && ($allow_view == 0) )
			 {   
				//$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )"; 
			 } 
		 }  
	 
		//$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string;
		
		$per_page = 20; 
 
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countSearchActivities_($search_string, "csa_bank_activities")->CountActivity;  
		$activities = $this->common->getSearchAll_($search_string, $search_string2, trim(urldecode($data[t_search])), $paging=array("limit"=>$per_page, "page"=>$page), $index);
		
	 	$total_rows = count($activities);    
					   
		$excel_data = array("DateAdded"=>"Date Added", 
							"DateUpdated"=>"Date Updated", 
							"Abbreviation"=>"Currency", 
							"Username"=>"Username",  
							"Activity"=>"Activity",  
							"Concern"=>"Concern", 
							"ESupportID"=>"E-Support ID",   
							"StatusName"=>"Status",  
							"GroupAssigneeName"=>"Assignee"
						);
		
		$force_str = array("Username");
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "search_all_activities".'-'.date("Ymdhis").".xls"; 
		$title = "Bank Activities";
		
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
		
		foreach($activities as $row=>$activity){   
			$x = 'A'; 
			//set format
			$activity->DateAdded = date("F d, Y H:i:s", strtotime($activity->DateAdded));  
			$activity->DateUpdated = date("F d, Y H:i:s", strtotime($activity->DateUpdated)); 
			//$activity->Amount = number_format($activity->Amount, 2); 
		 
			//$activity->Important = ($activity->Important==1)?"YES":"NO";
			//$activity->IsComplaint = ($activity->IsComplaint==1)?"YES":"NO";
			//$activity->IdReceived = ($activity->IdReceived==1)?"YES":"NO";
			
			//$activity->ReasonName = ($activity->ReasonSpecify)?$activity->ReasonName."(".$activity->ReasonSpecify.")":$activity->ReasonName;  
			$activity->Concern = ucfirst($activity->Concern);
			 
			foreach($excel_data as $index=>$field){ 
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				
				$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle); 
				$x++;   
			}
			$ctr++;
			 
		}//end foreach 
		
		   
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
	
	
	public function getAnalysisReasons()
	{
		$where_arr = array();
		$data = $this->input->post();  
		
		if($data[type]) $where_arr[Type] = $data[type];
		
		$results = $this->banks->getAnalysisReasonsById_($where_arr, "a.ReasonID, a.ReasonName, a.IsSpecify, a.CategoryID, b.Name AS CategoryName");   
		 
		$reasons = array(); 
		foreach($results as $row=>$reason)
		 {  
			 if (array_key_exists($reason->CategoryID, $reasons))
			 {
				 $reasons[$reason->CategoryID][values][] = $reason;  
			 }
			else
			 {
				 $reasons[$reason->CategoryID][category_name] = $reason->CategoryName;  
				 $reasons[$reason->CategoryID][values][] = $reason;   
			 }
			  
		 }  
		 
		 foreach ($reasons as $reason) {
			 $return[] = $reason;
		 }
		 
		 
		echo  json_encode($return); 
	} 
	 
	 
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */