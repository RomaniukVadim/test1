<?php
class Manage_Reports_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "reports"; 
    }
	
	function checkUsername_($username, $report_id=""){   
		$where = ($report_id)?" AND mb_no<>".$report_id." ":"";
		$sql = "SELECT  mb_no 
			  	FROM {$this->table_name} 
				WHERE LCASE(mb_username) =".strtolower($this->db->escape($username)). $where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	 
	} 
	
	function getReportsById_($report_id){  
		$sql = "SELECT  a.ReportID, a.Title, a.DateReport, a.Content, a.OriginalFile, a.File, a.AddedBy, a.UpdatedBy, a.DateUpdated, 
						a.Status, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic, 
						(SELECT COUNT(DISTINCT c.MemberNo) FROM reports_view AS c
						 LEFT JOIN g4_member AS e ON c.MemberNo=e.mb_no 
						 WHERE c.ReportID=a.ReportID  AND e.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")
						) AS CountViews    
				FROM {$this->table_name} AS a   
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no  
				WHERE a.ReportID={$this->db->escape($report_id)}  
				GROUP BY a.ReportID ";     
		/*$sql = "SELECT  a.ReportID, a.Title, a.DateReport, a.Content, a.OriginalFile, a.File, a.AddedBy, a.UpdatedBy, a.DateUpdated, 
						a.Status, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic, 
						COUNT(DISTINCT c.MemberNo) AS CountViews   
				FROM {$this->table_name} AS a   
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no 
				LEFT JOIN reports_view AS c ON (a.ReportID=c.ReportID)    
				INNER JOIN g4_member AS d ON c.MemberNo=d.mb_no AND  d.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")    
				WHERE a.ReportID={$this->db->escape($report_id)}  
				GROUP BY a.ReportID ";*/
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
	
	function getReportsMultipleById_($report_id){  
		$sql = "SELECT  a.ReportID, a.Title, a.DateReport, a.Content, a.OriginalFile, a.File, a.AddedBy, a.UpdatedBy, a.DateUpdated, 
						a.Status, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic, 
						(SELECT COUNT(DISTINCT c.MemberNo) FROM reports_view AS c
						 LEFT JOIN g4_member AS e ON c.MemberNo=e.mb_no 
						 WHERE c.ReportID=a.ReportID  AND e.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")
						) AS CountViews
				FROM {$this->table_name} AS a   
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no  
				WHERE FIND_IN_SET(a.ReportID, ".$this->db->escape($report_id).")  
				GROUP BY a.ReportID ";//
				
		/*$sql = "SELECT  a.ReportID, a.Title, a.DateReport, a.Content, a.OriginalFile, a.File, a.AddedBy, a.UpdatedBy, a.DateUpdated, 
						a.Status, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic, 
						COUNT(DISTINCT c.MemberNo) AS CountViews   
				FROM {$this->table_name} AS a   
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no 
				LEFT JOIN reports_view AS c ON (a.ReportID=c.ReportID)   
				INNER JOIN g4_member AS d ON c.MemberNo=d.mb_no AND  d.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")    
				WHERE FIND_IN_SET(a.ReportID, ".$this->db->escape($report_id).")  
				GROUP BY a.ReportID ";//*/
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
	  
	 
	public function manageReports_($table, $datax, $action="add", $index="", $index_value=""){ 
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
			//return $x;  
			return $this->db->insert_id();
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
		 }
		
	} 
	 
	function getReportsReplies_($report_id){  
		$sql = "SELECT  a.ReplyID, a.Content, a.AddedBy, a.UpdatedBy, a.DateUpdated AS ReplyDate, a.OriginalFile, a.File, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic 
				FROM reports_replies AS a   
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no 
				WHERE a.ReportID={$this->db->escape($report_id)} AND a.Status='1' 
				ORDER BY DateAdded ASC, DateUpdated ASC  ";//
		$result = $this->db->query($sql); 
		return $result->result();
	}
	  
	function getReplyById_($reply_id){  
		$sql = "SELECT  a.ReplyID, a.Content, a.AddedBy, a.UpdatedBy, a.DateUpdated AS ReplyDate, a.OriginalFile, a.File, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic 
				FROM reports_replies AS a   
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no 
				WHERE a.ReplyID={$this->db->escape($reply_id)}  
				ORDER BY DateUpdated ASC  ";//
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
	 
	
	public function deleteReply_($table, $params , $index){ 
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":"";
		
		$reply_id = $params[reply_id]; 
		  
		$this->db->where("ReplyID = {$reply_id} $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	public function submitReply_($table, $datax, $action="add", $index="", $index_value=""){ 
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
	
	public function insertReportViews_($table, $data, $data_update){ 
		$data_up = array(); 
		
		foreach($data_update as $field => $value){  
			 $data_up[$field] = (!preg_match("/[\+]/",$value))?$this->db->escape($value):$value; 
		}      
		
		$x = $this->db->on_duplicate($table, $data, $data_up);
		
		return $x;
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