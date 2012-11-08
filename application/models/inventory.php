<?php
class Inventory extends CI_Model 
{	
	function insert($inventory_data)
	{
		return $this->db->insert('inventory',$inventory_data);
	}
	
	function get_inventory_data_for_item($item_id)
	{
		$this->db->from('inventory');
		$this->db->where('trans_items',$item_id);
		$this->db->order_by("trans_date", "desc");
		return $this->db->get();		
	}
	
	function get_last_inventory_trans_data_for_item($item_id)
	{
		$this->db->from('inventory');
		$this->db->where('trans_items',$item_id);
		$this->db->order_by("trans_date", "desc");
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->trans_date;
		}

		return false;
	}
	
}

?>