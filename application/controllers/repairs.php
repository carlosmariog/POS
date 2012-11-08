<?php
require_once ("secure_area.php");
class Repairs extends Secure_area
{
	function __construct()
	{
		parent::__construct('repairs');
		$this->load->helper('report');		

	}

	function index()
	{
		$data = $this->_get_common_report_data();
		foreach($this->Employee->get_all()->result_array() as $row)
		{
			$employees[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}
		
		$customers = array('' => $this->lang->line('items_none'));
		foreach($this->Customer->get_all()->result_array() as $row)
		{
			$customers[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}		
		$data['employees']=$employees;
		$data['customers']=$customers;

		$this->load->view('repairs/repair_view', $data);
	}
	
	function _get_common_report_data()
	{
		$data = array();
		$data['report_date_range_simple'] = get_simple_date_ranges();
		$data['months'] = get_months();
		$data['days'] = get_days();
		$data['years'] = get_years();
		$data['selected_month']=date('n');
		$data['selected_day']=date('d');
		$data['selected_year']=date('Y');	
		return $data;
	}
	
	function save(){
		
		$repair_data = array(
			'customer_id'=>$this->input->post('customer'),
			'employee_id'=>$this->input->post('employee'),
			'repair_item_number'=>$this->input->post('repair_item_number'),
			'equipment'=>$this->input->post('equipment'),
			'defect_type'=>$this->input->post('defect_type'),
			'repair_price'=>$this->input->post('repair_price'),
			'notes'=>$this->input->post('notes'),
			'password'=>$this->input->post('password'),
			'flaws'=>$this->input->post('flaws'),
			'accessories'=>$this->input->post('accessories'),
			'deposit'=>$this->input->post('deposit'),
			'delivery_date'=>$this->input->post('start_year')."-".$this->input->post('start_month')."-".$this->input->post('start_day')
		);

		if($this->Repair->save($repair_data))
		{
			$data = $this->_get_common_report_data();
					foreach($this->Employee->get_all()->result_array() as $row)
		{
			$employees[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}
		
		$customers = array('' => $this->lang->line('items_none'));
		foreach($this->Customer->get_all()->result_array() as $row)
		{
			$customers[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}		
		$data['employees']=$employees;
		$data['customers']=$customers;
		$this->load->view('repairs/repair_view', $data);
		}
	}

	function remove_customer()
	{
		$this->sale_lib->remove_customer();
		$this->_reload();
	}

	function select_customer()
	{
		$customer_id = $this->input->post("customer");
		$this->sale_lib->set_customer($customer_id);
		$this->_reload();
	}
}
?>