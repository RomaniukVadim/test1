<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_Types extends MY_Controller {

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
		$this->userTypesList();   
	}
	 
	public function userTypesList()
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
					   
		$data = array("page_title"=>"12Bet - CAL - Activity Source", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/user_types_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getUserTypesList()
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
		
		if($this->common->ids['super_admin_id'] != $this->session->userdata("mb_usertype"))$search_string .= " AND a.GroupID<>{$this->common->ids[super_admin_id]} ";  
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.GroupID='".$this->common->escapeString_($data[s_id])."') "; 
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
		$total_rows = $this->common->countRecords_($search_string, "csa_users_group AS a", "a.GroupID")->TotalCount; 
	 	 
		$types = $this->manage->getUserTypesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
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
			$return = array("types"=>$this->generateHtmlUserTypesList($types), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlUserTypesList($types)
	{
		$return = ""; 
		if(count($types))
		 { 
			foreach($types as $row=>$type){   
				$status = ($type->Status=='0' || $type->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($type->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($type->DateUpdated)):'';
				$return .= "
						<tr class=\"type_row\" id=\"TypeRow{$result->GroupID}\" > 
							<td class=\"center\" >".str_pad($type->GroupID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$type->Name}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#UserTypeModal\" title=\"edit user type\" alt=\"edit user type\" class=\"edit_type tip\" type-id=\"{$type->GroupID}\"  id=\"Edit{$type->GroupID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No activity source found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageUserType()
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
		  
		$type_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.GroupID =' => $type_id); 
		$type = ($type_id)?$this->manage->getUserTypeById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list, 
					   "type"=>$type, 
					   "type_id"=>$type_id
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage User Type", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true), 
					  "types"=>$this->common->getUserTypes_(array("Status ="=>1, "GroupID <>"=>$this->common->ids['super_admin_id'])),
					 );
		$this->load->view('manage/user_type_popup_tpl',$data);  
		 
	} 
	
	
	public function manageUserType()
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
		$data['type_level'] = 1; 
		
		if($data[type_name] == "")
		 {
			 $error .= "Enter user type name!<br>";
		 }
		 
		if($data[type_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		
		$type_canassign = implode(',', $this->input->post("type_group"));   
		$type_canoverride = implode(',', $this->input->post("type_override"));   
		
		if($type_canassign == "")
		 {
			 $error .= "Select atleast one can assign!<br>";
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
					'Level'=>$data['type_level'],  
					'CanAssign'=>$type_canassign,  
					'CanOverride'=>$type_canoverride,  
					'DateAdded'=>$current_date,   
					'DateUpdated'=>$current_date,
					'Status'=>$data['type_status'] 
				 );   
				 
				 
				$last_id = $this->manage->manageSettings_("csa_users_group", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"User type added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding source!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['type_name'],  
					'CanAssign'=>$type_canassign,   
					'CanOverride'=>$type_canoverride,  
					'Description'=>$data['type_desc'], 
					'DateUpdated'=>$current_date,
					'Status'=>$data['type_status'] 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.GroupID ='=>$data[hidden_atypeid]);  
				 $old = $this->manage->getUserTypeById_($conditions_array);	
				   
				 $changes .= ($data['type_name'] != $old->Name)?"User Type Name changed to ".$data['type_name']." from ".$old->Name."|||":"";  
				 $changes .= ($type_canassign != $old->CanAssign)?"Can Assign changed to ".$type_canassign." from ".$old->CanAssign."|||":"";  
				 $changes .= ($type_canoverride != $old->CanOverride)?"Can Override changed to ".$type_canoverride." from ".$old->CanOverride."|||":""; 
				 $changes .= ($data['type_desc'] != $old->Description)?"Description changed to ".$data['type_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($data['type_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_users_group", $post_data, $action, "GroupID", $this->input->post("hidden_atypeid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"User type updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating user type!");
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