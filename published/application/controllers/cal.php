<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cal extends MY_Controller {

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
		$this->staff_reports = "1,2"; 
		$this->csdsup_allow_types = (!admin_access())?array(1,10):array(); //if not set get all types
		
		$this->report_status = array("new_status"=>array("StatusID"=>$this->common->ids['new_status'], "Label"=>"New", "CountName"=>"NewCount"), 
									 "pending_status"=>array("StatusID"=>$this->common->ids['pending_status'], "Label"=>"Pending", "CountName"=>"PendingCount"), 
									 "inprogress_status"=>array("StatusID"=>$this->common->ids['inprogress_status'], "Label"=>"In Progress", "CountName"=>"InProgressCount"), 
									 "close_status"=>array("StatusID"=>$this->common->ids['close_status'], "Label"=>"Closed", "CountName"=>"CloseCount"), 
									 "deposited_status"=>array("StatusID"=>$this->common->ids['deposited_status'], "Label"=>"Deposited", "CountName"=>"DepositedCount"), 
									 "nondeposited_status"=>array("StatusID"=>$this->common->ids['nondeposited_status'], "Label"=>"Non Deposited", "CountName"=>"NonDepositedCount") 
									); 
									
		$this->report_customs = array("complaint_cus"=>array("IsComplaint"=>1, "Label"=>"Complaint", "CountName"=>"ComplaintCount"),
									 "complain_users_cus"=>array("Username"=>62, "Label"=>"Complaint Username", "CountName"=>"ComplaintUsername") 
									); 
		
		$this->activity_types = array("deposit_withdrawal"=>array("Value"=>"deposit_withdrawal", "Label"=>"Banks", "Controller"=>"banks"),
									  "promotion"=>array("Value"=>"promotion", "Label"=>"Promotions", "Controller"=>"promotions"), 
									  "casino_issues"=>array("Value"=>"casino_issues", "Label"=>"Casino", "Controller"=>"casino"),  
									  "account_issues"=>array("Value"=>"account_issues", "Label"=>"Accounts", "Controller"=>"accounts"),  
									  "suggestions_complaints"=>array("Value"=>"suggestions_complaints", "Label"=>"Suggestions", "Controller"=>"suggestions"), 
									  "website_mobile"=>array("Value"=>"website_mobile", "Label"=>"Access", "Controller"=>"access")
									); 
									
		$this->report_sources = $this->common->getSourceAll_(array("Status"=>1));
	 
	}
 	
	public function index()
	{    
		$this->calReports();    
	}
	 
	public function calReports()
	{    
		if(!admin_access() && !csd_supervisor_access() && !can_cal_system()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$user_currencies = explode(',', $this->session->userdata('mb_currencies')); 
		$currency_str = "";
		$agent_condition = array("a.mb_status ="=>1, "a.mb_name"=>"csa");
		if(!admin_access())
		 { 
			if(csd_supervisor_access())
			  {
				  foreach($user_currencies as $i)
				    {
						$currency_str .= " FIND_IN_SET({$i}, a.mb_currencies) OR ";	 
					}
				  
				   $currency_str = trim(trim($currency_str), "OR");
				   $currency_str = ($currency_str)?"({$currency_str})":"";  
				   
				   if($currency_str)
				    {
						//$search_string .= " AND {$currency_str} "; 
						$agent_condition["{$currency_str} !="] = 0; 
					}
				   else
				    {
						//$search_string .= " AND a.mb_no='{$this->session->userdata('mb_no')}' ";
						$agent_condition["a.mb_no"] = $this->session->userdata("mb_no");    
					}
						
				  //$agent_condition["FIND_IN_SET(a.mb_usergroup, '".implode(',', $this->csdsup_allow_types)."') !="] = 0;
				  //$agent_condition["a.mb_usertype IN(".implode(',', $this->csdsup_allow_types).")"] = 0;  
			  }
			 else
			  {
				 $agent_condition["a.mb_no"] = $this->session->userdata("mb_no");   
			  }
		 }
		else
		 {
			 
		 }
		 
		$agents = $this->common->getUserAll_($agent_condition); 
		 
		 
		 
		//$agents = (admin_access())?$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa")):$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "FIND_IN_SET(a.mb_usergroup, '".implode(',', $this->csdsup_allow_types)."') !="=>0)); 	
		
						
		$data2 = array("main_page"=>"reports",      	
					   "status_list"=>$this->status_list, 
					   "agents"=>$agents, 
					   //"shifts"=>$this->common->getShifts_(array("a.Status ="=>1)), 
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>1)), 
					   "currencies"=>$this->common->getCurrency_(), 
					   "reports_status"=>$this->report_status, 
					   "sources"=>$this->common->getSourceAll_(array("Status"=>1)),  
					   "customs"=>$this->report_customs
					  );
		 
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Reports ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('reports/cal_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getCalStatusReports($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !csd_supervisor_access() && !can_cal_system()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string .= " AND a.mb_status='1' AND a.mb_name='csa' "; 
		$search_string2 = "";   
		$search_string3 = "";   
		$search_arr = array(); 
		 
		$user_currencies = explode(',', $this->session->userdata('mb_currencies')); 
		$currency_str = "";
		if(!admin_access())
		 {
			 if(csd_supervisor_access())
			   {
				   
				   foreach($user_currencies as $i)
				    {
						$currency_str .= " FIND_IN_SET({$i}, a.mb_currencies) OR ";	 
					}
				   $currency_str = trim(trim($currency_str), "OR");
				   $currency_str = ($currency_str)?"({$currency_str})":"";  
				   
				   if($currency_str)
				    {
						$search_string .= " AND {$currency_str} "; 
					}
				   else
				    {
						$search_string .= " AND a.mb_no='{$this->session->userdata('mb_no')}' "; 
					}
				   //$search_string2 .= " AND UpdatedBy='{$this->session->userdata('mb_no')}' "; 
				   //$search_string3 .= " AND (AddedBy='{$this->session->userdata('mb_no')}') ";	  
			   }
			 else
			   {
				   $search_string .= " AND a.mb_no='{$this->session->userdata('mb_no')}' "; 
				   $search_string2 .= " AND UpdatedBy='{$this->session->userdata('mb_no')}' "; 
				   $search_string3 .= " AND (AddedBy='{$this->session->userdata('mb_no')}') ";
			   }
			 
		 }
		 
		if(trim($data[s_usertype]))
		 {
			//$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(trim($data[s_usertype]))."') ";  
			 
			$search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(trim($data[s_usertype])).") "; 
			
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		else
		 {
			if(count($this->csdsup_allow_types) > 0)
			 {
				 //$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(implode(',', $this->csdsup_allow_types))."') ";
				 $search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(implode(',', $this->csdsup_allow_types)).") ";  
				 //$search_url .= "&s_agent=".trim($data[s_agent]);
			 }
		 }
		 
		if(trim($data[s_agent]))
		 {
			$search_string .= " AND (a.mb_no=".$this->common->escapeString_(trim($data[s_agent])).") ";  
			$search_string3 .= " AND (AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") ";
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 }  
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND FIND_IN_SET('".$this->common->escapeString_(trim($data[s_currency]))."', a.mb_currencies) ";  
			$search_string3 .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") ";
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string2 .= " AND (DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";  
			$search_string3 .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  "; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		  
		$per_page = 20;  
		 
		$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countRecords_($search_string, "g4_member AS a", "a.mb_no")->TotalCount; 
	 	
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		$search_string3 = trim(trim($search_string3), "AND");
		 
		$status_rec = $this->reports->getCalStatusReports_($search_string, $search_string2, $this->report_status, $paging=array("limit"=>$per_page, "page"=>$page)); 
		$source_rec = $this->reports->getCalSourceReports_($search_string, $search_string3, $this->report_sources, $this->report_customs, $paging=array("limit"=>$per_page, "page"=>$page)); 
		 
		$total_rows = $status_rec[total_rows]; 
		
		$status_data  = $status_rec[result];
		$source_data  = $source_rec[result];
	  
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"users":"user";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("user"=>$status_data,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows, 
						 
					   );
		 	 echo  json_encode($return);  
		 }
		else
		 {
			$return = array(//"users"=>$this->generateHtmlUserData($status_data, $source_data), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows, 
							"status_data"=>$status_data,
							"source_data"=>$source_data, 
							"report_status"=>$this->report_status,
							"report_sources"=>$this-> report_sources, 
							"report_customs"=>$this->report_customs
					   ); 
					//$return = $source_data;
			 echo json_encode($return); 
		 }
		
	} 
	
	
	public function generateHtmlUserData($status_data, $source_data)
	{
		$return = "";   
		//$mdata =  (object) array_merge((array) $status_data, (array) $source_data);//array_merge($status_data, $source_data); 
		
		if(count($status_data))
		 { 
		 	$ctr = 0; 		 
			foreach($status_data as $row=>$user){   
				$status = ($user->mb_status=='0' || $user->mb_status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active"; 
				$return .= "
						<tr class=\"user_row\" id=\"User{$user->mb_no}\" > 
							<!--<td class=\"center\" >".str_pad($user->mb_no,4,'0', STR_PAD_LEFT)."</td>--> 
							<td class=\"center\" >{$user->mb_nick}</td>  
						";
				
				foreach($this->report_status as $row=>$status){ 
					$return .= "<td class=\"center status-col report-col\" >{$user->{$status[CountName]}}</td> ";
				}
				
				foreach($this->report_sources as $rowx=>$source){  
					$data_s = $source_data[$ctr]->{'Source_'.$source->SourceID}; //$source_data[$ctr]['Source_4'];//['Source_{$source->SourceID}']; 
					$return .= "<td class=\"center source-col report-col\" >{$data_s}</td> "; 
				}
				
				foreach($this->report_customs as $row=>$custom){  
					$data_s = $source_data[$ctr]->$custom[CountName]; 
					$data_s = str_replace(',', ', ', $data_s);
					$return .= "<td class=\"center custom-col report-col act-danger\" >{$data_s}</td> "; 
				}
				  
				$return .= " 
						</tr> ";
				$ctr++; 
			}//end foreach
								 
		 }
		else
		 {
			 $colspan = count($this->report_status) + count($this->report_source); 
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"{$colspan}\" >No user found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
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
	 
	
	public function exportCalReport($actual=0)
	 {	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !csd_supervisor_access() && !can_cal_system()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string .= " AND a.mb_status='1' AND a.mb_name='csa' "; 
		$search_string2 = "";   
		$search_string3 = "";   
		$search_arr = array(); 
		 
		$user_currencies = explode(',', $this->session->userdata('mb_currencies')); 
		$currency_str = "";
		if(!admin_access())
		 {
			 if(csd_supervisor_access())
			   {
				   
				   foreach($user_currencies as $i)
				    {
						$currency_str .= " FIND_IN_SET({$i}, a.mb_currencies) OR ";	 
					}
				   $currency_str = trim(trim($currency_str), "OR");
				   $currency_str = ($currency_str)?"({$currency_str})":"";  
				   
				   if($currency_str)
				    {
						$search_string .= " AND {$currency_str} "; 
					}
				   else
				    {
						$search_string .= " AND a.mb_no='{$this->session->userdata('mb_no')}' "; 
					}
				   //$search_string2 .= " AND UpdatedBy='{$this->session->userdata('mb_no')}' "; 
				   //$search_string3 .= " AND (AddedBy='{$this->session->userdata('mb_no')}') ";	  
			   }
			 else
			   {
				   $search_string .= " AND a.mb_no='{$this->session->userdata('mb_no')}' "; 
				   $search_string2 .= " AND UpdatedBy='{$this->session->userdata('mb_no')}' "; 
				   $search_string3 .= " AND (AddedBy='{$this->session->userdata('mb_no')}') ";
			   }
			 
		 }
		 
		if(trim($data[s_usertype]))
		 {
			//$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(trim($data[s_usertype]))."') ";  
			 
			$search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(trim($data[s_usertype])).") "; 
			
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		else
		 {
			if(count($this->csdsup_allow_types) > 0)
			 {
				 //$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(implode(',', $this->csdsup_allow_types))."') ";
				 $search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(implode(',', $this->csdsup_allow_types)).") ";  
				 //$search_url .= "&s_agent=".trim($data[s_agent]);
			 }
		 }
		 
		if(trim($data[s_agent]))
		 {
			$search_string .= " AND (a.mb_no=".$this->common->escapeString_(trim($data[s_agent])).") ";  
			$search_string3 .= " AND (AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") ";
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 }  
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND FIND_IN_SET('".$this->common->escapeString_(trim($data[s_currency]))."', a.mb_currencies) ";  
			$search_string3 .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") ";
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string2 .= " AND (DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";  
			$search_string3 .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  "; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		   
	 	
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		$search_string3 = trim(trim($search_string3), "AND");
		 
		$status_rec = $this->reports->getCalStatusReports_($search_string, $search_string2, $this->report_status); 
		$source_rec = $this->reports->getCalSourceReports_($search_string, $search_string3, $this->report_sources, $this->report_customs); 
		 
		$total_rows = $status_rec[total_rows]; 
		
		$status_data  = $status_rec[result];
		$source_data  = $source_rec[result];
		     
					   
		$excel_data = array("mb_nick"=>"Agent"
						);
		
		$force_str = array("ComplaintUsername");
		
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "cal_reports".'-'.date("Ymdhis").".xls"; 
		$title = "CAL Reports";
		
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
								  
		$reportStyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
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
	 	
		foreach($this->report_status as $row=>$headstat){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,trim($headstat[Label]).' ');
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
			$y++; 
		}//end foreach
		
		foreach($this->report_sources as $row=>$headsource){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,trim($headsource->Source).' ');
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
			$y++; 
		}//end foreach 
		
		foreach($this->report_customs as $row=>$headcustom){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,trim($headcustom[Label]).' ');
			$activeSheet->getStyle($row_cel)->applyFromArray($headerComplaint);   
			$y++; 
		}//end foreach
	 	
		//END PUT HEADER 
				
		$ctr = $start + 1;
		$category_code = "";  
		$count_result = 0;   
	   
		$ctr_3 = 0;  
		foreach($status_data as $row=>$user){   
			$x = 'A';  
			 
			$activeSheet->setCellValue($x.$ctr,trim($user->mb_nick), PHPExcel_Cell_DataType::TYPE_STRING);
			$x++;
			foreach($this->report_status as $row=>$status){  
				$activeSheet->setCellValue($x.$ctr,trim($user->{$status[CountName]}), PHPExcel_Cell_DataType::TYPE_STRING); 
				$x++;
			}
			
			foreach($this->report_sources as $row=>$source){  
				$data_s = (int)$source_data[$ctr_3]->{'Source_'.$source->SourceID};   
				/*if(in_array($field, $force_str))
				 {  
					$activeSheet->setCellValueExplicit($x.$ctr,trim($data_s)."{Source_$source->SourceID}", PHPExcel_Cell_DataType::TYPE_STRING); 
				 } 
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($data_s)."{Source_$source->SourceID}", PHPExcel_Cell_DataType::TYPE_STRING);  
				 }*/
				$activeSheet->setCellValue($x.$ctr,$data_s, PHPExcel_Cell_DataType::TYPE_STRING);  
				$x++; 
				
			}
			
			foreach($this->report_customs as $row=>$custom){  
				$data_s = $source_data[$ctr_3]->{$custom[CountName]};   
				//$activeSheet->setCellValue($x.$ctr,trim($data_s), PHPExcel_Cell_DataType::TYPE_STRING);
				$data_s = str_replace(',', ', ', $data_s);   
				if(in_array($custom[CountName], $force_str))
				 {  
					$activeSheet->setCellValueExplicit($x.$ctr,trim($data_s), PHPExcel_Cell_DataType::TYPE_STRING); 
				 } 
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($data_s), PHPExcel_Cell_DataType::TYPE_STRING);  
				 }  
				$activeSheet->getStyle($x.$ctr)->applyFromArray($redStyle);    
				$x++; 
				
			}
			
			$ctr_3++;    
			$ctr++; 
		}//end foreach
		 
		   
		//count reports	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Record(s)"); 
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->setCellValue('B'.($ctr+2), count($status_data));
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle);  
	
		//set auto width
		$x='A';
		$col = 0;
 
		for($i=0; $i<(count($excel_data)+count($this->report_status)+count($this->report_sources)+count($this->report_customs)); $i++){
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
	
	
	public function generateParams($url="")
	{    
		$data = $this->input->post(); 
		$params = encode_string(http_build_query($data, '', '&amp;'));  
		$return = array("params"=>$params 
					    );   
		 
		echo json_encode($return); 
	}
	
	 
	public function calReportsStatusDetails()
	{    
		if(!admin_access() && !csd_supervisor_access() && !can_cal_system()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$params = str_replace("amp;", "", decode_string($this->uri->segment(4)));   
		parse_str($params, $sdata); 
		
		
		$user_currencies = explode(',', $this->session->userdata('mb_currencies')); 
		$currency_str = "";
		$agent_condition = array("a.mb_status ="=>1, "a.mb_name"=>"csa");
		if(!admin_access())
		 { 
			if(csd_supervisor_access())
			  {
				  foreach($user_currencies as $i)
				    {
						$currency_str .= " FIND_IN_SET({$i}, a.mb_currencies) OR ";	 
					}
				  
				   $currency_str = trim(trim($currency_str), "OR");
				   $currency_str = ($currency_str)?"({$currency_str})":"";  
				   
				   if($currency_str)
				    {
						//$search_string .= " AND {$currency_str} "; 
						$agent_condition["{$currency_str} !="] = 0; 
					}
				   else
				    {
						//$search_string .= " AND a.mb_no='{$this->session->userdata('mb_no')}' ";
						$agent_condition["a.mb_no"] = $this->session->userdata("mb_no");    
					}
						
				  //$agent_condition["FIND_IN_SET(a.mb_usergroup, '".implode(',', $this->csdsup_allow_types)."') !="] = 0;
				  //$agent_condition["a.mb_usertype IN(".implode(',', $this->csdsup_allow_types).")"] = 0;  
			  }
			 else
			  {
				 $agent_condition["a.mb_no"] = $this->session->userdata("mb_no");   
			  }
		 }
		else
		 {
			 
		 }
		 
		$agents = $this->common->getUserAll_($agent_condition);   
		
		 
		//$agents = (admin_access())?$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa")):$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "FIND_IN_SET(a.mb_usergroup, '".implode(',', $this->csdsup_allow_types)."') !="=>0)); 
						 
			
		$data2 = array("main_page"=>"reports",      	 
					   "status_list"=>$this->common->getStatusList_(), 
					   "agents"=>$agents,   
					   "currencies"=>$this->common->getCurrency_(),  
					   //"sources"=>$this->common->getSourceAll_(array("Status"=>1)),  
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>1)), 
					   "sdata"=>$sdata 
					  );
		 
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Reports Source Details ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('reports/cal_status_details_tpl');
		$this->load->view('footer');   
	}
	 
	
	public function getCalStatusReportsDetails()
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !csd_supervisor_access() && !can_cal_system()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post();  
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string .= ""; 
		$search_string2 = "";   
		$search_string3 = "";   
		$search_arr = array(); 
		 
		/*if(trim($data[s_usertype]))
		 {
			//$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(trim($data[s_usertype]))."') ";  
			 
			$search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(trim($data[s_usertype])).") "; 
			
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		else
		 {
			if(count($this->csdsup_allow_types) > 0)
			 {
				 //$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(implode(',', $this->csdsup_allow_types))."') ";
				 $search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(implode(',', $this->csdsup_allow_types)).") ";  
				 $search_url .= "&s_agent=".trim($data[s_agent]);
			 }
		 }*/
		 
		/*if(trim($data[s_mbno]))
		 {
			$search_string .= " AND (UpdatedBy=".$this->common->escapeString_(trim($data[s_mbno])).") "; 
			$search_url .= "&s_mbno=".trim($data[s_mbno]);   
		 } */ 
		
		/*if(trim($data[s_currency]))
		 {
			$search_string .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } */
		 
		if(trim($data[s_agent]) != "" )
		 {
			$search_string .= " AND (UpdatedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		
 
		if(isset($data[s_status]) )
		 { 
			$search_string .= " AND (Status=".$this->common->escapeString_(trim($data[s_status])).") "; 
			$search_url .= "&s_status=".trim($data[s_status]);   
		 } 
		  
		if(trim($data[s_activity]))
		 {
			$search_string .= " AND (Activity='".$this->common->escapeString_(trim($data[s_activity]))."') "; 
			$search_url .= "&s_activity=".trim($data[s_activity]);   
		 } 
		
		if(trim($data[s_assignee]))
		 {
			$search_string .= " AND (GroupAssignee=".$this->common->escapeString_(trim($data[s_assignee])).") "; 
			$search_url .= "&s_assignee=".trim($data[s_assignee]);   
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";  
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } 
		 
		if(trim($data[s_username]))
		 {
			$search_string2 .= " AND (Username=".$this->common->escapeString_(trim($data[s_username])).") ";  
			$search_url .= "&s_username=".trim($data[s_username]);   
		 } 
		
		if(trim($data[s_important])=='1')
		 { 
			$search_string .= " AND (Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$$data[s_important];    
		 }
		
		if(trim($data[s_iscomplaint])=='1')
		 { 
			$search_string .= " AND (IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);   
		 }
		 
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		   
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		 
		$return = $this->reports->getCalStatusReportsDetails_($search_string, $search_string2, $paging=array("limit"=>$per_page, "page"=>$page)); 
		 
		//$return = $activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index, $order_by); 
		
		$total_rows = $return[total_rows]; 
		$activities = $return[result];
		
		  
		$pagination_options = array("link"=>"",//base_url()."access/activities",   
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"users":"user";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		$return = array(//"users"=>$this->generateHtmlUserData($status_data, $source_data),  
						"activities"=>$activities,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows, 
						"activity_types"=>$this->activity_types
						//"status_data"=>$status_data,
						//"source_data"=>$source_data, 
						//"report_status"=>$this->report_status,
						//"report_sources"=>$this-> report_sources, 
						//"report_customs"=>$this->report_customs
				   ); 
				//$return = $source_data;
		 echo json_encode($return);
		
	} 
	
	
	public function exportStatusReportsDetails($actual=0)
	{	  
		
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !csd_supervisor_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string .= ""; 
		$search_string2 = "";   
		$search_string3 = "";   
		$search_arr = array(); 
		 
		/*if(trim($data[s_usertype]))
		 {
			//$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(trim($data[s_usertype]))."') ";  
			 
			$search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(trim($data[s_usertype])).") "; 
			
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		else
		 {
			if(count($this->csdsup_allow_types) > 0)
			 {
				 //$search_string .= " AND FIND_IN_SET(a.mb_usertype, '".$this->common->escapeString_(implode(',', $this->csdsup_allow_types))."') ";
				 $search_string .= " AND a.mb_usertype IN(".$this->common->escapeString_(implode(',', $this->csdsup_allow_types)).") ";  
				 $search_url .= "&s_agent=".trim($data[s_agent]);
			 }
		 }*/
		 
		/*if(trim($data[s_mbno]))
		 {
			$search_string .= " AND (UpdatedBy=".$this->common->escapeString_(trim($data[s_mbno])).") "; 
			$search_url .= "&s_mbno=".trim($data[s_mbno]);   
		 } */ 
		
		/*if(trim($data[s_currency]))
		 {
			$search_string .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } */
		 
		if(trim($data[s_agent]) != "" )
		 {
			$search_string .= " AND (UpdatedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		
 
		if(isset($data[s_status]) )
		 { 
			$search_string .= " AND (Status=".$this->common->escapeString_(trim($data[s_status])).") "; 
			$search_url .= "&s_status=".trim($data[s_status]);   
		 } 
		  
		if(trim($data[s_activity]))
		 {
			$search_string .= " AND (Activity='".$this->common->escapeString_(trim($data[s_activity]))."') "; 
			$search_url .= "&s_activity=".trim($data[s_activity]);   
		 } 
		
		if(trim($data[s_assignee]))
		 {
			$search_string .= " AND (GroupAssignee=".$this->common->escapeString_(trim($data[s_assignee])).") "; 
			$search_url .= "&s_assignee=".trim($data[s_assignee]);   
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (DateUpdatedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";  
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string2 .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } 
		 
		if(trim($data[s_username]))
		 {
			$search_string2 .= " AND (Username=".$this->common->escapeString_(trim($data[s_username])).") ";  
			$search_url .= "&s_username=".trim($data[s_username]);   
		 } 
		
		if(trim($data[s_important])=='1')
		 { 
			$search_string .= " AND (Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$$data[s_important];    
		 }
		
		if(trim($data[s_iscomplaint])=='1')
		 { 
			$search_string .= " AND (IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);   
		 }
		 
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		   
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		 
		$return = $this->reports->getCalStatusReportsDetails_($search_string, $search_string2, $paging=array("limit"=>$per_page, "page"=>$page)); 
		 
		//$return = $activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index, $order_by);  
		$total_rows = $return[total_rows]; 
		$activities = $return[result];
	  
		//$per_page = 20; 
		//$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
		//$page = ($data['s_page'])? $data['s_page'] : 0;
		//$total_rows = $this->common->countSearchActivities_($search_string, "csa_promotion_activities", "promotion", $index)->CountActivity; 
		//$activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index);
		
		//$return = $this->reports->getCalStatusReportsDetails_($search_string, $view_statuslist, array(), $index, $order_by); 
		//$total_rows = $return[total_rows]; 
		//$activities = $return[result];
		  
	 
		$excel_data = array("CurrencyName"=>"Currency", 
			 				"Activity"=>"Activity",
							"Username"=>"Username",
							"Remarks"=>"Remarks",
							"StatusName"=>"Status",
							"GroupAssignee"=>"Assignee", 
							"DateUpdatedInt"=>"Date Updated", 
							"IsComplaint"=>"Is Complaint",
							"Important"=>"Important" 
							);  
							
		$force_str = array("Username", "Remarks");
		
		//delete_old_files("media/temp/", "*.xls");  
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "status_reports_details".'-'.date("Ymdhis").".xls"; 
		$title = "Status Reports Details";  
		
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
										'font'=> array('bold'=>true)));  
										   
		$normalStyle = array('alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        )
							  );
							  
							  
		$y = 'A';
		$start = 1;
		 
		foreach($excel_data as $row=>$val){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,$val);
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
			$y++; 
		}//end foreach
		
		$ctr = $start + 1;
		$category_code = "";  
		$count_result = 0;   
	 
		$end_cell = '';
		$start_cell = 'A'.$ctr; 
		foreach($activities as $row=>$activity){   
			$x = 'A'; 
			//set format  
			$activity->DateUpdatedInt = date("F d, Y H:i:s D", $activity->DateUpdatedInt);  
			  
		 
			$activity->Important = ($activity->Important==1)?"YES":"NO";
			$activity->IsComplaint = ($activity->IsComplaint==1)?"YES":"NO";  
			$activity->Activity = strtolower($this->activity_types[$activity->Activity][Label]);
			  
			$activity->Remarks = strip_symbols($activity->Remarks); 
			 
			foreach($excel_data as $index=>$field){ 
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				
				//$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle); 
				$end_cell = $x.$ctr; 
				$x++;   
			}
			$ctr++;
			 
		}//end foreach 
		
		$activeSheet->getStyle($start_cell.':'.$end_cell)->applyFromArray($normalStyle); 
		   
		//count reports	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Activities(s)"); 
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->setCellValue('B'.($ctr+2), count($activities));
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle);  
		
		//set auto width
		$x='A';
		$col = 0; 
		foreach($excel_data as $row=>$data){
			$activeSheet->getColumnDimension($x)->setAutoSize(true);     
			$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
			$x++;	
		}  
		 
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//$objWriter->save('php://output'); 
		$filePath = $this->common->temp_file; //"media/temp/"
		$objWriter->save($filePath.$file_name);    
		
		$return = (file_exists($filePath.$file_name))?array("success"=>1, "message"=>"Downloading file.", "download_link"=>encode_string($filePath.$file_name)):array("success"=>0, "message"=>"Error downloading file.", "download_link"=>"");     
		  
		echo json_encode($return); 
		
	}//end export activities 
	
	
	public function calReportsSourceDetails()
	{    
		if(!admin_access() && !csd_supervisor_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$params = str_replace("amp;", "", decode_string($this->uri->segment(4)));   
		parse_str($params, $sdata); 
		 
		$agents = (admin_access())?$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa")):$this->common->getUserAll_(array("a.mb_status ="=>1, "a.mb_name"=>"csa", "FIND_IN_SET(a.mb_usergroup, '".implode(',', $this->csdsup_allow_types)."') !="=>0)); 				 
			
		$data2 = array("main_page"=>"reports",      	 
					   "status_list"=>$this->common->getStatusList_(), 
					   "agents"=>$agents,  
					   "currencies"=>$this->common->getCurrency_(),  
					   "sources"=>$this->common->getSourceAll_(array("Status"=>1)),  
					   "utypes"=>$this->common->getUsersGroup_(array("Status ="=>1)), 
					   "sdata"=>$sdata 
					  );
		 
					  
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Reports Source Details ", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('reports/cal_source_details_tpl');
		$this->load->view('footer');   
	}
	 
	
	public function getCalSourceReportsDetails()
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !csd_supervisor_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string .= ""; 
		$search_string2 = "";   
		$search_string3 = "";   
		$search_arr = array(); 
		$activity = "";
		  
		if(trim($data[s_source]))
		 {
			$search_string .= " AND (Source=".$this->common->escapeString_(trim($data[s_source])).") "; 
			$search_url .= "&s_sourcce=".trim($data[s_sourcce]);   
		 } 
		 
		if(trim($data[s_agent]))
		 {
			$search_string .= " AND (AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		
		if(trim($data[s_status]))
		 {
			$search_string .= " AND (Status=".$this->common->escapeString_(trim($data[s_status])).") "; 
			$search_url .= "&s_status=".trim($data[s_status]);   
		 } 
		  
		if(trim($data[s_activity]))
		 {
			$activity =  $this->activity_types[trim($data[s_activity])][Controller];    
			//$search_string .= " AND (Activity='".$this->common->escapeString_(trim($data[s_activity]))."') "; 
			$search_url .= "&s_activity=".trim($data[s_activity]);   
		 } 
		  
		if(trim($data[s_assignee]))
		 {
			$search_string .= " AND (GroupAssignee=".$this->common->escapeString_(trim($data[s_assignee])).") "; 
			$search_url .= "&s_assignee=".trim($data[s_assignee]);   
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";  
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } 
		 
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (Username='".$this->common->escapeString_(trim($data[s_username]))."') ";  
			$search_url .= "&s_username=".trim($data[s_username]);   
		 } 
		
		if(trim($data[s_important])=='1')
		 { 
			$search_string .= " AND (Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$$data[s_important];    
		 }
		
		if(trim($data[s_iscomplaint])=='1')
		 { 
			$search_string .= " AND (IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);   
		 }
		 
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		   
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		 
		$return = $this->reports->getCalSourceReportsDetails_($search_string, $search_string2, $activity, $paging=array("limit"=>$per_page, "page"=>$page)); 
		 
		//$return = $activities = $this->promotions->getPromotionActivities_($search_string, $view_statuslist, $paging=array("limit"=>$per_page, "page"=>$page), $index, $order_by); 
		
		$total_rows = $return[total_rows]; 
		$activities = $return[result];
		
		  
		$pagination_options = array("link"=>"",//base_url()."access/activities",   
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"users":"user";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		$return = array(//"users"=>$this->generateHtmlUserData($status_data, $source_data),  
						"activities"=>$activities,
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string, 
						"records"=>$total_rows, 
						"activity_types"=>$this->activity_types
						//"status_data"=>$status_data,
						//"source_data"=>$source_data, 
						//"report_status"=>$this->report_status,
						//"report_sources"=>$this-> report_sources, 
						//"report_customs"=>$this->report_customs
				   ); 
				//$return = $source_data;
		 echo json_encode($return);
		
	} 
	
	
	public function exportSourceReportsDetails($actual=0)
	{	  
		
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 }  
		 
		if(!admin_access() && !csd_supervisor_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post(); 
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		    
		$search_string .= ""; 
		$search_string2 = "";   
		$search_string3 = "";   
		$search_arr = array(); 
		$activity = "";
		  
		if(trim($data[s_source]))
		 {
			$search_string .= " AND (Source=".$this->common->escapeString_(trim($data[s_source])).") "; 
			$search_url .= "&s_sourcce=".trim($data[s_sourcce]);   
		 } 
		 
		if(trim($data[s_agent]))
		 {
			$search_string .= " AND (AddedBy=".$this->common->escapeString_(trim($data[s_agent])).") "; 
			$search_url .= "&s_agent=".trim($data[s_agent]);   
		 } 
		
		if(trim($data[s_status]))
		 {
			$search_string .= " AND (Status=".$this->common->escapeString_(trim($data[s_status])).") "; 
			$search_url .= "&s_status=".trim($data[s_status]);   
		 } 
		  
		if(trim($data[s_activity]))
		 {
			$activity =  $this->activity_types[trim($data[s_activity])][Controller];    
			//$search_string .= " AND (Activity='".$this->common->escapeString_(trim($data[s_activity]))."') "; 
			$search_url .= "&s_activity=".trim($data[s_activity]);   
		 } 
		  
		if(trim($data[s_assignee]))
		 {
			$search_string .= " AND (GroupAssignee=".$this->common->escapeString_(trim($data[s_assignee])).") "; 
			$search_url .= "&s_assignee=".trim($data[s_assignee]);   
		 } 
		    
		if($s_fromdate && $s_todate)
		 {  
			$search_string .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate})  ";  
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]));    
		 } 
		
		if(trim($data[s_currency]))
		 {
			$search_string .= " AND (Currency=".$this->common->escapeString_(trim($data[s_currency])).") "; 
			$search_url .= "&s_currency=".trim($data[s_currency]);   
		 } 
		 
		if(trim($data[s_username]))
		 {
			$search_string .= " AND (Username='".$this->common->escapeString_(trim($data[s_username]))."') ";  
			$search_url .= "&s_username=".trim($data[s_username]);   
		 } 
		
		if(trim($data[s_important])=='1')
		 { 
			$search_string .= " AND (Important='".$this->common->escapeString_($data[s_important])."') "; 
			$search_url .= "&s_important=".$$data[s_important];    
		 }
		
		if(trim($data[s_iscomplaint])=='1')
		 { 
			$search_string .= " AND (IsComplaint='".$this->common->escapeString_(trim($data[s_iscomplaint]))."') "; 
			$search_url .= "&s_iscomplaint=".trim($$data[s_iscomplaint]);   
		 }
		 
		$search_string = trim(trim($search_string), "AND");
		$search_string2 = trim(trim($search_string2), "AND");
		   
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		 
		$return = $this->reports->getCalSourceReportsDetails_($search_string, $search_string2, $activity, $paging=array()); 
		 
		$total_rows = $return[total_rows]; 
		$activities = $return[result];  
	 
		$excel_data = array("CurrencyName"=>"Currency", 
			 				"Activity"=>"Activity",
							"Username"=>"Username",
							"SourceName"=>"Source",
							"StatusName"=>"Status",
							"GroupAssigneeName"=>"Assignee", 
							"DateAddedInt"=>"Date Added", 
							"IsComplaint"=>"Is Complaint",
							"Important"=>"Important" 
							);  
							
		$force_str = array("Username", "SourceName");
		
		//delete_old_files("media/temp/", "*.xls");  
		delete_old_files($this->common->temp_file, "*.xls"); 
		$file_name = "status_reports_details".'-'.date("Ymdhis").".xls"; 
		$title = "Status Reports Details";  
		
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
										'font'=> array('bold'=>true)));  
										   
		$normalStyle = array('alignment' => array('wrap' => true,
												'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
												'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                                        )
							  );
							  
							  
		$y = 'A';
		$start = 1;
		 
		foreach($excel_data as $row=>$val){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,$val);
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);   
			$y++; 
		}//end foreach
		
		$ctr = $start + 1;
		$category_code = "";  
		$count_result = 0;   
	 
		$end_cell = '';
		$start_cell = 'A'.$ctr; 
		foreach($activities as $row=>$activity){   
			$x = 'A'; 
			//set format  
			$activity->DateAddedInt = date("F d, Y H:i:s D", $activity->DateAddedInt);    
		 
			$activity->Important = ($activity->Important==1)?"YES":"NO";
			$activity->IsComplaint = ($activity->IsComplaint==1)?"YES":"NO";   
			   
			foreach($excel_data as $index=>$field){ 
				if(in_array($field, $force_str))
				 {
					$activeSheet->setCellValueExplicit($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				else
				 {
					$activeSheet->setCellValue($x.$ctr,trim($activity->$index), PHPExcel_Cell_DataType::TYPE_STRING);   
				 }
				
				//$activeSheet->getStyle($x.$ctr)->applyFromArray($normalStyle); 
				$end_cell = $x.$ctr; 
				$x++;   
			}
			$ctr++;
			 
		}//end foreach 
		
		$activeSheet->getStyle($start_cell.':'.$end_cell)->applyFromArray($normalStyle); 
		   
		//count reports	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Activities(s)"); 
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->setCellValue('B'.($ctr+2), count($activities));
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle);  
		
		//set auto width
		$x='A';
		$col = 0; 
		foreach($excel_data as $row=>$data){
			$activeSheet->getColumnDimension($x)->setAutoSize(true);     
			$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
			$x++;	
		}  
		 
		$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
		//$objWriter->save('php://output'); 
		$filePath = $this->common->temp_file; //"media/temp/"
		$objWriter->save($filePath.$file_name);    
		
		$return = (file_exists($filePath.$file_name))?array("success"=>1, "message"=>"Downloading file.", "download_link"=>encode_string($filePath.$file_name)):array("success"=>0, "message"=>"Error downloading file.", "download_link"=>"");     
		  
		echo json_encode($return); 
		
	}//end export activities 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */