<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   
class Dashboard extends MY_Controller {

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
		$this->load->model("dashboard_model","dashboard");
		$this->load->model("common_model","common");
		$this->load->helper('text');  
		
		$date_start = "2013-09-01 00:00:00"; 
		
		$this->date_to = date("Y-m-d", strtotime("+1 day") );  
		//$this->date_to = date("Y-m-d");  
		//$this->date_from = date("Y-m-d", strtotime("-10 day", strtotime($this->date_to)));  
		//$this->date_from = (view_management())?date("Y-m-d", strtotime("-2 month", strtotime($this->date_to))):date("Y-m-d", strtotime("-10 day", strtotime($this->date_to)));
		$this->date_from = (view_management())?date("Y-m-d", strtotime("-20 day", strtotime($this->date_to))):date("Y-m-d", strtotime("-20 day", strtotime($this->date_to)));
		 
		//$this->date_to = date("Y-m-d");
		//$this->date_from = date("Y-m-d", strtotime($date_start));
		
		//$this->date_to = date("Y-m-d", strtotime("-5 day") );
		//$this->date_from = date("Y-m-d", strtotime("-1 month", strtotime($this->date_to)));
		
	}
	
	public function index()
	{	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		$data2 = array("main_page"=>"dashboard");
		$sidebar = "sidebar";//(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar";//"operations/sidebar_operations_tpl"; 
		 			   
		$data = array("page_title"=>"12Bet - CAL - Dashboard", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true), 
					  "activity_types"=>$this->common->activity_types, 
					  "date_from"=>$this->date_from, 
					  "date_to"=>$this->date_to, 
					  "date_index"=>($this->common->date_index=="DateUpdatedInt")?"updated":"added"
					 );
		 
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('dashboard_tpl');
		$this->load->view('footer');   
	}
	
	public function countProcessActivity()
	{
		 
		$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_to:date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_from:date("Y-m-d", strtotime($this->input->post("date_from")) );
		//$result_bank = $this->dashboard->countProcessActivity_("deposit_withdrawal", strtotime($date_from), strtotime($date_to)); 
		
		$return = array("bank"=>$this->dashboard->countProcessActivity_("deposit_withdrawal", strtotime($date_from), strtotime($date_to), $this->common->date_index)->CountRecord, 
						"promotion"=>$this->dashboard->countProcessActivity_("promotion", strtotime($date_from), strtotime($date_to), $this->common->date_index)->CountRecord,  
						"casino_issues"=>$this->dashboard->countProcessActivity_("casino_issues", strtotime($date_from), strtotime($date_to), $this->common->date_index)->CountRecord, 
						"account_issues"=>$this->dashboard->countProcessActivity_("account_issues", strtotime($date_from), strtotime($date_to), $this->common->date_index)->CountRecord,
						"suggestions_complaints"=>$this->dashboard->countProcessActivity_("suggestions_complaints", strtotime($date_from), strtotime($date_to), $this->common->date_index)->CountRecord, 
						"website_mobile"=>$this->dashboard->countProcessActivity_("website_mobile", strtotime($date_from), strtotime($date_to), $this->common->date_index)->CountRecord 
					   );	
		
		echo json_encode($return);
	}
	
	
	public function getActivityStatistic()
	{
		$type = $this->input->post("type"); 
		 
		$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_to:date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_from:date("Y-m-d", strtotime($this->input->post("date_from")) );
		 
		$result = $this->dashboard->getActivityStatistic_($type, strtotime($date_from), strtotime($date_to)); 
		$data = array();
		
		$type_name = ucwords($this->common->activity_types[$type][label]);
		
		$count_complaint = 0; 
		$count_important = 0;
		$count_close = 0; 
 		  
		if(count($result) == 1)
		 {
			$newdata = $result;   
			$x = array($newdata->DateGroup * 1000, (int)$newdata->CountRecord);  
			//$x = array(strtotime($newdata->DateGroup) * 1000, (int)$newdata->CountRecord);  
			array_push($data, $x);   
			$count_complaint += $newdata->TotalComplaint; 
			$count_important += $newdata->TotalImportant;
			$count_close += $newdata->TotalCloseTicket;
		 }
		elseif(count($result) > 1)
		 {
			foreach($result as $row=>$newdata) { 
				$x = array($newdata->DateGroup * 1000, (int)$newdata->CountRecord);  
				//$x = array(strtotime($newdata->DateGroup) * 1000, (int)$newdata->CountRecord);  
				array_push($data, $x);  
				$count_complaint += $newdata->TotalComplaint;  
				$count_important += $newdata->TotalImportant;
				$count_close += $newdata->TotalCloseTicket;
			}//end foreach 
		 }
		else
		 {
			$newdata = $result;   
			$x = array($newdata->DateGroup * 1000, (int)$newdata->CountRecord);  
			//$x = array(strtotime($newdata->DateGroup) * 1000, (int)$newdata->CountRecord);  
			array_push($data, $x);   
		 }
		
		$return = array("label"=>$type_name,
						"data" =>$data,
						"lines"=>array("fillColor" => $this->common->activity_types[$type]), //get the fillcolor in model variable activity_types 
						"points"=>array("fillColor" => "#fff"), 
						"date_from"=>strtotime($date_from) * 1000, 
						"date_to"=>strtotime($date_to) * 1000, 
						"count_complaint"=>$count_complaint, 
						"count_important"=>$count_important, 
						"count_close"=>$count_close
					);
		echo json_encode($return);  
	} 
	
	public function getComplaintImportant()
	{
		$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_to:date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_from:date("Y-m-d", strtotime($this->input->post("date_from")) );
		
		$result = $this->dashboard->getComplaintImportant_($date_from, $date_to); 
		$data = array();
		
	}
	
	
	public function countAssignedActivity()
	{
		 
		$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_to:date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_from:date("Y-m-d", strtotime($this->input->post("date_from")) );
		 
		$return = array("bank"=>$this->dashboard->countAssignedActivity_("deposit_withdrawal", strtotime($date_from), strtotime($date_to), $this->common->date_index), 
						"promotion"=>$this->dashboard->countAssignedActivity_("promotion", strtotime($date_from), strtotime($date_to), $this->common->date_index),  
						"casino_issues"=>$this->dashboard->countAssignedActivity_("casino_issues", strtotime($date_from), strtotime($date_to), $this->common->date_index), 
						"account_issues"=>$this->dashboard->countAssignedActivity_("account_issues", strtotime($date_from), strtotime($date_to), $this->common->date_index),
						"suggestions_complaints"=>$this->dashboard->countAssignedActivity_("suggestions_complaints", strtotime($date_from), strtotime($date_to), $this->common->date_index), 
						"website_mobile"=>$this->dashboard->countAssignedActivity_("website_mobile", strtotime($date_from), strtotime($date_to), $this->common->date_index) 
					   );	 
		 
		echo json_encode($return);
	}
	
	
	public function countGroupAssigned()
	{ 
		/*$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_to:date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?$this->date_from:date("Y-m-d", strtotime($this->input->post("date_from")) ); */
		
		$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?"":date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?"":date("Y-m-d", strtotime($this->input->post("date_from")) ); 
		 
		$data['s_fromdate'] = $date_from; 
		$data['s_todate'] = $date_to;  
		$data['s_dashboard'] = 1;    
		$data['s_dateindex'] = ($this->common->group_assigned_index == "SearchAllUpdatedKey")?"updated":"added";
	 	 
		$return = $this->dashboard->countGroupAssigned_("", strtotime($date_from), strtotime($date_to), $this->common->group_assigned_index); 
		foreach($return as &$row){
			$data['s_assignee'] = $row->GroupID; 
			$params = encode_string(http_build_query($data, '', '&amp;')); 
			$type_link = base_url("search-activities/".$params); 
			
			$row->Link = $type_link; //also set the $return 
		} 
		echo json_encode($return);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 
	
	
	public function getDataOverpay()
	{
		$status_id = $this->input->post("series"); 
		$date_to = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?date("Y-m-d", strtotime("+2 day") ):date("Y-m-d", strtotime($this->input->post("date_to")) ); 
		//$date_from = ($this->input->post("date_from")=="" || $this->input->post("date_to")=="")?date("Y-m-d", strtotime($this->common->system_startdate) ):date("Y-m-d", strtotime($this->input->post("date_from")) );
		$date_from = date("Y-m-d", strtotime("-10 day", strtotime($date_to)));
		
		$result = $this->dashboard->getDataOverpay_($status_id, $date_from, $date_to); 
		$data = array();
		
		if(count($result) == 1)
		 {
			$newdata = $result;   
			$x = array(strtotime($newdata->DateGroup) * 1000, (int)$newdata->CountOverypay); 	 
			$status_name = ucwords($newdata->StatusName);
			array_push($data, $x); 
		 }
		else
		 {
			foreach($result as $row=>$newdata) { 
				$x = array(strtotime($newdata->DateGroup) * 1000, (int)$newdata->CountOverypay); 	 
				$status_name = ucwords($newdata->StatusName);
				array_push($data, $x); 
			}//end foreach 
		 }
		
		
		$return = array("label"=>ucwords($status_name),
						"data" =>$data,
						"lines"=>array("fillColor" => "#f3faff"), 
						"points"=>array("fillColor" => "#fff"), 
						"date_from"=>strtotime($date_from) * 1000, 
						"date_to"=>strtotime($date_to) * 1000, 
					);
		echo json_encode($return);  
	} 
	
	
	public function getDataOverpayXXX()
	{
		$series = $this->input->post("series"); 
		
		//$count = $this->getRandom();//rand(1,5)*2;
		$return = array("label"=>ucwords($series),
						"data" => array(array(1394985600000, $this->getRandom()), array(1395072000000, $this->getRandom()), array(1395158400000, $this->getRandom()), array(1395244800000, $this->getRandom()), 
								  array(1395331200000, $this->getRandom()), array(1395417600000, $this->getRandom()), array(1395504000000, $this->getRandom()), array(1395676800000, $this->getRandom()),
								  array(1395936000000, $this->getRandom())	
							  ),
						"lines"=>array("fillColor" => "#f3faff"), 
						"points"=>array("fillColor" => "#fff")
					);
		echo json_encode($return); 
		
		/*echo "{ 
				\"label\": \"Japan\",
				\"data\": [[1999, -0.1], [2000, 2.9], [2001, 0.2], [2002, 0.3], [2003, 1.4], [2004, 2.7], [2005, 1.9], [2006, 2.0], [2007, 2.3], [2008, -0.7]]
				lines: {fillColor: "#f3faff"},
				points: {fillColor: "#fff"}
			}";*/
	} 
	
	public function getRandom()
	 {
		 $count = (rand(0,15)*3) + 10; 
		 return $count; 
	 }
	
	
	 
	
	public function getNotice()
	 {
		$limit = ($this->input->post("limit") > 0)?$this->input->post("limit"):"";  
		$return = $this->common->getNotice_($limit); 
		echo json_encode($return);  
	 }
	
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */