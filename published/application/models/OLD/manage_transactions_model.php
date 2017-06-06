<?php
class Manage_Transactions_Model extends CI_Model {
	
	private $table_name;
	
	/* Public Functions */
	
	public function __construct()
    {
        // Call the Model constructor
        parent::__construct();
		$this->table_name = "recon_local";
		$this->table_name_internal = "recon_internal";
		$this->table_banks = "banks"; 
    }
	
	public function checkTransactionNo_($transaction_no, $recon_id=""){   
		$where = ($recon_id)?" AND ReconID<>".$recon_id." ":"";
		$sql = "SELECT  ReconID 
			  	FROM {$this->table_name} 
				WHERE LCASE(TransactionNo) =".strtolower($this->db->escape($transaction_no)). $where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	}
	
	public function getTransactionById_($recon_id){  
		$sql = "SELECT a.*, b.BankName, c.mb_nick AS CreditByName, d.mb_nick AS UpdatedByName 
				FROM {$this->table_name} AS a 
				LEFT JOIN {$this->table_banks} AS b ON a.BankID=b.BankID 
				LEFT JOIN g4_member AS c ON a.CreditBy=c.mb_no   
				LEFT JOIN g4_member AS d ON a.UpdatedBy=d.mb_no   
				WHERE a.Status<>'9' AND a.ReconID=".$this->db->escape($recon_id);//
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
	
	public function getTransactionByMultiple_($recon_id){  
		$sql = "SELECT a.*, b.BankName, c.mb_nick AS CreditByName, d.mb_nick AS UpdatedByName 
				FROM {$this->table_name} AS a 
				LEFT JOIN {$this->table_banks} AS b ON a.BankID=b.BankID 
				LEFT JOIN g4_member AS c ON a.CreditBy=b.mb_no   
				LEFT JOIN g4_member AS d ON a.UpdatedBy=b.mb_no   
				WHERE FIND_IN_SET(ReconID, ".$this->db->escape($recon_id).")";//
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
	 
	public function manageTransactions_($table, $datax, $action="add", $index="", $index_value=""){ 
		$test="";
		$data = array(); 
		
		foreach($datax as $field => $value){ 
			 $data[$field] = $value;
			 $test .= $field.'-';
		}   
  
		if($action == "add") 
		 {
			$x = $this->db->insert($table,$data);  
			return $this->db->insert_id(); 
		 }
		else
		 {
			 $this->db->where($index, $index_value);
			 $x = $this->db->update($table, $data);
			 return $x;  
		 }
		
	}
	  
	public function getHistoryById_($recon_id){  
		$sql = "SELECT a.*, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic
				FROM local_history AS a 
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no    
				WHERE a.ReconID={$this->db->escape($recon_id)} 
				ORDER BY a.DateUpdated DESC ";//
		$result = $this->db->query($sql); 
		return $result->result();
	} 
	
	
	//INTERNAL 
	public function getInternalTransactionById_($recon_id){  
		$sql = "SELECT a.*, b.mb_nick AS UpdatedByName  
				FROM {$this->table_name_internal} AS a    
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no   
				WHERE a.Status<>'9' AND a.ReconID=".$this->db->escape($recon_id);//
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
	 
	public function checkInternalTransactionNo_($transaction_no, $recon_id=""){   
		$where = ($recon_id)?" AND ReconID<>".$recon_id." ":"";
		$sql = "SELECT  ReconID 
			  	FROM {$this->table_name_internal} 
				WHERE LCASE(TransactionNo) =".strtolower($this->db->escape($transaction_no)). $where;//
		$result = $this->db->query($sql); 
		return $result->num_rows();
	} 
	
	public function getInternalHistoryById_($recon_id){  
		$sql = "SELECT a.*, b.mb_nick AS UpdatedByName, b.mb_profilepic AS ProfilePic
				FROM internal_history AS a 
				LEFT JOIN g4_member AS b ON a.UpdatedBy=b.mb_no    
				WHERE a.ReconID={$this->db->escape($recon_id)} 
				ORDER BY a.DateUpdated DESC ";//
		$result = $this->db->query($sql); 
		return $result->result();
	}
	//END INTERNAL 
	
	
		
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