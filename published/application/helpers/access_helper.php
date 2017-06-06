<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */
// ------------------------------------------------------------------------

/**
 * CodeIgniter Download Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/helpers/download_helper.html
 */
// ------------------------------------------------------------------------ 

if (!function_exists('can_credit_bonus')) {

    function can_credit_bonus() {
        $can_access_specific = array(); //allow to specific person 
        $can_access_group = array(11, 12); //allowed user type 6- john, 11-patrick, 12-st supervisor
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access_group) || in_array($obj->session->userdata("mb_no"), $can_access_specific)) ? 1 : 0;
        return $access;
    }

}

if (!function_exists('call_api')) {

    function call_api($method = "POST", $url, $data) {

        $data_string = json_encode($data);
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        //curl_setopt ($c, CURLOPT_CONNECTTIMEOUT, 0);
        //curl_setopt ($c, CURLOPT_TIMEOUT, 20);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);                                                                                               

        $result = curl_exec($ch);

        if ($result === false) {
            $return = json_encode(array("Success" => 0, "Message" => "Error in CURL. " . curl_error($ch)));
        } else {
            $return = $result;
        }


        curl_close($ch); 
		
        return $return;
    }

}

if (!function_exists('can_offer_promotion')) {

    function can_offer_promotion() {
        $can_access_specific = array(); //allow to specific person 
        $can_access_group = array(10); //allowed user type
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access_group) || in_array($obj->session->userdata("mb_no"), $can_access_specific) || admin_access()) ? 1 : 0;
        return $access;
    }

}

if (!function_exists('days_diff')) {

    function days_diff($from_date, $to_date = "") {
        $to_date = ($to_date) ? $to_date : date("Y-m-d H:i:s");
        $datetime1 = strtotime($from_date);
        $datetime2 = strtotime($to_date);
        $secs = $datetime2 - $datetime1; // == <seconds between the two times>
        $days = intval($secs / 86400);
        return $days;
    }

}

if (!function_exists('can_view_crm_record')) {

    function can_view_crm_record() {
        $can_access_specific = array(); //allow to specific person 119-meaw
        $can_access_group = array(7, 6, 13, 15, 8, 5, 14, 3, 10, 16); //allowed user type
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access_group) || in_array($obj->session->userdata("mb_no"), $can_access_specific) || admin_access()) ? 1 : 0;
        return $access;
    }

}

if (!function_exists('can_upload_crm_record')) {

    function can_upload_crm_record() {
        $can_access_specific = array(); //allow to specific person
        $can_access_group = array(7, 6, 13, 15, 8, 3); //allowed user type
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access_group) || in_array($obj->session->userdata("mb_no"), $can_access_specific) || admin_access()) ? 1 : 0;
        return $access;
    }

}

if (!function_exists('can_cal_system')) {

    function can_cal_system() {
        $can_access = array(1); //crm, kk/chase, maggie, jeffrey, risk management
        $obj = & get_instance();  //get instance, access the CI superobject 
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access) || admin_access() || csd_supervisor_access() ) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('can_crm_calls')) {

    function can_crm_calls() {
        $can_access = array(10, 8, 5, 14, 16); //crm, kk/chase, maggie, jeffrey, risk management, Management Team
        $obj = & get_instance();  //get instance, access the CI superobject 
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access) || admin_access() || csd_supervisor_access() ) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('allow_post_view_notification')) {
    
    function allow_post_view_notification($returnid = false){
        $allowed = Array(10,13,1,2,6);
        if($returnid) return $allowed;
        $obj = & get_instance();  //get instance, access the CI superobject 
        return (in_array($obj->session->userdata("mb_usertype"), $allowed) || admin_access()) ? true : false ;
    }

}

if (!function_exists('call_internal_api')) {

    function call_internal_api($url, $data = array()) {
        //$data = array ('foo' => 'bar', 'bar' => 'baz');
        $data = http_build_query($data);

        $context_options = array(
            'http' => array(
                'method' => 'POST',
                'header' => "Content-type: application/x-www-form-urlencoded\r\n"
                . "Content-Length: " . strlen($data) . "\r\n",
                'content' => $data
            )
        );

        $context = stream_context_create($context_options);
        $fp = fopen($url, 'r', false, $context);

        return $fp;
    }

}

