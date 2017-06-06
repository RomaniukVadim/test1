<?
include_once("./_common.php");
include_once("../Classes/PHPExcel.php"); 

//check if user is allowed to access this page
if(!allowExportData())die("You are not allowed to access this page.");	

$action = trim($_GET['action']);

switch($action){
	
	case "promotion_activities":
		json_encode(promotion_activities($_REQUEST));
		break;	
	
	case "bank_activities":
		bank_activities($_REQUEST);
		break;
	
	case "casino_issues":
		json_encode(casino_issues($_REQUEST));
		break;
	
	case "account_issues":
		json_encode(account_issues($_REQUEST));
		break;
	
	case "suggestions_complaints":
		json_encode(suggestions_complaints($_REQUEST));
		break;
	
	case "website_mobile":
		json_encode(website_mobile($_REQUEST));
		break;
		
	case "qq_check":
		json_encode(qq_check($_REQUEST));
		break;
	
	case "casino_check":
		json_encode(casino_check($_REQUEST));
		break;
	
	case "indo_check":
		json_encode(indo_check($_REQUEST));
		break;
	
	case "malaysia_check":
		json_encode(malaysia_check($_REQUEST));
		break;
	
	case "thailand_check":
		json_encode(thailand_check($_REQUEST));
		break;
	
	case "vietnam_check":
		json_encode(vietnam_check($_REQUEST));
		break;
	
	case "china_check":
		json_encode(china_check($_REQUEST));
		break;
	
	case "website_check":
		json_encode(website_check($_REQUEST));
		break;
	
	case "search_activities":
		json_encode(search_activities($_REQUEST));
		break;
	
	case "shift_report":
		shift_report($_REQUEST);
		break;
													 	 
	default:
		echo json_encode(array("msg"=>"Invalid Request",
						"has_err"=>1));
}

 
 
/** Filters **/
function website_mobile($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel";
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency']; 
	$s_username = $get['s_username'];
	$s_source = $get['s_source']; 
	$s_product = $get['s_product'];
	$s_problem = $get['s_problem'];
	$s_udate = $get['s_udate'];
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent'];
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = ""; 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_source))
	 {
		$search_string .= " AND (a.Source='".mysql_real_escape_string($s_source)."') "; 
		$search_url .= "&s_source=".$s_source;
	 }
	 
	if(trim($s_product))
	 {
		$search_string .= " AND (a.Product='".mysql_real_escape_string($s_product)."') "; 
		$search_url .= "&s_product=".$s_product;
	 } 
	 
	if(trim($s_problem))
	 {
		$search_string .= " AND (a.Problem='".mysql_real_escape_string($s_problem)."') "; 
		$search_url .= "&s_problem=".$s_problem;
	 }
	 
	/*if(trim($s_udate))
	 {
		$search_string .= " AND (DATE(a.DateUpdated)=DATE('".mysql_real_escape_string($s_udate)."')) "; 
		$search_url .= "&s_udate=".$s_udate;
	 } */
	
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	 
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 } 
	
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (g.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 } 
	  
	$page = ($page <= 0 || $page == "")?1:$page;  
	$start = 0; 
	$limit = 20; 
	 
	if($page)
		$start = ($page - 1) * $limit; 
	else
		$start = 0; 
		 
	$sql = " SELECT a.*, b.Abbreviation AS Currency, c.Name AS ProblemName, d.Source AS ActivitySource, 
					e.Name AS StatusName, f.Name AS ProductName, g.mb_nick, h.Name AS ProductName  
			 FROM csa_website_access AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN csa_access_problems AS c ON a.Problem=c.ProblemID 
				LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID 
				LEFT JOIN csa_products AS f ON a.Product=f.ProductID  
				LEFT JOIN g4_member AS g ON a.UpdatedBy=g.mb_no 
				LEFT JOIN csa_products AS h ON a.Product=h.ProductID
			 WHERE a.ActivityID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "Currency", "Username", "Product", "Problem", "E-Support ID", "Source", 
							"Phone Service Provider", "IP Address", "Location", "ISP", "Used URL", "Used Browser", "Error Message", 
							"Status", "Remarks");
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "Username", "ProductName", "ProblemName", "ESupportID", "Source", 
							"PSP", "IPAddress", "Location", "ISP", "UsedURL", "UsedBrowser", "ErrorMessage", 
							"StatusName", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}

 
/** Filters **/
function promotion_activities($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_category = $get['s_category'];
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency']; 
	$s_username = $get['s_username'];
	$s_source = $get['s_source']; 
	$s_product = $get['s_product'];
	$s_promotion = $get['s_promotion'];
	$s_udate = $get['s_udate'];
	$s_transactionid = $get['s_transactionid'];
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent']; 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));
  
	$search_string = "";  
	
	if(trim($s_category))
	 {
		$search_string .= " AND (a.Category='".mysql_real_escape_string($s_category)."') "; 
		$search_url .= "&s_category=".$s_category;
	 }
	 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_source))
	 {
		$search_string .= " AND (a.Source='".mysql_real_escape_string($s_source)."') "; 
		$search_url .= "&s_source=".$s_source;
	 }
	 
	if(trim($s_promotion))
	 {
		$search_string .= " AND (a.Promotion='".mysql_real_escape_string($s_promotion)."') "; 
		$search_url .= "&s_promotion=".$s_promotion;
	 }
	 
	/*if(trim($s_udate))
	 {
		$search_string .= " AND (DATE(a.DateUpdated)=DATE('".mysql_real_escape_string($s_udate)."')) "; 
		$search_url .= "&s_udate=".$s_udate;
	 } */
	
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	
	if(trim($s_transactionid))
	 {
		$search_string .= " AND (a.TransactionID='".$s_transactionid."') ";  
		$search_url .= "&s_transactionid=".$s_transactionid;
	 }
	  
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 }  
	
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (f.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 }  
	 
		 
	$sql = " SELECT a.*, b.Abbreviation AS Currency, c.Name AS PromotionName, d.Source AS ActivitySource, e.Name AS StatusName,  
					f.mb_nick, g.Name AS ProductName, h.Name AS CategoryName
			 FROM csa_promotion_activities AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN csa_promotions AS c ON a.Promotion=c.PromotionID
				LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID  
				LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no 
				LEFT JOIN csa_products AS g ON a.Product=g.ProductID
				LEFT JOIN csa_promotion_categories AS h ON a.Category=h.CategoryID
			 WHERE a.ActivityID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";
		  
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result); 
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "Currency", "Username", "Product", "Category Name", "Promotion", "System ID", "E-Support ID", "Source", "System ID", "Transaction ID", 
							"Current Balance", "Outstanding Bets", "Deposit Amount", "Bonus Amount", "Wagering Amount", "Turnover Amount", "Cashback Amount",  
							"Status", "Agent Remarks", "Risk Management Remarks", "Management Remarks");
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "Username", "ProductName", "CategoryName", "PromotionName", "SystemID", "ESupportID", "ActivitySource", "SystemeID", "TransactionID", 
							"CurrentBalance", "OutstandingBets", "DepositAmount", "BonusAmount", "WageringAmount", "TurnoverAmount", "CashbackAmount",  
							"StatusName", "Remarks", "RMRemarks", "MRemarks" );   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
} 


/** Filters **/
function bank_activities($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency']; 
	$s_username = $get['s_username'];
	$s_source = $get['s_source']; 
	$s_methodtype = $get['s_methodtype'];
	$s_method = $get['s_method'];
	$s_udate = $get['s_udate'];
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent'];
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));
	
	$search_string = ""; 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_source))
	 {
		$search_string .= " AND (a.Source='".mysql_real_escape_string($s_source)."') "; 
		$search_url .= "&s_source=".$s_source;
	 }
	 
	if(trim($s_methodtype))
	 {
		$search_string .= " AND (a.Category='".mysql_real_escape_string($s_methodtype)."') "; 
		$search_url .= "&s_methodtype=".$s_methodtype;
	 }
	
	if(trim($s_method))
	 {
		$search_string .= " AND (a.CategoryId='".mysql_real_escape_string($s_method)."') "; 
		$search_url .= "&s_method=".$s_method;
	 }
	 
	/*if(trim($s_udate))
	 {
		$search_string .= " AND (DATE(a.DateUpdated)=DATE('".mysql_real_escape_string($s_udate)."')) "; 
		$search_url .= "&s_udate=".$s_udate;
	 } */ 
	 
	 if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 }
	 
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (f.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 }  
	 
		 
	$sql = " SELECT a.*, b.Abbreviation AS Currency, c.Name AS Method, d.Source AS ActivitySource, e.Name AS StatusName, f.mb_nick 
			 FROM csa_bank_activities AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN csa_bank_category AS c ON a.CategoryID=c.CategoryID
				LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no   
			 WHERE a.ActivityID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";
		  
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result); 
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "Currency", "Username", "E-Support ID", "Source", "Category", "Method", "Transaction ID", "Amount",    
							"Last Updated By", "Status", "Agent Remarks");
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "Username", "ESupportID", "ActivitySource", "Category", "Method", "TransactionID", "Amount", 
							"mb_nick", "StatusName", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters **/
function casino_issues($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency']; 
	$s_username = $get['s_username'];
	$s_source = $get['s_source']; 
	$s_subproductid = $get['s_subproductid']; 
	$s_category = $get['s_category']; 
	$s_udate = $get['s_udate'];
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent'];
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));
	
	$search_string = ""; 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_source))
	 {
		$search_string .= " AND (a.Source='".mysql_real_escape_string($s_source)."') "; 
		$search_url .= "&s_source=".$s_source;
	 }
	 
	if(trim($s_subproductid))
	 {
		$search_string .= " AND (a.SubProductID='".mysql_real_escape_string($s_subproductid)."') "; 
		$search_url .= "&s_subproductid=".$s_subproductid;
	 } 
	 
	if(trim($s_category))
	 {
		$search_string .= " AND (a.IssueCategory='".mysql_real_escape_string($s_category)."') "; 
		$search_url .= "&s_category=".$s_category;
	 }
	 
	/*if(trim($s_udate))
	 {
		$search_string .= " AND (DATE(a.DateUpdated)=DATE('".mysql_real_escape_string($s_udate)."')) "; 
		$search_url .= "&s_udate=".$s_udate;
	 }*/ 
	
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	 
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 } 
	
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (f.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 }  
	 
	$sql = " SELECT a.*, b.Abbreviation AS Currency, c.Name AS CategoryName, d.Source AS ActivitySource, e.Name AS StatusName, 
					f.mb_nick, g.Name AS SubProduct  
			 FROM csa_casino_issues AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN csa_issues_category AS c ON a.IssueCategory=c.CategoryID
				LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID  
				LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no  
				LEFT JOIN csa_sub_products AS g ON a.SubProductID=g.SubID 
			 WHERE a.ActivityID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";
		  
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result); 
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "Currency", "Username", "E-Support ID", "Source", "Casino Product", "Category", "Transaction ID",   
							"Last Updated By", "Status", "Agent Remarks", "Risk Management Remarks", "Management Remarks");
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "Username", "ESupportID", "ActivitySource", "SubProduct", "CategoryName", "TransactionID", 
							"mb_nick", "StatusName", "Remarks", "MRemarks", "RMRemarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}

/** Filters **/
function account_issues($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency']; 
	$s_username = $get['s_username'];
	$s_source = $get['s_source']; 
	$s_accountproblem = $get['s_accountproblem']; 
	$s_udate = $get['s_udate'];
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent'];
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));
	
	$search_string = ""; 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_source))
	 {
		$search_string .= " AND (a.Source='".mysql_real_escape_string($s_source)."') "; 
		$search_url .= "&s_source=".$s_source;
	 }
	 
	/*if(trim($s_product))
	 {
		$search_string .= " AND (a.Product='".mysql_real_escape_string($s_product)."') "; 
		$search_url .= "&s_product=".$s_product;
	 }*/ 
	 
	if(trim($s_accountproblem))
	 {
		$search_string .= " AND (a.AccountProblem='".mysql_real_escape_string($s_accountproblem)."') "; 
		$search_url .= "&s_accountproblem=".$s_accountproblem;
	 }
	 
	/*if(trim($s_udate))
	 {
		$search_string .= " AND (DATE(a.DateUpdated)=DATE('".mysql_real_escape_string($s_udate)."')) "; 
		$search_url .= "&s_udate=".$s_udate;
	 }*/ 
	
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	 
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 } 
	
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (g.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 }  
	  
	$sql = " SELECT a.*, b.Abbreviation AS Currency, c.ProblemName, d.Source AS ActivitySource, e.Name AS StatusName, 
					f.Name AS ProductName, g.mb_nick 
			 FROM csa_account_issues AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN csa_account_problems AS c ON a.AccountProblem=c.ProblemID
				LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID
				LEFT JOIN csa_products AS f ON a.Product=f.ProductID 
				LEFT JOIN g4_member AS g ON a.UpdatedBy=g.mb_no 
			 WHERE a.ActivityID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";
		  
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result); 
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "Currency", "Username", "E-Support ID", "Source", "Account Problem", 
							 "Bet Limit Per Bet", "Bet Limit Per Match", "Proposed Limit Per Bet", "Proposed Limit Per Match",
							 "Revised Limit Per Bet", "Revised Limit Per Match", "Last Updated By", "Status", "Agent Remarks" );
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "Username", "ESupportID", "ActivitySource", "ProblemName", 
						   "BetLimitPerBet", "BetLimitPerMatch", "ProposedLimitPerBet", "ProposedLimitPerMatch", 
						   "RevisedLimitPerBet", "RevisedLimitPerMatch", "mb_nick", "StatusName", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters **/
function suggestions_complaints($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency']; 
	$s_username = $get['s_username'];
	$s_source = $get['s_source']; 
	$s_product = $get['s_product'];
	$s_complainttype = $get['s_complainttype']; 
	$s_udate = $get['s_udate'];
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent'];
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));
	
	$search_string = ""; 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_source))
	 {
		$search_string .= " AND (a.Source='".mysql_real_escape_string($s_source)."') "; 
		$search_url .= "&s_source=".$s_source;
	 }
	 
	if(trim($s_product))
	 {
		$search_string .= " AND (a.Product='".mysql_real_escape_string($s_product)."') "; 
		$search_url .= "&s_product=".$s_product;
	 } 
	 
	if(trim($s_complainttype))
	 {
		$search_string .= " AND (a.ComplaintType='".mysql_real_escape_string($s_complainttype)."') "; 
		$search_url .= "&s_complainttype=".$s_complainttype;
	 }
	 
	/*if(trim($s_udate))
	 {
		$search_string .= " AND (DATE(a.DateUpdated)=DATE('".mysql_real_escape_string($s_udate)."')) "; 
		$search_url .= "&s_udate=".$s_udate;
	 } */
	 
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 } 
	
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (g.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 } 
	 
	$sql = " SELECT a.*, b.Abbreviation AS Currency, c.Name AS ComplaintName, d.Source AS ActivitySource, e.Name AS StatusName, 
					f.Name AS ProductName, g.mb_nick 
			 FROM csa_suggestions_complaints AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID 
				LEFT JOIN csa_complaints_types AS c ON a.ComplaintType=c.ComplaintID 
				LEFT JOIN csa_source AS d ON a.Source=d.SourceID  
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID
				LEFT JOIN csa_products AS f ON a.Product=f.ProductID  
				LEFT JOIN g4_member AS g ON a.UpdatedBy=g.mb_no 
			 WHERE a.ActivityID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";
		  
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result); 
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("DateAdded", "DateUpdated", "Currency", "Username", "E-Support ID", "Source", "Product", "Complaint", 
							 "Account Blocked", "Account Locked", "Account Closed", "Last Updated By", "Status", "Agent Remarks", "Management Remarks" );
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "Username", "ESupportID", "ActivitySource", "ProductName", "ComplaintName", 
						   "AccountBlocked", "AccountLocked", "AccountClosed", "mb_nick", "StatusName", "Remarks", "MRemarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters qq check **/
