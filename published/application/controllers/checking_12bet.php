<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checking_12bet extends MY_Controller {

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
		$this->load->model("manage_model","manage");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active"));  
		 						  
	}
 	
	public function index()
	{    
		$this->checkingList();    
	}
	 
	public function checkingList()
	{    
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"manage",      	
					   "status_list"=>$this->status_list 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Checking", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true), 
					  "currencies"=>$this->manage->getCurrencyAll_(array("Status"=>1)),
					  "categories"=>$this->manage->getCheckingCategoriesById_(array("Status"=>1))
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/checking_12bet_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCheckingList()
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access()) 
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
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.UrlName LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		
		if(trim($data[s_category]))
		 {
			$search_string .= " AND (a.Category='".$this->common->escapeString_($data[s_category])."') "; 
			$search_url .= "&s_category=".trim($data[s_category]);   
		 }
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (a.Currency='".$this->common->escapeString_($data[s_currency])."') "; 
			$search_url .= "&s_category=".trim($data[s_currency]);   
		 }
		 
		if(trim($data[s_type]))
		 {
			$search_string .= " AND (a.Urltype='".$this->common->escapeString_($data[s_type])."') "; 
			$search_url .= "&s_type=".trim($data[s_type]);   
		 }
		   
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_12beturl AS a", "a.UrlID")->TotalCount; 
	 	 
		$checking = $this->manage->getCheckingList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"records":"record";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("checking"=>$checking, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("checking"=>$this->generateHtmlCheckingCategoriesList($checking), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlCheckingCategoriesList($checking)
	{
		$return = ""; 
		if(count($checking))
		 { 
			foreach($checking as $row=>$check){   
				$status = ($check->Status=='0' || $result->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($check->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($check->DateUpdated)):'';
				$return .= "
						<tr class=\"checking_row\" id=\"CheckingRow{$result->UrlID}\" > 
							<td class=\"center\" >".str_pad($check->UrlID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$check->UrlName}</td>
							<td class=\"center\" >{$check->CategoryName}</td>
							<td class=\"center\" >{$check->Abbreviation}<fasdfadstd>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#Checking12BetModal\" title=\"edit checking\" alt=\"edit checking\" class=\"edit_checking tip\" checking-id=\"{$check->UrlID}\"  id=\"Edit{$check->UrlID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No record found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageChecking12Bet()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$checking_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.UrlID =' => $checking_id); 
		$check = ($checking_id)?$this->manage->getCheckingById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "currencies"=>$this->manage->getCurrencyAll_(array("Status"=>1)),
					   "categories"=>$this->manage->getCheckingCategoriesById_(array("Status"=>1)),
					   "check"=>$check 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage 12Bet Checking ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/checking_12bet_popup_tpl',$data);  
		 
	} 
	
	
	public function manageChecking12bet()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		 
		   
		$error = "";   
		$data = $this->input->post();   
		
		if($data[checking_name] == "")
		 {
			 $error .= "Enter checking name!<br>";
		 }
		
		if($data[checking_category] == "")
		 {
			 $error .= "Select category!<br>";
		 }
		 
		if($data[checking_currency] == "")
		 {
			 $error .= "Select currency!<br>";
		 }
		   
		if($data[checking_status] == "")
		 {
			 $error .= "Select status!<br>";
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
					'UrlName'=>$data['checking_name'],  
					'Description'=>$data['checking_desc'],   
					'Category'=>$data['checking_category'],  
					'Currency'=>$data['checking_currency'],   
					'AddedBy'=>$this->session->userdata("mb_no"),  
					'DateAdded'=>$current_date,   
					'UpdatedBy'=>$this->session->userdata("mb_no"),  
					'DateUpdated'=>$current_date,
					'Status'=>$data['checking_status'] 
				 );   
				  
				$last_id = $this->manage->manageSettings_("csa_12beturl", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Checking added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding checking !");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'UrlName'=>$data['checking_name'],  
					'Description'=>$data['checking_desc'],   
					'Category'=>$data['checking_category'],  
					'Currency'=>$data['checking_currency'],     
					'UpdatedBy'=>$this->session->userdata("mb_no"),  
					'DateUpdated'=>$current_date,
					'Status'=>$data['checking_status']
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.UrlID ='=>$data[hidden_acheckingid]);  
				 $old = $this->manage->getCheckingById_($conditions_array);	
				  
				 
				 $changes .= ($data['checking_name'] != $old->UrlName)?"Url Name changed to ".$data['checking_name']." from ".$old->UrlName."|||":"";  
				 $changes .= ($data['checking_desc'] != $old->Description)?"Description changed to ".$data['checking_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($data['checking_category'] != $old->Category)?"Category changed to ".$data['hidden_acategory']." from ".$old->CategoryName."|||":"";  
				 $changes .= ($data['checking_currency'] != $old->Currency)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->Abbreviation."|||":"";  
				 $changes .= ($data['checking_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_12beturl", $post_data, $action, "UrlID", $this->input->post("hidden_acheckingid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Checking updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating checking!");
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
	 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */