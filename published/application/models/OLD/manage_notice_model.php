<?php
class Manage_Notice_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "pages"; 
    }
	
	function checkUsername_($username, $notice_id=""){   
		$where = ($notice_id)?" AND mb_no<>".$notice_id." ":"";
		$sql = "SELECT  mb_no 
			  	FROM {$this->table_name} 
				WHERE LCASE(mb_username) =".strtolower($this->db->escape($username)). $where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	 
	} 
	
	function getNoticeById_($notice_id){  
		$sql = "SELECT  a.PageID, a.Title, a.Excerpt, a.Content, a.OriginalFile, a.File, a.TopicID, a.ParentPageID, a.Tags, a.SortOrder, a.Status, 
						(SELECT COUNT(DISTINCT b.MemberNo) FROM page_view AS b 
						 LEFT JOIN g4_member AS e ON b.MemberNo=e.mb_no 
						 WHERE b.PageID=a.PageID  AND e.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")
						) AS CountViews 
				FROM {$this->table_name} AS a       
				WHERE FIND_IN_SET(a.PageID, ".$this->db->escape($notice_id).") AND (a.Type='notice' OR a.DisplayNotice='1') 
				GROUP BY a.PageID
				";//
	 
		/*$sql = "SELECT  a.PageID, a.Title, a.Excerpt, a.Content, a.OriginalFile, a.File, a.TopicID, a.ParentPageID, a.Tags, a.SortOrder, a.Status, 
						COUNT(DISTINCT b.MemberNo) AS CountViews 
				FROM {$this->table_name} AS a 
				LEFT JOIN page_view AS b ON (a.PageID=b.PageID)     
				INNER JOIN g4_member AS c ON b.MemberNo=c.mb_no AND  c.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")
				WHERE FIND_IN_SET(a.PageID, ".$this->db->escape($notice_id).") AND (a.Type='notice' OR a.DisplayID='1') 
				GROUP BY a.PageID
				";//*/
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
	
	function getParentPage_($topic_id="", $parent_id="", $notice_id=""){  
		$where_pageid = ($notice_id > 0)?" AND PageID<>".$this->db->escape($notice_id):"";
		
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
	 
	public function managePages_($table, $datax, $action="add", $index="", $index_value=""){ 
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
	
	function viewViewers_($notice_id=""){   
		
		$sql = "SELECT a.ViewID, a.PageID, a.MemberNo, a.DateLastViewed, b.mb_nick AS ViewByNickname, b.mb_usertype
				FROM page_view AS a 
				LEFT JOIN g4_member AS b ON a.MemberNo=b.mb_no 
				WHERE a.PageID=".$this->db->escape($notice_id)." AND b.mb_usertype NOT IN(".implode(',', $this->common->restrict_viewcount).")
				GROUP BY a.MemberNo 
				ORDER BY a.DateLastViewed DESC 
				";//   
		$result = $this->db->query($sql);   
		return $result->result(); 
	 
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