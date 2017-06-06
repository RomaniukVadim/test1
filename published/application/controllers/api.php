<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Api extends CI_Controller {

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
        $this->load->model("api_model", "api");
        $this->load->model("common_model", "common");
        //$this->load->library('ws', array('tag' => getenv('HTTP_HOST') == '10.120.10.92' ? 'intra' : 'intratest'));
        $this->allowed_domain = "*";
        //$this->issue_allow_regularize = array(3); 
        $this->valid_markets = array("ch" => "China", "id" => "Indonesia", "my" => "Malaysia", "th" => "Thailand", "vn" => "Vietnam", "kr" => "Korea", "jp" => "Japan", "hi" => "India", "sup" => "Supervisor");

        $this->market_map = array("1" => "my", "2" => "ch", "3" => "vn", "4" => "th", "5" => "id", "7" => "kr", "8" => "kr", "9" => "jp", "15" => "hi", "16" => "hi");
        $production_domain = array("psbcal.12csd.com");
        $domain = $_SERVER['HTTP_HOST'];

        $this->allowed_ip_test = array("127.0.0.1", "10.120.10.1", "10.120.0.196", "10.120.10.196", "10.120.0.221", "10.120.10.221",
            "122.53.154.194", //PSP public IP 
            //"121.127.8.50", //New PSP public IP 
            "61.58.41.75", //psbcal.zzs33.com
            "63.217.89.79", //psbcal.12csd.com 
            "211.75.6.134", //internal test
            "211.75.6.135", //internal test
            "211.75.6.136", //internal test  
            "10.4.106.78", //internal test 
            "10.3.254.13", //test environment
            "122.146.1.194", //Kevin PC
            "10.254.106.1", //internal production 
            "203.192.162.81", //internal production, 
			"122.255.95.82" //SIT test environment - requested by Kevin Liao
        );

        $this->allowed_ip_prod = array("122.53.154.194", //PSP public IP
            "10.3.254.13" //internal production 
        );

        $this->allowed_ip = (in_array($domain, $production_domain)) ? $this->allowed_ip_prod : $this->allowed_ip_test;

        $this->default_value = array("GroupAssignee" => 1, //CSA
            "Status" => 0, //new 
            "Source" => 18, //Internal System 
            "RejectedCategories" => array(35, 36), //35-WD Rejected - Bonus T/O, 36-WD Rejected - Deposit T/O
            "StatusSettlementCredited" => 19,
            "AssigneeInternalApprove" => 2 //CSD Supervisor		
        );
    }

    public function index() {
        $return = array("Success" => '0', "Message" => "Access not permitted!");
        echo json_encode($return);
    }

    public function GetRemarkType() {
        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: *");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

        $client_ip = $this->input->ip_address();
        //validate ip   
        $check_ip = $this->CheckClientIP($client_ip);
        if ($check_ip[Success] <= 0) {
            $return = array("Success" => 0, "Message" => $check_ip[Message], "MessageArray" => $check_ip[MessageArray]
            );
            echo json_encode($return);
            exit;
        }

        $input_post = $this->input->post() ? $this->input->post() : array();
        $input_get = $this->input->get() ? $this->input->get() : array();

        $data = array_merge($input_post, $input_get);

        $where_arr = array("Status" => '1');

        if (trim($data[MethodType])) {
            $where_arr["Category"] = trim($data[MethodType]);
        }

        if (trim($data[CategoryID])) {
            $where_arr["CategoryID"] = trim($data[CategoryID]);
        }

        if (trim($data[ShowInInternal])) {
            $where_arr["ShowInInternal"] = trim($data[ShowInInternal]);
        }

        if (trim($data[InternalStatus])) {
            $where_arr["InternalStatus"] = trim($data[InternalStatus]);
        }
        $categories = $this->api->getCategoriesList_($where_arr, $paging = array("limit" => $per_page, "page" => $page));

        $result = json_encode($categories);
        echo $result;
    }

    //ADD ACTIVITY 
    public function AddActivity() {
        //header('content-type: application/json; charset=utf-8'); 
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");


        //@file_put_contents("/tmp/log.txt", $this->input->post("activity"));

        $client_ip = $this->input->ip_address();
        //validate ip   
        $check_ip = $this->CheckClientIP($client_ip);
        if ($check_ip[Success] <= 0) {
            $return = array("Success" => 0, "Message" => $check_ip[Message], "MessageArray" => $check_ip[MessageArray]
            );
            echo json_encode($return);
            exit;
        }

        $referrer_address = trim($_SERVER["HTTP_REFERER"]);
        $full_url = current_url() . '?' . $_SERVER['QUERY_STRING'];

        //$data = json_decode($this->input->post());  
        //$data = $this->input->post();  //use when testing to my local
        $data = json_decode($this->input->post("activity"), true);  //for production
        $activity = strtolower(trim($data[Activity]));
        $post_data = http_build_query($data, '', '&amp;');
        $current_date = date("Y-m-d H:i:s");

        //validate data  
        $validate = $this->ValidateData($data, $activity);
        if ($validate[Success] <= 0) {
            $return = array("Success" => 0, "Message" => $validate[Message]
            );
            echo json_encode($return);
            exit;
        }

        if ($activity == "bank") {
            $transaction_id = trim($data[TransactionID]);
            $return = $this->BankActivity($data, "add"); //add activity  
        } elseif ($activity == "promotion") {
            $return = array("Success" => '0', "Message" => "Error!");
        } else {
            $return = array("Success" => '0', "Message" => "Error!");
        }



        //save in history  
        $transaction_id = preg_replace('/[^0-9]+/', '', $data[TransactionID]);
        $history_data = array(
            'Activity' => trim($data[Activity]),
            'TransactionID' => $transaction_id,
            'Category' => strtolower(trim($data[MethodType])),
            'InternalUsername' => trim($data[Operator]),
            'Currency' => trim($data[Currency]),
            'Username' => trim($data[Username]),
            'StringParameters' => trim($full_url),
            'PostData' => trim($post_data),
            'ReferrerUrl' => trim($referrer_address),
            'ReferrerIP' => trim($client_ip),
            'DateAdded' => $current_date,
            'ReturnMessage' => json_decode('"' . trim($return[Message]) . '"'),
            'ActivityID' => trim($return[LastID]),
            'Status' => trim($return[Success]),
        );
        $y = $this->api->manageActivity_("csa_internal_api", $history_data, "add", '', '');

        echo json_encode($return);
    }

    //END ADD ACTIVITY  

    function BankActivity($data, $action = "add") {

        //$this->load->model("banks_model","banks"); 
        $activity_type = "deposit_withdrawal";

        //validate data
        $validate = $this->ValidateData($data, $data[Activity]);
        if ($validate[Success] <= 0) {
            $return = array("Success" => 0, "Message" => $validate[Message]);
            return $return;
        }

        $user = $this->api->getUserById_(array("a.mb_internal_user =" => $data[Operator], "a.mb_name =" => "csa"));
        $currency = $this->api->getCurrencyById_(array("InternalAbbreviation =" => trim($data[Currency]), "Status =" => '1'));
        $category = $this->api->getCategoriesList_(array("CategoryID" => trim($data[Issue]), "Status" => '1'));
        $error = array();

        //check if operator exist in CAL System
        if (count($user) <= 0) {
            array_push($error, "Operator {$data[Operator]} not found in CAL System");
        }

        //check if currency exist in CAL System
        if (count($currency) <= 0) {
            array_push($error, "Currency {$data[Currency]} not found in CAL System");
        }

        $assignee = $this->default_value[GroupAssignee];
        //check if category exist in CAL System 
        if (count($category) <= 0) {
            array_push($error, "Issue {$data[Issue]} not found in CAL System");
        } else {
            if ($category->Assignee > 0)
                $assignee = $category->Assignee;
        }

        //check if rejected TO/BTO
        if (in_array(trim($data[Issue]), $this->default_value[RejectedCategories])) {
            if (trim($data['AmountMade']) === "" || !isset($data['AmountMade']))
                array_push($error, "Amount Made is missing");
            if (trim($data['AmountNeed']) === "" || !isset($data['AmountNeed']))
                array_push($error, "Amount Need is missing");
        }

        if (count($error) > 0) {
            $return = array("Success" => 0, "Message" => implode('. ', $error));
            return $return;
        }



        $current_date = date("Y-m-d H:i:s");

        $post_data = array(
            'Username' => trim($data[Username]),
            'Currency' => $currency->CurrencyID, //check this value
            'Source' => $this->default_value[Source],
            'Category' => strtolower(trim($data[MethodType])),
            'CategoryID' => trim($data[Issue]),
            'TransactionID' => trim($data[TransactionID]),
            'Amount' => trim($data[Amount]),
            'UpdatedBy' => $user->mb_no,
            'DateUpdated' => $current_date,
            'DateUpdatedInt' => strtotime($current_date),
            'Remarks' => json_decode('"' . $data['Remarks'] . '"'),
            'GroupAssignee' => $assignee,
            'FromInternal' => '1',
        );

        if (in_array(trim($data[Issue]), $this->default_value[RejectedCategories])) {
            $post_data[AmountMade] = clean_currency($data['AmountMade']);
            $post_data[AmountNeed] = clean_currency($data['AmountNeed']);
            $post_data[OutstandingAmount] = clean_currency($data['OutstandingAmount']);
        }


        if ($action == "add") { //ADD
            $post_data[AddedBy] = $user->mb_no;
            $post_data[DateAdded] = $current_date;
            $post_data[DateAddedInt] = strtotime($current_date);
            $post_data[Status] = $this->default_value[Status];

            $last_id = $this->api->manageActivity_("csa_bank_activities", $post_data, $action, '', '');

            if ($last_id > 0) {

                $return = array("Success" => 1, "Message" => "Bank activity added successfully.", "LastID" => $last_id);

                $history_data = array(
                    'ActivityID' => $last_id,
                    'Activity' => $activity_type,
                    'Status' => $this->default_value[Status],
                    'Remarks' => json_decode('"' . $data['Remarks'] . '"'),
                    'UpdatedBy' => $user->mb_no,
                    'Important' => '0',
                    'IsComplaint' => '0',
                    'DateUpdated' => $current_date,
                    'DateUpdatedInt' => strtotime($current_date),
                    'GroupAssignee' => $assignee
                );

                $y = $this->api->manageActivity_("csa_activities_history", $history_data, "add", '', '');

                //ADD TO 12BET USERS  
                $user12bet_insert = insert_12bet_user($post_data, $activity_type);
            } else {
                $return = array("Success" => 0, "Message" => "Error adding activity!", "LastID" => 0);
            }
        } else { //UPDATE
            $return = array("Success" => 0, "Message" => "Error adding activity! Not allowed to add activity", "LastID" => 0);
        }

        return $return;
    }

    function ValidateData($data, $activity = "") {

        $error = array();
        //check for token here if function is available

        if (trim($data[Currency]) == "") {
            array_push($error, "Currency is invalid");
        }

        if (trim($data[TransactionID]) == "") {
            array_push($error, "Transaction ID is invalid");
        }

        if (trim(trim($data[Operator])) == "") {
            array_push($error, "Operator is invalid");
        }

        if (count($error) > 0) {
            $return = array("Success" => 0, "Message" => "Invalid parameters", "MessageArray" => $error);
        } else {
            $return = array("Success" => 1, "Message" => "Good parameters");
        }

        return $return;
    }

    function CheckClientIP($client_ip = "") {

        $error = array();

        if (!in_array($client_ip, $this->allowed_ip) || $client_ip == "") {
            array_push($error, "IP: {$client_ip} not allowed to access");
        }

        if (count($error) > 0) {
            $return = array("Success" => 0, "Message" => "Invalid client IP", "MessageArray" => $error);
        } else {
            $return = array("Success" => 1, "Message" => "Good parameters");
        }

        return $return;
    }

    //ADD ACTIVITY 
    public function NotifyCreditBonus() {
        //header('content-type: application/json; charset=utf-8'); 
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");

        $client_ip = $this->input->ip_address();

        //validate ip    
        $check_ip = $this->CheckClientIP($client_ip);
        if ($check_ip[Success] <= 0) {
            $return = array("Success" => 0, "Message" => $check_ip[Message], "MessageArray" => $check_ip[MessageArray]
            );
            echo json_encode($return);
            exit;
        }

        $this->load->model("promotions_model", "promotions");

        $referrer_address = trim($_SERVER["HTTP_REFERER"]);
        $full_url = current_url() . '?' . $_SERVER['QUERY_STRING'];

        $data = json_decode($this->input->post("promotion"), true);
        $post_data = http_build_query($data, '', '&amp;');
        $current_date = date("Y-m-d H:i:s");

        $error = array();
        $old = array();

        if (trim($data[ActivityID]) == "" || !isset($data[ActivityID])) {
            array_push($error, "Activity ID is invalid");
        } else {
            $old = $this->promotions->getActivityById_(array("a.ActivityID =" => trim($data[ActivityID]))); //need to confirm if already have System ID  
            if (count($old) <= 0) {
                array_push($error, "Activity ID in not found");
            } else {
                if (trim($old->BonusReferenceNo) > 0)
                    array_push($error, "Activity ID: {$data[ActivityID]} has Reference No. {$old->BonusReferenceNo}. ");
            }
        }


        //check Reference No.
        if (trim($data[ReferenceNo]) == "" || !isset($data[ReferenceNo]) || $data[ReferenceNo] <= 0) {
            array_push($error, "Reference No. is empty");
        } else {
            $reference = $this->promotions->getActivityById_(array("a.BonusReferenceNo =" => trim($data[ReferenceNo])));
            if (count($reference) > 0) {
                if ($reference->ActivityID != trim($data[ActivityID])) {
                    array_push($error, "Reference No. is already used by other Activity ID: {$reference->ActivityID}. Duplicate ");
                } else {
                    array_push($error, "Reference No. is already credited to this Activity ID. Credited ");
                }
            }
        }
        //end check Reference No.

        if (trim($data[Approver]) == "" || !isset($data[Approver])) {
            array_push($error, "Approver is empty");
        } else {
            $user = $this->api->getUserById_(array("a.mb_internal_user =" => trim($data[Approver]), "a.mb_name =" => "csa"));
            if (count($user) <= 0) {
                array_push($error, "Approver {$data[Approver]} not found in CAL System");
            }
        }
        //check for token here if function is available

        if (count($error) > 0) {
            $error_message = implode(', ', $error);
            $return = array("Success" => 0, "Message" => $error_message, "MessageArray" => $error
            );
            echo json_encode($return);
            exit;
        }

        $reference_no = trim($data[ReferenceNo]);
        $status_id = $this->default_value['StatusSettlementCredited'];
        $new_assignee = $this->default_value['AssigneeInternalApprove'];

        $this->load->model("promotions_model", "promotions");

        //check the old  	     
        $changes = "";
        $default_remarks = "Approved by {$data[Approver]} - {$user->mb_nick} using Internal System";

        if (trim($data[Remarks]))
            $default_remarks .= trim($data[Remarks]);

        if (count($old) <= 0) {
            $return = array("Success" => 0, "Message" => "Activity ID : {$data[ActivityID]} not found");
            echo json_encode($return);
            exit;
        }

        //get status info
        $status = $this->common->getStatusById(array("a.StatusID" => $status_id));
        if (count($status) <= 0) {
            $return = array("Success" => 0, "Message" => "Status not found");
            echo json_encode($return);
            exit;
        }

        //get assignee info
        $assignee = $this->common->getGroupAssigneeById_(array("a.GroupID" => $new_assignee));
        if (count($assignee) <= 0) {
            $return = array("Success" => 0, "Message" => "Group Assignee not found");
            echo json_encode($return);
            exit;
        }



        $main_post_data = array(
            'ActivityID' => trim($data['ActivityID']),
            'BonusReferenceNo' => $reference_no,
            'Status' => $status_id,
            'UpdatedBy' => $user->mb_no,
            'DateUpdated' => $current_date,
            'DateUpdatedInt' => strtotime($current_date),
            'ApprovedBonusBy' => $user->mb_no,
            'DateBonusNotify' => strtotime($current_date),
            'GroupAssignee' => $new_assignee
        ); //new_assignee
        //check for history
        $changes .= (trim($data['BonusReferenceNo']) != $old->BonusReferenceNo) ? "Reference No. changed to " . $reference_no . " from '" . $old->BonusReferenceNo . "'|||" : "";
        $changes .= ($status_id != $old->Status) ? "Status changed to {$status->StatusName} from '" . $old->StatusName . "'|||" : "";
        $changes .= "Bonus Credit approved by {$user->mb_nick} on " . $current_date . "|||";
        $changes .= ($new_assignee != $old->GroupAssignee) ? "Assignee changed to {$assignee->GroupAssigneeName} from '" . $old->GroupAssigneeName . "'|||" : "";

        $main_updated = ($changes) ? '1' : '0';

        $history_data = array(
            'ActivityID' => $old->ActivityID,
            'Activity' => "promotion",
            'Status' => $status_id,
            'UpdatedBy' => $user->mb_no,
            'Important' => '0',
            'IsComplaint' => '0',
            'MainUpdated' => $main_updated,
            'DateUpdated' => $current_date,
            'DateUpdatedInt' => strtotime($current_date),
            'GroupAssignee' => $new_assignee
        );

        if ($default_remarks) {
            //$changes .= ($data['act_remarks'] != $old->Remarks)?"Remarks changed to ".$data['act_remarks']." from ".$old->Remarks."|||":"";	 
            $changes .= "Remarks changed to " . $default_remarks . " from " . $old->Remarks . "|||";
            $main_post_data['Remarks'] = trim($default_remarks);
            $history_data['Remarks'] = trim($default_remarks);
        }
        $changes .= "";

        $history_data['Changes'] = $changes;

        $x = $this->api->manageActivity_("csa_promotion_activities", $main_post_data, "update", "ActivityID", trim($data[ActivityID]));
        if ($x > 0) {

            $return = array("Success" => 1, "Message" => "Received Successfully.");
            $y = $this->promotions->manageActivity_("csa_activities_history", $history_data, "add", '', '');
        } else {
            $return = array("Success" => 0, "Message" => "Error updating activity!");
        }

        //save in history   
        $transaction_id = preg_replace('/[^0-9]+/', '', $old->TransactionID);
        $api_data = array(
            'Activity' => "promotion",
            'TransactionID' => $transaction_id,
            'ReferenceNo' => $reference_no,
            'Category' => "notify_bonus",
            'InternalUsername' => trim($data[Approver]),
            'Currency' => $old->CurrencyName,
            'Username' => $old->Username,
            'StringParameters' => trim($full_url),
            'PostData' => trim($post_data),
            'ReferrerUrl' => trim($referrer_address),
            'ReferrerIP' => trim($client_ip),
            'DateAdded' => $current_date,
            'ReturnMessage' => trim($return[Message]),
            'ActivityID' => $old->ActivityID,
            'Status' => trim($return[Success]),
        );
        $y = $this->api->manageActivity_("csa_internal_api", $api_data, "add", '', '');
        //end save in history

        echo json_encode($return);
    }
    
    public function portal_notification(){
        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: *");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");   
        $this->load->model('portal_model','portal');
        $user_type = $this->input->post("mb_usertype");
        $mb_no = $this->input->post("mb_no");
        $mb_currencies = $this->input->post("mb_currencies");
        
        $currencies = explode(",", $mb_currencies);
        $valid_markets = array();
        if (in_array($user_type, array(2, 6, 7, 13)) || supervisor_rights() || admin_access()) $valid_markets["sup"] = "Supervisor";

        foreach ($currencies as $curr){
            if (isset($this->market_map[$curr])) $valid_markets[] = $this->market_map[$curr];
        }
        
        $allowed_users = allow_post_view_notification(true);
        if(!in_array(intval($user_type),$allowed_users)) $valid_markets = array();
        
        $data = Array();
        
        if($valid_markets){
            $active_notif = $this->portal->check_notification($valid_markets,$mb_no);
            $menu_id = Array();
            foreach($active_notif as $notice){
                if(!in_array($user_type,explode(",",$notice->hidden_to))){
                  if(!in_array(intval($notice->menu_id),$menu_id)){
                      $data[] = $notice;
                      $menu_id[] = intval($notice->menu_id);
                  }
                }
            }
        }
        echo json_encode(Array("data"=>$data));
        
    }    
    
    //END Notify Credit Bonus

    public function TestValues() {
        //echo "HTTP_HOST: ".$_SERVER['HTTP_HOST']."<br>"; 

        /* $this->load->helper('url');
          $url_parts = parse_url(current_url());
          print_r($url_parts);
          echo str_replace('www.', '', $url_parts['host'])."<br>";
          echo nl2br(json_decode('"adfadf\r\ndadf\r\n\u0026\u003e\u003c\r\n()$%"')); */

        $remarks = "Promotion: Bonus Test For CAL  
				    Bonus Code: BonusTestCAL   
				    Transaction ID: 24316   
				    System ID: 12BETUUS02042  
				    Deposit Amount: 300.00   
				    Bonus Amount: 15.00   
				    Wagering Amount: 1,575.00";

        echo nl2br($remarks);
    }

    function get_employee_details() {
        header('content-type: application/json; charset=utf-8');
        header("access-control-allow-origin: *");
        header("Access-Control-Allow-Headers: X-Requested-With, Content-Type");
        
        $this->load->model('employee');
        $users_details = ($this->employee->get_employee(array('mb_username' => $this->input->post('username'), 'mb_status' => 1)));
        $allowed_users = allow_post_view_notification(true);
        if(count($users_details) > 0 and !in_array(intval($users_details->mb_usertype),$allowed_users)) $valid_markets = array();
        
        echo json_encode($users_details);
    }

    function prtl_get_page() {
        $username = $this->input->post('username');
        $page_id = $this->input->post('page_id');
        //$this->load->model('employee');
        //$users_details = ($this->employee->get_employee(array('mb_username' => $username)));
        $this->load->model('portal_model', 'portal');


        $page = $this->portal->get_page_by_id($page_id);
        $page = $page[0];

        //  echo "<input type='hidden' value='" . $users_details->mb_no . "' id='hidden_csd_mb_no'>";
        echo "<div class='scroller' style='padding-left:1%;padding-right:1%;border-bottom:solid;border-width:1px;max-height:500px;overflow-y:auto'>";
        echo $page->page_content;
        echo "</span><br><br>";
        echo "</div>";
        echo "<span style='float:right;color:#438eb9;font-style:italic'>";

        echo "</span>";
        echo "<span style='font-size:18px;color:#438eb9; '><input type='checkbox' value='undestood' id='csd_prtl_understand_check' data-page_market='" . $page->menu_market . "' data-notice_id='" . $page->page_id . "' notice_id='" . $page->page_id . "' ><label for='csd_prtl_understand_check' style='float:none;line-height: inherit;display:inline-block;min-width: 100px;width: auto;cursor:pointer;margin-left:8px;margin-top:5px;'> I have read and understood the contents of this page </label></span>";
    }

    function prtl_markAsRead() {
        /*$this->ws->load('pagenotice');
        $post = $this->input->post();

        PAGENOTICE::read($post['page_id'], $post['username'], $post['channel']);
        echo "1";*/
    }

}

/* End of file promotions.php */
/* Location: ./application/controllers/promotions.php */