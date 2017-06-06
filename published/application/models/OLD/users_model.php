<?php
class Users_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "g4_member"; 
    }
	 
	
	public function deleteUsersXXX_($table, $params , $index){
		$selected_values = $params[selected_values]; 
		 
		/*$this->db->where("FIND_IN_SET(BannerID, '$selected_values')");
		$x = $this->db->update($table, $data);*/ 
		
		$this->db->where("FIND_IN_SET(mb_no, '".$selected_values."')");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	public function deleteUsers_($table, $params , $index){
		if($params == "")
		 {
			 $error .= "No selected users!<br> ";
		 } 
		 
		if($error)
		 { 
			echo "0|||".$error;
		 }
		else
		 {
			  
			    
			$result = explode(",", $params[selected_values]); 
			  
			$date_updated = date("Y-m-d H:i:s");  
			if(count($result)==1)
			 { 
				$data = array( 
				  'mb_no'=>$result[0],
				  'mb_status'=>'9',  
				  'mb_updatedby'=>$this->session->userdata("mb_no"), 
				  'mb_datetime'=>$date_updated
				);   
			 }
			else
			 {
				 $data = array();
				 $data2 = array();  
				 $i=0;
				 foreach($result as $row=>$old) {
					$data[$i] = array( 
					  'mb_no'=>$result[$row],
					  'mb_status'=>'9',  
					  'mb_updatedby'=>$this->session->userdata("mb_no"), 
					  'mb_datetime'=>$date_updated
					);    
					$i++; 	   
				}//end foreach  
				
			 }
			   
			//update_table_entry
			if(count($result) > 0)
			 {
				
				$field_condition = array("mb_no"=>$this->input->post("selected_values")); 	
				$x = (count($result)>1)?$this->common->batchUpdate_('g4_member', $data, "mb_no"):$this->users->update_table_entry("g4_member", $data, $field_condition);   
				
				if($x > 0)
				 {   	 
					echo "1|||User(s) deleted successfully.";    
				 }
				else
				 { 
					 echo "0|||Error deleting user(s)!";
				 } 
			 }
			else
			 {
				//no changes 
				echo "1|||No changes made.";  
			 } 
			 
		 }
		 
	}//end delete
	
	
	function getUserById_($user_id){ 
		 /*$sql = "SELECT * FROM banners_12bet  
				WHERE BannerID=".$this->db->escape($banner_id); */ 
		$sql = "SELECT * FROM g4_member  
				WHERE FIND_IN_SET(mb_no, '".$user_id."')";//
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
    
	public function generate_image($img, $width, $height, $cache){

        //$obj =& get_instance();

        //$obj->load->library('image_lib');
        //$obj->load->helper('url');

        $config['image_library'] = 'gd2';

        $config['source_image'] = $img;
        $config['new_image'] = $cache; 
        $config['width'] = $width;
        $config['height'] = $height;

        $this->image_lib->initialize($config);
		
        //$this->image_lib->resize();
        //if(!$this->image_lib->resize()) echo $this->image_lib->display_errors();
		return $cache;
    }
		
	public function getUsers_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$admin_where = (admin_only())?"":" AND a.mb_usertype<>2 ";
		$admin_where = ($this->session->userdata('mb_usertype')<>1)?$admin_where." AND a.mb_usertype<>5 ":$admin_where;//others users
		
		$aColumnsWhere = array('a.mb_no', 'a.mb_profilepic', 'a.mb_nick', 'a.mb_username', 'a.mb_email', 'b.Name','a.mb_today_login',
							   'CASE a.mb_status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.mb_fname', 'a.mb_lname',  'a.mb_status', 
							  );
		$aColumns = array('a.mb_no', 'a.mb_profilepic', 'a.mb_nick', 'a.mb_username', 'a.mb_email', 'b.Type', 'a.mb_today_login', 'a.mb_status', 'a.action');
		
		$sIndexColumn = "a.mb_no";
		//(SELECT GROUP_CONCAT(' ', LCASE(TRIM(d.Name))) FROM language AS d WHERE FIND_IN_SeT(d.LanguageID, a.mb_access)) AS Access
		/* DB table to use */
		$sTable = "g4_member AS a";
		
		/* Database connection information */
		$gaSql['user'] = $this->db->username;
		$gaSql['password'] = $this->db->password;
		$gaSql['db'] = $this->db->database;
		$gaSql['server'] = $this->db->hostname; 		
		
		/*$gaSql['user']     	 = "betindo_12design";
		$gaSql['password']   = "y12SuQHB";
		$gaSql['db']         = "betindo_designers"; 
		$gaSql['server']     = "localhost";*/
		
		
		/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
		 * If you just want to use the basic configuration for DataTables with PHP server-side, there is
		 * no need to edit below this line
		 */
		
		/* 
		 * MySQL connection
		 */
		$gaSql['link'] =  mysql_pconnect( $gaSql['server'], $gaSql['user'], $gaSql['password']  ) or
			die( 'Could not open connection to server' );
		
		mysql_select_db( $gaSql['db'], $gaSql['link'] ) or 
			die( 'Could not select database '. $gaSql['db'] );
		
		
		/* 
		 * Paging
		 */
		$sLimit = "";
		if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
		{
			$sLimit = "LIMIT ".mysql_real_escape_string( $_GET['iDisplayStart'] ).", ".
				mysql_real_escape_string( $_GET['iDisplayLength'] );
		}
		
		
		/*
		 * Ordering
		 */
		if ( isset( $_GET['iSortCol_0'] ) )
		{
			$sOrder = "ORDER BY  ";
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$sOrder .= $aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."
						".mysql_real_escape_string( $_GET['sSortDir_'.$i] ) .", ";
				}
			}
			
			$sOrder = substr_replace( $sOrder, "", -2 );
			if ( $sOrder == "ORDER BY" )
			{
				$sOrder = "";
			}
		}
		
		
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited 
		 */
		$sWhere = "";
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE a.mb_status<>'9' AND a.mb_usertype<>1 $admin_where AND a.mb_no <>".mysql_real_escape_string($this->session->userdata('mb_no'))." AND (";
			for ( $i=0 ; $i<count($aColumnsWhere) ; $i++ )
			{
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */ 
		for ( $i=0 ; $i<count($aColumnsWhere) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE a.mb_status<>'9' AND a.mb_usertype<>1 $admin_where  AND a.mb_no <>".mysql_real_escape_string($this->session->userdata('mb_no'))." AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		 
		$sWhere = ($sWhere=="")?"WHERE a.mb_status<>'9' AND a.mb_usertype<>1 $admin_where AND a.mb_no <>".mysql_real_escape_string($this->session->userdata('mb_no'))." ":$sWhere;   //jdereal 
		
		
		
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN users_types AS b ON a.mb_usertype=b.GroupID "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere))."
			FROM $sTable  
			$sLeftJoin
			$sWhere 
			$sOrder
			$sLimit
		"; 
		 
		$rResult = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		 	
		/* Data set length after filtering */
		$sQuery = "
			SELECT FOUND_ROWS()  
		";
		$rResultFilterTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
		$iFilteredTotal = $aResultFilterTotal[0];
		
		/* Total data set length */
		$sQuery = "
			SELECT COUNT(".$sIndexColumn.")
			FROM   $sTable   
			WHERE a.mb_status<>'9' AND a.mb_usertype<>1 $admin_where AND a.mb_no <>".mysql_real_escape_string($this->session->userdata('mb_no'))."
		";
		$rResultTotal = mysql_query( $sQuery, $gaSql['link'] ) or die(mysql_error());
		$aResultTotal = mysql_fetch_array($rResultTotal);
		$iTotal = $aResultTotal[0];
		
		
		/*
		 * Output
		 */ 
		$output = array(
			"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);
		
		$ctr = 0; 
		while ( $aRow = mysql_fetch_array( $rResult ) )
		{
			$row = array(); 
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{	
				$edit_link = (admin_access())?base_url().'manage-users/'.$aRow[mb_no]:base_url().'view-user/'.$aRow[mb_no];
				$view_link = base_url().'view-user/'.$aRow[mb_no];
				 
				if( $aColumns[$i] == "a.mb_no" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['mb_no'].'" >';
				 }
				elseif( $aColumns[$i] == "a.mb_profilepic" )
				 {
					/* Special output formatting for 'version' column */
					//$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
					
					//jdreal
					if($aRow['mb_profilepic'] && file_exists("media/uploads/".$aRow['mb_profilepic']))
					 {	
					 	$config['image_library'] = 'gd2';
						$config['source_image'] = "media/uploads/".$aRow['mb_profilepic'];
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						$config['width'] = 50;
						$config['height'] = 50;
						
						
						$new_filename = basename($aRow['mb_profilepic']); 
						$data_path  = str_replace($new_filename, "", $aRow['mb_profilepic']);
						
						$config['new_image'] = "media/uploads/cache/".$data_path; 
						$config['thumb_marker'] = ""; 
						
						if(!is_dir($config['new_image'])) mkdir($config['new_image'], 0777, true);
						
						$this->image_lib->clear(); 
						$this->image_lib->initialize($config);
						if(!file_exists($config['new_image'].$new_filename))$this->image_lib->resize();  
						
						$thumbimg = (file_exists($config['new_image'].$new_filename))?$config['new_image'].$new_filename:"media/images/avatar-male.jpg";
						//if(!$this->image_lib->crop()) echo $this->image_lib->display_errors();
						$row[] = '<a href="'.$edit_link.'" ><img src="'.site_url().$thumbimg.'" alt="'.$aRow[mb_nick].'" title="'.$aRow[mb_nick].'"  border="0" ></a>';
						
					 }
					else
					 {
						$row[] = ""; 
					 } 
					//end jdereal  
				 }
				elseif ( $aColumns[$i] == "a.mb_nick" )
				 {
					//$row[] = '<a href="'.base_url().'manage-banners/'.$aRow[BannerID].'" >'.$aRow[Title].'</a> ';
				 	$row[] = '<a class="tip" title="'.$aRow[mb_fname].' '.$aRow[mb_lname].'" href="'.$edit_link.'" >'.$aRow[mb_nick].'</a> ';
				 }
				elseif ( $aColumns[$i] == "a.mb_username" )
				 { 
				 	$row[] = $aRow[mb_username]; 
				 } 
				elseif ( $aColumns[$i] == "a.mb_email" )
				 { 
					$row[] = $aRow[mb_email];
				 }
				elseif ( $aColumns[$i] == "b.Type" )
				 { 
					$row[] = $aRow[Name]; 
				 }
				elseif ( $aColumns[$i] == "a.mb_today_login" )
				 {
					$row[] = ($aRow[mb_today_login] != "0000-00-00 00:00:00")?$aRow[mb_today_login]:""; 
				 }
				elseif ( $aColumns[$i] == "a.mb_status" )
				 {
					$row[] = ($aRow[mb_status]=='1')?"Active":"<span class='inactive_stat'>Inactive</span>"; 
				 }
				elseif ( $aColumns[$i] == "a.action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>'; 
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit user" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link;
					$link = (admin_only())?$link.'<a class="tip delete_one" title="delete user"  user-id="'.$aRow[mb_no].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
					$row[] = $link; 
				 }
				else if ( $aColumns[$i] != ' ' )
				 {
					/* General output */
					$row[] = $aRow[ $aColumns[$i] ];
				 }
			}
			$output['aaData'][] = $row; 
			$ctr++;
		}//end while
		
		echo json_encode( $output );
		
	}
	
	
	function searchUser_($keywords="", $where=array(), $action=""){ 
		 
		$aColumnsWhere = array('a.mb_no', 'a.mb_username', 'a.mb_nick', 'a.mb_email', 'a.mb_fname', 'a.mb_lname', 'b.Name', 'a.mb_today_login', 'a.mb_status', 
								'CASE a.mb_status WHEN "1" THEN "Active" ELSE "Inactive" END'); 
		$sWhere = " a.mb_status<>'9' AND a.mb_usertype<>1 AND a.mb_no <>".$this->db->escape($this->session->userdata('mb_no'));
		
		if($action == "specific")
		 { 
			foreach($where as $field=>$value){
				$sWhere .= " AND (".$field."=".$this->db->escape($value).") "; 
			} 
		 }
		else
		 {
			if ($keywords != "")
			{
				$sWhere .= " AND (";
				for ( $i=0 ; $i<count($aColumnsWhere) ; $i++ )
				{
					$sWhere .= $aColumnsWhere[$i]." LIKE ".$this->db->escape('%'.$keywords.'%')." OR ";
				}
				$sWhere = substr_replace( $sWhere, "", -3 );
				$sWhere .= ')';
			}  
			
		 }
		 
							   
		$sql = "SELECT a.*, CONCAT(mb_fname,' ', mb_lname) AS FullName, CASE a.mb_status WHEN '1' THEN 'Active' ELSE 'Inactive' END AS StatusState, b.Name 
				FROM g4_member AS a   
				LEFT JOIN users_types AS b ON a.mb_usertype=b.GroupID 
				WHERE ".$sWhere."
				ORDER BY a.mb_datetime DESC 
				"; 
		$result = $this->db->query($sql);   
		if($result->num_rows() > 0){
			/*if($result->num_rows() == 1){
				return $result->row();
			}
			else{
				return $result->result();
			}*/
			return $result->result();
		}
		else {
			return array();
		} 
	 
	}
	
	public function selectStrict_($where=array(),$table = "",$order=array(),$offset=0,$limit=0){
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