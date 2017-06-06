<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "login";
//$route['404_override'] = '';
$route['404_override'] = 'error'; 

//under maintenance
$route['error/under-maintenance'] = 'error/under_maintenance';

//MANAGE INDEX 
$route['banners/(:num)'] = "banners/index/$1";

//MANAGE BANNERS 
$route['manage-banners'] = "manage_banners";
//$route['manage-banners/:num'] = "manage_banners/edit"; 
$route['manage-banners/(:num)'] = "manage_banners/edit/$1";

//MANAGE USERS 
$route['manage-users'] = "manage_users"; 
$route['manage-users/(:num)'] = "manage_users/edit/$1"; 
$route['view-user/(:num)'] = "manage_users/view/$1";

 
//MANAGE PROFILE 
$route['manage-profile'] = "manage_profile"; 
//$route['manage-users/(:num)'] = "manage_users/edit/$1"; 

//MANAGE PAGES 
$route['manage-pages'] = "manage_pages";
//$route['manage-banners/:num'] = "manage_banners/edit"; 
$route['manage-pages/(:num)'] = "manage_pages/edit/$1";
$route['view-pages/(:num)'] = "manage_pages/view/$1";  

 
//MANAGE TOPICS 
$route['manage-topics'] = "manage_topics";  
$route['manage-topics/(:num)'] = "manage_topics/edit/$1"; 
$route['view-topics/(:num)'] = "manage_topics/view/$1"; 


//MANAGE TOPICS 
$route['manage-accounts'] = "manage_accounts"; 
$route['manage-accounts/(:num)'] = "manage_accounts/edit/$1"; 
$route['view-account/(:num)'] = "manage_accounts/view/$1"; 

//MANAGE WARNING 
$route['manage-agents'] = "manage_agents";  
$route['manage-agents/(:num)'] = "manage_agents/edit/$1"; 
$route['view-agents/(:num)'] = "manage_agents/view/$1"; 

//MANAGE MODES 
$route['manage-modes'] = "manage_modes"; 
$route['manage-modes/(:num)'] = "manage_modes/edit/$1"; 
$route['view-modes/(:num)'] = "manage_modes/view/$1";


//VIEW OPERATIONS PAGES. DYNAMIC PAGES. 
$route['operations/(:num)'] = "operations/view/$1"; 


//MANAGE SCHEDULES 
$route['manage-schedules'] = "manage_schedules"; 
$route['manage-schedules/(:num)'] = "manage_schedules/edit/$1"; 

//MANAGE BONUS 
$route['manage-bonus'] = "manage_bonus"; 
$route['manage-bonus/(:num)'] = "manage_bonus/edit/$1"; 
$route['import-bonus'] = "import_bonus"; 
$route['import-bonus/export'] = "import_bonus/export"; 
$route['import-bonus/upload'] = "import_bonus/upload"; 
$route['view-bonus/(:num)'] = "manage_bonus/view/$1"; 

//MANAGE WEBSITES 
$route['manage-websites'] = "manage_websites"; 
$route['manage-websites/(:num)'] = "manage_websites/edit/$1"; 
$route['view-websites/(:num)'] = "manage_websites/view/$1"; 

//MANAGE OVERPAY 
$route['manage-overpay'] = "manage_overpay"; 
$route['manage-overpay/(:num)'] = "manage_overpay/edit/$1"; 
$route['view-overpay/(:num)'] = "manage_overpay/view/$1"; 

//MANAGE NOTICE 
$route['manage-notice'] = "manage_notice";  
$route['manage-notice/(:num)'] = "manage_notice/edit/$1"; 
$route['view-notice/(:num)'] = "manage_notice/view/$1";

//MANAGE WARNING 
$route['manage-warning'] = "manage_warning";  
$route['manage-warning/(:num)'] = "manage_warning/edit/$1"; 
$route['view-warning/(:num)'] = "manage_warning/view/$1"; 

