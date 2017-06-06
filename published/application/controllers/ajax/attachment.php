<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Attachment extends CI_Controller {

	public function __construct(){
		parent::__construct();
	}
	
	public function download(){
		$lnk = $this->input->get('lnk');
		$file = $this->input->get('file');
		if(!empty($lnk) && !empty($file)){
			try{
				$file = str_replace("[percent]","%",$file);
				$file_name = $file;
				$absolute_path = $lnk;
				
				ob_start();
				$file_type = filetype("./media/uploads/".$absolute_path);
				$file_type_error = ob_get_contents();
				ob_end_clean();
				
				if(!empty($file_type_error))
					throw new Exception('File does not exist');
				
				ob_start();
				readfile("./media/uploads/".$absolute_path);
				$file_content = ob_get_contents();
				ob_end_clean();
				
				//prevent caching on client side:
				header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				//header("Cache-Control: no-store, no-cache, must-revalidate");
				//header("Cache-Control: post-check=0, pre-check=0", false);
				header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
				header("Pragma: no-cache");
				
				header("Content-Type: ".$file_type);
				header("Content-Disposition: attachment; filename=\"".$file_name."\"");
				header("Content-Transfer-Encoding: binary");
				echo $file_content;
				
			}
			catch(Exception $e){
				header("HTTP/1.1 404 Not Found"); exit;
			
			}
		}
		else{
			header("HTTP/1.1 404 Not Found"); exit;
		}
	}
}

/* End of file attachment.php */
/* Location: ./application/controllers/ajax/attachment.php */