function qq_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_qq_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "QQ:1174962231", "QQ:947561425", "QQ:907904934", "QQ:1248790758", "QQ:1104736621", "QQ:1457286523", "VND YM", 
							"INDO ym", "Skype(Thai) Support 1", "Skype(Thai) Support 2", "Skype(Thai) Line", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "QQ117", "QQ947", "QQ907", "QQ124", "QQ110", "QQ145", "VndYm", 
							"IndoYm", "ThaiSupport1", "ThaiSupport2", "LineThai", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters casino check **/
function casino_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_casino_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "Chat Applet Server", "Live Game", "Online Player and Registration", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "ChatAppletServer", "LiveGame", "OLPlayer", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters indo check **/
function indo_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_indo_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "12wang.com", "12bocai.com", "12bopan.com", "12facai.com", "12fada.com", "23121232.com", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "Wang12Com", "Bocai12Com", "Bopan12Com", "Facai12Com", "Fada12Com", "Com23121232", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters malaysia check **/
function malaysia_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_malaysia_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "12wang.com", "12bocai.com", "12bopan.com", "12facai.com", "12fada.com", "23121232.com", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "Wang12Com", "Bocai12Com", "Bopan12Com", "Facai12Com", "Fada12Com", "Com23121232", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters thailand check **/
function thailand_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_thailand_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "12wang.com", "12bocai.com", "12bopan.com", "12facai.com", "12fada.com", "23121232.com", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "Wang12Com", "Bocai12Com", "Bopan12Com", "Facai12Com", "Fada12Com", "Com23121232", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}

/** Filters vietnam check **/
function vietnam_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_vietnam_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "12wang.com", "12bocai.com", "12bopan.com", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "Wang12Com", "Bocai12Com", "Bopan12Com", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters china check **/
function china_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_china_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "12bo.com", "12bocai.com", "12bopan.com", "12facai.com", "12betcn.com", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "Bo12Com", "Bocai12Com", "Bopan12Com", "Facai12Com", "Betcn12Com", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters website check **/
function website_check($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate'])); 
	 
	$search_string = "";   
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateChecked BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
		 
	$sql = " SELECT a.*, b.mb_nick 
			 FROM csa_website_check AS a  
				LEFT JOIN g4_member AS b ON a.CheckedBy=b.mb_no   
			 WHERE a.CheckID <> 0 $search_string 
			 ORDER BY a.DateChecked DESC 
		   ";   
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
    
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Checked", "Home", "Sports", "Casino", "Racebook", "12BET TV", "Mobile", "Promotions", "Affiliates", "Deposit", "Withdrawal", 
							  "Transfer Funds", "Manage Account", "Bet List", "Statement", "Result", "Spostbook Page", "Hot News", "Live chat", "Agent Name", "Remarks");
							
		$data_list = array("DateChecked", "Home", "Sports", "Casino", "Racebook", "Bet12TV", "Mobile", "Promotions", "Affiliates", "Deposit", "Withdrawal", "TransferFunds",
						   "ManageAccount", "BetList", "Statement", "Result", "SportsbookPage", "HotNews", "LiveChat", "mb_nick", "Remarks");   
		export_data($result, $report_type, $header_list, $data_list, $file_name, 1);
	 }
	else
	 {
		return 0; 
	 }
	
}

