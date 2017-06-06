<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Website_Promotions extends CI_Controller {

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
        $this->allowed_domain = "*";
        //$this->issue_allow_regularize = array(3); 

        $this->allowed_ip = array("127.0.0.1", "10.120.10.1", "10.120.0.196", "10.120.10.196", "10.120.0.221", "10.120.10.130", "166.62.2.1", "205.177.209.250", "182.50.130.105", "191.238.84.45","10.120.10.15", "122.53.154.206", "121.127.8.57", "61.58.41.75", "52.175.147.236"); //12winwin, staticpage, new promotion IP
    }

    public function index() {
        /* header("Access-Control-Allow-Origin: {$this->allowed_domain}");
          header('Access-Control-Allow-Methods: POST');
          header("Access-Control-Allow-Headers: X-Requested-With, Content-Type"); */

        //
        $data = $this->input->post();
        $referrer_address = trim($data[full_url]); //$_SERVER["HTTP_REFERER"];
 
		
        /* $postData = file_get_contents('php://input'); 
          if ($postData) {
          $ie_data = array();
          parse_str($postData, $ie_data);
          if ($ie_data['ie_data']) {
          $ie_data = json_decode($ie_data['ie_data'], true);
          $data = $ie_data;
          $referrer_address = $data['url'];

          }

          }
          else
          {
          if(!$this->input->is_ajax_request())
          {
          $return = array("success"=>false, "message"=>"Not allowed to access" );
          echo json_encode($return);
          exit;
          }
          } */

        $client_ip = $this->input->ip_address();
        if (!in_array($client_ip, $this->allowed_ip) || $client_ip == "") {
            $return = array("success" => false, "message" => "Not allowed to access" . $client_ip, "error_code" => "not_allowed");
            echo json_encode($return);
            exit;
        }

        /* if ($client_ip != '205.177.209.250') {
          $return = array("success" => false, "message" => "Not allowed to access", "error_code" => "not_allowed");
          echo json_encode($return);
          exit;
          } */

        //for CAL checking	  
        $str_connector = "***";
        $full_url = current_url() . '?' . $_SERVER['QUERY_STRING'];  //get full URL string


        $parse = parse_url(trim($referrer_address));
        $check_url = ($parse['host']) ? $parse['host'] : $parse['path'];

        $unique_id = $data[last_id];

        $key = trim($data[key]);
        $valid_key = md5(trim($data[token]) . $str_connector . trim($data[bonus_code]) . $str_connector . trim($data[currency]) . $str_connector . trim($data[username]) . $str_connector . $check_url);
        $default = array("GroupAssignee" => 1,
            "AddedBy" => 49,
            "UpdatedBy" => 49,
            "Status" => 64,
            "Source" => ($data['source_device'] ? $data['source_device'] : 16), //11
            "CashbackCategory" => 5,
            "CloseStatus" => 5,
            "NotStatus" => "5,43,63", //5-Close
            "NotStatusDiamond" => "43,63", //43-CRM Note, 63-Cancel 
            "Remarks" => ($data['source_device'] ? "Subscribed from the 12BET Promotion Mobile Page" : "Subscribed from the 12BET Promotion Page")
        );

        $week_number_pro = array("12NG50", "NVK25");
        $diamond_first_20 = "12DIA20"; //"12DIA-V2"; this old 
        $diamond_welcome = "100DIA";
        $one_only = "12DIA20,100DIA";
        $daily_bonus = array("SB-KR05-V2", "SB-KR10-V2");

        $current_date = date("Y-m-d H:i:s");
		 
        if ($data[token] && ($key == $valid_key) && $unique_id) {
            $post_str = http_build_query($data, '', '&');

            $promotion_row = $this->promotions->getPromotionById_(array("a.BonusCode =" => trim($data[bonus_code]),
                "a.CurrencyID =" => trim($data[currency]),
                "a.Status" => '1', 
				"a.IsWebPromotion" => '1',
				"a.ForUserType"=>1
                    )
            );
            $promotion = array();
            $promotion = (count($promotion_row) > 1) ? $promotion_row[0] : $promotion_row;

            $bonus_amt = "0.00";
            $wagering_amt = "0.00";
            $deposit_amt = "0.00";


            if (count($promotion) > 0) {

                //CHECK IF PROMOTION IS CASHBACK 
                /* $promotion_id = (count($promotion) > 1) ? $promotion[0]->PromotionID : $promotion->PromotionID;
                  $category_id = (count($promotion) > 1) ? $promotion[0]->CategoryID : $promotion->CategoryID; */

                $promotion_id = $promotion->PromotionID;
                $category_id = $promotion->CategoryID;

                if ($category_id == $default[CashbackCategory]) {
                    $old = $this->promotions->checkWebsitePromotion_(array("a.Category =" => $default[CashbackCategory], 
																		   "a.Username =" => trim($data[username]), 
																		   "a.Promotion =" => $promotion_id, 
																		   "h.ForUserType"=>1
																		  )
																	);
                    if (count($old) > 0) {
                        $return = array("success" => false, "message" => "You already subscribed to this cashback promotion", "error_code" => "one_time");
                        echo json_encode($return);
                        exit;
                    }
                } else {
                    //"a.Source ="=>$default[Source]
                    $old = $this->promotions->checkWebsitePromotion_(array("a.Category <>" => $default[CashbackCategory],
																		   "a.Username =" => trim($data[username]),
																		   "FIND_IN_SET(a.Status, '{$default[NotStatus]}') =" => 0,
																		   "h.IsWebPromotion =" => '1', 
																		   "h.ForUserType"=>1
																		   //"a.Status <>" => $default[CloseStatus]
																			)
                   													 );

                    $deposit_amt = str_replace(",", "", trim($data[prom_depositAMT]));
                    $min_amt = trim($promotion->MinimumAmount);
                    $max_amt = trim($promotion->MaximumAmount);

                    $reqt = trim($promotion->Turnover);

                    $bonus = trim($promotion->BonusRate) / 100;

                    $formula = str_replace("$", "$", trim($promotion->Formula));
                    $wagering_formula = str_replace("$", "$", trim($promotion->WageringFormula));


                    $type = trim($promotion->Type);

                    $a = 1;
                    $b = 2;

                    eval("\$bonus_amt = $formula; ");

                    $bonus_amt = ($bonus_amt * 100) / 100;
                    $bonus_amt = round($bonus_amt, 2);

                    // 
                    eval("\$wagering_amt = $wagering_formula; ");
                    $wagering_amt = ($wagering_amt > 0) ? $wagering_amt : 0;
                    $wagering_amt = round($wagering_amt, 2);

                    if (count($old) > 0) {
                        $return = array("success" => false, "message" => "You still have pending subscription", "error_code" => "pending_subscription");
                        echo json_encode($return);
                        exit;
                    } else {
                        //number game   
                        if(in_array(trim($data[bonus_code]), $week_number_pro)) {
                            $old = $this->promotions->checkWebsitePromotion_(array("h.BonusCode =" => trim($data[bonus_code]), 
																				   "a.Username =" => trim($data[username]), 
																				   "a.Status =" => $default[CloseStatus], 
																				   "h.ForUserType"=>1
																				   )
																			); 
																			 
                            $last_reg_date = (count($old) > 1) ? $old[0]->DateAdded : $old->DateAdded;
                            $time = strtotime($last_reg_date);
                            $next_reg = strtotime('next monday', $time);
                            $today = strtotime(date("Y-m-d H:i:s"));
                            if ($today < $next_reg) {
                                $return = array("success" => false, "message" => "You can only subscribe to this promotion once a week.", "error_code" => "once_a_week");
                                echo json_encode($return);
                                exit;
                            }
                        }//number game 
                        elseif ((trim($data[bonus_code]) == $diamond_first_20) || (trim($data[bonus_code]) == $diamond_welcome)) {
                            $old = $this->promotions->checkWebsitePromotion_(array("FIND_IN_SET(h.BonusCode, '{$one_only}') !=" => 0,
																				   "a.Username =" => trim($data[username]),
																				   "FIND_IN_SET(a.Status, '{$default[NotStatusDiamond]}') =" => 0, 
																				   "h.ForUserType"=>1
																				   )
																				);

                            if (count($old) > 0) {
                                $return = array("success" => false, "message" => "You already subscribed your welcome/first deposit bonus", "error_code" => "atleast_one");
                                echo json_encode($return);
                                exit;
                            }
                        } elseif (in_array($data[bonus_code], $daily_bonus)) {
                            $old = $this->promotions->checkWebsitePromotion_(array("h.BonusCode =" => trim($data[bonus_code]),
																				   "a.Username =" => trim($data[username]),
																				   "DATE(a.DateUpdated) =" => date("Y-m-d"), 
																				   "h.ForUserType"=>1
																				  )
																				);

                            if (count($old) > 0) {
                                $return = array("success" => false, "message" => "Bonus claimed for this day", "error_code" => "once_a_day");
                                echo json_encode($return);
                                exit;
                            }
                        } else {
                            $old = $this->promotions->checkWebsitePromotion_(array("h.BonusCode =" => trim($data[bonus_code]), 
																				   "a.Username =" => trim($data[username]), 
																				   "h.ForUserType"=>1
																				  )
																			);
                            if (count($old) > 0) {
                                $return = array("success" => false, "message" => "You already subscribed to this promotion", "error_code" => "one_time");
                                echo json_encode($return);
                                exit;
                            }
                        }
                    }
                }
                //IF NOT CASHBACK AND USER HAS A PENDING PROMOTION REQUEST IN CAL 

                $check_reg = $this->promotions->getWebsiteRegisterById_(array("a.UniqueID =" => trim($unique_id)), "a.RegisterID");

                if (count($check_reg) > 0) {
                    $return = array("success" => false, "message" => "Unique ID already registered", "error_code" => "one_time");
                    echo json_encode($return);
                    exit;
                } else {
                    $register_data = array("BonusCode" => trim($data[bonus_code]),
                        "Currency" => trim($data[currency]),
                        "Username" => trim($data[username]),
                        "UniqueID" => trim($unique_id),
                        "Market" => trim($data[market]),
                        "StringParameters" => trim($full_url),
                        "PostData" => trim($post_str),
                        "ReferrerUrl" => trim($referrer_address),
                        "DateAdded" => trim($current_date)
                    );
                    $last_id = $this->promotions->manageActivity_("csa_website_register", $register_data, "add", '', '');
                    if (count($last_id) <= 0) {
                        $return = array("success" => false, "message" => "Error inserting to register table", "error_code" => "error_register");
                        echo json_encode($return);
                        exit;
                    }
                }


                $post_data = array("Username" => trim($data[username]),
                    "Currency" => trim($promotion->CurrencyID),
                    "Promotion" => trim($promotion->PromotionID),
                    "Product" => trim($promotion->ProductID),
                    "Category" => trim($promotion->CategoryID),
                    "DepositAmount" => $deposit_amt,
                    "MinimumAmount" => trim($promotion->MinimumAmount),
                    "MaximumAmount" => trim($promotion->MaximumAmount),
                    "TurnOver" => trim($promotion->Turnover),
                    "BonusRate" => trim($promotion->BonusRate),
                    "Formula" => trim($promotion->Formula),
                    "WageringFormula" => trim($promotion->WageringFormula),
                    'BonusAmount' => $bonus_amt,
                    'WageringAmount' => $wagering_amt,
                    "AddedBy" => $default[AddedBy],
                    "DateAdded" => $current_date,
                    "UpdatedBy" => $default[UpdatedBy],
                    "DateUpdated" => $current_date,
                    "DateAddedInt" => strtotime($current_date),
                    "DateUpdatedInt" => strtotime($current_date),
                    "PromotionStartDate" => trim($promotion->StartedDate),
                    "PromotionEndDate" => trim($promotion->EndDate),
                    "GroupAssignee" => $default[GroupAssignee],
                    "Status" => $default[Status],
                    "Source" => $default[Source],
                    "TransactionID" => trim($data[prom_depositID]),
                    "UserLastDigitMobile" => trim($data[prom_mobile])
                );

                $last_id = $this->promotions->manageActivity_("csa_promotion_activities", $post_data, 'add', '', '');
                if ($last_id > 0) {

                    $history_data = array(
                        'ActivityID' => $last_id,
                        'Activity' => "promotion",
                        'Status' => $default[Status],
                        'Remarks' => $default[Remarks],
                        'UpdatedBy' => $default[UpdatedBy],
                        'DateUpdated' => $current_date,
                        'DateUpdatedInt' => strtotime($current_date),
                        'GroupAssignee' => $default[GroupAssignee]
                    );

                    $y = $this->promotions->manageActivity_("csa_activities_history", $history_data, "add", '', '');

                    //ADD TO 12BET USERS 
                    $user12bet_insert = insert_12bet_user($post_data, $this->activity_type);

                    $return = array("success" => true, "message" => "Save", "error_code" => "saved");
                } else {
                    $return = array("success" => false, "message" => "Error saving to database", "error_code" => "error_register_promotion");
                }
            } else {
                $return = array("success" => false, "message" => "Promotion not found", "error_code" => "code_not_found");
            }
        } else {
            $return = array("success" => false, "message" => "Wrong parameters", "error_code" => "wrong_parameters");
            //  $return = array("success" => false, "message" => "token-->" . $data[token] . "key-->" . $key . "valid key-->" . $valid_key . "unique-->" . $unique_id . "currency-->" . $data['prom_currency'], "error_code" => "wrong_parameters");
        }

        echo json_encode($return);
    }

    public function registerPromotions($actual = 0) {

        $data = $this->input->post();
        $view_statuslist = array();
        $result = $this->common->getUserStatusViews(3); //3-promotions
        $view_statuslist = explode(',', $result->StatusList);
    }

}

/* End of file promotions.php */
/* Location: ./application/controllers/promotions.php */