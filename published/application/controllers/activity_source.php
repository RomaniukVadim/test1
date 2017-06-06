<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Activity_Source extends MY_Controller {

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
		$this->activitySourceList();    
	}
	 
	public function activitySourceList()
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
		$this->load->view('manage/activity_source_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getActivitySourceList()
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
			$search_string .= " AND (a.CurrencyID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Source LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		 
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_source AS a", "a.SourceID")->TotalCount; 
	 	 
		$sources = $this->manage->getActivitySourceList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
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
		 	 $return = array("sources"=>$sources, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("sources"=>$this->generateHtmlSourceList($sources), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlSourceList($sources)
	{
		$return = ""; 
		if(count($sources))
		 { 
			foreach($sources as $row=>$source){   
				$status = ($source->Status=='0' || $source->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($source->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($source->DateUpdated)):'';
				$return .= "
						<tr class=\"source_row\" id=\"SourceRow{$result->SourceID}\" > 
							<td class=\"center\" >".str_pad($source->SourceID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$source->Source}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#ActivitySourceModal\" title=\"edit source\" alt=\"edit source\" class=\"edit_source tip\" source-id=\"{$source->SourceID}\"  id=\"Edit{$source->SourceID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
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
	
	
	public function popupManageActivitySource()
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
		  
		$source_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.SourceID =' => $source_id); 
		$source = ($source_id)?$this->manage->getActivitySourceById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list, 
					   "source"=>$source
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Activity Source", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/activity_source_popup_tpl',$data);  
		 
	} 
	
	
	public function manageActivitySource()
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
		
		if($data[source_name] == "")
		 {
			 $error .= "Enter source name!<br>";
		 }
		 
		if($data[source_status] == "")
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
					'Source'=>$data['source_name'],  
					'Description'=>$data['source_desc'],  
					'DateAdded'=>$current_date,   
					'DateUpdated'=>$current_date,
					'Status'=>$data['source_status'] 
				 );   
				 
				 
				$last_id = $this->manage->manageSettings_("csa_source", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Source added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding source!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Source'=>$data['source_name'],  
					'Description'=>$data['source_desc'],   
					'DateUpdated'=>$current_date,
					'Status'=>$data['source_status'] 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.SourceID ='=>$data[hidden_asourceid]);  
				 $old = $this->manage->getActivitySourceById_($conditions_array);	
				  
				 
				 $changes .= ($data['source_name'] != $old->Source)?"Source Name changed to ".$data['source_name']." from ".$old->Source."|||":"";  
				 $changes .= ($data['source_desc'] != $old->Description)?"Description changed to ".$data['source_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($data['source_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_source", $post_data, $action, "SourceID", $this->input->post("hidden_asourceid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Source updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating source!");
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