//MANAGE WARNING 
$route['manage-reports'] = "manage_reports";  
$route['manage-reports/(:num)'] = "manage_reports/edit/$1"; 
$route['view-reports/(:num)'] = "manage_reports/view/$1"; 

//MANAGE MEETING 
$route['manage-meetings'] = "manage_meetings";  
$route['manage-meetings/(:num)'] = "manage_meetings/edit/$1"; 
$route['view-meetings/(:num)'] = "manage_meetings/view/$1";
$route['reply-meetings/(:num)/(:num)'] = "manage_meetings/reply/$1/$2"; 
$route['reply-meetings/(:num)'] = "manage_meetings/reply/$1";

//DAILY TASK 
$route['manage-tasks'] = "manage_tasks";  
$route['manage-tasks/(:num)'] = "manage_tasks/edit/$1"; 
$route['view-tasks/(:num)'] = "manage_tasks/view/$1";   


//SUGGESTIONS   
$route['view-suggestions/(:num)'] = "suggestions/view/$1";   


//LOCAL TRANSACTIONS 
$route['local-transactions'] = "local_transactions";

//MANAGE TRANSACTION BANK RECON 
$route['manage-transactions'] = "manage_transactions"; 
$route['manage-transactions/(:num)'] = "manage_transactions/edit/$1"; 
$route['local-transactions/(:num)'] = "manage_transactions/view/$1"; 

//INTERNAL TRANSACTIONS 
$route['internal-transactions'] = "internal_transactions";
$route['manage-internal-transactions'] = "manage_internal_transactions"; 
$route['manage-internal-transactions/(:num)'] = "manage_internal_transactions/edit/$1";
$route['internal-transactions/(:num)'] = "manage_internal_transactions/view/$1"; 

$route['export-recon'] = "export_recon";


//MANAGE BANKS 2
$route['manage-banks'] = "manage_banks"; 
$route['manage-banks/(:num)'] = "manage_banks/edit/$1"; 
$route['view-bank/(:num)'] = "manage_banks/view/$1"; 

// PAYMENT MODES
$route['payment-modes'] = "payment_modes"; 
$route['manage-payment-modes'] = "manage_payment_modes"; 
$route['manage-payment-modes/(:num)'] = "manage_payment_modes/edit/$1"; 
$route['view-payment-mode/(:num)'] = "manage_payment_modes/view/$1"; 

$route['manage-payment-modes'] = "manage_payment_modes"; 
$route['manage-payment-modes/(:num)'] = "manage_payment_modes/edit/$1"; 
$route['view-payment-mode/(:num)'] = "manage_payment_modes/view/$1"; 




//BANKS ACTIVITIES
$route['banks/(:num)'] = "banks/$1";
$route['bank/activities'] = "banks";  
$route['banks/activities/(:num)'] = "deposit_methods/activities/$1";  
$route['banks/deposit-methods'] = "deposit_methods/depositMethods";
//$route['banks/deposit-methods/(:num)'] = "deposit_methods/depositMethods/$1"; 
$route['banks/deposit-categories'] = "deposit_categories/depositCategories";
$route['banks/withdrawal-categories'] = "deposit_categories/withdrawalCategories";

$route['banks/search'] = "search_banks_activities";  
$route['banks/search/(:num)'] = "search_banks_activities/searchActivities/$1";

$route['banks/analysis-reasons'] = "analysis_reasons";   
$route['banks/analysis-reasons/(:num)'] = "analysis_reasons/analysisReasons/$1"; 

$route['banks/analysis-categories'] = "analysis_categories";   
$route['banks/analysis-categories/(:num)'] = "analysis_categories/analysisCategories/$1"; 



//ACCOUNTS
$route['accounts/(:num)'] = "accounts/$1"; 

$route['accounts/activities'] = "accounts";  
$route['accounts/activities/(:num)'] = "accounts/activities/$1";  
$route['accounts/related-problems'] = "related_problems/relatedProblems";  

