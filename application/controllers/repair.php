<?php
require_once ("secure_area.php");
class Repair extends Secure_area
{
	function __construct()
	{
		parent::__construct('repair');
	}

	function index()
	{
		$this->load->view('repair/repair_view');
	}
}
?>