<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suggestion_Types extends MY_Controller {

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
		$this->load->model("suggestions_model","suggestions");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")); 
	}
 	
	public function index()
	{   
		$this->suggestionTypes();    
	}
	 
	public function suggestionTypes($dcategory="deposit")
	{   
	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"suggestions",  
					   "dcategory"=>$dcategory,  
					   "status_list"=>$this->status_list
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Suggestion Types", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('suggestions/suggestion_types_tpl');
		$this->load->view('footer');   
		 
	}  
	
	public function getTypesList()
	{
		if(!admin_access()) 
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
			$search_string .= " AND (a.ComplaintID='".$this->common->escapeString_($data[s_id])."') "; 
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
		$total_rows = $this->common->countRecords_($search_string, "csa_complaints_types AS a", "a.ComplaintID")->TotalCount; 
	 	 
		$types = $this->suggestions->getTypesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 
		$pagination_options = array("link"=>"",//base_url()."banks/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"types":"type";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
						   
		if($actual == 1)//
		 {  
		 	 $return = array("types"=>$types, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("types"=>$this->generateHtmlTypesList($types), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlTypesList($types)
	{
		$return = ""; 
		if(count($types))
		 { 
			foreach($types as $row=>$type){  
				$status = ($type->Status=='0' || $type->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$return .= "
						<tr class=\"activity_row\" id=\"TypeRow{$type->ComplaintID}\" > 
							<td class=\"center\" >".str_pad($type->ComplaintID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$type->Name}</td> 
							<td class=\"center\" >{$type->DateUpdated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#TypeModal\" title=\"edit type\" alt=\"edit type\" class=\"edit_type tip\" type-id=\"{$type->ComplaintID}\"  id=\"EditType{$type->ComplaintID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"6\" >No category found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageType()
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
		  
		$type_id = trim($this->uri->segment(3));
		 
		
		$conditions_array = array('a.ComplaintID =' => $type_id); 
		$type = ($type_id)?$this->suggestions->getTypeById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"suggestions",     
					   "status_list"=>$this->status_list,
					   "type"=>$type
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Suggestion Type", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('suggestions/suggestion_type_popup_tpl',$data);  
		 
	} 
	
	
	public function manageSuggetionType()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		  
		$error = "";   
		$data = $this->input->post(); 
		 
		if($data[type_name] == "")
		 {
			 $error .= "Enter name!<br> ";
		 } 
		
		if($data[type_status] == "")
		 {
			 $error .= "Select status!<br> ";
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
					'Name'=>$data['type_name'],      
					'Description'=>$data['type_desc'],
					'Status'=>$data['type_status'], 
					'DateAdded'=>$current_date,  
					'DateUpdated'=>$current_date
				 );   
				 
				$last_id = $this->suggestions->manageActivity_("csa_complaints_types", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Complaint type added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding complaint type!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['type_name'],      
					'Description'=>$data['type_desc'],
					'Status'=>$data['type_status'],  
					'DateUpdated'=>$current_date
				 );
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.ComplaintID ='=>$data[hidden_atypeid]);  
				 $old = $this->suggestions->getTypeById_($conditions_array);	
				 
				 $changes .= ($data['type_name'] != $old->Name)?"Type Name changed to ".$data['type_name']." from ".$old->Name."|||":"";    
				 $changes .= ($data['type_desc'] != $old->Description)?"Description changed to ".$data['type_desc']." from ".$old->Description."|||":""; 
				 $changes .= ($data['type_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";    
				 
				 if($changes != "")
				  {
					$x = $this->suggestions->manageActivity_("csa_complaints_types", $post_data, $action, "ComplaintID", $this->input->post("hidden_atypeid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Complaint type updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating complaint type!");
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
	
	
	//WITHDRAWAL CATEGORIES 
	public function withdrawalCategories()
	{   
		$this->depositCategories("withdrawal");
	} 
	//END WITHDRAWAL CATEGORIES
	
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */