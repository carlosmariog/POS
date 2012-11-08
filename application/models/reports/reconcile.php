<?php
require_once("report.php");
class Reconcile extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array('summary' => array( $this->lang->line('reports_payment_type'), $this->lang->line('reports_total')), 'details' => array());		
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('sale_id, payment_type, sum(total) as total, SUBSTRING(payment_type,1,4) as payment', false);
		$this->db->from('sales_items_temp');
		$this->db->join('people as employee', 'sales_items_temp.employee_id = employee.person_id');
		$this->db->join('people as customer', 'sales_items_temp.customer_id = customer.person_id', 'left');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where('store_id', $this->session->userdata('store_id'));
		//$this->db->where('sales_items_temp.employee_id', $this->session->userdata('person_id'));

		if ($inputs['sale_type'] == 'sales')
		{
			$this->db->where('quantity_purchased > 0');
		}
		elseif ($inputs['sale_type'] == 'returns')
		{
			$this->db->where('quantity_purchased < 0');
		}
		$this->db->group_by('payment');
		$this->db->order_by('payment');

		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();
		
		foreach($data['summary'] as $key=>$value)
		{
			$this->db->select('sales_items_temp.name, quantity_purchased, serialnumber, sales_items_temp.description, subtotal,total, tax, profit, discount_percent');
			$this->db->from('sales_items_temp');
			$this->db->join('items', 'sales_items_temp.item_id = items.item_id');
			$this->db->where('sale_id = '.$value['sale_id']);
			$this->db->where('sales_items_temp.store_id', $this->session->userdata('store_id'));		
			//$this->db->where('sales_items_temp.employee_id', $this->session->userdata('person_id'));
			$data['details'][$key] = $this->db->get()->result_array();
		}
		
		return $data;
	}
	
	public function getSummaryData(array $inputs)
	{
		$this->db->select('sum(subtotal) as subtotal, sum(total) as total, sum(tax) as tax, sum(profit) as profit');
		$this->db->from('sales_items_temp');
		$this->db->where('sale_date BETWEEN "'. $inputs['start_date']. '" and "'. $inputs['end_date'].'"');
		$this->db->where('sales_items_temp.store_id', $this->session->userdata('store_id'));
		//$this->db->where('sales_items_temp.employee_id', $this->session->userdata('person_id'));

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