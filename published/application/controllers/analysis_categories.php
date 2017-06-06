<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Analysis_Categories extends MY_Controller {

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
		$this->types = array(array("Value"=>"deposit", "Label"=>"Deposit"), array("Value"=>"withdrawal", "Label"=>"Withdrawal"));
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")); 
	}
 	
	public function index()
	{   
		$this->analysisCategories("deposit");    
	}
	
	
	//DEPOSIT CATEGORIES 
	public function analysisCategories($type="")
	{   
	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"banks",  
					   "categories"=>$this->common->getAnalysisCategories_(array("Status"=>1)),  
					   "status_list"=>array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")), 
					   "types"=>$this->types   
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Analysis Reasons", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('banks/analysis_categories_tpl');
		$this->load->view('footer');   
		 
	} 
	//END DEPOSIT CATEGORIES
	
	
	public function getAnalysisCategories()
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
		$view_statuslist = array();   
		  
		
		$s_fromdate = strtotime(trim($data[s_fromdate]));  
		$s_todate = strtotime(trim($data[s_todate]));  
		
		$search_string = "";  
		$allow_close = 0;
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.CategoryID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		 
		   
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 } 
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_analysis_category AS a", "a.CategoryID")->TotalCount; 
	 	 
		$categories = $this->banks->getAnalysisCategories_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 
		$pagination_options = array("link"=>"",//base_url()."banks/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1;
		$plural_txt = ($total_rows > 1)?"reasons":"reason";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
						   
		if($actual == 1)//
		 {  
		 	 $return = array("reasons"=>$categories, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("reasons"=>$this->generateHtmlAnalysisCategories($categories), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlAnalysisCategories($categories)
	{
		$return = ""; 
		if(count($categories))
		 { 
			foreach($categories as $row=>$category){  
				$status = ($category->Status=='0' || $category->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$return .= "
						<tr class=\"activity_row\" id=\"ReasonRow{$category->CategoryID}\" > 
							<td class=\"center\" >".str_pad($category->CategoryID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$category->Name}</td>   
							<td class=\"center\" >{$category->DateUpdated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#ReasonModal\" title=\"edit category\" alt=\"edit reason\" class=\"edit_category tip\" category-id=\"{$category->CategoryID}\"  id=\"Edit{$category->CategoryID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"7\" >No category found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageAnalysisCategory()
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
		  
		$category_id = trim($this->uri->segment(3)); 
		
		$conditions_array = array('a.CategoryID ='=>$category_id);  
		   
		$data2 = array("main_page"=>"banks",     
					   "status_list"=>$this->status_list,   
					   "categories"=>$this->common->getAnalysisCategories_(array("Status"=>1)), 
					   "types"=>$this->types, 
					   "category"=>$this->banks->getAnalysisCategoryById_(array("a.CategoryID"=>$category_id))  
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Category", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('banks/analysis_categories_popup_tpl',$data);  
		 
	} 
	
	
	public function manageCategory()
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
		 
		if($data[category_name] == "")
		 {
			 $error .= "Enter category name!<br> ";
		 } 
		   
		if($data[category_status] == "")
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
					'Name'=>$data['category_name'],    
					'Description'=>$data['category_desc'],  
					'AddedBy'=>$this->session->userdata("mb_no"), 
					'DateAdded'=>$current_date,   
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date, 
					'Status'=>$data['category_status'], 
				 );   
				 
				$last_id = $this->banks->manageActivity_("csa_analysis_category", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Analysis category added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding analysis category!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['category_name'],    
					'Description'=>$data['category_desc'],  
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date, 
					'Status'=>$data['category_status'], 
				 );   
				 
				 $changes = ""; 
				 
				 $old = $this->banks->getAnalysisCategoryById_(array("a.CategoryID"=>$data[hidden_acategoryid]));  
				 
				 $new_specifytxt = ($data['category_isspecify'] == 1)?"YES":"NO"; 
				 $old_specifytxt = ($old->IsSpecify == 1)?"YES":"NO"; 
				 
				 $changes .= ($data['category_name'] != $old->Name)?"Category Name changed to ".$data['category_name']." from ".$old->Name."|||":"";    
				 $changes .= ($data['category_desc'] != $old->Description)?"Description changed to ".$data['category_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($data['category_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";    
				 
				 if($changes != "")
				  {
					$x = $this->banks->manageActivity_("csa_analysis_category", $post_data, $action, "CategoryID", $this->input->post("hidden_acategoryid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Analysis Category updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating analysis category!");
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
		$this->analysisCategories("withdrawal");
	} 
	//END WITHDRAWAL CATEGORIES
	
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */