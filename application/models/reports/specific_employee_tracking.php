<?php
require_once("report.php");
class Specific_employee_tracking extends Report
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function getDataColumns()
	{
		return array($this->lang->line('reports_start_session'), $this->lang->line('reports_end_session'), $this->lang->line('reports_time'));		
	}
	
	public function getData(array $inputs)
	{
		$this->db->select('start_session, end_session');
		$this->db->from('ospos_employees_tracking');
		$this->db->join('ospos_people', 'ospos_employees_tracking.person_id = ospos_people.person_id');
		$this->db->where('start_session BETWEEN "'.$inputs['start_session'].'" and "'.$inputs['end_session'].'" and ospos_employees_tracking.person_id='.$inputs['employee_id']);
		$this->db->order_by('start_session');
		$data = array();
		$data['summary'] = $this->db->get()->result_array();
		$data['details'] = array();

		//$query =  $this->db->query("SELECT start_session, end_session FROM ospos_employees_tracking JOIN ospos_people ON ospos_employees_tracking.person_id=ospos_people.person_id WHERE start_session BETWEEN '".$inputs['start_session']."' and '".$inputs['end_session']."' AND ospos_employees_tracking.person_id=".$inputs['employee_id']." ORDER BY start_session");
		
		return $data;
	}
	
	public function getSummaryData(array $inputs)
	{
	}
}
?>