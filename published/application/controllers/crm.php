<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Crm extends MY_Controller {

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
	 
	}
 	
	public function index()
	{    
		$this->crmCalls();    
	} 
	 
	public function crmCalls()
	{    
		if(!can_crm_calls()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$categories_where['a.Status ='] = '1';  
		
   		if(restriction_type()) 
		 { 
			//$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
			$categories_where["FIND_IN_SET({$this->session->userdata('mb_usertype')}, a.Viewers) !="] = 0;
		 }
		  					
		$data2 = array("main_page"=>"reports",      	 
					   "agents"=>$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "a.mb_usertype"=>$this->common->ids['crm_id'])),  
					   //"outcomes"=>$this->reports->getCallOutcomeList_(array("a.outcome_status ="=>1, "b.result_status ="=>1)),  
					   "results"=>$this->reports->getCallResultList_(array("a.result_status"=>'1')),
					   "currencies"=>$this->common->getCurrency_(), 
					   "promotions"=>$this->reports->getChangePromotion_(array(), array(), 2), 
					   "categories"=>$this->common->getPromotionCategoriesAll_($categories_where),
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - CRM Calls ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('reports/cal_crm_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCrmReports($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!can_crm_calls()) 
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
		$data_link =  array(); 
	 	 
		/*if(trim($data[s_result]))
		 {
			$search_string .= " AND (a.result_id=".$this->common->escapeString_(trim($data[s_result])).") "; 
			$search_url .= "&s_result=".trim($data[s_result]);   
		 }*/ 
		  
		if(trim($data[s_agent]))
		 {
			$agent = $this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name ="=>"csa", "a.mb_no ="=>$data[s_agent])); 
			$crm_agent = $agent[0]->mb_nick; 
			 
			$search_string2 .= " AND (y.AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);    
			$data_link['s_agent'] = trim($data[s_agent]);
		 }  
		else
		 {
			$crm_agent = "All"; 	 
		 }
		 
		if(trim($data[s_currency]))
		 {
			$currency = $this->reports->getCurrencyById_(array("a.CurrencyID"=>$data[s_currency])); 
			$currency_name = $currency->Abbreviation;
			 
			$search_string2 .= " AND (x.Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);    
			$search_arr["a.Currency ="] = trim($data[s_currency]);   
			$data_link['s_currency'] = trim($data[s_currency]);  
			
		 }
		else
		 {
			$currency_name = "All"; 
		 }
		 
		if(trim($data[s_promotion]))
		 {
			/*if($data[s_promotion] == "N/A")
			 {
				 $search_string2 .= " AND (h.Name LIKE '%".$this->common->escapeString_($data[s_promotion])."%') "; 
			 }
			else
			 {
				$search_string2 .= " AND (a.Promotion='".$this->common->escapeString_($data[s_promotion])."') ";  
			 }*/
			$promotion = $this->reports->getPromotionById_(array("a.PromotionID ="=>$data[s_promotion])); 
			$promotion_name = $promotion->Name; 
			  
			$search_string2 .= " AND (x.Promotion=".$this->common->escapeString_(trim($data[s_promotion])).") "; 
			$search_url .= "&s_promotion=".trim($data[s_promotion]);    
			$search_arr["a.Promotion ="] = trim($data[s_promotion]); 
			$data_link['s_promotion'] = trim($data[s_promotion]);
		 }
		else
		 {
			$promotion_name = "All"; 
		 }
		 
		 if(trim($data[s_category]))
		 {
			$category = $this->common->getPromotionCategoriesAll_(array("a.CategoryID ="=>$data[s_category])); 
			$category_name = $category[0]->Name;
			  
			$search_string2 .= " AND (x.Category=".$this->common->escapeString_(trim($data[s_category])).") "; 
			$search_url .= "&s_category=".trim($data[s_category]);    
			$search_arr["a.Category ="] = trim($data[s_category]); 
			$data_link['s_category'] = trim($data[s_category]);
		 }
		else
		 {
			$category_name = "All"; 
		 }
		   
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string2 .= " AND (x.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));   
			$search_arr["a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate} !="] = 0;   
			
			$data_link['s_fromdate'] = trim($data[s_fromdate]);
			$data_link['s_todate'] = trim($data[s_todate]); 
			//if($active==1)$this->db->where("DATE(NOW()) BETWEEN a.StartedDate AND a.EndDate");  
		 } 
		
		if(trim($data[s_isupload]) == '1' )
		 {
			$search_string2 .= " AND (x.IsUpload='".$this->common->escapeString_(trim($data[s_isupload]))."') "; 
			$search_url .= "&s_isupload=".trim($data[s_isupload]);    
			$search_arr["a.IsUpload ="] = '1'; 
			$base_count =  $this->reports->getCountUploadTotal_($search_arr)->CountBase; 
			$data_link['s_isuploaded'] = 1;
			$data_link['s_dateindex'] = "uploaded"; 
		 }
		else
		 {
			$base_count = trim($data[s_basetotal]);  
		 }
		//$per_page = 20;  
		
		//$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countRecords_($search_string, "g4_member AS a", "a.mb_no")->TotalCount; 
	 	  
		$outcome_data = $this->reports->getCrmReports_($search_string, $search_string2); 
	   
		//separate results
		//$results_data = $this->reports->getCountCallResults_($search_string2);  
		$results = array();    
		$ctr = 0; 
		$all_total = 0; 
		$all_ave = 0;  
		foreach($outcome_data as $row=>$outcome){ 
			$all_total += $outcome->CallCount; 
			$all_ave += $average; 
			if(array_key_exists($outcome->result_id, $results))
			 {
				 $results[$outcome->result_id][CallCount] = $results[$outcome->result_id][CallCount] + $outcome->CallCount;  
				 $results[$outcome->result_id][CallData][] = $outcome;    
			 }
			else
			 {
				 //base total link
				 $datas = $data_link; 
				 $datas['s_dateindex'] = "uploaded";
				 $datas['s_displayclose'] = '1';
				 $params = encode_string(http_build_query($datas, '', '&amp;'));   
				 $base_total_link = (trim($data[s_isupload]) == '1')?base_url("promotions/activities/".$params):"#";
				 
				 //call total link
				 $call_data_link = $data_link; 
				 $call_data_link[s_call] = '1'; 
				 $call_data_link['s_displayclose'] = '1';
				 $call_params = encode_string(http_build_query($call_data_link, '', '&amp;'));   
				 $call_count_link = (trim($data[s_isupload]) == '1')?base_url("promotions/activities/".$call_params):"#";
				 
				 $results[$outcome->result_id] = array("Name"=>$outcome->result_name, 
				 									   "CallCount"=>$outcome->CallCount, 
													   "CallCountLink"=>$call_count_link, 
				 									   "CallData"=>array($outcome), 
													   "BaseCount"=>$base_count, 
													   "BaseCountLink"=>$base_total_link );  
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
		$options = array("currency_name"=>$currency_name,
				 		 "promotion_name"=>$promotion_name, 
						 "crm_agent"=>$crm_agent, 
						 "category_name"=>$category_name, 
						 "base_total_link"=>$base_total_link 
				        );  
				 
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
			//list($outcomes, $total_arr) = $this->generateHtmlOutcomeData($outcome_data, $results, $options); 
			list($outcomes, $total_arr) = $this->processData($outcome_data, $results, $options); 		   
			$return = array("outcomes"=>$outcomes, 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>count($outcome_data), 
							"summary"=>$total_arr
							//"total_arr"=>$total_arr
					   ); 
					 
			 echo json_encode($return); 
		 }
		
	} 
	
	
	public function processData($outcome_data, $results, $options=array())
	{
		$return = "";    
		
		$items = array(); 
		
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
					   
					$data->Average = $average;  
					$data->IsSummary = 0; 
					
					if($count_x == 0)
					 {
						$data->RowSpan = (count($result[CallData]) > 1)?count($result[CallData]):0; 
					 }
					$data->Countx = $count_x;  
					
					$data->html = "td";
					
					$count_x++; 
					$item = $data; 
					  
					$items[] = $item; 
					
							 
						
				}//end foreach
				  
			  	$last_item = clone $data; 
				
				$last_item->RowSpan = 0; 
				$last_item->outcome_name = ""; 
				$last_item->CallCount = $result[CallCount];  
				$last_item->Average = round($total_ave, 1); 
				$last_item->IsSummary = 1; 
				$last_item->Countx = $count_x;  
				$last_item->html = "th";  
				$items[] = $last_item; 
				  
				$ctr++; 
			}//end foreach 
			  			
			$items2 = array("CrmAgent"=>$options[crm_agent],
							"Currency"=>$options[currency_name],
							"Category"=>$options[category_name],
							"Promotion"=>$options[promotion_name],
							"TotalBase"=>number_format($base_total, 0, '.', ','), 
							"TotalBaseLink"=>$base_total_link,
							"TotalCalls"=>number_format($all_total, 0, '.', ','), 
							"TotalCallsLink"=>$result_total_link, 
							"TotalCallsAve"=>round($all_ave,1),
							"UnattemptedCalls"=>number_format(($base_total-$all_total), 0, '.', ','),
							"UnattemptedCallsAve"=>round((($base_total-$all_total) / $base_total) * 100,1) 
							 ); 			 
								 
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
		 
		return array($items, $items2); 
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
							<th class=\"center\" colspan=\"2\" > </th>  
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
							<td class=\"center\" ><a href=\"{$base_total_link}\" >".number_format($base_total, 0, '.', ',')."</a></td>
						</tr> 
						<tr>   
							<th class=\"center\" >Total Calls</th> 
							<td class=\"center\" ><a href=\"{$result_total_link}\" >".number_format($all_total, 0, '.', ',')."</a></td>
						</tr> 
						<tr>   
							<th class=\"center\" >Total Calls Ave.</th> 
							<td class=\"center\" >".round($all_ave,1)."%</td>
						</tr> 
						<tr>   
							<th class=\"center\" >Unattempted Calls</th> 
							<td class=\"center\" >".number_format(($base_total-$all_total), 0, '.', ',')."</td>
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
	
	
	
	public function getCrmReportsPromotions($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!can_crm_calls()) 
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
		$data_link =  array(); 
	 	   
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (x.Currency=".$this->common->escapeString_(trim($data[s_currency])).") ";   
		 } 
		 
		if(trim($data[s_promotion]))
		 {     
			$search_string2 .= " AND (x.Promotion=".$this->common->escapeString_(trim($data[s_promotion])).") ";   
		 } 
		 
		 if(trim($data[s_category]))
		 {    
			$search_string2 .= " AND (x.Category=".$this->common->escapeString_(trim($data[s_category])).") ";  
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string2 .= " AND (x.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));     
		 } 
		
		if(trim($data[s_isupload]) == '1' )
		 {
			$search_string2 .= " AND (x.IsUpload='".$this->common->escapeString_(trim($data[s_isupload]))."') "; 
			$search_url .= "&s_isupload=".trim($data[s_isupload]);      
		 }  
		 
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		
        $per_page = 20; 
        $page = ($data['s_ppage']) ? $data['s_ppage'] : 0;
         
		$paging = ($action == "excel")?array():$paging=array("limit"=>$per_page, "page"=>$page); //if excell export all without paging
        $return = $this->reports->getCrmReportsPromotions_($search_string, $search_string2, $paging); 
		
        $total_rows = $return[total_rows];
        $promotions_data = $return[results];

        $pagination_options = array("link" => "", 
									"total_rows" => $total_rows,
									"per_page" => $per_page,
									"cur_page" => $page
								);

        $of_str = (($page + 20) <= $total_rows) ? $page + 20 : $total_rows;
        $disp_page = ($page == 0) ? 1 : $page + 1;
        $plural_txt = ($total_rows > 1) ? "promotions" : "promotion";
        $pagination_string = ($total_rows > 0) ? "Showing " . $disp_page . " to " . $of_str . " of " . $total_rows . " " . $plural_txt : "";
		
		 
		//separate results 
		$promotions = array();    
		$ctr = 0; 
		$all_total = 0; 
		$all_ave = 0;  
		/*foreach($outcome_data as $row=>$promotion){ 
			  
		} */
		//end separate results 	  
		
		$pagination_options = array("link"=>"",  
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								);   
				 
		if($actual == 1)//
		 {  
		 	 $return = array("promotions"=>$promotions_data,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {  	   
			$return = array("promotions"=>$promotions_data, 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows
					   ); 
					 
			 echo json_encode($return); 
		 }
		
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
	 
	
	public function exportCrmReport($actual=0)
	 {	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!can_crm_calls()) 
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
		$data_link =  array(); 
	 	 
		/*if(trim($data[s_result]))
		 {
			$search_string .= " AND (a.result_id=".$this->common->escapeString_(trim($data[s_result])).") "; 
			$search_url .= "&s_result=".trim($data[s_result]);   
		 }*/ 
		  
		if(trim($data[s_agent]))
		 {
			$agent = $this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name ="=>"csa", "a.mb_no ="=>$data[s_agent])); 
			$crm_agent = $agent[0]->mb_nick; 
			 
			$search_string2 .= " AND (y.AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);    
			$data_link['s_agent'] = trim($data[s_agent]);
		 }  
		else
		 {
			$crm_agent = "All"; 	 
		 }
		 
		if(trim($data[s_currency]))
		 {
			$currency = $this->reports->getCurrencyById_(array("a.CurrencyID"=>$data[s_currency])); 
			$currency_name = $currency->Abbreviation;
			 
			$search_string2 .= " AND (x.Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);    
			$search_arr["a.Currency ="] = trim($data[s_currency]);   
			$data_link['s_currency'] = trim($data[s_currency]);  
			
		 }
		else
		 {
			$currency_name = "All"; 
		 }
		 
		if(trim($data[s_promotion]))
		 {
			/*if($data[s_promotion] == "N/A")
			 {
				 $search_string2 .= " AND (h.Name LIKE '%".$this->common->escapeString_($data[s_promotion])."%') "; 
			 }
			else
			 {
				$search_string2 .= " AND (a.Promotion='".$this->common->escapeString_($data[s_promotion])."') ";  
			 }*/
			$promotion = $this->reports->getPromotionById_(array("a.PromotionID ="=>$data[s_promotion])); 
			$promotion_name = $promotion->Name; 
			  
			$search_string2 .= " AND (x.Promotion=".$this->common->escapeString_(trim($data[s_promotion])).") "; 
			$search_url .= "&s_promotion=".trim($data[s_promotion]);    
			$search_arr["a.Promotion ="] = trim($data[s_promotion]); 
			$data_link['s_promotion'] = trim($data[s_promotion]);
		 }
		else
		 {
			$promotion_name = "All"; 
		 }
		 
		 if(trim($data[s_category]))
		 {
			$category = $this->common->getPromotionCategoriesAll_(array("a.CategoryID ="=>$data[s_category])); 
			$category_name = $category[0]->Name;
			  
			$search_string2 .= " AND (x.Category=".$this->common->escapeString_(trim($data[s_category])).") "; 
			$search_url .= "&s_category=".trim($data[s_category]);    
			$search_arr["a.Category ="] = trim($data[s_category]); 
			$data_link['s_category'] = trim($data[s_category]);
		 }
		else
		 {
			$category_name = "All"; 
		 }
		   
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string2 .= " AND (x.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));   
			$search_arr["a.DateUploadedInt BETWEEN {$s_fromdate} AND {$s_todate} !="] = 0;   
			
			$data_link['s_fromdate'] = trim($data[s_fromdate]);
			$data_link['s_todate'] = trim($data[s_todate]); 
			//if($active==1)$this->db->where("DATE(NOW()) BETWEEN a.StartedDate AND a.EndDate");  
		 } 
		
		if(trim($data[s_isupload]) == '1' )
		 {
			$search_string2 .= " AND (x.IsUpload='".$this->common->escapeString_(trim($data[s_isupload]))."') "; 
			$search_url .= "&s_isupload=".trim($data[s_isupload]);    
			$search_arr["a.IsUpload ="] = '1'; 
			$base_count =  $this->reports->getCountUploadTotal_($search_arr)->CountBase; 
			$data_link['s_isuploaded'] = 1;
		 }
		else
		 {
			$base_count = trim($data[s_basetotal]);  
		 } 
	 	 
		 
		$outcome_data = $this->reports->getCrmReports_($search_string, $search_string2); 
		 
	   
	          
		//separate results
		//$results_data = $this->reports->getCountCallResults_($search_string2);  
		$results = array();    
		$ctr = 0; 
		$all_total = 0; 
		$all_ave = 0;  
		foreach($outcome_data as $row=>$outcome){ 
			$all_total += $outcome->CallCount; 
			$all_ave += $average; 
			if(array_key_exists($outcome->result_id, $results))
			 {
				 $results[$outcome->result_id][CallCount] = $results[$outcome->result_id][CallCount] + $outcome->CallCount;  
				 $results[$outcome->result_id][CallData][] = $outcome;    
			 }
			else
			 {
				 $results[$outcome->result_id] = array("Name"=>$outcome->result_name, "CallCount"=>$outcome->CallCount, "CallData"=>array($outcome), "BaseCount"=>$base_count );  
			 } 
		}     
					   
		$excel_data = array("result_name"=>"Result", 
							"outcome_name"=>"Outcome",
							"CallCount"=>"Total",
							"Average"=>"Percentage",
						);
		
		$force_str = array();
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "cal_crm_reports".'-'.date("Ymdhis").".xls"; 
		$title = "CRM Reports";
		
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
                                        )
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
							  
		$y = 'A';
		$start = 1;
		 
		 
		//PUT HEADER 
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
		
		foreach($results as $row=>$result){  
			$count_x = $ctr;     
			$total_ave = 0; 
			$total_count = 0;   
			
			$base_total = $result[BaseCount]; 
			$result_total = $result[CallCount];
			
			foreach($result[CallData] as $row2=>$data) { 
			 	$x = 'A';  
				 
				$data->Average = round(($data->CallCount / $base_total) * 100, 2);  
				$total_ave += $data->Average;
				$total_count += $data->CallCount; 
				
				$all_ave += $data->Average; 
				$all_total += $data->CallCount;  
				 
				foreach($excel_data as $index=>$field){  
					$end_char = $x;  
					    
					if(in_array($field, $force_str))
					 {
						$activeSheet->setCellValueExplicit($x.$ctr,trim($data->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
					 }
					else
					 {
						$activeSheet->setCellValue($x.$ctr,trim($data->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
					 }
					
					$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle);  
					$x++; 
					
				}//end foreach  
				 
				$ctr++; 
				
			}//end foreach 
			
			//result footer  
			//$activeSheet->mergeCells("G".($row_count+1).":I".($row_count+1));
			$row_span = "A{$ctr}:{$end_char}{$ctr}";
			$activeSheet->setCellValue('C'.$ctr,trim($total_count), PHPExcel_Cell_DataType::TYPE_STRING); 
			$activeSheet->setCellValue('D'.$ctr,round($total_ave,1).'%', PHPExcel_Cell_DataType::TYPE_STRING); 
			
			//merge
			$merge_cells = "A{$count_x}:A".($ctr-1);
			$activeSheet->mergeCells($merge_cells); 
			 
			$activeSheet->getStyle($row_span)->applyFromArray($res_footer);
			  
			$count_x++;    
			$ctr++;
			 
		}//end foreach 
		  
		//total reports	  
		$title_data = array("CRM Agent"=>$crm_agent, 
							"Currency"=>$currency_name, 
							"Category"=>$category_name, 
						    "Promotion"=>$promotion_name, 
						    "Total Base"=>$base_total, 
						    "Total Calls"=>$all_total, 
						    "Total Calls Ave."=>round($all_ave, 1).'%',
						    "Unattempted Calls"=>($base_total-$all_total), 
						    "Unattempted Calls Ave."=>round((($base_total-$all_total) / $base_total) * 100, 1).'%'
						  );
		$start = $ctr + 2; 
		foreach($title_data as $row=>$val){   
			$activeSheet->setCellValue('A'.$start, $row);  
			$activeSheet->setCellValue('B'.$start, $val); 
			$activeSheet->getStyle('A'.$start)->applyFromArray($reportStyle); 
			$activeSheet->getStyle('B'.$start)->applyFromArray($reportStyle2);
			 
			$start++; 
		}//end foreach 	
	 
		
		/*$activeSheet->setCellValue('A'.($ctr+2),"Total Base");  
		$activeSheet->setCellValue('B'.($ctr+2), $base_total); 
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle2); 
		
		$activeSheet->setCellValue('A'.($ctr+3),"Total Calls"); 
		$activeSheet->setCellValue('B'.($ctr+3), $all_total); 
		$activeSheet->getStyle('A'.($ctr+3))->applyFromArray($reportStyle);
		$activeSheet->getStyle('B'.($ctr+3))->applyFromArray($reportStyle2);
		
		
		$activeSheet->setCellValue('A'.($ctr+4),"Total Calls Ave."); 
		$activeSheet->setCellValue('B'.($ctr+4), round($all_ave, 1).'%'); 
		$activeSheet->getStyle('A'.($ctr+4))->applyFromArray($reportStyle); 
		$activeSheet->getStyle('B'.($ctr+4))->applyFromArray($reportStyle2);
		
		$activeSheet->setCellValue('A'.($ctr+5),"Unattempted Calls"); 
		$activeSheet->setCellValue('B'.($ctr+5), ($base_total-$all_total)); 
		$activeSheet->getStyle('A'.($ctr+5))->applyFromArray($reportStyle); 
		$activeSheet->getStyle('B'.($ctr+5))->applyFromArray($reportStyle2);
		
		$activeSheet->setCellValue('A'.($ctr+6),"Unattempted Calls Ave."); 
		$activeSheet->setCellValue('B'.($ctr+6), round((($base_total-$all_total) / $base_total) * 100, 1).'%'); 
		$activeSheet->getStyle('A'.($ctr+6))->applyFromArray($reportStyle); 
		$activeSheet->getStyle('B'.($ctr+6))->applyFromArray($reportStyle2);*/
		 
		//$activeSheet->getStyle("A".($ctr+2).":A".($ctr+6))->applyFromArray($reportStyle); 
		 
		//set auto width
		$x='A';
		$col = 0;
 
		for($i=0; $i<(count($outcome_data)); $i++){
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
	 
	
	public function getCrmReportsOld($actual=0)
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
	 	 
		$outcome_data = $this->reports->getCrmReports_($search_string, $search_string2); 
		 
	   
	          
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
	 
	
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */