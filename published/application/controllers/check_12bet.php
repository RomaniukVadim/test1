<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Check_12bet extends MY_Controller {

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
		$this->load->model("checking_model","checking");  
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
		$this->check12Bet();    
	} 
	 
	public function check12Bet()
	{    
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"checking",      	 
					   //"agents"=>$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "a.mb_usertype"=>$this->common->ids['crm_id'])),  
					   //"outcomes"=>$this->checking->getCallOutcomeList_(array("a.outcome_status ="=>1, "b.result_status ="=>1)),  
					   "currencies"=>$this->common->getCurrency_(),
					   "checking_categories"=>$this->checking->getCheckingCategory(array("a.Status"=>'1'))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Checking ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('checking/12bet_checking_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCheck12Bet($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = "";  
	 	   
		if(trim($data[s_category]))
		 {
			$search_string2 .= " AND (a.Category=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 }  
		
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (a.Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);     
		 }
		  
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateCheckedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_12beturl_check AS a", "a.CheckID")->TotalCount; 
	 	 
		 
		$checking_data = $this->checking->getCheck12Bet_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
		  
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"records":"record";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
		 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("checklist"=>$outcome_data,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
		 
			$return = array("checklist"=>$this->generateHtmlCheckedData($checking_data),
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows, 
							"total_arr"=>$total_arr
					   ); 
					 
			 echo json_encode($return); 
		 }
		
	} 
	
	 
	public function generateHtmlCheckedData($results)
	{
		$return = "";    
		
		if(count($results))
		 { 
		 	$ctr = 0;    	    
			foreach($results as $row=>$result){  
			  
				$return .= "
							<tr class=\"check_row check_category_{$result->Category}\" id=\"Check{$result->CheckID}\" >   
								<td class=\"center\" >".date("Y-m-d H:i:s", strtotime($result->DateChecked))."</td>  
								<td class=\"center\" >{$result->Abbreviation}</td>  
								<td class=\"center\" >{$result->CategoryName}</td>  
								<td class=\"center\" >{$result->UpdatedByNickname}</td>  
								<td class=\"center\" >
								<a href=\"#UserModal\" data-toggle=\"modal\" title=\"show details\" alt=\"show remarks\" class=\"show_remarks tip\" check-id=\"{$result->CheckID}\"  ><i class=\"icon16 i-file gap-left0 gap-right10\" ></i></a>
							";
				
				if(trim($result->Remarks))$return .= "<a href=\"#UserModal\" data-toggle=\"modal\"  title=\"show remarks\" alt=\"show remarks\" class=\"show_remarks tip\" check-id=\"{$result->CheckID}\"  ><i class=\"icon16 i-bubble-13 gap-left0 gap-right10\" ></i></a>";
							
				$return .= " 
								</td>  
							";
					 
				$return .= " 
						</tr> ";  
			   
				$ctr++; 
			}//end foreach 
			
			 
						 
								 
		 }
		else
		 {
			 $colspan = count($this->report_status) + count($this->report_source); 
			 $return = "
			 			<tr class=\"check_row\"  > 
							<td class=\"center\" colspan=\"5\" >No record found!</td>
						</tr>
			 			";
		 }
		
		return array($return, $return2); 
	}
	
	
	//POPUP CHANGE STATUS
	public function view12betCheckingDetails()
	{    
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!admin_access() && !can_check() ) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$check_id = trim($this->uri->segment(3));
		$view_only = trim($this->uri->segment(4)); 
		 
		if(!$check_id || $check_id == "") 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		$conditions_array = array('a.CheckID =' => $check_id); 
		$check = $this->checking->getCheck12BetById_($conditions_array);  
		 
		if(count($check) <= 0)
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false;  
		 }
		else
		 {
			//$view_only = (allowEditMain() || ($activity->GroupAssignee==$this->session->userdata('mb_usertype')) || ($activity->AddedBy==$this->session->userdata('mb_no')) )?0:1; 
			$where_arr = array("a.Category ="=>$check->Category, "a.Currency ="=>$check->Currency, "a.Status ="=>'1');
		  	$check_list = $this->checking->get12BetChecklist_($where_arr);   
		 } 
		 
		$data2 = array("main_page"=>"checking",
					   "check_list"=>$check_list,      
					   "check"=>$check
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Checking Details", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('checking/12bet_checking_details_tpl', $data); 
		 
	}
	 
	 
	
	public function exportCheck12Bet($actual=0)
	 {	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = "";  
	 	   
		if(trim($data[s_category]))
		 {
			$search_string2 .= " AND (a.Category=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 }  
		
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (a.Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);     
		 }
		  
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (a.DateCheckedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";   
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		 
		  
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countRecords_($search_string, "csa_12beturl_check AS a", "a.CheckID")->TotalCount;  
		
		$per_page = 20; 
 
		$page = ($data['s_page'])? $data['s_page'] : 0; 
		 
		$checking_data = $this->checking->getCheck12Bet_($search_string); 
		     
					   
		$excel_data = array("DateChecked"=>"Date Checked", 
							"Abbreviation"=>"Currency", 
							"CategoryName"=>"Category", 
							"Checked"=>"Checked", 
							"UnChecked"=>"UnChecked", 
							"Remarks"=>"Remarks",
							"UpdatedByNickname"=>"Checked By"
						);
		
		$force_str = array();
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "12bet_checking".'-'.date("Ymdhis").".xls"; 
		$title = "CAL 12Bet Checking";
		
		//load our new PHPExcel library
		$this->load->library('excel'); 
		 
		//activate worksheet number 1
		$activeSheet = $this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$activeSheet->setTitle($title);
		  
		$headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => '8DB4E2'),
										'font'=> array('bold'=>true)
									   ), 
							 'alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        ), 
							 'borders' => array('outline' => array(
												'style' => PHPExcel_Style_Border::BORDER_THIN,
												'color' => array('argb' => '000000'),
										 )), 
							
							); 
								  
		$checkingtyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'f4ec12'),
										'font'=> array('bold'=>true)));  
										   
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
		
		$redStyle = array('font'  => array(
											//'bold'  => true,
											'color' => array('rgb' => 'B94A48')
											//'size'  => 15,
											//'name'  => 'Verdana'
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
		$category_code = "";  
		$count_result = 0;   
	 
		
		foreach($checking_data as $row=>$check){   
			$x = 'A'; 
			//set format
			$check->DateChecked = date("F d, Y H:i:s", strtotime($check->DateChecked));  
			//$check->Checked = str_replace(', ', '\n', $check->Checked);  
			//$check->UnChecked = str_replace(', ', '\n', $check->UnChecked);  
			 
			   
			foreach($excel_data as $index=>$field){ 
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$ctr,trim($check->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($check->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				//$activeSheet->setCellValueExplicit($x.$ctr,trim($check->$index), PHPExcel_Cell_DataType::TYPE_STRING);
				$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle);  
				$x++;   
			}
			$ctr++;
			 
		}//end foreach 
		 
		   
		//count checking	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Record(s)"); 
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($checkingtyle); 
		$activeSheet->setCellValue('B'.($ctr+2), count($checking_data));
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($checkingtyle);  
	
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
	 
	public function popupCheck12bet()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		
		$data2 = array("main_page"=>"checking",  
					   "currencies"=>$this->common->getCurrency_(),
					   "checking_categories"=>$this->checking->getCheckingCategory(array("a.Status"=>'1'))
					   //"promotions"=>$promotions  
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Check 12Bet ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('checking/12bet_checking_popup_tpl',$data); 
		 
	}  
	 
	public function getCheckList()
	{ 
		$data = $this->input->post();
		$where_arr = array("a.Category ="=>$this->input->post('check_category'), "a.Currency ="=>$this->input->post('check_currency'), "a.Status ="=>'1');   
		$result = $this->checking->get12BetChecklist_($where_arr);   
		
		$return = ($result > 0)?array("success"=>1, "checklist"=>$result):array("success"=>0, "checklist"=>"", "message"=>"No checklist found");
		echo  json_encode($return); 
	} 
	
	
	public function manage12betChecking()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s"); 
		$current_datecontrol = date("Y-m-d H:00:00"); 
		$error =  "";  
		  
		
		if($data[check_currency] == "")
		 {
			 $error .= "Select currency!<br> ";
		 }
		
		if($data[check_category] == "")
		 {
			 $error .= "Select category!<br> ";
		 } 
		  
		$check_list = implode(',', $data[check_item]);   
		 
		if($error)
		 {
			$return = array("success"=>0, "message"=>$error); 
		 }
		else
		 {    
			$post_data = array(   
					'Currency'=>$data['check_currency'],
					'Category'=>$data['check_category'],
					'Urls'=>$check_list,   
					'Remarks'=>$data['check_remarks'],    
					'CheckedBy'=>$this->session->userdata("mb_no"),
					'DateControl'=>$current_datecontrol,
					'DateChecked'=>$current_date, 
					'DateCheckedInt'=>strtotime($current_date) 
				);    			  
			
			$last_id = $this->checking->manageChecking_("csa_12beturl_check", $post_data, 'add', '', '');   
			 
			if($last_id > 0)
			 {
				$return = array("success"=>1, "message"=>"Item checked successfully. <br> ","is_change"=>1); 
			 }
			else
			 {
				 $return = array("success"=>0, "message"=>"Error checking list! <br> ", "is_change"=>0); 
			 }
			 
		 }
		
		
		echo json_encode($return);
		 
		 
	}
	
	
	public function getCrmcheckingOld($actual=0)
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
	 	 
		$outcome_data = $this->checking->getCrmchecking_($search_string, $search_string2); 
		 
	   
	          
		//separate results
		//$results_data = $this->checking->getCountCallResults_($search_string2);  
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
			$return = array("outcomes"=>$this->generateHtmlCheckedData($outcome_data, $results), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows 
					   ); 
					 
			 echo json_encode($return); 
		 }
		
	}
	 
	
	public function checkMarketApps()
	{    
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"checking",      	 
					   //"agents"=>$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "a.mb_usertype"=>$this->common->ids['crm_id'])),  
					   //"outcomes"=>$this->checking->getCallOutcomeList_(array("a.outcome_status ="=>1, "b.result_status ="=>1)),  
					   "currencies"=>$this->common->getCurrency_(),
					   "checking_categories"=>$this->checking->getCheckingCategory(array("a.Status"=>'1'))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Market Apps Checking ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('checking/market_apps_checking_tpl');
		$this->load->view('footer');   
	}  
	
	public function popupCheckMarketApps()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_check()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		
		$data2 = array("main_page"=>"checking",  
					   "currencies"=>$this->common->getCurrency_(),
					   //"checking_categories"=>$this->checking->getCheckingCategory(array("a.Status"=>'1'))
					   //"promotions"=>$promotions  
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Check 12Bet ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('checking/market_apps_checking_popup_tpl',$data); 
		 
	} 	
	
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */