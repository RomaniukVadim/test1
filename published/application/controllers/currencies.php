<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Currencies extends MY_Controller {

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
		$this->currenciesList();    
	}
	 
	public function currenciesList()
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
					   
		$data = array("page_title"=>"12Bet - CAL - Currencies", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/currencies_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCurrenciesList()
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
		
		if(trim($data[s_abbreviation]))
		 {
			$search_string .= " AND (a.Abbreviation LIKE '%".$this->common->escapeString_(trim($data[s_abbreviation]))."%') "; 
			$search_url .= "&s_abbreviation=".trim($data[s_abbreviation]); 
		 }
		
		if(trim($data[s_internal]))
		 {
			$search_string .= " AND (a.InternalAbbreviation LIKE '%".$this->common->escapeString_(trim($data[s_internal]))."%') "; 
			$search_url .= "&s_internal=".trim($data[s_internal]); 
		 }
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 }  
		 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_currency AS a", "a.CurrencyID")->TotalCount; 
	 	 
		$currencies = $this->manage->getCurrenciesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"currencies":"currency";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("currencies"=>$currencies, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("currencies"=>$this->generateHtmlCallResultsList($currencies), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlCallResultsList($currencies)
	{
		$return = ""; 
		if(count($currencies))
		 { 
			foreach($currencies as $row=>$currency){   
				$status = ($currency->Status=='0' || $result->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($currency->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($currency->DateUpdated)):'';
				$return .= "
						<tr class=\"currency_row\" id=\"OutcomeRow{$result->CurrencyID}\" > 
							<td class=\"center\" >".str_pad($currency->CurrencyID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$currency->Abbreviation}</td>   
							<td >{$currency->InternalAbbreviation}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#CurrencyModal\" title=\"edit currency\" alt=\"edit currency\" class=\"edit_currency tip\" currency-id=\"{$currency->CurrencyID}\"  id=\"Edit{$currency->CurrencyID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"10\" >No currency found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageCurrency()
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
		  
		$CurrencyID = trim($this->uri->segment(3));
		 
		$conditions_array = array('a.CurrencyID =' => $CurrencyID); 
		$currency = ($CurrencyID)?$this->manage->getCurrencyById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,    
					   "currency"=>$currency 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Currency", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/currencies_popup_tpl',$data);  
		 
	} 
	
	
	public function manageCurrency()
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
		
		if($data[currency_abbreviation] == "")
		 {
			 $error .= "Enter currency abbreviation!<br>";
		 }
		
		if($data[currency_internal] == "")
		 {
			 $error .= "Enter currency `!<br>";
		 } 
		 
		if($data[currency_status] == "")
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
					'Abbreviation'=>$data['currency_abbreviation'],  
					'InternalAbbreviation'=>$data['currency_internal'],  
					'Description'=>$data['currency_desc'], 
					'IsChecking'=>$data['currency_ischecking'], 
					'DateAdded'=>$current_date,   
					'DateUpdated'=>$current_date,
					'Status'=>$data['currency_status'] 
				 );   
				 
				 
				$last_id = $this->manage->manageSettings_("csa_currency", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Currency added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding currency!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Abbreviation'=>$data['currency_abbreviation'],  
					'InternalAbbreviation'=>$data['currency_internal'],  
					'Description'=>$data['currency_desc'],  
					'IsChecking'=>$data['currency_ischecking'],  
					'DateUpdated'=>$current_date,
					'Status'=>$data['currency_status'] 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.CurrencyID ='=>$data[hidden_acurrencyid]);  
				 $old = $this->manage->getCurrencyById_($conditions_array);	
				 
				 $ischecking_txt = ($data[currency_ischecking] == 1)?"Yes":"No";
				 
				 $changes .= ($data['currency_abbreviation'] != $old->Abbreviation)?"Abbreviation changed to ".$data['currency_abbreviation']." from ".$old->Abbreviation."|||":"";  
				 $changes .= ($data['currency_internal'] != $old->InternalAbbreviation)?"Internal System Abbreviation changed to ".$data['currency_internal']." from ".$old->InternalAbbreviation."|||":"";  
				 $changes .= ($data['currency_desc'] != $old->Description)?"Description changed to ".$data['currency_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($data['currency_ischecking'] != $old->IsChecking)?"Is Checking changed to ".$ischecking_txt." from ".$old->IsCheckingName."|||":"";  
				 $changes .= ($data['currency_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_currency", $post_data, $action, "CurrencyID", $this->input->post("hidden_acurrencyid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Currency updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating currency!");
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