$route['accounts/related-problem-categories'] = "related_problem_categories";
$route['accounts/related-problem-categories/(:num)'] = "related_problem_categories/relatedProblemCategories/$1";

$route['accounts/search'] = "search_accounts_issues";  
$route['accounts/search/(:num)'] = "search_accounts_issues/searchActivities/$1";


//SUGGESTIONS
$route['suggestions/(:num)'] = "suggestions/$1";
$route['suggestions/activities'] = "suggestions";  
$route['suggestions/activities/(:num)'] = "suggestions/activities/$1";  
$route['suggestions/types'] = "suggestion_types/suggestionTypes"; 

$route['suggestions/search'] = "search_suggestions_activities";  
$route['suggestions/search/(:num)'] = "search_suggestions_activities/searchActivities/$1";

//ACCESS
$route['access/(:num)'] = "access/$1";
$route['access/activities'] = "access";  
$route['access/activities/(:num)'] = "access/activities/$1";  
$route['access/problems'] = "access_problems/accessProblems"; 

$route['access/search'] = "search_access_activities";  
$route['access/search/(:num)'] = "search_access_activities/searchActivities/$1";

//PROMOTIONS
$route['promotions/(:num)'] = "promotions/$1";
$route['promotions/activities'] = "promotions";  
$route['promotions/activities/(:num)'] = "promotions/activities/$1";  
$route['promotions/manage-promotions'] = "manage_promotions";
$route['promotions/categories'] = "promotional_categories";
$route['promotions/issues'] = "promotional_issues";
$route['promotions/agent-summary-report'] = "agent_summary_report";
$route['promotions/agent-summary-report/export'] = "agent_summary_report/exportReports";
$route['promotions/call-details/(:any)'] = "promotions/callDetails";
$route['promotions/call-details'] = "promotions/callDetails";

$route['promotions/search'] = "search_promotions_activities";  
$route['promotions/search/(:num)'] = "search_promotions_activities/searchActivities/$1";

$route['promotions/uploaded'] = "promotions_uploaded";  
$route['promotions/uploaded/(:num)'] = "promotions_uploaded/searchActivities/$1";

$route['promotions/management-approval'] = "promotions_management_approval";  
$route['promotions/management-approval/(:num)'] = "promotions_management_approval/activities/$1";


//CASINO
$route['casino/(:num)'] = "casino/$1";
$route['casino/activities'] = "casino";  
$route['casino/activities/(:num)'] = "casino/activities/$1"; 
$route['casino/categories'] = "casino_categories";

$route['casino/search'] = "search_casino_issues";  
$route['casino/search/(:num)'] = "search_casino_issues/searchActivities/$1";


//MANAGE
$route['manage/(:num)'] = "manage";
$route['manage/call-outcomes'] = "call_outcomes";   
$route['manage/call-outcomes/(:num)'] = "call_outcomes/callOutcomesList/$1";  

$route['manage/call-results'] = "call_results";   
$route['manage/call-results/(:num)'] = "call_results/getCallResults/$1";   

$route['manage/result-categories'] = "result_categories";   
$route['manage/result-categories/(:num)'] = "result_categories/getResultCategoriesList/$1";

$route['manage/chat-groups'] = "chat_groups";   
$route['manage/chat-groups/(:num)'] = "chat_groups/getChatGroups/$1";

$route['manage/users'] = "users";   
$route['manage/users/(:num)'] = "users/getUsers/$1";

$route['manage/currencies'] = "currencies";   
$route['manage/currencies/(:num)'] = "currencies/currenciesList/$1";

$route['manage/status'] = "status";   
$route['manage/status/(:num)'] = "status/getStatus/$1"; 

$route['manage/activity-source'] = "activity_source";   
$route['manage/activity-source/(:num)'] = "activity_source/activitySourceList/$1"; 

$route['manage/suggestions-types'] = "suggestion_types";   
$route['manage/suggestions-types/(:num)'] = "suggestion_types/suggestionTypes/$1";  

