<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Manage_Promotions extends MY_Controller {

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
		$this->status_list = array(array("Value"=>0, "Label"=>"Inactive"), array("Value"=>1, "Label"=>"Active"), array("Value"=>9, "Label"=>"Delete"));  
		
		//for formula
		$this->deposit_amt =  1200; 
		$this->bonus = 100/100;
		$this->min_amt = 200; 
		$this->max_amt = 600; 
		$this->reqt = 10; 
		$this->formula = "((\$deposit_amt*\$bonus)<\$min_amt)?0:min((\$bonus*\$deposit_amt), \$max_amt)";  	
		$this->wagering_formula = "round((\$deposit_amt-(min(\$deposit_amt,\$bonus_amt/\$bonus)))+((min(\$deposit_amt,\$bonus_amt/\$bonus))+\$bonus_amt)*\$reqt)";
								  
	}
 	
	public function index()
	{   
		$this->promotionsList();    
	}
	 
	public function promotionsList()
	{    
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		if(restriction_type())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 }
		  					
		$data2 = array("main_page"=>"promotions",    
					   "status_list"=>$this->status_list, 
					   "currencies"=>$this->common->getCurrency_(),
					   "categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where), 
					   "products"=>$this->common->getProductsList(array("Status"=>'1'))
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Promotions", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('header',$data);
		$this->load->view('header_nav');
		$this->load->view('promotions/manage_promotions_tpl');
		$this->load->view('footer');   
		 
	}  
	
	public function getPromotionsList()
	{
		if(!admin_access() && !manage_promotion()) 
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
		   
		$search_string = "";   
		
		if(trim($data[s_id]))
		 {
			$search_string .= " AND (a.PromotionID='".$this->common->escapeString_($data[s_id])."') "; 
			$search_url .= "&s_id=".trim($data[s_id]);   
		 }
		
		if(trim($data[s_bonuscode]) != '')
		 {
			$search_string .= " AND (a.BonusCode='".$this->common->escapeString_(trim($data[s_bonuscode]), true)."') ";  
			$search_url .= "&s_bonuscode=".trim($data[s_bonuscode]); 
		 }
		 
		if(trim($data[s_name]))
		 {
			$search_string .= " AND (a.Name LIKE '%".$this->common->escapeString_(trim($data[s_name]))."%') "; 
			$search_url .= "&s_name=".trim($data[s_name]); 
		 }
		  
		if(trim($data[s_currency]) != '')
		 {
			$search_string .= " AND (a.CurrencyID='".$this->common->escapeString_(trim($data[s_currency]))."') ";  
			$search_url .= "&s_currency=".trim($data[s_currency]); 
		 }
		
		if(trim($data[s_category]) != '')
		 {
			$search_string .= " AND (a.CategoryID='".$this->common->escapeString_(trim($data[s_category]))."') ";  
			$search_url .= "&s_category=".trim($data[s_category]); 
		 }
		 
		if(trim($data[s_product]) != '')
		 {
			$search_string .= " AND (a.ProductID='".$this->common->escapeString_(trim($data[s_product]))."') ";  
			$search_url .= "&s_product=".trim($data[s_product]); 
		 }  
		
		if(trim($data[s_subproduct]) != '')
		 {
			$search_string .= " AND (a.SubProductID='".$this->common->escapeString_(trim($data[s_subproduct]))."') ";  
			$search_url .= "&s_subproduct=".trim($data[s_subproduct]); 
		 }  
		
		if(trim($data[s_foruser]) != '')
		 {
			$search_string .= " AND (a.ForUserType='".$this->common->escapeString_(trim($data[s_foruser]))."') ";  
			$search_url .= "&s_foruser=".trim($data[s_foruser]); 
		 } 
		  
		if(trim($data[s_status]) != '')
		 {
			$search_string .= " AND (a.Status='".$this->common->escapeString_(trim($data[s_status]))."') ";  
			$search_url .= "&s_status=".trim($data[s_status]); 
		 } 
		else
		 {
			if(!admin_access())
			{
				$search_string .= " AND (a.Status<>'9' )";	
			}		 
		 }
		 
		
		
		
		$per_page = 20;  
		
		$page = ($data['s_page'])? $data['s_page'] : 0;
		$total_rows = $this->common->countRecords_($search_string, "csa_promotions AS a", "a.PromotionID")->TotalCount; 
	 	 
		$problems = $this->promotions->getPromotionsList_($search_string, $paging=array("limit"=>$per_page, "page"=>$page)); 
	 	
		$pagination_options = array("link"=>"",//base_url()."access/activities", 
								 "total_rows"=>$total_rows, 
								 "per_page"=>$per_page, 
								 "cur_page"=>$page
								); 
		
		$of_str = (($page + $per_page) <= $total_rows)?$page + $per_page:$total_rows;  
		$disp_page = ($page==0)?1:$page+1; 
		$plural_txt = ($total_rows > 1)?"promotions":"promotion";
		$pagination_string = ($total_rows > 0)?"Showing ".$disp_page. " to ".$of_str." of ".$total_rows." ".$plural_txt:""; 
				 
		if($actual == 1)//
		 {  
		 	 $return = array("promotions"=>$problems, 
						"pagination"=>create_pagination($pagination_options), 
						"pagination_string"=>$pagination_string
					   );
		 	 return $return;  
		 }
		else
		 {
			$return = array("promotions"=>$this->generateHtmlPromotionsList($problems), 
						    "pagination"=>create_pagination($pagination_options), 
							"pagination_string"=>$pagination_string
					   );
			 echo json_encode($return); 
		 }
		
	} 
	
	public function generateHtmlPromotionsList($promotions)
	{
		$return = "";  
		/*<td class=\"center\" >".str_pad($promotion->PromotionID,4,'0', STR_PAD_LEFT)."</td>*/
		if(count($promotions))
		 { 
			foreach($promotions as $row=>$promotion){   
				 
				$pending_class = (date("Y-m-d") < date("Y-m-d", strtotime($row[StartedDate])))?"orange":"";
				$expire_class = (date("Y-m-d") > date("Y-m-d", strtotime($row[EndDate])))?"danger":""; 
				$inactive_class = ($row[Status] != '1')?"danger":""; 
				 
				$status = ($promotion->Status=='0' || $promotion->Status=='9')?"<span class=\"act-danger\" >{$promotion->StatusName}</span>":$promotion->StatusName;
				$start_date = (date("Y-m-d") < date("Y-m-d", strtotime($promotion->StartedDate)))?"<span class=\"orange tip\" title=\"pending\" >".date("Y-m-d", strtotime($promotion->StartedDate))."</span>":date("Y-m-d", strtotime($promotion->StartedDate)); 
				
				$end_date = (date("Y-m-d") > date("Y-m-d", strtotime($promotion->EndDate)))?"<span class=\"act-danger tip\" title=\"expired\" >".date("Y-m-d", strtotime($promotion->EndDate))."</span>":date("Y-m-d", strtotime($promotion->EndDate));
				
				$return .= "
						<tr class=\"activity_row\" id=\"PromotionRow{$promotion->PromotionID}\" >  
							<td class=\"center\" >{$promotion->BonusCode}</td>
							<td >{$promotion->Name}</td> 
							<td class=\"center\" >{$promotion->CurrencyName}</td> 
							<td class=\"center\" >{$promotion->CategoryName}</td> 
							<td class=\"center\" >{$promotion->ProductName}</td> 
							<td class=\"center\" >{$start_date}</td>  
							<td class=\"center\" >{$end_date}</td>  
							<td class=\"center\" >".date("Y-m-d H:i:s", strtotime($promotion->DateUpdated))."</td>   
							<td class=\"center\" >{$promotion->UpdatedByNickname}</td>
							<td class=\"center {$inactive_class}\" >{$status}</td>  
							<td class=\"center action\" >		
							";	 
				//$this->session->userdata("mb_usertype") == "super_admin"
				if(($promotion->Status != '9' && $promotion->Status != 9))$return .= "<a href=\"#PromotionModal\" title=\"edit promotion\" alt=\"edit promotion\" class=\"edit_promotion tip\" promotion-id=\"{$promotion->PromotionID}\"  id=\"Edit{$promotion->PromotionID}\" data-toggle=\"modal\" ><i class=\"icon16 i-pencil-6 gap-left0 gap-right10\" ></i></a>";
								
				$return .= "
							</td>
						</tr> ";
			}//end foreach
								 
		 }
		else
		 {
			 $return = "
			 			<tr class=\"activity_row\"  > 
							<td class=\"center\" colspan=\"12\"  >No promotion found!</td>
						</tr>
			 			";
		 }
		
		return $return; 
	}
	 
	
	public function popupManagePromotion()
	{   
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Direct access not permitted.", "403");
		 	return false; 
		 } 
		  
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 }
		
		$categories_where = " AND a.Status='1' "; 
   		if(restriction_type())
		 { 
			$categories_where .= " AND (FIND_IN_SET('".$this->session->userdata('mb_usertype')."', a.Viewers) ) ";
		 }
		 
		   
		$promotion_id = trim($this->uri->segment(3));
		 
		
		$conditions_array = array('a.PromotionID =' => $promotion_id); 
		$promotion = ($promotion_id)?$this->promotions->getPromotionById_($conditions_array):""; 
		
		$data2 = array("main_page"=>"promotions",     
					   "status_list"=>$this->status_list, 
					   "currencies"=>$this->common->getCurrency_(),
					   "products"=>$this->common->getProductsList(array("Status"=>'1')), 
					   "categories"=>$this->promotions->getPromotionsCategoriesList_($categories_where),
					   "promotion"=>$promotion
					  );
		$sidebar = "sidebar"; //(admin_access() &&  ($this->session->userdata("mb_pageview") == "approvers") )?"sidebar":"sidebar"; 
					   
		$data = array("page_title"=>"12Bet - CAL - Manage Promotion", 
					  "sidebar_view"=>$this->load->view($sidebar, $data2,true)
					 );
		$this->load->view('promotions/manage_promotion_popup_tpl',$data);  
		 
	} 
	
	
	public function managePromotion()
	{	
		if(!$this->input->is_ajax_request()) 
		 {
			error_page("404 Page Not Found", "404 Page Not Found", "The page you requested was not found. Check URL. ", "404");
		 	return false; 
		 } 
		
		if(!admin_access() && !manage_promotion()) 
		 {
			error_page("403 Access forbidden", "403 Access forbidden", "403 access to this page is forbidden. Ask admin.", "403");
		 	return false; 
		 } 
		 
		   
		$error = "";   
		$data = $this->input->post(); 
		$data['pro_webpromotion'] = ($data['pro_webpromotion'] == "")?0:$data['pro_webpromotion'];   
		 
		if($data[pro_name] == "")
		 {
			 $error .= "Enter promotion name!<br> ";
		 } 
		
		if($data[pro_bonuscode] == "")
		 {
			 $error .= "Enter bonus code!<br>";
		 }
		
		if($data[pro_type] == "")
		 {
			 $error .= "Select type!<br>";
		 }
		  
		if($data[pro_startdate] == "")
		 {
			 $error .= "Select start date!<br> ";
		 }
		
		if($data[pro_enddate] == "")
		 {
			 $error .= "Select end date!<br>";
		 }
		
		if($data[pro_currency] == "")
		 {
			 $error .= "Select currency!<br>";
		 }
		
		if($data[pro_product] == "")
		 {
			 $error .= "Select product!<br>";
		 }
		
		if($data[pro_category] == "")
		 {
			 $error .= "Select category!<br>";
		 }
		 
		if($data[pro_status] == "")
		 {
			 $error .= "Select status!<br>";
		 }
		
		if($data[pro_minimum] == "")
		 {
			 $error .= "Enter minimum amount!<br>";
		 }
		
		if($data[pro_maximum] == "")
		 {
			 $error .= "Enter maximum amount!<br>";
		 }
		
		if($data[pro_turnover] == "")
		 {
			 $error .= "Enter turnover";
		 } 
		
		if($data[pro_bonusrate] == "")
		 {
			 $error .= "Enter bonus rate!<br>";
		 }
		
		if($data[pro_forusertype] == "")
		 {
			 $error .= "Select user type to use!<br>";
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
					'Name'=>$data['pro_name'], 
					'Type'=>$data['pro_type'], 
					'Description'=>$data['pro_description'], 
					'Terms'=>$data['pro_terms'], 
					'CurrencyID'=>$data['pro_currency'], 
					'ProductID'=>$data['pro_product'], 
					'SubProductID'=>$data['pro_subproduct'], 
					'CategoryID'=>$data['pro_category'], 
					'Formula'=>$this->formula,
					'WageringFormula'=>$this->wagering_formula, 
					'MinimumAmount'=>$data['pro_minimum'], 
					'MaximumAmount'=>$data['pro_maximum'], 
					'Turnover'=>$data['pro_turnover'], 
					'BonusRate'=>$data['pro_bonusrate'], 
					'BonusCode'=>$data['pro_bonuscode'], 
					'StartedDate'=>$data['pro_startdate'], 
					'EndDate'=>$data['pro_enddate'], 
					'Status'=>$data['pro_status'], 
					'CreatedBy'=>$this->session->userdata("mb_no"),
					'DateAdded'=>$current_date,
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date, 
					'IsWebPromotion'=>$data['pro_webpromotion'], 
					'ForUserType'=>$data['pro_forusertype']
				 );   
				 
				$last_id = $this->promotions->manageActivity_("csa_promotions", $post_data, $action, '', '');
				if($last_id > 0)
				 { 
					 $return = array("success"=>1, "message"=>"Promotion added successfully.", "is_change"=>1);    
				 }
				else
				 {
					 $return = array("success"=>0, "message"=>"Error adding promotion!");
				 }   
				 
			 }
			else
			 {    
				 $post_data = array(      
					'Name'=>$data['pro_name'], 
					'Type'=>$data['pro_type'], 
					'Description'=>$data['pro_description'], 
					'Terms'=>$data['pro_terms'], 
					'CurrencyID'=>$data['pro_currency'], 
					'ProductID'=>$data['pro_product'],   
					'SubProductID'=>$data['pro_subproduct'], 
					'CategoryID'=>$data['pro_category'],  
					//'Formula'=>$this->formula, 
					//'WageringFormula'=>$this->wagering_formula, 
					'MinimumAmount'=>$data['pro_minimum'], 
					'MaximumAmount'=>$data['pro_maximum'], 
					'Turnover'=>$data['pro_turnover'], 
					'BonusRate'=>$data['pro_bonusrate'], 
					'BonusCode'=>$data['pro_bonuscode'], 
					'StartedDate'=>$data['pro_startdate'], 
					'EndDate'=>$data['pro_enddate'], 
					'Status'=>$data['pro_status'],  
					'UpdatedBy'=>$this->session->userdata("mb_no"), 
					'DateUpdated'=>$current_date, 
					'IsWebPromotion'=>$data['pro_webpromotion'], 
					'ForUserType'=>$data['pro_forusertype']
				 );  
				 
				 $changes = ""; 
				  
				 $conditions_array = array('a.PromotionID ='=>$data[hidden_apromotionid]);  
				 $old = $this->promotions->getPromotionById_($conditions_array);	
				 
				 $new_webpromotiontxt = ($data['pro_webpromotion'] == 1)?"True":"False";
				 $old_webpromotiontxt = ($old->IsWebPromotion == 1)?"True":"False";
				 
				 $changes .= ($data['pro_name'] != $old->Name)?"Promotion Name changed to ".$data['pro_name']." from ".$old->Name."|||":"";    
				 $changes .= ($data['pro_bonuscode'] != $old->BonusCode)?"Bonus Code changed to ".$data['pro_bonuscode']." from ".$old->BonusCode."|||":"";    
				 $changes .= ($data['pro_type'] != $old->Type)?"Type changed to ".$data['pro_type']." from ".$old->Type."|||":"";    
				 $changes .= ($data['pro_description'] != $old->Description)?"Description changed to ".$data['pro_description']." from ".$old->Description."|||":""; 
				 $changes .= ($data['pro_terms'] != $old->Terms)?"Terms changed to ".$data['pro_terms']." from ".$old->Terms."|||":""; 
				 $changes .= ($data['pro_startdate'] != $old->StartedDate)?"Start Date changed to ".$data['pro_startdate']." from ".$old->StartedDate."|||":""; 
				 $changes .= ($data['pro_enddate'] != $old->EndDate)?"End Date changed to ".$data['pro_enddate']." from ".$old->EndDate."|||":""; 
				 $changes .= ($data['pro_currency'] != $old->CurrencyID)?"Currency changed to ".$data['hidden_acurrency']." from ".$old->CurrencyName."|||":""; 
				 $changes .= ($data['pro_product'] != $old->ProductID)?"Product changed to ".$data['hidden_aproduct']." from ".$old->ProductName."|||":""; 
				 $changes .= ($data['pro_subproduct'] != $old->SubProductID)?"Sub Product changed to ".$data['hidden_asubproduct']." from ".$old->SubProductName."|||":""; 
				 $changes .= ($data['pro_category'] != $old->CategoryID)?"Category changed to ".$data['hidden_acategory']." from ".$old->CategoryName."|||":""; 
				 $changes .= ($data['pro_status'] != $old->Status)?"Status changed to ".$data['hidden_astatus']." from ".$old->StatusName."|||":"";     
				 
				 $changes .= ($data['pro_minimum'] != $old->MinimumAmount)?"Minimum changed to ".$data['pro_minimum']." from ".$old->MinimumAmount."|||":"";    
				 $changes .= ($data['pro_maximum'] != $old->MaximumAmount)?"Maximu changed to ".$data['pro_maximum']." from ".$old->MaximumAmount."|||":"";    
				 $changes .= ($data['pro_turnover'] != $old->Turnover)?"Turnover changed to ".$data['pro_turnover']." from ".$old->Turnover."|||":"";    
				 $changes .= ($data['pro_bonusrate'] != $old->BonusRate)?"Bonus Rate changed to ".$data['pro_bonusrate']." from ".$old->BonusRate."|||":"";    
				 
				 $changes .= ($data['pro_webpromotion'] != $old->IsWebPromotion)?"Display in Web Promotion changed to ".$new_webpromotiontxt." from ".$old_webpromotiontxt."|||":"";    			 $changes .= ($data['pro_forusertype'] != $old->IsWebPromotion)?"User Type to use changed to ".$data['pro_forusertype']." from ".$old->ForUserType."|||":"";    
							 
				 if($changes != "")
				  {
					$x = $this->promotions->manageActivity_("csa_promotions", $post_data, $action, "PromotionID", $this->input->post("hidden_apromotionid"));	 
					if($x > 0)
					 {  
						$return = array("success"=>1, "message"=>"Promotion updated successfully.", "is_change"=>1);   
					 }
					else
					 { 
						  array("success"=>0, "message"=>"Error updating promotion!");
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
	 
	
	public function getSubProductList()
	{  
		$where_arr = array("a.Status ="=>'1');   
		
		if(trim($this->input->post('product'))) $where_arr['a.MainProductID ='] = trim($this->input->post('product'));  
		//if(trim($this->input->post('default'))) $where_or['a.SubID ='] = trim($this->input->post('default'));
		
		$x = $this->common->getSubProductsList_($where_arr); 
		 
		echo  json_encode($x); 
	} 
	 
}

/* End of file deposit_mehods.php */
/* Location: ./application/controllers/deposit_mehods.php */