if (!function_exists('strict_backup_url')) {

    function strict_backup_url() {
        $can_access = array(115); //allow to kelly-mb_no
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_no"), $can_access) || strict_management()) ? 1 : 0;

        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('strict_management')) {

    function strict_management() {
        $can_access = array(7, 5, 15, 16); //user types
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access)) ? 1 : 0;

        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}


//INSERT TO 12BET USERS TABLE
if (!function_exists('insert_12bet_user')) {

    function insert_12bet_user($data, $activity_type = '') {
        $obj = & get_instance();  //get instance, access the CI superobject
        $user_data = array("Username" => trim($data['Username']),
            "Currency" => trim($data['Currency']),
            "Activity" => trim($activity_type),
            "UpdatedBy" => $data['UpdatedBy'],
            "DateUpdated" => trim($data['DateUpdated'])
        );

        $user_12bet = $obj->common->get12betUserById_(array("a.Username =" => trim($data['Username'])));

        if (trim($data[SystemID]) != "")
            $user_data[SystemID] = trim($data['SystemID']);
        if (count($user_12bet) <= 0) {//add new record
            $user_data[AddedBy] = $data[UpdatedBy];
            $user_data[DateAdded] = trim($data['DateUpdated']);
            $user_rec = $obj->common->insert12betUser_("csa_12bet_users", $user_data, "add", '', '');
        } else {
            $user_rec = $obj->common->insert12betUser_("csa_12bet_users", $user_data, "update", 'UserID', $user_12bet->UserID);
        }

        return $user_rec;
    }

}

