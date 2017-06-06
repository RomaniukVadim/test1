<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_Methods extends MY_Controller {

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
		$this->load->model("banks_model","banks");  
		$this->load->model("common_model","common"); 
	}
 	
	public function index()
	{   
		$this->depositMethods();    
	}
	
	
	//DEPOSIT METHODS 
	public function depositMethods()
	{   
	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		//$activities = $this->getActivities($this->input->post());						
		$data2 = array("main_page"=>"banks",  
					   "currencies"=>$this->common->getCurrency_(),  
					   "status_list"=>array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")) 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Deposit Methods", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('banks/deposit_methods_tpl');
		$this->load->view('footer');   
		 
	} 
	
	public function getDepositMethodsList($actual=0)
	{
		$data = $this->input->post();
		$view_statuslist = array();  
		$result = $this->common->getUserStatusViews(1);//1-bank
		$view_statuslist = explode(',',$result->StatusList); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		
		$search_string = "";  
		$allow_close = 0;
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.MethodID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		 
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (a.CurrencyID='".$this->common->escapeString_(trim($data[s_currency]))."') "; 
			$search_url .= "&s_currency=".trim($data[s_currency]); 
		 }
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 } 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_bank_methods AS a", "a.MethodID")->TotalCount; 
	 	 
		$methods = $this->banks->getDepositMethodsList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 
		$pagination_options = array("link"=>"",//base_url()."banks/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"methods":"method";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 				   
		if($actual == 1)//
		 {  
		 	 $return = array("methods"=>$methods, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("methods"=>$this->generateHtmlDepositMethodList($methods), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlDepositMethodList($methods)
	{
		$return = ""; 
		if(count($methods))
		 { 
			foreach($methods as $row=>$method){  
				$status = ($method->Status=='0' || $method->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$return .= "
						<tr class=\"activity_row\" id=\"MethodRow{$method->MethodID}\" > 
							<td class=\"center\" >".str_pad($method->MethodID,4,'0', STR_PAD_LEFT)."</td>
							<td>{$method->Name}</td>
							<td class=\"center\" >{$method->CurrencyName}</td>
							<td class=\"center\" >{$method->DateUpdated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#DepositMethodModal\" title=\"edit deposit method\" alt=\"edit deposit method\" class=\"edit_method tip\" method-id=\"{$method->MethodID}\"  id=\"Edit{$method->MethodID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
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
	
	
	public function popupManageDepositMethod()
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
		
		$method_id = trim($this->uri->segment(3));
		$conditions_array = array('MethodID =' => $method_id); 
		$method = ($method_id)?$this->banks->getDepositMethodById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"banks",  
					   "currencies"=>$this->common->getCurrency_(),   
					   "status_list"=>array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")),   
					   "method"=>$method 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Deposit Method - ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('banks/deposit_method_popup_tpl',$data); 
		 
	} 
	
	
	public function manageDepositMethod()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		  
		$error = "";   
		$data = $this->input->post(); 
		 
		if($data[method_name] == "")
		 {
			 $error .= "Enter name!<br> ";
		 }
		 
		if($data[method_currency] == "")
		 {
			 $error .= "Select currency!<br> ";
		 }
		
		if($data[method_status] == "")
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
					'Name'=>$data['method_name'],  
					'CurrencyID'=>$data['method_currency'],  
					'Category'=>"deposit",  
					'Description'=>$data['method_desc'],
					'Status'=>$data['method_status'],
					'AddedBy'=>$this->session->userdata("mb_no"),
					'DateAdded'=>$current_date, 
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date
				 );   
				 
				$last_id = $this->banks->manageActivity_("csa_bank_methods", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Deposit method added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding deposit method!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['method_name'],  
					'CurrencyID'=>$data['method_currency'],  
					'Description'=>$data['method_desc'],
					'Status'=>$data['method_status'],
					'UpdatedBy'=>$this->session->userdata("mb_no"),
					'DateUpdated'=>$current_date
				 );
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.MethodID =' => $data[hidden_amethodid]);  
				 $old = $this->banks->getDepositMethodById_($conditions_array);	
				 
				 $changes .= ($data['method_name'] != $old->Name)?"Method Name changed to ".$data['method_name']." from ".$old->Name."|||":"";    
				 $changes .= ($data['method_currency'] != $old->CurrencyID)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->CurrencyName."|||":"";     
				 $changes .= ($data['method_desc'] != $old->Description)?"Description changed to ".$data['method_desc']." from ".$old->Description."|||":""; 
				 $changes .= ($data['method_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";    
				 
				 if($changes != "")
				  {
					$x = $this->banks->manageActivity_("csa_bank_methods", $post_data, $action, "MethodID", $this->input->post("hidden_amethodid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Bank method updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating bank method!");
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
	//END DEPOSIT METHODS
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */