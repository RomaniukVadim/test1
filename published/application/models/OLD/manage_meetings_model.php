<?php
class Manage_Meetings_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "meetings"; 
    }
	 
	
	function getMeetingsById_($meeting_id){
		$usertype_sql = ($this->session->userdata("mb_usertype") == '3')?" AND a.Type='1' ":" ";
			   
		$sql = "SELECT a.*, b.TypeName, c.mb_nick AS PreparedByName, d.mb_nick AS ReviewedByName, e.mb_nick AS AddedByName, 
					   f.mb_nick AS UpdatedByName, f.mb_profilepic AS ProfilePic
				FROM {$this->table_name} AS a  
					LEFT JOIN meeting_types AS b ON a.Type=b.TypeID 
					LEFT JOIN g4_member AS c on a.PreparedBy=c.mb_no 
					LEFT JOIN g4_member AS d on a.ReviewedBy=d.mb_no 
					LEFT JOIN g4_member AS e on a.AddedBy=e.mb_no  
					LEFT JOIN g4_member AS f on a.UpdatedBy=f.mb_no 
				WHERE a.MeetingID=".$meeting_id." ".$usertype_sql." ";// 
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
	
	function getMeetingsReplies_($meeting_id){
		///$usertype_sql = ($this->session->userdata("mb_usertype") == '3')?" AND a.Type='1' ":" ";
			   
		$sql = "SELECT a.*, b.Title, b.Type, c.mb_nick AS PreparedByName, 
					   d.mb_nick AS ReviewedByName, e.mb_nick AS AddedByName, f.mb_nick AS UpdatedByName, f.mb_profilepic AS ProfilePic, 
					   g.TypeName   
				FROM meetings_replies AS a  
					LEFT JOIN meetings AS b ON a.MeetingID=b.MeetingID 
					LEFT JOIN g4_member AS c ON a.PreparedBy=c.mb_no 
					LEFT JOIN g4_member AS d ON a.ReviewedBy=d.mb_no 
					LEFT JOIN g4_member AS e ON a.AddedBy=e.mb_no  
					LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no 
					LEFT JOIN meeting_types AS g ON b.Type=g.TypeID  
				WHERE a.MeetingID=".$meeting_id;//  
				
		$result = $this->db->query($sql);  
		return $result->result();
		/*if($result->num_rows() > 0){
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
	 
	} 
	
	 
   
	public function manageMeetings_($table, $datax, $action="add", $index="", $index_value=""){ 
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
			return $x; 
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
		 }
		
	} 
	
	public function getMeetingReplyById_($reply_id){
			   
		$sql = "SELECT a.*, b.Title, b.Type, c.mb_nick AS PreparedByName, d.mb_nick AS ReviewedByName, 
					   e.mb_nick AS AddedByName, f.mb_nick AS UpdatedByName, 
					   g.TypeName  
				FROM meetings_replies AS a  
					LEFT JOIN meetings AS b ON a.MeetingID=b.MeetingID 
					LEFT JOIN g4_member AS c ON a.PreparedBy=c.mb_no 
					LEFT JOIN g4_member AS d ON a.ReviewedBy=d.mb_no 
					LEFT JOIN g4_member AS e ON a.AddedBy=e.mb_no  
					LEFT JOIN g4_member AS f ON a.UpdatedBy=f.mb_no 
					LEFT JOIN meeting_types AS g ON b.Type=g.TypeID
				WHERE a.ReplyID=".$reply_id;  
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