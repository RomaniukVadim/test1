<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Status extends MY_Controller {

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
		$this->statusList();    
	}
	 
	public function statusList()
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
					   
		$data = array("page_title"=>"12Bet - CAL - Status", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/status_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getStatusList()
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
			$search_string .= " AND (a.StatusID='".$this->common->escapeString_($data[s_id])."') "; 
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
		$total_rows = $this->common->countRecords_($search_string, "csa_status AS a", "a.StatusID")->TotalCount; 
	 	 
		$status = $this->manage->getStatusList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
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
			$return = array("status"=>$this->generateHtmlCallResultsList($status), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlCallResultsList($status)
	{
		$return = ""; 
		if(count($status))
		 { 
			foreach($status as $row=>$stat){   
				$status = ($stat->Status=='0' || $stat->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($stat->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($stat->DateUpdated)):'';
				$return .= "
						<tr class=\"status_row\" id=\"StatusRow{$result->CurrencyID}\" > 
							<td class=\"center\" >".str_pad($stat->StatusID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$stat->Name}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#StatusModal\" title=\"edit status\" alt=\"edit status\" class=\"edit_status tip\" status-id=\"{$stat->StatusID}\"  id=\"Edit{$stat->StatusID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No status found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageStatus()
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
		  
		$status_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.StatusID =' => $status_id); 
		$status = ($status_id)?$this->manage->getStatusById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "status"=>$status, 
					   "user_types"=>$this->common->getUserTypes_(array("Status"=>1, "GroupID <>"=>$this->common->ids['super_admin_id']))  
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Status", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/status_popup_tpl',$data);  
		 
	} 
	
	
	public function manageStatus()
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
		
		if($data[status_name] == "")
		 {
			 $error .= "Enter status name!<br>";
		 }
		
		//if(!in_array("super_admin", $users_arr)) array_push($users_arr,"super_admin");
		//$user_currencies = implode(',', $this->input->post("user_cur"));   
		
		if(count($data[status_user])<=0)
		 {
			 $error .= "Select atleast one user!<br>"; 
			 $status_users = "";
		 }
		else
		 { 
			 if(!in_array($this->common->ids["super_admin_id"], $data[status_user])) array_push($data[status_user],$this->common->ids["super_admin_id"]);
			 $status_users = implode(',', $data[status_user]);  
		 }
		
		if(count($data[status_viewer])<=0)
		 {
			 $error .= "Select atleast one viewer!<br>"; 
			 $status_viewers = "";
		 }
		else
		 {
			 if(!in_array($this->common->ids["super_admin_id"], $data[status_viewer])) array_push($data[status_viewer],$this->common->ids["super_admin_id"]);
			 $status_viewers = implode(',', $data[status_viewer]);  
		 }
		 
		if($data[status_status] == "")
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
			$data['status_ishighlight'] = ($data['status_ishighlight'])?$data['status_ishighlight']:0;
			 
			if($action == "add")
			 {	  
			 	$post_data = array(   
					'Name'=>$data['status_name'],  
					'Users'=>$status_users, 
					'Viewers'=>$status_viewers, 
					'Description'=>$data['status_desc'], 
					'IsHighlight'=>$data['status_ishighlight'], 
					'Color'=>$data['status_color'],  
					'DateAdded'=>$current_date,   
					'DateUpdated'=>$current_date,
					'Status'=>$data['status_status'] 
				 );   
				 
				$last_id = $this->manage->manageSettings_("csa_status", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Status added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding status!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['status_name'],  
					'Users'=>$status_users, 
					'Viewers'=>$status_viewers, 
					'Description'=>$data['status_desc'], 
					'IsHighlight'=>$data['status_ishighlight'], 
					'Color'=>$data['status_color'],   
					'DateUpdated'=>$current_date,
					'Status'=>$data['status_status'] 
				 );  
				        
				 $changes = ""; 
				  
				 $conditions_array = array('a.StatusID ='=>$data[hidden_astatusid]);  
				 $old = $this->manage->getStatusById_($conditions_array);	
				 
				 $highlight_text = ($data['status_ishighlight'] == 1)?"Yes":"No"; 
				 
				 $changes .= ($data['status_name'] != $old->Name)?"Status Name changed to ".$data['status_name']." from ".$old->Name."|||":"";  
				 $changes .= ($data['status_color'] != $old->Color)?"Color changed to ".$data['status_color']." from ".$old->Color."|||":"";  
				 $changes .= ($data['status_desc'] != $old->Description)?"Description changed to ".$data['status_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($status_users != $old->Users)?"Users changed to ".$status_users." from ".$old->Users."|||":"";  
				 $changes .= ($status_viewers != $old->Viewers)?"Viewers changed to ".$status_viewers." from ".$old->Viewers."|||":"";      
				 $changes .= ($data['status_ishighlight'] != $old->IsHighlight)?"Highlight changed to ".$highlight_text." from ".$old->IsHighlighText."|||":"";  
				 $changes .= ($data['status_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_status", $post_data, $action, "StatusID", $this->input->post("hidden_astatusid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Status updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating status!");
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