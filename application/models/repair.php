<?php
class Repair extends CI_Model
{
	
	function save($repair_data)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		//$this->db->trans_start();		
		$success = $this->db->insert('repair',$repair_data);

		if($success) return true;
		else return false;
	}
		
}
?>
