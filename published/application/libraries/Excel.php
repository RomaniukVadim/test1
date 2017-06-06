<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 *  ======================================= 
 *  Author     : Muhammad Surya Ikhsanudin 
 *  License    : Protected 
 *  Email      : mutofiyah@gmail.com 
 *   
 *  Dilarang merubah, mengganti dan mendistribusikan 
 *  ulang tanpa sepengetahuan Author 
 *  ======================================= 
 */  
require_once APPPATH."third_party/PHPExcel.php";  
$cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
$cacheSettings = array('memoryCacheSize' => '5MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings); 
 
class Excel extends PHPExcel { 
    public function __construct() { 
        parent::__construct();  
	} 
}