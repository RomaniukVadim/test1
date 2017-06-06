<?php
class Websites_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "websites"; 
    }
	 
	
	public function deleteWebsites_($table, $params , $index){
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":"";
		
		$selected_values = $params[selected_values];  
		$this->db->where("FIND_IN_SET(WebsiteID, '".$selected_values."') $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	function getWebsiteById_($website_id){   
		$sql = "SELECT * FROM websites  
				WHERE FIND_IN_SET(WebsiteID, '".$website_id."')";//
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
	
	 
		
	public function getWebsites_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$aColumnsWhere = array('a.WebsiteID', 'a.Name', 'a.Link', 'b.mb_nick', 'a.DateUpdated', 'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.Status',  );
		$aColumns = array('a.WebsiteID', 'a.Name', 'a.Link', 'b.mb_nick', 'a.DateUpdated', 'a.Status', 'a.Action' );
		
		$sIndexColumn = "a.WebsiteID";
		//(SELECT GROUP_CONCAT(' ', LCASE(TRIM(d.Name))) FROM language AS d WHERE FIND_IN_SeT(d.LanguageID, a.mb_access)) AS Access
		/* DB table to use */
		$sTable = "websites AS a";
		
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
			$sWhere = "WHERE a.Status<>'9'  AND (";
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
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no  	
					 "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere)).", 
					a.AddedBy
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
				$edit_link = (admin_access())?base_url().'manage-websites/'.$aRow[WebsiteID]:base_url().'view-websites/'.$aRow[WebsiteID];
				$view_link = base_url().'view-websites/'.$aRow[WebsiteID];
					 
				if( $aColumns[$i] == "a.WebsiteID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['WebsiteID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.Name" )
				 {  
					$row[] = '<a href="'.$edit_link.'"  class="tip" title="Manage website/link" >'.$aRow[Name].'</a> ';
				 }
				elseif ( $aColumns[$i] == "a.Link" )
				 { 
					$row[] = '<a href="'.$aRow[Link].'" target="_blank" class="tip" title="Visit link" >'.$aRow[Link].'</a>';
				 }
				elseif ( $aColumns[$i] == "b.mb_nick" )
				 { 
					$row[] = $aRow[mb_nick];
				 }
				elseif ( $aColumns[$i] == "a.DateUpdated" )
				 {
					$row[] = ($aRow[DateUpdated] != "0000-00-00 00:00:00")?$aRow[DateUpdated]:""; 
				 }
				elseif ( $aColumns[$i] == "a.Status" )
				 {
					$row[] = ($aRow[Status]==1)?"Active":"<span class='inactive_stat'>Inactive</span>";
				 } 
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit user" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link;
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")))?$link.'<a class="tip delete_one" title="delete user"  website-id="'.$aRow[WebsiteID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link;													 
					$row[] = $link; 
				 }
				else if ( $aColumns[$i] != ' ' )
				 {
					/* General output */
					//$row[] = $aRow[ $aColumns[$i] ];
				 }
			}
			$output['aaData'][] = $row; 
			$ctr++;
		}//end while
		
		echo json_encode( $output );
		
	}
	
	function searchWebsite_($keywords="", $where=array(), $action=""){ 
		 
		$aColumnsWhere = array('a.Name', 'a.Link', 'a.Description', 'b.mb_nick', 'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END'); 
		$sWhere = " a.WebsiteID<>0 "; 
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
							   
		$sql = "SELECT a.*, CASE a.Status WHEN '1' THEN 'Active' ELSE 'Inactive' END AS StatusState, b.mb_nick
				FROM websites AS a     
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no
				WHERE ".$sWhere.
				" ORDER BY a.DateUpdated DESC ";  
				
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