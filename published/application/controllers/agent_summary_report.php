<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agent_Summary_Report extends MY_Controller {

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
		$this->load->model("promotions_model","promotions");  
		$this->load->model("common_model","common");   
		$this->activity_type = "promotion";
		
		//$this->load->library('encrypt');
		 
	}
 	
	public function index()
	{   
	 
		if(!admin_access() && !allow_agent_report()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		  
		$call_results = $this->promotions->getResultList_();  
							
		$data2 = array("main_page"=>"promotions",  
					   "currencies"=>$this->common->getCurrency_(), 
					   "call_results"=>$call_results,
					   //"counts"=>$this->promotions->getPromotionsCategoriesList_($categories_where),
					   //"status_list"=>$this->common->getStatusList_(3),//3 for promotion page  
					   //"outcomes"=>$this->promotions->getCallOutcomeList_(array("a.outcome_status ="=>'1')),
					   //"s_page"=>$page
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Promotional Activities", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('promotions/agent_summary_report_tpl');
		$this->load->view('footer');   
	}
	
	public function agentSummaryReport()
	{ 
		$this->index();
	}
	
	public function getCountCalls($actual=0)
	{
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !allow_agent_report()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data = $this->input->post();   
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate])); 
		$call_search_string = ""; 
		$search_string = " a.mb_status = '1' AND a.mb_usertype IN({$this->common->callers_usertype}) ";
		 
		if($s_fromdate && $s_todate)
		 {  
		 	$call_search_string .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate}) "; 
			$index = "AgentSummaryReport"; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]))."&s_dateindex=AgentSummaryReport";  
		 } 
		else
		 {
			 $index = "AgentSummaryReport"; 
		 }
		
	 	$call_results = $this->promotions->getResultList_();  
		
		if(count($call_results) > 0)
		 { 	
			foreach($call_results as $row=>$result) { 
				$res_name = "Result_".$result->result_id;
				//array_push($res_arr, array("name"=>$res_name, "total"=>0));
				$count_str .= ", SUM(IF(CallResultID = ".$result->result_id.", 1, 0)) AS ".$res_name;     
			}
			$new_str = rtrim($count_str, ",");
			$sum_str = $new_str;
		 } 
		
		$call_search_string = trim(trim($call_search_string), "AND");
		$search_string = trim(trim($search_string), "AND");
		  
		$per_page = 20; 
		//$page = ($this->uri->segment(3))? $this->uri->segment(3) : 0;
		$page = ($data['s_page'])? $data['s_page'] : 0;
		 
		//$count_agents = $this->promotions->countAgentsCalls_($data); 
		//$total_rows = count($count_agents);  
		//$calls_count = $this->promotions->getCountCallsList_($data, $call_results, $paging=array("limit"=>$per_page, "page"=>$page));   
		  
		$return = $activities = $this->promotions->getCountCallsList_($search_string, $call_search_string, $sum_str, $paging=array("limit"=>$per_page, "page"=>$page), $index, $order_by); 
		$total_rows = $return[total_rows]; 
		$calls_count = $return[result];
		
		 
		$pagination_options = array("link"=>"",//base_url()."promotions/agent-summary-report", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + 20) <= $total_rows)?$page + 20:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"records":"record";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
						   
		if($actual == 1)//
		 {  
		 	 $return = array("call_results"=>$call_results, 
							 "count_agents"=>$count_agents,
							 "calls_count"=>$calls_count,
							 "pagination"=>create_pagination($pagination_options), 
							 "pagination_string"=>$pagination_string, 
							 "records"=>$total_rows
						   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("reports"=>$this->generateSummaryReportHtmlList($call_results, $total_rows, $calls_count, $data), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string, 
							"records"=>$total_rows
					   );
			 echo json_encode($return); 
		 }
		
	}
	
 
	  
	public function generateSummaryReportHtmlList($call_results, $count_agents, $calls_count, $data)
	{ 
		if(count($calls_count))
		 { 
		 	$res = array(); 
			$overall_total = 0; 
			$total_duration = 0; 
			foreach($calls_count as $row=>$count){  
				$data['s_agentid'] = $count->mb_no; 
				$params = encode_string(http_build_query($data, '', '&amp;')); 	 
				
				$return .= "
						<tr class=\"activity_row\" id=\"Agent{$count->mb_no}\" > 
							<td class=\"center\" ><a  href=".base_url("promotions/call-details/".$params)." >{$count->mb_nick}</a></td>
							<td class=\"center\" >{$count->TotalCall}</td>
							<td class=\"center\" >".gmdate("H:i:s", $count->ReachDuration)."</td>
						"; 
				$overall_total += $count->TotalCall;		 
				$total_duration += $count->ReachDuration;	
				
				foreach($call_results as $row=>$result) {
					$field = "Result_".$result->result_id;
					$return .= "<td class=\"center\" >{$count->{$field}}</td>";	 
					$res[$field][total] += $count->$field;
				}
				
				$return .= "
						</tr> ";
						
			}//end foreach 
	 
			if(count($res) > 0)
			 { 
				$return .= "
							<tr class=\"activity_row\" id=\"AgentTotalCount\" > 
								<td class=\"center\" >Total Calls</td>
								<td class=\"center\" >{$overall_total}</td>
								<td class=\"center\" >".gmdate("d H:i:s", $total_duration)."</td>
							";
				foreach($res as $row=>$total_res) { 
					$return .= "<td class=\"center\" >{$total_res[total]}</td>";	 
				}
				
				$return .= "
							</tr> ";	
										 
			 }
			 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"9\" >No record found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	} 
	
	
	public function exportReports($actual=0)
	{  
		  
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !allow_agent_report()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		  
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate])); 
		$call_search_string = ""; 
		$search_string = " a.mb_status = '1' AND a.mb_usertype IN({$this->common->callers_usertype}) ";
		 
		if($s_fromdate && $s_todate)
		 {  
		 	$call_search_string .= " AND (DateAddedInt BETWEEN {$s_fromdate} AND {$s_todate}) "; 
			$index = "AgentSummaryReport"; 
			$search_url .= "&s_fromdate=".urlencode(trim($data[s_fromdate]))."&s_todate=".urlencode(trim($data[s_todate]))."&s_dateindex=AgentSummaryReport";  
		 } 
		else
		 {
			 $index = "AgentSummaryReport"; 
		 }
		  
		//delete old files
		delete_old_files($this->common->temp_file, "*.xls"); 
		 
		$file_name = "agent_report".'-'.date("Ymdhis").".xls"; 
		
		//load our new PHPExcel library
		$this->load->library('excel'); 
		 
		//activate worksheet number 1
		$activeSheet = $this->excel->setActiveSheetIndex(0);
		//name the worksheet
		$activeSheet->setTitle($file_name);
		  
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
										
										
		$categoryStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'E4E4E4'),
										'font'=> array('bold'=>true)));
		$reportStyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
										'color' => array('rgb' => 'f4ec12'),
										'font'=> array('bold'=>true)));  
										
		$header_list = array("Agent","All","Reached Duration");
		$data_list = array();
		$data = $this->input->post();
		$per_page = 20;  
		$page = ($data['s_page'])? $data['s_page'] : 0;
		
		
		$call_results = $this->promotions->getResultList_();  
		
		if(count($call_results) > 0)
		 { 	
			foreach($call_results as $row=>$result) { 
				$res_name = "Result_".$result->result_id;
				//array_push($res_arr, array("name"=>$res_name, "total"=>0));
				$count_str .= ", SUM(IF(CallResultID = ".$result->result_id.", 1, 0)) AS ".$res_name;     
			}
			$new_str = rtrim($count_str, ",");
			$sum_str = $new_str;
		 } 
		
		$call_search_string = trim(trim($call_search_string), "AND");
		$search_string = trim(trim($search_string), "AND");
		
		//$total_rows = count($count_agents); 
	    //$calls_count = $this->promotions->getCountCallsList_($data, $call_results);  
		
		
		$return = $activities = $this->promotions->getCountCallsList_($search_string, $call_search_string, $sum_str, array(), $index, $order_by); 
		$total_rows = $return[total_rows]; 
		$calls_count = $return[result];
		
		  
		$y = 'A';
		$start = 1;
		 
		foreach($header_list as $row=>$val){ 
			$row_cel = $y.$start;   
			$activeSheet->setCellValue($row_cel,$val);
			$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);  
			$y++; 
		}//end foreach
		
		//check for dynamic header
		if(count($call_results) > 0)
		 {
			foreach($call_results as $row=>$header){   
				$row_cel = $y.$start;   
				$activeSheet->setCellValue($row_cel,$header->result_name);
				$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle);  
				$y++; 
			}
		 }//end dynamic header
		 
		
		$ctr = $start + 1;
		$category_code = "";  
		$count_result = 0;   
	
		$res = array(); 
		$overall_total = 0; 
		$total_duration = 0;
		
		
		foreach($calls_count as $row=>$count){   
			$x = 'A';
			$duration = $count->ReachDuration;
			$data_list["Agent".$count->mb_no] = array("mb_nick"=>$count->mb_nick,
													  "TotalCall"=>$count->TotalCall,
													  "ReachDuration"=>gmdate("d H:i s", $count->ReachDuration) 
												);
			
			$activeSheet->setCellValue($x.$ctr, $count->mb_nick, PHPExcel_Cell_DataType::TYPE_STRING);  
			$x++;
			$activeSheet->setCellValue($x.$ctr, $count->TotalCall, PHPExcel_Cell_DataType::TYPE_STRING);  
			$x++;
			$activeSheet->setCellValue($x.$ctr, gmdate("d H:is", $count->ReachDuration), PHPExcel_Cell_DataType::TYPE_STRING);  
			$x++;
					
			$overall_total += $count->TotalCall;		 
			$total_duration += $count->ReachDuration;	
			
			foreach($call_results as $row=>$result) {
				$field = "Result_".$result->result_id;
 				$activeSheet->setCellValue($x.$ctr, $count->{$field}, PHPExcel_Cell_DataType::TYPE_STRING);    
				$res[$field][total] += $count->$field; //for total per result
				$x++;
			}
			$ctr++;
			 
		}//end foreach 
		
		//for footer
		if(count($res) > 0)
		 { 
		 	$x = 'A'; 
			$current_x = '';
			$activeSheet->setCellValue($x.$ctr, "Total Calls", PHPExcel_Cell_DataType::TYPE_STRING);   
			$x++; 
			$activeSheet->setCellValue($x.$ctr, $overall_total, PHPExcel_Cell_DataType::TYPE_STRING);  
			$x++;
			$activeSheet->setCellValue($x.$ctr, gmdate("d H:i:s", $total_duration), PHPExcel_Cell_DataType::TYPE_STRING);  
			$x++; 
			
			foreach($res as $row=>$total_res) {  	  
				$activeSheet->setCellValue($x.$ctr, $total_res[total], PHPExcel_Cell_DataType::TYPE_STRING);  
				$current_x = $x; 
				$x++;
			}
			$activeSheet->getStyle("A{$ctr}:{$current_x}{$ctr}")->applyFromArray($headerStyle);  
			
			$ctr++; 				 
		 }//end footer 
		 
		 
		//count reports	 
		$activeSheet->setCellValue('A'.($ctr+2),"Total Agent(s)");
		$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
		$activeSheet->setCellValue('B'.($ctr+2), count($calls_count));
		$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle); 
		
		$x='A';
		for ($col = 0; $col<count($calls_count); $col++) {
			$activeSheet->getColumnDimension($x)->setAutoSize(true); 
			$activeSheet->getStyle($x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true); 
			$x++; 
			
		} 
		//$activeSheet->getStyle("A:{$current_x}")->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
		
		/*for ($col = 2; $col<count($data_list); $col++) { 
			$activeSheet->getRowDimension($col)->setRowHeight(-1); 
			$activeSheet->getStyle('E'.$col)->getAlignment()->setWrapText(true);
		} 
		*/
		
		
		//breakline
		/*$rw='A';
		for ($y=1; $y<=count($data_list); $y++) { 
			for ($x = 1; $x<=count($data_list); $x++) { 
				 $objPHPExcel->getActiveSheet()->getStyle($rw.$x)->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);
			 }
			$rw++; 
		}
		
		//set auto height 
		for ($col = 2; $col<count($data_list); $col++) { 
			$objPHPExcel->getActiveSheet()->getRowDimension($col)->setRowHeight(-1); 
			//$objPHPExcel->getActiveSheet()->getStyle('E'.$col)->getAlignment()->setWrapText(true);
		}*/
		
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
	} 
	 
	 
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */