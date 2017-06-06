<?php
class Warning_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "performance_warning"; 
    }
	 
	
	public function deleteWarning_($table, $params , $index){
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":"";
			
		$selected_values = $params[selected_values]; 
		$this->db->where("FIND_IN_SET(".$index.", '".$selected_values."') $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}  
	
	function getWarningById_($warning_id){  
		$sql = "SELECT a.*, CASE a.Status WHEN '1' THEN 'Active' WHEN '0' THEN 'Inactive' ELSE 'Deleted' END StatusName, 
					  CONCAT(b.mb_fname,' ',b.mb_lname) AS EmployeeName, c.Name OffenseName, d.mb_nick,CONCAT(d.mb_fname,' ',d.mb_lname) AS IssuedBy  
				FROM {$this->table_name} AS a  
				LEFT JOIN g4_member AS b ON a.Assignee=b.mb_no 
				LEFT JOIN offenses AS c ON a.OffenseID=c.OffenseID   
				LEFT JOIN g4_member AS d ON a.AddedBy=d.mb_no
				WHERE FIND_IN_SET(a.WarningID, ".$this->db->escape($warning_id).") ";//
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
	
	
	function getWarningByIdMultiple_($warning_id){   
		$custom_where = (!admin_only())?" AND a.AddedBy=".$this->session->userdata('mb_no')." ":"";
		
		$sql = "SELECT a.*, CASE a.Status WHEN '1' THEN 'Active' WHEN '0' THEN 'Inactive' ELSE 'Deleted' END StatusName, 
					   CONCAT(b.mb_fname,' ',b.mb_lname) AS EmployeeName, c.Name OffenseName, d.mb_nick,CONCAT(d.mb_fname,' ',d.mb_lname) AS IssuedBy  
				FROM {$this->table_name} AS a  
				LEFT JOIN g4_member AS b ON a.Assignee=b.mb_no 
				LEFT JOIN offenses AS c ON a.OffenseID=c.OffenseID   
				LEFT JOIN g4_member AS d ON a.AddedBy=d.mb_no
				WHERE FIND_IN_SET(a.WarningID, ".$this->db->escape($warning_id).") ";//
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
		
	public function getWarning_($type="warning")
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$aColumnsWhere = array('a.WarningID', 'CONCAT(b.mb_fname," ",b.mb_lname)', 'a.Case', 'c.Name', 'a.DateIssue', 'a.ControlNo', 
							   'd.mb_nick', 'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.Status', 'b.mb_fname', 'b.mb_lname' 
							  );
		$aColumns = array('a.WarningID', 'CONCAT(b.mb_fname," ",b.mb_lname)', 'a.Case', 'c.Name', 'a.DateIssue', 'a.ControlNo', 'd.mb_nick', 'a.Status', 'a.Action');
		
		$sIndexColumn = "a.WarningID";
		 
		/* DB table to use */
		$sTable = "performance_warning AS a";
		
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
			$sWhere = "WHERE a.Status<>'9' AND (";
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
					$sWhere = "WHERE a.Status<>'9' AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		$sWhere = ($sWhere=="")?"WHERE a.Status<>'9' ":$sWhere;  //jdereal 
		
		//$sWhere = (!admin_access() && !view_only())?$sWhere." AND Assignee={$this->session->userdata(mb_no)} AND a.Status='1' ":$sWhere; 
		if(!admin_access())
		 {
			 if(view_only())
			  {
				  $sWhere .= " AND a.Status='1' "; 
			  }
			 else
			  {
				  $sWhere .= " AND Assignee={$this->session->userdata(mb_no)} AND a.Status='1' "; 
			  }
		 }
		 
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN g4_member AS b ON a.Assignee=b.mb_no  
					   LEFT JOIN offenses AS c ON a.OffenseID=c.OffenseID 
					   LEFT JOIN g4_member AS d ON a.AddedBy=d.mb_no  
					 "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere)).", 
					b.mb_nick AS AssigneeName, a.AddedBy
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
			WHERE a.Status<>'9' 
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
				 
				//'a.WarningID', 'a.Title', 'a.TopicID', 'a.File', 'a.DateUpdated',  'a.SortOrder', 'a.Status', 'b.mb_nick', 'c.Name'
				$edit_link = (admin_access())?base_url().'manage-warning/'.$aRow[WarningID]:base_url().'view-warning/'.$aRow[WarningID];
				$view_link = base_url().'view-warning/'.$aRow[WarningID];
				 
				if( $aColumns[$i] == "a.WarningID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['WarningID'].'" >';
				 }  
				elseif ( $aColumns[$i] == 'CONCAT(b.mb_fname," ",b.mb_lname)' )
				 {  
					$row[] = '<a href="'.$edit_link.'" class="tip" title="manage warning" >'.$aRow[AssigneeName].'</a> ';
				 } 
				elseif ( $aColumns[$i] == "a.Case" )
				 { 
					$row[] = '<span class="tip" title="'.$aRow['Case'].'" >'.character_limiter($aRow['Case'], 20).'</span>';//$aRow['Case']; 
				 }
				elseif ( $aColumns[$i] == "c.Name" )
				 { 
					$row[] = $aRow[Name];
				 }
				elseif ( $aColumns[$i] == "a.DateIssue" )
				 {  
					$row[] = ($aRow[DateIssue] != "0000-00-00")?$aRow[DateIssue]:""; 
				 }
				elseif ( $aColumns[$i] == "a.ControlNo" )
				 { 
					$row[] = $aRow[ControlNo];
				 }
				elseif ( $aColumns[$i] == "d.mb_nick" )
				 { 
					$row[] = $aRow[mb_nick];
				 } 
				elseif ( $aColumns[$i] == "a.Status" )
				 {  
					$row[] = ($aRow[Status]=='1')?"Active":"<span class='inactive_stat'>Inactive</span>"; 
				 }
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view warning" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit warning" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link;
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")) )?$link.'<a class="tip delete_one" title="delete warning"  warning-id="'.$aRow[WarningID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
					$row[] = $link; 
				 }
				elseif ( $aColumns[$i] != ' ' )
				 {
					/* General output */
					//$row[] = $aRow[ $aColumns[$i] ];
				 }
				else 
				 {
					 //$row[] = $aRow[ $aColumns[$i] ];
				 }
			}
			$output['aaData'][] = $row; 
			$ctr++;
		}//end while
		
		echo json_encode( $output );
		
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