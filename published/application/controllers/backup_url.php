<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Backup_Url extends MY_Controller {

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
	 * @see http://codeigniter.com/url_guide/general/urls.html
	 */
	
	public function __construct(){
		parent::__construct();
		$this->load->model("backup_url_model","urls");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>1, "Label"=>"Active"), array("Value"=>0, "Label"=>"Inactive"), array("Value"=>2, "Label"=>"Release"));    
		
		$this->import_types = array('csv'); 
		$this->def_stat = 1; 
	}
 	
	public function index()
	{    
		$this->backupUrlList();    
	}
	 
	public function backupUrlList()
	{    
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  					
		$data2 = array("main_page"=>"backup_url",      	
					   "status_list"=>$this->status_list, 
					   "currencies"=>$this->common->getCurrency_() 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Backup URL", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('backup_url/backup_url_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getBackupUrlList()
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		   
		$search_string = "";   
		$search_arr = array(); 
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.UrlID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_url]))
		 {
			$search_string .= " AND (a.Url LIKE '%".$this->common->escapeString_(trim($data[s_url]))."%') "; 
			$search_url .= "&s_url=".trim($data[s_url]); 
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (FIND_IN_SET(".$this->common->escapeString_($data[s_currency]).", a.Currencies)) "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 }
		
		if(trim($data[s_blocked]))
		 {
			$search_string .= " AND (FIND_IN_SET(".$this->common->escapeString_($data[s_blocked]).", a.BlockedCurrencies)) "; 
			$search_url .= "&s_blocked=".trim($data[s_blocked]);   
		 } 
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_backup_url AS a", "a.UrlID")->TotalCount; 
	 	 
		$users = $this->urls->getBackupUrlList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"status":"status";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("status"=>$currencies, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("status"=>$this->generateHtmlBackupUrlList($users), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlBackupUrlList($urls)
	{
		$return = ""; 
		if(count($urls))
		 { 
			foreach($urls as $row=>$url){   
				if($url->Status > 0)
				 {
					$status_class = ($url->Status=='2' )?"green":""; 
				 }
				else
				 {
					$status_class = "act-danger"; 
				 }
				 
				
				$date_updated = ($url->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($url->DateUpdated)):''; 
				
				$return .= "
						<tr class=\"url_row\" id=\"Backup_UrlRow{$url->UrlID}\" > 
							<td class=\"center\" ><input type=\"checkbox\" value=\"{$url->UrlID}\" name=\"check_url[]\"  /></td>
							<td class=\"left\" >{$url->Url}</td>
							<td class=\"center\" >{$url->CurrencyNames}</td>    
							<td class=\"center\" >{$url->BlockedCurrencyNames}</td>    
							<td class=\"center {$status_class}\" >{$url->StatusName}</td>    
							<td class=\"center action\" >			 
								<a href=\"#UserModal\" title=\"edit url\" alt=\"edit url\" class=\"edit_url tip\" url-id=\"{$url->UrlID}\"  id=\"Edit{$url->UrlID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"8\" >Backup URL not found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageBackupUrl()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$url_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.UrlID =' => $url_id); 
		$url = ($url_id)?$this->urls->getBackupUrlById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "currencies"=>$this->common->getCurrency_(), 
					   "url"=>$url, 
					   "def_stat"=>$this->def_stat
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Backup URL", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('backup_url/backup_popup_tpl',$data);  
		 
	} 
	
	
	public function manageBackupUrl()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		 
		   
		$error = "";   
		$data = $this->input->post();   
		 
		 
		if($data[url_name] == "")
		 {
			 $error .= "Enter URL!<br>";
		 }
		else
		 { 
			$parse = parse_url(trim($data[url_name])); 
			$check_url = ($parse['host'])?$parse['host']:$parse['path']; 
			$check_url = str_replace("www.", '', strtolower($check_url));  
			 
			$where_arr = array();   
			$where_arr["a.Url LIKE '%{$check_url}' !="] = 0;
			
			if($data[hidden_urlid] > 0)
			 {
				 $where_arr["a.UrlID <>"] = $data[hidden_urlid];  
			 }
			 
			$check = $this->urls->getBackupUrlById_($where_arr, array());   
			if(count($check) > 0)$error .= "{$data[url_name]} is already exist. Please check!  <br>";
		 }
		 
		if($data[url_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		  
		$url_currencies = implode(',', $this->input->post("url_cur"));   
		$url_currencies = ($url_currencies)?$url_currencies:""; 
		
		$url_blocked = implode(',', $this->input->post("url_blo"));   
		$url_blocked = ($url_blocked)?$url_blocked:"";
		
		if($url_currencies == "")
		 {
			 $error .= "Select currency!<br>";
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
			 	 
			 	$post_data = array(  
					'Url'=>$data['url_name'], 
					'Description'=>$data['url_description'], 
					'Currencies'=>$url_currencies, 
					'BlockedCurrencies'=>$url_blocked,   
					'AddedBy'=>$this->session->userdata('mb_no'),
					'DateAdded'=>$current_date, 
					'UpdatedBy'=>$this->session->userdata('mb_no'),
					'DateUpdated'=>$current_date, 
					'Status'=>$data['url_status'] 
				 );   
				 
				$last_id = $this->urls->manageUrl_("csa_backup_url", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"URL added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding URL!");
				 }   
				 
			 }
			else
			 {     
				 $post_data = array(      
					'Url'=>$data['url_name'], 
					'Description'=>$data['url_description'], 
					'Currencies'=>$url_currencies, 
					'BlockedCurrencies'=>$url_blocked,     
					'UpdatedBy'=>$this->session->userdata('mb_no'),
					'DateUpdated'=>$current_date, 
					'Status'=>$data['url_status'] 
				 );  
				   
				 $changes = "";  
				  
				 $conditions_array = array('a.UrlID ='=>$data[hidden_urlid]);  
				 $old =  $this->urls->getBackupUrlById_($conditions_array);   
				 
				 $changes .= ($data['url_name'] != $old->Url)?"Url  changed to ".$data['url_name']." from ".$old->Url."|||":""; 
				 $changes .= ($data['url_description'] != $old->Description)?"Description changed to ".$data['url_description']." from ".$old->Description."|||":""; 
  				 $changes .= ($url_currencies != $old->Currencies)?"Currencies changed to ".$url_currencies." from ".$old->Currencies."|||":"";       
				 $changes .= ($url_blocked != $old->BlockedCurrencies)?"Blocked Currencies changed to ".$url_blocked." from ".$old->BlockedCurrencies."|||":""; 
				 $changes .= ($data['url_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {  
					$x = $this->urls->manageUrl_("csa_backup_url", $post_data, $action, 'UrlID', $data[hidden_urlid]);
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"URL updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						$return = array("success"=>0, "message"=>"Error updating URL!");
					 }  
				  }
				 else
				  { 
					//no changes    
					$return = array("success"=>1, "message"=>"No changes made!");
				  }
				
			 }//end else UPDATE
			  
		 }//end else NO ERROR 
		 
		 echo json_encode($return);
		
	} 
	
	public function popupUploadBackupUrl()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		if(restriction_type() && !can_upload_promotions())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 } 
		   
		
		$data2 = array("main_page"=>"backup_url",  
					   "status_list"=>$this->status_list,    
					   "currencies"=>$this->common->getCurrency_(), 
					   "def_stat"=>$this->def_stat
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Upload Backup URL ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('backup_url/backup_upload_popup_tpl',$data); 
		 
	} 
	
	public function uploadBackupUrl()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s");
		
		$error =  ""; 
		$filename = $_FILES['url_attachfile']['name']; 
		$ext = pathinfo($filename, PATHINFO_EXTENSION); 
		$upload_id = $this->session->userdata("mb_no").'-'.uniqid();
		
		if (!isset($_FILES['url_attachfile']) && empty($_FILES['url_attachfile']['name']))
		 {  
			 $error .= "Please select file to upload!<br> "; 
		 } 
		 
		if(!in_array($ext,$this->import_types) ) 
		{
			$error .= "Please select a valid file type!<br> ";
		}  
		
		if($data[url_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		   
		$url_currencies = implode(',', $this->input->post("url_cur"));    
		$url_currencies = ($url_currencies)?$url_currencies:""; 
		
		//$url_blocked = implode(',', $this->input->post("url_blo"));   
		
		if($url_currencies == "")
		 {
			 //$error .= "Select currency!<br>";
		 }
		 
		 
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 {    
			 $rows = array();    
			 $url_list = array();
			 //Import uploaded file to Database
			 
			 $handle = fopen($_FILES['url_attachfile']['tmp_name'], "r"); 
			 $x = 0;   
			 $all_ctr = 0; 
			 $duplicate_url =  "";
			 $url_error = "";
				 
			 while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) { 
				$url_name = strtolower(trim($record[0]));  
				  
				if($url_name && $url_name!="")
				 {  
				 	$url_name = (strpos($url_name, "://") === FALSE)?"http://".$url_name:$url_name;
				 	//check if url is valid   
				 	if(check_url($url_name) > 0)
					 {    
						$parse = parse_url(trim($url_name));
						$check_url = ($parse['host'])?$parse['host']:$parse['path'];  
						$check_url = str_replace("www.", '', strtolower($check_url)); 
						 
						$where_arr = array();   
						//if($data[hidden_urlid] > 0)$where_arr["a.UrlID <>"] = $data[hidden_urlid]; 
						$where_arr["a.Url LIKE '%{$check_url}' !="] = 0;
						$check = $this->urls->getBackupUrlById_($where_arr, array());   
					 
						if(count($check) <= 0 && (!in_array($check_url, $url_list)) )
						 {
							 $rows[$x] = array("Url"=>trim($url_name), 
											   "Currencies"=>trim($url_currencies),   
											   "AddedBy"=>$this->session->userdata("mb_no"),
											   "DateAdded"=>$current_date, 
											   "UpdatedBy"=>$this->session->userdata("mb_no"),
											   "DateUpdated"=>$current_date, 
											   "Status"=>trim($data[url_status])
											 ); 
							 $url_list[$check_url] = $url_name; 
							 
							 $x++;
						 }
						else
						 { 
							$duplicate_url .= $duplicate_url." is already exist. Please check! <br>";  
						 } 
						 
					 }
					else
					 {
						$url_error .= $url_name." is not valid! <br> ";	 
					 }
					  
					 
				 }  
				 
				 $all_ctr++;  
				 
			}//end while
  			 
			if(count($rows) > 0 && ($url_error == "") )
			 {
				$count_rec = $this->common->batchInsert_("csa_backup_url", $rows);
				$return = ($count_rec > 0)?array("success"=>1, "message"=>"File uploaded successfully. <br>Total Uploaded: <b>{$count_rec}</b> ", "records"=>$count_rec, "is_change"=>1):array("success"=>0, "message"=>"Error uploading promotions activities. Please check your uploaded file.! <br> "); 
			 }
			else
			 {
				 $return = array("success"=>0, "message"=>"No record to save. Please check your uploaded file! <br> Make sure you put http or https in all the URL! <br>".$url_error); 
			 }
			 
		 }
		
		
		echo json_encode($return);
		  
	} 
	
	public function updateBackupUrlStatus($actual=0)
	{
		 if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!strict_backup_url()) 
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
		
		
		$url_list = trim(implode(',', $this->input->post("check_url")));   
		$url_list = ($url_list)?$url_list:""; 
		$current_date = date("Y-m-d H:i:s"); 
		
		if($data[s_updatestatus]=="")
		 {
			 $error .= "Please select status!<br> ";
		 }
		 
		if($url_list=="")
		 {
			 $error .= "Please select URL(s) to update!<br> ";
		 }
		
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 { 
		 	$i = 0; 
		 	foreach($data[check_url] as $row=>$value){ 
				if($value)
				 {   
					$act_data[$i] = array( 
					  "UrlID"=>$value,
					  "Status"=>$data[s_updatestatus],    
					  "UpdatedBy"=>$this->session->userdata("mb_no"), 
					  "DateUpdated"=>$current_date 
					);   
					$i++;  
				 } 
			}//end foreach
			 
			$x = $this->common->batchUpdate_("csa_backup_url", $act_data, "UrlID");	   
			$return = ($x > 0)?array("success"=>1, "message"=>"URL status updated successfully."):array("success"=>0, "message"=>"Error updating URL status. Please check!");
		 } 
		
		echo json_encode($return);
		  
	}	   
	
	public function searchUrl()
	{      
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  					
		$data2 = array("main_page"=>"backup_url",      	
					   "status_list"=>$this->status_list, 
					   "currencies"=>$this->common->getCurrency_() 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Backup URL", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('backup_url/backup_url_search_tpl');
		$this->load->view('footer');   
	} 
	
	public function getSearchBackupUrl()
	{
		$where_arr = array();
		$data = $this->input->post();   
		$error = ""; 
		
		if(trim($data[s_url]) == "")
		 {
			$error .= "Enter backup URL!<br>";
		 } 
		
		if(check_url(trim($data[s_url])) <= 0 )
		 {
			$error .= "Enter valid URL!<br>";
		 } 
		
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error);  
		 }
		else
		 {
			$url_name = trim($data[s_url]);  
			$url_name = (strpos($url_name, "://") === FALSE)?"http://".$url_name:$url_name;  
			
			$parse = parse_url(trim($url_name));
			 
			
			$check_url = ($parse['host'])?$parse['host']:$parse['path']; 
			$check_url = str_replace("www.", '', strtolower($check_url)); 
			$where_arr["a.Status"] = '2';
			 	
			if($data[s_url]) $where_arr["a.Url LIKE '%{$check_url}' !="] = 0;
			  
			$results = $this->urls->getBackupUrlById_($where_arr);   
  		
			if(count($results) > 0) 
			 {
				 $return = array("success"=>1, "url"=>$results, "message"=>"URL found!");
			 } 
			else
			 {
				$return = array("success"=>0, "message"=>$data[s_url]." not found. Please add to record!"); 
			 } 
		 }
		 
		 
		echo  json_encode($return); 
	}
	
	public function getRelatedBackupUrl()
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
		   
		$search_string = "";   
		$search_arr = array();  
		
		$search_string .= " AND (a.Status='2') AND (a.UrlID <> {$data[s_urlid]}) ";  
		
		$currencies = explode(',', $data[s_currencies]);
		if(count($currencies) > 0)
		 {
			$search_string .= " AND ( "; 
			for($i=0; $i<count($currencies); $i++){
				$search_string .= " (FIND_IN_SET({$currencies[$i]}, a.Currencies)) OR"; 
			} 
			$search_string = trim($search_string, "OR");
			$search_string .= " ) ";
			
			$per_page = 20;  
		
			$page = ($data['s_page'])? $data['s_page'] : 0;  
			 
			$urls = $this->urls->getBackupUrlList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
		    
			$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
			$disp_page = ($page==0)?1:$page+1; 
			$plural_txt = ($total_rows > 1)?"status":"status";
			$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
			
			$return = array("urls"=>$this->generateHtmlBackupUrlListView($urls), 
						    "pagination"=>"", 
							"pagination_string"=>""
					   );
			 echo json_encode($return); 
			
		 }
	 	else
		 {
			 $return = array("urls"=>$this->generateHtmlBackupUrlListView(array()), 
						    "pagination"=>"", 
							"pagination_string"=>""
					   );
			 echo json_encode($return); 
		 }
   
	} 	
	 
	 
	public function generateHtmlBackupUrlListView($urls)
	{
		$return = ""; 
		if(count($urls))
		 { 
			foreach($urls as $row=>$url){   
				if($url->Status > 0)
				 {
					$status_class = ($url->Status=='2' )?"green":""; 
				 }
				else
				 {
					$status_class = "act-danger"; 
				 }
				 
				
				$date_updated = ($url->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($url->DateUpdated)):''; 
				
				$return .= "
						<tr class=\"url_row\" id=\"Backup_UrlRow{$url->UrlID}\" >  
							<td class=\"left\" >{$url->Url}</td>
							<td class=\"center\" >{$url->CurrencyNames}</td>    
							<td class=\"center\" >{$url->BlockedCurrencyNames}</td>    
							<td class=\"center {$status_class}\" >{$url->StatusName}</td>    
							<td class=\"center action\" >	
							";		 
								/*<a href=\"#UserModal\" title=\"edit url\" alt=\"edit url\" class=\"edit_url tip\" url-id=\"{$url->UrlID}\"  id=\"Edit{$url->UrlID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";*/
								
				$return .= "
							</td> 
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"8\" >Backup URL not found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function getSearchBackupUrlList()
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
		   
		$search_string = "";   
		$search_arr = array(); 
	 
		if(trim($data[s_url]))
		 {
			$search_string .= " AND (a.Url LIKE '%".$this->common->escapeString_(trim($data[s_url]))."%') "; 
			$search_url .= "&s_url=".trim($data[s_url]); 
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (FIND_IN_SET(".$this->common->escapeString_($data[s_currency]).", a.Currencies)) "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 }
		
		if(trim($data[s_blocked]))
		 {
			$search_string .= " AND (FIND_IN_SET(".$this->common->escapeString_($data[s_blocked]).", a.BlockedCurrencies)) "; 
			$search_url .= "&s_blocked=".trim($data[s_blocked]);   
		 } 
		
		if(!strict_backup_url())$search_string .= " AND (a.Status='2') ";    
		/*if(trim($data[s_status]) != '')
		 {
			//$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";   
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }*/  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_backup_url AS a", "a.UrlID")->TotalCount; 
	 	 
		$users = $this->urls->getBackupUrlList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"status":"status";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("status"=>$currencies, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("status"=>$this->generateHtmlBackupUrlListView($users), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }	 
   
	}  
	
	
	public function deleteUrl($actual=0)
	{
		 if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!strict_backup_url()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		   
		$data = $this->input->post(); 
		 
		$check_str = implode(',', $data[check_url]); 
		 
		if(count($data[check_url])<=0 || $check_str=="")
		 {
			 $error .= "Please select URL to delete!<br> ";
		 }
		
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 { 
			$x = $this->common->deleteRecords_("csa_backup_url", $check_str, "UrlID", array() );	    
			$return = ($x == true)?$return = array("success"=>1, "message"=>"URL deleted successfully."):$return = array("success"=>0, "message"=>"Error deleting activities. Please check!"); ;
		 }
		
		
		echo json_encode($return);
		 
		
	}
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */