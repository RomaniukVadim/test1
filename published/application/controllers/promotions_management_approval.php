<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Promotions_Management_Approval extends MY_Controller {

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

        $this->allowed_users = array(5, 14, 15, 16);
    }

    public function index() {

        if (!admin_only() && !in_array($this->session->userdata("mb_usertype"), $this->allowed_users)) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $categories_where = " AND a.Status='1' ";

        if (restriction_type()) {
            $categories_where .= " AND (FIND_IN_SET('" . $this->session->userdata('mb_usertype') . "', a.Viewers) ) ";
        }

        $data2 = array("main_page" => "promotions",
            "currencies" => $this->common->getCurrency_(),
            //"sources"=>$this->common->getSource_(), 
            "categories" => $this->promotions->getPromotionsCategoriesList_($categories_where),
            "status_list" => $this->common->getStatusList_(3), //3 for promotion page  
            "outcomes" => $this->promotions->getCallOutcomeList_(array("a.outcome_status =" => '1')),
            "utypes" => $this->common->getUsersGroup_(array("Status =" => '1')), //user types 
            "s_page" => $page,
            "date_index" => $this->common->date_index
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Activities for Management Approval",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('header', $data);
        $this->load->view('header_nav');
        $this->load->view('promotions/promotions_activities_management_tpl');
        $this->load->view('footer');
    }

    public function activities() {
        $this->index();
    }

    public function getActivities($actual = 0) {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_only() && !in_array($this->session->userdata("mb_usertype"), $this->allowed_users)) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

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

        if (trim($data[s_isuploaded]) == 1) {
            $search_string .= " AND (a.IsUpload='" . $this->common->escapeString_($data[s_isuploaded]) . "') ";
            $search_url .= "&s_isuploaded=" . $$data[s_isuploaded];
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

        if (trim($data[s_status]) != "") {
            $search_string .= " AND (a.Status='" . trim($data[s_status]) . "') ";
            $search_url .= "&s_status=" . trim($data[s_status]);
            $allow_close++;
        } else {
            
        }

        if (trim($data[s_assignee]) != "") {
            $search_string .= " AND (a.GroupAssignee='" . trim($data[s_assignee]) . "') ";
            $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            $allow_close++;
        } else {
            if (restriction_type()) {
                $search_string .= " AND ((a.GroupAssignee='" . trim($this->session->userdata("mb_usertype")) . "') OR (a.AddedBy='" . trim($this->session->userdata("mb_no")) . "') )";
                $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            } else {
                $search_string .= " AND a.GroupAssignee IN(" . implode(',', $this->allowed_users) . ")";
                $search_url .= "&s_assignee=" . trim($data[s_assignee]);
            }
        }

        /* if(trim($data[s_assignee]) != "")
          {
          $search_string .= " AND (a.GroupAssignee='".trim($data[s_assignee])."') ";
          $search_url .= "&s_assignee=".trim($data[s_assignee]);
          $allow_close++;
          $allow_view++;
          } */

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

        if (trim($data[s_source])) {
            $search_string .= " AND (a.Source='" . $this->common->escapeString_(trim($data[s_source])) . "') ";
            $search_url .= "&s_source=" . trim($data[s_source]);
            $allow_close++;
        }

        if (trim($data[s_problem])) {
            $search_string .= " AND (a.Problem='" . $this->common->escapeString_(trim($data[s_problem])) . "') ";
            $search_url .= "&s_problem=" . trim($data[s_problem]);
            $allow_close++;
        }

        if ($s_fromdate && $s_todate) {
            if ($data[s_dateindex] == 'added') {
                $search_string .= " AND (a.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = "ActivitiesAddedKey";
                $order_by = "a.DateAddedInt";
            } elseif ($data[s_dateindex] == 'updated') {
                $search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = "ActivitiesUpdatedKey";
                $order_by = "a.DateUpdatedInt";
            } elseif ($data[s_dateindex] == 'uploaded') {
                $search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = "ActivitiesUploadedKey";
                $order_by = "a.DateUploadedInt";
            } else {
                $search_string .= " AND (a.DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";
                $index = "ActivitiesUpdatedKey";
                $order_by = "a.DateUpdatedInt";
            }

            $search_url .= "&s_fromdate=" . urlencode(trim($data[s_fromdate])) . "&s_todate=" . urlencode(trim($data[s_todate])) . "&s_dateindex=" . urlencode(trim($data[s_dateindex]));
        } else {
            if ($data[s_dateindex] == 'added') {
                $index = "ActivitiesAddedKey";
                $order_by = "a.DateAddedInt";
            } elseif ($data[s_dateindex] == 'updated') {
                $index = "ActivitiesUpdatedKey";
                $order_by = "a.DateUpdatedInt";
            } elseif ($data[s_dateindex] == 'uploaded') {
                $index = "ActivitiesUploadedKey";
                $order_by = "a.DateUploadedInt";
            } else {
                $index = "ActivitiesUpdatedKey"; //$data[s_dateindex];  
                $order_by = "a.DateUpdatedInt";
            }
        }

        /* if(trim($data[s_agent]) != "")
          {
          $search_string .= " AND (f.mb_nick LIKE '%".$this->common->escapeString_($data[s_agent])."%') ";
          $search_url .= "&s_agent=".trim($data[s_agent]);
          $allow_close++;
          } */

        /* if(trim($data[s_remarks]) != "")
          {
          $search_string .= " AND (a.Remarks LIKE '%".$this->common->escapeString_($data[s_remarks])."%') ";
          $search_url .= "&s_remarks=".trim($data[s_remarks]);
          $allow_close++;
          } */

        //for call search 
        if (trim($data[s_calloutcome])) {
            $search_string .= " AND (a.CallOutcomeID='" . $this->common->escapeString_($data[s_calloutcome]) . "') ";
            $search_url .= "&s_calloutcome=" . $s_calloutcome;
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

        if (trim($data[s_callproblem])) {
            $search_string .= " AND (a.CallProblem='" . $this->common->escapeString_($data[s_callproblem]) . "') ";
            $search_url .= "&s_callproblem=" . $s_callproblem;
            $allow_close++;
        }
        //end for call search  

        $search_string = ($allow_close <= 0 && $this->common->display_close_ticket == 1) ? $search_string . " AND (a.Status NOT IN ({$this->common->hide_status}) ) " : $search_string;
        $search_string = trim(trim($search_string), "AND");

        $per_page = 10;
        //$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
        $page = ($data['s_page']) ? $data['s_page'] : 0;
        //$total_rows = $this->common->countSearchActivities_($search_string, "csa_promotion_activities", $index)->CountActivity; 
        //$activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index);

        $return = $activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging = array("limit" => $per_page, "page" => $page), $index, $order_by);
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
            $return = array("activities" => $this->generateHtmlList($activities),
                "pagination" => create_pagination($pagination_options),
                "pagination_string" => $pagination_string,
                "records" => $total_rows
            );
            echo json_encode($return);
        }
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
                $status_checkbox = ($activity->Status > 0) ? "<input type=\"checkbox\" value=\"{$activity->ActivityID}\" name=\"check_activity[]\"  />" : "";
                $SystemIDVal = ($activity->SystemID != '' && strtolower($activity->SystemID) != 'n/a' && strtolower($activity->SystemID) != 'na') ? $activity->SystemID : "";
                $SystemID = ($activity->SystemID != '' && strtolower($activity->SystemID) != 'n/a' && strtolower($activity->SystemID) != 'na') ? $activity->SystemID : "<input type=\"text\" class=\"batch_system_id\" value=\"{$SystemIDVal}\" name=\"actid_{$activity->ActivityID}\"/>";
                $return .= "
						<tr class=\"activity_row\" id=\"ActivityRow{$activity->activittyID}\" > 
							<td class=\"center\" >{$status_checkbox}</td>
							<td class=\"center\" >{$activity->Currency}</td>
							<td class=\"center {$is_important}\" >{$activity->Username}</td>
							<td >{$activity->PromotionName}</td>
                                                        <td >{$SystemID}</td>
							<td class=\"right\">{$activity->BonusAmount}</td> 
							<td class=\"center green\" >" . ucwords($activity->StatusName) . "</td> 
							<td class=\"center\" >{$activity->GroupAssigneeName}</td>
							<td class=\"center action\" >
								<a href=\"#ActivityDetailsModal\" title=\"view activity\" alt=\"view activity\" class=\"view_activity tip\" activity-id=\"{$activity->ActivityID}\"  id=\"View{$activity->ActivityID}\"  data-toggle=\"modal\"  target=\"CsaContentDetails\" ><i class=\"icon16 i-file-8 gap-left0 gap-right10\" ></i></a>
							";

                //check if usertype allowed to edit activity
                if (allowEditMain())
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
							<td class=\"center\" colspan=\"9\" >No activity found!</td>
						</tr>
			 			";
        }

        return $return;
    }

    public function updateActivityStatus() {
        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_only() && !in_array($this->session->userdata("mb_usertype"), $this->allowed_users)) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        $data = $this->input->post();

        /* $check = array();  

          foreach($this->input->post("check_activity") as $selected){
          if($selected) array_push($check, $selected);
          }
          $check_str = implode(',', $check); */

        $check_str = $this->input->post("hidden_aids");

        if ($data["act_status"] == "") {
            $error .= "Please select status!<br> ";
        }

        if ($data["act_assignee"] == "") {
            $error .= "Please select assignee!<br> ";
        }

        if ($check_str == "") {
            $error .= "Please select activity to update!<br> ";
        }

        if ($error) {
            $return = array("success" => 0, "message" => $error);
        } else {
            $current_date = date("Y-m-d H:i:s");
            $where_arr = array("FIND_IN_SET(a.ActivityID, '{$check_str}') !=" => 0);

            $new_status = $data['hidden_astatus']; //$this->common->getStatusById(array("a.StatusID ="=>$data[act_status]))->StatusName; 
            $new_assignee = $data['hidden_aassignee']; //$this->common->getUsersGroup_(array("GroupID ="=>$data[act_assignee]));  

            $activities = $this->promotions->getActivityManagement_($where_arr);

            $i = 0;
            $act_data = array();
            $history_data = array();
            foreach ($activities as $row => $old) {
                $changes = "";
                if ($old->Status != $data[act_status] || $old->GroupAssignee != $data[act_assignee]) {
                    if ($old->Status != $data[act_status])
                        $changes .= "Status changed to {$new_status} from " . $old->StatusName . "|||";
                    if ($old->GroupAssignee != $data[act_assignee])
                        $changes .= "Assignee changed to {$new_assignee} from " . $old->GroupAssigneeName . "|||";
                    if (isset($data['actid_' . $old->ActivityID])) {
                        if ($old->SystemID != $data['actid_' . $old->ActivityID] && trim($data['actid_' . $old->ActivityID]) != '') {
                            $changes .= "System ID changed to {$data['actid_' . $old->ActivityID]} from " . $old->SystemID . "|||";
                        }
                    }

                    $act_data[$i] = array(
                        "ActivityID" => $old->ActivityID,
                        "Status" => $data[act_status],
                        "GroupAssignee" => $data[act_assignee],
                        "UpdatedBy" => $this->session->userdata("mb_no"),
                        "DateUpdated" => $current_date,
                        "DateUpdatedInt" => strtotime($current_date),
                        "SystemID" => $data['actid_' . $old->ActivityID]
                    );

                    $history_data[$i] = array(
                        "Activity" => "promotion",
                        "ActivityID" => $old->ActivityID,
                        "Changes" => $changes,
                        "Status" => $data[act_status],
                        "GroupAssignee" => $data[act_assignee],
                        "UpdatedBy" => $this->session->userdata("mb_no"),
                        "DateUpdated" => $current_date,
                        "DateUpdatedInt" => strtotime($current_date),
                        'MainUpdated' => '1',
                        'Remarks' => $data[update_remarks]
                    );

                    $i++;
                }
            }


            if (count($old) > 0) {

                $x = $this->common->batchUpdate_("csa_promotion_activities", $act_data, "ActivityID");

                if ($x > 0) {
                    $y = $this->common->batchInsert_("csa_activities_history", $history_data);
                    $return = ($y > 0) ? array("success" => 1, "message" => "Activities updated successfully.", "is_change" => 1) : array("success" => 0, "message" => "Error inserting to history. Please check! <br>");
                } else {
                    $return = array("success" => 0, "message" => "Error updating activities. Please check! <br>");
                }

                //$return = ($x == true)?$return = array("success"=>1, "message"=>"Activities updated successfully."):$return = array("success"=>0, "message"=>"Error updating activities. Please check!"); 
            } else {
                $return = array("success" => 0, "message" => "Selected activities not found! <br>");
            }
        }


        echo json_encode($return);
    }

    //POPUP UPDATE
    public function popupManageActivity() {

        if (!admin_only() && !in_array($this->session->userdata("mb_usertype"), $this->allowed_users)) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }

        if (!$this->input->is_ajax_request()) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
            return false;
        }

        if (!admin_only() && !in_array($this->session->userdata("mb_usertype"), $this->allowed_users)) {
            error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
            return false;
        }


        $data2 = array("main_page" => "promotions",
            "status_list" => $this->common->getStatusList_(3), //3 promotions
            "utypes" => $this->common->getUsersGroup_(array("Status =" => '1')), //user types
            "check_str" => $this->input->post("check_activity")
        );
        $sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 

        $data = array("page_title" => "12Bet - CAL - Management Approval Update Activities",
            "sidebar_view" => $this->load->view($sidebar, $data2, true)
        );
        $this->load->view('promotions/promotions_activities_management_popup_tpl', $data);
    }

    public function updateActivitySystemID() {
        $post = $this->input->post();

        if ($this->promotions->updateActivitySystemID('csa_promotion_activities', $post['params'], 'ActivityID')) {
            $y = $this->promotions->insertBatch("csa_activities_history", $post['history_data']);
            echo true;
        } else {
            echo false;
        }
    }

}

/* End of file promotions.php */
/* Location: ./application/controllers/promotions.php */