if (!function_exists('report_module')) {

    function report_module() {
        $can_access = array(10, 8, 5, 14, 3, 1, 16); //crm, kk/chase, maggie, jeffrey, risk management
        $obj = & get_instance();  //get instance, access the CI superobject 
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access) || admin_access() || csd_supervisor_access() ) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('change_date_index')) {

    function change_date_index() {
        $access = array(7, 6, 13, 15, 2, 8);
        $obj = & get_instance();  //get instance, access the CI superobject
        $admin = (in_array($obj->session->userdata("mb_usertype"), $access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $admin;
    }

}


//clean up currency
if (!function_exists('download_report')) {

    function download_report($filepath, $filename = "report.xls") {
        $obj = & get_instance();
        $obj->load->helper('download');

        if (file_exists($filepath)) {
            $download = 1;
            $data = file_get_contents($filepath); // Read the file's contents  
            $x = force_download($filename, $data);
        } else {
            $download = 0;
            /* error_page("404 Access forbidden", "404 Access forbidden", "The page you requested was not found. Check URL.", "404"); */
            redirect("error");
        }
        return $x;
    }

}


if (!function_exists('shift_report')) {

    function shift_report() {
        $can_access = array(1, 2, 10, 13); //10-crm 13-crm sup
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('shift_report_all')) {

    function shift_report_all() {
        $can_access = array(2,13);
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('can_post_shift_report')) {

    function can_post_shift_report() {
        $can_access = array(1,2);
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $access;
    }

}

if (!function_exists('can_override')) {

    function can_override($assignee = '') {
        $obj = & get_instance();  //get instance, access the CI superobject 

        $user_type = $obj->common->getUserTypes_(array("GroupID =" => $obj->session->userdata("mb_usertype")));
        $override_type = explode(',', $user_type[0]->CanOverride);
        $access = (in_array($assignee, $override_type) || ($assignee == $obj->session->userdata('mb_usertype')) && $assignee ) ? 1 : 0;

        return $access;
    }

}


if (!function_exists('custom_group_chat')) {

    function custom_group_chat($groups) {
        $chat = array();
        $obj = & get_instance();  //get instance, access the CI superobject

        $currencies2 = explode(',', $obj->session->userdata('mb_currencies'));
        //create groups
        foreach ($groups as $row => $group) {
            $specific_users = ($group->SpecificUsers) ? explode(',', $group->SpecificUsers) : array();
            if ($group->Currency <= 0) {
                /* if(count($specific_users) > 0 && $group->SpecificUsers)
                  {
                  if(in_array($obj->session->userdata('mb_no'), $specific_users) )array_push($chat, $group);
                  }
                  else
                  {
                  array_push($chat, $group);
                  } */

                array_push($chat, $group);
            } else {
                if (in_array($group->Currency, $currencies2)) {
                    /* if(count($specific_users) > 0 && $group->SpecificUsers)
                      {
                      if(in_array($obj->session->userdata('mb_no'), $specific_users))array_push($chat, $group);
                      }
                      else
                      {
                      array_push($chat, $group);
                      } */
                    array_push($chat, $group);
                }
            }
        }//end foreach

        return $chat;
    }

}

if (!function_exists('can_check')) {

    function can_check() {
        $can_access = array(1, 2, 5, 14, 15, 16);
        $obj = & get_instance();  //get instance, access the CI superobject
        $admin = (in_array($obj->session->userdata("mb_usertype"), $can_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $admin;
    }

}


if (!function_exists('can_edit_upload')) {

    function can_edit_upload() {
        $can_access = array(7, 6, 13, 15);
        $obj = & get_instance();  //get instance, access the CI superobject
        $admin = (in_array($obj->session->userdata("mb_usertype"), $can_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $admin;
    }

}


if (!function_exists('allow_agent_report')) {

    function allow_agent_report() {
        $admin_access = array(6, 7, 10, 2, 13, 15);
        $obj = & get_instance();  //get instance, access the CI superobject
        $allow = (in_array($obj->session->userdata("mb_usertype"), $admin_access)) ? 1 : 0; //$data->session->userdata("mb_access");  

        return $allow;
    }

}


if (!function_exists('view_management')) {

    function view_management() {
        $default_access = array(5, 14, 15, 16); //5-management
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $default_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$access = ($obj->session->userdata('mb_pageview') == "approvers")?$access:0; 
        return $access;
    }

}


if (!function_exists('manage_user')) {

    function manage_user() {
        //$view_access = array(11); //Patrick

        $view_access = array(7, 5, 15, 16);

        $obj = & get_instance();  //get instance, access the CI superobject
        return (in_array($obj->session->userdata("mb_usertype"), $view_access)) ? 1 : 0; //$data->session->userdata("mb_access");
    }

}

if (!function_exists('manage_promotion')) {

    function manage_promotion() {
        //allow to specific person: meaw, dao, danny, kristy, remmy, french, austin, andrew.hvt, aree, jack.cwj, jang, jazlynn.chy, mcastillo, wikandat
        $can_access_specific = array(114, 110, 253, 86, 292, 306, 102, 338, 241, 560, 111, 122);   
        $can_access_group = array(6, 7, 13, 15); //allowed user type john, patrick, st supervisor
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $can_access_group) || in_array($obj->session->userdata("mb_no"), $can_access_specific)) ? 1 : 0;
        return $access;
    }

}


if (!function_exists('is_login')) {

    function is_login($str = "") {
        $obj = & get_instance();
        return ($obj->session->userdata("mb_no") && $obj->session->userdata("mb_session_key")) ? 1 : 0;
    }

}


if (!function_exists('encode_string')) {

    function encode_string($str = "") {
        $obj = & get_instance();
        $obj->load->library('encrypt');
        $str = $obj->encrypt->encode($str);
        $str = str_replace("/", "+123+", $str);
        return $str;
    }

}


if (!function_exists('decode_string')) {

    function decode_string($str = "") {
        $obj = & get_instance();
        $obj->load->library('encrypt');
        $str = str_replace("+123+", "/", $str);
        return $obj->encrypt->decode($str);
    }

}


//delete old files being exported
if (!function_exists('delete_old_files')) {

    function delete_old_files($folder, $fileTypes = "*.xls") {
        //$obj = & get_instance(); 
        // Filetypes to check (you can also use *.*)
        $fileTypes = '*.xls';

        // minutes the files should get deleted
        $expire_time = 10; //mins
        //timestamp
        $current_time = time();

        // Find all files of the given file type
        foreach (glob($folder . $fileTypes) as $Filename) {

            // Read file creation time
            //$FileCreationTime = filectime($Filename);
            $FileCreationTime = filemtime($Filename);

            // Calculate file age in seconds
            $FileAge = $current_time - $FileCreationTime;

            // Is the file older than the given time span?
            if ($FileAge >= ($expire_time * 60)) {
                // For example deleting files:
                unlink($Filename);
            }
        }//end foreach
    }

}
//end delete old files being exported
//clean up currency
if (!function_exists('clean_currency')) {

    function clean_currency($amount) {
        $amount = str_replace(',', '', $amount);
        $amount = str_replace(' ', '', $amount);

        return $amount;
    }

}

//allow to export data
if (!function_exists('download_attachment')) {

    function download_attachment($files = array(), $options = array()) {
        $obj = & get_instance();

        if (count($files) == 1) {
            $obj->load->helper('download');

            $file = $files[0];
            $file_path = "./media/uploads/" . $file->Path;

            if ($file->Path && file_exists($file_path)) {
                $data = file_get_contents("./media/uploads/" . $file->Path); // Read the file's contents
                $new_name = $file->ClientFilename;
                force_download($new_name, $data);
            }
        } elseif (count($files) > 1) {
            $obj->load->library('zip');
            $attachments = array();
            foreach ($files as $row => $file) {
                //$this->zip->read_file("./media/uploads/".$file->Path, FALSE);  
                $file_path = "./media/uploads/" . $file->Path;
                if ($file->Path && file_exists($file_path)) {
                    $data = file_get_contents($file_path);
                    $full_path_with_name = str_replace("\\", "/", $file_path);
                    $path_only = dirname($full_path_with_name);
                    $new_name = $path_only . '/' . $file->ClientFilename;
                    $new_name = preg_replace("|.*/(.+)|", "\\1", $new_name);

                    if (in_array($new_name, $attachments))
                        $new_name = $file->AttachID . '_' . $new_name;
                    array_push($attachments, $new_name);

                    $obj->zip->add_data($new_name, $data);
                }
            }//end foreach
            //if(!file_exists())$this->zip->archive('/path/to/directory/'.$this->input->get('activity_id')."_attachment".'.zip'); 
            $return = $obj->zip->download($options['id'] . "_attachment" . '.zip');
        }
        else {
            
        }
    }

}


//upload data
if (!function_exists('upload_file')) {

    function upload_file($options = array()) {
        $obj = & get_instance();
        $dir = "media/uploads/" . $options['upload_path'];
        //$path = "./media/uploads/".$options['upload_path'];  
        $path = dirname($_SERVER["SCRIPT_FILENAME"]) . "/" . $dir;
        if (!is_dir($path)) {
            mkdir($path, 0777);
        }

        $config['upload_path'] = $path;
        $config['upload_url'] = base_url() . $dir;
        $config['allowed_types'] = (count($options['allowed_types'])) ? implode('|', $options['allowed_types']) : "gif|jpg|png|xls|xlsx|doc|docx|ods|odt|rar|zip";
        $config['max_size'] = ($options['max_size']) ? $options['max_size'] : '5000';
        $config['max_width'] = ($options['max_width']) ? $options['max_width'] : '1600';
        $config['max_height'] = ($options['max_height']) ? $options['max_height'] : '1200';

        //$config['encrypt_name'] = true;
        $config['overwrite'] = false;
        $config['remove_spaces'] = true;

        //$obj->load->library('upload', $config);
        $obj->load->library('upload');


        //$attach_files = array(); 
        //$files = $_FILES[$options['input_file']]; 

        $field = $options['input_file'];

        $files = array();
        foreach ($_FILES[$field] as $key => $all) {
            foreach ($all as $i => $val) {
                $files[$i][$key] = $val;
            }
        }

        $files_uploaded = array();
        for ($i = 0; $i < count($files); $i++) {
            $config['file_name'] = uniqid() . '_' . rand(1000, 9999) . '_' . $obj->session->userdata("mb_no");

            $obj->upload->initialize($config);

            $_FILES[$field] = $files[$i];
            if ($obj->upload->do_upload($field)) {
                $files_uploaded[$i] = $obj->upload->data($files);
                $success = 1;
            } else {
                //$files_uploaded[$i] = null; 
                $error = $obj->upload->display_errors();
                $success = 0;
                foreach ($files_uploaded as $key => $file) {
                    if ($file['full_path'])
                        unlink($file['full_path']);
                }
                break;
            }
        }//end for 
        return ($success > 0) ? array("success" => $success, "upload_data" => $files_uploaded) : array("success" => $success, "error" => $error);
    }

}


//allow to export data
if (!function_exists('allow_export_promotion')) {

    function allow_export_promotion($options) {
        $obj = & get_instance();
        $return = 1;
        $allow_usertype = array(3, 8, 12, 11, 5, 14, 15, 2, 6, 7, 13, 16);
        $allow_individual = array(68, 115); //jess   

        if ((!in_array($obj->session->userdata('mb_usertype'), $allow_usertype)) && (!in_array($obj->session->userdata('mb_no'), $allow_individual)) && ($obj->session->userdata('mb_level') < 5)) {
            $return = 0;
        }

        return $return;
    }

}


//allow to export data
if (!function_exists('allow_export_data')) {

    function allow_export_data($options) {
        $obj = & get_instance();
        $return = 1;
        $allow_usertype = array(3, 8, 12, 11, 5, 14, 15, 2, 6, 7, 13, 16);
        $allow_individual = array(); //specific id no
        if ((!in_array($obj->session->userdata('mb_usertype'), $allow_usertype)) && (!in_array($obj->session->userdata('mb_no'), $allow_individual)) && ($obj->session->userdata('mb_level') < 5)) {
            $return = 0;
        }

        return $return;
    }

}

//create pagination
if (!function_exists('create_pagination')) {

    function create_pagination($options) {
        $obj = & get_instance();
        $obj->load->library('pagination');

        $config['base_url'] = $options['link'];
        $config['total_rows'] = $options['total_rows'];
        $config['per_page'] = ($options['per_page']) ? $options['per_page'] : 20;
        $config['uri_segment'] = 3;
        $config['cur_page'] = $options['cur_page'];
        //$config['use_page_numbers'] = TRUE;
        //$config['page_query_string'] = TRUE;
        $config['num_links'] = 3;


        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '<li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '<li>';

        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['next_link'] = 'Next &#8594;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&#8592; Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';

        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';


        //$config['display_pages'] = FALSE; 

        $obj->pagination->initialize($config);

        $return = $obj->pagination->create_links();

        return $return;
    }

}

//check if the user is allowed to post remarks from (rm, management, settlement)
if (!function_exists('restriction_type')) {

    function restriction_type() {
        $obj = & get_instance();
        $return = 1;
        $restrict_usertype = array(5, 14, 3, 8, 4, 11, 12, 1, 10, 2, 16);
        //if (!isset($obj->session->userdata('mb_no')) || !isset($obj->session->userdata('mb_session_key')) || (!in_array($obj->session->userdata('mb_usertype'),$restrict_usertype)) ) 		{  
        if ((!in_array($obj->session->userdata('mb_usertype'), $restrict_usertype))) {
            $return = 0;
        }
        return $return;
    }

}


/**
 * ALLOW SEARCH
 *
 * Check if user is allowed to search activities 
 *  
 */
if (!function_exists('allow_search')) {

    function allow_search() {
        $admin_access = array(6, 7, 1, 2, 3, 4, 8, 5, 14, 15, 10, 13, 11, 12, 9, 16);
        $obj = & get_instance();  //get instance, access the CI superobject
        $allow = (in_array($obj->session->userdata("mb_usertype"), $admin_access)) ? 1 : 0; //$data->session->userdata("mb_access");  

        return $allow;
    }

}


/**
 * ADMIN ACCES
 *
 * Check if user is admin 
 *  
 */
if (!function_exists('super_admin')) {

    function super_admin() {
        $admin_access = array(7);
        $obj = & get_instance();  //get instance, access the CI superobject
        $admin = (in_array($obj->session->userdata("mb_usertype"), $admin_access)) ? 1 : 0; //$data->session->userdata("mb_access");  

        return $admin;
    }

}


if (!function_exists('can_upload_promotions')) {

    function can_upload_promotions() {
        $access = array(2);
        $obj = & get_instance();  //get instance, access the CI superobject ]

        $allow_individual = array(68, 115, 424, 559); //68-jess, 119-meaw, 115-kelly, 424-jessica, 559-helen
        $admin = (in_array($obj->session->userdata("mb_usertype"), $access) || in_array($obj->session->userdata("mb_no"), $allow_individual)) ? 1 : 0;

        return $admin;
    }

}


if (!function_exists('admin_only')) {

    function admin_only() {
        $admin_access = array(7, 6, 15, 5, 16);
        $obj = & get_instance();  //get instance, access the CI superobject
        $admin = (in_array($obj->session->userdata("mb_usertype"), $admin_access)) ? 1 : 0; //$data->session->userdata("mb_access");  

        return $admin;
    }

}

if (!function_exists('admin_access')) {

    function admin_access() {
        $admin_access = array(7, 6, 13, 15, 5, 16);
        $obj = & get_instance();  //get instance, access the CI superobject
        $admin = (in_array($obj->session->userdata("mb_usertype"), $admin_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $admin;
    }

}

if (!function_exists('csd_supervisor_access')) {

    function csd_supervisor_access() {
        $csd_supervisors = array(2);
        $obj = & get_instance();  //get instance, access the CI superobject
        $supervisor = (in_array($obj->session->userdata("mb_usertype"), $csd_supervisors)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $supervisor;
    }

}

if (!function_exists('additional_page_admins')) {

    function additional_page_admins() {
        $additional_page_admins = array(12, 11);
        $obj = & get_instance();  //get instance, access the CI superobject
        $page_admin = (in_array($obj->session->userdata("mb_usertype"), $additional_page_admins)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?1:$admin; 
        return $page_admin;
    }

}

//allow to export data
if (!function_exists('allowEditMain')) {

    function allowEditMain() {
        $obj = & get_instance();
        $return = 1;
        //$allow_usertype = array(6, 7, 1, 2);  
        $allow_usertype = array(6, 7, 13, 15);
        if ((!in_array($obj->session->userdata('mb_usertype'), $allow_usertype))) {
            $return = 0;
        }

        return $return;
    }

}


//allow knowledge portal - MACK
if (!function_exists('allow_knowledge_portal')) {

    function view_knowledge_portal() {
        $obj = & get_instance();
        $return = 1;
        $allow_usertype = array(1, 2, 5, 14, 15, 6, 7, 10, 16);
        if ((!in_array($obj->session->userdata('mb_usertype'), $allow_usertype))) {
            $return = 0;
        }

        return $return;
    }

}


if (!function_exists('check_url')) {

    function check_url($url) {
        /* if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) { 
          return 1;
          }
          else {
          return 0;
          } */

        if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
            return 0;
        } else {
            return 1;
        }
    }

}

if (!function_exists('strip_symbols')) {

    function strip_symbols($text) {
        $text = trim(iconv("UTF-8", "UTF-8//IGNORE", trim($text)));
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_NOQUOTES, "UTF-8");

        $plus = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
        $minus = '\x{2012}\x{208B}\x{207B}';

        $units = '\\x{00B0}\x{2103}\x{2109}\\x{23CD}';
        $units .= '\\x{32CC}-\\x{32CE}';
        $units .= '\\x{3300}-\\x{3357}';
        $units .= '\\x{3371}-\\x{33DF}';
        $units .= '\\x{33FF}';

        $ideo = '\\x{2E80}-\\x{2EF3}';
        $ideo .= '\\x{2F00}-\\x{2FD5}';
        $ideo .= '\\x{2FF0}-\\x{2FFB}';
        $ideo .= '\\x{3037}-\\x{303F}';
        $ideo .= '\\x{3190}-\\x{319F}';
        $ideo .= '\\x{31C0}-\\x{31CF}';
        $ideo .= '\\x{32C0}-\\x{32CB}';
        $ideo .= '\\x{3358}-\\x{3370}';
        $ideo .= '\\x{33E0}-\\x{33FE}';
        $ideo .= '\\x{A490}-\\x{A4C6}';

        return preg_replace(
                array(
            // Remove modifier and private use symbols.
            '/[\p{Sk}\p{Co}]/u',
            // Remove mathematics symbols except + - = ~ and fraction slash
            '/\p{Sm}(?<![' . $plus . $minus . '=~\x{2044}])/u',
            // Remove + - if space before, no number or currency after
            '/((?<= )|^)[' . $plus . $minus . ']+((?![\p{N}\p{Sc}])|$)/u',
            // Remove = if space before
            '/((?<= )|^)=+/u',
            // Remove + - = ~ if space after
            '/[' . $plus . $minus . '=~]+((?= )|$)/u',
            // Remove other symbols except units and ideograph parts
            '/\p{So}(?<![' . $units . $ideo . '])/u',
            // Remove consecutive white space
            '/ +/',
                ), ' ', $text);
    }

}








if (!function_exists('dual_view')) {

    function dual_view() {
        $dual_access = array(1, 2, 4);
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $dual_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$admin = ($obj->session->userdata('mb_pageview') == "approvers")?$admin:0; 
        return $access;
    }

}

if (!function_exists('view_only')) {

    function view_only() {
        //$default_access = array(7, 6, 2);
        $default_access = array(6, 7); //6-hr, 7-management
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $default_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$access = ($obj->session->userdata('mb_pageview') == "approvers")?$access:0; 
        return $access;
    }

}


if (!function_exists('view_management')) {

    function view_management() {
        $default_access = array(5, 14, 15, 16); //7-management
        $obj = & get_instance();  //get instance, access the CI superobject
        $access = (in_array($obj->session->userdata("mb_usertype"), $default_access)) ? 1 : 0; //$data->session->userdata("mb_access");  
        //$access = ($obj->session->userdata('mb_pageview') == "approvers")?$access:0; 
        return $access;
    }

}


if (!function_exists('notify_type')) {

    function notify_type() {
        $notify_access = array(1, 2, 4, 11, 12, 3, 8, 10); //3-operation, 4-supervisor
        $obj = & get_instance();  //get instance, access the CI superobject
        $notify = (in_array($obj->session->userdata("mb_usertype"), $notify_access)) ? 1 : 0; //$data->session->userdata("mb_access");  

        $notify = ($obj->session->userdata('mb_pageview') == "approvers") ? $notify : 0;
        return $notify;
    }

}


if (!function_exists('error_page')) {

    function error_page($page_title = "404 Page Not Found", $header_title = "404 Page Not Found", $message = "The page you requested was not found. Check URL.", $type = 404) {
        //RETURN 404
        $obj = & get_instance();
        $data = array("page_title" => $page_title,
            "header_title" => $header_title,
            "error_message" => $message,
            "error_type" => $type);
        $obj->load->view('header', $data);
        $obj->load->view('error_tpl');
        $obj->load->view('footer');
    }

}


if (!function_exists('view_access')) {

    function view_access() {
        $view_access = array(5, 14, 15, 3, 8, 4, 11, 12, 1, 2, 10, 9, 16);
        $obj = & get_instance();  //get instance, access the CI superobject
        return (in_array($obj->session->userdata("mb_usertype"), $view_access)) ? 1 : 0; //$data->session->userdata("mb_access");
    }

}

if (!function_exists('create_select2_tags')) {

    function create_select2_tags($values = array(), $params = array()) {
        $newemp = array();
        foreach ($values as $k => $val) {
            $newemp[$k]['id'] = $val->$params[0];
            $newemp[$k]['text'] = $val->$params[1];
        }
        return $newemp;
    }
}
    if (!function_exists('supervisor_rights')) {

        function supervisor_rights() {
            $array = array(116, 293);
            $obj = & get_instance();  //get instance, access the CI superobject
            return (in_array($obj->session->userdata("mb_no"), $array)) ? 1 : 0; //$data->session->userdata("mb_access");
        }

    }






    /* End of file admin_access.php */
    /* Location: ./application/helpers/download_helper.php */    