<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends MY_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/dashboard

     * 	- or -  
     * 		http://example.com/index.php/dashboard/index
     * 	- or -
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/dashboard/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->model("accounts_model", "accounts");
        $this->load->model("common_model", "common");
        $this->activity_type = "account_issues";
    }

    public function index() {

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));
        parse_str($params, $sdata);

        //$activities = $this->getActivities($this->input->post());						
        $data2 = array("main_page" => "accounts",
            "currencies" => $this->common->getCurrency_(),
            "sources" => $this->common->getSource_(),
            "problems" => $this->accounts->getProblemById_(array("Status =" => '1')),
            "status_list" => $this->common->getStatusList_(4), //1 for bank page
            "utypes" => $this->common->getUsersGroup_(array("Status =" => '1')), //user types
            "s_page" => $page,
            "sdata" => $sdata,
            "date_index" => $this->common->date_index
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Account Related Issues",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('accounts/account_activities_tpl');
        $this->load->view('footer');
    }

    public function activities() {
        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));
        parse_str($params, $sdata);
        $this->index();
    }

    public function getActivities($actual=0, $action="json", $post_data=array()) {
        
		$data = ($action == "excel")?$post_data:$this->input->post();
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(4); //4-accounts
        $view_statuslist = explode(',', $result->StatusList);

        $s_fromdate = strtotime(trim($data[s_fromdate]));
        $s_todate = strtotime(trim($data[s_todate]));

        $search_string = "";
        $allow_close = 0;
        $allow_view = 0; 

        if (trim($data[s_idreceived]) != '') {
            $search_string .= " AND (a.IdReceived='" . $this->common->escapeString_($data[s_idreceived]) . "') ";
            $search_url .= "&s_idreceived=" . $$data[s_idreceived];
            $allow_close++;
        }

        if (trim($data[s_important]) == 1) {
            $search_string .= " AND (a.Important='" . $this->common->escapeString_($data[s_important]) . "') ";
            $search_url .= "&s_important=" . $$data[s_important];
            $allow_close++;
        }

        if (trim($data[s_iscomplaint]) == 1) {
            $search_string .= " AND (a.IsComplaint='" . $this->common->escapeString_(trim($data[s_iscomplaint])) . "') ";
            $search_url .= "&s_iscomplaint=" . trim($$data[s_iscomplaint]);
            $allow_close++;
        }

        if (trim($data[s_uploadpm]) == 1) {
            $search_string .= " AND (a.IsUploadPM='" . $this->common->escapeString_($data[s_uploadpm]) . "') ";
            $search_url .= "&s_uploadpm=" . $$data[s_uploadpm];
            $allow_close++;
        }
		
		if (trim($data[s_tofollowup]) == 1) {
            $search_string .= " AND (a.ToFollowup='" . $this->common->escapeString_($data[s_tofollowup]) . "') ";
            $search_url .= "&s_tofollowup=" . $$data[s_tofollowup];
            $allow_close++;
        }
		
        if (trim($data[s_esupportid])) {
            $search_string .= " AND (a.ESupportID LIKE '%" . $this->common->escapeString_(trim($data[s_esupportid])) . "%') ";
            $search_url .= "&s_esupportid=" . trim($data[s_esupportid]);
            $allow_close++;
        }

        if (trim($data[s_currency])) {
            $search_string .= " AND (a.Currency='" . $this->common->escapeString_(trim($data[s_currency])) . "') ";
            $search_url .= "&s_currency=" . trim($data[s_currency]);
            $allow_close++;
        } else {
            $search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)})"; 
        }

        if (trim($data[s_username])) {
            $search_string .= " AND (a.Username LIKE '%" . $this->common->escapeString_(trim($data[s_username])) . "%') ";
            $search_url .= "&s_username=" . trim($data[s_username]);
            $allow_close++;
            $allow_view++;
        }

        if (trim($data[s_source])) {
            $search_string .= " AND (a.Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        if (trim($data[s_accountproblem])) {
            $search_string .= " AND (a.AccountProblem='" . $this->common->escapeString_(trim($data[s_accountproblem])) . "') ";
            $search_url .= "&s_accountproblem=" . trim($data[s_accountproblem]);
            $allow_close++;
        }

        if (trim($data[s_method])) {
            $search_string .= " AND (a.CategoryId='" . $this->common->escapeString_(trim($data[s_method])) . "') ";
            $search_url .= "&s_method=" . trim($data[s_method]);
            $allow_close++;
        }

        if($s_fromdate && $s_todate)
		 {   
			if($data[s_dateindex] == 'added')
			 {
				$search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "ActivitiesAddedKey";   
				$order_by = "a.DateAddedInt"; 
			 }
			elseif($data[s_dateindex] == 'updated')
			 {
				$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "ActivitiesUpdatedKey";   
				$order_by = "a.DateUpdatedInt"; 
			 }
			else
			 {
				$search_string .= " AND (a.{$this->common->date_index} BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = $this->common->group_activities_index;    
				$order_by = "a.DateUpdatedInt"; 
			 }
			
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]))."&s_dateindex=".urlencode(trim($data[s_dateindex]));
			
		 } 
		else
		 {
			 if($data[s_dateindex] == 'added')
			 { 
				$index = "ActivitiesAddedKey";   
				$order_by = "a.DateAddedInt";
			 }
			elseif($data[s_dateindex] == 'updated')
			 {   
				$index = "ActivitiesUpdatedKey"; 
				$order_by = "a.DateUpdatedInt";  
			 }
			else
			 {  
				$index = $this->common->group_activities_index; //$data[s_dateindex];  
				$order_by = "a.DateUpdatedInt";   
			 }
		 }
		  
        if (trim($data[s_agent]) != "") {
            $search_string .= " AND (f.mb_nick LIKE '%" . $this->common->escapeString_($data[s_agent]) . "%') ";
            $search_url .= "&s_agent=" . trim($data[s_agent]);
            $allow_close++;
        }

        if (trim($data[s_assignee]) != "") {
            $search_string .= " AND (a.GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
            $allow_view++;
        }
		
		if(trim($data[s_warningdays])==1)
		 {     
			$allow_close = 0;  
			$data[s_displayclose] = 0; 
		 }
		 
        if(trim($data[s_dashboard]) && $allow_close == 0) { 
		
			if(trim($data[s_displayclose]) != '1') 
			 {
				 $search_string .= " AND (a.Status NOT IN({$this->common->notcount_status})) ";    
				 $hide_close = 1; 
			 }
			 
            if (!super_admin()) {
                $search_string .= " AND ((a.GroupAssignee='" . trim($this->session->userdata("mb_usertype")) . "') )";
            }
        } 
		else {
            if (restriction_type() && ($allow_view == 0)) {
                //$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )"; 
            }
        }
  		
		if (trim($data[s_status]) != "") {
            $search_string .= " AND (a.Status='" . trim($data[s_status]) . "') ";
            $search_url .= "&s_status=" . trim($data[s_status]);
            $allow_close++;
        } 
	   else 
		{   
			if(trim($data[s_displayclose]) != '1' && $hide_close != 1)
			 {
				$search_string .= " AND (a.Status NOT IN ({$this->common->hide_status}) )  ";  
				$search_url .= "&s_displayclose=".trim($data[s_displayclose]); 
			 }       
        }
		
		//$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status NOT IN ({$this->common->hide_status}) ) ":$search_string;
		$search_string = trim(trim($search_string), "AND");

        $per_page = 20;
        //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        $page = ($data['s_page']) ? $data['s_page'] : 0; 
		
		$paging = ($action == "excel")?array():$paging=array("limit"=>$per_page, "page"=>$page); //if excell export all without paging 
		$return = $activities = $this->accounts->getAccountActivities_($search_string, $view_statuslist, $paging, $index, $order_by);	  
		$total_rows = $return[total_rows]; 
		$activities = $return[result];

        $pagination_options = array("link" => "", //base_url()."banks/activities", 
            "total_rows" => $total_rows,
            "per_page" => $per_page,
            "cur_page" => $page
        );

        $of_str = (($page + 20) <= $total_rows) ? $page + 20 : $total_rows;
        $disp_page = ($page == 0) ? 1 : $page + 1;
        $plural_txt = ($total_rows > 1) ? "activities" : "activity";
        $pagination_string = ($total_rows > 0) ? "Showing " . $disp_page . " to " . $of_str . " of " . $total_rows . " " . $plural_txt : "";

        if($actual == 1)//
		 {  
		 	 $return = array("activities"=>$activities,   
						"records"=>$total_rows 
					   );
			 
			 if($action != "excel")
			  {
				  $return["pagination"] = create_pagination($pagination_options); 
				  $return["pagination_string"] = $pagination_string;
			  }
			  
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

    public function getActivityMethods() {
        $type = $this->input->post('type');
        $x = $this->banks->getActivityMethods_($type);

        echo json_encode($x);
    }

    public function generateHtmlList($activities) {
        /* <td class=\"center\" >
          <a href=\"#ActivityStatusModal\" title=\"change status\" alt=\"change status\" class=\"change_status\" activity-id=\"{$activity->ActivityID}\" id=\"Status{$activity->ActivityID}\" data-toggle=\"modal\" >
          ".ucwords($activity->StatusName)."
          </a>
          </td> */

        $return = "";
        if (count($activities)) {
            foreach ($activities as $row => $activity) {
                $is_important = ($activity->Important == 1) ? " act-danger " : "";
                $can_edit = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy == $this->session->userdata('mb_no')) ) ? 1 : 0;
				
				$days_diff = (!in_array($activity->Status, $this->common->days_notcount_status))?days_diff(date("Y-m-d", $activity->DateAddedInt)):days_diff(date("Y-m-d", $activity->DateAddedInt), date("Y-m-d", $activity->DateUpdatedInt));
			  	$days_class = (!in_array($activity->Status, $this->common->days_notcount_status) && ($days_diff >= $this->common->days_warning) )?"<i class='icon12 i-clock-6  orange'  ></i>":"";
				$days_txt = (!in_array($activity->Status, $this->common->days_notcount_status))?"Days in Process":"Days Processed";
				$days_diff_txt = ($days_diff > 0)?"<br>{$days_txt}: <span class='badge badge-info' >{$days_diff}</span>":"";
				
				$to_followup_class = ($activity->ToFollowup == '1')?"<i class='icon12 i-bubbles-4 green'  ></i>":"";
				
                $return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" data-toggle=\"popover\" data-placement=\"top\" data-content=\"Date Added : <span class='orange'>".date("M d, Y H:i", $activity->DateAddedInt)."</span><br>Last Updated : <span class='green'>".date("M d, Y H:i", $activity->DateUpdatedInt)."</span>{$days_diff_txt}\" data-original-title=\"<i class='icon16 i-info gap-left0' ></i> Other Info\" data-html=\"true\"   >  
							<td class=\"center\" >{$to_followup_class} {$days_class}{$activity->Currency}</td>
							<td class=\"center {$is_important}\" >{$activity->Username}</td>
							<td class=\"center\" >{$activity->ActivitySource}</td>
							<td class=\"center\" >" . ucwords($activity->ProblemName) . "</td>
							<td class=\"center\" >
								<!--<div class=\"red tip\" title=\"Date added\" >" . date("Y-m-d H:i:s", strtotime($activity->DateAdded)) . "</div>-->
								<div class=\"green tip\" title=\"Date last updated\" >" . date("Y-m-d H:i:s", strtotime($activity->DateUpdated)) . "</div>
							</td>
							<td class=\"center green\" >" . ucwords($activity->StatusName) . "</td>
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							";

                //check if usertype allowed to edit activity
                if ($can_edit > 0)
                    $return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";

                if ($activity->CountAttach > 0)
                    $return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0\" ></i></a>";

                $return .= "
							</td>
						</tr> ";
            }//end foreach
        }
        else {
            $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"9\" >No activity found!</td>
						</tr>
			 			";
        }

        return $return;
    }

    public function getDepositMethods() {
        $currency = $this->input->post('currency');
        $x = $this->banks->getDepositMethods_($currency);

        echo json_encode($x);
    }

    public function popupManageActivity() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $activity_id = trim($this->uri->segment(3));

        //$conditions_array = array('ActivityID =' => $activity_id); 
        //$activity = ($activity_id)?$this->accounts->getActivity_("csa_account_issues", $conditions_array):"";  

        $conditions_array = array('a.ActivityID =' => $activity_id);
        $activity = $this->accounts->getActivityById_($conditions_array);

        if ($activity <= 0 || $activity == "") {
            $user_id = trim($this->input->post('user12_id'));
            $default_user = ($user_id) ? $this->common->get12betUserById_(array("a.UserID =" => $user_id)) : array();
        }


        $data2 = array("main_page" => "accounts",
            "currencies" => $this->common->getCurrency_(),
            "sources" => $this->common->getSource_(),
            "problems" => $this->accounts->getProblemById_(array("a.Status =" => '1')),
            "status_list" => $this->common->getStatusList_(4), //4 for accouts page
            "activity" => $activity,
            "s_page" => $page,
            "activity_id" => $activity_id,
            "assignees" => $this->common->getAssignees_($this->session->userdata('mb_usertype'), $activity->GroupAssignee),
            "settings_ids" => $this->common->ids,
            "default_user" => $default_user,
            "id_types" => $this->common->getIdTypes_(array("a.Status =" => '1')),
            "countries" => $this->common->getCountries_()
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Manage Account Related Issue ",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('accounts/account_popup_tpl', $data);
    }

    public function manageActivity() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $data = $this->input->post();
        $data['act_important'] = ($data['act_important'] == "") ? 0 : $data['act_important'];
        $data['act_iscomplaint'] = ($data['act_iscomplaint'] == "") ? 0 : $data['act_iscomplaint'];
        $data['act_isuploadpm'] = ($data['act_isuploadpm'] == "") ? 0 : $data['act_isuploadpm'];
		$data['act_tofollowup'] = ($data['act_tofollowup'] == "") ? 0 : $data['act_tofollowup'];

        $data['act_blpbet'] = clean_currency($data['act_blpbet']);
        $data['act_blpmatch'] = clean_currency($data['act_blpmatch']);
        $data['act_plpbet'] = clean_currency($data['act_plpbet']);
        $data['act_plpmatch'] = clean_currency($data['act_plpmatch']);
        $data['act_rlpbet'] = clean_currency($data['act_rlpbet']);
        $data['act_rlpmatch'] = clean_currency($data['act_rlpmatch']);

        $data['act_currentlimit'] = clean_currency($data['act_currentlimit']);
        $data['act_proposedlimit'] = clean_currency($data['act_proposedlimit']);
        $data['act_revisedlimit'] = clean_currency($data['act_revisedlimit']);
		
		$data['act_idtype'] = ($data['act_idtype'] == "") ? 0 : $data['act_idtype'];
		$data['act_issuedcountry'] = ($data['act_issuedcountry'] == "") ? 0 : $data['act_issuedcountry']; 
		
		$data['act_category'] = ($data['act_category'] == "") ? 0 : $data['act_category'];
		$data['act_tgpamount'] = clean_currency($data['act_tgpamount']);
		 
        $error = "";

        if ($data[act_assignee] == "") {
            $error .= "Select assignee!<br> ";
        }

        if ($data[act_currency] == "") {
            $error .= "Select currency!<br> ";
        }

        if ($data[act_username] == "") {
            $error .= "Enter username!<br> ";
        }

        if ($data[act_source] == "") {
            $error .= "Select source!<br> ";
        }

        if ($data[act_accountproblem] == "") {
            $error .= "Select account problem!<br> ";
        } else {
            
        }


        if ($data[act_status] == "") {
            $error .= "Select status!<br> ";
        }

        if ($data[act_idreceived] == "") {
            $error .= "Please verify if ID received or not!<br> ";
        }

        if ($data[act_emailsent] == "") {
            $error .= "Please verify if email sent or not!<br> ";
        }

        if ($data[act_remarks] == "") {
            $error .= "Enter remarks!<br> ";
        }

        //uploading files  
        if (isset($_FILES['act_attachfile']) && !empty($_FILES['act_attachfile']['name'][0])) {
            $config = array("input_file" => "act_attachfile",
                "upload_path" => $this->activity_type . "/"
            );

            $upload = upload_file($config);
            $upload_data = array();
            if ($upload['success'] <= 0) {
                $error .= strip_tags($upload['error']);
            } else {
                $upload_data = $upload['upload_data'];
            }
        }
        //end uploading files 

        $data['act_regularizeamount'] = ($data['act_regularizeamount']) ? clean_currency($data['act_regularizeamount']) : "0.00";
        $data['act_currentbalance'] = ($data['act_currentbalance']) ? clean_currency($data['act_currentbalance']) : "0.00";

        if ($error) {
            $return = array("success" => 0, "message" => $error);
        } else {

            $balance = str_replace(',', '', $data[account_balance]);
            $action = ($this->input->post('hidden_action') == "add") ? "add" : "update";
            $current_date = date("Y-m-d H:i:s");

            if ($action == "add") {

                $post_data = array(
                    'Username' => trim($data['act_username']),
                    'Currency' => $data['act_currency'],
                    'ESupportID' => $data['act_esupportid'],
                    'Source' => $data['act_source'],
                    'AccountProblem' => $data['act_accountproblem'], 
					'ProblemCategory' => $data['act_category'],
                    'WithDocuments' => $data['act_withdocuments'],
					'TGPAmount' => $data['act_tgpamount'],
					'EmailSent' => $data['act_emailsent'],
                    'IdReceived' => $data['act_idreceived'],
                    'IDType' => $data['act_idtype'],
                    'IssuedCountry' => $data['act_issuedcountry'],
                    'BetLimitPerBet' => $data['act_blpbet'],
                    'BetLimitPerMatch' => $data['act_blpmatch'],
                    'ProposedLimitPerBet' => $data['act_plpbet'],
                    'ProposedLimitPerMatch' => $data['act_plpmatch'],
                    'RevisedLimitPerBet' => $data['act_rlpbet'],
                    'RevisedLimitPerMatch' => $data['act_rlpmatch'],
                    'RegularizeAmount' => $data['act_regularizeamount'],
                    'CurrentBalance' => $data['act_currentbalance'],
                    'CurrentLimit' => $data['act_currentlimit'],
                    'ProposedLimit' => $data['act_proposedlimit'],
                    'RevisedLimit' => $data['act_revisedlimit'],
                    'AddedBy' => $this->session->userdata("mb_no"),
                    'DateAdded' => $current_date,
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateAddedInt' => strtotime($current_date),
                    'DateUpdatedInt' => strtotime($current_date),
                    'UserIP' => $this->input->ip_address(),
                    'Remarks' => $data['act_remarks'], 
                    'IsUploadPM' => $data['act_isuploadpm'],
					'ToFollowup' => $data['act_tofollowup'],
                    'GroupAssignee' => $data['act_assignee'],
                    'Status' => $data['act_status']
                );
				
				if(admin_access() || csd_supervisor_access() || 1){
					$post_data['Important'] = $data['act_important'];
                    $post_data['IsComplaint'] = $data['act_iscomplaint'];   
				}
				
                if ($data[act_idreceived] == 1) {
                    $post_data[IDType] = trim($data['act_idtype']);
                    $post_data[IssuedCountry] = trim($data['act_issuedcountry']);
                }

                $last_id = $this->accounts->manageActivity_("csa_account_issues", $post_data, $action, '', '');
                if ($last_id > 0) {
                    //save attacment; 
                    $attach = $this->saveAttachment($upload_data, array("last_id" => $last_id, "current_date" => $current_date, "activity_type" => $this->activity_type, "caption" => ""), "csa_attach_file");

                    $return = array("success" => 1, "message" => "Account related issue added successfully.", "upload_data" => json_encode($upload_data), "is_change" => 1);

                    $history_data = array(
                        'ActivityID' => $last_id,
                        'Activity' => $this->activity_type,
                        'Status' => $data['act_status'],
                        'Remarks' => $data['act_remarks'],
                        'UpdatedBy' => $this->session->userdata("mb_no"),
                        'Important' => $data['act_important'],
                        'IsComplaint ' => $data['act_iscomplaint'],
                        'DateUpdated' => $current_date,
                        'DateUpdatedInt' => strtotime($current_date),
                        'GroupAssignee' => $data['act_assignee'],
                    );

                    $y = $this->accounts->manageActivity_("csa_activities_history", $history_data, "add", '', '');
                } else {
                    $return = array("success" => 0, "message" => "Error adding activity!");
                }
            } else {

                $post_data = array(
                    'Username' => trim($data['act_username']),
                    'Currency' => $data['act_currency'],
                    'ESupportID' => $data['act_esupportid'],
                    'Source' => $data['act_source'],
                    'AccountProblem' => $data['act_accountproblem'], 
					'ProblemCategory' => $data['act_category'], 
					'WithDocuments' => $data['act_withdocuments'], 
					'TGPAmount' => $data['act_tgpamount'],
                    'EmailSent' => $data['act_emailsent'],
                    'IdReceived' => $data['act_idreceived'],
                    'IDType' => $data['act_idtype'],
                    'IssuedCountry' => $data['act_issuedcountry'],
                    'BetLimitPerBet' => $data['act_blpbet'],
                    'BetLimitPerMatch' => $data['act_blpmatch'],
                    'ProposedLimitPerBet' => $data['act_plpbet'],
                    'ProposedLimitPerMatch' => $data['act_plpmatch'],
                    'RevisedLimitPerBet' => $data['act_rlpbet'],
                    'RevisedLimitPerMatch' => $data['act_rlpmatch'],
                    'RegularizeAmount' => $data['act_regularizeamount'],
                    'CurrentBalance' => $data['act_currentbalance'],
                    'CurrentLimit' => $data['act_currentlimit'],
                    'ProposedLimit' => $data['act_proposedlimit'],
                    'RevisedLimit' => $data['act_revisedlimit'],
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'Remarks' => $data['act_remarks'],  
                    'IsUploadPM' => $data['act_isuploadpm'], 
					'ToFollowup' => $data['act_tofollowup'],
                    'GroupAssignee' => $data['act_assignee'],
                    'Status' => $data['act_status']
                );
				
				if(admin_access() || csd_supervisor_access() || 1){
					$post_data['Important'] = $data['act_important'];
                    $post_data['IsComplaint'] = $data['act_iscomplaint'];   
				}

                /* if($data[act_idreceived] == 1)
                  {
                  $post_data[IDType] = trim($data['act_idtype']);
                  $post_data[IssuedCountry] = trim($data['act_issuedcountry']);
                  } */

                $old = $this->accounts->getActivityById_(array("a.ActivityID =" => $data["hidden_activityid"]));

                $new_imptxt = ($data['act_important'] == 1) ? "Important" : "Not Important";
                $old_imptxt = ($old->Important == 1) ? "Important" : "Not Important";

                $new_idreceived_txt = ($data['act_idreceived'] == 1) ? "Yes" : "No";
                $old_idreceived_txt = ($old->IdReceived == 1) ? "Yes" : "No";

                $new_emailsent_txt = ($data['act_emailsent'] == 1) ? "Yes" : "No";
                $old_emailsent_txt = ($old->EmailSent == 1) ? "Yes" : "No";

                $new_iscomplainttxt = ($data['act_iscomplaint'] == 1) ? "Complain" : "Not Complain";
                $old_iscomplainttxt = ($old->IsComplaint == 1) ? "Complain" : "Not Complain";

                $new_isuploadpm_txt = ($data['act_isuploadpm'] == 1) ? "Yes" : "No";
                $old_isuploadpm_txt = ($old->IsUploadPM == 1) ? "Yes" : "No";
				
				$new_tofollowup_txt = ($data['act_tofollowup'] == 1) ? "Yes" : "No";
                $old_tofollowup_txt = ($old->ToFollowup == 1) ? "Yes" : "No";
				
				$old_problem_category_name = ($old->ProblemCategory > 0) ? $old->ProblemCategoryName : "";
				$new_withdocuments_txt = ($data['act_withdocuments'] == 1) ? "Yes" : "No";
                $old_withdocuments_txt = ($old->WithDocuments == 1) ? "Yes" : "No";


                $changes = "";
                $changes .= ($data['act_assignee'] != $old->GroupAssignee) ? "Group Assignee changed to " . $data['hidden_aassignee'] . " from " . $old->GroupAssigneeName . "|||" : "";
                $changes .= ($data['act_username'] != $old->Username) ? "Username changed to " . $data['act_username'] . " from " . $old->Username . "|||" : "";
                $changes .= ($data['act_currency'] != $old->Currency) ? "Currency changed to " . $data['hidden_acurrency'] . " from " . $old->CurrencyName . "|||" : "";
                $changes .= ($data['act_esupportid'] != $old->ESupportID) ? "E-Support ID changed to " . $data['act_esupportid'] . " from " . $old->ESupportID . "|||" : "";
                $changes .= ($data['act_source'] != $old->Source) ? "Source changed to " . $data['hidden_asource'] . " from " . $old->ActivitySource . "|||" : "";
                $changes .= ($data['act_accountproblem'] != $old->AccountProblem) ? "Account Problem changed to " . $data['hidden_aaccountproblem'] . " from " . $old->ProblemName . "|||" : "";  
				$changes .= ($data['act_category'] != $old->ProblemCategory) ? "Account Problem Category changed to " . $data['hidden_acategory'] . " from " . $old_problem_category_name . "|||" : "";
				$changes .= ($data['act_withdocuments'] != $old->WithDocuments) ? "With Documents changed to " . $new_withdocuments_txt . " from " . $old_withdocuments_txt . "|||" : "";
				$changes .= ($data['act_tgpamount'] != $old->TGPAmount) ? "TGP Amount changed to " . $data['act_tgpamount'] . " from " . $old->TGPAmount . "|||" : "";
				 			
                $changes .= ($data['act_idreceived'] != $old->IdReceived) ? "ID Received changed to " . $new_idreceived_txt . " from " . $old_idreceived_txt . "|||" : "";
                $changes .= ($data['act_emailsent'] != $old->EmailSent) ? "Email Sent changed to " . $new_emailsent_txt . " from " . $old_emailsent_txt . "|||" : "";

                $changes .= ($data['act_idtype'] != $old->IDType) ? "ID Type changed to " . $data['hidden_aidtype'] . " from " . $old->IDTypeName . "|||" : "";
                $changes .= ($data['act_issuedcountry'] != $old->IssuedCountry) ? "Issued Country changed to " . $data['hidden_aissuedcountry'] . " from " . $old->CountryName . "|||" : "";
 
                $changes .= ($data['act_product'] != $old->Product && ($old->Product != 0)) ? "Product changed to " . $data['hidden_aproduct'] . " from " . $old->ProductName . "|||" : "";
                $changes .= ($data['act_amount'] != $old->Amount) ? "Amount changed to " . $data['act_amount'] . " from " . $old->Amount . "|||" : "";
                
				if(admin_access() || csd_supervisor_access()){
					$changes .= ($data['act_important'] != $old->Important) ? "Activity changed to " . $new_imptxt . " from " . $old_imptxt . "|||" : "";
                	$changes .= ($data['act_iscomplaint'] != $old->IsComplaint) ? "Activity changed to " . $new_iscomplainttxt . " from " . $old_iscomplainttxt . "|||" : "";
				}
				 
				$changes .= ($data['act_isuploadpm'] != $old->IsUploadPM) ? "Updated No. changed to " . $new_isuploadpm_txt . " from " . $old_isuploadpm_txt . "|||" : "";
				$changes .= ($data['act_tofollowup'] != $old->ToFollowup) ? "To Follow Up changed to " . $new_tofollowup_txt . " from " . $old_tofollowup_txt . "|||" : "";

                $changes .= ($data['act_blpbet'] != $old->BetLimitPerBet) ? "Bet Limit Per Bet changed to " . $data['act_blpbet'] . " from " . $old->BetLimitPerBet . "|||" : "";
                $changes .= ($data['act_blpmatch'] != $old->BetLimitPerMatch) ? "Bet Limit Per Match changed to " . $data['act_blpmatch'] . " from " . $old->BetLimitPerMatch . "|||" : "";
                $changes .= ($data['act_plpbet'] != $old->ProposedLimitPerBet) ? "Proposed Limit Per Bet changed to " . $data['act_plpbet'] . " from " . $old->ProposedLimitPerBet . "|||" : "";
                $changes .= ($data['act_plpmatch'] != $old->ProposedLimitPerMatch) ? "Proposed Limit Per Match changed to " . $data['act_plpmatch'] . " from " . $old->ProposedLimitPerMatch . "|||" : "";
                $changes .= ($data['act_rlpbet'] != $old->RevisedLimitPerBet) ? "Revised Limit Per Bet changed to " . $data['act_rlpbet'] . " from " . $old->RevisedLimitPerBet . "|||" : "";
                $changes .= ($data['act_rlpmatch'] != $old->RevisedLimitPerMatch) ? "Revised Limit Per Match changed to " . $data['act_rlpmatch'] . " from " . $old->RevisedLimitPerMatch . "|||" : "";
                $changes .= ($data['act_regularizeamount'] != $old->RegularizeAmount) ? "Regularize Amount changed to " . $data['act_regularizeamount'] . " from " . $old->RegularizeAmount . "|||" : "";
                $changes .= ($data['act_currentbalance'] != $old->CurrentBalance) ? "Current Balance changed to " . $data['act_currentbalance'] . " from " . $old->CurrentBalance . "|||" : "";

                $changes .= ($data['act_currentlimit'] != $old->CurrentLimit) ? "Current Limit changed to " . $data['act_currentlimit'] . " from " . $old->CurrentLimit . "|||" : "";
                $changes .= ($data['act_proposedlimit'] != $old->ProposedLimit) ? "Proposed Limit changed to " . $data['act_proposedlimit'] . " from " . $old->ProposedLimit . "|||" : "";
                $changes .= ($data['act_revisedlimit'] != $old->RevisedLimit) ? "Revised Limit changed to " . $data['act_revisedlimit'] . " from " . $old->RevisedLimit . "|||" : "";

                //add attachment to changes 
                if (count($upload_data) > 0) {
                    $attach_txt = (count($upload_data) > 1) ? "Attached files: " : "Attached file: ";
                    foreach ($upload_data as $key => $file) {
                        $attach_txt .= $file['client_name'] . ', ';
                    }//end foreach 
                    $attach_txt = trim(trim($attach_txt), ",") . "|||";
                    $changes .= $attach_txt;
                }

                $main_updated = ($changes) ? '1' : '0';

                $changes .= ($data['act_status'] != $old->Status) ? "Status changed to " . $data['hidden_astatus'] . " from " . $old->StatusName . "|||" : "";

                $history_data = array(
                    'ActivityID' => $old->ActivityID,
                    'Activity' => $this->activity_type,
                    'Status' => $data['act_status'],
                    'Changes' => $changes,
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'Important' => $data['act_important'],
                    'IsComplaint' => $data['act_iscomplaint'],
                    'MainUpdated' => $main_updated,
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'GroupAssignee' => $data['act_assignee']
                );

                if ($data['act_remarks']) {
                    //$changes .= ($data['act_remarks'] != $old->Remarks)?"Remarks changed to ".$data['act_remarks']." from ".$old->Remarks."|||":"";	 
                    $changes .= "Remarks changed to " . trim($data['act_remarks']) . " from " . trim($old->Remarks) . "|||";
                    $post_data['Remarks'] = trim($data['act_remarks']);
                    $history_data['Remarks'] = trim($data['act_remarks']);
                }

                //save attacment; 
                $attach = $this->saveAttachment($upload_data, array("last_id" => $old->ActivityID, "current_date" => $current_date, "activity_type" => $this->activity_type, "caption" => ""), "csa_attach_file");

                $uploaded_id = ($attach > 0) ? $old->ActivityID : "";

                if ($changes != "") {
                    $x = $this->accounts->manageActivity_("csa_account_issues", $post_data, $action, "ActivityID", $this->input->post("hidden_activityid"));
                    if ($x > 0) {
                        $return = array("success" => 1, "message" => "Account related issue updated successfully.", "uploaded_id" => $uploaded_id, "is_change" => 1);
                        $y = $this->accounts->manageActivity_("csa_activities_history", $history_data, "add", '', '');
                    } else {
                        array("success" => 0, "message" => "Error updating activity!");
                    }
                } else {

                    //no changes   
                    $message = ($attach > 0) ? "File uploaded successfully." : "No changes made.";
                    $return = array("success" => 1, "message" => $message, "uploaded_id" => $uploaded_id);
                }
            }//end else UPDATE
            //ADD TO 12BET USERS 
            $user12bet_insert = insert_12bet_user($post_data, $this->activity_type);
        }//end else NO ERROR

        echo json_encode($return);
    }

    //POPUP CHANGE STATUS
    public function popupManageStatusActivity() {

        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $activity_id = trim($this->uri->segment(3));

        if (!$activity_id || $activity_id == "") {
            error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
            return false;
        }

        $conditions_array = array('a.ActivityID =' => $activity_id);
        $activity = ($activity_id) ? $this->accounts->getActivityById_($conditions_array) : "";

        $data2 = array("main_page" => "accounts",
            "status_list" => $this->common->getStatusList_(4), //1 for bank page
            "activity" => $activity,
            "activity_id" => $activity_id
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Account Related Issue Update Status",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('accounts/account_status_popup_tpl', $data);
    }

    public function manageActivityStatus() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        $data = $this->input->post();
        $error = "";

        if ($data[act_assignee] == "") {
            $error .= "Select assignee!<br> ";
        }

        if ($data[act_status] == "") {
            $error .= "Select status!<br> ";
        }

        if ($data[act_remarks] == "") {
            $error .= "Enter remarks!<br> ";
        }

        if ($error) {

            $return = array("success" => 0, "message" => $error);
        } else {
            $action = ($this->input->post('hidden_action') == "add") ? "add" : "update";
            $current_date = date("Y-m-d H:i:s");

            if ($action == "add") {
                
            } else {
                $post_data = array(
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'Remarks' => $data['act_remarks'],
                    'GroupAssignee' => $data['act_assignee'],
                    'Status' => $data['act_status']
                );

                $old = $this->accounts->getActivityById_(array("a.ActivityID =" => $this->input->post("hidden_activityid")));

                $changes = "";
                $changes .= ($data['act_assignee'] != $old->GroupAssignee) ? "Group Assignee changed to " . $data['hidden_aassignee'] . " from " . $old->GroupAssigneeName . "|||" : "";
                $changes .= ($data['act_remarks']) ? "Remarks changed to " . $data['act_remarks'] . " from " . $old->Remarks . "|||" : "";
                $changes .= ($data['act_status'] != $old->Status) ? "Status changed to " . $data['hidden_astatus'] . " from " . $old->StatusName . "|||" : "";

                $history_data = array(
                    'ActivityID' => $old->ActivityID,
                    'Activity' => $this->activity_type,
                    'Status' => $data['act_status'],
                    'Remarks' => $data['act_remarks'],
                    'Changes' => $changes,
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'GroupAssignee' => $data['act_assignee'],
                );

                if ($changes != "") {
                    $x = $this->accounts->manageActivity_("csa_account_issues", $post_data, $action, "ActivityID", $this->input->post("hidden_activityid"));
                    if ($x > 0) {
                        $return = array("success" => 1, "message" => "Account related issue updated successfully.", "is_change" => 1);
                        $y = $this->accounts->manageActivity_("csa_activities_history", $history_data, "add", '', '');
                    } else {
                        $return = array("success" => 0, "message" => "Error updating account related issue!");
                    }
                } else {
                    //no changes    
                    $return = array("success" => 1, "message" => "No changes made.");
                }
            }//end else UPDATE
        }//end else NO ERROR

        echo json_encode($return);
    }

    //POPUP CHANGE STATUS
    public function viewActivityDetails() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $activity_id = trim($this->uri->segment(3));
        $view_only = trim($this->uri->segment(4));

        if (!$activity_id || $activity_id == "") {
            error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
            return false;
        }

        $conditions_array = array('a.ActivityID =' => $activity_id);
        $activity = $this->accounts->getActivityById_($conditions_array);

        if (count($activity) <= 0) {
            error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
            return false;
        } else {
            $view_only = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy == $this->session->userdata('mb_no')) ) ? 0 : 1;
        }

        $data2 = array("main_page" => "accounts",
            "status_list" => $this->common->getStatusList_(4), //1 for bank page
            "activity" => $activity,
            "activity_id" => $activity_id,
            "attachments" => $this->common->displayUploaded_(array("ActivityID =" => $activity_id, "Activity =" => $this->activity_type, "Status =" => '1')),
            "histories" => $this->common->getHistoryRemarks(array("a.ActivityID =" => $activity_id, "a.Activity =" => $this->activity_type)),
            "view_only" => $view_only,
            "assignees" => $this->common->getAssignees_($this->session->userdata('mb_usertype'), $activity->GroupAssignee),
            "settings_ids" => $this->common->ids
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Account Related Issue Details",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('accounts/account_activity_details_popup_tpl', $data);
    }

    public function saveAttachment($upload_data, $custom = array(), $table = "csa_attach_file") {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        $batch_x = 0;
        $x = 0;
        if ($upload_data && (count($upload_data) > 0)) {
            foreach ($upload_data as $key => $file) {
                $attach[$x] = array(
                    'Activity' => $custom['activity_type'],
                    'ActivityID' => $custom['last_id'],
                    'Caption' => $custom['caption'],
                    'Path' => $custom['activity_type'] . '/' . $file['orig_name'],
                    'FullPath' => $file['full_path'],
                    'Type' => $file['file_type'],
                    'Extension' => $file['file_ext'],
                    'OrigFilename' => $file['orig_name'],
                    'ClientFilename' => $file['client_name'],
                    'Size' => $file['file_size'],
                    'IsImage' => $file['is_image'],
                    'Width' => $file['image_width'],
                    'Height' => $file['image_height'],
                    'AddedBy' => $this->session->userdata("mb_no"),
                    'DateAdded' => strtotime($custom['current_date']),
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => strtotime($custom['current_date']),
                    'Status' => '1'
                );
                $x++;
            }//end foreach

            $batch_x = $this->common->batchInsert_($table, $attach);
        }

        return $batch_x;
    }

