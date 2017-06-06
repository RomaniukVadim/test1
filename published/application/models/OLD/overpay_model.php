<?php
class Overpay_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "overpay_case"; 
    }
	 
	
	public function deleteOverpay_($table, $params , $index){
		$selected_values = $params[selected_values];  
		$this->db->where("FIND_IN_SET(OverpayID, '".$selected_values."')");
		$x = $this->db->delete($table);
		return $x; 
	}
	
 
	public function getOverpayByIdMultiple_($overpay_id){   
		$sql = "SELECT a.OverpayID, a.Username, a.Currency, a.TransactionNo, a.TransactionDate, a.OverpaidAmount, a.RecoveredAmount, a.AccountablePerson, 
					   a.AdjustmentProcess, a.Case, a.Remarks, a.Status, b.Name AS StatusName, GROUP_CONCAT(c.mb_nick SEPARATOR ',') AS mb_nick,  GROUP_CONCAT(CONCAT(c.mb_fname,' ',c.mb_lname)) AS AccountablePersonName, d.Abbreviation, 
					   e.ProcessName, f.mb_nick AS UpdatedBy 
				FROM {$this->table_name} AS a  
				LEFT JOIN overpay_status AS b ON a.Status=b.StatusID 
				LEFT JOIN g4_member AS c ON a.AccountablePerson=c.mb_no 
				LEFT JOIN currency AS d ON a.Currency=d.CurrencyID 
				LEFT JOIN adjustment_process AS e ON a.AdjustmentProcess=e.ProcessID  
				LEFT JOIN g4_member AS f ON a.Updatedby=f.mb_no   
				WHERE FIND_IN_SET(OverpayID, ".$this->db->escape($overpay_id).")
				GROUP BY a.OverpayID  ";// 
						
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
	 
	public function update_table_entry($table,$data,$param){
		$this->db->update($table,$data,$param);
		return $this->db->affected_rows();
	}
	
	 
	public function update_entry($data,$param){
		$this->db->update($this->table_name,$data,$param);
		return $this->db->affected_rows();
	}
	
	public function insert_table_entry($table,$data){
		$this->db->insert($table,$data);
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
    
	 
    
	public function getOverpay_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		/*$aColumnsWhere = array('a.OverpayID', 'a.Username', 'b.Abbreviation', 'a.OverpaidAmount', 'a.AmountInMyr',  'a.RecoveredAmount', 'a.RemainingAmount', 
							   'c.mb_nick', 'd.mb_nick', 'a.DateUpdated', 'e.Name', 'c.mb_fname', 'c.mb_lname', 'a.Status', 'g.Name');*/
		
		$aColumnsWhere = array('a.OverpayID', 'a.Username', 'b.Abbreviation', 'a.OverpaidAmount', 'a.AmountInMyr',  'a.RecoveredAmount', 'a.RemainingAmount', 
							   'c.mb_nick', 'd.mb_nick', 'a.DateUpdated', 'e.Name', 'g.Name', 'c.mb_fname', 'c.mb_lname', 'a.Status',); 
							   					    
		$aColumns = array('a.OverpayID', 'a.Username', 'b.Abbreviation', 'a.OverpaidAmount', 'a.AmountInMyr', 'a.RecoveredAmount', 'a.RemainingAmount', 
						  'c.mb_nick', 'd.mb_nick', 'a.DateUpdated', 'e.Name', 'g.Name', 'a.Action');
		
		$sIndexColumn = "a.OverpayID";
		
		/* DB table to use */
		$sTable = "overpay_case AS a";
		
		/* Database connection information */
		$gaSql['user']       = $this->db->username;
		$gaSql['password']   = $this->db->password;
		$gaSql['db']         = $this->db->database;
		$gaSql['server']     = $this->db->hostname;
		 
		 
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
		
		$sOrder = ($sOrder=="")?"ORDER BY a.DateUpdated DESC ":$sOrder; 
	  
		/* 
		 * Filtering
		 * NOTE this does not match the built-in DataTables filtering which does it
		 * word by word on any field. It's possible to do here, but concerned about efficiency
		 * on very large tables, and MySQL's regex functionality is very limited 
		 */
	 	$sHaving = "";
		if ( $_GET['sSearch'] != "" )
		{
			$sWhere = "WHERE a.Status<>'0'  AND (";
			for ( $i=0 ; $i<count($aColumnsWhere) ; $i++ )
			{
				if (strstr(strtoupper($aColumnsWhere[$i]), "GROUP_CONCAT"))//my custom
				 {
					 $sHaving .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR "; 
				 }
				else
				 {
					$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";  
				 } 
				 //$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";	
				
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sHaving = substr_replace( $sHaving, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */ 
		for ( $i=0 ; $i<count($aColumnsWhere) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				 
				if (strstr(strtoupper($aColumnsWhere[$i]), "GROUP_CONCAT"))//my custom
				 {
					 if ( $sHaving == "" )
					 {
						$sHaving .= "";
					 }
					else
					 {
						$sHaving .= " AND ";
					 }
					 $sHaving .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				 }
				else
				 {	
				 	if ( $sWhere == "" )
					{
						$sWhere = "WHERE a.Status<>'0' AND ";
					}
					else
					{
						$sWhere .= " AND ";
					}
					$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
				 } 
				//$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		$sWhere = ($sWhere=="")?"WHERE a.Status<>'0' ":$sWhere;  //jdereal
		/*
		 * SQL queries
		 * Get data to display
		 */
		
		$sHaving = ($sHaving != "")?" HAVING ".$sHaving:""; 
		$sLeftJoin = " LEFT JOIN currency AS b ON a.Currency=b.CurrencyID   
					   LEFT JOIN g4_member AS c ON FIND_IN_SET(c.mb_no, a.AccountablePerson) 
					   LEFT JOIN g4_member AS d ON a.UpdatedBy=d.mb_no 
					   LEFT JOIN overpay_status AS e ON a.Status=e.StatusID
					   LEFT JOIN offenses AS g ON a.WarningLevel=g.OffenseID   	
					 "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere)).",  
					d.mb_nick AS UpdatedByNickname, e.Name AS StatusName, g.Name AS WarningLevelName, 
					(SELECT GROUP_CONCAT(f.mb_nick SEPARATOR ',' ) FROM g4_member AS f WHERE FIND_IN_SET(f.mb_no, a.AccountablePerson) ) AS NickNames
			FROM $sTable  
			$sLeftJoin
			$sWhere  
			GROUP BY a.OverpayID   
			$sHaving 
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
			WHERE a.Status<>'0'  
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
				$edit_link = (admin_access())?base_url().'manage-overpay/'.$aRow[OverpayID]:base_url().'view-overpay/'.$aRow[OverpayID];
				$view_link = base_url().'view-overpay/'.$aRow[OverpayID]; 
		 	 
				if( $aColumns[$i] == "a.OverpayID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['OverpayID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.Username" )
				 {  
					$row[] = '<a href="'.$edit_link.'" class="tip" title="manage overpay" >'.$aRow[Username].'</a> ';
				 }
				elseif ( $aColumns[$i] == "b.Abbreviation" )
				 { 
					$row[] = $aRow[Abbreviation];
				 }
				elseif ( $aColumns[$i] == "a.OverpaidAmount")
				 { 
					$row[] = number_format($aRow[OverpaidAmount],2);//$aRow[OverpaidAmount];
				 }
				elseif ( $aColumns[$i] == "a.AmountInMyr")
				 { 
					$row[] = ($aRow[AmountInMyr]=="" || $aRow[AmountInMyr]==0.00 || $aRow[AmountInMyr]==0)?"":number_format($aRow[AmountInMyr],2); 
				 }
				elseif ( $aColumns[$i] == "a.RecoveredAmount")
				 { 
					$row[] = number_format($aRow[RecoveredAmount],2);//$aRow[RecoveredAmount];
				 }
				elseif ( $aColumns[$i] == "a.RemainingAmount")
				 { 
					$row[] = number_format($aRow[OverpaidAmount]-$aRow[RecoveredAmount],2);//$aRow[RemainingAmount];
				 }
				elseif ( $aColumns[$i] == 'c.mb_nick')
				 { 
					$row[] = $aRow[NickNames];
				 }
				elseif ( $aColumns[$i] == "a.DateUpdated")
				 { 
					$row[] = date("Y-m-d", strtotime($aRow[DateUpdated]));
				 }
				elseif ( $aColumns[$i] == "d.mb_nick" )
				 { 
					$row[] = $aRow[UpdatedByNickname];
				 }
				elseif ( $aColumns[$i] == "e.Name" )
				 {
					$row[] = ($aRow[Status]=='1')?"<span class='inactive_stat'>".$aRow[StatusName]."</span>":$aRow[StatusName]; 
				 } 
				elseif ( $aColumns[$i] == "g.Name" )
				 {
					$row[] = $aRow[WarningLevelName]; 
				 } 
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit overpay" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link;
					$link = (admin_only())?$link.'<a class="tip delete_one" title="delete overpay"  overpay-id="'.$aRow[OverpayID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
					$row[] = $link; 
				 }
				else
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
	
	/*public function exportOverpay_(){
		if($report_type == "excel") 
		 {
			$file_name = $action.'-'.date("Ymdhis").".xls"; 
			$header_list = array("Date Added", "Date Updated", "Currency", "E-Support ID", "Report",  "Username","Last Updated By", "Status" );
								
			$data_list = array("DateAdded", "DateUpdated", "Currency", "ESupportID", "ReportAll", "Username", "mb_nick", "StatusName");   
			$this->exportData($result, $report_type, $header_list, $data_list, $file_name);
		 }
		else
		 {
			return 0; 
		 }	
	}*/ 
	
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
	
	
	function searchOverpay_($keywords="", $where=array(), $action=""){ 
		 
		$aColumnsWhere = array('a.Username', 'a.OverpaidAmount', 'a.AmountInMyr', 'a.RecoveredAmount', 'c.mb_nick', 'a.DateUpdated', 'b.Abbreviation', 'e.Name' ); 
		$sWhere = " a.Status<>0 "; 
		if ($keywords != "")
		 {
			$sWhere .= " AND (";
			for ( $i=0 ; $i<count($aColumnsWhere) ; $i++ )
			{
				//$sWhere .= $aColumnsWhere[$i]." LIKE ".$this->db->escape('%'.$keywords.'%')." OR "; 
				
				if (strstr(strtoupper($aColumnsWhere[$i]), "GROUP_CONCAT"))//my custom
				 {
					 $sHaving .= $aColumnsWhere[$i]." LIKE ".$this->db->escape('%'.$keywords.'%')." OR "; 
				 }
				else
				 {    
					$sWhere .= $aColumnsWhere[$i]." LIKE ".$this->db->escape('%'.$keywords.'%')." OR "; 
				 } 
				 
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sHaving = substr_replace( $sHaving, "", -3 );
			$sWhere .= ')';
		 } 
		$sHaving = ($sHaving != "")?" HAVING ".$sHaving:"";    
	 	$sql = "
			SELECT SQL_CALC_FOUND_ROWS a.*, (a.OverpaidAmount-a.RecoveredAmount) AS RemainingAmount, 
				   (SELECT GROUP_CONCAT(c.mb_nick SEPARATOR ',' ) FROM g4_member AS c WHERE FIND_IN_SET(c.mb_no, a.AccountablePerson) ) AS AccountableNickname, 
				   b.Abbreviation, d.mb_nick, e.Name, c.mb_fname, c.mb_lname, a.Status, g.Name, 
				   d.mb_nick AS UpdatedByNickname, e.Name AS StatusName, 
				   f.ProcessName, g.Name AS WarningLevelName
			FROM overpay_case AS a  
			 	LEFT JOIN currency AS b ON a.Currency=b.CurrencyID   
		   		LEFT JOIN g4_member AS c ON FIND_IN_SET(c.mb_no, a.AccountablePerson)
		   		LEFT JOIN g4_member AS d ON a.UpdatedBy=d.mb_no 
		   		LEFT JOIN overpay_status AS e ON a.Status=e.StatusID 
				LEFT JOIN adjustment_process AS f ON a.AdjustmentProcess=f.ProcessID  
		   		LEFT JOIN offenses AS g ON a.WarningLevel=g.OffenseID   	
			WHERE $sWhere
			GROUP BY a.OverpayID   
			$sHaving
			ORDER BY a.DateUpdated DESC 
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
	
	/* End of Public Functions */
	
	/* Private Functions */
	private function selectStrictXX_($where=array(),$table = "",$order=array(),$offset=0,$limit=0){
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
		 
		return $this->db->last_query();//$result;
	}
	
	/* End of Private Functions */
}

?>