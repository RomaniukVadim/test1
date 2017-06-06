<?php
class Transactions_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "recon_local"; 
		$this->table_internal = "recon_internal"; 
    }
	 
	
	public function deleteAccounts_($table, $params , $index){
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":"";
		$selected_values = $params[selected_values];  
		$this->db->where("FIND_IN_SET(AccountID, '".$selected_values."') $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	public function getTransactionsById_($recon_id){   
		$sql = "SELECT *, CASE Status WHEN '1' THEN 'Active' WHEN '0' THEN 'Inactive' ELSE 'Deleted' END AS StatusName
				FROM {$this->table_name} 
				WHERE ReconID={$recon_id} ";//
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
	
	
	public function getTransactionsByIdMultiple_($recon_id){   
		$sql = "SELECT *, CASE Status WHEN '1' THEN 'Active' WHEN '0' THEN 'Inactive' ELSE 'Deleted' END AS StatusName  
				FROM {$this->table_name} 
				WHERE FIND_IN_SET(ReconID, '".$recon_id."')";//
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
		
	public function getTransactions_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$aColumnsWhere = array('a.ReconID', 'a.TransactionNo', 'b.BankName', 'a.Type', 'a.TransactionDate', 'a.Amount', 'a.ClosingBalance', 
							   'a.AmountCredit',  'a.Username'
							  );
		$aColumns = array('a.ReconID', 'a.TransactionNo', 'b.BankName', 'a.Type', 'a.TransactionDate', 'a.Amount', 'a.ClosingBalance', 
						  'a.AmountCredit', 'a.Username', 'a.Action'
						);
		
		$sIndexColumn = "a.ReconID";
		
		/* DB table to use */
		$sTable = "recon_local AS a";
		
		/* Database connection information */
		$gaSql['user'] = $this->db->username;
		$gaSql['password'] = $this->db->password;
		$gaSql['db'] = $this->db->database;
		$gaSql['server'] = $this->db->hostname; 		
		 
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
		$sLeftJoin = " LEFT JOIN banks AS b ON a.BankID=b.BankID 
					   LEFT JOIN g4_member AS c ON a.CreditBy=c.mb_no
					   LEFT JOIN g4_member AS d ON a.UpdatedBy=d.mb_no 	
					 "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere)).", 
				   c.mb_nick AS CreditedByName, d.mb_nick AS UpdatedByName 
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
				$edit_link = (admin_access())?base_url().'manage-transactions/'.$aRow[ReconID]:base_url().'local-transactions/'.$aRow[ReconID];
				$view_link = base_url().'local-transactions/'.$aRow[ReconID];
				 
				if( $aColumns[$i] == "a.ReconID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['ReconID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.TransactionNo" )
				 {  
					$row[] = '<a class="tip" title="manage transaction" href="'.site_url("manage-transactions/".$aRow[ReconID]).'" >'.$aRow[TransactionNo].'</a> ';
				 }
				elseif ( $aColumns[$i] == "b.BankName" )
				 { 
					$row[] = $aRow[BankName];
				 }
				elseif ( $aColumns[$i] == "a.Type" )
				 { 
					$row[] = ucwords($aRow[Type]);
				 } 
				elseif ( $aColumns[$i] == "a.TransactionDate" )
				 {
					$row[] = ($aRow[TransactionDate] != "0000-00-00")?$aRow[TransactionDate]:""; 
				 }
				elseif ( $aColumns[$i] == "a.Amount" )
				 { 
					$row[] = number_format($aRow[Amount],2); //$aRow[Amount];
				 }
				elseif ( $aColumns[$i] == "a.ClosingBalance" )
				 { 
					$row[] = number_format($aRow[ClosingBalance],2); //$aRow[ClosingBalance];
				 }
				elseif ( $aColumns[$i] == "a.AmountCredit" )
				 { 
					$row[] = number_format($aRow[AmountCredit],2); //$aRow[AmountCredit];
				 }
				elseif ( $aColumns[$i] == "a.Username" )
				 {
					$row[] = $aRow[Username];
				 }
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit transaction" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link; 
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")) )?$link.'<a class="tip delete_one" title="delete transaction"  transaction-id="'.$aRow[ReconID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
					$row[] = $link; 
				 } 
				else
				 {
					 
				 }
			}
			$output['aaData'][] = $row; 
			$ctr++;
		}//end while
		
		echo json_encode( $output );
		
	}
	
	
	function searchAccount_($keywords="", $where=array(), $action=""){ 
		 
		$aColumnsWhere = array('a.AccountNo', 'a.Remarks', 'a.DateUpdated', 'b.Name', 'c.mb_nick', 'd.Abbreviation'); 
		$sWhere = " a.AccountID<>0 ";  
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
							   
		$sql = "SELECT a.*, b.Name AS StatusName, c.mb_nick, d.Abbreviation
				FROM accounts AS a   
				LEFT JOIN account_status AS b ON a.Status=b.StatusID  
				LEFT JOIN g4_member AS c ON a.UpdatedBy=c.mb_no 
				LEFT JOIN currency AS d On a.Currency=d.CurrencyID
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
	
	//Internal Transactions 
	public function getInternalTransactions_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$aColumnsWhere = array('a.ReconID', 'a.TransactionNo', 'a.Type',  'a.ApproveDate', 'a.Amount', 'a.Balance' 
							  );
		$aColumns = array('a.ReconID', 'a.TransactionNo', 'a.Type', 'a.ApproveDate', 'a.Amount', 'a.Balance', 'a.Action'
						);
		
		$sIndexColumn = "a.ReconID";
		
		/* DB table to use */
		$sTable = "recon_internal AS a";
		
		/* Database connection information */
		$gaSql['user'] = $this->db->username;
		$gaSql['password'] = $this->db->password;
		$gaSql['db'] = $this->db->database;
		$gaSql['server'] = $this->db->hostname; 		
		 
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
				   b.mb_nick AS UpdatedByName 
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
				$edit_link = (admin_access())?base_url().'manage-internal-transactions/'.$aRow[ReconID]:base_url().'internal-transactions/'.$aRow[ReconID];
				$view_link = base_url().'internal-transactions/'.$aRow[ReconID];
				 
				if( $aColumns[$i] == "a.ReconID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['ReconID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.TransactionNo" )
				 {  
					$row[] = '<a class="tip" title="manage transaction" href="'.site_url("manage-internal-transactions/".$aRow[ReconID]).'" >'.$aRow[TransactionNo].'</a> ';
				 } 
				elseif ( $aColumns[$i] == "a.Type" )
				 { 
					$row[] = ucwords($aRow[Type]);
				 } 
				elseif ( $aColumns[$i] == "a.ApproveDate" )
				 {
					$row[] = ($aRow[ApproveDate] != "0000-00-00")?$aRow[ApproveDate]:""; 
				 }
				elseif ( $aColumns[$i] == "a.Amount" )
				 { 
					$row[] = number_format($aRow[Amount],2); //$aRow[Amount];
				 }
				elseif ( $aColumns[$i] == "a.Balance" )
				 { 
					$row[] = number_format($aRow[Balance],2); //$aRow[ClosingBalance];
				 } 
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit transaction" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link; 
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")) )?$link.'<a class="tip delete_one" title="delete transaction"  transaction-id="'.$aRow[ReconID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
					$row[] = $link; 
				 } 
				else
				 {
					 
				 }
			}
			$output['aaData'][] = $row; 
			$ctr++;
		}//end while
		
		echo json_encode( $output );
		
	} 
	//END Internal Transactions 
	 
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