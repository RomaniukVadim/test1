<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Deposit_Categories extends MY_Controller {

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
		$this->depositCategories("deposit");    
	}
	
	
	//DEPOSIT CATEGORIES 
	public function depositCategories($dcategory="deposit")
	{   
	 
		if(!admin_access() && !view_access()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 					
		$data2 = array("main_page"=>"banks",  
					   "dcategory"=>$dcategory,  
					   "status_list"=>array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")) 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - ".ucwords($dcategory)." Categories", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('banks/deposit_categories_tpl');
		$this->load->view('footer');   
		 
	} 
	//END DEPOSIT CATEGORIES
	
	
	public function getCategoriesList()
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
		
		$search_string .= " AND (a.Category='".$this->common->escapeString_(trim($data[s_category]))."') ";  
		 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_bank_category AS a", "a.CategoryID")->TotalCount; 
	 	 
		$categories = $this->banks->getCategoriesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 
		$pagination_options = array("link"=>"",//base_url()."banks/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1;
		$plural_txt = ($total_rows > 1)?"categories":"category";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
						   
		if($actual == 1)//
		 {  
		 	 $return = array("categories"=>$categories, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("categories"=>$this->generateHtmlCategoriesList($categories), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlCategoriesList($categories)
	{
		$return = ""; 
		if(count($categories))
		 { 
			foreach($categories as $row=>$category){  
				$status = ($category->Status=='0' || $category->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$return .= "
						<tr class=\"activity_row\" id=\"CategoryRow{$category->CategoryID}\" > 
							<td class=\"center\" >".str_pad($category->CategoryID,4,'0', STR_PAD_LEFT)."</td>
							<td  >{$category->Name}</td> 
							<td class=\"center\" >{$category->DateUpdated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#CategoryModal\" title=\"edit category\" alt=\"edit category\" class=\"edit_category tip\" category-id=\"{$category->CategoryID}\"  id=\"Edit{$category->CategoryID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
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
	
	
	public function popupManageCategory()
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
		 
		$dcategory = (trim($this->uri->segment(3)))?trim($this->uri->segment(3)):"deposit";
		$category_id = trim($this->uri->segment(4));
		
		if(!$dcategory || $dcategory == "") 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		 
		
		$conditions_array = array('a.CategoryID =' => $category_id, 'a.Category ='=>$dcategory); 
		$category = ($category_id)?$this->banks->getCategoryById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"banks",     
					   "status_list"=>array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active")),   
					   "category"=>$category, 
					   "dcategory"=>$dcategory, 
					   "user_types"=>$this->common->getUserTypes_(array("Status ="=>1)),  
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Category", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('banks/deposit_category_popup_tpl',$data);  
		 
	} 
	
	
	public function manageCategory()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		  
		$error = "";   
		$data = $this->input->post(); 
		
		$data['category_showininternal'] = ($data['category_showininternal'])?$data['category_showininternal']:"";
		 
		if($data[category_name] == "")
		 {
			 $error .= "Enter name!<br> ";
		 } 
		
		if($data[category_status] == "")
		 {
			 $error .= "Select status!<br> ";
		 }
		
		if($data[hidden_acategory] == "")
		 {
			 $error .= "Missing data!<br> ";
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
					'Category'=>$data['hidden_acategory'],  
					'Description'=>$data['category_desc'],
					'ShowInInternal'=>$data['category_showininternal'], 
					'Assignee'=>$data['category_assignee'],
					'InternalStatus'=>$data['category_internalstatus'], 
					'Status'=>$data['category_status'], 
					'DateAdded'=>$current_date,  
					'DateUpdated'=>$current_date
				 );   
				 
				$last_id = $this->banks->manageActivity_("csa_bank_category", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>ucwords($data['hidden_acategory'])." category added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding ".$data['hidden_acategory']." category!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['category_name'],       
					'Description'=>$data['category_desc'], 
					'ShowInInternal'=>$data['category_showininternal'],  
					'Assignee'=>$data['category_assignee'],  
					'InternalStatus'=>$data['category_internalstatus'], 
					'Status'=>$data['category_status'],  
					'DateUpdated'=>$current_date
				 );
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.CategoryID ='=>$data[hidden_acategoryid], 'a.Category ='=>$data[hidden_acategory]);  
				 $old = $this->banks->getCategoryById_($conditions_array);	
				 
				 $new_showinternaltxt = ($data[category_showininternal] == '1')?"Yes":"No";
				 $old_showinternaltxt = ($old->ShowInInternal == '1')?"Yes":"No"; 
				 
				 $changes .= ($data['category_name'] != $old->Name)?"Category Name changed to ".$data['category_name']." from ".$old->Name."|||":"";    
				 $changes .= ($data['category_desc'] != $old->Description)?"Description changed to ".$data['category_desc']." from ".$old->Description."|||":"";  
				 $changes .= ($data['category_showininternal'] != $old->ShowInInternal)?"Show in Internal changed to ".$new_showinternaltxt." from ".$old_showinternaltxt."|||":"";    			 $changes .= ($data['category_assignee'] != $old->Assignee)?"Assignee changed to ".$data['hidden_aasignee']." from ".$old->Assignee."|||":"";  
				 $changes .= ($data['category_internalstatus'] != $old->InternalStatus)?"Internal Status changed to ".$data['category_internalstatus']." from ".$old->InternalStatus."|||":"";     
				 $changes .= ($data['category_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";       
				 
				 if($changes != "")
				  {
					$x = $this->banks->manageActivity_("csa_bank_category", $post_data, $action, "CategoryID", $this->input->post("hidden_acategoryid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>ucwords($data['hidden_acategory'])." category updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating ".$data['hidden_acategory']." category!");
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
		$this->depositCategories("withdrawal");
	} 
	//END WITHDRAWAL CATEGORIES
	
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */