<?php
class Manage_Warning_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "performance_warning"; 
    }
	
	function checkUsername_($username, $warning_id=""){   
		$where = ($warning_id)?" AND mb_no<>".$warning_id." ":"";
		$sql = "SELECT  mb_no 
			  	FROM {$this->table_name} 
				WHERE LCASE(mb_username) =".strtolower($this->db->escape($username)). $where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	 
	} 
	
	function getWarningById_($warning_id){  
		//$where_admin = (!admin_access())?" AND Assignee={$this->session->userdata(mb_no)} AND a.Status='1' ":""; 
		if(!admin_access())
		 {
			 if(view_only())
			  {
				  $where_admin = " AND a.Status='1' "; 
			  }
			 else
			  {
				  $where_admin = " AND Assignee={$this->session->userdata(mb_no)} AND a.Status='1' "; 
			  }
		 }
		
		$sql = "SELECT a.WarningID, a.Assignee, a.Case, a.OffenseID, a.DateIssue, a.ControlNo, a.Status, 
					   CASE a.Status WHEN '1' THEN 'Active' WHEN '0' THEN 'Inactive' ELSE 'Deleted' END StatusName,   
					   a.StaffExplanation, a.StaffRequest, a.StaffSpecify, a.SupervisorRemarks, a.HrRemarks, a.ManagementRemarks,  
					   CONCAT(b.mb_fname,' ',b.mb_lname) AS EmployeeName, b.mb_nick AS AssigneeNickname, c.Name OffenseName, 
					   d.mb_nick,CONCAT(d.mb_fname,' ',d.mb_lname) AS IssuedBy    
				FROM {$this->table_name} AS a   
				LEFT JOIN g4_member AS b ON a.Assignee=b.mb_no 
				LEFT JOIN offenses AS c ON a.OffenseID=c.OffenseID 
				LEFT JOIN g4_member AS d ON a.AddedBy=d.mb_no
				WHERE FIND_IN_SET(a.WarningID, ".$this->db->escape($warning_id).") $where_admin ";//
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
	
	function getParentPage_($topic_id="", $parent_id="", $warning_id=""){  
		$where_pageid = ($warning_id > 0)?" AND PageID<>".$this->db->escape($warning_id):"";
		
		$sql = "SELECT PageID, Title, ParentPageID    
				FROM {$this->table_name}   
				WHERE TopicID=".$this->db->escape($topic_id)." $where_pageid AND Status='1' 
				ORDER BY SortOrder ASC 
				";//   
		$result = $this->db->query($sql);   
		if($result->num_rows() > 0){
			/*if($result->num_rows() == 1){
				return $result->row();
			}
			else{*/
				return $result->result();
			//}
			 
		}
		else {
			return array();
		} 
	 
	} 
	
	 
	public function manageWarning_($table, $datax, $action="add", $index="", $index_value=""){ 
		$test="";
		$data = array(); 
		  
		foreach($datax as $field => $value){ 
			if($field == "mb_password") 
			 { 
				 $this->db->set('mb_password', 'PASSWORD("'.$value.'")', FALSE);
			 }
			else 
			 {
				$data[$field] = $value; 
			 }  
			 $test .= $field.'-';
		}   
	 
		//$this->db->set('mb_password', 'PASSWORD("'.$data('user_password', TRUE).'")', FALSE);
		
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$data);  
			return $this->db->insert_id();
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
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