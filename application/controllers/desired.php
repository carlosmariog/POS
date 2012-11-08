<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Desired extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('desired');
	}
	
	function index()
	{
	}
	function suggest()
	{
	}	
	
	function search(){}

	function get_row(){}
	function view($data_item_id=-1){}
	function save($data_item_id=-1){}
	function delete(){}
	function get_form_width(){}
}
?>