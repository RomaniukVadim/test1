<?php
class Employee extends CI_Model {
	
	private $table_name;
	private $login_table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "g4_member";
		$this->login_table_name = "member_login";  
    }
	
	public function validate_credentials($username,$password){
		$result = $this->select_strict(
										array(
											"mb_username"	=>$username,
											"mb_password" 	=>$password,  
											"mb_name"		=>"csa", 
											"mb_status" 	=>1
										)
									);
		return $result->num_rows() > 0?true:false;
	}
	
	public function get_employee($where=array()){
		$result = $this->select_strict($where);
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
	
	//GET SESSION IN INTRANET
	public function checkCrossSession_($where=array(), $table){
		$result = $this->select_strict_cross($where, $table); 
		return $result->num_rows() > 0?true:false;  
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
        
        public function get_active_employee($sel){
            return $this->db->select($sel,false)
                            ->from($this->table_name)
                            ->where("mb_status",1)
                            ->where_not_in("mb_no",Array(433,384,280,242,243,244,245,35,33,388,387,34,463,156,1)) // test and admin accounts
                            ->get()->result();
        }
	
	/* End of Public Functions */
	
	/* Private Functions */
	
	private function select_strict ($where=array(),$table = ""){ 
		if(empty($table))
			$table = $this->table_name;
		$where_str = "";
		$where_arr = array();
		foreach($where as $field=>$value){
			if($field == "mb_password"){
				$where_arr[] = " `".$field."` = PASSWORD(".$this->db->escape($value).") ";
			}
			else{
				$where_arr[] = " `".$field."` = ".$this->db->escape($value)." ";
			}
		}
		
		$where_str = implode(" AND ",$where_arr);
		
		$result = $this->db->query("SELECT * FROM ".$table." ".($where_str==""?"":"WHERE ".$where_str));
		
		return $result;
	}
	 
	 
	private function select_strict_cross($where=array(),$table = ""){ 
		$this->db_intra = $this->load->database('intra', TRUE);
		if(empty($table))
			$table = $this->table_name;
		$where_str = "";
		$where_arr = array();
		foreach($where as $field=>$value){
			if($field == "mb_password"){
				$where_arr[] = " `".$field."` = PASSWORD(".$this->db_intra->escape($value).") ";
			}
			else{
				$where_arr[] = " `".$field."` = ".$this->db_intra->escape($value)." ";
			}
		}
		
		$where_str = implode(" AND ",$where_arr);
		$result = $this->db_intra->query("SELECT * FROM ".$table." ".($where_str==""?"":"WHERE ".$where_str));
		
		return $result;
	}
	 
        
	/* End of Private Functions */
}

?>