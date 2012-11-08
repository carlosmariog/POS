<?php
class Appconfig extends CI_Model 
{
	
	function exists($key)
	{
		$this->db->from('app_config');	
		$this->db->where('app_config.key',$key);
		$query = $this->db->get();
		
		return ($query->num_rows()==1);
	}
	
	function get_all()
	{
		$this->db->from('app_config');
		$this->db->order_by("key", "asc");
		return $this->db->get();		
	}
	
	function get_all_store()
	{
		$this->db->from('store');
		$this->db->where('deleted',0);
		$this->db->order_by("store_name asc, workstation asc");
		//$this->db->group_by('store_id');
		return $this->db->get();		
	}
	
	/*
	Deletes a list of stores
	*/
	function delete_list($store_ids)
	{
		$this->db->where_in('store_id',$store_ids);
		return $this->db->update('store', array('deleted' => 1));
 	}


	function get_last_store()
	{
		$this->db->from('store');
		$this->db->where('deleted',0);		
		$this->db->order_by("store_id", "desc");
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->store_id;
		}

		return 0;
	}

	function get_last_workstation($store_id){
		$this->db->from('store');
		$this->db->where('deleted',0);
		$this->db->where('store_id',$store_id);				
		$this->db->order_by("workstation", "desc");
		$this->db->limit(1);

		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row()->workstation;
		}

		return 0;

	}
	
	function get_all_store_info_by_id($store_id, $workstation=-1)
	{
		if($workstation==-1){
			$this->db->from('store');
			$this->db->where('deleted',0);
			$this->db->where('store_id',$store_id);
			$this->db->limit(1);
		}else{
			$this->db->from('store');
			$this->db->where('deleted',0);
			$this->db->where('store_id',$store_id);
			$this->db->where('workstation',$workstation);
			$this->db->limit(1);
		}
		return $this->db->get();		
	}

	function get_store_info($store_id)
	{
	
		$this->db->from('store');
		$this->db->where('store_id',$store_id);
		$this->db->where('deleted',0);		
		
		$query = $this->db->get();

		if($query->num_rows()==1)
		{
			return $query->row();
		}
		else
		{
			//Get empty base parent object, as $item_id is NOT an item
			$item_obj=new stdClass();

			//Get all the fields from items table
			$fields = $this->db->list_fields('store');

			foreach ($fields as $field)
			{
				$item_obj->$field='';
			}

			return $item_obj;
		}
	
	}
	
	function get($key)
	{
		$query = $this->db->get_where('app_config', array('key' => $key), 1);
		
		if($query->num_rows()==1)
		{
			return $query->row()->value;
		}
		
		return "";
		
	}
	
	function save($key,$value)
	{
		$config_data=array(
		'key'=>$key,
		'value'=>$value
		);
				
		if (!$this->exists($key))
		{
			return $this->db->insert('app_config',$config_data);
		}
		
		$this->db->where('key', $key);
		return $this->db->update('app_config',$config_data);		
	}
	
	function batch_save($data)
	{
		$success=true;
		
		//Run these queries as a transaction, we want to make sure we do all or nothing
		$this->db->trans_start();
		foreach($data as $key=>$value)
		{
			if(!$this->save($key,$value))
			{
				$success=false;
				break;
			}
		}
		
		$this->db->trans_complete();		
		return $success;
		
	}
		
	function delete($key)
	{
		return $this->db->delete('app_config', array('key' => $key)); 
	}
	
	function delete_all()
	{
		return $this->db->empty_table('app_config'); 
	}
	
	function save_store($store_data)
	{
		$this->db->insert('store',$store_data);
	}
	
	function update_store($store_id, $store_data)
	{
		$this->db->where('store_id', $store_id);
		$this->db->update('store', $store_data); 
	}

 	/*
	Get search suggestions to find items
	*/
	function get_search_suggestions($search,$limit=25)
	{
		$suggestions = array();

		$this->db->from('store');
		$this->db->like('store_name', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("name", "asc");
		$by_name = $this->db->get();
		foreach($by_name->result() as $row)
		{
			$suggestions[]=$row->store_name;
		}
		
		$this->db->from('store');
		$this->db->like('store_address', $search);
		$this->db->where('deleted',0);
		$this->db->order_by("store_address", "asc");
		$by_item_number = $this->db->get();
		foreach($by_item_number->result() as $row)
		{
			$suggestions[]=$row->store_address;
		}


		//only return $limit suggestions
		if(count($suggestions > $limit))
		{
			$suggestions = array_slice($suggestions, 0,$limit);
		}
		return $suggestions;
	}


}

?>