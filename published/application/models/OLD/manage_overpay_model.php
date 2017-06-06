<?php
class Manage_Overpay_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "overpay_case"; 
    }
	 
	function getOverpayById_($overpay_id){  
		 //a.AccountablePerson=c.mb_no 
		$sql = "SELECT a.OverpayID, a.Username, a.Currency, a.TransactionNo, a.TransactionDate, a.OverpaidAmount, a.RecoveredAmount, a.AccountablePerson, 
					   a.AdjustmentProcess, a.Case, a.Remarks, a.Status, a.AmountInMyr, a.WarningLevel, b.Name AS StatusName, 
					   GROUP_CONCAT(c.mb_nick SEPARATOR ', ') AS mb_nick,  GROUP_CONCAT(CONCAT(c.mb_fname,' ',c.mb_lname)) AS AccountablePersonName, d.Abbreviation, 
					   e.ProcessName, f.mb_nick AS UpdatedBy, g.Name AS WarningLevelName  
				FROM {$this->table_name} AS a  
				LEFT JOIN overpay_status AS b ON a.Status=b.StatusID 
				LEFT JOIN g4_member AS c ON FIND_IN_SET(c.mb_no, a.AccountablePerson)
				LEFT JOIN currency AS d ON a.Currency=d.CurrencyID 
				LEFT JOIN adjustment_process AS e ON a.AdjustmentProcess=e.ProcessID  
				LEFT JOIN g4_member AS f ON a.Updatedby=f.mb_no   
				LEFT JOIN offenses AS g ON a.WarningLevel=g.OffenseID 
				WHERE a.OverpayID=".$this->db->escape($overpay_id)." AND a.Status<>'0' 
				GROUP BY a.OverpayID 
				";// 
	 
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
	
	function getOverpayByIdMultiple_($overpay_id){  
		 //a.AccountablePerson=c.mb_no 
		$sql = "SELECT a.OverpayID, a.Username, a.Currency, a.TransactionNo, a.TransactionDate, a.OverpaidAmount, a.RecoveredAmount, a.AccountablePerson, 
					   a.AdjustmentProcess, a.Case, a.Remarks, a.Status, b.Name AS StatusName, GROUP_CONCAT(c.mb_nick SEPARATOR ',') AS mb_nick,  GROUP_CONCAT(CONCAT(c.mb_fname,' ',c.mb_lname)) AS AccountablePersonName, d.Abbreviation, 
					   e.ProcessName, f.mb_nick AS UpdatedBy 
				FROM {$this->table_name} AS a  
				LEFT JOIN overpay_status AS b ON a.Status=b.StatusID 
				LEFT JOIN g4_member AS c ON FIND_IN_SET(c.mb_no, a.AccountablePerson)
				LEFT JOIN currency AS d ON a.Currency=d.CurrencyID 
				LEFT JOIN adjustment_process AS e ON a.AdjustmentProcess=e.ProcessID  
				LEFT JOIN g4_member AS f ON a.Updatedby=f.mb_no   
				WHERE FIND_IN_SET(a.OverpayID, ".$this->db->escape($overpay_id).") AND a.Status<>'0' ";// 
	  
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
	
	 
	public function manageOverpay_($table, $datax, $action="add", $index="", $index_value=""){ 
		$test="";
		$data = array(); 
		
		foreach($datax as $field => $value){ 
			 $data[$field] = $value;
			 $test .= $field.'-';
		}   
  
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$data);  
			return $this->db->insert_id();//$x; 
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
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