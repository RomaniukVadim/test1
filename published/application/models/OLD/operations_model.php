<?php
class Operations_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "pages"; 
    }
	 
	public function getTopicList_() 
	 { 
		 /*$sql = "SELECT a.TopicID, a.Name, COUNT(DISTINCT b.PageID) AS CountPage  
		 		FROM topics AS a 
				LEFT JOIN pages AS b ON a.TopicID=b.TopicID   
				WHERE a.Status='1'    
				GROUP BY a.TopicID
				HAVING CountPage > 0  
				ORDER BY a.Name ASC, a.SortOrder ASC  
				"; */
		$sql = "SELECT a.TopicID, a.Name, COUNT(DISTINCT b.PageID) AS CountPage  
		 		FROM topics AS a 
				LEFT JOIN pages AS b ON a.TopicID=b.TopicID   
				WHERE a.Status='1'    
				GROUP BY a.TopicID 
				ORDER BY a.Name ASC, a.SortOrder ASC  
				"; 
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
	
	public function getPageList_($topic_id, $parent_id)
	 {
		 $sql = "SELECT PageID, Title FROM pages  
				WHERE Status='1' AND TopicID=".$this->db->escape($topic_id)."  AND ParentPageID=".$this->db->escape($parent_id)." 
				ORDER BY SortOrder ASC 
				"; 
		$result = $this->db->query($sql); 
		if($result->num_rows() > 0){
			return $result->result();
		}
		else {
			return array();
		}  
	 }
	 
 
	function viewPageById_($page_id){   
		$sql = "SELECT a.PageID, a.Title, a.SeoUrl, a.Content, a.OriginalFile, a.File, a.ParentPageID, 
					   a.Tags, a.DateUpdated, b.Name AS TopicName, c.mb_nick AS UpdatedBy 
				FROM pages AS a   
				LEFT JOIN topics AS b ON b.TopicID=a.TopicID 
				LEFT JOIN g4_member AS c ON c.mb_no=a.UpdatedBy 
				WHERE a.PageID=".$this->db->escape($page_id)."
					  AND a.Status='1' 
				";
				
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
	
	function getPageBySeoUrl($seo_url){   
		$sql = "SELECT a.PageID, a.Title, a.SeoUrl, a.Content, a.OriginalFile, a.File, a.ParentPageID, 
					   a.Tags, a.DateUpdated, b.Name AS TopicName, c.mb_nick AS UpdatedBy 
				FROM pages AS a   
				LEFT JOIN topics AS b ON b.TopicID=a.TopicID 
				LEFT JOIN g4_member AS c ON c.mb_no=a.UpdatedBy 
				WHERE LCASE(a.SeoUrl)=".$this->db->escape(strtolower($seo_url))."
					  AND a.Status='1' 
				";
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
	
	public function submitComment_($table, $datax, $action="add", $index="", $index_value=""){ 
		$test="";
		$data = array(); 
		
		foreach($datax as $field => $value){ 
			 $data[$field] = $value;
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
	
	
	public function insertPageViews_($table, $data, $data_update){ 
		$data_up = array(); 
		
		foreach($data_update as $field => $value){  
			 $data_up[$field] = (!preg_match("/[\+]/",$value))?$this->db->escape($value):$value; 
		}      
		
		$x = $this->db->on_duplicate($table, $data, $data_up);
		
		return $x;
	}
	 
	
	public function getTutorials_($data) 
	 {  
		if($data[action] == "specific" && $data[topic_id])
		 {
			$where = " AND a.TopicID=".$data[topic_id];
			$limit = "";
			$order = " ORDER BY a.SortOrder ASC, a.Title ASC ";
		 }
		elseif($data[action] == "search" && $data[keywords])
		 {
			$data[keywords] = strtolower(trim(urldecode(urlencode($data[keywords]))));	
			$keywords_arr = explode(" ", $data[keywords]); 	  
			$search_fields = array('a.Title', 'a.SeoUrl', 'a.Excerpt', 'a.Tags', 'c.Name');
			$search_where = "";
			
			if(count($keywords_arr) > 0)
			 {
				 array_push($keywords_arr, $data[keywords]);
				 $search_where .= " ("; 
				 for($i=0; $i<count($search_fields); $i++) {
					for($x=0; $x<count($keywords_arr); $x++) {
						$search_where .= " LCASE(".$search_fields[$i].") LIKE '%".$keywords_arr[$x]."%' OR";
					}//end for
				}//end for
				$search_where = trim($search_where, "OR");
				$search_where .= ") ";
			 }
			 
			
			$where = " AND ".$search_where;
			$limit = "";
			$order = " ORDER BY a.SortOrder ASC, a.Title ASC ";
		
		 }
		else
		 {
			$where = " ";
			$limit = " LIMIT 0, 6 ";	
			$order = " ORDER BY b.ViewCount DESC, a.SortOrder ASC, a.Title ASC "; 
		 }
		 
		 $sql = "SELECT a.PageID, a.Title, a.Excerpt, a.topicID, SUM(b.ViewCount) AS ViewCount, 
		 				c.Name  
		 		FROM pages AS a 
				LEFT JOIN page_view AS b ON a.PageID=b.PageID  
				LEFT JOIN topics AS c ON a.TopicID=c.TopicID    
				WHERE a.Status='1' $where  
				GROUP BY a.PageID 
				$order
				$limit
				";    
		$result = $this->db->query($sql); 
		if($result->num_rows() > 0){ 
			return $result->result();
			/*if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}*/
		}
		else {
			return array();
		} 
		
	 } 
	  
	 
	/* End of Private Functions */
}

?>