/** SEARCH ACTIVITIES **/
function search_activities($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency'];
	$s_activity = $get['s_activity']; 
	$s_username = $get['s_username'];  
	$s_concern = urldecode(urlencode($get['s_concern'])); 
	$s_status = $get['s_status'];
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));  
	
	$s_source = $get['s_source']; 
	$s_problem = $get['s_problem'];
	$s_udate = $get['s_udate'];
	$s_agent  = $get['s_agent']; 
	 
	$search_string = ""; 
	if(trim($s_esupportid) != "")
	 {
		$search_string_having .= " AND (ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') ";  
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	
	if(trim($s_currency) != "")
	 {
		$search_string_having .= " AND (Currency='".$s_currency."') ";  
		$search_url .= "s_currency=".$s_currency;
	 }
	  
	/*if(trim($s_activityid))
	 {
		$search_string .= " AND (a.ActivityID='".mysql_real_escape_string($s_activityid)."') "; 
		$search_url .= "&s_activityid=".$s_activityid;
	 }*/ 
	 
	if(trim($s_activity))
	 {
		$search_string .= " AND (a.Activity='".mysql_real_escape_string($s_activity)."') "; 
		$search_url .= "&s_activity=".$s_activity;
	 } 
	
	if(trim($s_concern) != "")
	 {
		$search_string_having .= " AND (CONCERN LIKE '%".mysql_real_escape_string($s_concern)."%') ";  
		$search_url .= "&s_concern=".$s_concern;
	 }
					 
	/*if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateUpdated BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 } */
	
	if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string_having .= " AND (DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 } 
	
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 }
	
	if(trim($s_username) != "")
	 {
		$search_string_having .= " AND (Username LIKE '%".mysql_real_escape_string($s_username)."%') ";  
		$search_url .= "&s_username=".$s_username;
	 
	 } 
	 
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (c.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 }  
	  
	$page = ($page <= 0 || $page == "")?1:$page;  
	$start = 0; 
	$limit = 20; 
	 
	if($page)
		$start = ($page - 1) * $limit; 
	else
		$start = 0; 
		 
	$sql = " SELECT a.ActivityID, a.Status, a.Activity, a.DateUpdated, CONCAT(a.Activity,'-',a.ActivityID) AS HistoryIndex, 
				 a.Remarks, a.RMRemarks, a.SRemarks, a.MRemarks, b.Name AS StatusName, c.mb_nick, 
				 CASE a.Activity  
				   WHEN 'account_issues' THEN (SELECT z.ProblemName AS Concern FROM csa_account_issues AS d 
											   LEFT JOIN csa_account_problems AS z ON d.AccountProblem=z.ProblemID 
											   WHERE a.ActivityID=d.ActivityID
											  )
				   WHEN 'casino_issues' THEN (SELECT z.Name AS Concern  FROM csa_casino_issues AS d  
											  LEFT JOIN csa_issues_category AS z ON d.IssueCategory=z.CategoryID 
											  WHERE a.ActivityID=d.ActivityID 
											 )
				   WHEN 'promotion' THEN (SELECT z.Name AS Concern FROM csa_promotion_activities AS d  
										  LEFT JOIN csa_promotions AS z ON d.Promotion=z.PromotionID 
										  WHERE a.ActivityID=d.ActivityID 
										 )
				   WHEN 'suggestions_complaints' THEN (SELECT z.Name AS Concern FROM csa_suggestions_complaints AS d  
													   LEFT JOIN csa_complaints_types AS z ON d.ComplaintType=z.ComplaintID 
													   WHERE a.ActivityID=d.ActivityID 
													  )
				   WHEN 'website_mobile' THEN  (SELECT z.Name AS Concern FROM csa_website_access AS d  
												LEFT JOIN csa_access_problems AS z ON d.Problem=z.ProblemID 
												WHERE a.ActivityID=d.ActivityID 
											   )
				   WHEN 'deposit_withdrawal' THEN  (SELECT CONCAT(d.Category,'::',z.Name) AS Concern
													FROM csa_bank_activities AS d    
													LEFT JOIN csa_bank_category AS z ON d.CategoryID=z.CategoryID 
													WHERE a.ActivityID=d.ActivityID 
												   ) 
				 ELSE '' 
				 END Concern, 
				 
				 CASE a.Activity 
				   WHEN 'account_issues' THEN (SELECT d.Username FROM csa_account_issues AS d  WHERE a.ActivityID=d.ActivityID)
				   WHEN 'casino_issues' THEN (SELECT d.Username FROM csa_casino_issues AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'promotion' THEN (SELECT d.Username FROM csa_promotion_activities AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'suggestions_complaints' THEN (SELECT d.Username FROM csa_suggestions_complaints AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'website_mobile' THEN (SELECT d.Username FROM csa_website_access AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'deposit_withdrawal' THEN  (SELECT d.Username FROM csa_bank_activities AS d WHERE a.ActivityID=d.ActivityID) 
				 ELSE '' 
				 END Username, 
				 
				 CASE a.Activity 
				   WHEN 'account_issues' THEN (SELECT d.ESupportID FROM csa_account_issues AS d  WHERE a.ActivityID=d.ActivityID)
				   WHEN 'casino_issues' THEN (SELECT d.ESupportID FROM csa_casino_issues AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'promotion' THEN (SELECT d.ESupportID FROM csa_promotion_activities AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'suggestions_complaints' THEN (SELECT d.ESupportID FROM csa_suggestions_complaints AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'website_mobile' THEN (SELECT d.ESupportID FROM csa_website_access AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'deposit_withdrawal' THEN  (SELECT d.ESupportID FROM csa_bank_activities AS d WHERE a.ActivityID=d.ActivityID) 
				 ELSE '' 
				 END ESupportID,
				 
				 CASE a.Activity 
				   WHEN 'account_issues' THEN (SELECT d.Currency FROM csa_account_issues AS d  WHERE a.ActivityID=d.ActivityID)
				   WHEN 'casino_issues' THEN (SELECT d.Currency FROM csa_casino_issues AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'promotion' THEN (SELECT d.Currency FROM csa_promotion_activities AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'suggestions_complaints' THEN (SELECT d.Currency FROM csa_suggestions_complaints AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'website_mobile' THEN (SELECT d.Currency FROM csa_website_access AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'deposit_withdrawal' THEN  (SELECT d.Currency FROM csa_bank_activities AS d WHERE a.ActivityID=d.ActivityID) 
				 ELSE '' 
				 END Currency, 
				 
				 CASE a.Activity  
				   WHEN 'account_issues' THEN (SELECT z.Abbreviation FROM csa_account_issues AS d 
											   LEFT JOIN csa_currency AS z ON d.Currency=z.CurrencyID 
											   WHERE a.ActivityID=d.ActivityID
											  )
				   WHEN 'casino_issues' THEN (SELECT z.Abbreviation FROM csa_casino_issues AS d  
											  LEFT JOIN csa_currency AS z ON d.Currency=z.CurrencyID 
											  WHERE a.ActivityID=d.ActivityID 
											 )
				   WHEN 'promotion' THEN (SELECT z.Abbreviation FROM csa_promotion_activities AS d  
										  LEFT JOIN csa_currency AS z ON d.Currency=z.CurrencyID 
										  WHERE a.ActivityID=d.ActivityID 
										 )
				   WHEN 'suggestions_complaints' THEN (SELECT z.Abbreviation FROM csa_suggestions_complaints AS d  
													   LEFT JOIN csa_currency AS z ON d.Currency=z.CurrencyID 
													   WHERE a.ActivityID=d.ActivityID 
													  )
				   WHEN 'website_mobile' THEN  (SELECT z.Abbreviation FROM csa_website_access AS d  
												LEFT JOIN csa_currency AS z ON d.Currency=z.CurrencyID 
												WHERE a.ActivityID=d.ActivityID 
											   )
				   WHEN 'deposit_withdrawal' THEN  (SELECT z.Abbreviation FROM csa_bank_activities AS d    
													LEFT JOIN csa_currency AS z ON d.Currency=z.CurrencyID 
													WHERE a.ActivityID=d.ActivityID 
												   ) 
												   
				 ELSE '' 
				 END CurrencyName,  
				 
				 CASE a.Activity 
				   WHEN 'account_issues' THEN (SELECT d.DateAdded FROM csa_account_issues AS d  WHERE a.ActivityID=d.ActivityID)
				   WHEN 'casino_issues' THEN (SELECT d.DateAdded FROM csa_casino_issues AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'promotion' THEN (SELECT d.DateAdded FROM csa_promotion_activities AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'suggestions_complaints' THEN (SELECT d.DateAdded FROM csa_suggestions_complaints AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'website_mobile' THEN (SELECT d.DateAdded FROM csa_website_access AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'deposit_withdrawal' THEN  (SELECT d.DateAdded FROM csa_bank_activities AS d WHERE a.ActivityID=d.ActivityID) 
				 ELSE '' 
				 END DateAdded,
				 
				 CASE a.Activity 
				   WHEN 'account_issues' THEN (SELECT d.DateUpdated FROM csa_account_issues AS d  WHERE a.ActivityID=d.ActivityID)
				   WHEN 'casino_issues' THEN (SELECT d.DateUpdated FROM csa_casino_issues AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'promotion' THEN (SELECT d.DateUpdated FROM csa_promotion_activities AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'suggestions_complaints' THEN (SELECT d.DateUpdated FROM csa_suggestions_complaints AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'website_mobile' THEN (SELECT d.DateUpdated FROM csa_website_access AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'deposit_withdrawal' THEN  (SELECT d.DateUpdated FROM csa_bank_activities AS d WHERE a.ActivityID=d.ActivityID) 
				 ELSE '' 
				 END DateUpdated, 
				 
				 CASE a.Activity 
				   WHEN 'account_issues' THEN (SELECT d.Status FROM csa_account_issues AS d  WHERE a.ActivityID=d.ActivityID)
				   WHEN 'casino_issues' THEN (SELECT d.Status FROM csa_casino_issues AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'promotion' THEN (SELECT d.Status FROM csa_promotion_activities AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'suggestions_complaints' THEN (SELECT d.Status FROM csa_suggestions_complaints AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'website_mobile' THEN (SELECT d.Status FROM csa_website_access AS d WHERE a.ActivityID=d.ActivityID)
				   WHEN 'deposit_withdrawal' THEN  (SELECT d.Status FROM csa_bank_activities AS d WHERE a.ActivityID=d.ActivityID) 
				 ELSE '' 
				 END CurrentStatus 	 
					 
			 FROM csa_activities_history AS a  
				LEFT JOIN csa_status AS b ON a.Status=b.StatusID 
				LEFT JOIN g4_member AS c ON a.UpdatedBy=c.mb_no   
			 WHERE a.HistoryID<>0 $search_string  
			 GROUP BY HistoryIndex  
			 HAVING a.ActivityID<>0 $search_string_having
			 ORDER BY a.DateUpdated DESC, a.Status ASC
			 ";    
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result);
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "E-Support ID", "Currency", "Activity", "Concern", "Username", "Status", "Remarks", "RM Remarks", "SRemarks", "MRemarks"  );
							
		$data_list = array("DateAdded", "DateUpdated", "ESupportID", "CurrencyName", "Activity", "Concern", "Username", "StatusName", "Remarks", "RMRemarks", "SRemarks", "MRemarks" );   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Filters **/
function shift_report($get) {
	
	$action = $get['action'];
	$report_type = ($report_type)?$report_type:"excel"; 
	
	$s_supportid = $get['s_supportid'];
	$s_currency = $get['s_currency'];  
	$s_username = $get['s_username'];
	$s_report = $get['s_report'];  
	$s_status = $get['s_status']; 
	$s_agent  = $get['s_agent']; 
	$s_fromdate = urldecode(urlencode($get['s_fromdate']));
	$s_todate = urldecode(urlencode($get['s_todate']));
	
	$search_string = ""; 
	if(trim($s_esupportid))
	 {
		$search_string .= " AND (a.ESupportID LIKE '%".mysql_real_escape_string($s_esupportid)."%') "; 
		$search_url .= "&s_esupportid=".$s_esupportid;
	 }
	 
	if(trim($s_currency))
	 {
		$search_string .= " AND (a.Currency='".mysql_real_escape_string($s_currency)."') "; 
		$search_url .= "&s_currency=".$s_currency;
	 }
	 
	if(trim($s_username))
	 {
		$search_string .= " AND (a.Username LIKE '%".mysql_real_escape_string($s_username)."%') "; 
		$search_url .= "&s_username=".$s_username;
	 } 
	 
	if(trim($s_report))
	 {
		$search_string .= " AND (a.Report LIKE '%".mysql_real_escape_string($s_report)."%') "; 
		$search_url .= "&s_report=".$s_report;
	 } 
	  
	   
	 if(trim($s_fromdate) && trim($s_todate))
	 { 
		$search_string .= " AND (a.DateAdded BETWEEN '".mysql_real_escape_string($s_fromdate)."' AND '".mysql_real_escape_string($s_todate)."')  "; 
		$search_url .= "&s_fromdate=".urlencode($s_fromdate)."&s_todate=".urlencode($s_todate);  
	 }
	
	if(trim($s_status) != "")
	 {
		$search_string .= " AND (a.Status='".$s_status."') ";  
		$search_url .= "&s_status=".$s_status;
	 }
	 
	if(trim($s_agent) != "")
	 {
		$search_string .= " AND (f.mb_nick LIKE '%".mysql_real_escape_string($s_agent)."%') ";  
		$search_url .= "&s_agent=".$s_agent;
	 }  
	 
		 
	$sql = " SELECT a.*, b.Abbreviation AS Currency, e.Name AS StatusName, f.mb_nick, 
					(SELECT GROUP_CONCAT('(',c.DateUpdated,')', ' ', LCASE(TRIM(c.Report)) ORDER BY c.DateUpdated DESC SEPARATOR '\n' ) FROM csa_report_history AS c  WHERE c.ReportID=a.ReportID  ) AS ReportAll 
			 FROM csa_shift_report AS a 
				LEFT JOIN csa_currency AS b ON a.Currency=b.CurrencyID   
				LEFT JOIN csa_status AS e ON a.Status=e.StatusID 
				LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no   
			 WHERE a.ReportID <> 0 $search_string 
			 ORDER BY a.DateAdded DESC, a.DateUpdated DESC, a.Status ASC 
		   ";
		  
	$result = sql_query($sql);
	$count_result = mysql_num_rows($result); 
 
	if($report_type == "excel") 
	 {
		$file_name = $action.'-'.date("Ymdhis").".xls"; 
		$header_list = array("Date Added", "Date Updated", "Currency", "E-Support ID", "Report",  "Username","Last Updated By", "Status" );
							
		$data_list = array("DateAdded", "DateUpdated", "Currency", "ESupportID", "ReportAll", "Username", "mb_nick", "StatusName");   
		export_data($result, $report_type, $header_list, $data_list, $file_name);
	 }
	else
	 {
		return 0; 
	 }
	
}


/** Export Function  **/
function export_data($resource, $report_type="excel", $header_list=array(), $data_list=array(), $file_name="csa_log.xls", $checking=0){ 
	 //checking is for checking
	//$file_name =	"login_list".date("Ymd").".xls";
	//$resource = filter_login($post,'excel');

	$objPHPExcel = new PHPExcel();
	$activeSheet = $objPHPExcel->setActiveSheetIndex(0);
	$headerStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => '8DB4E2'),
									'font'=> array('bold'=>true)));
	$categoryStyle = array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'E4E4E4'),
									'font'=> array('bold'=>true)));
	$reportStyle =array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,
									'color' => array('rgb' => 'f4ec12'),
									'font'=> array('bold'=>true)));
	
	$y = 'A';
	$start = 1; 
	foreach($header_list as $row=>$val){ 
		$row_cel = $y.$start;   
		$activeSheet->setCellValue($row_cel,$val);
		$activeSheet->getStyle($row_cel)->applyFromArray($headerStyle); 
		$y++; 
	}//end foreach
	
	$ctr = $start + 1;
	$category_code = "";  
	while($row = sql_fetch_array($resource)){ 
		$row[DateAdded] = date("F d, Y H:i:s", strtotime($row[DateAdded]));  
		$row[DateUpdated] = date("F d, Y H:i:s", strtotime($row[DateUpdated])); 
		$row[Amount] = number_format($row[Amount], 2);
		$row[CurrentBalance] = number_format($row[CurrentBalance], 2);
		$row[DepositAmount] = number_format($row[DepositAmount], 2);
		$row[BonusAmount] = number_format($row[BonusAmount], 2);
		$row[WageringAmount] = number_format($row[WageringAmount], 2);
		$row[TurnoverAmount] = number_format($row[TurnoverAmount], 2);
		$row[CashbackAmount] = number_format($row[CashbackAmount], 2);  
		$row[BetLimitPerBet] = number_format($row[BetLimitPerBet], 2);
		$row[BetLimitPerMatch] = number_format($row[BetLimitPerMatch], 2);
		$row[ProposedLimitPerBet] = number_format($row[ProposedLimitPerBet], 2);
		$row[ProposedLimitPerMatch] = number_format($row[ProposedLimitPerMatch], 2);
		$row[RevisedLimitPerBet] = number_format($row[RevisedLimitPerBet], 2);
		$row[RevisedLimitPerMatch] = number_format($row[RevisedLimitPerMatch], 2); 
		$row[DateChecked] = date("F d, Y H:i:s", strtotime($row[DateChecked]));
		
		$row[AccountBlocked] = ($row[AccountBlocked]==1)?"YES":"NO";
		$row[AccountLocked] = ($row[AccountLocked]==1)?"YES":"NO";
		$row[AccountClosed] = ($row[AccountClosed]==1)?"YES":"NO";
		
		if(!empty($row['DateUpdated'])){ 
			//$activeSheet->setCellValue("A".$ctr,$asset_count);
			//$activeSheet->getCell('B'.$ctr)->setValueExplicit($row['as_assetNum'], PHPExcel_Cell_DataType::TYPE_STRING);
			$x = 'A';
			for($i=0; $i<count($data_list);$i++)
			 {	
			 	if($checking == 1 && (($data_list[$i]!="DateChecked") && ($data_list[$i]!="Remarks") && ($data_list[$i]!="mb_nick")) )$row[$data_list[$i]] = ($row[$data_list[$i]] == 1)?"Yes":"No"; //if checking convert 1:yes, 0,no 
				
				$activeSheet->setCellValue($x.$ctr,$row[$data_list[$i]], PHPExcel_Cell_DataType::TYPE_STRING); 
				$x++;  
			 }  
			$ctr++;
		}
	}//end while
	
	//count reports								
	$activeSheet->setCellValue('A'.($ctr+2),"Total Record(s)");
	$activeSheet->getStyle('A'.($ctr+2))->applyFromArray($reportStyle); 
	$activeSheet->setCellValue('B'.($ctr+2),mysql_num_rows($resource));
	$activeSheet->getStyle('B'.($ctr+2))->applyFromArray($reportStyle); 
	 
	$objPHPExcel->getActiveSheet()->setTitle('Report');
	$objPHPExcel->setActiveSheetIndex(0);
	
	$x='A';
	for ($col = 0; $col<count($data_list); $col++) {
		$objPHPExcel->getActiveSheet()->getColumnDimension($x)->setAutoSize(true); 
		$x++; 
	} 
	 
	for ($col = 2; $col<count($data_list); $col++) { 
		$objPHPExcel->getActiveSheet()->getRowDimension($col)->setRowHeight(-1); 
		$objPHPExcel->getActiveSheet()->getStyle('E'.$col)->getAlignment()->setWrapText(true);
	} 
	 
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$file_name.'"');
	header('Cache-Control: max-age=0');
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
} 

function removeSpace($string) { 
	$string = trim(str_replace(" ","",$string));
	$string = trim(str_replace("-","",$string));
	return $string;
} 

function export_dataxx($resource, $report_type="excel", $header_list=array(), $data_list=array(), $file_name="csa_log.xls"){ 
	while($row = sql_fetch_array($resource)){ 
		$row[DateUpdated] = date("F d, Y H:i:s", strtotime($row[DateUpdated]));  
		$row[Amount] = number_format($row[Amount], 2);
		$row[CurrentBalance] = number_format($row[CurrentBalance], 2);
		$row[DepositAmount] = number_format($row[DepositAmount], 2);
		$row[BonusAmount] = number_format($row[BonusAmount], 2);
		$row[WageringAmount] = number_format($row[WageringAmount], 2);
		$row[TurnoverAmount] = number_format($row[TurnoverAmount], 2);
		$row[CashbackAmount] = number_format($row[CashbackAmount], 2);  
		$row[BetLimitPerBet] = number_format($row[BetLimitPerBet], 2);
		$row[BetLimitPerMatch] = number_format($row[BetLimitPerMatch], 2);
		$row[ProposedLimitPerBet] = number_format($row[ProposedLimitPerBet], 2);
		$row[ProposedLimitPerMatch] = number_format($row[ProposedLimitPerMatch], 2);
		$row[RevisedLimitPerBet] = number_format($row[RevisedLimitPerBet], 2);
		$row[RevisedLimitPerMatch] = number_format($row[RevisedLimitPerMatch], 2); 
		
		if(!empty($row['DateUpdated'])){ 
			//$activeSheet->setCellValue("A".$ctr,$asset_count);
			//$activeSheet->getCell('B'.$ctr)->setValueExplicit($row['as_assetNum'], PHPExcel_Cell_DataType::TYPE_STRING);
			echo  $row['DateUpdated']."<br>";
		}
	}//end while
}

?>