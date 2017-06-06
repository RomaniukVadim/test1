<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Result_Categories extends MY_Controller {

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
		$this->resultCategoriesList();    
	}
	 
	public function resultCategoriesList()
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
		 			   
		$data = array("page_title"=>"12Bet - CAL - Result Categories", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('manage/result_categories_tpl');
		$this->load->view('footer');   
	}  
	
	
	public function getResultCategoriesList()
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
		
		$search_string = trim(trim($search_string), "AND"); 
 
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0; 
	 	 
		$return = $this->manage->getResultCategoriesList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	   	
		$total_rows = $return[total_rows]; 
		$categorys = $return[results]; 
				
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"results":"result";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("results"=>$categorys, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("results"=>$this->generateHtmlResultCategoriesList($categorys), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlResultCategoriesList($categorys)
	{
		$return = ""; 
		if(count($categorys))
		 { 
			foreach($categorys as $row=>$category){   
				$status = ($category->Status=='0' || $category->Status=='9')?"<span class=\"act-danger\" >Inactive</span>":"Active";
				$date_updated = ($category->DateUpdated != "0000-00-00 00:00:00")?date("Y-m-d H:i:s", strtotime($category->DateUpdated)):'';
				$return .= "
						<tr class=\"result_row\" id=\"CategoryRow{$category->CategoryID}\" > 
							<td class=\"center\" >".str_pad($category->CategoryID,4,'0', STR_PAD_LEFT)."</td>
							<td >{$category->Name}</td>   
							<td class=\"center\" >{$date_updated}</td>  
							<td class=\"center\" >{$status}</td>  
							<td class=\"center action\" >			 
								<a href=\"#CallResultModal\" title=\"edit result\" alt=\"edit result\" class=\"edit_result tip\" category-id=\"{$category->CategoryID}\"  id=\"Edit{$category->result_id}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"5\" >No result category found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	
	
	public function popupManageResultCategory()
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
		 
		$conditions_array = array('a.CategoryID =' => $category_id); 
		$category = ($category_id)?$this->manage->getResultCategoryById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"manage",     
					   "status_list"=>$this->status_list,  
					   "results"=>$this->common->getResultList_(array("a.result_status =" => '1')),  
					   "category"=>$category 
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Call Result", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('manage/result_category_popup_tpl',$data);  
		 
	} 
	
	
	public function manageResultCategory()
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
			 $error .= "Enter category name!<br>";
		 }
		
		if($data[category_result] == "")
		 {
			 $error .= "Select result!<br>";
		 }
		  
		if($data[category_status] == "")
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
					'Name'=>$data['category_name'],  
					'Description'=>$data['category_description'],    
					'Result'=>$data['category_result'], 
					'AddedBy'=>$this->session->userdata("mb_no"), 
					'DateAdded'=>$current_date,
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date,
					'Status'=>$data['category_status']    
				 );   
				 
				$last_id = $this->manage->manageSettings_("csa_result_categories", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Result category added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding result category!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['category_name'],  
					'Description'=>$data['category_description'],    
					'Result'=>$data['category_result'],  
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date,
					'Status'=>$data['category_status'] 
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.CategoryID ='=>$data[hidden_acategoryid]);  
				 $old = $this->manage->getResultCategoryById_($conditions_array);	
				 
				 $changes .= ($data['category_name'] != $old->Name)?"Category Name changed to ".$data['category_name']." from ".$old->Name."|||":"";  
				 $changes .= ($data['category_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 $changes .= ($data['category_result'] != $old->Result)?"Result changed to ".$data['hidden_aresult']." from ".$old->ResultName."|||":"";     
				 
				 if($changes != "")
				  {
					$x = $this->manage->manageSettings_("csa_result_categories", $post_data, $action, "CategoryID", $this->input->post("hidden_acategoryid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Result category updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating result category!");
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