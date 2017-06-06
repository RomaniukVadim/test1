<?php
class Banners_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "banners_12bet";
		$this->table_view_name = "g4_notice_view";
		$this->table_reply_name = "g4_notice_post_reply";
    }
	
	public function get_notice_list($where=array(),$order=array(),$offset=0,$limit=0){
		$result = $this->select_strict($where,"",$order,$offset,$limit);
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
	
	/*public function deleteBanners_($table, $data, $params , $index){
		$selected_values = $params[selected_values]; 
		 
		$this->db->where("FIND_IN_SET(BannerID, '$selected_values')");
		$x = $this->db->update($table, $data);  
		return $x; 
	}*/
	
	public function deleteBanners_($table, $params , $index){
		$selected_values = $params[selected_values]; 
		 
		/*$this->db->where("FIND_IN_SET(BannerID, '$selected_values')");
		$x = $this->db->update($table, $data);*/ 
		
		$this->db->where("FIND_IN_SET(BannerID, '".$selected_values."')");
		$x = $this->db->delete($table);
		return $x; 
	}
	
	
	function getBannerById_($banner_id){ 
		 /*$sql = "SELECT * FROM banners_12bet  
				WHERE BannerID=".$this->db->escape($banner_id); */ 
		$sql = "SELECT * FROM banners_12bet  
				WHERE FIND_IN_SET(BannerID, '".$banner_id."')";//
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
		
	public function getBanners_($lang)
	{
		/* Array of database columns which should be read and sent back to DataTables. Use a space where
		 * you want to insert a non-database field (for example a counter or static image)
		 */
		$aColumns = array('a.BannerID', 'a.ImageFile', 'a.Title', 'a.DateShow', 'a.DateEnd', 'a.SortOrder', 'a.LanguageID', 'b.Name', 'b.FolderName', 'c.mb_nick');
		$sIndexColumn = "a.BannerID";
		
		/* DB table to use */
		$sTable = "banners_12bet AS a";
		
		/* Database connection information */
		$gaSql['user']       = "root";
		$gaSql['password']   = "root";
		$gaSql['db']         = "designers"; 
		$gaSql['server']     = "localhost"; 
		
		/*$gaSql['user']       = "betindo_12design";
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
			$sWhere = "WHERE a.Status<>9 AND FIND_IN_SET(a.LanguageID, '".$lang."' )  AND (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere .= $aColumns[$i]." LIKE '%".$this->common->escapeString_( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}
		
		/* Individual column filtering */ 
		for ( $i=0 ; $i<count($aColumns) ; $i++ )
		{
			if ( $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
			{
				if ( $sWhere == "" )
				{
					$sWhere = "WHERE a.Status<>9  AND FIND_IN_SET(a.LanguageID, '".$lang."' ) ";
				}
				else
				{
					$sWhere .= " AND ";
				}
				$sWhere .= $aColumns[$i]." LIKE '%".$this->common->escapeString_($_GET['sSearch_'.$i])."%' ";
			}
		}
		
		$sWhere = ($sWhere=="")?"WHERE a.Status<>9  AND FIND_IN_SET(a.LanguageID, '".$lang."' ) ":$sWhere;  //jdereal
		/*
		 * SQL queries
		 * Get data to display
		 */
		$sLeftJoin = " LEFT JOIN language AS b ON a.LanguageID=b.LanguageID   
					   LEFT JOIN g4_member AS c ON a.UpdatedBy=c.mb_no 
					 "; 
		$sQuery = "
			SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
			FROM   $sTable 
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
			WHERE a.Status<>'9' AND FIND_IN_SET(a.LanguageID, '".$lang."' )  
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
				if( $aColumns[$i] == "a.BannerID" ) 
				 {
				 	$row[] = '<input type="checkbox" name="check_banner" id="checkbox'.$ctr.'" value="'.$aRow['BannerID'].'" >';
				 }
				elseif( $aColumns[$i] == "a.ImageFile" )
				 {
					/* Special output formatting for 'version' column */
					//$row[] = ($aRow[ $aColumns[$i] ]=="0") ? '-' : $aRow[ $aColumns[$i] ];
					
					//jdreal
					if($aRow['ImageFile'] && file_exists("media/uploads/".$aRow['ImageFile']))
					 {	
					 	$config['image_library'] = 'gd2';
						$config['source_image'] = "media/uploads/".$aRow['ImageFile'];
						$config['create_thumb'] = TRUE;
						$config['maintain_ratio'] = TRUE;
						$config['width'] = 70;
						$config['height'] = 70;
						
						
						$new_filename = basename($aRow['ImageFile']); 
						$data_path  = str_replace($new_filename, "", $aRow['ImageFile']);
						
						$config['new_image'] = "media/uploads/cache/".$data_path; 
						$config['thumb_marker'] = ""; 
						
						if(!is_dir($config['new_image'])) mkdir($config['new_image'], 0777, true);
						
						$this->image_lib->clear(); 
						$this->image_lib->initialize($config);
						if(!file_exists($config['new_image'].$new_filename))$this->image_lib->resize();  
						
						//if(!$this->image_lib->crop()) echo $this->image_lib->display_errors();
						$row[] = '<a href="'.base_url().'manage-banners/'.$aRow[BannerID].'" ><img src="'.base_url().$config['new_image'].$new_filename.'" alt="'.$aRow[Title].'" title="'.$aRow[Title].'"  border="0" ></a>';
						
					 }
					else
					 {
						$row[] = "xxx"; 
					 } 
					//end jdereal  
				 }
				elseif ( $aColumns[$i] == "a.Title" )
				 {
					//$row[] = '<a href="'.base_url().'manage-banners/'.$aRow[BannerID].'" >'.$aRow[Title].'</a> ';
				 	$row[] = '<a href="'.site_url("manage-banners/".$aRow[BannerID]).'" >'.$aRow[Title].'</a> ';
				 }
				elseif ( $aColumns[$i] == "a.Link" && $aRow[Link] )
				 {
					$row[] = '<a href="'.$aRow[Link].'" target="_blank" >'.$aRow[Link].'</a> ';
				 }
				elseif ( $aColumns[$i] == "a.SortOrder" && $aRow[SortOrder] )
				 {
					$row[] = number_format($aRow[SortOrder], 2, '.', '');
				 } 
				elseif ( $aColumns[$i] == "a.DateShow")
				 {
					$row[] = $aRow[DateShow];
				 }
				elseif ( $aColumns[$i] == "a.DateEnd")
				 {
					$row[] = $aRow[DateEnd];
				 } 
				elseif ( $aColumns[$i] == "a.LanguageID" )
				 {
					//$row[] = $aRow[Name];
				 }
				elseif ( $aColumns[$i] == "b.FolderName" )
				 {
					$row[] = $aRow[FolderName];
				 }
				elseif ( $aColumns[$i] == "b.Name" )
				 {
					//$row[] = $aRow[Name];
				 } 
				elseif ( $aColumns[$i] == "c.mb_nick" )
				 {
					$row[] = $aRow[mb_nick];
				 }
				elseif ( $aColumns[$i] != ' ' )
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