<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crm_Conversions extends MY_Controller {

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
		$this->load->model("reports_model","reports");  
		$this->load->model("common_model","common"); 
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active"));   
		 
		$this->report_status = array("new_status"=>array("StatusID"=>0, "Label"=>"New", "CountName"=>"NewCount"), 
									 "pending_status"=>array("StatusID"=>62, "Label"=>"Pending", "CountName"=>"PendingCount"), 
									 "inprogress_status"=>array("StatusID"=>61, "Label"=>"In Progress", "CountName"=>"InProgressCount"), 
									 "close_status"=>array("StatusID"=>5, "Label"=>"Closed", "CountName"=>"CloseCount"), 
									 "deposited_status"=>array("StatusID"=>11, "Label"=>"Deposited", "CountName"=>"DepositedCount"), 
									 "nondeposited_status"=>array("StatusID"=>9, "Label"=>"Non Deposited", "CountName"=>"NonDepositedCount") 
									); 
									
		/*$this->report_customs = array("complaint_cus"=>array("IsComplaint"=>1, "Label"=>"Complaint", "CountName"=>"ComplaintCount"),
									 "complain_users_cus"=>array("Username"=>62, "Label"=>"Complaint Username", "CountName"=>"ComplaintUsername") 
									); */
									 
									
		//$this->report_sources = $this->common->getSourceAll_(array("Status"=>1)); 
		
		$this->import_types = array('csv');
		$this->deposited_claimed = array(1,2); //1-deposited, 2-claimed. Once claimed it is already considered as deposited
	 
	}
 	
	public function index()
	{     
		$this->crmConversions();    
	} 
	 
	public function crmConversions()
	{   
		
		if(!admin_access() && !can_upload_crm_record() && !can_view_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		$categories_where = array("a.Status ="=>'1', 
								  "a.ForConversions"=>'1'
								 ); 
   		if(restriction_type()) 
		 { 
			//$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
			$categories_where["FIND_IN_SET({$this->session->userdata('mb_usertype')}, a.Viewers) !="] = 0;
		 }
		  					
		$data2 = array("main_page"=>"reports",      	 
					   "agents"=>$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "a.mb_usertype"=>$this->common->ids['crm_id'])),  
					   //"outcomes"=>$this->reports->getCallOutcomeList_(array("a.outcome_status ="=>1, "b.result_status ="=>1)),  
					   "sub_products"=>$this->common->getSubProductsList_(array("a.Status"=>'1')),
					   "currencies"=>$this->common->getCurrency_(), 
					   "promotions"=>$this->reports->getChangePromotion_(array(), array(), 0), 
					   "categories"=>$this->common->getPromotionCategoriesAll_($categories_where),
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - Reports - CRM Conversions ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('reports/crm_conversions_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCrmConversions($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_upload_crm_record() && !can_view_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = ""; 
		$search_string2 = "";
		$search_string3 = "";   
		$search_string4 = "";    
		$search_arr = array(); 
		$data_link =  array();   
		
		if(trim($data[s_agent]))
		 {
			//$agent = $this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name ="=>"csa", "a.mb_no ="=>$data[s_agent])); 
			//$crm_agent = $agent[0]->mb_nick; 
			 
			$search_string3 .= " AND (b.AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_string4 .= " AND (a.OfferedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);      
		 }  
		else
		 {
			//$crm_agent = "All"; 	   
			if(!admin_access())
			 {
				 if(!csd_supervisor_access()) 
				 {
					$search_string3 .= " AND (b.AddedBy={$this->session->userdata(mb_no)}) "; 
					$search_string4 .= " AND (a.OfferedBy={$this->session->userdata(mb_no)}) "; 
				 }	 
			 }
			 
		 }
		 
		if(trim($data[s_currency]))
		 { 
			$search_string .= " AND (a.Currency=".$this->common->escapeString_(trim($data[s_currency])).") ";    
		 }
		else{
			if(!admin_access()) 
			 {
				$search_string .= " AND (a.Currency IN({$this->session->userdata(mb_currencies)}) ) "; 
			 }
		}
		
		/*if(trim($data[s_promotion]))
		 { 
			//$promotion = $this->reports->getPromotionById_(array("a.PromotionID ="=>$data[s_promotion])); 
			//$promotion_name = $promotion->Name; 
			  
			$search_string2 .= " AND (Promotion=".$this->common->escapeString_(trim($data[s_promotion])).") "; 
			$search_url .= "&s_promotion=".trim($data[s_promotion]);    
			$search_arr["a.Promotion ="] = trim($data[s_promotion]); 
			$data_link['s_promotion'] = trim($data[s_promotion]);
		 }
		else
		 {
			$promotion_name = "All"; 
		 }*/
		 
		 if(trim($data[s_category]))
		 {
			//$category = $this->common->getPromotionCategoriesAll_(array("a.CategoryID ="=>$data[s_category])); 
			//$category_name = $category[0]->Name;
			  
			$search_string .= " AND (a.Category=".$this->common->escapeString_(trim($data[s_category])).") "; 
			$search_url .= "&s_category=".trim($data[s_category]);     
		 }
		else
		 {
			$search_string .= " AND (a.Category > 0) "; 
		 }
		   
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			//if(trim($data[s_agent]))$search_string3 .= " AND (b.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			//if(trim($data[s_agent]))$search_string3 .= " AND (b.DateAddedInt >= {$s_fromdate} )  "; //removed because there are some record for Nov. but already called on Oct.
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));     
		 } 
		
		if(trim($data[s_product]))
		 {    
			$search_string2 .= " AND (c.SubID=".$this->common->escapeString_(trim($data[s_product])).") "; 
			$search_url .= "&s_product=".trim($data[s_product]);     
		 }
		else
		 {
			$search_string2 .= " AND (b.SubProductID > 0) "; 
		 }
		  
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		 
		$results = $this->reports->getCrmConversions_($search_string, $search_string2, $search_string3, $search_string4); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
	  
				 
		if($actual == 1)//
		 {  
		 	 $return = array("results"=>$results,
						//"pagination"=>create_pagination($pagination_options), 
						//"pagination_string"=>$pagination_string, 
						//"records"=>$total_rows 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
			//list($outcomes, $total_arr) = $this->generateHtmlOutcomeData($outcome_data, $results, $options); 
		   
			$return = array("results"=>$results, 
						    //"pagination"=>create_pagination($pagination_options), 
							//"pagination_string"=>$pagination_string, 
							//"records"=>count($outcome_data), 
							//"total_arr"=>$total_arr
					   ); 
					 
			 echo json_encode($return); 
		 }
		
	} 
	
	public function getCrmConversionsDetails($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_upload_crm_record() && !can_view_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = ""; 
		$search_string2 = "";
		$search_string3 = "";   
		$search_string4 = "";    
		$search_arr = array(); 
		$data_link =  array();   
		 
		if(trim($data[s_agent]))
		 {
			//$agent = $this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name ="=>"csa", "a.mb_no ="=>$data[s_agent])); 
			//$crm_agent = $agent[0]->mb_nick; 
			 
			$search_string3 .= " AND (b.AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_string4 .= " AND (a.OfferedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);      
		 }  
		else
		 {
			//$crm_agent = "All"; 	   
			if(!admin_access())
			 {
				 if(!csd_supervisor_access()) 
				 {
					$search_string3 .= " AND (b.AddedBy={$this->session->userdata(mb_no)}) "; 
					$search_string4 .= " AND (a.OfferedBy={$this->session->userdata(mb_no)}) "; 
				 }	 
			 }
			 
		 } 
		  
		if(trim($data[s_currency]))
		 { 
			$search_string .= " AND (a.Currency=".$this->common->escapeString_(trim($data[s_currency])).") ";    
		 }
		else{
			if(!admin_access()) 
			 {
				$search_string .= " AND (a.Currency IN({$this->session->userdata(mb_currencies)}) ) "; 
			 }
		}
		 
		/*if(trim($data[s_promotion]))
		 { 
			//$promotion = $this->reports->getPromotionById_(array("a.PromotionID ="=>$data[s_promotion])); 
			//$promotion_name = $promotion->Name; 
			  
			$search_string2 .= " AND (Promotion=".$this->common->escapeString_(trim($data[s_promotion])).") "; 
			$search_url .= "&s_promotion=".trim($data[s_promotion]);    
			$search_arr["a.Promotion ="] = trim($data[s_promotion]); 
			$data_link['s_promotion'] = trim($data[s_promotion]);
		 }
		else
		 {
			$promotion_name = "All"; 
		 }*/
		 
		 /*if(trim($data[s_category]))
		 {    
			$search_string .= " AND (a.Category=".$this->common->escapeString_(trim($data[s_category])).") "; 
			$search_url .= "&s_category=".trim($data[s_category]);     
		 }
		else
		 {
			$search_string .= " AND (a.Category > 0) "; 
		 }*/
		 
		 if(trim($data[click_categoryid]))
		 {    
			$search_string .= " AND (a.Category=".$this->common->escapeString_(trim($data[click_categoryid])).") "; 
			$search_url .= "&click_categoryid=".trim($data[click_categoryid]);     
		 }
		else
		 {
			$search_string .= " AND (a.Category > 0) "; 
		 }
		   
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			//if(trim($data[s_agent]))$search_string3 .= " AND (b.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));     
		 } 
		
		/*if(trim($data[s_product]))
		 {    
			$search_string2 .= " AND (c.SubID=".$this->common->escapeString_(trim($data[s_product])).") "; 
			$search_url .= "&s_product=".trim($data[s_product]);     
		 }
		else
		 {
			$search_string2 .= " AND (b.SubProductID > 0) "; 
		 }*/
		
		if(trim($data[click_subproductid]))
		 {    
			$search_string2 .= " AND (c.SubID=".$this->common->escapeString_(trim($data[click_subproductid])).") "; 
			$search_url .= "&click_subproductid=".trim($data[click_subproductid]);     
		 }
		else
		 {
			$search_string2 .= " AND (b.SubProductID > 0) "; 
		 }
		   
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		 
		$results = $this->reports->getCrmConversionsDetails_($search_string, $search_string2, $search_string3, $search_string4); 
	   
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
	  
				 
		if($actual == 1)//
		 {  
		 	 $return = array("results"=>$results,
						//"pagination"=>create_pagination($pagination_options), 
						//"pagination_string"=>$pagination_string, 
						//"records"=>$total_rows 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
			//list($outcomes, $total_arr) = $this->generateHtmlOutcomeData($outcome_data, $results, $options); 
		   
			$return = array("results"=>$results, 
						    //"pagination"=>create_pagination($pagination_options), 
							//"pagination_string"=>$pagination_string, 
							//"records"=>count($outcome_data), 
							//"total_arr"=>$total_arr
					   ); 
					 
			 echo json_encode($return); 
		 }
		
	}
	 
	public function generateHtmlOutcomeData($outcome_data, $results, $options=array())
	{
		$return = "";    
		
		if(count($results))
		 { 
		 	$ctr = 0;   
			$all_ave = 0; 
			$all_total = 0; 	    
			foreach($results as $row=>$result){    
				$count_x = 0;     
		 		$total_ave = 0; 
				$total_count = 0;   
				
				$base_total = $result[BaseCount]; 
				$base_total_link = $result[BaseCountLink]; 
				$result_total = $result[CallCount];
				$result_total_link = $result[CallCountLink];
				
				foreach($result[CallData] as $row2=>$data) {  
					$average = round(($data->CallCount / $base_total) * 100, 2);  
					$total_ave += $average;
					$total_count += $data->CallCount; 
					
					$all_ave += $average; 
					$all_total += $data->CallCount; 
				
					$row_span = (count($result[CallData]) > 1)?'rowspan="'.count($result[CallData]).'"':''; 
				 
					$return .= "
							<tr class=\"outcome_row result_{$data->result_id}\" id=\"Outcome{$data->outcome_id}\" >  
							";
					$return .= ($count_x == 0)?"<td class=\"center result_name\"  {$row_span} style=\"border-left: 0 !important;\" >{$data->result_name}</td>":""; 
					
					$return .= "
								<td class=\"center\" >{$data->outcome_name}</td>  
								<td class=\"center\" >{$data->CallCount}</td>  
								<td class=\"center\" >{$average}</td>  
							";
					 
					$return .= " 
							</tr> ";  
					
					$count_x++; 		 
						
				}//end foreach
				
				$return .= " 
						<tr class=\"outcome_row result_{$data->result_id} total_info summary_{$data->result_id} \">   
							<th class=\"center\" colspan=\"2\" >{$result->CallCount}</th>  
							<th class=\"center\" >{$result[CallCount]}</th>  
							<th class=\"center\" >".round($total_ave, 1)."%</th>  
					"; 
				$return .= " 
						</tr>";
				   
				$ctr++; 
			}//end foreach 
			 
			 
			$return2 .= " 
						<tr>   
							<th class=\"center\" >CRM Agent</th> 
							<td class=\"center\" >{$options[crm_agent]}</td>
						</tr>
						<tr>   
							<th class=\"center\" >Currency</th> 
							<td class=\"center\" >{$options[currency_name]}</td>
						</tr>
						<tr>   
							<th class=\"center\" >Category</th> 
							<td class=\"center\" >{$options[category_name]}</td>
						</tr>
						<tr>   
							<th class=\"center\" >Promotion</th> 
							<td class=\"center\" >{$options[promotion_name]}</td>
						</tr>
						<tr>   
							<th class=\"center\" >Total Base</th> 
							<td class=\"center\" ><a href=\"{$base_total_link}\" >{$base_total}</a></td>
						</tr> 
						<tr>   
							<th class=\"center\" >Total Calls</th> 
							<td class=\"center\" ><a href=\"{$result_total_link}\" >{$all_total}</a></td>
						</tr> 
						<tr>   
							<th class=\"center\" >Total Calls Ave.</th> 
							<td class=\"center\" >".round($all_ave,1)."%</td>
						</tr> 
						<tr>   
							<th class=\"center\" >Unattempted Calls</th> 
							<td class=\"center\" >".($base_total-$all_total)."</td>
						</tr>
						<tr>   
							<th class=\"center\" >Unattempted Calls Ave.</th> 
							<td class=\"center\" >".round((($base_total-$all_total) / $base_total) * 100,1)."%</td>
						</tr> 
					";  
						
			/*$total_arr = array("base_count"=>$base_total, 
							   "all_total"=>$all_total, 
							   "all_ave"=>$all_ave, 
							   "not_call"=>($base_total-$all_total), 
							   "not_call_ave"=>round((($base_total-$all_total) / $base_total) * 100, 2)
							    );*/
					 
								 
		 }
		else
		 {
			 $colspan = count($this->report_status) + count($this->report_source); 
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"10\" >No user found!</td>
						</tr>
			 			";
		 }
		
		return array($return, $return2); 
	}
	
	
	public function getAgentList()
	{
		
		//$where_arr = array("a.outcome_status"=>'1');
		$data = $this->input->post();   
		
		if($data[result]) $where_arr = array("FIND_IN_SET(a.mb_usertype, '{$data[result]}') !="=>0 );  
		//$this->db->where("FIND_IN_SET(a.mb_usertype, '{$data[result]}') !=", '0'); 
		
		$results = $this->common->getUserAll_($where_arr);   
 		 
		echo  json_encode($results); 
	} 
	 
	
	public function exportCrmConversions($actual=0)
	 {	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_upload_crm_record() && !can_view_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = ""; 
		$search_string2 = "";
		$search_string3 = "";   
		$search_string4 = "";    
		$search_arr = array(); 
		$data_link =  array();   
		 
		if(trim($data[s_agent]))
		 {
			//$agent = $this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name ="=>"csa", "a.mb_no ="=>$data[s_agent])); 
			//$crm_agent = $agent[0]->mb_nick; 
			 
			$search_string3 .= " AND (b.AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_string4 .= " AND (a.OfferedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);      
		 }  
		else
		 {
			//$crm_agent = "All"; 	   
			if(!admin_access())
			 {
				 if(!csd_supervisor_access()) 
				 {
					$search_string3 .= " AND (b.AddedBy={$this->session->userdata(mb_no)}) "; 
					$search_string4 .= " AND (a.OfferedBy={$this->session->userdata(mb_no)}) "; 
				 }	 
			 }
			 
		 }
		    
		if(trim($data[s_currency]))
		 { 
			$search_string .= " AND (a.Currency=".$this->common->escapeString_(trim($data[s_currency])).") ";    
		 }
		else{
			if(!admin_access()) 
			 {
				$search_string .= " AND (a.Currency IN({$this->session->userdata(mb_currencies)}) ) "; 
			 }
		}
		 
		/*if(trim($data[s_promotion]))
		 { 
			//$promotion = $this->reports->getPromotionById_(array("a.PromotionID ="=>$data[s_promotion])); 
			//$promotion_name = $promotion->Name; 
			  
			$search_string2 .= " AND (Promotion=".$this->common->escapeString_(trim($data[s_promotion])).") "; 
			$search_url .= "&s_promotion=".trim($data[s_promotion]);    
			$search_arr["a.Promotion ="] = trim($data[s_promotion]); 
			$data_link['s_promotion'] = trim($data[s_promotion]);
		 }
		else
		 {
			$promotion_name = "All"; 
		 }*/
		 
		 if(trim($data[s_category]))
		 {
			//$category = $this->common->getPromotionCategoriesAll_(array("a.CategoryID ="=>$data[s_category])); 
			//$category_name = $category[0]->Name;
			  
			$search_string .= " AND (a.Category=".$this->common->escapeString_(trim($data[s_category])).") "; 
			$search_url .= "&s_category=".trim($data[s_category]);     
		 }
		else
		 {
			$search_string .= " AND (a.Category > 0) "; 
		 }
		   
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			//if(trim($data[s_agent]))$search_string3 .= " AND (b.DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			//if(trim($data[s_agent]))$search_string3 .= " AND (b.DateAddedInt >= {$s_fromdate} )  ";    
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));     
		 } 
		
		if(trim($data[s_product]))
		 {    
			$search_string2 .= " AND (c.SubID=".$this->common->escapeString_(trim($data[s_product])).") "; 
			$search_url .= "&s_product=".trim($data[s_product]);     
		 }
		else
		 {
			$search_string2 .= " AND (b.SubProductID > 0) "; 
		 }
		  
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		   
		$results = $this->reports->getCrmConversionsDetails_($search_string, $search_string2, $search_string3, $search_string4);      
					   
		$excel_data = array("SubProductName"=>"Product", 
							"CategoryName"=>"Campaign",
							"PromotionName"=>"Promotion",
							"Abbreviation"=>"Currency",
							"TotalLeads"=>"Total Leads",
							"TotalReached"=>"Contacted",
							"TotalOffered"=>"Offer",
							"TotalDeposited"=>"Market", 
							"TotalPersonalDeposited"=>"Personal", 
							"TotalClaimed"=>"Market", 
							"TotalPersonalClaimed"=>"Personal", 
							"ConversionMarket"=>"Market", 
							"ConversionPersonal"=>"Personal", 
							"ConversionAuto"=>"Auto" 
						);
		
		$force_str = array("SubProductName", "CategoryName");
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "crm_conversions".'-'.date("Ymdhis").".xls"; 
		$title = "CRM Conversions";
		
		//load our new PHPExcel library
		$this->load->library('excel'); 
		 
		//activate worksheet number 1
		$activeSheet = $this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$activeSheet->setTitle($title);
		  
		$headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => '8DB4E2')
									   ), 
							 'font'=> array('bold'=>true), 		   
							 'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 )), 
							
							); 
								  
		$reportStyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'f4ec12'),
										'font'=> array('bold'=>true)
									 ), 
							'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 ))
						   );  
		$reportStyle2 =array('fill' => array('color' => array('rgb' => 'f4ec12'),
										 	 'font'=> array('bold'=>true)
										),
							'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 ))
						   ); 
										   
		$normalStyle = array('alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 ))
							  );
		
		$headerComplaint = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'B94A48'),
										'font'=> array('bold'=>true)
									   ), 
							 'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 ))  
							);
		
		$res_footer = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'B94A48'),
										'font'=> array('bold'=>true)
									   ),
						   'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        )
							
						);   
		 
		 $invidual_total = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'DEE2E3'),
										'font'=> array('bold'=>true)
									   ), 
							 'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 ))  
							);
							   
		//PUT HEADER  
		$activeSheet->setCellValue("A1", " ");
		$activeSheet->setCellValue("H1", "Deposited");
		$activeSheet->setCellValue("J1", "Claimed");
		$activeSheet->setCellValue("L1", "Conversion");   
		
		//merge 
		$activeSheet->mergeCells("A1:G1");  
		$activeSheet->getStyle("A1:G1")->applyFromArray($headerStyle);
		$activeSheet->mergeCells("H1:I1");   
		$activeSheet->getStyle("H1:I1")->applyFromArray($headerStyle);
		$activeSheet->mergeCells("J1:K1");  
		$activeSheet->getStyle("J1:K1")->applyFromArray($headerStyle);
		$activeSheet->mergeCells("L1:N1"); 
		$activeSheet->getStyle("L1:N1")->applyFromArray($headerStyle);
		
		$y = 'A';
		$start = 2; 
		foreach($excel_data as $row=>$val){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,$val.' ');
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
			$y++; 
		}//end foreach  
		//END PUT HEADER 
		 
		$ctr = $start + 1; 
		$all_ave = 0; 
		$all_total = 0; 
		 
		$total = array(
					"total_leads"=> 0, 
					"total_contacted"=> 0, 
					"total_offered"=> 0, 
					"total_deposited"=> 0, 
					"total_personal_deposited"=> 0,	   
					"total_claimed"=> 0,  
					"total_personal_claimed"=> 0,       
					"total_market_percentage"=> 0, 
					"total_personal_deposited_per"=> 0,	  
					//"total_personal_claimed_per"=> 0,  
					"total_auto_percentage"=> 0	 	 
				); 
		$mandatory_footer = array("total_leads", "total_contacted", "total_offered");  
		$percent_txt = array("ConversionMarket", "ConversionPersonal", "ConversionAuto", "total_market_percentage", "total_personal_deposited_per", "total_auto_percentage" );  
		
		$data_cat = array(); 
		$data_details_list =  array();   
												
		foreach($results as $row=>$data){  
			$count_x = $ctr;     
			$total_ave = 0; 
			$total_count = 0;  
			$x = 'A'; 
			
			//$base_total = $result[BaseCount]; 
			//$result_total = $result[CallCount];
			if($data->ClaimedOnly == '1')
			 {
				 $data->ConversionMarket = round(($data->TotalClaimed/$data->TotalLeads) * 100, 2) * 1; 
				 $data->ConversionPersonal = round(($data->TotalPersonalClaimed/($data->TotalReached + $data->TotalOffered)) * 100, 2) * 1; 
				 $data->ConversionAuto = round((($data->TotalClaimed - $data->TotalPersonalClaimed)/$data->TotalLeads) * 100, 2) * 1; 
			 }
			else
			 {
				 $data->ConversionMarket = round(($data->TotalDeposited/$data->TotalLeads) * 100, 2) * 1; 
				 $data->ConversionPersonal = round(($data->TotalPersonalDeposited/($data->TotalReached + $data->TotalOffered)) * 100, 2) * 1; 
				 $data->ConversionAuto = round((($data->TotalDeposited - $data->TotalPersonalDeposited)/$data->TotalLeads) * 100, 2) * 1; 
			 }
			
			
			
			$total[total_leads] += $data->TotalLeads;
			$total[total_contacted] += $data->TotalReached;
			$total[total_offered] += $data->TotalOffered;
			
			if($data->ClaimedOnly != '1')$total[total_deposited] += $data->TotalDeposited;
			if($data->ClaimedOnly != '1')$total[total_personal_deposited] += $data->TotalPersonalDeposited;
			
			$total[total_claimed] += $data->TotalClaimed;
			$total[total_personal_claimed] += $data->TotalPersonalClaimed;
			
			if($data->ClaimedOnly != '1')
			 {
				$data->TotalDeposited = ($data->TotalDeposited > 0 || $data->TotalClaimed > 0)?$data->TotalDeposited:""; 	
				$data->TotalPersonalDeposited = ($data->TotalPersonalClaimed > 0 || $data->TotalPersonalDeposited > 0 || $data->TotalClaimed > 0 || $data->TotalDeposited > 0)?$data->TotalPersonalDeposited:""; 
			 }
			else
			 {
				$data->TotalDeposited = "NA"; 	
				$data->TotalPersonalDeposited = "NA"; 
			 } 
			 
			$data->TotalClaimed = ($data->TotalClaimed > 0 || $data->TotalDeposited > 0)?$data->TotalClaimed:""; 	
			$data->TotalPersonalClaimed = ($data->TotalPersonalClaimed > 0 || $data->TotalPersonalDeposited > 0 || $data->TotalClaimed > 0 || $data->TotalDeposited > 0)?$data->TotalPersonalClaimed:""; 
			
			$data->ConversionMarket = ($data->TotalDeposited > 0 || $data->TotalClaimed > 0)?$data->ConversionMarket:"";
			$data->ConversionPersonal = ($data->TotalDeposited > 0 || $data->TotalClaimed > 0)?$data->ConversionPersonal:"";
			$data->ConversionAuto = ($data->TotalDeposited > 0 || $data->TotalClaimed > 0)?$data->ConversionAuto:""; 
			     
			//checking $data_details_list  
			if(!isset($data_details_list[$data->SubProductID.'_'.$data->Category]))
			 {  
			 
				if(count($data_details_list) > 0)
				 {
					$last = end(array_keys($data_details_list));    
					$data_details_list[$last][end] = $ctr;       
					if(isset($data_cat[$data_details_list[$last][sub_productid]]))$data_cat[$data_details_list[$last][sub_productid]][end] = $ctr;   
					
					$ctr++;       
					
				 } 
				 
				$data_details_list[$data->SubProductID.'_'.$data->Category] = array(
					"start"=>$ctr, 
					"end"=>$ctr, 
					"sub_productid"=>$data->SubProductID,
					"category"=>$data->Category,
					"total_leads"=>$data->TotalLeads, 
					"total_contacted"=>$data->TotalReached, 
					"total_offered"=>$data->TotalOffered, 
					"total_deposited"=>($data->TotalDeposited != '1')?$data->TotalDeposited:0, 
					"total_personal_deposited"=>($data->ClaimedOnly != '1')?$data->TotalPersonalDeposited:0, 	   
					"total_claimed"=>$data->TotalClaimed,  
					"total_personal_claimed"=>$data->TotalPersonalClaimed,       
					"total_market_percentage"=>$data->ConversionMarket,  
					"total_personal_deposited_per"=>$data->ConversionPersonal,	   
					"total_auto_percentage"=>$data->ConversionAuto	 
				);   
				 
					
			 }
			else
			 {     
				$data_details_list[$data->SubProductID.'_'.$data->Category][end] = $ctr;   
				$data_details_list[$data->SubProductID.'_'.$data->Category][sub_productid] = $data->SubProductID; 
				$data_details_list[$data->SubProductID.'_'.$data->Category][category] = $data->Category; 
				
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_leads] += $data->TotalLeads; 
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_contacted] += $data->TotalReached; 
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_offered] += $data->TotalOffered;  
				
				if($data->ClaimedOnly != '1')
				 {
					$data_details_list[$data->SubProductID.'_'.$data->Category][total_deposited] += $data->TotalDeposited; 
					$data_details_list[$data->SubProductID.'_'.$data->Category][total_personal_deposited] += $data->TotalPersonalDeposited;
				 }
				 
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_claimed] += $data->TotalClaimed;  
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_personal_claimed] += $data->TotalPersonalClaimed;
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_market_percentage] += $data->ConversionMarket; 
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_personal_deposited_per] += $data->ConversionPersonal;	   
				$data_details_list[$data->SubProductID.'_'.$data->Category][total_auto_percentage] += $data->ConversionAuto; 
			 }
		 
			//checking data_cat
			if(!isset($data_cat[$data->SubProductID]))
			 {    	
			 	//if(count($data_cat) > 0)$ctr++;
				$data_cat[$data->SubProductID] = array("start"=>$ctr, "end"=>$ctr); 
			 }
			else
			 {
				 $data_cat[$data->SubProductID][end] = $ctr; 
			 }
			     
			foreach($excel_data as $index=>$field){  
				$end_char = $x;  
				//$per_txt = (in_array($index, $percent_txt) && $data->TotalDeposited && $data->TotalDeposited != "NA")?'%':'';   
				$per_txt = (in_array($index, $percent_txt) && ($data->TotalDeposited || $data->TotalClaimed) )?'%':'';   
				
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$ctr,trim($data->$index).$per_txt, PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($data->$index).$per_txt, PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				  
				$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle);  
				$x++; 
				
			}//end foreach  
			   
			//result footer   
			//$row_span = "A{$ctr}:{$end_char}{$ctr}";
			//$activeSheet->setCellValue('C'.$ctr,trim($total_count), PHPExcel_Cell_DataType::TYPE_STRING); 
			//$activeSheet->setCellValue('D'.$ctr,round($total_ave,1).'%', PHPExcel_Cell_DataType::TYPE_STRING); 
			
			//merge
			//$merge_cells = "A{$count_x}:A".($ctr-1);
			//$activeSheet->mergeCells($merge_cells); 
			 
			//$activeSheet->getStyle($row_span)->applyFromArray($res_footer);
			  
			$count_x++;    
			$ctr++;
			 
		}//end foreach 
		
		//put the total for last campaign
		if(count($data_details_list) > 0)
		 {
			$last = end(array_keys($data_details_list));    
			$data_details_list[$last][end] = $ctr;    
			if(isset($data_cat[$data_details_list[$last][sub_productid]]))$data_cat[$data_details_list[$last][sub_productid]][end] = $ctr;  
			$ctr++; 		 
		}
				 
		 
		$total[total_market_percentage] = (($total[total_deposited] / $total[total_leads]) * 100) * 1; 
		$total[total_personal_deposited_per] = (($total[total_personal_deposited] / ($total[total_contacted] + $total[total_offered])) * 100) * 1; 
		//$total[total_personal_claimed_per] = (($total[total_personal_claimed] / $total[total_contacted]) * 100) * 1;  
		$total[total_auto_percentage] = ((($total[total_deposited] - $total[total_personal_deposited]) / $total[total_leads]) * 100) * 1;
		  
		//$data_cat_details  
		foreach($data_details_list as $row=>$field)
		 { 
		 	 $y_last = 'E';  
			 $not_display = array("start", "end", "sub_productid", "category"); 
			 $activeSheet->getStyle("A{$field[end]}")->applyFromArray($normalStyle);
			 $activeSheet->getStyle("B{$field[end]}")->applyFromArray($invidual_total);
			 $activeSheet->getStyle("C{$field[end]}")->applyFromArray($invidual_total);
			 $activeSheet->getStyle("D{$field[end]}")->applyFromArray($invidual_total);
			 //$activeSheet->getStyle("B{$ctr}:C{$ctr}")->applyFromArray($normalStyle);  
			 //$activeSheet->setCellValue("A{$ctr}", "TOTAL");
			 $activeSheet->mergeCells("C{$field[end]}:D{$field[end]}");    
			 
			 foreach($field as $row_2=>$tdata) {
				if(!in_array($row_2, $not_display))
				 {
					$activeSheet->setCellValue("{$y_last}{$field[end]}", $tdata);   
					$activeSheet->getStyle("{$y_last}{$field[end]}")->applyFromArray($invidual_total); 
					$y_last++;
				 }
			 } 
			 
			 if($field[start] != $field[end])$activeSheet->mergeCells("B{$field[start]}:B{$field[end]}");  
		 }   
		
		//$data_cat
		foreach($data_cat as $row=>$field)
		 {
			 if($field[start] != $field[end])$activeSheet->mergeCells("A{$field[start]}:A{$field[end]}"); 
		 } 
	  
		//FOOTER   
		$y = 'D';
		$activeSheet->setCellValue("A{$ctr}", "TOTAL"); 
		$activeSheet->mergeCells("A{$ctr}:{$y}{$ctr}");  
		$activeSheet->getStyle("A{$ctr}:{$y}{$ctr}")->applyFromArray($headerStyle); 
		$y++;
		foreach($total as $row=>$val){  
			$value = round($val, 2) * 1;   
			$per_txt = (in_array($row, $percent_txt))?'%':'';      
			$row_cel = $y.$ctr;   
			$value_txt = (($total[total_claimed] <= 0) && ($total[total_deposited] <= 0) && !in_array($row, $mandatory_footer) )?" ":$value.$per_txt;     
			
			$activeSheet->setCellValue($row_cel, $value_txt.' ');
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle); 
			$y++; 
		}//end foreach 
		//END FOOTER   
		$ctr++; 
		
		 
		 
		//total reports	  
		 /*$title_data = array("CRM Agent"=>$crm_agent, 
							"Currency"=>$currency_name, 
							"Category"=>$category_name, 
						    "Promotion"=>$promotion_name, 
						    "Total Base"=>$base_total, 
						    "Total Calls"=>$all_total, 
						    "Total Calls Ave."=>round($all_ave, 1).'%',
						    "Unattempted Calls"=>($base_total-$all_total), 
						    "Unattempted Calls Ave."=>round((($base_total-$all_total) / $base_total) * 100, 1).'%'
						  ); */
						  
		 	
	  
		//set auto width
		$x='A';
		$col = 0;
 
		for($i=0; $i<(count($excel_data)); $i++){
			$activeSheet->getColumnDimension($x)->setAutoSize(true);      
			$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
			$x++;	
		} 
		 
		//end set auto width 
		
		//REMOVE COMMENT IF WANT TO DOWNLOAD DIRECTLY
		//header('Content-Type: application/vnd.ms-excel');
		//header('Content-Disposition: attachment;filename="'.$file_name.'"');
		//header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5'); 
		
		//$objWriter->save('php://output'); 
		$filePath = $this->common->temp_file;
		$objWriter->save($filePath.$file_name);  
		$return = (file_exists($filePath.$file_name))?array("success"=>1, "message"=>"Downloading file.", "download_link"=>encode_string($filePath.$file_name)):array("success"=>0, "message"=>"Error downloading file.", "download_link"=>""); 
		echo json_encode($return); 
		
	}//end export activities
	 
	
	public function getCrmConversionsOld($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_upload_crm_record() && !can_view_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = ""; 
		$search_string2 = "";    
		$search_arr = array(); 
	 
		
		if(trim($data[s_result]))
		 {
			$search_string .= " AND (a.result_id=".$this->common->escapeString_(trim($data[s_result])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 }
		 
		  
		if(trim($data[s_agent]))
		 {
			$search_string2 .= " AND (AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 }  
		
		/*if(trim($data[s_currency]))
		 {
			$search_string .= " AND FIND_IN_SET('".$this->common->escapeString_(trim($data[s_currency]))."', a.mb_currencies) "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } */
		  
		if($s_fromdate && $s_todate)
		 {  
			$search_string2 .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		  
		//$per_page = 20;  
		
		//$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countRecords_($search_string, "g4_member AS a", "a.mb_no")->TotalCount; 
	 	 
		$outcome_data = $this->reports->getCrmConversions_($search_string, $search_string2); 
		 
	   
	          
		//separate results
		//$results_data = $this->reports->getCountCallResults_($search_string2);  
		$results = array();    
		$ctr = 0; 
		foreach($outcome_data as $row=>$outcome){ 
			if(array_key_exists($outcome->result_id, $results))
			 {
				 $results[$outcome->result_id][CallCount] = $results[$outcome->result_id][CallCount] + $outcome->CallCount;  
				 $results[$outcome->result_id][CallData][] = $outcome;    
			 }
			else
			 {
				 $results[$outcome->result_id] = array("Name"=>$outcome->result_name, "CallCount"=>$outcome->CallCount, "CallData"=>array($outcome) );  
			 } 
		} 
		//end separate results 	 
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		/*$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"users":"user";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; */
		 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("outcomes"=>$outcome_data,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
			$return = array("outcomes"=>$this->generateHtmlOutcomeData($outcome_data, $results), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows 
					   ); 
					 
			 echo json_encode($return); 
		 }
		
	}
	 
	
	public function popupUploadRecord()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_upload_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$post = $this->input->post(); 
		
		if(!isset($post[s_action])) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 no action detected. Ask admin.", "403");
		 	return false; 
		 }
		 
		  
		$categories_where = array("a.Status"=>'1', "a.ForConversions"=>'1'); 
		
		$data2 = array("main_page"=>"reports",  
					   //"currencies"=>$this->common->getCurrency_(),  
					   //"status_list"=>$this->common->getStatusList_(3),//3 promotion 
					   //"products"=>$this->common->getProductsList_(array("a.Status ="=>1)),
					   //"utypes"=>$this->common->getUsersGroup_(array("Status ="=>'1')), //user types
					   "categories"=>$this->common->getPromotionCategoriesAll_($categories_where), 
					   "post"=>$this->input->post()
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - Reports - CRM Conversions - Upload Records ",  
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('reports/crm_uploaded_popup_tpl',$data); 
		 
	}  
	 
	 
	public function uploadCrmRecord()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_upload_crm_record()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		set_time_limit(0); 
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s");
		
		$error =  ""; 
		$filename = $_FILES['act_attachfile']['name']; 
		$ext = pathinfo($filename, PATHINFO_EXTENSION); 
		$upload_id = $this->session->userdata("mb_no").'-'.uniqid();
		  
		if (!isset($_FILES['act_attachfile']) && empty($_FILES['act_attachfile']['name']))
		 {  
			 $error .= "Please select file to upload!<br> "; 
		 }  
		 
		if(!in_array($ext,$this->import_types) ) 
		{
			$error .= "Please select a valid file type!<br> ";
		}  
		  
		if($data[act_category] == "")
		 {
			 $error .= "Select category!<br> ";
		 } 
		
		if($data[act_subproductid] == "")
		 {
			 $error .= "Select sub product!<br> ";
		 } 
		 
		if($data[act_fromdate] == "" || $data[act_fromdate] == "")
		 {
			 $error .= "Select category!<br> ";
		 } 
		 
		if($data[hidden_action] == "")
		 {
			 $error .= "No selected action!<br> ";
		 }
		  
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else 
		 {   
		 	 $from_date = strtotime(trim($data[act_fromdate]));   
			 $to_date = strtotime(trim($data[act_todate]));    
			 
		 	 $action = trim($data[hidden_action]);  
			 $claimed_deposit = ($action == "deposited")?'1':'2'; //1-deposited, 2-claimed
			 
			 $rows = array();   
			 //Import uploaded file to Database   
			 $rows_data = explode("\n", file_get_contents($_FILES['act_attachfile']['tmp_name']));  
			 
			 $x = 0;    
			 //$rows = array_map(function($user) use ($claimed_deposit) {return ($user && (trim($user)!= ""))?array("Username"=>$user,"ClaimedDeposited"=>$claimed_deposit):false;}, $rows_data);
			 
			 //$handle = fopen($_FILES['act_attachfile']['tmp_name'], "r");   
			 /*while (($record = fgetcsv($handle, 1000, ",")) !== FALSE) { 
				$username = strtolower(trim($record[0])); 
				if($username && $username!="")
				 { 
				 	//echo trim($username)."<br>"; 
					$rows[$x] = array("Username"=>trim($username), 
									  "ClaimedDeposited"=>($action == "deposited")?1:2  
									  );   
					$x++;   
					 
				 }  
				 
			}
			*///end while 
			//fclose($handle);  
			  
			foreach($rows_data as $rec) {
				$username = strtolower(trim($rec)); 
				if($username && $username!="")
				 {  
					$rows[$x] = array("Username"=>trim($username), 
									  "ClaimedDeposited"=>$claimed_deposit 
									  );   
					$x++;    
				 }  	
			}    
			
			$chunk_rows = array_chunk($rows, 1000);   
			
			if(count($rows) > 0)
			 { 
				$where_arr = array("a.IsUpload"=>'1', 
								   "a.Category"=>trim($data[act_category]),   
								   "(a.DateUploadedInt BETWEEN {$from_date} AND {$to_date}) <>"=>0, 
								   "a.ClaimedDeposited <>"=>'2', //2-claimed
								   "b.SubProductID"=>trim($data[act_subproductid])
								  ); 
			 	
				$history_data = array("f_date"=>$from_date, 
								   "t_date"=>$to_date, 
								   "allowed_status"=>array(19, 67) //19-stlm credited, 67-kk credited
								 ); 
				
				//if($claimed_deposit == '2')$where_arr["c.CountStatusHistory >"] = 0; 
				
				$count_rec = 0;  
				$total_rec = 0;  
				$total_updated = 0; 
				foreach($chunk_rows as $rec){
					$count_rec = $this->reports->batchUpdateCrmConversion_($rec, "Username", $where_arr, $history_data);	
					$total_rec += count($rec);  
					$total_updated += $count_rec; 
					//break;
				}
				 
				$return = ($count_rec > 0)?array("success"=>1, "message"=>"File uploaded successfully. <br>Total Updated: <b>{$total_updated} --- {$total_rec}</b>", "records"=>$count_rec, "is_change"=>1):array("success"=>1, "message"=>"No record updated. Please check your uploaded file.! <br> "); 
			 }
			else
			 {
				 $return = array("success"=>0, "message"=>"No record to save. Please check your uploaded file.! <br> ", "no_update"=>1); 
			 }
			  
		 }
		
		
		echo json_encode($return);
		 
		 
	} 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */