<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('employee', 'employee');
        $this->load->model("common_model", "common");
    }

    public function index($rand = "") {
		
		if(date("Y/m/d H:i") < $this->config->item('maintenance_uptime') && $this->input->ip_address() != "10.120.10.221" && $this->input->ip_address() != "10.120.0.221") {
			redirect("error/under-maintenance");
		}
		
        if ($this->session->userdata("mb_no") && $this->session->userdata("mb_session_key")) {
            //redirect("dashboard");	
            redirect("login/logout");
        }

        //$this->session->sess_destroy();
        $data = array("page_title" => "CAL - Login"
        );
        $this->load->view('header', $data);
        $this->load->view('login_tpl');
        $this->load->view('footer');  
    }

    public function logout() {
        $last_activity = mdate("%Y-%m-%d %H:%i:%s");
        $this->employee->update_table_entry(
                "member_login", array(
            "date_logout" => $last_activity,
            "logout_by" => "user"
                ), array(
            "mb_no" => $this->session->userdata("mb_no"),
            "date_logout" => "0000-00-00 00:00:00"
                )
        );
        $this->session->unset_userdata();
        $this->session->sess_destroy();
        //$this->index();
        redirect("login");
    }

    public function validate_user() {
        //$admin_types = array(7,6,2);
        $username = trim($this->input->post('username'));
        $password = $this->input->post('password');
        if ($this->employee->validate_credentials($username, $password)) {
            $empdata = $this->employee->get_employee(array("mb_username" => $username, "mb_password" => $password, "mb_status" => 1));
            if (!empty($empdata)) {
                $last_activity = mdate("%Y-%m-%d %H:%i:%s");
                $session_key = md5($empdata->mb_username . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $last_activity);

                //Set the session variables 
                //$page_view = (in_array($empdata->mb_usertype, $admin_types))?"approvers":"checkers";

                $this->session->set_userdata(
                        array(
                            "mb_no" => $empdata->mb_no,
                            "mb_id" => $empdata->mb_id,
                            "mb_nick" => $empdata->mb_nick,
                            "mb_username" => $empdata->mb_username,
							"mb_internal_user" => $empdata->mb_internal_user,
                            "mb_usertype" => $empdata->mb_usertype,
                            "mb_level" => $empdata->mb_level,
                            //"mb_usertypename"=>$empdata->UserTypeName, 
                            //"mb_deptno"=>$empdata->mb_deptno, 
                            "mb_profilepic" => $empdata->mb_profilepic,
                            "mb_last_activity" => $last_activity,
                            "mb_currencies" => $empdata->mb_currencies,
                            "mb_session_key" => $session_key
                        //"mb_pageview"=>$page_view
                        )
                );


                //$this->session->set_userdata('mb_pageview', $page_view);
                //Update the date and status of the g4_member table		
                if (substr($empdata->mb_today_login, 0, 10) != $this->config->config['time_ymd']) {
                    $update_arr = array(
                        "mb_last_activity" => $last_activity,
                        "mb_today_login" => $last_activity,
                        "mb_login_ip" => $_SERVER['REMOTE_ADDR'],
                        "mb_islogin" => 1,
                        "mb_session_key" => $session_key
                    );
                } else {
                    $update_arr = array(
                        "mb_last_activity" => $last_activity,
                        "mb_login_ip" => $_SERVER['REMOTE_ADDR'],
                        "mb_islogin" => 1,
                        "mb_session_key" => $session_key
                    );
                }

                $this->employee->update_entry(
                        $update_arr, array(
                    "mb_no" => $empdata->mb_no
                        )
                );

                //Check if the member is currently logged in
                $emplog = $this->employee->get_table_entry(
                        "member_login", array(
                    "mb_no" => $empdata->mb_no,
                    "date_logout" => "0000-00-00 00:00:00"
                        )
                );

                //Update the logout time of existing connection
                if (!empty($emplog)) {
                    $this->employee->update_table_entry(
                            "member_login", array(
                        "date_logout" => $last_activity,
                        "logout_by" => "system"
                            ), array(
                        "mb_no" => $empdata->mb_no,
                        "date_logout" => "0000-00-00 00:00:00"
                            )
                    );
                }

                //Insert a new record on the login history
                $this->employee->insert_table_entry(
                        "member_login", array(
                    "mb_no" => $empdata->mb_no,
                    "ip_address" => $_SERVER['REMOTE_ADDR'],
                    "session_key" => $session_key,
                    "date_login" => $last_activity
                        )
                );

                if (admin_access()) {
                    $url = "dashboard";
                } else {
                    /* if($this->session->userdata('mb_usertype') == '6')//6-HR
                      {
                      $url = "warning";
                      }
                      else
                      {
                      $url = "operations";
                      } */
                    $url = "dashboard";
                }

                $return = array("has_err" => false, "msg" => "", "url" => $url, "session_key" => $this->session->userdata("mb_session_key"));
            } else {
                $return = array("has_err" => true, "msg" => "An error occured please try again.");
            }
        } else {
            $return = array("has_err" => true, "msg" => "Invalid username/password");
        }
        echo json_encode($return);
    }

    
    public function crossLogin_OLD() {
        $username = $this->input->get('username');
        $session_key = $this->input->get('session_key');
        $encryption_key = $this->input->get('encryption_key');

        if ($this->session->userdata('mb_session_key') && $this->session->userdata('mb_no')) {
            if (view_access()) {
                redirect("dashboard");
            } else {
                redirect("login?error=intranet");
            }
            exit;
        }

        if ($username && $session_key && $encryption_key) {
            if (md5($username . $session_key) == $encryption_key) {//check if valid encryption
                //check if login in intranet
                $exist = $this->employee->checkCrossSession_(array("mb_username" => $username,
                    "session_key" => $session_key,
                    "date_logout" => "0000-00-00 00:00:00",
                    "logout_by" => ""
                        ), "member_login"
                );
                if ($exist) {//login using username only
                    //$admin_types = array(7, 6);
                    $empdata = $this->employee->get_employee(array("mb_username" => $username, "mb_status" => 1, "mb_name" => "csa"));
                    if (!empty($empdata)) {
                        $last_activity = mdate("%Y-%m-%d %H:%i:%s");
                        $session_key = md5($empdata->mb_username . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $last_activity);

                        //Set the session variables 
                        //$page_view = (in_array($empdata->mb_usertype, $admin_types))?"approvers":"checkers";

                        $this->session->set_userdata(
                                array(
                                    "mb_no" => $empdata->mb_no,
                                    "mb_id" => $empdata->mb_id,
                                    "mb_nick" => $empdata->mb_nick,
                                    "mb_username" => $empdata->mb_username,
                                    "mb_usertype" => $empdata->mb_usertype,
                                    "mb_level" => $empdata->mb_level,
                                    //"mb_usertypename"=>$empdata->UserTypeName, 
                                    //"mb_deptno"=>$empdata->mb_deptno,
                                    "mb_profilepic" => $empdata->mb_profilepic,
                                    "mb_last_activity" => $last_activity,
                                    "mb_currencies" => $empdata->mb_currencies,
                                    "mb_session_key" => $session_key
                                //"mb_pageview"=>$page_view
                                )
                        );

                        //Update the date and status of the g4_member table		
                        if (substr($empdata->mb_today_login, 0, 10) != $this->config->config['time_ymd']) {
                            $update_arr = array(
                                "mb_last_activity" => $last_activity,
                                "mb_today_login" => $last_activity,
                                "mb_login_ip" => $_SERVER['REMOTE_ADDR'],
                                "mb_islogin" => 1,
                                "mb_session_key" => $session_key
                            );
                        } else {
                            $update_arr = array(
                                "mb_last_activity" => $last_activity,
                                "mb_login_ip" => $_SERVER['REMOTE_ADDR'],
                                "mb_islogin" => 1,
                                "mb_session_key" => $session_key
                            );
                        }

                        $this->employee->update_entry(
                                $update_arr, array(
                            "mb_no" => $empdata->mb_no
                                )
                        );

                        //Check if the member is currently logged in
                        $emplog = $this->employee->get_table_entry(
                                "member_login", array(
                            "mb_no" => $empdata->mb_no,
                            "date_logout" => "0000-00-00 00:00:00"
                                )
                        );

                        //Update the logout time of existing connection
                        if (!empty($emplog)) {
                            $this->employee->update_table_entry(
                                    "member_login", array(
                                "date_logout" => $last_activity,
                                "logout_by" => "system"
                                    ), array(
                                "mb_no" => $empdata->mb_no,
                                "date_logout" => "0000-00-00 00:00:00"
                                    )
                            );
                        }

                        //Insert a new record on the login history
                        $this->employee->insert_table_entry(
                                "member_login", array(
                            "mb_no" => $empdata->mb_no,
                            "ip_address" => $_SERVER['REMOTE_ADDR'],
                            "session_key" => $session_key,
                            "date_login" => $last_activity
                                )
                        );

                        $url = "dashboard";

                        /* if(admin_access())
                          {
                          $url = "dashboard";
                          }
                          else
                          {
                          if($this->session->userdata('mb_usertype') == '6')//6-HR
                          {
                          $url = "warning";
                          }
                          else
                          {
                          $url = "operations";
                          }
                          } */

                        $return = array("has_err" => false, "msg" => "", "url" => $url, "session_key" => $this->session->userdata("mb_session_key"));

                        if ($this->input->get('to_portal')) {
                            redirect('portal/dashboard');
                        } else {
                            redirect($url);
                        }
                    } else {
                        $return = array("has_err" => true, "msg" => "An error occured please try again.");
                        redirect("error");
                    }
                } else {
                    redirect("login");
                }
            } else {
                redirect("login");
            }
        } else {
            redirect("login");
        }
    }
	 
	public function crossLogin() {
        $username = $this->input->get('username');
        $session_key = $this->input->get('session_key');
        $encryption_key = $this->input->get('encryption_key');

        if ($this->session->userdata('mb_session_key') && $this->session->userdata('mb_no')) {
            if (view_access()) {
                redirect("dashboard");
            } else {
                redirect("login?error=intranet");
            }
            exit;
        }

        if ($username && $session_key && $encryption_key) {
            if (md5($username . $session_key) == $encryption_key) {//check if valid encryption
                
				//check if login in intranet  
				$api_data = array("mb_username" =>$username,
								  "date_logout" =>"0000-00-00 00:00:00",
								  "session_key" =>$session_key,
								  "logout_by" => "", 
								  "from_cal"=>'1'
								 );
				
				$exist = call_api("POST", trim($this->common->intranet_api["login_url"]), $api_data);     
 
                if ($exist == true) {//login using username only
                    //$admin_types = array(7, 6);
                    $empdata = $this->employee->get_employee(array("mb_username" => $username, "mb_status" => 1, "mb_name" => "csa"));
                    if (!empty($empdata)) {
                        $last_activity = mdate("%Y-%m-%d %H:%i:%s");
                        $session_key = md5($empdata->mb_username . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $last_activity);

                        //Set the session variables 
                        //$page_view = (in_array($empdata->mb_usertype, $admin_types))?"approvers":"checkers";

                        $this->session->set_userdata(
                                array(
                                    "mb_no" => $empdata->mb_no,
                                    "mb_id" => $empdata->mb_id,
                                    "mb_nick" => $empdata->mb_nick,
                                    "mb_username" => $empdata->mb_username,
                                    "mb_usertype" => $empdata->mb_usertype,
                                    "mb_level" => $empdata->mb_level,
                                    //"mb_usertypename"=>$empdata->UserTypeName, 
                                    //"mb_deptno"=>$empdata->mb_deptno,
                                    "mb_profilepic" => $empdata->mb_profilepic,
                                    "mb_last_activity" => $last_activity,
                                    "mb_currencies" => $empdata->mb_currencies,
                                    "mb_session_key" => $session_key
                                //"mb_pageview"=>$page_view
                                )
                        );

                        //Update the date and status of the g4_member table		
                        if (substr($empdata->mb_today_login, 0, 10) != $this->config->config['time_ymd']) {
                            $update_arr = array(
                                "mb_last_activity" => $last_activity,
                                "mb_today_login" => $last_activity,
                                "mb_login_ip" => $_SERVER['REMOTE_ADDR'],
                                "mb_islogin" => 1,
                                "mb_session_key" => $session_key
                            );
                        } else {
                            $update_arr = array(
                                "mb_last_activity" => $last_activity,
                                "mb_login_ip" => $_SERVER['REMOTE_ADDR'],
                                "mb_islogin" => 1,
                                "mb_session_key" => $session_key
                            );
                        }

                        $this->employee->update_entry(
                                $update_arr, array(
                            "mb_no" => $empdata->mb_no
                                )
                        );

                        //Check if the member is currently logged in
                        $emplog = $this->employee->get_table_entry(
                                "member_login", array(
                            "mb_no" => $empdata->mb_no,
                            "date_logout" => "0000-00-00 00:00:00"
                                )
                        );

                        //Update the logout time of existing connection
                        if (!empty($emplog)) {
                            $this->employee->update_table_entry(
                                    "member_login", array(
                                "date_logout" => $last_activity,
                                "logout_by" => "system"
                                    ), array(
                                "mb_no" => $empdata->mb_no,
                                "date_logout" => "0000-00-00 00:00:00"
                                    )
                            );
                        }

                        //Insert a new record on the login history
                        $this->employee->insert_table_entry(
                                "member_login", array(
                            "mb_no" => $empdata->mb_no,
                            "ip_address" => $_SERVER['REMOTE_ADDR'],
                            "session_key" => $session_key,
                            "date_login" => $last_activity
                                )
                        );

                        $url = "dashboard";

                        /* if(admin_access())
                          {
                          $url = "dashboard";
                          }
                          else
                          {
                          if($this->session->userdata('mb_usertype') == '6')//6-HR
                          {
                          $url = "warning";
                          }
                          else
                          {
                          $url = "operations";
                          }
                          } */

                        $return = array("has_err" => false, "msg" => "", "url" => $url, "session_key" => $this->session->userdata("mb_session_key"));

                        if ($this->input->get('to_portal')) {
                            redirect('portal/dashboard');
                        } else {
                            redirect($url);
                        }
                    } else {
                        $return = array("has_err" => true, "msg" => "An error occured please try again.");
                        redirect("error");
                    }
                } else {
                    redirect("login");
                }
            } else {
                redirect("login");
            }
        } else {
            redirect("login");
        }
    }
	

}

/* End of file login.php */
/* Location: ./application/controllers/login.php */