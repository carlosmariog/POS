<?php
require_once("report.php");
class Summary_police extends Report
{
	function __construct()
	{
		parent::__construct();
	}

	public function getDataColumns()
	{
		return array($this->lang->line('reports_date'), $this->lang->line('reports_customer_name'),$this->lang->line('reports_customer_lastname'),$this->lang->line('reports_customer_address') ,$this->lang->line('reports_customer_phone') , $this->lang->line('reports_identification_type'), $this->lang->line('reports_identification_number'),  $this->lang->line('reports_sale_id'), $this->lang->line('reports_item_id'), $this->lang->line('reports_item'), $this->lang->line('reports_serialnumber') , $this->lang->line('reports_total'));
	}
	
	public function getData(array $inputs)
	{		
		$query = $this->db->select('sale_date, first_name, last_name, address_1, phone_number, identification_type, identification, sale_id, item_id, name, serialnumber, total');
		$this->db->from('sales_items_temp');
		if ($inputs['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		
		elseif ($inputs['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->order_by('sale_date');
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		return $data;
	}
	
	public function getSummaryData(array $inputs)
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total');
		$this->db->from('sales_items_temp');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		if ($inputs['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($inputs['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		return $this->db->get()->row_array();		
	}

}
?>