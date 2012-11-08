<?php
require_once ("secure_area.php");
class Config extends Secure_area 
{
	function __construct()
	{
		parent::__construct('config');
	}
			
	function view($store_id){
		//item_id = -1 -> Add new store
		//item_id = -2 -> Edit store 
		//item_id >  0 -> Information of a store  
	
		$data['store_id'] = $store_id;
		if ($store_id == -2){
			$adresses = array('' => $this->lang->line('config_adress'));
			$adress_selected = '';
			foreach($this->Appconfig->get_all_store()->result_array() as $row)
			{
				$adresses[$row['store_id']] = $row['store_address']." - workstation ".$row['workstation'];
				$stores[$row['store_id']] = array($row['store_id'], 
										  		  $row['workstation'], 
												  $row['store_name'], 
												  $row['store_address'], 
												  $row['store_email'], 
												  $row['store_fax'], 
												  $row['phone'], 
												  $row['store_timezone']);
			}
			$data['stores'] = $stores;
			$data['adresses'] = $adresses;	
			$data['adress_selected'] = $adress_selected; 
		}
		
		$data['store_info']=$this->Appconfig->get_store_info($store_id);
		$this->load->view("add_store", $data);
	}
	
	function index()
	{
		$data = array();
		$adresses = array('' => $this->lang->line('config_adress'));
		foreach($this->Appconfig->get_all_store()->result_array() as $row)
		{
			$adresses[$row['store_id']] = $row['store_address'];
		}
		$data['adresses'] = $adresses;
		$this->load->view("config", $data);
	}
	
	function addstore(){
		if($this->input->post('store_id')>0){
			$store_data=array(
				'store_address'=>$this->input->post('address'),
				'store_name'=>$this->input->post('name'),			
				'phone'=>$this->input->post('phone'),
				'store_email'=>$this->input->post('email'),
				'store_fax'=>$this->input->post('fax'),
				'store_timezone'=>$this->input->post('timezone'),
			);
			$this->Appconfig->update_store($this->input->post('store_id'), $store_data);	
		}else{
			$store_id = $this->Appconfig->get_last_store()+1;
			$store_data=array(
				'store_id' => $store_id,		
				'store_address'=>$this->input->post('address'),
				'store_name'=>$this->input->post('name'),			
				'phone'=>$this->input->post('phone'),
				'store_email'=>$this->input->post('email'),
				'store_fax'=>$this->input->post('fax'),
				'store_timezone'=>$this->input->post('timezone'),
				'workstation' => $this->Appconfig->get_last_workstation($store_id)+1
			);
			$this->Appconfig->save_store($store_data);
		}
		$this->stores();		
	}

	function selectStore(){
	
		$store_id = $this->input->post('address_id');
		foreach($this->Appconfig->get_all_store_info_by_id($store_id)->result_array() as $row)
		{
			$store_info = array(
                   				'store_id'  => $row['store_id'],
                   				'store_name'     => $row['store_name'],
                   				'store_address' => $row['store_address'],
                   				'store_email' => $row['store_email'],
                   				'store_fax' => $row['store_fax'],
                   				'store_phone' => $row['phone'],
                   				'store_timezone' => $row['store_timezone'],
								'workstation' => $row['workstation']
            );
			$this->session->set_userdata('store_sel', TRUE);
			$this->session->set_userdata($store_info);
		}
		
		$this->load->view("home");
	}

	function addworkstation(){
		$data = array();
		$adresses = array('' => $this->lang->line('config_adress'));
		$adress_selected = '';
		foreach($this->Appconfig->get_all_store()->result_array() as $row)
		{
			$adresses[$row['store_id']] = $row['store_address'];
		}
		$data['adresses'] = $adresses;	
		$this->load->view("config/selectStore", $data);
	}

	function newWorkstation(){
		$store_id = $this->input->post('address_id');
		foreach($this->Appconfig->get_all_store_info_by_id($store_id)->result_array() as $row)
		{
			//echo "Store id: ". $store_id;
			//echo "Last workstation: ".$this->Appconfig->get_last_workstation($row['store_id']);

			$store_data=array(
				'store_id' => $row['store_id'],		
				'store_address'=>$row['store_address'],
				'store_name'=>$row['store_name'],			
				'phone'=>$row['phone'],
				'store_email'=>$row['store_email'],
				'store_fax'=>$row['store_fax'],
				'store_timezone'=>$row['store_timezone'],
				'workstation' => $this->Appconfig->get_last_workstation($row['store_id'])+1
			);
			$this->Appconfig->save_store($store_data);
		}
		$this->stores();
	}

	
	function stores(){
		$data = array();
		$config['base_url'] = site_url('/items/index');
		$config['total_rows'] = $this->Item->count_all();
		$config['per_page'] = '20';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_stores_manage_table( $this->Appconfig->get_all_store(), $this );
		$this->load->view('config/stores',$data);	
	}

	function delete(){
		$stores_to_delete=$this->input->post('ids');
		if($this->Appconfig->delete_list($stores_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_deleted').' '.
			count($stores_to_delete).' '.$this->lang->line('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_cannot_be_deleted')));
		}
		//$this->stores();
	}

	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 600;
	}

	function suggest()
	{
		$suggestions = $this->Appconfig->get_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}
		
	function save()
	{
		$batch_save_data=array(
		'company'=>$this->input->post('company'),
		//'address'=>$this->input->post('address'),
		//'phone'=>$this->input->post('phone'),
		//'email'=>$this->input->post('email'),
		//'fax'=>$this->input->post('fax'),
		'website'=>$this->input->post('website'),
		'default_tax_1_rate'=>$this->input->post('default_tax_1_rate'),		
		'default_tax_1_name'=>$this->input->post('default_tax_1_name'),		
		'default_tax_2_rate'=>$this->input->post('default_tax_2_rate'),	
		'default_tax_2_name'=>$this->input->post('default_tax_2_name'),		
		'currency_symbol'=>$this->input->post('currency_symbol'),
		'return_policy'=>$this->input->post('return_policy'),
		'buy_policy'=>$this->input->post('buy_policy'),
		'language'=>$this->input->post('language'),
		//'timezone'=>$this->input->post('timezone'),
		'print_after_sale'=>$this->input->post('print_after_sale')	
		);
		
		if($_SERVER['HTTP_HOST'] !='ospos.pappastech.com' && $this->Appconfig->batch_save($batch_save_data))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('config_saved_successfully')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('config_saved_unsuccessfully')));
	
		}
	}
}
?>