<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pages extends MY_Controller {

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
		$this->pageList();    
	}
	 
	public function pageList()
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
					   
		$data = array("page_title"=>"12Bet - CAL - Pages", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/pages_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getPagesList()
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
			$search_string .= " AND (a.PageID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		 
		if(trim($data[s_controller]))
		 {
			$search_string .= " AND (a.FileUsed LIKE '%".$this->common->escapeString_(trim($data[s_controller]))."%') "; 
			$search_url .= "&s_controller=".trim($data[s_controller]); 
		 } 
		 
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_pages AS a", "a.PageID")->TotalCount; 
	 	 
		$pages = $this->manage->getPagesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"sources":"source";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("pages"=>$pages, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("pages"=>$this->generateHtmlPagesList($pages), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlPagesList($pages)
	{
		$return = ""; 
		if(count($pages))
		 { 
			foreach($pages as $row=>$page){   
				$status = ($page->Status=='0' || $page->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($page->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($page->DateUpdated)):'';
				$return .= "
						<tr class=\"page_row\" id=\"PageRow{$result->PageID}\" > 
							<td class=\"center\" >".str_pad($page->PageID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$page->Name}</td>   
							<td class=\"center\" >{$page->FileUsed}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#PageModal\" title=\"edit page\" alt=\"edit page\" class=\"edit_page tip\" page-id=\"{$page->PageID}\"  id=\"Edit{$page->PageID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"6\" >No page found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManagePage()
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
		  
		$page_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.PageID =' => $page_id); 
		$page = ($page_id)?$this->manage->getPagesById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,  
					   "stats"=>$this->manage->getStatusById_(array("a.Status ="=>1)),//get all as long as it is not deleted:9
					   "page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Page", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/pages_popup_tpl',$data);  
		 
	} 
	
	
	public function managePage()
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
		
		if($data[page_name] == "")
		 {
			 $error .= "Enter source name!<br>";
		 }
		
		if($data[page_controller] == "")
		 {
			 $error .= "Enter controller!<br>";
		 } 
		
		if(count($data[page_stat])<=0)
		 {
			 $error .= "Select atleast one status!<br>"; 
			 $status_users = "";
		 }
		else
		 { 
			 $page_statuslist = implode(',', $data[page_stat]);  
		 }
		    
		if($data[page_status] == "")
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
			 	//, , , , 
			 	$post_data = array(   
					'Name'=>$data['page_name'],  
					'FileUsed'=>$data['page_controller'],  
					'Description'=>$data['page_desc'],  
					'StatusList'=>$page_statuslist,  
					'DateAdded'=>$current_date,   
					'DateUpdated'=>$current_date,
					'Status'=>$data['page_status'] 
				 );   
				 
				 
				$last_id = $this->manage->manageSettings_("csa_pages", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Page added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding page!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['page_name'],  
					'FileUsed'=>$data['page_controller'],  
					'Description'=>$data['page_desc'],  
					'StatusList'=>$page_statuslist,   
					'DateUpdated'=>$current_date,
					'Status'=>$data['page_status'] 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.PageID ='=>$data[hidden_apageid]);  
				 $old = $this->manage->getPagesById_($conditions_array);	
				  
				 
				 $changes .= ($data['page_name'] != $old->Name)?"Page Name changed to ".$data['page_name']." from ".$old->Name."|||":"";  
				 $changes .= ($data['page_controller'] != $old->FileUsed)?"File Used changed to ".$data['page_controller']." from ".$old->FileUsed."|||":"";  
				 $changes .= ($data['page_desc'] != $old->Description)?"Description changed to ".$data['page_desc']." from ".$old->Description."|||":"";   
				 $changes .= ($page_statuslist != $old->StatusList)?"Status List changed to ".$page_statuslist." from ".$old->StatusList."|||":"";  
				 $changes .= ($data['page_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_pages", $post_data, $action, "PageID", $this->input->post("hidden_apageid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Page updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating page!");
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