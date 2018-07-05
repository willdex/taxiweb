<?php
class Model_wallet extends CI_Model{
function __construct() {
parent::__construct();
}
public function add_wallet($data) {
	
	$table = 'wallet';
	
	 $where_data = array(	// ----------------Array for check data exist ot not
			'item_no'     => $data["item_no"]
		);
		$select_data="*";
		
	$result = $this->get_table_where( $select_data, $where_data, $table );
	if(count($result)==0){
		if($this->db->insert($table, $data)){
		
		$this->db->set('wallet_amount', 'wallet_amount+ ' .  (int) $data["amount"], FALSE);
$this->db->where('username', $data["username"]);
$this->db->update('userdetails');	
			
		}
		
	}
	
} function get_table_where( $select_data, $where_data, $table){
       
		$this->db->select($select_data);
		$this->db->where($where_data);
		 
		$query  = $this->db->get($table); 
       		//--- Table name = User
		$result = $query->result_array(); 
		
		return $result;	
   }	function update_table_where( $update_data, $where_data, $table){	
	$this->db->where($where_data);
	$this->db->update($table, $update_data);
	
	
 }
}
