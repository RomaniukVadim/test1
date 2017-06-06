<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Call_Results extends MY_Controller {

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
		$this->callResultList();    
	}
	 
	public function callResultList()
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
					   
		$data = array("page_title"=>"12Bet - CAL - Call Results", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/call_results_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCallResultsList()
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
			$search_string .= " AND (a.result_id='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.result_name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		 
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.result_status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "call_result AS a", "a.result_id")->TotalCount; 
	 	 
		$results = $this->manage->getCallResultsList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"results":"result";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("results"=>$results, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("results"=>$this->generateHtmlCallResultsList($results), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlCallResultsList($results)
	{
		$return = ""; 
		if(count($results))
		 { 
			foreach($results as $row=>$result){   
				$status = ($result->result_status=='0' || $result->result_status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($result->date_updated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($result->date_updated)):'';
				$return .= "
						<tr class=\"result_row\" id=\"OutcomeRow{$result->result_id}\" > 
							<td class=\"center\" >".str_pad($result->result_id,4,'0', STR_PAD_LEFT)."</td>
							<td >{$result->result_name}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#CallResultModal\" title=\"edit result\" alt=\"edit result\" class=\"edit_result tip\" result-id=\"{$result->result_id}\"  id=\"Edit{$result->result_id}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No call result found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageCallResult()
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
		  
		$result_id = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.result_id =' => $result_id); 
		$result = ($result_id)?$this->manage->getCallResultById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "result"=>$result 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Call Result", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/call_result_popup_tpl',$data);  
		 
	} 
	
	
	public function manageCallResult()
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
		
		if($data[result_name] == "")
		 {
			 $error .= "Enter result name!<br>";
		 }
		 
		if($data[result_status] == "")
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
					'result_name'=>$data['result_name'],  
					'date_added'=>$current_date,
					'date_updated'=>$current_date, 
					'result_status'=>$data['result_status'],    
				 );   
				 
				$last_id = $this->manage->manageSettings_("call_result", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Call result added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding call result!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'result_name'=>$data['result_name'],  
					'date_updated'=>$current_date, 
					'result_status'=>$data['result_status'], 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.result_id ='=>$data[hidden_aresultid]);  
				 $old = $this->manage->getCallResultById_($conditions_array);	
				 
				 $changes .= ($data['result_name'] != $old->result_name)?"Result Name changed to ".$data['result_name']." from ".$old->result_name."|||":"";  
				 $changes .= ($data['result_status'] != $old->result_status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("call_result", $post_data, $action, "result_id", $this->input->post("hidden_aresultid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Call result updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating call result!");
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