<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Related_Problems extends MY_Controller {

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
		$this->load->model("accounts_model","accounts");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")); 
	}
 	
	public function index()
	{   
		$this->relatedProblems();    
	}
	 
	public function relatedProblems($dcategory="deposit")
	{   
	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"accounts",  
					   "dcategory"=>$dcategory,  
					   "status_list"=>$this->status_list
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Related Problems", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('accounts/related_problems_tpl');
		$this->load->view('footer');   
		 
	}  
	
	public function getProblemsList()
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
			$search_string .= " AND (a.ProblemID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.ProblemName LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		  
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 } 
		 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_account_problems AS a", "a.ProblemID")->TotalCount; 
	 	 
		$problems = $this->accounts->getProblemsList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 
		$pagination_options = array("link"=>"",//base_url()."banks/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"problems":"problem";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
						   
		if($actual == 1)//
		 {  
		 	 $return = array("problems"=>$problems, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("problems"=>$this->generateHtmlProblemsList($problems), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlProblemsList($problems)
	{
		$return = ""; 
		if(count($problems))
		 { 
			foreach($problems as $row=>$problem){  
				$status = ($problem->Status=='0' || $problem->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$return .= "
						<tr class=\"activity_row\" id=\"ProblemRow{$problem->ProblemID}\" > 
							<td class=\"center\" >".str_pad($problem->ProblemID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$problem->ProblemName}</td> 
							<td class=\"center\" >{$problem->DateUpdated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#ProblemModal\" title=\"edit problem\" alt=\"edit problem\" class=\"edit_problem tip\" problem-id=\"{$problem->ProblemID}\"  id=\"Edit{$problem->ProblemID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
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
	
	
	public function popupManageProblem()
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
		  
		$problem_id = trim($this->uri->segment(3));
		 
		
		$conditions_array = array('a.ProblemID =' => $problem_id); 
		$problem = ($problem_id)?$this->accounts->getProblemById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"accounts",     
					   "status_list"=>$this->status_list,
					   "problem"=>$problem
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Account Related Problem", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('accounts/related_problem_popup_tpl',$data);  
		 
	} 
	
	
	public function manageRelatedProblem()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		  
		$error = "";   
		$data = $this->input->post(); 
		 
		if($data[problem_name] == "")
		 {
			 $error .= "Enter name!<br> ";
		 } 
		
		if($data[problem_status] == "")
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
					'ProblemName'=>$data['problem_name'],      
					'Description'=>$data['problem_desc'],
					'Status'=>$data['problem_status'], 
					'DateAdded'=>$current_date,  
					'DateUpdated'=>$current_date
				 );   
				 
				$last_id = $this->accounts->manageActivity_("csa_account_problems", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Related problem added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding related problem!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'ProblemName'=>$data['problem_name'],      
					'Description'=>$data['problem_desc'],
					'Status'=>$data['problem_status'],  
					'DateUpdated'=>$current_date
				 );
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.ProblemID ='=>$data[hidden_aproblemid]);  
				 $old = $this->accounts->getProblemById_($conditions_array);	
				 
				 $changes .= ($data['problem_name'] != $old->ProblemName)?"Problem Name changed to ".$data['problem_name']." from ".$old->ProblemName."|||":"";    
				 $changes .= ($data['problem_desc'] != $old->Description)?"Description changed to ".$data['problem_desc']." from ".$old->Description."|||":""; 
				 $changes .= ($data['problem_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";    
				 
				 if($changes != "")
				  {
					$x = $this->accounts->manageActivity_("csa_account_problems", $post_data, $action, "ProblemID", $this->input->post("hidden_aproblemid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Related problem updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating related problem category!");
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