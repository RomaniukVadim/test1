<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promotional_Issues extends MY_Controller {

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
		$this->load->model("promotions_model","promotions");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active"));  
		 						  
	}
 	
	public function index()
	{   
		$this->promotionsCategoriesList();    
	}
	 
	public function promotionsCategoriesList()
	{    
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"promotions",    
					   "status_list"=>$this->status_list, 
					   //"currencies"=>$this->common->getCurrency_(),
					   //"products"=>$this->common->getProductsList(array("Status"=>'1'))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Promotional Issues", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('promotions/promotional_issues_tpl');
		$this->load->view('footer');   
		 
	}  
	
	public function getPromotionsIssuesList()
	{
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		  
		$data = $this->input->post(); 
		   
		$search_string = "";   
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.IssueID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		 
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 } 
		 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_promotion_issues AS a", "a.IssueID")->TotalCount; 
	 	 
		$categories = $this->promotions->getPromotionsIssuesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"categories":"category";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("categories"=>$categories, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("categories"=>$this->generateHtmlPromotionsIssuesList($categories), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlPromotionsIssuesList($issues)
	{
		$return = ""; 
		if(count($issues))
		 { 
			foreach($issues as $row=>$issue){   
				$status = ($issue->Status=='0' || $issue->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				
				$return .= "
						<tr class=\"activity_row\" id=\"CategoryRow{$issue->IssueID}\" > 
							<td class=\"center\" >".str_pad($issue->IssueID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$issue->Name}</td>  
							<td class=\"center\" >".date("Y-m-d H:i:s", strtotime($issue->DateUpdated))."</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#PromotionCategoryModal\" title=\"edit category\" alt=\"edit category\" class=\"edit_issue tip\" issue-id=\"{$issue->IssueID}\"  id=\"Edit{$promotion->IssueID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No promotional issue found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManagePromotionIssue()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$issue_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.IssueID =' => $issue_id); 
		$issue = ($issue_id)?$this->promotions->getPromotionIssuesById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"promotions",     
					   "status_list"=>$this->status_list,  
					   //"groups"=>$this->common->getUsersGroup_(array("Status"=>'1')),
					   "issue"=>$issue
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Promotion Issue", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('promotions/promotional_issues_popup_tpl',$data);  
		 
	} 
	
	
	public function managePromotionIssue()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		   
		$error = "";   
		$data = $this->input->post();   
		
		if($data[issue_name] == "")
		 {
			 $error .= "Enter issue name!<br>";
		 }
		
		if($data[issue_status] == "")
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
					'Name'=>$data['issue_name'],   
					'Description'=>$data['issue_description'],  
					'AddedBy'=>$this->session->userdata("mb_no"),
					'DateAdded'=>$current_date,
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date,
					'Status'=>$data['issue_status']
				 );   
				 
				$last_id = $this->promotions->manageActivity_("csa_promotion_issues", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Promotional issue added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding promotional issue!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['issue_name'],  
					'Description'=>$data['issue_description'],   
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date,
					'Status'=>$data['issue_status']
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.IssueID ='=>$data[hidden_aissueid]);  
				 $old = $this->promotions->getPromotionIssuesById_($conditions_array);	
				 
				 $changes .= ($data['issue_name'] != $old->Name)?"Issue Name changed to ".$data['issue_name']." from ".$old->Name."|||":""; 
				 $changes .= ($data['issue_description'] != $old->Description)?"Description changed to ".$data['issue_description']." from ".$old->Description."|||":"";
				 $changes .= ($data['issue_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->promotions->manageActivity_("csa_promotion_issues", $post_data, $action, "IssueID", $this->input->post("hidden_aissueid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Promotional issue updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating promotional issue!");
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