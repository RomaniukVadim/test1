<?php
class Agents_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "agents"; 
    }
	 
	
	public function deleteAgents_($table, $params , $index){ 
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":"";
		
		$selected_values = $params[selected_values];  
		$this->db->where("FIND_IN_SET(AgentID, '".$selected_values."') $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	function getAgentById_($agent_id){   
		$sql = "SELECT * FROM agents  
				WHERE FIND_IN_SET(AgentID, '".$agent_id."')";//
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
		
	public function getAgents_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */  
		 
		$aColumnsWhere = array('a.AgentID', 'a.AgentName', 'b.Abbreviation', 'c.ModeName', 'a.ExcelPassword', 'a.ToEmail',  
							   'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.CcEmail', 'a.Status' );
		$aColumns = array('a.AgentID', 'a.AgentName', 'b.Abbreviation', 'c.ModeName', 'a.ExcelPassword', 'a.ToEmail', 
						  'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.Action');
		
		$sIndexColumn = "a.AgentID";
		
		/* DB table to use */
		$sTable = "agents AS a";
		
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
			$sLimit = "LIMIT ".$this->common->escapeString_( $_GET['iDisplayStart'] ).", ".
				$this->common->escapeString_( $_GET['iDisplayLength'] );
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
						".$this->common->escapeString_( $_GET['sSortDir_'.$i] ) .", ";
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
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".$this->common->escapeString_( $_GET['sSearch'] )."%' OR ";
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
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".$this->common->escapeString_($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		$sWhere = ($sWhere=="")?"WHERE a.Status<>'9' ":$sWhere;  //jdereal
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN currency AS b ON a.Currency=b.CurrencyID 	
					   LEFT JOIN agent_modes AS c ON a.Mode=c.ModeID	
					 "; 
		$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumnsWhere)).", 
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
				$edit_link = (admin_access())?base_url().'manage-agents/'.$aRow[AgentID]:base_url().'view-agents/'.$aRow[AgentID];
				$view_link = base_url().'view-agents/'.$aRow[AgentID];
				 //$aColumns = array('a.AgentID', 'a.AgentName', 'b.Abbreviation', 'c.ModeName', 'a.ExcelPassword', 'c.ToEmail', 
						 // 'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.Action');
				if( $aColumns[$i] == "a.AgentID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['AgentID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.AgentName" )
				 {  
					$row[] = '<a class="tip" title="manage agent" href="'.$edit_link.'" >'.$aRow[AgentName].'</a> ';
				 }
				elseif ( $aColumns[$i] == "b.Abbreviation" )
				 { 
					$row[] = $aRow[Abbreviation];
				 }
				elseif ( $aColumns[$i] == "c.ModeName" )
				 { 
					$row[] = $aRow[ModeName];
				 }
				elseif ( $aColumns[$i] == "a.ExcelPassword" )
				 {
					$row[] = $aRow[ExcelPassword];
				 }
				elseif ( $aColumns[$i] == 'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END' )
				 {
					$row[] = ($aRow[Status]=='1')?"Active":"<span class='inactive_stat'>Inactive</span>"; 
				 }
				elseif ( $aColumns[$i] == "a.ToEmail" )
				 {
					$row[] = str_replace(",",", ", $aRow[ToEmail]);
				 }
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit agent" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link;
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")) )?$link.'<a class="tip delete_one" title="delete agent"  agent-id="'.$aRow[AgentID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
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
	
	
	function searchAgent_($keywords="", $where=array(), $action=""){ 
		 
		$aColumnsWhere = array('a.AgentNo', 'a.Remarks', 'a.DateUpdated', 'b.Name', 'c.mb_nick', 'd.Abbreviation'); 
		$sWhere = " a.AgentID<>0 ";  
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
				FROM agents AS a   
				LEFT JOIN agent_status AS b ON a.Status=b.StatusID  
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