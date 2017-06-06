<?php

// MySQL Class v0.8.1
class MyFunctions extends My_Controller {
	
	 var $error;  
	 
	public function __construct(){
		//parent::__construct();
		//$this->load->model("ManageBannersModel","banners");  
	}
	 
	// Closes the connections
	public function getLanguagesx($lang=""){ 
		/* $sql = "SELECT LanguageID, Name, FolderName, SpecialCharacters FROM language  
				WHERE Status<>'9' 
				ORDER BY LanguageID ASC "; 
		return "xxxxx"; 
		 
		$result = $this->db->query($sql); 
		if($result->num_rows() > 0){
			if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}
		}
		else {
			return array();
		} */
		
		return "";
	} 
	
	
	public function errorPage() {
		//RETURN 404
		$data = array("page_title"=>"404 Page Not Found", 
				  "header_title"=>"404 Page Not Found", 
				  "error_message"=>"The page you requested was not found. Check URL.",
				  "error_type"=>"404");
		$this->load->view('header',$data);
		$this->load->view('error_tpl');
		$this->load->view('footer');
	}
 
	 
}

?>
