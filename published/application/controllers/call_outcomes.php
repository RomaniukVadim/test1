<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Call_Outcomes extends MY_Controller {

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
		$this->callOutcomesList();    
	}
	 
	public function callOutcomesList()
	{    
		if(!admin_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"manage",    
					   "results"=>$this->manage->getCallResultById_(array("result_status"=>'1')), 	
					   "status_list"=>$this->status_list 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Call Outcomes", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/call_outcomes_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCallOutcomesList()
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
			$search_string .= " AND (a.outcome_id='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.outcome_name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		
		if(trim($data[s_result]))
		 {
			$search_string .= " AND (a.result_id='".$this->common->escapeString_($data[s_result])."') "; 
			$search_url .= "&s_result=".trim($data[s_result]);   
		 }
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.outcome_status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
		
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "call_outcome AS a", "a.outcome_id")->TotalCount; 
	 	 
		$outcomes = $this->manage->getCallOutcomesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"outcomes":"outcome";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("outcomes"=>$outcomes, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("outcomes"=>$this->generateHtmlCallOutcomesList($outcomes), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlCallOutcomesList($outcomes)
	{
		$return = ""; 
		if(count($outcomes))
		 { 
			foreach($outcomes as $row=>$outcome){   
				$status = ($outcome->outcome_status=='0' || $outcome->outcome_status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($outcome->date_updated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($outcome->date_updated)):'';
				$return .= "
						<tr class=\"outcome_row\" id=\"OutcomeRow{$outcome->outcome_id}\" > 
							<td class=\"center\" >".str_pad($outcome->outcome_id,4,'0', STR_PAD_LEFT)."</td>
							<td >{$outcome->outcome_name}</td>  
							<td >{$outcome->result_name}</td> 
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#CallOutcomeModal\" title=\"edit outcome\" alt=\"edit outcome\" class=\"edit_outcome tip\" outcome-id=\"{$outcome->outcome_id}\"  id=\"Edit{$outcome->outcome_id}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"6\" >No call outcome found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageCallOutcome()
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
		  
		$outcome_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.outcome_id =' => $outcome_id); 
		$outcome = ($outcome_id)?$this->manage->getCallOutcomeById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "outcome"=>$outcome, 
					   "results"=>$this->manage->getCallResultById_(array("result_status"=>'1')), 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Call Outcome", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/call_outcome_popup_tpl',$data);  
		 
	} 
	
	
	public function manageCallOutcome()
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
		
		if($data[outcome_name] == "")
		 {
			 $error .= "Enter outcome name!<br>";
		 }
		
		if($data[outcome_result] == "")
		 {
			 $error .= "Select result!<br>";
		 }
		 
		if($data[outcome_status] == "")
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
					'outcome_name'=>$data['outcome_name'], 
					'result_id'=>$data['outcome_result'],
					'date_added'=>$current_date,
					'date_updated'=>$current_date, 
					'outcome_status'=>$data['outcome_status'],    
				 );   
				 
				$last_id = $this->manage->manageSettings_("call_outcome", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Call outcome added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding call outcome!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'outcome_name'=>$data['outcome_name'], 
					'result_id'=>$data['outcome_result'],  
					'date_updated'=>$current_date, 
					'outcome_status'=>$data['outcome_status'], 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.outcome_id ='=>$data[hidden_aoutcomeid]);  
				 $old = $this->manage->getCallOutcomeById_($conditions_array);	
				 
				 $changes .= ($data['outcome_name'] != $old->outcome_name)?"Outcome Name changed to ".$data['outcome_name']." from ".$old->outcome_name."|||":""; 
				 $changes .= ($data['outcome_result'] != $old->result_id)?"Result changed to ".$data['hidden_aresult']." from ".$old->result_name."|||":"";  
				 $changes .= ($data['outcome_status'] != $old->outcome_status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("call_outcome", $post_data, $action, "outcome_id", $this->input->post("hidden_aoutcomeid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Call outcome updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating call outcome!");
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