$route['manage/pages'] = "pages";   
$route['manage/pages/(:num)'] = "pages/pageList/$1"; 

$route['manage/checking-category'] = "checking_category";   
$route['manage/checking-category/(:num)'] = "pages/checkingCategoryList/$1";  

$route['manage/12bet-checking'] = "checking_12bet";   
$route['manage/12bet-checking/(:num)'] = "checking_12bet/checkingList/$1";  
   
$route['manage/user-types'] = "user_types";   
$route['manage/user-types/(:num)'] = "user_types/userTypesList/$1"; 

$route['search-activities/(:any)'] = "search/index";   

$route['reports/cal'] = "cal";   
$route['reports/cal/(:num)'] = "cal/calReports/$1"; 

$route['reports/cal/status-details'] = "cal/calReportsStatusDetails";   
$route['reports/cal/status-details/(:any)'] = "cal/calReportsStatusDetails/$1";

$route['reports/cal/source-details'] = "cal/calReportsSourceDetails";   
$route['reports/cal/source-details/(:any)'] = "cal/calReportsSourceDetails/$1";

$route['reports/crm'] = "crm";   
$route['reports/crm/(:num)'] = "crm/crmCalls/$1";

$route['reports/conversions'] = "crm_conversions";   
$route['reports/conversions/(:num)'] = "crm_conversions/crmConversions/$1"; 

$route['checking/12bet'] = "check_12bet";   
$route['checking/12bet/(:num)'] = "check_12bet/checking12Bet/$1";

$route['checking/shift-report'] = "shift_report";   
$route['checking/shift-report/(:num)'] = "shift_report/shiftReports/$1";  

$route['checking/market-apps'] = "check_12bet/checkMarketApps";   
$route['checking/market-apps/(:num)'] = "check_12bet/checkMarketApps/$1";

//DOWNLOAD
$route['download/(:any)'] = "download";

//12BET USERS
$route['12bet-users'] = "users_12bet";   
$route['12bet-users/(:num)'] = "users_12bet/users12betList/$1"; 
$route['12bet-users/list'] = "users_12bet";   
$route['12bet-users/list/(:num)'] = "users_12bet/users12betList/$1"; 

$route['12bet-users/search'] = "users_12bet/searchActivities";   
$route['12bet-users/search/(:any)'] = "users_12bet/searchActivities/$1"; 


//BACKUP URL
$route['backup-url'] = "backup_url";   
$route['backup-url/(:num)'] = "backup_url/backupUrlList/$1"; 
$route['backup-url/list'] = "backup_url";   
$route['backup-url/list/(:num)'] = "backup_url/backupUrlList/$1"; 

$route['backup-url/search'] = "backup_url/searchUrl";   
$route['backup-url/search/(:any)'] = "backup_url/searchUrl/$1"; 

//WEBSITE PROMOTIONS
$route['website-promotions'] = "website_promotions";   
$route['website-promotions/(:any)'] = "website_promotions/registerPromotions/$1"; 

  
//Portal
$route['portal/configure/(menu)/(edit|add)'] = "portal/$1/$2"; 
$route['portal/configure/(menu)/(view)/(:num)'] = "portal/$1/$2/$3"; 
$route['portal/configure/(page)/(edit|add|home|statistics)'] = "portal/$1/$2"; 
$route['portal/configure/(page)/(home)/(view|add)/(:any)'] = "portal/$1/$2/$3/$4"; 
$route['portal/configure/(page)/(view|delete|add)/(:num)'] = "portal/$1/$2/$3"; 
$route['portal/configure/(get_menu_list|get_submenu_by_market|save_menu|delete_menu|upload_image|get_image_list)'] = "portal/$1"; 
$route['portal/configure/(get_page_list|save_page|delete_page)'] = "portal/$1"; 
$route['portal/configure/(get_statistics)'] = "portal/$1"; 


/* End of file routes.php */
/* Location: ./application/config/routes.php */