//end saveAttachment 

    public function displayUploaded() {
        $where_arr = array("ActivityID =" => $this->input->post('last_id'), "Activity =" => $this->input->post('activity'), "Status =" => '1');

        $result = $this->common->displayUploaded_($where_arr);

        $return = ($result > 0) ? array("success" => 1, "uploaded_data" => $result) : array("success" => 0, "uploaded_data" => "");
        echo json_encode($return);
    }

    public function deleteAttachment() {
        $root_folder = "./media/uploads/";

        $where_arr = array("AttachID =" => $this->input->post('attach_id'));
        $result = $this->common->displayUploaded_($where_arr);

        if (count($result) > 0) {
            foreach ($result as $row => $attach) {
                if ($attach->Path && file_exists($root_folder . $attach->Path) && unlink($root_folder . $attach->Path))
                    $this->common->deleteAttachment_($where_arr);
            }
            $return = array("success" => 1, "message" => "Attachment deleted successfully.");
        }
        else {
            $return = array("success" => 0, "message" => "Error deleting attachment!");
        }

        echo json_encode($return);
    }

    public function downloadAttachment() {
        $activity_id = $this->uri->segment(3);
        $activity = $this->uri->segment(4);
        $attach_id = $this->uri->segment(5);

        $where_arr = array("ActivityID =" => $activity_id,
            "Activity =" => $activity
        );

        if ($attach_id)
            $where_arr[AttachID] = $attach_id;

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $files = $this->common->displayUploaded_($where_arr);

        if (count($files) <= 0) {
            error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
            return false;
        }

        $options = array("id" => $activity_id,
            "activity" => $activity
        );

        if (count($files) > 0)
            download_attachment($files, $options);
    }

    public function exportActivities($actual = 0) {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !allow_export_promotion()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        set_time_limit(0);
		$data = $this->input->post(); 
		  
		$return = $this->getActivities(1, "excel", $data); 
	  	 
		$total_rows = $return[records]; 
		$activities = $return[activities];
		 

        $excel_data = array("DateAdded" => "Date Added",
            "DateUpdated" => "Date Updated",
            "Currency" => "Currency",
            "Username" => "Username",
            "ESupportID" => "E-Support ID",
            "ActivitySource" => "Source",
            "IdReceived" => "ID Received",
            "DescriptionID" => "ID Type",
            "CountryIssuedName" => "Country Issued",
            "ProblemName" => "Account Problem",
			"ProblemCategoryName" => "Category",
			"TGPAmount" => "TGP Amount",  
            "CurrentBalance" => "Current Balance",
			"RegularizeAmount" => "Regularize Amount", 
            "Important" => "Important",
            "IsComplaint" => "Is Complaint",
            "IsUploadPM" => "Updated No.",
			"ToFollowup" => "To Follow Up",
            "EmailSent" => "Email Sent",
            "StatusName" => "Status",
            "Remarks" => "Remarks",
            "GroupAssigneeName" => "Assignee", 
			"CreatedByNickname" => "Created By",
            "mb_nick" => "Last Updated By"
        );

        $force_str = array("Username");

        delete_old_files($this->common->temp_file, "*.xls");
        $file_name = "accounts_issues" . '-' . date("Ymdhis") . ".xls";
        $title = "Accounts Issues";

        //load our new PHPExcel library
        $this->load->library('excel');

        //activate worksheet number 1
        $activeSheet = $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $activeSheet->setTitle($title);

        $headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '8DB4E2')
            ),
            'font' => array('bold' => true),
            'alignment' => array('wrap' => true,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array('outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                )),
        );

        $reportStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f4ec12'),
                'font' => array('bold' => true)));

        $normalStyle = array('alignment' => array('wrap' => true,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );


        $y = 'A';
        $start = 1;

        foreach ($excel_data as $row => $val) {
            $row_cel = $y . $start;
            $activeSheet->setCellValue($row_cel, $val);
            $activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);
            $y++;
        }//end foreach

        $ctr = $start + 1;
        $category_code = "";
        $count_result = 0;

        $end_cell = '';
        $start_cell = 'A' . $ctr;
        foreach ($activities as $row => $activity) {
            $x = 'A';
            //set format
            $activity->DateAdded = date("F d, Y H:i:s D", strtotime($activity->DateAdded));
            $activity->DateUpdated = date("F d, Y H:i:s D", strtotime($activity->DateUpdated));
            //$activity->Amount = number_format($activity->Amount, 2); 

            $activity->Important = ($activity->Important == 1) ? "YES" : "NO";
            $activity->IsComplaint = ($activity->IsComplaint == 1) ? "YES" : "NO";
            $activity->IdReceived = ($activity->IdReceived == 1) ? "YES" : "NO";
            $activity->IsUploadPM = ($activity->IsUploadPM == 1) ? "YES" : "NO";
			$activity->ToFollowup = ($activity->ToFollowup == 1) ? "YES" : "NO";
            $activity->EmailSent = ($activity->EmailSent == 1) ? "YES" : "NO";
            $activity->Remarks = strip_symbols($activity->Remarks);

            foreach ($excel_data as $index => $field) {
                if (in_array($field, $force_str)) {
                    $activeSheet->setCellValueExplicit($x . $ctr, trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $activeSheet->setCellValue($x . $ctr, trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);
                }

                //$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle); 
                $end_cell = $x . $ctr;
                $x++;
            }
            $ctr++;
        }//end foreach 

        $activeSheet->getStyle($start_cell . ':' . $end_cell)->applyFromArray($normalStyle);

        //count reports	 
        $activeSheet->setCellValue('A' . ($ctr + 2), "Total Activities(s)");
        $activeSheet->getStyle('A' . ($ctr + 2))->applyFromArray($reportStyle);
        $activeSheet->setCellValue('B' . ($ctr + 2), count($activities));
        $activeSheet->getStyle('B' . ($ctr + 2))->applyFromArray($reportStyle);

        //set auto width
        $x = 'A';
        $col = 0;
        foreach ($excel_data as $row => $data) {
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
        $objWriter->save($filePath . $file_name);
        $return = (file_exists($filePath . $file_name)) ? array("success" => 1, "message" => "Downloading file.", "download_link" => encode_string($filePath . $file_name)) : array("success" => 0, "message" => "Error downloading file.", "download_link" => "");
        echo json_encode($return);
    }
 	//end export activities 
	
	
	public function exportActivities_XXX($actual = 0) {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !allow_export_promotion()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        set_time_limit(0);

        $data = $this->input->post();
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(4); //4-accounts
        $view_statuslist = explode(',', $result->StatusList);


        $s_fromdate = strtotime(trim($data[s_fromdate]));
        $s_todate = strtotime(trim($data[s_todate]));

        $search_string = "";
        $allow_close = 0;
        $allow_view = 0;

        if (trim($data[s_idreceived]) != '') {
            $search_string .= " AND (a.IdReceived='" . $this->common->escapeString_($data[s_idreceived]) . "') ";
            $search_url .= "&s_idreceived=" . $$data[s_idreceived];
            $allow_close++;
        }

        if (trim($data[s_important]) == 1) {
            $search_string .= " AND (a.Important='" . $this->common->escapeString_($data[s_important]) . "') ";
            $search_url .= "&s_important=" . $$data[s_important];
            $allow_close++;
        }

        if (trim($data[s_iscomplaint]) == 1) {
            $search_string .= " AND (a.IsComplaint='" . $this->common->escapeString_(trim($data[s_iscomplaint])) . "') ";
            $search_url .= "&s_iscomplaint=" . trim($$data[s_iscomplaint]);
            $allow_close++;
        }

        if (trim($data[s_uploadpm]) == 1) {
            $search_string .= " AND (a.IsUploadPM='" . $this->common->escapeString_($data[s_uploadpm]) . "') ";
            $search_url .= "&s_uploadpm=" . $$data[s_uploadpm];
            $allow_close++;
        }

        if (trim($data[s_esupportid])) {
            $search_string .= " AND (a.ESupportID LIKE '%" . $this->common->escapeString_(trim($data[s_esupportid])) . "%') ";
            $search_url .= "&s_esupportid=" . trim($data[s_esupportid]);
            $allow_close++;
        }

        if (trim($data[s_currency])) {
            $search_string .= " AND (a.Currency='" . $this->common->escapeString_(trim($data[s_currency])) . "') ";
            $search_url .= "&s_currency=" . trim($data[s_currency]);
            $allow_close++;
        } else {
            $search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)})"; 
        }

        if (trim($data[s_username])) {
            $search_string .= " AND (a.Username LIKE '%" . $this->common->escapeString_(trim($data[s_username])) . "%') ";
            $search_url .= "&s_username=" . trim($data[s_username]);
            $allow_close++;
            $allow_view++;
        }

        if (trim($data[s_source])) {
            $search_string .= " AND (a.Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        if (trim($data[s_accountproblem])) {
            $search_string .= " AND (a.AccountProblem='" . $this->common->escapeString_(trim($data[s_accountproblem])) . "') ";
            $search_url .= "&s_accountproblem=" . trim($data[s_accountproblem]);
            $allow_close++;
        }

        if (trim($data[s_method])) {
            $search_string .= " AND (a.CategoryId='" . $this->common->escapeString_(trim($data[s_method])) . "') ";
            $search_url .= "&s_method=" . trim($data[s_method]);
            $allow_close++;
        }

        if($s_fromdate && $s_todate)
		 {   
			if($data[s_dateindex] == 'added')
			 {
				$search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "ActivitiesAddedKey";   
				$order_by = "a.DateAddedInt"; 
			 }
			elseif($data[s_dateindex] == 'updated')
			 {
				$search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = "ActivitiesUpdatedKey";   
				$order_by = "a.DateUpdatedInt"; 
			 }
			else
			 {
				$search_string .= " AND (a.{$this->common->date_index} BETWEEN {$s_fromdate} AND {$s_todate})  ";   
				$index = $this->common->group_activities_index;    
				$order_by = "a.DateUpdatedInt"; 
			 }
			
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]))."&s_dateindex=".urlencode(trim($data[s_dateindex]));
			
		 } 
		else
		 {
			 if($data[s_dateindex] == 'added')
			 { 
				$index = "ActivitiesAddedKey";   
				$order_by = "a.DateAddedInt";
			 }
			elseif($data[s_dateindex] == 'updated')
			 {   
				$index = "ActivitiesUpdatedKey"; 
				$order_by = "a.DateUpdatedInt";  
			 }
			else
			 {  
				$index = $this->common->group_activities_index; //$data[s_dateindex];  
				$order_by = "a.DateUpdatedInt";   
			 }
		 }

        if (trim($data[s_status]) != "") {
            $search_string .= " AND (a.Status='" . trim($data[s_status]) . "') ";
            $search_url .= "&s_status=" . trim($data[s_status]);
            $allow_close++;
        } else {
            
        }


        if (trim($data[s_agent]) != "") {
            $search_string .= " AND (f.mb_nick LIKE '%" . $this->common->escapeString_($data[s_agent]) . "%') ";
            $search_url .= "&s_agent=" . trim($data[s_agent]);
            $allow_close++;
        }

        if (trim($data[s_assignee]) != "") {
            $search_string .= " AND (a.GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
            $allow_view++;
        }
		
		if(trim($data[s_warningdays])==1)
		 {     
			$allow_close = 0; 
		 }
		 
        if (trim($data[s_dashboard]) && $allow_close == 0) {
            /* $search_string .= " AND ((a.Status<>{$this->common->ids[close_status]}) AND (a.Status<>{$this->common->ids[crm_note_status]}) 
              AND (a.Status<>{$this->common->ids[deposited_status]} )
              )"; */
            $search_string .= " AND (a.Status NOT IN({$this->common->notcount_status})) "; 
            if (!super_admin()) {
                $search_string .= " AND ((a.GroupAssignee='" . trim($this->session->userdata("mb_usertype")) . "') )";
            }
        } else {
            if (restriction_type() && ($allow_view == 0)) {
                //$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )"; 
            }
        }
  
		$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status NOT IN ({$this->common->hide_status}) ) ":$search_string;
		$search_string = trim(trim($search_string), "AND");

        $per_page = 20;

        $page = ($data['s_page']) ? $data['s_page'] : 0;
        //$total_rows = $this->accounts->getCountAccountActivities_($search_string, $view_statuslist, array(), $index, $order_by)->CountActivity;
		//$activities = $this->accounts->getAccountActivities_($search_string, $view_statuslist, array(), $index, $order_by);
        //$total_rows = count($activities);
		
		$return = $activities = $this->accounts->getAccountActivities_($search_string, $view_statuslist, array(), $index, $order_by);	  
		$total_rows = $return[total_rows]; 
		$activities = $return[result];

        $excel_data = array("DateAdded" => "Date Added",
            "DateUpdated" => "Date Updated",
            "Currency" => "Currency",
            "Username" => "Username",
            "ESupportID" => "E-Support ID",
            "ActivitySource" => "Source",
            "IdReceived" => "ID Received",
            "DescriptionID" => "ID Type",
            "CountryIssuedName" => "Country Issued",
            "ProblemName" => "Account Problem",
			"ProblemCategoryName" => "Category",
			"TGPAmount" => "TGP Amount", 
            /*"BetLimitPerBet" => "Bet Limit Per Bet",
            "BetLimitPerMatch" => "Bet Limit Per Match",
            "ProposedLimitPerBet" => "Proposed Limit Per Bet",
            "ProposedLimitPerMatch" => "Proposed Limit Per Match",
            "RevisedLimitPerBet" => "Revised Limit Per Bet",
            "RevisedLimitPerMatch" => "Revised Limit Per Match",*/
            "CurrentBalance" => "Current Balance",
			"RegularizeAmount" => "Regularize Amount", 
            "Important" => "Important",
            "IsComplaint" => "Is Complaint",
            "IsUploadPM" => "Updated No.",
            "EmailSent" => "Email Sent",
            "StatusName" => "Status",
            "Remarks" => "Remarks",
            "GroupAssigneeName" => "Assignee", 
			"CreatedByNickname" => "Created By",
            "mb_nick" => "Last Updated By"
        );

        $force_str = array("Username");

        delete_old_files($this->common->temp_file, "*.xls");
        $file_name = "accounts_issues" . '-' . date("Ymdhis") . ".xls";
        $title = "Accounts Issues";

        //load our new PHPExcel library
        $this->load->library('excel');

        //activate worksheet number 1
        $activeSheet = $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $activeSheet->setTitle($title);

        $headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '8DB4E2')
            ),
            'font' => array('bold' => true),
            'alignment' => array('wrap' => true,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array('outline' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('argb' => '000000'),
                )),
        );

        $reportStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'f4ec12'),
                'font' => array('bold' => true)));

        $normalStyle = array('alignment' => array('wrap' => true,
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            )
        );


        $y = 'A';
        $start = 1;

        foreach ($excel_data as $row => $val) {
            $row_cel = $y . $start;
            $activeSheet->setCellValue($row_cel, $val);
            $activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);
            $y++;
        }//end foreach

        $ctr = $start + 1;
        $category_code = "";
        $count_result = 0;

        $end_cell = '';
        $start_cell = 'A' . $ctr;
        foreach ($activities as $row => $activity) {
            $x = 'A';
            //set format
            $activity->DateAdded = date("F d, Y H:i:s D", strtotime($activity->DateAdded));
            $activity->DateUpdated = date("F d, Y H:i:s D", strtotime($activity->DateUpdated));
            //$activity->Amount = number_format($activity->Amount, 2); 

            $activity->Important = ($activity->Important == 1) ? "YES" : "NO";
            $activity->IsComplaint = ($activity->IsComplaint == 1) ? "YES" : "NO";
            $activity->IdReceived = ($activity->IdReceived == 1) ? "YES" : "NO";
            $activity->IsUploadPM = ($activity->IsUploadPM == 1) ? "YES" : "NO";
            $activity->EmailSent = ($activity->EmailSent == 1) ? "YES" : "NO";
            $activity->Remarks = strip_symbols($activity->Remarks);

            foreach ($excel_data as $index => $field) {
                if (in_array($field, $force_str)) {
                    $activeSheet->setCellValueExplicit($x . $ctr, trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);
                } else {
                    $activeSheet->setCellValue($x . $ctr, trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);
                }

                //$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle); 
                $end_cell = $x . $ctr;
                $x++;
            }
            $ctr++;
        }//end foreach 

        $activeSheet->getStyle($start_cell . ':' . $end_cell)->applyFromArray($normalStyle);

        //count reports	 
        $activeSheet->setCellValue('A' . ($ctr + 2), "Total Activities(s)");
        $activeSheet->getStyle('A' . ($ctr + 2))->applyFromArray($reportStyle);
        $activeSheet->setCellValue('B' . ($ctr + 2), count($activities));
        $activeSheet->getStyle('B' . ($ctr + 2))->applyFromArray($reportStyle);

        //set auto width
        $x = 'A';
        $col = 0;
        foreach ($excel_data as $row => $data) {
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
        $objWriter->save($filePath . $file_name);
        $return = (file_exists($filePath . $file_name)) ? array("success" => 1, "message" => "Downloading file.", "download_link" => encode_string($filePath . $file_name)) : array("success" => 0, "message" => "Error downloading file.", "download_link" => "");
        echo json_encode($return);
    }
	
    public function userActivities() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));
        parse_str($params, $sdata);

        //$activities = $this->getActivities($this->input->post());						
        $data2 = array("main_page" => "accounts",
            //"currencies"=>$this->common->getCurrency_(), 
            //"sources"=>$this->common->getSource_(), 
            //"problems"=>$this->accounts->getProblemById_(array("Status ="=>'1')),
            //"status_list"=>$this->common->getStatusList_(4),//1 for bank page
            //"utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')),//user types
            "s_page" => $page,
            //"sdata"=>$sdata, 
            "date_index" => $this->common->date_index
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Account Related Issues",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        //$this->load->view('header',$data);
        //$this->load->view('header_nav');
        $this->load->view('accounts/account_users_activities_tpl', $data);
        //$this->load->view('footer');  
    }

    public function getUserActivities($actual = 0) {
        $data = $this->input->post();
        $view_statuslist = array();

        $search_string = "";
        $allow_close = 0;
        $allow_view = 0;

        $username = trim($data[hidden_ausername]);

        if ($username) {
            $search_string .= " AND (a.Username ='" . $this->common->escapeString_($username, true) . "') ";
            $search_url .= "&s_username=" . $username;
            $allow_close++;
            $allow_view++;
        } else {
            $error .= "Enter 12BET Username!<br>";
        }

        if ($error == "") { 
			$search_string = trim(trim($search_string), "AND"); 
			
            $per_page = 20;
            $page = ($data['s_page']) ? $data['s_page'] : 0;
            //$total_rows = $this->common->countSearchActivities_($search_string, "csa_account_issues", "Username")->CountActivity;
            //$activities = $this->accounts->getAccountActivities_($search_string, $view_statuslist, $paging = array("limit" => $per_page, "page" => $page), "Username");
			
			$return =  $this->accounts->getAccountActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), "Username", "a.DateUpdatedInt");	   			$total_rows = $return[total_rows]; 
			$activities = $return[result];
			
            $pagination_options = array("link" => "", //base_url()."banks/activities", 
                "total_rows" => $total_rows,
                "per_page" => $per_page,
                "cur_page" => $page
            );

            $of_str = (($page + 20) <= $total_rows) ? $page + 20 : $total_rows;
            $disp_page = ($page == 0) ? 1 : $page + 1;
            $plural_txt = ($total_rows > 1) ? "activities" : "activity";
            $pagination_string = ($total_rows > 0) ? "Showing " . $disp_page . " to " . $of_str . " of " . $total_rows . " " . $plural_txt : "";

            if ($actual == 1) {//
                $return = array("activities" => $activities,
                    "pagination" => create_pagination($pagination_options),
                    "pagination_string" => $pagination_string,
                    "records" => $total_rows
                );
                return $return;
            } else {
                $return = array("activities" => $this->generateUserActivitiesHtmlList($activities),
                    "pagination" => create_pagination($pagination_options),
                    "pagination_string" => $pagination_string,
                    "records" => $total_rows
                );
                echo json_encode($return);
            }
        } else {
            $return = array("activities" => "<tr class=\"activity_row\"  ><td class=\"center\" colspan=\"9\" >Enter 12BET username to search!</td></tr>",
                "pagination" => "",
                "pagination_string" => "",
                "records" => 0
            );
            echo json_encode($return);
        }
    }

    public function generateUserActivitiesHtmlList($activities) {
        if (count($activities)) {
            foreach ($activities as $row => $activity) {
                $is_important = ($activity->Important == 1) ? " act-danger " : "";
                $can_edit = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy == $this->session->userdata('mb_no')) ) ? 1 : 0;

                $return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" > 
							<td class=\"center\" >" . ucwords($activity->ProblemName) . "</td>
							<td class=\"center\" >
								<!--<div class=\"red tip\" title=\"Date added\" >" . date("Y-m-d H:i:s", strtotime($activity->DateAdded)) . "</div>-->
								<div class=\"green tip\" title=\"Date last updated\" >" . date("Y-m-d H:i:s", strtotime($activity->DateUpdated)) . "</div>
							</td>
							<td class=\"center green\" >" . ucwords($activity->StatusName) . "</td>
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							";

                //check if usertype allowed to edit activity
                if ($can_edit > 0)
                    $return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";

                if ($activity->CountAttach > 0)
                    $return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0\" ></i></a>";

                $return .= "
							</td>
						</tr> ";
            }//end foreach
        }
        else {
            $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"9\" >No issue found!</td>
						</tr>
			 			";
        }

        return $return;
    }

    public function insertUsernameTo12betUsers() {
        if (!super_admin()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }


        set_time_limit(0);
        $rows = array();
        $records = $this->common->getGenerateActivities_("csa_account_issues", array("Username <>" => ''), "ActivityID, Username, Currency, AddedBy, DateAdded, UpdatedBy, DateUpdated");
        $x = 0;

        echo count($records) . " -------------------------------- <br>";
        foreach ($records as $rec) {
            $username = strtolower(trim(iconv("UTF-8", "UTF-8//IGNORE", trim($rec->Username))));
            $username = preg_replace('/[^[:alnum:]]/', '', $username);

            $system_id = trim(iconv("UTF-8", "UTF-8//IGNORE", trim($rec->SystemID)));
            $system_id = trim(preg_replace('/[^[:alnum:]]/', '', $system_id));

            if ($username && $username != "") {
                $exist = $this->common->get12betUserById_(array("a.Username =" => $username), " COUNT(a.UserID) AS CountUser ");

                //if no record found
                if (($exist->CountUser <= 0 || $exist->CountUser == "")) {
                    $x++;
                    echo $rec->ActivityID . ') ' . $username . '<br>';

                    if (!array_key_exists($username, $rows)) {
                        $rows[$username] = array("Username" => $username,
                            "Currency" => trim($rec->Currency),
                            "Activity" => trim($this->activity_type),
                            "AddedBy" => trim($rec->AddedBy),
                            "DateAdded" => trim($rec->DateAdded),
                            "UpdatedBy" => $this->session->userdata("mb_no"),
                            "DateUpdated" => trim($rec->DateUpdated)
                        );

                        if ($system_id != "")
                            $rows[$username][SystemID] = $system_id;
                    }
                    else {
                        if ($system_id != "" && ($rows[$username][SystemID] != $system_id))
                            $rows[$username][SystemID] = $system_id;
                    }
                }
                else {
                    if ($system_id != "" && ($system_id != $exist->SystemID)) {
                        $update_id = $this->promotions->manageActivity_("csa_12bet_users", array("Currency" => trim($rec->Currency),
                            "SystemID" => trim($system_id),
                            "Activity" => trim($this->activity_type),
                            "UpdatedBy" => $this->session->userdata("mb_no"),
                            "DateUpdated" => trim($rec->DateUpdated)
                                ), "update", "UserID", $exist->UserID);
                        if ($update_id > 0)
                            echo $rec->ActivityID . ') ' . $username . " is updated to " . $system_id . "<br>";
                    }
                }
            }
            ob_flush();
            flush();
        }//end foreach  

        echo "<br> --------------------------------";
        ob_end_flush();

        if (count($rows) > 0) {
            $count_rec = $this->common->batchInsert_("csa_12bet_users", $rows);
            echo "<br><br> Inserted rows : " . $count_rec;
        }

        //return $count_rec; 
    }
	
	
	public function getAccountProblemCategory()
	{ 
		$data = $this->input->post(); 	
		$where_arr = array("a.Status ="=>'1');
		 
		if($data[act_problem]) $where_arr['a.AcccountProblem'] = $data[act_problem];
		
		$results = $this->accounts->getAccountProblemCategory_($where_arr);   
 		 
		echo  json_encode($results); 
	} 
	

}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */