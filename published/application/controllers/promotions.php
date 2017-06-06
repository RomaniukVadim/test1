<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Promotions extends MY_Controller {

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
        $this->load->model("promotions_model", "promotions");
        $this->load->model("common_model", "common");
        $this->activity_type = "promotion";
        $this->issue_allow_regularize = array(3, 8); //If select this issue regularize ammount will appear. From csa_promotion_issues table.  

        $this->can_custremarks = array(1);

        $this->process_credit_status = array(19); 
		$this->credit_status = array(57, 18, 68); //18-approve, 57-RM Approved For STS Credit, 68-Approved by Mgmt - From CSS
    }

    public function index() {

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));
        parse_str($params, $sdata);

        $categories_where = " AND a.Status='1' ";

        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
        }

        //$activities = $this->getActivities($this->input->post());						
        $data2 = array("main_page" => "promotions",
            "currencies" => $this->common->getCurrency_(),
            "sources" => $this->common->getSource_(),
            "categories" => $this->promotions->getPromotionsCategoriesList_($categories_where),
            "issues" => $this->promotions->getPromotionIssues_(array("a.Status =" => '1')),
            "status_list" => $this->common->getStatusList_(3), //3 for promotion page  
            "outcomes" => $this->promotions->getCallOutcomeList_(array("a.outcome_status =" => '1')),
            "utypes" => $this->common->getUsersGroup_(array("Status =" => '1')), //user types
            "s_page" => $page,
            "sdata" => $sdata,
            "date_index" => $this->common->date_index
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activities",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('promotions/promotions_activities_tpl');
        $this->load->view('footer');
    }

    public function activities() {
        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));
        parse_str($params, $data);
        $this->index();
    }

    public function getActivities($actual=0, $action="json", $post_data=array()) {  
	
        $data = ($action == "excel")?$post_data:$this->input->post();
		
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(3); //3-promotions
        $view_statuslist = explode(',', $result->StatusList);

        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
            //$search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)})";  
        }
        $categories = $this->promotions->getPromotionsCategoriesList_($categories_where);
        $cat_ids = implode(',', array_map(function($object) {
                    return $object->CategoryID;
                }, $categories));

        $s_fromdate = strtotime(trim($data[s_fromdate]));
        $s_todate = strtotime(trim($data[s_todate]));

        $search_string = "";
        $allow_close = 0;
        $allow_view = 0;
        $use_index2 = 0;
		$na_string = "";
        if (trim($data[s_currency])) {
            $search_string .= " AND (a.Currency='" . $this->common->escapeString_(trim($data[s_currency])) . "') ";
            $search_url .= "&s_currency=" . trim($data[s_currency]);
            $allow_close++;
        } else {
            $search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)})";
        }
 
        if (trim($data[s_dashboard]) && $allow_close == 0) {
            //$search_string .= " AND (a.Status NOT IN({$this->common->notcount_status})) AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ";
			if(trim($data[s_displayclose]) != '1') 
			 {
				 $search_string .= " AND (a.Status NOT IN({$this->common->notcount_status}))  AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ";      
				 $hide_close = 1; 
			 }
			 
            if (!super_admin()) {
                $search_string .= " AND ((a.GroupAssignee='" . trim($this->session->userdata("mb_usertype")) . "') )";
            }
        } else {
            if (restriction_type() && ($allow_view == 0)) {
                //$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )";  
            }
        }

        if (trim($data[s_assignee]) != "") {
            $search_string .= " AND (a.GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
            $allow_view++;
        }

        if (trim($data[s_promotion])) {
            if (trim($data[s_promotion]) == "N/A") {
                $na_string .= " AND (UCASE(b.BonusCode)='" . $this->common->escapeString_($data[s_promotion]) . "') ";
				
            } else {
                $search_string .= " AND (a.Promotion='" . $this->common->escapeString_($data[s_promotion]) . "') ";
            }

            //$search_string .= " AND (a.Promotion='".$this->common->escapeString_($data[s_promotion])."') "; 
            $search_url .= "&s_promotion=" . trim($data[s_promotion]);
            $allow_close++;
            $allow_view++;
        }

        if (trim($data[s_categories])) {
            $search_string .= " AND (a.Category='" . $this->common->escapeString_($data[s_categories]) . "') ";
            $search_url .= "&s_category=" . $s_category;
            $allow_close++;
        } else {
            if (restriction_type()) {
                $search_string .= " AND a.Category IN({$cat_ids}) ";
                $search_url .= "&s_category=" . $s_category;
            }
        }

        if (trim($data[s_issue])) {
            $search_string .= " AND (a.Issue='" . $this->common->escapeString_($data[s_issue]) . "') ";
            $search_url .= "&s_issue=" . $s_issue;
            $allow_close++;
        }

        if (trim($data[s_source])) {
            $search_string .= " AND (a.Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        //for call search 
        if (trim($data[s_calloutcome])) {
            $search_string .= " AND (a.CallOutcomeID='" . $this->common->escapeString_($data[s_calloutcome]) . "') ";
            $search_url .= "&s_calloutcome=" . $s_calloutcome;
            $allow_close++;
        }

        //FOR CRM CALL
        if (trim($data[s_call]) == '1') {
            $search_string .= " AND (a.CallOutcomeID<>0 AND a.CallResultID<>0)  ";
            $search_url .= "&s_call=" . trim($data[s_call]);
            $allow_close++;
        }

        if (trim($data[s_callproblem])) {
            $search_string .= " AND (a.CallProblem='" . $this->common->escapeString_($data[s_callproblem]) . "') ";
            $search_url .= "&s_callproblem=" . $s_callproblem;
            $allow_close++;
        }

        if (trim($data[s_callsendsms]) == 1) {
            $search_string .= " AND (a.CallSendSMS='" . $this->common->escapeString_($data[s_callsendsms]) . "') ";
            $search_url .= "&s_callsendsms=" . $s_callsendsms;
            $allow_close++;
        }

        if (trim($data[s_callsendemail]) == 1) {
            $search_string .= " AND (a.CallSendEmail='" . $this->common->escapeString_($data[s_callsendemail]) . "') ";
            $search_url .= "&s_callsendemail=" . $s_callsendemail;
            $allow_close++;
        }
        //end for call search 

        if (trim($data[s_important]) == '1') {
            $search_string .= " AND (a.Important='" . $this->common->escapeString_($data[s_important]) . "') ";
            $search_url .= "&s_important=" . $$data[s_important];
            $allow_close++;
        }

        if (trim($data[s_iscomplaint]) == '1') {
            $search_string .= " AND (a.IsComplaint='" . $this->common->escapeString_(trim($data[s_iscomplaint])) . "') ";
            $search_url .= "&s_iscomplaint=" . trim($$data[s_iscomplaint]);
            $allow_close++;
        }

        if (trim($data[s_custremarks]) == '1') {
            $search_string .= " AND (a.CustomerRemarks<>'') ";
            $search_url .= "&s_custremarks=" . trim($$data[s_custremarks]);
            $allow_close++;
            $use_index2++;
        }

        if (trim($data[s_tofollowup]) == 1) {
            $search_string .= " AND (a.ToFollowup='" . $this->common->escapeString_($data[s_tofollowup]) . "') ";
            $search_url .= "&s_tofollowup=" . $$data[s_tofollowup];
            $allow_close++;
        }
		
		if (trim($data[s_uploadpm]) == 1) {
            $search_string .= " AND (a.IsUploadPM='" . $this->common->escapeString_($data[s_uploadpm]) . "') ";
            $search_url .= "&s_uploadpm=" . $$data[s_uploadpm];
            $allow_close++;
        }
		
        if (trim($data[s_isuploaded]) == 1) {
            $search_string .= " AND (a.IsUpload='" . $this->common->escapeString_($data[s_isuploaded]) . "') ";
            $search_url .= "&s_isuploaded=" . $$data[s_isuploaded];
            $allow_close++;
        }

        /* if(trim($data[s_esupportid]))
          {
          $search_string .= " AND (a.ESupportID LIKE '%".$this->common->escapeString_(trim($data[s_esupportid]))."%') ";
          $search_url .= "&s_esupportid=".trim($data[s_esupportid]);
          $allow_close++;
          } */

        if (trim($data[s_username])) {
            $search_string .= " AND (a.Username LIKE '%" . $this->common->escapeString_(trim($data[s_username])) . "%') ";
            $search_url .= "&s_username=" . trim($data[s_username]);
            $allow_close++;
        }

        if (trim($data[s_transactionid])) {
            $search_string .= " AND (a.TransactionID LIKE '%" . $this->common->escapeString_(trim($data[s_transactionid])) . "%') ";
            $search_url .= "&s_transactionid=" . trim($data[s_transactionid]);
            $allow_close++;
        }

        /* if(trim($data[s_problem]))
          {
          $search_string .= " AND (a.Problem='".$this->common->escapeString_(trim($data[s_problem]))."') ";
          $search_url .= "&s_problem=".trim($data[s_problem]);
          $allow_close++;
          } */

        if ($s_fromdate && $s_todate) {
            if ($data[s_dateindex] == 'added') {
                $search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = "ActivitiesAddedKey";
                $order_by = "a.DateAddedInt";
            } elseif ($data[s_dateindex] == 'updated') {
                $search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2";
                $order_by = "a.DateUpdatedInt";
            } elseif ($data[s_dateindex] == 'uploaded') {
                $search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = ($use_index2 == 0) ? "ActivitiesUploadedKey" : "ActivitiesUploadedKey2";
                $order_by = "a.DateUploadedInt";
            } else {
                $search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2";
                $order_by = "a.DateUpdatedInt";
            }

            $search_url .= "&s_fromdate=" . urlencode(trim($data[s_fromdate])) . "&s_todate=" . urlencode(trim($data[s_todate])) . "&s_dateindex=" . urlencode(trim($data[s_dateindex]));
        } else {
            if ($data[s_dateindex] == 'added') {
                $index = ($use_index2 == 0) ? "ActivitiesAddedKey" : "ActivitiesAddedKey2";
                $order_by = "a.DateAddedInt";
            } elseif ($data[s_dateindex] == 'updated') {
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2";
                $order_by = "a.DateUpdatedInt";
            } elseif ($data[s_dateindex] == 'uploaded') {
                $index = ($use_index2 == 0) ? "ActivitiesUploadedKey" : "ActivitiesUploadedKey2";
                $order_by = "a.DateUploadedInt";
            } else {
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2"; //$data[s_dateindex];  
                $order_by = "a.DateUpdatedInt";
            }
        }

        if (trim($data[s_warningdays]) == 1) {
            $allow_close = 0; 
			$data[s_displayclose] = 0; 
        }

        /* if(trim($data[s_agent]) != "")
          {
          $search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";
          $search_url .= "&s_agent=".trim($data[s_agent]);
          $allow_close++;
          } */
		
		if(trim($data[s_status]) != "")
		 {
			$search_string .= " AND (a.Status='".trim($data[s_status])."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
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
		 	 
        //$search_string = ($allow_close <= 0 && $this->common->display_close_ticket == 1) ? $search_string . " AND (a.Status NOT IN ({$this->common->hide_status}) ) " : $search_string;
		
        $search_string = trim(trim($search_string), "AND");
		$na_string = trim(trim($na_string), "AND");
		
        $per_page = 20;
        //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        $page = ($data['s_page']) ? $data['s_page'] : 0;
         
		$paging = ($action == "excel")?array():$paging=array("limit"=>$per_page, "page"=>$page); //if excell export all without paging
        $return = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging, $index, $order_by, $na_string);
        $total_rows = $return[total_rows];
        $activities = $return[result];

        $pagination_options = array("link" => "", //base_url()."promotions/activities", 
            "total_rows" => $total_rows,
            "per_page" => $per_page,
            "cur_page" => $page
        );

        $of_str = (($page + 20) <= $total_rows) ? $page + 20 : $total_rows;
        $disp_page = ($page == 0) ? 1 : $page + 1;
        $plural_txt = ($total_rows > 1) ? "activities" : "activity";
        $pagination_string = ($total_rows > 0) ? "Showing " . $disp_page . " to " . $of_str . " of " . $total_rows . " " . $plural_txt : "";
 
		if($actual == 1)
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

    public function getPromotionsList() {
        $post = $this->input->post(); 
		//$where_arr = array("a.Status =" => '1');
		$where_arr = array();
        $where_or = array();
			
        
		if(trim($post[product])) $where_arr["a.ProductID"] = trim($post[product]);   
		if(trim($post[currency])) $where_arr["a.CurrencyID"] = trim($post[currency]);  
		if(trim($post[category])) $where_arr["a.CategoryID"] = trim($post[category]);     
		
		if(trim($post[is_active]) == 1) 
		 { 
		 	$where_arr["a.Status"] = '1';   
		 }
		else
		 {
			$where_arr["a.Status <>"] = '9';  
		 }
		 
		if(trim($post[is_expired]) == 1) $where_arr["DATE(NOW()) BETWEEN a.StartedDate AND a.EndDate !="] = 0;
		 
        if(trim($post[default_promotion])) $where_or["a.PromotionID"] = $post[default_promotion];

        $x = $this->promotions->getChangePromotionById_($where_arr, $where_or);

        echo json_encode($x);
    }
	
	public function getPromotionsListClear($active=1) {
        $where_arr = array("a.Status =" => '1');
        $where_or = array();

        if (trim($this->input->post('product')))
            $where_arr['a.ProductID ='] = trim($this->input->post('product'));
        if (trim($this->input->post('currency')))
            $where_arr['a.CurrencyID ='] = trim($this->input->post('currency'));

        if (trim($this->input->post('category')))
            $where_arr['a.CategoryID ='] = trim($this->input->post('category'));

        //if(trim($this->input->post('default'))) $where_or['a.PromotionID ='] = trim($this->input->post('default'));

        $x = $this->promotions->getChangePromotionById_($where_arr, $where_or);

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
                $promotion_name = $activity->PromotionName;
                $can_edit = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy == $this->session->userdata('mb_no')) ) ? 1 : 0;

                if ($activity->PromotionStartDate != "0000-00-00" && $activity->PromotionEndDate != "0000-00-00" && $activity->PromotionEndDate != "0000-00-00") {
                    $promotion_name = (date("Y-m-d") > $activity->PromotionEndDate) ? "<div class=\"act-danger tip\" title=\"subscription expired\" >" . $activity->PromotionName . "</div>" : $activity->PromotionName;
                    $promotion_name = ($activity->PromotionStatus != 1) ? "<div class=\"act-warning tip\" title=\"promotion is inactive\" >" . $activity->PromotionName . "</div>" : $promotion_name;
                }

                $days_diff = (!in_array($activity->Status, $this->common->days_notcount_status)) ? days_diff(date("Y-m-d", $activity->DateAddedInt)) : days_diff(date("Y-m-d", $activity->DateAddedInt), date("Y-m-d", $activity->DateUpdatedInt));
                $days_class = (!in_array($activity->Status, $this->common->days_notcount_status) && ($days_diff >= $this->common->days_warning) ) ? "<i class='icon12 i-clock-6  orange'  ></i>" : "";
                $days_txt = (!in_array($activity->Status, $this->common->days_notcount_status)) ? "Days in Process" : "Days Processed";
                $days_diff_txt = ($days_diff > 0) ? "<br>{$days_txt}: <span class='badge badge-info' >{$days_diff}</span>" : "";
				
				$to_followup_class = ($activity->ToFollowup == '1')?"<i class='icon12 i-bubbles-4 green'  ></i>":"";
				
                //''
                $return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" data-toggle=\"popover\" data-placement=\"top\" data-content=\"Date Added : <span class='orange'>" . date("M d, Y H:i", $activity->DateAddedInt) . "</span><br>Last Updated : <span class='green'>" . date("M d, Y H:i", $activity->DateUpdatedInt) . "</span>{$days_diff_txt}\" data-original-title=\"<i class='icon16 i-info gap-left0' ></i> Other Info\" data-html=\"true\"  >  
							<td class=\"center\" >{$to_followup_class} {$days_class}{$activity->Currency}</td>
							<td class=\"center {$is_important}\" >";
                $return .= ($activity->IsComplaint == '1') ? "<i class=\"icon12 i-warning act-danger tip\" title=\"complaint\" ></i>" : "";
                $return .= "{$activity->Username}</td>";

                $return .= "
							<td  >{$promotion_name}</td>
							<td class=\"center\">{$activity->TransactionID}</td> 
							<td class=\"right\">{$activity->DepositAmount}</td>
							<td class=\"right\">{$activity->BonusAmount}</td>
							<td class=\"right\">{$activity->WageringAmount}</td>
							<td class=\"center green\" >" . ucwords($activity->StatusName) . "</td>
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\"  target=\"CsaContentDetails\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							";
                //$x = (can_edit_upload() && ($activitiy->IsUpload=='1' &&) )
                //check if usertype allowed to edit activity
                if ($can_edit > 0)
                    $return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";

                //check if attachment
                if ($activity->CountAttach > 0)
                    $return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0 gap-right10\" ></i></a>";

                //check if call
                if (($activity->CallStart != "0000-00-00 00:00:00" && $activity->CallStart != "" && $activity->CallEnd != "0000-00-00 00:00:00" && $activity->CallEnd != ""))
                    $return .= "<a href=\"#ActivityDetailsModal\" title=\"view call info\" alt=\"view call info\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"ViewCall{$activity->ActivityID}\"  data-toggle=\"modal\" call-info=\"1\" target=\"CrmContentDetails\" ><i class=\"icon16 i-phone-2 gap-left0\" ></i></a>";


                $return .= "
							</td>
						</tr> ";
            }//end foreach
        }
        else {
            $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"10\" >No activity found!</td>
						</tr>
			 			";
        }

        return $return;
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

        $categories_where .= " AND (a.Status='1') ";
        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
        }

        $activity_id = trim($this->uri->segment(3));

        if ($activity <= 0 || $activity == "") {
            $user_id = trim($this->input->post('user12_id'));
            $default_user = ($user_id) ? $this->common->get12betUserById_(array("a.UserID =" => $user_id)) : array();
        }

        $conditions_array = array('a.ActivityID =' => $activity_id);
        $activity = ($activity_id) ? $this->promotions->getActivityById_($conditions_array) : "";


        //for promotions
        if (count($activity) > 0) {
            $where_arr = array("a.Status =" => '1',
                "a.ProductID" => $activity->Product,
                "a.CurrencyID" => $activity->Currency
            );
            $where_or = array("a.PromotionID" => $activity->Promotion);
            $def_stat = $activity->Status;
        }
        $promotions = $this->promotions->getChangePromotionById_($where_arr, $where_or);
        //end for promotions



        $data2 = array("main_page" => "promotions",
            "currencies" => $this->common->getCurrency_(),
            "sources" => $this->common->getSource_(),
            "status_list" => $this->common->getStatusList_(3), //3 promotion
            "activity" => $activity,
            "s_page" => $page,
            "categories" => $this->promotions->getPromotionsCategoriesList_($categories_where),
            "issues" => $this->promotions->getPromotionIssues_(array("a.Status =" => '1')),
            //"outcomes"=>$this->promotions->getCallOutcomeList_(array("a.outcome_status ="=>'1')), 
            "results" => $this->promotions->getResultList_(array("a.result_status =" => '1')),
            //"products"=>$this->common->getProductsList_(array("a.Status ="=>1)),
            "promotions" => $promotions,
            "activity_id" => $activity_id,
            "assignees" => $this->common->getAssignees_($this->session->userdata('mb_usertype'), $activity->GroupAssignee),
            "settings_ids" => $this->common->ids,
            "issue_allow_regularize" => $this->issue_allow_regularize,
            "default_user" => $default_user
        );

        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activities ",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('promotions/promotional_popup_tpl', $data);
    }

    public function manageActivity() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        $data = $this->input->post();
        $data['act_important'] = ($data['act_important'] == "") ? 0 : $data['act_important'];
        $data['act_iscomplaint'] = ($data['act_iscomplaint'] == "") ? 0 : $data['act_iscomplaint'];
        $data['act_isuploadpm'] = ($data['act_isuploadpm'] == "") ? 0 : $data['act_isuploadpm'];
		 $data['act_tofollowup'] = ($data['act_tofollowup'] == "") ? 0 : $data['act_tofollowup'];

        $data[act_callstart] = ($data[act_callstart] == "") ? "0000-00-00 00:00:00" : $data[act_callstart];
        $data[act_callend] = ($data[act_callend] == "") ? "0000-00-00 00:00:00" : $data[act_callend];
        $data[act_calloutcome] = ($data[act_calloutcome] == "") ? 0 : $data[act_calloutcome];
        $data[act_callresult] = ($data[act_callresult] == "") ? 0 : $data[act_callresult];
        $data[act_callsendsms] = ($data[act_callsendsms] == "") ? 0 : $data[act_callsendsms];
        $data[act_callsendemail] = ($data[act_callsendemail] == "") ? 0 : $data[act_callsendemail];
        $data[act_callproblem] = ($data[act_callproblem] == "") ? "" : $data[act_callproblem];


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

        if ($data[act_systemid] == "") {
            $error .= "Select system ID!<br> ";
        }
  
        if ($data[act_category] == "") {
            $error .= "Select category!<br> ";
        }

        if ($data[act_promotion] == "" || !isset($data[act_promotion])) {
            $error .= "Select promotion!<br> ";
        }
	   else
		{
			$promotion = $this->promotions->getPromotionById_(array('a.PromotionID =' => $data['act_promotion']));
        	$data[act_product] = $promotion->ProductID;		
		}
		
	    if ($data[act_product] == "" || $data[act_product] == 0) {
            $error .= "Promotion product type is not set. Please manage promotion!<br> ";
        }
			
        if ($data[act_currentbalance] == "") {
            $error .= "Enter current balance!<br> ";
        }

        if ($data[act_depositamount] == "") {
            $error .= "Enter deposit amount!<br> ";
        }

        if ($data[act_bonusamountx] == "") {
            $error .= "Enter bonus amount!<br> ";
        }

        if ($data[act_wageringamount] == "") {
            $error .= "Enter wagering amount!<br> ";
        }

        if (in_array($data[act_issue], $this->issue_allow_regularize)) {
            if ($data[act_regularizeamount] == "") {
                $error .= "Enter regularize amount!<br>";
            }

            if ($data[act_bonusdeduct] == "") {
                $error .= "Enter bonus deduct!<br>";
            }

            if ($data[act_winningsdeduct] == "") {
                $error .= "Enter winnings deduct!<br>";
            }
        } else {
            $data[act_regularizeamount] = 0;
        }

        /* if($data[act_idreceived] == "")
          {
          $error .= "Please verify if ID received or not!<br> ";
          } */

        if ($data[act_status] == "") {
            $error .= "Select status!<br> ";
        }

        if ($data[act_remarks] == "") {
            $error .= "Enter remarks!<br> ";
        }

        //check the call data
        $is_call = 0;
        if ($data[act_callstart] != "0000-00-00 00:00:00" || $data[act_callend] != "0000-00-00 00:00:00" || $data[act_calloutcome] > 0 || $data[act_callresult] > 0 || $data[act_callproblem] != "" || $data[act_callsendsms] == '1' || $data[act_callsendemail] == '1' || trim($data[act_callerid] != '')) {
            if (($data[act_callstart] > $data[act_callend]) || ($data[act_callend] < $data[act_callstart])) {
                $error .= "Check call start and call end!<br> ";
            }

            if ($data[act_calloutcome] == "") {
                $error .= "Select call outcome!<br> ";
            }

            if ($data[act_callresult] == "") {
                $error .= "Select call result!<br> ";
            } else {
                /* if($data[act_custremarks] == "" && in_array($data[act_callresult], $this->can_custremarks))
                  {
                  $error .= "Enter customer remarks!<br> ";
                  } */
            }

            if (trim($data[act_callerid]) == "") {
                $error .= "Enter ameyo caller ID!<br> ";
            }

            $is_call = 1;
        }
        //end call checking
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

        if ($error) {
            $return = array("success" => 0, "message" => $error);
        } else {
            $action = ($this->input->post('hidden_action') == "add") ? "add" : "update";
            $current_date = date("Y-m-d H:i:s");

            //$promotion = $this->promotions->getPromotionById_(array('a.PromotionID ='=>$data['act_promotion'])); 

            if ($action == "add") {
                $post_data = array(
                    'Username' => trim($data['act_username']),
                    'Currency' => $data['act_currency'],
                    'ESupportID' => $data['act_esupportid'],
                    'Source' => $data['act_source'],
                    'Promotion' => $data['act_promotion'],
                    'Product' => $data['act_product'],
                    'Category' => $data['act_category'],
                    'Issue' => $data['act_issue'],
                    'SystemID' => $data['act_systemid'],  
					'TransactionID' => $data['act_transactionid'],
                    'DepositAmount' => $data['act_depositamount'],
                    'CurrentBalance' => $data['act_currentbalance'],
                    'OutstandingBets' => $data['act_outstandingbets'],
                    'BonusAmount' => $data['act_bonusamount'],
                    'WageringAmount' => $data['act_wageringamount'],
                    'BonusDeduct' => $data['act_bonusdeduct'],
                    'WinningsDeduct' => $data['act_winningsdeduct'],
                    'MinimumAmount' => $data['hidden_aminimum'],
                    'MaximumAmount' => $data['hidden_amaximum'],
                    'TurnOver' => $data['hidden_aturnover'],
                    'BonusRate' => $data['hidden_abonusrate'],
                    'Formula' => $data['hidden_aformula'],
                    'WageringFormula' => $data['hidden_awageringformula'],
                    'TurnoverAmount' => $data['act_turnoveramount'],
                    'CashbackAmount' => $data['act_cashbackamount'],
                    'RegularizeAmount' => $data['act_regularizeamount'],
                    'AddedBy' => $this->session->userdata("mb_no"),
                    'DateAdded' => $current_date,
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateAddedInt' => strtotime($current_date),
                    'DateUpdatedInt' => strtotime($current_date),
                    'UserIP' => $this->input->ip_address(),
                    'Remarks' => $data['act_remarks'],
                    //'RMRemarks'=>$data['act_rmremarks'],
                    //'MRemarks'=>$data['act_mremarks'],
                    //'SRemarks'=>$data['act_sremarks'],     
                    'IsUploadPM' => $data['act_isuploadpm'],
					'ToFollowup' => $data['act_tofollowup'],
                    'GroupAssignee' => $data['act_assignee'],
                    'Status' => $data['act_status'],
					
                    'CallStart' => $data['act_callstart'],
                    'CallEnd' => $data['act_callend'],
                    'AmeyoCallerID' => trim($data['act_callerid']),
                    'CallOutcomeID' => $data['act_calloutcome'],
                    'CallResultID' => $data['act_callresult'], 
					'CallResultCategoryID' => $data['act_callresultcategory'],
                    'CallSendSMS' => $data['act_callsendsms'],
                    'CallSendEmail' => $data['act_callsendemail'],
                    'CallProblem' => $data['act_callproblem'],
                    
					"PromotionStartDate" => $data['hidden_apromotionstart'],
                    "PromotionEndDate" => $data['hidden_apromotionend'], 
					'CasinoTransferID' => $data['act_casinotransferid']
                );
				
				if(admin_access() || csd_supervisor_access() || 1){
					$post_data['Important'] = $data['act_important'];
                    $post_data['IsComplaint'] = $data['act_iscomplaint'];   
				}
				
                if ($data[act_offer] == 1) {
                    $post_data[OfferedBy] = $this->session->userdata("mb_no");
                }

                //$post_data['Status'] = ($data['act_callstart'] != "" && $data['act_callend'] != "" && $data['act_calloutcome'] && $data['act_callresult'])?$this->common->ids['crm_note_status']:$post_data['Status'];

                if ($is_call == 1)
                    $post_data['CustomerRemarks'] = trim($data['act_custremarks']);

                $last_id = $this->promotions->manageActivity_("csa_promotion_activities", $post_data, $action, '', '');
                if ($last_id > 0) {
                    //INSERT TO CALLS
                    if ($is_call == 1) {
                        $call_data = array(
                            'ActivityID' => $last_id,
                            'Activity' => $this->activity_type,
                            'AddedBy' => $this->session->userdata("mb_no"),
                            'DateAdded' => $current_date,
                            'UpdatedBy' => $this->session->userdata("mb_no"),
                            'DateUpdated' => $current_date,
                            'DateAddedInt' => strtotime($current_date),
                            'DateUpdatedInt' => strtotime($current_date),
                            'CallStart' => $data['act_callstart'],
                            'CallEnd' => $data['act_callend'],
                            'AmeyoCallerID' => trim($data['act_callerid']),
                            'CallOutcomeID' => $data['act_calloutcome'],
                            'CallResultID' => $data['act_callresult'], 
							'CallResultCategoryID' => $data['act_callresultcategory'],
                            'CallSendSMS' => $data['act_callsendsms'],
                            'CallSendEmail' => $data['act_callsendemail'],
                            'CallProblem' => $data['act_callproblem'],
                            'CustomerRemarks' => trim($data['act_custremarks']),
                        );
                        $call_add = $this->promotions->manageActivity_("csa_calls", $call_data, "add", '', '');
                    }
                    //END INSERT TO CALLS  
                    //save attacment; 
                    $attach = $this->saveAttachment($upload_data, array("last_id" => $last_id, "current_date" => $current_date, "activity_type" => $this->activity_type, "caption" => ""), "csa_attach_file");

                    $return = array("success" => 1, "message" => "Promotional activity added successfully.", "upload_data" => json_encode($upload_data), "is_change" => 1);

                    $history_data = array(
                        'ActivityID' => $last_id,
                        'Activity' => $this->activity_type,
                        'Status' => $post_data['Status'],
                        'Remarks' => $data['act_remarks'],
                        'UpdatedBy' => $this->session->userdata("mb_no"),
                        'Important' => $data['act_important'],
                        'IsComplaint ' => $data['act_iscomplaint'],
                        'DateUpdated' => $current_date,
                        'DateUpdatedInt' => strtotime($current_date),
                        'GroupAssignee' => $data['act_assignee']
                    );

                    $y = $this->promotions->manageActivity_("csa_activities_history", $history_data, "add", '', '');
                } else {
                    $return = array("success" => 0, "message" => "Error adding activity!");
                }
            } else {
                $post_data = array(
                    'Username' => trim($data['act_username']),
                    'Currency' => $data['act_currency'],
                    'ESupportID' => $data['act_esupportid'],
                    'Source' => $data['act_source'],
                    'Promotion' => $data['act_promotion'],
                    'Product' => $data['act_product'],
                    'Category' => $data['act_category'],
                    'Issue' => $data['act_issue'],
                    'SystemID' => $data['act_systemid'],
                    'TransactionID' => $data['act_transactionid'],
                    'DepositAmount' => $data['act_depositamount'],
                    'CurrentBalance' => $data['act_currentbalance'],
                    'OutstandingBets' => $data['act_outstandingbets'],
                    'BonusAmount' => $data['act_bonusamount'],
                    'WageringAmount' => $data['act_wageringamount'],
                    'BonusDeduct' => $data['act_bonusdeduct'],
                    'WinningsDeduct' => $data['act_winningsdeduct'],
                    'MinimumAmount' => $data['hidden_aminimum'],
                    'MaximumAmount' => $data['hidden_amaximum'],
                    'TurnOver' => $data['hidden_aturnover'],
                    'BonusRate' => $data['hidden_abonusrate'],
                    'Formula' => $data['hidden_aformula'],
                    'WageringFormula' => $data['hidden_awageringformula'],
                    'TurnoverAmount' => $data['act_turnoveramount'],
                    'CashbackAmount' => $data['act_cashbackamount'],
                    'RegularizeAmount' => $data['act_regularizeamount'],
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'Remarks' => $data['act_remarks'],
                    //'RMRemarks'=>$data['act_rmremarks'],
                    //'MRemarks'=>$data['act_mremarks'],
                    //'SRemarks'=>$data['act_sremarks'],      
                    'IsUploadPM' => $data['act_isuploadpm'], 
					'ToFollowup' => $data['act_tofollowup'],
                    'GroupAssignee' => $data['act_assignee'],
                    'Status' => $data['act_status'],
                    'CallStart' => $data['act_callstart'],
                    'CallEnd' => $data['act_callend'],
                    'AmeyoCallerID' => trim($data['act_callerid']),
                    'CallOutcomeID' => $data['act_calloutcome'],
                    'CallResultID' => $data['act_callresult'],    
					'CallResultCategoryID' => $data['act_callresultcategory'],
                    'CallSendSMS' => $data['act_callsendsms'],
                    'CallSendEmail' => $data['act_callsendemail'],
                    'CallProblem' => $data['act_callproblem'], 
					'CasinoTransferID' => $data['act_casinotransferid']
                );
				
				if(admin_access() || csd_supervisor_access() || 1){
					$post_data['Important'] = $data['act_important'];
                    $post_data['IsComplaint'] = $data['act_iscomplaint'];   
				}
				
                if ($is_call == 1)
                    $post_data['CustomerRemarks'] = trim($data['act_custremarks']);
				  

                $old = $this->promotions->getActivityById_(array("a.ActivityID =" => $data["hidden_activityid"]));

                $changes = "";

                //offer promotion  
                if ($old->OfferedBy == 0) {
                    if ($data[act_offer] == 1) {
                        $post_data[OfferedBy] = $this->session->userdata("mb_no");
                        $changes .= "Promotion offered by {$this->session->userdata('mb_nick')} |||";
                    }
                } else {
                    if ($data[act_offer] != 1 && admin_access()) {
                        $post_data[OfferedBy] = 0;
                        $changes .= "Promotion offered removed by {$this->session->userdata('mb_nick')} |||";
                    }
                }


                if (trim($old->IsUpload) == '1' && $old->Status <= 0) {
                    $post_data[DateAdded] = $current_date;
                    $post_data[DateAddedInt] = strtotime($current_date);
                }

                $new_imptxt = ($data['act_important'] == 1) ? "Important" : "Not Important";
                $old_imptxt = ($old->Important == 1) ? "Important" : "Not Important";

                $new_iscomplainttxt = ($data['act_iscomplaint'] == 1) ? "Complain" : "Not Complain";
                $old_iscomplainttxt = ($old->IsComplaint == 1) ? "Complain" : "Not Complain";

                $new_callsmstxt = ($data[act_callsendsms] == 1) ? "True" : "False";
                $old_callsmstxt = ($old->CallSendSMS == 1) ? "True" : "False";

                $new_callemailtxt = ($data[act_callsendemail] == 1) ? "True" : "False";
                $old_callemailtxt = ($old->CallSendEmail == 1) ? "True" : "False";

                $new_iscomplainttxt = ($data['act_iscomplaint'] == 1) ? "Complain" : "Not Complain";
                $old_iscomplainttxt = ($old->IsComplaint == 1) ? "Complain" : "Not Complain";

                $new_callprobtxt = ($data[act_callproblem]) ? ucwords(str_replace("_", " ", $act_callproblem)) : "";
                $old_callprobtxt = ($old->CallProblem) ? ucwords(str_replace("_", " ", $old->CallProblem)) : "";
				
				$new_tofollowup_txt = ($data['new_tofollowup_txt'] == 1) ? "YES" : "NO";
                $old_tofollowup_txt = ($old->ToFollowup == 1) ? "YES" : "NO";
				
                $changes .= ($data['act_assignee'] != $old->GroupAssignee) ? "Group Assignee changed to " . $data['hidden_aassignee'] . " from " . $old->GroupAssigneeName . "|||" : "";
                $changes .= ($data['act_currency'] != $old->Currency) ? "Currency changed to " . $data['hidden_acurrency'] . " from " . $old->CurrencyName . "|||" : "";
                $changes .= ($data['act_username'] != $old->Username) ? "Username changed to " . $data['act_username'] . " from " . $old->Username . "|||" : "";
                $changes .= ($data['act_esupportid'] != $old->ESupportID) ? "E-Support ID changed to " . $data['act_esupportid'] . " from " . $old->ESupportID . "|||" : "";
                $changes .= ($data['act_systemid'] != $old->SystemID) ? "System ID changed to " . $data['act_systemid'] . " from " . $old->SystemID . "|||" : "";
                $changes .= ($data['act_source'] != $old->Source) ? "Source changed to " . $data['hidden_asource'] . " from " . $old->ActivitySource . "|||" : "";
                $changes .= ($data['act_transactionid'] != $old->TransactionID) ? "Transaction ID changed to " . $data['act_transactionid'] . " from " . $old->TransactionID . "|||" : "";

                if ($data['act_promotion'] != $old->Promotion) {
                    $post_data["PromotionStartDate"] = $data['hidden_apromotionstart'];
                    $post_data["PromotionEndDate"] = $data['hidden_apromotionend'];
                    //$changes .= ($data['act_promotion'] != $old->Promotion)?"Promotion changed to ".$data['hidden_apromotion']." from ".$old->PromotionName."|||":"";
                    $changes .= "Promotion changed to " . $data['hidden_apromotion'] . " from " . $old->PromotionName . "|||";
                }

                $changes .= ($data['act_product'] != $old->Product) ? "Product changed to " . $data['hidden_aproduct'] . " from " . $old->ProductName . "|||" : "";
                $changes .= ($data['act_category'] != $old->Category) ? "Category changed to " . $data['hidden_acategory'] . " from " . $old->CategoryName . "|||" : "";
                $changes .= ($data['act_issue'] != $old->Issue && ($data['act_issue'] != "" && $old->Issue != 0) ) ? "Issue changed to " . $data['hidden_aissue'] . " from " . $old->IssueName . "|||" : "";
				
                $changes .= ($data['act_depositamount'] != $old->DepositAmount) ? "Deposit Amount changed to " . $data['act_depositamount'] . " from " . $old->DepositAmount . "|||" : "";
                $changes .= ($data['act_currentbalance'] != $old->CurrentBalance) ? "Current Balance changed to " . $data['act_currentbalance'] . " from " . $old->CurrentBalance . "|||" : "";
                $changes .= ($data['act_outstandingbets'] != $old->OutstandingBets) ? "Outstanding Bets changed to " . $data['act_outstandingbets'] . " from " . $old->OutstandingBets . "|||" : "";
                $changes .= ($data['act_bonusamount'] != $old->BonusAmount) ? "Bonus Amount changed to " . $data['act_bonusamount'] . " from " . $old->BonusAmount . "|||" : "";
                $changes .= ($data['act_wageringamount'] != $old->WageringAmount) ? "Wagering Amount changed to " . $data['act_wageringamount'] . " from " . $old->WageringAmount . "|||" : "";
                $changes .= ($data['act_bonusdeduct'] != $old->BonusDeduct) ? "Bonus Deduct changed to " . $data['act_bonusdeduct'] . " from " . $old->BonusDeduct . "|||" : "";
                $changes .= ($data['act_winningsdeduct'] != $old->WinningsDeduct) ? "Bonus Deduct changed to " . $data['act_winningsdeduct'] . " from " . $old->WinningsDeduct . "|||" : "";
                $changes .= ($data['hidden_aminimum'] != $old->MinimumAmount) ? "Minimum Amount changed to " . $data['hidden_aminimum'] . " from " . $old->MinimumAmount . "|||" : "";
                $changes .= ($data['hidden_amaximum'] != $old->MaximumAmount) ? "Maximum Amount changed to " . $data['hidden_amaximum'] . " from " . $old->MaximumAmount . "|||" : "";
                $changes .= ($data['hidden_abonusrate'] != $old->BonusRate) ? "Bonus Rate changed to " . $data['hidden_abonusrate'] . " from " . $old->BonusRate . "|||" : "";
                $changes .= ($data['hidden_aturnover'] != $old->TurnOver) ? "Turnover changed to " . $data['hidden_aturnover'] . " from " . $old->TurnOver . "|||" : "";
                $changes .= ($data['hidden_aformula'] != $old->Formula) ? "Formula changed to " . $data['hidden_aformula'] . " from " . $old->Formula . "|||" : "";
                $changes .= ($data['hidden_awageringformula'] != $old->WageringFormula) ? "Wagering Formula changed to " . $data['hidden_awageringformula'] . " from " . $old->WageringFormula . "|||" : "";
                $changes .= ($data['act_turnoveramount'] != $old->TurnoverAmount) ? "Turnover Amount changed to " . $data['act_turnoveramount'] . " from " . $old->TurnoverAmount . "|||" : "";
                $changes .= ($data['act_cashbackamount'] != $old->CashbackAmount) ? "Cashback Amount changed to " . $data['act_cashbackamount'] . " from " . $old->CashbackAmount . "|||" : "";
                $changes .= ($data['act_regularizeamount'] != $old->RegularizeAmount) ? "Regularize Amount changed to " . $data['act_regularizeamount'] . " from " . $old->RegularizeAmount . "|||" : "";
                
				if(admin_access() || csd_supervisor_access()){
					$post_data['Important'] = $data['act_important'];
                    $post_data['IsComplaint'] = $data['act_iscomplaint'];   
				}
				 
                $changes .= ($data['act_isuploadpm'] != $old->IsUploadPM) ? "Updated No. changed to " . $new_isuploadpm_txt . " from " . $old_isuploadpm_txt . "|||" : "";
				$changes .= ($data['act_tofollowup'] != $old->ToFollowup) ? "To follow up changed to " . $new_tofollowup_txt . " from " . $old_tofollowup_txt . "|||" : "";
				 
                //add attachment to changes 
                if (count($upload_data) > 0) {
                    $attach_txt = (count($upload_data) > 1) ? "Attached files: " : "Attached file: ";
                    foreach ($upload_data as $key => $file) {
                        $attach_txt .= $file['client_name'] . ', ';
                    }//end foreach 
                    $attach_txt = trim(trim($attach_txt), ",") . "|||";
                    $changes .= $attach_txt;
                }

                $new_call = 0;
                if ($old->CallStart != $data[act_callstart]) {
                    $changes .= "Call Start changed to " . $data[act_callstart] . " from " . $old->CallStart . "|||";
                    $new_call = 1;
                } else {
                    //$new_call = 0; 
                }

                if ($old->CallEnd != $data[act_callend]) {
                    $changes .= "Call End changed to " . $data[act_callend] . " from " . $old->CallEnd . "|||";
                    $new_call = 1;
                } else {
                    //$new_call = 0;  
                }

                $changes .= ($data['act_callresult'] != $old->CallResultID) ? "Call Result changed to " . $data['hidden_acallresult'] . " from " . $old->ResultName . "|||" : ""; 
                $changes .= ($data['act_calloutcome'] != $old->CallOutcomeID) ? "Call Outcome changed to " . $data['hidden_acalloutcome'] . " from " . $old->OutcomeName . "|||" : "";
				$changes .= ($data['act_callresultcategory'] != $old->CallResultCategoryID) ? "Call Result Category changed to " . $data['hidden_acallresultcategory'] . " from " . $old->CallResultCategoryName . "|||" : ""; 
                $changes .= ($data['act_callsendsms'] != $old->CallSendSMS) ? "Call Send SMS changed to " . $new_callsmstxt . " from " . $old_callsmstxt . "|||" : "";
                $changes .= ($data['act_callsendemail'] != $old->CallSendEmail) ? "Call Send Email changed to " . $new_callemailtxt . " from " . $old_callemailtxt . "|||" : "";
                $changes .= ($data['act_callproblem'] != $old->CallProblem) ? "Call Problem changed to " . $new_callprobtxt . " from " . $old_callprobtxt . "|||" : "";

                $main_updated = ($changes) ? '1' : '0';
                $changes .= ($data['act_status'] != $old->Status) ? "Status changed to " . $data['hidden_astatus'] . " from " . $old->StatusName . "|||" : "";


                //HISTORY DATA 
                $act_important = ($data['act_important'] != $old->Important) ? $data['act_important'] : 0;
                $act_iscomplaint = ($data['act_iscomplaint'] != $old->IsComplaint) ? $data['act_iscomplaint'] : 0;

                $history_data = array(
                    'ActivityID' => $old->ActivityID,
                    'Activity' => $this->activity_type,
                    'Status' => $data['act_status'],
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'Important' => $act_important,
                    'IsComplaint' => $act_iscomplaint,
                    'MainUpdated' => $main_updated,
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'GroupAssignee' => $data['act_assignee']
                );

                //if($data['act_important'] != $old->Important) $history_data['Important'] = $data['act_important']; 
                //if($data['act_iscomplaint'] != $old->IsComplaint) $history_data['IsComplaint'] = $data['act_iscomplaint'];

                if ($data['act_remarks']) {
                    //$changes .= ($data['act_remarks'] != $old->Remarks)?"Remarks changed to ".$data['act_remarks']." from ".$old->Remarks."|||":"";	 
                    $changes .= "Remarks changed to " . $data['act_remarks'] . " from " . $old->Remarks . "|||";
                    $post_data['Remarks'] = trim($data['act_remarks']);
                    $history_data['Remarks'] = trim($data['act_remarks']);
                }
                $history_data['Changes'] = $changes;

                //save attacment; 
                $attach = $this->saveAttachment($upload_data, array("last_id" => $old->ActivityID, "current_date" => $current_date, "activity_type" => $this->activity_type, "caption" => ""), "csa_attach_file");

                $uploaded_id = ($attach > 0) ? $old->ActivityID : "";

                if ($changes != "") {
                    $x = $this->promotions->manageActivity_("csa_promotion_activities", $post_data, $action, "ActivityID", $this->input->post("hidden_activityid"));
                    if ($x > 0) {

                        //INSERT TO CALLS
                        if ($data['act_callstart'] != "" && $data['act_callend'] != "" && $data['act_calloutcome'] && $data['act_callresult'] && $new_call == 1) {
                            $call_data = array(
                                'ActivityID' => $old->ActivityID,
                                'Activity' => $this->activity_type,
                                'AddedBy' => $this->session->userdata("mb_no"),
                                'DateAdded' => $current_date,
                                'UpdatedBy' => $this->session->userdata("mb_no"),
                                'DateUpdated' => $current_date,
                                'DateAddedInt' => strtotime($current_date),
                                'DateUpdatedInt' => strtotime($current_date),
                                'CallStart' => $data['act_callstart'],
                                'CallEnd' => $data['act_callend'],
                                'CallOutcomeID' => $data['act_calloutcome'],
                                'CallResultID' => $data['act_callresult'],
                                'CallSendSMS' => $data['act_callsendsms'],
                                'CallSendEmail' => $data['act_callsendemail'],
                                'CallProblem' => $data['act_callproblem'],
                                'CustomerRemarks' => trim($data['act_custremarks']),
                            );
                            $call_add = $this->promotions->manageActivity_("csa_calls", $call_data, "add", '', '');
                        }
                        //END INSERT TO CALLS  

                        $return = array("success" => 1, "message" => "Promotional activity updated successfully.", "uploaded_id" => $uploaded_id, "is_change" => 1);
                        $y = $this->promotions->manageActivity_("csa_activities_history", $history_data, "add", '', '');
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
        $activity = ($activity_id) ? $this->promotions->getActivityById_($conditions_array) : "";

        $data2 = array("main_page" => "access",
            "status_list" => $this->common->getStatusList_(3), //3 promotions
            "activity" => $activity,
            "activity_id" => $activity_id
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activity Update Status ",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('promotions/promotion_status_popup_tpl', $data);
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
                $return = array("success" => 0, "message" => "Error updating activity!");
            } else {
                $post_data = array(
                    'UpdatedBy' => $this->session->userdata("mb_no"),
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' =>strtotime($current_date),
                    'Remarks' => trim($data['act_remarks']),
                    'Status' => $data['act_status'],
                    'GroupAssignee' => $data['act_assignee']
                );

                $old = $this->promotions->getActivityById_(array("a.ActivityID =" => $this->input->post("hidden_activityid")));
 				 
				//CALL API FROM INTERTNAL SYSTEM  
 				if(trim($data[act_requesttocredit]) == 1 && ($this->common->internal_system_api["can_submit"] === true) )
				 { 
				 	 if($old->RequestBonusID != 0 )
					  {
						 $return = array("success" =>0, "message" => "Bonus already requested to Internal System. Controller 100234", "is_change" => 0);
						 echo json_encode($return); 
						 return false;  
					  }
					  
					  
					 //CALL API http://testinternal-api.zzs33.com/api/CreditBonus 
					 $transaction_id = preg_replace('/[^0-9]+/', '', $old->TransactionID); 
					 $casino_transferid = preg_replace('/[^0-9]+/', '', $old->CasinoTransferID);  
					 
					 $deposit_amt = number_format($old->DepositAmount, 2); 
					 $bonus_amt = number_format($old->BonusAmount, 2);
					 $wagering_amt = number_format($old->WageringAmount, 2);
					 
					 $remarks_str = "Promotion: {$old->PromotionName} \r\nBonus Code: {$old->BonusCode} \r\Deposit ID: {$transaction_id} \r\nCasino Transfer ID: {$casino_transferid} \r\nSystem ID: {$old->SystemID} \r\nDeposit Amount: {$deposit_amt} \r\nBonus Amount: {$bonus_amt} \r\nWagering Amount: {$wagering_amt} \r\n "; 
					  
					 $api_data = array("Username"=>trim($old->Username), 
									   "BonusAmount"=>$old->BonusAmount,
									   "BonusCode"=>trim($old->BonusCode),
									   "DepositID"=>trim($transaction_id),
									   "Remarks"=>trim($remarks_str),
									   "UpdatedBy"=>$this->session->userdata("mb_internal_user"),
									   "ActivityID"=>$old->ActivityID
									  );  
					  
					$api_return = call_api("POST", trim($this->common->internal_system_api["url"]), $api_data);    
					$api = json_decode($api_return);    
					 
					if($api->Success <= 0 || !isset($api->Success))
					 { 
						$return = array("success" =>0, "message" => "Not submitted in Internal System. ".$api->Message, "is_change" => 0);
						echo json_encode($return); 
						return false; 
					 }
					else
					 {
						 $post_data[RequestBonusID] = $api->LastID;
						 $post_data[RequestBonusBy] = $this->session->userdata("mb_no");
						 $post_data[DateBonusRequested] = strtotime($current_date);
						 $submitted_to_internal = 1; 
					 }
					
				 }
			 	//END CALL API FROM INTERTNAL SYSTEM 
				 
                $changes = "";
                $changes .= ($data['act_assignee'] != $old->GroupAssignee) ? "Group Assignee changed to " . $data['hidden_aassignee'] . " from " . $old->GroupAssigneeName . "|||" : "";
                $changes .= ($data['act_remarks']) ? "Remarks changed to " . $data['act_remarks'] . " from " . $old->Remarks . "|||" : "";
                $changes .= ($data['act_status'] != $old->Status) ? "Status changed to " . $data['hidden_astatus'] . " from " . $old->StatusName . "|||" : "";
				
				if($submitted_to_internal == 1) $changes .= "{$this->session->userdata(mb_nick)} requested to credit bonus via Internal System API|||"; 
				
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
                    $x = $this->promotions->manageActivity_("csa_promotion_activities", $post_data, $action, "ActivityID", $this->input->post("hidden_activityid"));
                    if ($x > 0) {
                        $return = array("success" => 1, "message" => "Activity updated successfully.", "is_change" => 1);
                        $y = $this->promotions->manageActivity_("csa_activities_history", $history_data, "add", '', '');
                    } else {
                        $return = array("success" => 0, "message" => "Error updating activity!");
                    }
                } else {
                    //no changes    
                    $return = array("success" => 1, "message" => "No changes made.", "is_change" => 0);
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
        $activity = $this->promotions->getActivityById_($conditions_array);

        if (count($activity) <= 0) {
            error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
            return false;
        } else {
            $view_only = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy == $this->session->userdata('mb_no')) ) ? 0 : 1;
        }

        $data2 = array("main_page" => "promotions",
            "status_list" => $this->common->getStatusList_(3), //3 for promotion
            "activity" => $activity,
            "activity_id" => $activity_id,
            "attachments" => $this->common->displayUploaded_(array("ActivityID =" => $activity_id, "Activity =" => $this->activity_type, "Status =" => '1')),
            "histories" => $this->common->getHistoryRemarks(array("a.ActivityID =" => $activity_id, "a.Activity =" => $this->activity_type)),
            "view_only" => $view_only,
            "assignees" => $this->common->getAssignees_($this->session->userdata('mb_usertype'), $activity->GroupAssignee),
            "settings_ids" => $this->common->ids,
            "issue_allow_regularize" => $this->issue_allow_regularize
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activity",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('promotions/promotional_activity_details_popup_tpl', $data);
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
 

        if ($data[s_fromdateuploaded] == '1') {//IF FROM CRM CALLS 
            $excel_data = array("DateAdded" => "Date Added",
                "DateUpdated" => "Date Updated",
                "Currency" => "Currency",
                "Username" => "Username",
                "ProductName" => "Product",
                "CategoryName" => "Category Name",
                "IssueName" => "Issue",
                "PromotionName" => "Promotion",
				"BonusCode" => "Code",
                "SystemID" => "System ID",
                "ESupportID" => "E-Support ID",
                "ActivitySource" => "Source",
                "TransactionID" => "Deposit Transaction ID",
                "CurrentBalance" => "Current Balance",
                "OutstandingBets" => "Outstanding Bets",
                "DepositAmount" => "Deposit Amount",
                "BonusAmount" => "Bonus Amount",
                "CashbackAmount" => "Cashback Amount",
                "BonusDeduct" => "Bonus Deduct",
                "WinningsDeduct" => "Winnings Deduct",
                "RegularizeAmount" => "Regularize Amount",  
				"Important" => "Important",
				"IsComplaint" => "Is Complaint",  
				"IsUploadPM" => "Updated No.",
				"ToFollowup" => "To Follow Up",
                "StatusName" => "Status",
				"OfferedByName" => "Offered By",
                "Remarks" => "Remarks",
                "AmeyoCallerID" => "Ameyo Caller ID",
                "CallOutcomeName" => "Call Outcome",
                "CallResultName" => "Call Result",
				"CallResultCategoryName" => "Result Category", 
                "CallProblem" => "Call Category",
                "CustomerRemarks" => "Customer Remarks",
                "CreatedByNickname" => "Created By",
                "mb_nick" => "Last Updated By"
            );
        } else {
            $excel_data = array("DateAdded" => "Date Added",
                "DateUpdated" => "Date Updated",
                "Currency" => "Currency",
                "Username" => "Username",
                "ProductName" => "Product",
                "CategoryName" => "Category Name",
                "IssueName" => "Issue",
                "PromotionName" => "Promotion", 
				"BonusCode" => "Code",
                "SystemID" => "System ID",
                "ESupportID" => "E-Support ID",
                "ActivitySource" => "Source",
                "TransactionID" => "Deposit Transaction ID",
                "CurrentBalance" => "Current Balance",
                "OutstandingBets" => "Outstanding Bets",
                "DepositAmount" => "Deposit Amount",
                "BonusAmount" => "Bonus Amount",
                "CashbackAmount" => "Cashback Amount",
                "BonusDeduct" => "Bonus Deduct",
                "WinningsDeduct" => "Winnings Deduct",
                "RegularizeAmount" => "Regularize Amount", 
				"Important" => "Important",
				"IsComplaint" => "Is Complaint",
                "IsUploadPM" => "Updated No.", 
				"ToFollowup" => "To Follow Up",
                "StatusName" => "Status", 
				"OfferedByName" => "Offered By",
                "Remarks" => "Remarks",
                "AmeyoCallerID" => "Ameyo Caller ID",
                "CallOutcomeName" => "Call Outcome",
                "CallResultName" => "Call Result", 
				"CallResultCategoryName" => "Result Category", 
                "CallProblem" => "Call Category",   
                "CustomerRemarks" => "Customer Remarks",
                "CreatedByNickname" => "Created By",
                "mb_nick" => "Last Updated By"
            );
        }
        $force_str = array("Username", "AmeyoCallerID");

        //delete_old_files("media/temp/", "*.xls");  
        delete_old_files($this->common->temp_file, "*.xls");
        $file_name = "promotional_activities" . '-' . date("Ymdhis") . ".xls";
        $title = "Promotional Activities";

        //load our new PHPExcel library
        $this->load->library('excel');

        // Initiate cache
        //$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;//cache_in_memory; //cache_to_phpTemp;
        //$cacheSettings = array( 'memoryCacheSize' => '256MB');
        //PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        //if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings))die('CACHEING ERROR');
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
            $activity->IsUploadPM = ($activity->IsUploadPM == 1) ? "YES" : "NO";
			$activity->ToFollowup = ($activity->ToFollowup == 1) ? "YES" : "NO";

            $activity->CallStart = ($activity->CallStart != "0000-00-00 00:00:00") ? date("F d, Y H:i:s", strtotime($activity->CallStart)) : "";
            $activity->CallEnd = ($activity->CallEnd != "0000-00-00 00:00:00") ? date("F d, Y H:i:s", strtotime($activity->CallEnd)) : "";
            $activity->CallSendSMS = ($activity->CallSendSMS == 1) ? "YES" : "NO";
            $activity->CallSendEmail = ($activity->CallSendEmail == 1) ? "YES" : "NO";
            $activity->CallProblem = ucwords(str_replace("_", " ", $activity->CallProblem));
            //$activity->CallDuration = gmdate("H:i:s", $activity->CallDuration); 

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
        $filePath = $this->common->temp_file; //"media/temp/"
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
        $result = $this->common->getUserStatusViews(3); //3-promotions
        $view_statuslist = explode(',', $result->StatusList);

        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
        }
        $categories = $this->promotions->getPromotionsCategoriesList_($categories_where);
        $cat_ids = implode(',', array_map(function($object) {
                    return $object->CategoryID;
                }, $categories));

        $s_fromdate = strtotime(trim($data[s_fromdate]));
        $s_todate = strtotime(trim($data[s_todate]));

        $search_string = "";
        $allow_close = 0;
        $allow_view = 0;
        $use_index2 = 0;

        if (trim($data[s_currency])) {
            $search_string .= " AND (a.Currency='" . $this->common->escapeString_(trim($data[s_currency])) . "') ";
            $search_url .= "&s_currency=" . trim($data[s_currency]);
            $allow_close++;
        } else {
            $search_string .= " AND a.Currency IN({$this->session->userdata(mb_currencies)})";
        }

        if (trim($data[s_status]) != "") {
            $search_string .= " AND (a.Status='" . trim($data[s_status]) . "') ";
            $search_url .= "&s_status=" . trim($data[s_status]);
            $allow_close++;
        } else {
            
        }

        if (trim($data[s_dashboard]) && $allow_close == 0) {
            /* $search_string .= " AND ( (a.Status<>{$this->common->ids[close_status]}) AND (a.Status<>{$this->common->ids[crm_note_status]}) AND (a.Status<>{$this->common->ids[deposited_status]}) )  AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') )  ";   */
            $search_string .= " AND (a.Status NOT IN({$this->common->notcount_status})) AND ((a.IsUpload='1' AND a.Status<>{$this->common->ids[new_status]}) OR (a.IsUpload='0') ) ";
            if (!super_admin()) {
                $search_string .= " AND ((a.GroupAssignee='" . trim($this->session->userdata("mb_usertype")) . "') )";
            }
        } else {
            if (restriction_type() && ($allow_view == 0)) {
                //$search_string .= " AND ((a.GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (a.AddedBy='".trim($this->session->userdata("mb_no"))."') )";  
            }
        }

        if (trim($data[s_assignee]) != "") {
            $search_string .= " AND (a.GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
            $allow_view++;
        }

        if (trim($data[s_promotion])) {
            if (trim($data[s_promotion]) == "N/A") {
                //$search_string .= " AND (h.Name LIKE '%".$this->common->escapeString_($data[s_promotion])."%') "; 
            } else {
                $search_string .= " AND (a.Promotion='" . $this->common->escapeString_($data[s_promotion]) . "') ";
            }

            //$search_string .= " AND (a.Promotion='".$this->common->escapeString_($data[s_promotion])."') "; 
            $search_url .= "&s_promotion=" . trim($data[s_promotion]);
            $allow_close++;
            $allow_view++;
        }

        if (trim($data[s_categories])) {
            $search_string .= " AND (a.Category='" . $this->common->escapeString_($data[s_categories]) . "') ";
            $search_url .= "&s_category=" . $s_category;
            $allow_close++;
        } else {
            if (restriction_type()) {
                $search_string .= " AND a.Category IN({$cat_ids}) ";
                $search_url .= "&s_category=" . $s_category;
            }
        }

        if (trim($data[s_issue])) {
            $search_string .= " AND (a.Issue='" . $this->common->escapeString_($data[s_issue]) . "') ";
            $search_url .= "&s_issue=" . $s_issue;
            $allow_close++;
        }

        if (trim($data[s_source])) {
            $search_string .= " AND (a.Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        //for call search 
        if (trim($data[s_calloutcome])) {
            $search_string .= " AND (a.CallOutcomeID='" . $this->common->escapeString_($data[s_calloutcome]) . "') ";
            $search_url .= "&s_calloutcome=" . $s_calloutcome;
            $allow_close++;
        }

        //FOR CRM CALL
        if (trim($data[s_call]) == '1') {
            $search_string .= " AND (a.CallOutcomeID<>0 AND a.CallResultID<>0)  ";
            $search_url .= "&s_call=" . trim($data[s_call]);
            $allow_close++;
        }

        if (trim($data[s_callproblem])) {
            $search_string .= " AND (a.CallProblem='" . $this->common->escapeString_($data[s_callproblem]) . "') ";
            $search_url .= "&s_callproblem=" . $s_callproblem;
            $allow_close++;
        }

        if (trim($data[s_callsendsms]) == 1) {
            $search_string .= " AND (a.CallSendSMS='" . $this->common->escapeString_($data[s_callsendsms]) . "') ";
            $search_url .= "&s_callsendsms=" . $s_callsendsms;
            $allow_close++;
        }

        if (trim($data[s_callsendemail]) == 1) {
            $search_string .= " AND (a.CallSendEmail='" . $this->common->escapeString_($data[s_callsendemail]) . "') ";
            $search_url .= "&s_callsendemail=" . $s_callsendemail;
            $allow_close++;
        }

        if (trim($data[s_custremarks]) == '1') {
            $search_string .= " AND (a.CustomerRemarks<>'') ";
            $search_url .= "&s_custremarks=" . trim($$data[s_custremarks]);
            $allow_close++;
            $use_index2++;
        }
        //end for call search 

        if (trim($data[s_important]) == '1') {
            $search_string .= " AND (a.Important='" . $this->common->escapeString_($data[s_important]) . "') ";
            $search_url .= "&s_important=" . $$data[s_important];
            $allow_close++;
        }

        if (trim($data[s_iscomplaint]) == '1') {
            $search_string .= " AND (a.IsComplaint='" . $this->common->escapeString_(trim($data[s_iscomplaint])) . "') ";
            $search_url .= "&s_iscomplaint=" . trim($$data[s_iscomplaint]);
            $allow_close++;
        }

        if (trim($data[s_uploadpm]) == 1) {
            $search_string .= " AND (a.IsUploadPM='" . $this->common->escapeString_($data[s_uploadpm]) . "') ";
            $search_url .= "&s_uploadpm=" . $$data[s_uploadpm];
            $allow_close++;
        }

        if (trim($data[s_isuploaded]) == 1) {
            $search_string .= " AND (a.IsUpload='" . $this->common->escapeString_($data[s_isuploaded]) . "') ";
            $search_url .= "&s_isuploaded=" . $$data[s_isuploaded];
            $allow_close++;
        }

        /* if(trim($data[s_esupportid]))
          {
          $search_string .= " AND (a.ESupportID LIKE '%".$this->common->escapeString_(trim($data[s_esupportid]))."%') ";
          $search_url .= "&s_esupportid=".trim($data[s_esupportid]);
          $allow_close++;
          } */

        if (trim($data[s_username])) {
            $search_string .= " AND (a.Username LIKE '%" . $this->common->escapeString_(trim($data[s_username])) . "%') ";
            $search_url .= "&s_username=" . trim($data[s_username]);
            $allow_close++;
        }

        if (trim($data[s_transactionid])) {
            $search_string .= " AND (a.TransactionID LIKE '%" . $this->common->escapeString_(trim($data[s_transactionid])) . "%') ";
            $search_url .= "&s_transactionid=" . trim($data[s_transactionid]);
            $allow_close++;
        }

        /* if(trim($data[s_problem]))
          {
          $search_string .= " AND (a.Problem='".$this->common->escapeString_(trim($data[s_problem]))."') ";
          $search_url .= "&s_problem=".trim($data[s_problem]);
          $allow_close++;
          } */

        if ($s_fromdate && $s_todate) {
            if ($data[s_dateindex] == 'added') {
                $search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = "ActivitiesAddedKey";
                $order_by = "a.DateAddedInt";
            } elseif ($data[s_dateindex] == 'updated') {
                $search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2";
                $order_by = "a.DateUpdatedInt";
            } elseif ($data[s_dateindex] == 'uploaded') {
                $search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = ($use_index2 == 0) ? "ActivitiesUploadedKey" : "ActivitiesUploadedKey2";
                $order_by = "a.DateUploadedInt";
            } else {
                $search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2";
                $order_by = "a.DateUpdatedInt";
            }

            $search_url .= "&s_fromdate=" . urlencode(trim($data[s_fromdate])) . "&s_todate=" . urlencode(trim($data[s_todate])) . "&s_dateindex=" . urlencode(trim($data[s_dateindex]));
        } else {
            if ($data[s_dateindex] == 'added') {
                $index = ($use_index2 == 0) ? "ActivitiesAddedKey" : "ActivitiesAddedKey2";
                $order_by = "a.DateAddedInt";
            } elseif ($data[s_dateindex] == 'updated') {
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2";
                $order_by = "a.DateUpdatedInt";
            } elseif ($data[s_dateindex] == 'uploaded') {
                $index = ($use_index2 == 0) ? "ActivitiesUploadedKey" : "ActivitiesUploadedKey2";
                $order_by = "a.DateUploadedInt";
            } else {
                $index = ($use_index2 == 0) ? "ActivitiesUpdatedKey" : "ActivitiesUpdatedKey2"; //$data[s_dateindex];  
                $order_by = "a.DateUpdatedInt";
            }
        }

        if (trim($data[s_warningdays]) == 1) {
            $allow_close = 0;
        }

       

        $search_string = ($allow_close <= 0 && $this->common->display_close_ticket == 1) ? $search_string . " AND (a.Status NOT IN ({$this->common->hide_status}) ) " : $search_string;
        $search_string = trim(trim($search_string), "AND");

        $per_page = 20;
        //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        $page = ($data['s_page']) ? $data['s_page'] : 0;
        //$total_rows = $this->common->countSearchActivities_($search_string, "csa_promotion_activities", "promotion", $index)->CountActivity; 
        //$activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index);

        $return = $activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, array(), $index, $order_by);
        $total_rows = $return[total_rows];
        $activities = $return[result];
  
        if ($data[s_fromdateuploaded] == '1') {//IF FROM CRM CALLS 
            $excel_data = array("DateAdded" => "Date Added",
                "DateUpdated" => "Date Updated",
                "Currency" => "Currency",
                "Username" => "Username",
                "ProductName" => "Product",
                "CategoryName" => "Category Name",
                "IssueName" => "Issue",
                "PromotionName" => "Promotion",
                "SystemID" => "System ID",
                "ESupportID" => "E-Support ID",
                "ActivitySource" => "Source",
                "TransactionID" => "Transaction ID",
                "CurrentBalance" => "Current Balance",
                "OutstandingBets" => "Outstanding Bets",
                "DepositAmount" => "Deposit Amount",
                "BonusAmount" => "Bonus Amount",
                "CashbackAmount" => "Cashback Amount",
                "BonusDeduct" => "Bonus Deduct",
                "WinningsDeduct" => "Winnings Deduct",
                "RegularizeAmount" => "Regularize Amount",
                "IsUploadPM" => "Updated No.",
                "StatusName" => "Status",
				"OfferedByName" => "Offered By",
                "Remarks" => "Remarks",
                "AmeyoCallerID" => "Ameyo Caller ID",
                "CallOutcomeName" => "Call Outcome",
                "CallResultName" => "Call Result",
                "CallProblem" => "Call Category",
                "CustomerRemarks" => "Customer Remarks",
                "CreatedByNickname" => "Created By",
                "mb_nick" => "Last Updated By"
            );
        } else {
            $excel_data = array("DateAdded" => "Date Added",
                "DateUpdated" => "Date Updated",
                "Currency" => "Currency",
                "Username" => "Username",
                "ProductName" => "Product",
                "CategoryName" => "Category Name",
                "IssueName" => "Issue",
                "PromotionName" => "Promotion",
                "SystemID" => "System ID",
                "ESupportID" => "E-Support ID",
                "ActivitySource" => "Source",
                "TransactionID" => "Transaction ID",
                "CurrentBalance" => "Current Balance",
                "OutstandingBets" => "Outstanding Bets",
                "DepositAmount" => "Deposit Amount",
                "BonusAmount" => "Bonus Amount",
                "CashbackAmount" => "Cashback Amount",
                "BonusDeduct" => "Bonus Deduct",
                "WinningsDeduct" => "Winnings Deduct",
                "RegularizeAmount" => "Regularize Amount",
                "IsUploadPM" => "Updated No.",
                "StatusName" => "Status", 
				"OfferedByName" => "Offered By",
                "Remarks" => "Remarks",
                "AmeyoCallerID" => "Ameyo Caller ID",
                "CallOutcomeName" => "Call Outcome",
                "CallResultName" => "Call Result",
                "CallProblem" => "Call Category",
                "CustomerRemarks" => "Customer Remarks",
                "CreatedByNickname" => "Created By",
                "mb_nick" => "Last Updated By"
            );
        }
        $force_str = array("Username", "AmeyoCallerID");

        //delete_old_files("media/temp/", "*.xls");  
        delete_old_files($this->common->temp_file, "*.xls");
        $file_name = "promotional_activities" . '-' . date("Ymdhis") . ".xls";
        $title = "Promotional Activities";

        //load our new PHPExcel library
        $this->load->library('excel');

        // Initiate cache
        //$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_discISAM;//cache_in_memory; //cache_to_phpTemp;
        //$cacheSettings = array( 'memoryCacheSize' => '256MB');
        //PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        //if (!PHPExcel_Settings::setCacheStorageMethod($cacheMethod,$cacheSettings))die('CACHEING ERROR');
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
            $activity->IsUploadPM = ($activity->IsUploadPM == 1) ? "YES" : "NO";

            $activity->CallStart = ($activity->CallStart != "0000-00-00 00:00:00") ? date("F d, Y H:i:s", strtotime($activity->CallStart)) : "";
            $activity->CallEnd = ($activity->CallEnd != "0000-00-00 00:00:00") ? date("F d, Y H:i:s", strtotime($activity->CallEnd)) : "";
            $activity->CallSendSMS = ($activity->CallSendSMS == 1) ? "YES" : "NO";
            $activity->CallSendEmail = ($activity->CallSendEmail == 1) ? "YES" : "NO";
            $activity->CallProblem = ucwords(str_replace("_", " ", $activity->CallProblem));
            //$activity->CallDuration = gmdate("H:i:s", $activity->CallDuration); 

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
        $filePath = $this->common->temp_file; //"media/temp/"
        $objWriter->save($filePath . $file_name);

        $return = (file_exists($filePath . $file_name)) ? array("success" => 1, "message" => "Downloading file.", "download_link" => encode_string($filePath . $file_name)) : array("success" => 0, "message" => "Error downloading file.", "download_link" => "");
 
        echo json_encode($return);
    }
	
	//CALL DETAILS
    public function callDetails() {

        if (!admin_access() && !allow_agent_report()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));

        parse_str($params, $data);

        /* $categories_where = " AND a.Status='1' "; 

          if(restriction_type())
          {
          $categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
          } */


        //if($params == "")redirect(base_url("promotions/agent-summary-report"));

        $data2 = array("main_page" => "promotions",
            "currencies" => $this->common->getCurrency_(),
            //"sources"=>$this->common->getSource_(), 
            //"categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where),
            "status_list" => $this->common->getStatusList_(3), //3 for promotion page  
            "outcomes" => $this->promotions->getCallOutcomeList_(array("a.outcome_status =" => '1')),
            "sdata" => $data,
            "call_agents" => $this->common->getCallAgentList_(array("a.mb_status =" => '1')),
            "utypes" => $this->common->getUsersGroup_(array("Status =" => '1')), //user types
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activities",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('promotions/call_details_tpl');
        $this->load->view('footer');
    }

    public function getPromotionCallDetails($actual = 0) {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_access() && !allow_agent_report()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $data = $this->input->post();
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(3); //3-promotions
        $view_statuslist = explode(',', $result->StatusList);

        /* if(restriction_type())
          {
          $categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
          }
          $categories = $this->promotions->getPromotionsCategoriesList_($categories_where);
          $cat_ids = implode(',', array_map(function($object){return $object->CategoryID;}, $categories)); */

        $s_fromdate = strtotime(trim($data[s_fromdate]));
        $s_todate = strtotime(trim($data[s_todate]));

        $search_string = "";
        $search_string2 = "";
        $allow_close = 0;

        if (trim($s_activity)) {
            $search_string .= " AND (Activity='" . $this->common->escapeString_($s_activity) . "') ";
            $search_url .= "&s_activity=" . $s_activity;
        }

        /* if(trim($data[s_categories]))
          {
          $search_string .= " AND (a.Category='".$this->common->escapeString_($data[s_categories])."') ";
          $search_url .= "&s_category=".$s_category;
          $allow_close++;
          }
          else
          {
          if(restriction_type())
          {
          $search_string .= " AND FIND_IN_SET(a.Category, '".$cat_ids."') ";  //jaypeexxxx
          $search_url .= "&s_category=".$s_category;
          }
          } */

        if ($s_fromdate && $s_todate) {
            $search_string .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
            $search_url .= "&s_fromdate=" . urlencode(trim($data[s_fromdate])) . "&s_todate=" . urlencode(trim($data[s_todate]));
        }

        if ($data[s_addedby]) {
            $search_string .= " AND (AddedBy='" . $this->common->escapeString_($data[s_addedby]) . "') ";
            $search_url .= "&s_addedby=" . $s_addedby;
        }


        if (trim($data[s_important]) == '1') {
            $search_string2 .= " AND (Important='" . $this->common->escapeString_($data[s_important]) . "') ";
            $search_url .= "&s_important=" . $$data[s_important];
            $allow_close++;
        }

        if (trim($data[s_iscomplaint]) == '1') {
            $search_string2 .= " AND (IsComplaint='" . $this->common->escapeString_(trim($data[s_iscomplaint])) . "') ";
            $search_url .= "&s_iscomplaint=" . trim($$data[s_iscomplaint]);
            $allow_close++;
        }

        if (trim($data[s_uploadpm]) == 1) {
            $search_string2 .= " AND (IsUploadPM='" . $this->common->escapeString_($data[s_uploadpm]) . "') ";
            $search_url .= "&s_uploadpm=" . $$data[s_uploadpm];
            $allow_close++;
        }

        if (trim($data[s_isuploaded]) == 1) {
            $search_string2 .= " AND (IsUpload='" . $this->common->escapeString_($data[s_isuploaded]) . "') ";
            $search_url .= "&s_isuploaded=" . $$data[s_isuploaded];
            $allow_close++;
        }

        if (trim($data[s_esupportid])) {
            $search_string2 .= " AND (ESupportID LIKE '%" . $this->common->escapeString_(trim($data[s_esupportid])) . "%') ";
            $search_url .= "&s_esupportid=" . trim($data[s_esupportid]);
            $allow_close++;
        }

        if (trim($data[s_currency])) {
            $search_string2 .= " AND (Currency='" . $this->common->escapeString_(trim($data[s_currency])) . "') ";
            $search_url .= "&s_currency=" . trim($data[s_currency]);
            $allow_close++;
        } else {
            //$search_string .= " AND FIND_IN_SET(a.Currency, '{$this->session->userdata(mb_currencies)}') ";  
        }

        if (trim($data[s_username])) {
            $search_string2 .= " AND (Username LIKE '%" . $this->common->escapeString_(trim($data[s_username])) . "%') ";
            $search_url .= "&s_username=" . trim($data[s_username]);
            $allow_close++;
        }


        if (trim($data[s_promotion])) {
            if ($data[s_promotion] == "N/A") {
                //$search_string .= " AND (h.Name LIKE '%".$this->common->escapeString_($data[s_promotion])."%') "; 
            } else {
                $search_string2 .= " AND (Promotion='" . $this->common->escapeString_($data[s_promotion]) . "') ";
            }
            $search_url .= "&s_promotion=" . $data[s_promotion];
        }


        if (trim($data[s_transactionid])) {
            $search_string2 .= " AND (TransactionID LIKE '%" . $this->common->escapeString_(trim($data[s_transactionid])) . "%') ";
            $search_url .= "&s_transactionid=" . trim($data[s_transactionid]);
            $allow_close++;
        }

        if (trim($data[s_source])) {
            $search_string2 .= " AND (Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        if (trim($data[s_problem])) {
            $search_string2 .= " AND (Problem='" . $this->common->escapeString_(trim($data[s_problem])) . "') ";
            $search_url .= "&s_problem=" . trim($data[s_problem]);
            $allow_close++;
        }


        if (trim($data[s_status]) != "") {
            $search_string2 .= " AND (Status='" . trim($data[s_status]) . "') ";
            $search_url .= "&s_status=" . trim($data[s_status]);
            $allow_close++;
        } else {
            /* if(restriction_type())
              {
              $search_string .= " AND FIND_IN_SET(a.Status, '".implode(',', $view_statuslist)."') ";
              $search_url .= "&s_status=".trim($data[s_status]);
              } */
        }

        if (trim($data[s_assignee]) != "") {
            $search_string2 .= " AND (GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
        } else {
            if (restriction_type()) {
                //$search_string2 .= " AND ((GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (AddedBy='".trim($this->session->userdata("mb_no"))."') )";   
                $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            }
        }


        /* if(trim($data[s_agent]) != "")
          {
          $search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";
          $search_url .= "&s_agent=".trim($data[s_agent]);
          $allow_close++;
          } */

        //$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string;

        $search_string = trim(trim($search_string), "AND");
        //$search_string2 = trim(trim($search_string), "AND");

        $per_page = 20;
        //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        $page = ($data['s_page']) ? $data['s_page'] : 0;

        $return = $this->promotions->getPromotionCallDetails_($search_string, $search_string2, $view_statuslist, $paging = array("limit" => $per_page, "page" => $page));
        $total_rows = $return[total_rows];
        $activities = $return[result];


        $pagination_options = array("link" => "", //base_url()."promotions/activities", 
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
            $return = array("activities" => $this->generateCallDetailsHtmlList($activities),
                "pagination" => create_pagination($pagination_options),
                "pagination_string" => $pagination_string,
                "records" => $total_rows
            );
            echo json_encode($return);
        }
    }

    public function generateCallDetailsHtmlList($activities) {
        $return = "";
        if (count($activities)) {
            foreach ($activities as $row => $activity) {
                $is_important = ($activity->Important == 1) ? " act-danger " : "";
                $return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" >  
							<td class=\"center\" >{$activity->Currency}</td>
							<td class=\"center {$is_important}\" >{$activity->Username}</td> 
							<td class=\"\">{$activity->PromotionName}</td> 
							<td class=\"center\">{$activity->TransactionID}</td> 
							<td class=\"right\">{$activity->DepositAmount}</td>
							<td class=\"right\">{$activity->BonusAmount}</td>
							<td class=\"right\">{$activity->WageringAmount}</td>
							<td class=\"center\" >
									" . ucwords($activity->StatusName) . "
							</td>
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\"  target=\"CsaContentDetails\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							";

                //check if usertype allowed to edit activity
                //if(allowEditMain())$return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
                //check if attachment
                if ($activity->CountAttach > 0)
                    $return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0 gap-right10\" ></i></a>";

                //check if call
                if (($activity->CallStart != "0000-00-00 00:00:00" && $activity->CallStartDetail != "" && $activity->CallEndDetail != "0000-00-00 00:00:00" && $activity->CallEnd != ""))
                    $return .= "<a href=\"#ActivityDetailsModal\" title=\"view call info\" alt=\"view call info\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"ViewCall{$activity->ActivityID}\"  data-toggle=\"modal\" call-info=\"1\" target=\"CrmContentDetails\" ><i class=\"icon16 i-phone-2 gap-left0\" ></i></a>";

                $return .= "
							</td>
						</tr> ";
            }//end foreach
        }
        else {
            $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"10\" >No activity found!</td>
						</tr>
			 			";
        }

        return $return;
    }

    public function exportPromotionsCallDetails($actual = 0) {
        $data = $this->input->post();
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(3); //3-promotions
        $view_statuslist = explode(',', $result->StatusList);


        $s_fromdate = strtotime(trim($data[s_fromdate]));
        $s_todate = strtotime(trim($data[s_todate]));

        $search_string = "";
        $search_string2 = "";
        $allow_close = 0;

        if (trim($s_activity)) {
            $search_string .= " AND (Activity='" . $this->common->escapeString_($s_activity) . "') ";
            $search_url .= "&s_activity=" . $s_activity;
        }

        /* if(trim($data[s_categories]))
          {
          $search_string .= " AND (a.Category='".$this->common->escapeString_($data[s_categories])."') ";
          $search_url .= "&s_category=".$s_category;
          $allow_close++;
          }
          else
          {
          if(restriction_type())
          {
          $search_string .= " AND FIND_IN_SET(a.Category, '".$cat_ids."') ";  //jaypeexxxx
          $search_url .= "&s_category=".$s_category;
          }
          } */

        if ($s_fromdate && $s_todate) {
            $search_string .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
            $search_url .= "&s_fromdate=" . urlencode(trim($data[s_fromdate])) . "&s_todate=" . urlencode(trim($data[s_todate]));
        }

        if ($data[s_addedby]) {
            $search_string .= " AND (AddedBy='" . $this->common->escapeString_($data[s_addedby]) . "') ";
            $search_url .= "&s_addedby=" . $s_addedby;
        }


        if (trim($data[s_important]) == '1') {
            $search_string2 .= " AND (Important='" . $this->common->escapeString_($data[s_important]) . "') ";
            $search_url .= "&s_important=" . $$data[s_important];
            $allow_close++;
        }

        if (trim($data[s_iscomplaint]) == '1') {
            $search_string2 .= " AND (IsComplaint='" . $this->common->escapeString_(trim($data[s_iscomplaint])) . "') ";
            $search_url .= "&s_iscomplaint=" . trim($$data[s_iscomplaint]);
            $allow_close++;
        }

        if (trim($data[s_uploadpm]) == 1) {
            $search_string2 .= " AND (IsUploadPM='" . $this->common->escapeString_($data[s_uploadpm]) . "') ";
            $search_url .= "&s_uploadpm=" . $$data[s_uploadpm];
            $allow_close++;
        }

        if (trim($data[s_isuploaded]) == 1) {
            $search_string2 .= " AND (IsUpload='" . $this->common->escapeString_($data[s_isuploaded]) . "') ";
            $search_url .= "&s_isuploaded=" . $$data[s_isuploaded];
            $allow_close++;
        }

        if (trim($data[s_esupportid])) {
            $search_string2 .= " AND (ESupportID LIKE '%" . $this->common->escapeString_(trim($data[s_esupportid])) . "%') ";
            $search_url .= "&s_esupportid=" . trim($data[s_esupportid]);
            $allow_close++;
        }

        if (trim($data[s_currency])) {
            $search_string2 .= " AND (Currency='" . $this->common->escapeString_(trim($data[s_currency])) . "') ";
            $search_url .= "&s_currency=" . trim($data[s_currency]);
            $allow_close++;
        } else {
            //$search_string .= " AND FIND_IN_SET(a.Currency, '{$this->session->userdata(mb_currencies)}') ";  
        }

        if (trim($data[s_username])) {
            $search_string2 .= " AND (Username LIKE '%" . $this->common->escapeString_(trim($data[s_username])) . "%') ";
            $search_url .= "&s_username=" . trim($data[s_username]);
            $allow_close++;
        }


        if (trim($data[s_promotion])) {
            if ($data[s_promotion] == "N/A") {
                //$search_string .= " AND (h.Name LIKE '%".$this->common->escapeString_($data[s_promotion])."%') "; 
            } else {
                $search_string2 .= " AND (Promotion='" . $this->common->escapeString_($data[s_promotion]) . "') ";
            }
            $search_url .= "&s_promotion=" . $data[s_promotion];
        }


        if (trim($data[s_transactionid])) {
            $search_string2 .= " AND (TransactionID LIKE '%" . $this->common->escapeString_(trim($data[s_transactionid])) . "%') ";
            $search_url .= "&s_transactionid=" . trim($data[s_transactionid]);
            $allow_close++;
        }

        if (trim($data[s_source])) {
            $search_string2 .= " AND (Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        if (trim($data[s_problem])) {
            $search_string2 .= " AND (Problem='" . $this->common->escapeString_(trim($data[s_problem])) . "') ";
            $search_url .= "&s_problem=" . trim($data[s_problem]);
            $allow_close++;
        }


        if (trim($data[s_status]) != "") {
            $search_string2 .= " AND (Status='" . trim($data[s_status]) . "') ";
            $search_url .= "&s_status=" . trim($data[s_status]);
            $allow_close++;
        } else {
            /* if(restriction_type())
              {
              $search_string .= " AND FIND_IN_SET(a.Status, '".implode(',', $view_statuslist)."') ";
              $search_url .= "&s_status=".trim($data[s_status]);
              } */
        }

        if (trim($data[s_assignee]) != "") {
            $search_string2 .= " AND (GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
        } else {
            if (restriction_type()) {
                //$search_string2 .= " AND ((GroupAssignee='".trim($this->session->userdata("mb_usertype"))."') OR (AddedBy='".trim($this->session->userdata("mb_no"))."') )";   
                $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            }
        }


        /* if(trim($data[s_agent]) != "")
          {
          $search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";
          $search_url .= "&s_agent=".trim($data[s_agent]);
          $allow_close++;
          } */

        //$search_string = ($allow_close <= 0 && $this->common->display_close_ticket==1)?$search_string." AND (a.Status<>'".$this->common->close_status."') ":$search_string;

        $search_string = trim(trim($search_string), "AND");
        //$search_string2 = trim(trim($search_string), "AND");

        $per_page = 20;
        //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        $page = ($data['s_page']) ? $data['s_page'] : 0;

        //$activities = $this->promotions->getPromotionCallDetails_($search_string, $view_statuslist);
        //$total_rows = count($activities);  

        $return = $this->promotions->getPromotionCallDetails_($search_string, $search_string2, $view_statuslist, array());
        $total_rows = $return[total_rows];
        $activities = $return[result];




        $excel_data = array("DateAdded" => "Date Added",
            "DateUpdated" => "Date Updated",
            "Currency" => "Currency",
            "Username" => "Username",
            "ProductName" => "Product",
            "CategoryName" => "Category Name",
            "PromotionName" => "Promotion",
            //"SystemID"=>"System ID", 
            //"ESupportID"=>"E-Support ID", 
            //"ActivitySource"=>"Source", 
            //"SystemID"=>"System ID", 
            //"TransactionID"=>"Transaction ID", 
            //"CurrentBalance"=>"Current Balance", 
            //"OutstandingBets"=>"Outstanding Bets", 
            //"DepositAmount"=>"Deposit Amount", 
            //"BonusAmount"=>"Bonus Amount", 
            //"WageringAmount"=>"Wagering Amount", 
            //"TurnoverAmount"=>"Turnover Amount", 
            //"CashbackAmount"=>"Cashback Amount",  
            //"Important"=>"Important", 
            //"IsComplaint"=>"Is Complaint", 
            //"StatusName"=>"Status", 
            //"Remarks"=>"Agent Remarks", 
            //"RMRemarks"=>"Risk Management Remarks", 
            //"MRemarks"=>"Management Remarks",  
            //"GroupAssigneeName"=>"Assignee", 
            "mb_nick" => "Agent Name",
            "CallDuration" => "Call Duration",
            "CallStartDetail" => "Call Started",
            "CallEndDetail" => "Call Ended",
            "CallOutcomeName" => "Call Outcome",
            "CallResultName" => "Call Result",
            "CallSendSMS" => "Send SMS",
            "CallSendEmail" => "Send Email",
            "CallProblem" => "Call Category"
        );


        $force_str = array("Username");

        delete_old_files($this->common->temp_file, "*.xls");
        $file_name = "call_details_report" . '-' . date("Ymdhis") . ".xls";
        $title = "Agent Call Details";

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
                ))
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
            $activeSheet->setCellValue($row_cel, $val . ' ');
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

            $activity->Important = ($activity->Important == 1) ? "YES" : "NO";
            $activity->IsComplaint = ($activity->IsComplaint == 1) ? "YES" : "NO";

            $activity->CallStartDetail = ($activity->CallStartDetail != "0000-00-00 00:00:00") ? date("F d, Y H:i:s", strtotime($activity->CallStartDetail)) : "";
            $activity->CallEndDetail = ($activity->CallEndDetail != "0000-00-00 00:00:00") ? date("F d, Y H:i:s", strtotime($activity->CallEndDetail)) : "";
            $activity->CallSendSMS = ($activity->CallSendSMS == 1) ? "YES" : "NO";
            $activity->CallSendEmail = ($activity->CallSendEmail == 1) ? "YES" : "NO";
            $activity->CallProblem = ucwords(str_replace("_", " ", $activity->CallProblem));
            //$activity->CallDuration = gmdate("H:i:s", $activity->CallDuration);

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
    //END CALL DETAILS 
    //SEASRCH ACTIVITIES
    public function searchActivities() {

        if (!admin_access() && !allow_search()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $categories_where = " AND a.Status='1' ";

        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
        }

        //$activities = $this->getActivities($this->input->post());						
        $data2 = array("main_page" => "promotions",
            "currencies" => $this->common->getCurrency_(),
            //"sources"=>$this->common->getSource_(), 
            "categories" => $this->promotions->getPromotionsCategoriesList_($categories_where),
            "status_list" => $this->common->getStatusList_(3), //3 for promotion page  
            "outcomes" => $this->promotions->getCallOutcomeList_(array("a.outcome_status =" => '1')),
            "s_page" => $page
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activities",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('promotions/promotions_activities_tpl');
        $this->load->view('footer');
    }

    public function getCallOutcomeList() {
        $where_arr = array("a.outcome_status" => '1');
        $data = $this->input->post();

        if ($data[result]) // && ($data[result] != $this->common->ids[reached_result])
            $where_arr['a.result_id'] = $data[result];

        $results = $this->promotions->getCallOutcomeList_($where_arr);

        echo json_encode($results);
    }
	
	
	public function getResultCategoriesList() {
        $where_arr = array("a.Status" => '1');
        $data = $this->input->post();

        if ($data[result])
            $where_arr['a.Result'] = $data[result];

        $results = $this->promotions->getResultCategoriesList_($where_arr);

        echo json_encode($results);
    }

    public function userActivities() {

        if (!admin_access() && !view_access()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $params = str_replace("amp;", "", decode_string($this->uri->segment(3)));
        parse_str($params, $sdata);

        $categories_where = " AND a.Status='1' ";

        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
        }

        //$activities = $this->getActivities($this->input->post());						
        $data2 = array(//"main_page"=>"promotions",  
            //"currencies"=>$this->common->getCurrency_(), 
            //"sources"=>$this->common->getSource_(), 
            //"categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where), 
            //"issues"=>$this->promotions->getPromotionIssues_(array("a.Status ="=>'1')),  
            //"status_list"=>$this->common->getStatusList_(3),//3 for promotion page  
            //"outcomes"=>$this->promotions->getCallOutcomeList_(array("a.outcome_status ="=>'1')),
            //"utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')),//user types
            "s_page" => $page,
            //"sdata"=>$sdata, 
            "date_index" => $this->common->date_index
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Promotional Activities",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        //$this->load->view('header',$data);
        //$this->load->view('header_nav');
        $this->load->view('promotions/promotions_users_activities_tpl', $data);
        //$this->load->view('footer');   
    }

    public function getUserActivities($actual = 0) {
        $data = $this->input->post();
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(3); //3-promotions
        $view_statuslist = explode(',', $result->StatusList);

        /* if(restriction_type())
          {
          $categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
          }

          $categories = $this->promotions->getPromotionsCategoriesList_($categories_where);
          $cat_ids = implode(',', array_map(function($object){return $object->CategoryID;}, $categories)); */

        //$s_fromdate = strtotime(trim($data[s_fromdate]));  
        //$s_todate = strtotime(trim($data[s_todate]));  

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
            //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
            $page = ($data['s_page']) ? $data['s_page'] : 0;
            //$total_rows = $this->common->countSearchActivities_($search_string, "csa_promotion_activities", "promotion", "Username")->CountActivity; 
            //$activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), "Username");

            $return = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging = array("limit" => $per_page, "page" => $page), "Username", "a.DateUpdatedInt");
            $total_rows = $return[total_rows];
            $activities = $return[result];

            $pagination_options = array("link" => "", //base_url()."promotions/activities", 
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
        $return = "";
        if (count($activities)) {
            foreach ($activities as $row => $activity) {
                $is_important = ($activity->Important == 1) ? " act-danger " : "";
                $promotion_name = $activity->PromotionName;
                $can_edit = (allowEditMain() || can_override($activity->GroupAssignee) || ($activity->AddedBy == $this->session->userdata('mb_no')) ) ? 1 : 0;

                if ($activity->PromotionStartDate != "0000-00-00" && $activity->PromotionEndDate != "0000-00-00" && $activity->PromotionEndDate != "0000-00-00") {
                    $promotion_name = (date("Y-m-d") > $activity->PromotionEndDate) ? "<div class=\"act-danger tip\" title=\"subscription expired\" >" . $activity->PromotionName . "</div>" : $activity->PromotionName;
                    $promotion_name = ($activity->PromotionStatus != 1) ? "<div class=\"act-warning tip\" title=\"promotion is inactive\" id=\"\" >" . $activity->PromotionName . "</div>" : $promotion_name;
                }
                //''
                $return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" >   
							<td  >{$promotion_name}</td> 
							<td class=\"center {$is_important}\" >";
                $return .= ($activity->IsComplaint == '1') ? "<i class=\"icon12 i-warning act-danger tip\" title=\"complaint\" ></i>" : "";
                $return .= "{$activity->TransactionID}</td>";

                $return .= " 
							<td class=\"right\">{$activity->DepositAmount}</td>
							<td class=\"right\">{$activity->BonusAmount}</td>
							<td class=\"right\">{$activity->WageringAmount}</td>
							<td class=\"center green\" >" . ucwords($activity->StatusName) . "</td>
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\"  target=\"CsaContentDetails\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							";
                //$x = (can_edit_upload() && ($activitiy->IsUpload=='1' &&) )
                //check if usertype allowed to edit activity
                if ($can_edit > 0)
                    $return .= "<a href=\"#ActivityModal\" title=\"edit activity\" alt=\"edit activity\" class=\"edit_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"Edit{$activity->ActivityID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";

                //check if attachment
                if ($activity->CountAttach > 0)
                    $return .= "<a title=\"download\" alt=\"download\" class=\"download_attachment tip\" activity-id=\"{$activity->ActivityID}\"  ><i class=\"icon16 i-attachment gap-left0 gap-right10\" ></i></a>";

                //check if call
                if (($activity->CallStart != "0000-00-00 00:00:00" && $activity->CallStart != "" && $activity->CallEnd != "0000-00-00 00:00:00" && $activity->CallEnd != ""))
                    $return .= "<a href=\"#ActivityDetailsModal\" title=\"view call info\" alt=\"view call info\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"ViewCall{$activity->ActivityID}\"  data-toggle=\"modal\" call-info=\"1\" target=\"CrmContentDetails\" ><i class=\"icon16 i-phone-2 gap-left0\" ></i></a>";


                $return .= "
							</td>
						</tr> ";
            }//end foreach
        }
        else {
            $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"10\" >No activity found!</td>
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
        $records = $this->common->getGenerateActivities_("csa_promotion_activities", array("Username <>" => ''), "ActivityID, Username, SystemID, Currency, AddedBy, DateAdded, UpdatedBy, DateUpdated");
        $x = 0;

        echo count($records) . " -------------------------------- <br>";
        foreach ($records as $rec) {
            $username = trim(strtolower(trim(iconv("UTF-8", "UTF-8//IGNORE", trim($rec->Username)))));
            $username = trim(strtolower(preg_replace('/[^[:alnum:]]/', '', $username)));

            $system_id = trim(iconv("UTF-8", "UTF-8//IGNORE", trim($rec->SystemID)));
            $system_id = trim(preg_replace('/[^[:alnum:]]/', '', $system_id));

            if ($username && $username != "") {
                $exist = $this->common->get12betUserById_(array("a.Username =" => $username), " a.UserID, a.SystemID ");

                //if no record found
                if ((count($exist) <= 0 || $exist->UserID == "")) {
                    if (!array_key_exists($username, $rows)) {
                        $x++;
                        $rows[$username] = array("Username" => $username,
                            "Currency" => trim($rec->Currency),
                            "SystemID" => $system_id,
                            "Activity" => trim($this->activity_type),
                            "AddedBy" => trim($rec->AddedBy),
                            "DateAdded" => trim($rec->DateAdded),
                            "UpdatedBy" => $this->session->userdata("mb_no"),
                            "DateUpdated" => trim($rec->DateUpdated)
                        );
                        echo $x . ') ' . $username . ' ********************* <br>';
                    } else {
                        if ($system_id != "" && ($rows[$username][SystemID] != $system_id))
                            $rows[$username][SystemID] = $system_id;
                    }
                }
                else {
                    //echo $rec->ActivityID.') '.$username.' is already exist '. ' <br>';
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

        echo "<br> -------------------------------- " . $x;
        ob_end_flush();

        if (count($rows) > 0) {
            $count_rec = $this->common->batchInsert_("csa_12bet_users", $rows);
            echo "<br><br> Inserted rows : " . count($rows);
        }

        //return $count_rec; 
    }

}

/* End of file promotions.php */
/* Location: ./application/controllers/promotions.php */