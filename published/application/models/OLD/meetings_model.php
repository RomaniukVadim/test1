<?php
class Meetings_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "meetings"; 
    }
	 
	
	public function deleteMeetings_($table, $params , $index){ 
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":""; 
		
		$selected_values = $params[selected_values]; 
		  
		$this->db->where("FIND_IN_SET(".$index.", '".$selected_values."')  $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	function getMeetingById_($meeting_id){   
		$usertype_sql = ($this->session->userdata("mb_usertype") == '3')?" AND a.Type='1' ":" ";
		$sql = "SELECT a.*, b.TypeName, c.mb_nick AS PreparedByName, d.mb_nick AS ReviewedByName, e.mb_nick AS AddedByName, f.mb_nick AS UpdatedByName 
				FROM meetings AS a  
					LEFT JOIN meeting_types AS b ON a.Type=b.TypeID 
					LEFT JOIN g4_member AS c on a.PreparedBy=c.mb_no 
					LEFT JOIN g4_member AS d on a.ReviewedBy=d.mb_no 
					LEFT JOIN g4_member AS e on a.AddedBy=d.mb_no  
					LEFT JOIN g4_member AS f on a.UpdatedBy=f.mb_no 
				WHERE FIND_IN_SET(a.MeetingID, '".$meeting_id."') {$usertype_sql} ";//
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
		
	public function getMeetings_()
	{ 
		$usertype_sql = (!admin_access())?" AND a.Type<>'2' ":" "; 
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$aColumnsWhere = array('a.MeetingID', 'a.Title', 'b.TypeName', 'a.DateStarted', 'a.DateEnd', 'c.mb_nick', 'd.mb_nick');
		$aColumns = array('a.MeetingID', 'a.Title', 'b.TypeName', 'a.DateStarted', 'a.DateEnd', 'c.mb_nick', 'd.mb_nick', 'a.Action');
		
		$sIndexColumn = "a.MeetingID";
		//(SELECT GROUP_CONCAT(' ', LCASE(TRIM(d.Name))) FROM language AS d WHERE FIND_IN_SeT(d.LanguageID, a.mb_access)) AS Access
		/* DB table to use */
		$sTable = "meetings AS a";
		
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
			$sWhere = "WHERE a.Status<>'9' $usertype_sql AND (";
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
					$sWhere = "WHERE a.Status<>'9' $usertype_sql AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		$sWhere = ($sWhere=="")?"WHERE a.Status<>'9' $usertype_sql ":$sWhere;  //jdereal
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN meeting_types AS b ON a.Type=b.TypeID 
					   LEFT JOIN g4_member AS c ON a.PreparedBy=c.mb_no
					   LEFT JOIN g4_member AS d ON a.ReviewedBy=d.mb_no   
					 "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere)).",  
								c.mb_nick AS PreparedByName, d.mb_nick AS ReviewedByName, a.AddedBy 
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
			WHERE a.Status<>'9' $usertype_sql  
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
				 
				//'a.MeetingID', 'a.Title', 'a.TopicID', 'a.File', 'a.DateUpdated',  'a.SortOrder', 'a.Status', 'b.mb_nick', 'c.Name'
				$edit_link = ((admin_access() || view_access()) && !view_management)?base_url().'manage-meetings/'.$aRow[MeetingID]:base_url().'view-meetings/'.$aRow[MeetingID];
				$view_link = base_url().'view-meetings/'.$aRow[MeetingID];
				 
				if( $aColumns[$i] == "a.MeetingID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['MeetingID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.Title" )
				 {  
					$row[] = '<a href="'.$edit_link.'" class="tip" title="'.$aRow['Title'].'" >'.character_limiter($aRow['Title'], 30).'</a> ';
				 } 
				elseif ( $aColumns[$i] == "b.TypeName" )
				 { 
					$row[] = $aRow[TypeName];
				 }  
				elseif ( $aColumns[$i] == "a.DateStarted" )
				 { 
					$row[] = ($aRow[DateStarted] != "0000-00-00 00:00:00")?$aRow[DateStarted]:""; 
				 }
				elseif ( $aColumns[$i] == "a.DateEnd" )
				 { 
					$row[] = ($aRow[DateEnd] != "0000-00-00 00:00:00")?$aRow[DateEnd]:""; 
				 } 
				elseif ( $aColumns[$i] == "c.mb_nick" )
				 { 
					$row[] = $aRow[PreparedByName];
				 }
				elseif ( $aColumns[$i] == "d.mb_nick" )
				 { 
					$row[] = $aRow[ReviewedByName];
				 }
				elseif ( $aColumns[$i] == "a.Status" )
				 {  
					$row[] = ($aRow[Status]=='1')?"Active":"<span class='inactive_stat'>Inactive</span>"; 
				 }
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view meeting" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = ((admin_access() || view_access()) && !view_management() )?$link.'<a href="'.$edit_link.'" class="tip" title="edit meeting" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link; 
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")) )?$link.'<a class="tip delete_one" title="delete meeting"  meeting-id="'.$aRow[MeetingID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
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