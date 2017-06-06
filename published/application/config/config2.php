<?php  

$timezone = "Asia/Manila";
if(function_exists('date_default_timezone_set'))
	date_default_timezone_set($timezone);
 
$config['b4_version']		= "1.0.x";
$config['server_time']		= time();
$config['time_ymd']			= date("Y-m-d", $config['server_time']);
$config['time_his']			= date("H:i:s", $config['server_time']);
$config['time_ymdhis']		= date("Y-m-d H:i:s", $config['server_time']);

$config['chat_eng'] = array(11, 10, 6, 1, 9);
$config['chat_kr'] = array(7, 8); 


//$config['maintenance_mode'] = FALSE;
$config['maintenance_uptime'] = "2015/08/28 14:30"; //update this date if want to down.

$config['change_internal_username'] = TRUE;
 
?>