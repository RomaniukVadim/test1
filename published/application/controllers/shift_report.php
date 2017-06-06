<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Shift_Report extends MY_Controller {

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
		
		$this->reporters = '1,2';
		$this->shift_report_status = array("pending"=>'0',  
										   "solved"=>'1'
										  ); 
	 
	}
 	
	public function index()
	{    
		$this->shiftReports();    
	} 
	 
	public function shiftReports()
	{    
		if(!admin_access() && !can_post_shift_report() && !shift_report()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"checking",      	 
					   //"agents"=>$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "a.mb_usertype"=>$this->common->ids['crm_id'])),  
					   //"outcomes"=>$this->checking->getCallOutcomeList_(array("a.outcome_status ="=>1, "b.result_status ="=>1)),  
					   //"currencies"=>$this->common->getCurrency_(),
					   //"checking_categories"=>$this->checking->getCheckingCategory(array("a.Status"=>'1')), 
					   "shifts"=>$this->common->getShifts_(array("a.Status"=>'1')), 
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>1, "FIND_IN_SET(GroupID, '{$this->reporters}') !="=>0)), 
					   "currencies"=>$this->common->getCurrency_(),
					   "shift_status"=>$this->shift_report_status
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Checking ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('checking/shift_reports_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getShiftReports()
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_post_shift_report() && !shift_report() ) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = "";  
	 	$shift = trim($this->uri->segment(3)); 
		
		$where_arr = array(); 
		   
		if(trim($data[hidden_autype]))
		 {  
		 	if((trim($data[hidden_autype]) == $this->common->ids['csa_id']) )
			 {
				$where_arr['c.mb_usertype ='] = trim($data[hidden_autype]); 
			 }
			else
			 {
				 //Currently only CSA and CSS can post report. CRM only can view CSA post and CRM Sup can only view CSA and CSS post   
				$where_arr['c.mb_usertype <>'] = trim($this->common->ids['csa_id']);  
				$where_arr['c.mb_usertype !='] = trim($this->common->ids['crm_id']);  
			 }
			  
		 }  
		else
		 {
			if(!admin_access() && !shift_report_all())
			 {
				 //only can view CSA post 
				 $where_arr['c.mb_usertype ='] = trim($this->common->ids['csa_id']); //$this->session->userdata('mb_usertype'); 
			 } 
		 }
		
		if(trim($data[s_currency]))
		 {
			$where_arr['a.Currency ='] = trim($data[s_currency]);  
		 }
		else
		 {    
			$where_arr["FIND_IN_SET(a.Currency, '{$this->session->userdata(mb_currencies)}') !="] = 0; 
		 }
		
		if(trim($data[s_status]) != '')
		 {
			$where_arr['a.Status ='] = trim($data[s_status]);  
		 }
		   
		if(trim($shift))
		 {     
			$where_arr['a.Shift ='] = trim($shift);
		 }
		 
		if(trim($data[report_currency]))
		 {     
			$where_arr['a.Shift ='] = trim($shift);
		 }
		   
		if($s_fromdate && $s_todate)
		 {  
		 	$where_arr["a.ReportDateInt BETWEEN {$s_fromdate} AND {$s_todate} !="] = 0;  
		 } 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countRecords_($search_string, "csa_12beturl_check AS a", "a.ReportID")->TotalCount; 
	 	 
		 
		$report_data = $this->checking->getShiftReport_($where_arr, $paging=array("limit"=>$per_page, "page"=>$page)); 
		   
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
		 	 $return = array("reportlist"=>$report_data,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
		 
			$return = array("reportlist"=>$this->generateHtmlReportData($report_data),
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>count($report_data), 
							"total_arr"=>$total_arr
					   ); 
			   
			 echo json_encode($return); 
		 }
		
	}  
	
	 
	public function generateHtmlReportData($results)
	{
		$return = "";    
		$this->load->helper('text');
		
		if(count($results))
		 { 
		 	$ctr = 0;    	    
			foreach($results as $row=>$result){  
				$status_class = ($result->Status == '1')?"green":"act-danger";
			  	//$report =  htmlentities(character_limiter($result->Report, 80));
				$report = mb_substr( strip_tags($result->Report) , 0,80 ,'UTF-8' );
				//$status_link = (can_post_shift_report())?"";
				$return .= "
							<tr class=\"check_row check_category_{$result->AddedUserType}\" id=\"Report{$result->ReportID}\" >   
								<td class=\"left\" ><span class=\"ellipsis\">{$report}</span></td> 
								<td class=\"center\" width=\"16%\" >".date("Y-m-d H:i:s", strtotime($result->DateUpdated))."</td>  
								<td class=\"center\" width=\"12%\" >{$result->UpdatedByNickname}</td> 
								
								<td class=\"center\" width=\"13%\" style=\"position: relative\" >
								 <a href=\"#Checking12BetModal\" data-toggle=\"modal\" title=\"view report\" alt=\"show remarks\" class=\"report_details tip {$status_class}\" report-id=\"{$result->ReportID}\" shift-id=\"{$result->Shift}\"  >{$result->StatusName}</a>
								</td>  
							";	  
								/*<td class=\"center\" >
								<a href=\"#Checking12BetModal\" data-toggle=\"modal\" title=\"show details\" alt=\"show remarks\" class=\"show_remarks tip\" check-id=\"{$result->ReportID}\"  ><i class=\"icon16 i-file gap-left0 gap-right10\" ></i></a>
							"; 
				//if(trim($result->Remarks))$return .= "<a href=\"#UserModal\" data-toggle=\"modal\"  title=\"show remarks\" alt=\"show remarks\" class=\"show_remarks tip\" check-id=\"{$result->ReportID}\"  ><i class=\"icon16 i-bubble-13 gap-left0 gap-right10\" ></i></a>";
							
				$return .= " 
								</td>  
							";*/
					 
				$return .= " 
						</tr> ";  
			   
				$ctr++; 
			}//end foreach 
			
			 
						 
								 
		 }
		else
		 { 
			 $return = "
			 			<tr class=\"check_row\"  > 
							<td class=\"center\" colspan=\"5\" >No report found!</td>
						</tr>
			 			";
		 }
		
		return array($return, $return2); 
	}
	
	
	public function popupShiftReport()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_post_shift_report() && !shift_report() ) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$default_shift = trim($this->uri->segment(3));
		$report_id = trim($this->uri->segment(4));
		if($report_id)
		 {
			 $report = $this->checking->getShiftReportById_(array("ReportID ="=>$report_id));  
			 $default_shift = $report->Shift;  
		 }
		
		$data2 = array("main_page"=>"checking",  
					   "shifts"=>$this->common->getShifts_(array("Status"=>'1')),
					   "default_shift"=>$default_shift,  
					   "report_status"=>$this->shift_report_status,
					   "report"=>$report, 
					   "currencies"=>$this->common->getCurrency_()
					   //"checking_categories"=>$this->checking->getCheckingCategory(array("a.Status"=>'1'))
					   //"promotions"=>$promotions  
					  );
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Shift Report ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('checking/shift_report_popup_tpl',$data); 
		 
	} 
	
	public function manageShiftReport()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !can_post_shift_report() ) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$data = $this->input->post(); 
		$current_date = date("Y-m-d H:i:s"); 
		$current_datecontrol = date("Y-m-d H:00:00"); 
		$error =  "";  
		  
		
		if($data[report_date] == "")
		 {
			 $error .= "Select date!<br> ";
		 }
		 
		if($data[report_currency] == "")
		 {
			 $error .= "Select currency!<br> ";
		 }
		 
		if($data[report_shift] == "")
		 {
			 $error .= "Select shift!<br> ";
		 }
		
		if($data[report_info] == "")
		 {
			 $error .= "Enter remarks!<br> ";
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
					'Shift'=>trim($data['report_shift']),  
					'Currency'=>trim($data['report_currency']),   
					'ReportDate'=>trim($data['report_date']),  
					'ReportDateInt'=>strtotime(trim($data['report_date'])),  
					'Report'=>trim($data['report_info']),     
					'AddedBy'=>$this->session->userdata('mb_no'),
					'DateAdded'=>$current_date,   
					'DateAddedInt'=>strtotime($current_date),  
					'UpdatedBy'=>$this->session->userdata('mb_no'), 
					'DateUpdated'=>$current_date,
					'DateUpdatedInt'=>strtotime($current_date),  
					'Status'=>'0' 
				 );   
				
				if(trim($data['report_status'])) $post_data['Status'] = trim($data['report_status']);
				 
				$last_id = $this->checking->manageChecking_("csa_shift_report", $post_data, 'add', '', '');   
				
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Shift report added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding shift report!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Shift'=>trim($data['report_shift']), 
					'Currency'=>trim($data['report_currency']),   
					'ReportDate'=>trim($data['report_date']),  
					'ReportDateInt'=>strtotime(trim($data['report_date'])), 
					'Report'=>trim($data['report_info']),      
					'UpdatedBy'=>$this->session->userdata('mb_no'), 
					'DateUpdated'=>$current_date,
					'DateUpdatedInt'=>strtotime($current_date) 
				 );  
				   
				 $changes = ""; 
				 
				 $conditions_array = array('a.ReportID ='=>$data[hidden_reportid]);  
				 $old = $this->checking->getShiftReportById_($conditions_array);
				  
				 if(trim($data['report_status']) != '' )
				  {
					  $post_data['Status'] = trim($data['report_status']); 
					  $changes .= ($data['report_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":""; 
				  }
				  
				 $changes .= ($data['report_shift'] != $old->Shift)?"Shift changed to ".$data['hidden_ashift']." from ".$old->ShiftName."|||":"";   
				 $changes .= ($data['report_date'] != $old->ReportDate)?"Report Date changed to ".$data['report_date']." from ".$old->ReportDate."|||":"";   
				 $changes .= ($data['report_currency'] != $old->Currency)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->CurrencyName."|||":"";  
				 $changes .= ($data['report_info'] != $old->Report)?"Report changed to ".$data['report_info']." from ".$old->Report."|||":"";   
				 //$changes .= ($data['report_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->checking->manageChecking_("csa_shift_report", $post_data, $action, "ReportID", $this->input->post("hidden_reportid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Shift report updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating shift report!");
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
	
	
	
	
	//POPUP CHANGE STATUS
	public function view12betCheckingDetails()
	{    
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		 
		if(!admin_access() && !can_post_shift_report() )
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
		
		$conditions_array = array('a.ReportID =' => $check_id); 
		$check = $this->checking->getCheck12BetById_($conditions_array);  
		 
		if(count($check) <= 0)
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false;  
		 }
		else
		 {
			//$view_only = (allowEditMain() || ($activity->GroupAssignee==$this->session->userdata('mb_usertype')) || ($activity->AddedBy==$this->session->userdata('mb_no')) )?0:1;
		 } 
		 
		$data2 = array("main_page"=>"checking",      
					   "check"=>$check
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - 12Bet Checking Details", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('checking/12bet_checking_details_tpl', $data); 
		 
	}
	 
	 
	
	public function exportShiftReport($actual=0)
	 {	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !can_post_shift_report() )
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string = "";  
	 	   
		$search_string = "";  
	 	$shift = trim($this->uri->segment(3)); 
		
		$where_arr = array(); 
		   
		if(trim($data[hidden_autype]))
		 {  
		 	if(trim($data[hidden_autype]) == $this->common->ids['csa_id'])
			 {
				$where_arr['c.mb_usertype ='] = trim($data[hidden_autype]); 
			 }
			else
			 {
				$where_arr['c.mb_usertype <>'] = trim($this->common->ids['csa_id']); 
			 }
			 
			
		 }  
		else
		 {
			if(!admin_access() && !shift_report_all())
			 {
				 $where_arr['c.mb_usertype ='] = $this->session->userdata('mb_usertype'); 
			 }
			
		 }
		
		if(trim($data[s_currency]))
		 {
			$where_arr['a.Currency ='] = trim($data[s_currency]);  
		 }
		else
		 {    
			$where_arr["FIND_IN_SET(a.Currency, '{$this->session->userdata(mb_currencies)}') !="] = 0; 
		 }
		
		if(trim($data[s_status]) != '')
		 {
			$where_arr['a.Status ='] = trim($data[s_status]);  
		 }
		   
		if(trim($shift))
		 {     
			$where_arr['a.Shift ='] = trim($shift);
		 }
		 
		 
		if($s_fromdate && $s_todate)
		 {  
		 	$where_arr["a.ReportDateInt BETWEEN {$s_fromdate} AND {$s_todate} !="] = 0;  
		 }  
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countRecords_($search_string, "csa_12beturl_check AS a", "a.ReportID")->TotalCount;   
		 
		$report_data = $this->checking->getShiftReport_($where_arr, $paging=array("limit"=>$per_page, "page"=>$page)); 
		$shifts = $this->common->getShifts_(array("a.Status"=>'1'));
		      
		$excel_data = array("Report"=>"Report", 
							"CurrencyName"=>"Currency",  
							"AddedByNickname"=>"Added By", 
							"ReportDate"=>"Report Date", 
							"UpdatedByNickname"=>"Updated By", 
							"DateUpdated"=>"Date Updated", 
							"StatusName"=>"Status"
						);
		
		$force_str = array();
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "shift_report".'-'.date("Ymdhis").".xls"; 
		
		$title_1 = "Morning Shift";
		$title_2 = "Afternoon Shift";
		$title_3 = "Night Shift";
		
		//load our new PHPExcel library
		$this->load->library('excel'); 
		  
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
		 
		
		//CREATE HEADERS AND SHEETS 
		$counter = array(); 
		$i = 0;  
		foreach($shifts as $row=>$shift){ 
			$y =  'A';
			$counter[$shift->ShiftID] = array("index"=>$i, "ctr"=>1);    
			
			$this->excel->createSheet($i);  
			$activeSheet = $this->excel->setActiveSheetIndex($i); 
			$activeSheet->setTitle($shift->ShiftName." Shift");
			foreach($excel_data as $row=>$val){ 
				$row_cel = $y.$counter[$shift->ShiftID]['ctr'];   
				$activeSheet->setCellValue($row_cel,$val.' ');
				$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
				$y++; 
			}//end foreach   
			$counter[$shift->ShiftID]['ctr'] = $counter[$shift->ShiftID]['ctr'] + 1;  
			$i++;
			
		}//end foreach
		//CREATE HEADERS AND SHEETS 
		 
		foreach($report_data as $row=>$report){   
			$x = 'A'; 
			$activeSheet = $this->excel->setActiveSheetIndex($counter[$report->Shift]['index']);
			
			//set format
			$report->DateAdded = date("F d, Y H:i:s", strtotime($report->DateAdded));  
			$report->DateUpdated = date("F d, Y H:i:s", strtotime($report->DateUpdated));  
			    
			foreach($excel_data as $index=>$field){ 
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$counter[$report->Shift]['ctr'],trim($report->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$counter[$report->Shift]['ctr'],trim($report->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				//$activeSheet->setCellValueExplicit($x.$ctr,trim($check->$index), PHPExcel_Cell_DataType::TYPE_STRING);
				$activeSheet->getStyle($x.$counter[$report->Shift]['ctr'])->applyFromArray($normalStyle);  
				$x++;   
			} 
			$counter[$report->Shift]['ctr']++; 
		}//end foreach  
		  
		//count checking	 
		//$activeSheet->setCellValue('A'.($ctr+2),"Total Record(s)"); 
		//$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($checkingtyle); 
		//$activeSheet->setCellValue('B'.($ctr+2), count($report_data));
		//$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($checkingtyle);  
	
		//set auto width 
		foreach($counter as $row=>$count) {  
			$x='A';
			$activeSheet = $this->excel->setActiveSheetIndex($count['index']);  
			for($i=0; $i<(count($excel_data)); $i++){
				$activeSheet->getColumnDimension($x)->setAutoSize(true);      
				$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
				$x++;	
			} 
		}
		
		/*for($i=0; $i<(count($excel_data)); $i++){
			$activeSheet->getColumnDimension($x)->setAutoSize(true);      
			$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
			$x++;	
		} */
		 
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
		
		$return = ($result > 0)?array("success"=>1, "reportlist"=>$result):array("success"=>0, "reportlist"=>"", "message"=>"No reportlist found");
		echo  json_encode($return); 
	} 
	 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */