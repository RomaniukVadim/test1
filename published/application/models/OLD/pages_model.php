<?php
class Pages_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "pages"; 
    }
	 
	
	public function deletePages_($table, $params , $index){ 
		$custom_where = (!admin_only())?" AND AddedBy=".$this->session->userdata('mb_no')." ":"";
		
		$selected_values = $params[selected_values]; 
		  
		$this->db->where("FIND_IN_SET(PageID, '".$selected_values."') $custom_where ");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	function getPageById_($page_id){   
		$sql = "SELECT * FROM pages  
				WHERE FIND_IN_SET(PageID, '".$page_id."') AND Type='tutorial' ";//
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
		
	public function getPages_()
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */ 
		//$aColumnsWhere = array();
		//$aColumns = array(); 
		
		$aColumnsWhere = array('a.PageID', 'a.Title',  'c.Name', 'a.SortOrder', 'b.mb_nick', 'a.DateUpdated',
							   'CASE a.Status WHEN "1" THEN "Active" ELSE "Inactive" END', 'a.Status', 'a.Excerpt', 'a.File'
							  );
		$aColumns = array('a.PageID', 'a.Title', 'c.Name', 'a.SortOrder', 'b.mb_nick', 'a.DateUpdated', 'a.Status', 'a.Action');
		
		$sIndexColumn = "a.PageID";
		//(SELECT GROUP_CONCAT(' ', LCASE(TRIM(d.Name))) FROM language AS d WHERE FIND_IN_SeT(d.LanguageID, a.mb_access)) AS Access
		/* DB table to use */
		$sTable = "pages AS a";
		
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
			$sWhere = "WHERE a.Status<>'9' AND a.Type='tutorial' AND (";
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
					$sWhere = "WHERE a.Status<>'9' AND a.Type='tutorial' AND ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumnsWhere[$i]." LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		$sWhere = ($sWhere=="")?"WHERE a.Status<>'9' AND a.Type='tutorial'  ":$sWhere;  //jdereal
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no  
					   LEFT JOIN topics AS c ON a.TopicID=c.TopicID 
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
			WHERE a.Status<>'9' AND a.Type='tutorial' 
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
				 
				$edit_link = (admin_access())?base_url().'manage-pages/'.$aRow[PageID]:base_url().'view-pages/'.$aRow[PageID];
				$view_link = base_url().'view-pages/'.$aRow[PageID];
				
				if( $aColumns[$i] == "a.PageID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_user" id="checkbox'.$ctr.'" value="'.$aRow['PageID'].'" >';
				 }  
				elseif ( $aColumns[$i] == "a.Title" )
				 {  
					$row[] = '<a href="'.$edit_link.'" class="tip" title="'.$aRow[Title].'" >'.character_limiter($aRow[Title], 30).'</a> ';
				 } 
				elseif ( $aColumns[$i] == "c.Name" )
				 { 
					$row[] = $aRow[Name];
				 }
				elseif ( $aColumns[$i] == "a.SortOrder" )
				 { 
					$row[] = $aRow[SortOrder];
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
					$row[] = ($aRow[Status]=='1')?"Active":"<span class='inactive_stat'>Inactive</span>"; 
				 } 
				elseif ( $aColumns[$i] == "a.Action" )
				 {
					$link = '<a href="'.$view_link.'" class="tip" title="view details" ><i class="icon16 i-eye-4 gap-left0 gap-right10"></i></a>';
					$link = (admin_access())?$link.'<a href="'.$edit_link.'" class="tip" title="edit page" ><i class="icon16 i-pencil-5 gap-left0 gap-right5"></i></a>':$link;
					$link = (admin_only() || (admin_access() && $aRow[AddedBy]==$this->session->userdata("mb_no")))?$link.'<a class="tip delete_one" title="delete page"  page-id="'.$aRow[PageID].'" ><i class="icon16 i-remove-3 gap-left0 gap-right5 "></i></a>':$link; 
					$row[] = $link; 
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