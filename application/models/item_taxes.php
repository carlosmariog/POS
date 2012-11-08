<?php
class Item_taxes extends CI_Model
{
	/*
	Gets tax info for a particular item
	*/
	function get_info($item_id)
	{
		$this->db->from('items_taxes');
		$this->db->where('item_id',$item_id);
		//return an array of taxes for an item
		return $this->db->get()->result_array();
	}

	function get_tax1($item_id)
	{
		$this->db->from('items_taxes');
		$this->db->where('item_id',$item_id);
		$this->db->where('name','Sales Tax');

		$this->db->limit(1);		

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->percent;
		}
		return false;
	}

	function get_tax2($item_id)
	{
		$this->db->from('items_taxes');
		$this->db->where('item_id',$item_id);
		$this->db->where('name','Sales Tax 2');

		$this->db->limit(1);		

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->percent;
		}
		return false;
	}

	
	/*
	Inserts or updates an item's taxes
	*/
	function save(&$items_taxes_data, $item_id)
	{
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();

		$this->delete($item_id);
		
		foreach ($items_taxes_data as $row)
		{
			$row['item_id'] = $item_id;
			$name = $row['name'];
			$percent = $row['percent'];
			$this->db->from('items_taxes');
			$this->db->where('item_id', $item_id);
			$this->db->where('name', $name);
			$this->db->where('percent', $percent);
			$query = $this->db->get();
		
			if(!($query->num_rows() > 0))
			{
				$this->db->insert('items_taxes',$row);	
			}			
		}
		
		$this->db->trans_complete();
		return true;
	}
	
	function save_multiple(&$items_taxes_data, $item_ids)
	{
		foreach($item_ids as $item_id)
		{
			$this->save($items_taxes_data, $item_id);
		}
	}

	/*
	Deletes taxes given an item
	*/
	function delete($item_id)
	{
		return $this->db->delete('items_taxes', array('item_id' => $item_id)); 
	}
}
?>
