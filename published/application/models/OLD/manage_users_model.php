<?php
class Manage_Users_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "g4_member"; 
    }
	
	function checkUsername_($username, $user_id=""){   
		$where = ($user_id)?" AND mb_no<>".$user_id." ":"";
		$sql = "SELECT  mb_no 
			  	FROM {$this->table_name} 
				WHERE LCASE(mb_username) =".strtolower($this->db->escape($username))." AND mb_status='1' ".$where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	 
	}
	
	function getUserById_($user_id){  
		$sql = "SELECT  a.mb_no, a.mb_id, a.mb_username, a.mb_password, a.mb_usertype, a.mb_fname, a.mb_mname, a.mb_lname, a.mb_nick, a.mb_datestarted, 
						a.mb_email, a.mb_pemail, a.mb_emptype, a.mb_tel, a.mb_mobileno, a.mb_othermobile, a.mb_addr1, a.mb_profilepic, a.mb_status, b.Name AS mb_usertypename  
				FROM {$this->table_name} AS a  
				LEFT JOIN users_types AS b ON a.mb_usertype=b.GroupID 
				WHERE FIND_IN_SET(a.mb_no, ".$this->db->escape($user_id).") AND a.mb_usertype<>'1' ";//
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
		} 
	 
	} 
	
	function getLanguages_($lang=""){ 
		 $sql = "SELECT LanguageID, Name, FolderName, SpecialCharacters FROM language  
				WHERE Status='1' 
				ORDER BY LanguageID ASC "; 
		 
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
		} 
	 
	} 
	
	 
	public function manageUsers_($table, $datax, $action="add", $index="", $index_value=""){ 
		$test="";
		$data = array(); 
		 
		foreach($datax as $field => $value){ 
			if($field == "mb_password") 
			 {
				 //$this->db->set('mb_password', 'PASSWORD("'.$value.'")', TRUE);  
				 //$this->db->set('mb_password', 'PASSWORD("'.$this->input->post('mb_password', TRUE).'")', FALSE);
				 $this->db->set('mb_password', 'PASSWORD("'.$value.'")', FALSE);
			 }
			else 
			 {
				$data[$field] = $value;//$this->db->escape($value)." "; 
			 }  
			 $test .= $field.'-';
		}   
	 
		//$this->db->set('mb_password', 'PASSWORD("'.$data('user_password', TRUE).'")', FALSE);
		
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$data);  
			return $x; 
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
		 }
		
	}
	
	
	public function uploadImage_(){
		
	}
	
	 
	public function get_notice_list_per_emp($where=array(),$order=array(),$offset=0,$limit=0){
		$where_str = $order_str = $limit_str = "";
		$where_arr = $order_arr = array();
		foreach($where as $field=>$value){
			$where_arr[] = " ".$field." = ".$this->db->escape($value)." ";
		}
		
		foreach($order as $field=>$order){
			$order_arr[] = " ".$field." ".$order." ";
		}
		
		$where_str = implode(" AND ",$where_arr);
		$where_str = ($where_str==""?"":"WHERE ".$where_str);
		$order_str = implode(" , ",$order_arr);
		$order_str = ($order_str==""?"":"ORDER BY ".$order_str);
		if(!empty($limit)){
			$limit_str = "LIMIT {$offset},{$limit}";
		}
		
		$query = 	"SELECT *,
						IF(datetime_viewed IS NULL OR datetime_viewed = '' OR datetime_viewed = '0000-00-00 00:00:00' OR np_updated_datetime > datetime_viewed,0,1) viewed,
						IF(NOW() < DATE_ADD(np_updated_datetime,INTERVAL 1 DAY),1,0) fresh
						FROM {$this->table_name} main 
						LEFT JOIN {$this->table_view_name} view ON main.np_id = view.np_id
							AND (view.mb_no = ".$this->db->escape($this->session->userdata("mb_no")).")
					".$where_str." ".$order_str." ".$limit_str;
		//echo $query;
		$result = $this->db->query($query);
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
		}
	}
	
	public function update_entry($data,$param){
		$this->db->update($this->table_name,$data,$param);
		return $this->db->affected_rows();
	}
	
	public function insert_table_entry($table,$data){
		$this->db->insert($table,$data);
		return $this->db->affected_rows();
	}
	
	public function update_table_entry($table,$data,$param){
		$this->db->update($table,$data,$param);
		return $this->db->affected_rows();
	}
	
	public function get_table_entry($table,$where=array()){
		$result = $this->select_strict($where,$table);
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
		}
	}
    
	public function create_thumbnail($config) { 
		$this->image_lib->initialize($config); 
		$this->image_lib->resize(); 
		
	}
     
	/* End of Public Functions */
	
	/* Private Functions */
	
	private function select_strict ($where=array(),$table = "",$order=array(),$offset=0,$limit=0){
		if(empty($table))
			$table = $this->table_name;
		$where_str = $order_str = $limit_str = "";
		$where_arr = $order_arr = array();
		foreach($where as $field=>$value){
			$where_arr[] = " `".$field."` = ".$this->db->escape($value)." ";
		}
		
		foreach($order as $field=>$order){
			$order_arr[] = " `".$field."` ".$order." ";
		}
		
		$where_str = implode(" AND ",$where_arr);
		$where_str = ($where_str==""?"":"WHERE ".$where_str);
		$order_str = implode(" , ",$order_arr);
		$order_str = ($order_str==""?"":"ORDER BY ".$order_str);
		if(!empty($limit)){
			$limit_str = "LIMIT {$offset},{$limit}";
		}
		
		$result = $this->db->query("SELECT * FROM ".$table." ".$where_str." ".$order_str." ".$limit_str);
		
		return $result;
	}
	
	/* End of Private Functions */
}

?>