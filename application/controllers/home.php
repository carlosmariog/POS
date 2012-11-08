<?php
require_once ("secure_area.php");

class Home extends Secure_area 
{
	function __construct()
	{
		parent::__construct();	
	}
	
	function index()
	{	
		$this->Customer->getDesiredItems();
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$itemDesiredArrived = array();
			$i=0;
			
			$tok = strtok($customer->desired_items, ",");
			while ($tok !== false) {
				if($this->Item->existsItemNumber($tok)){
					if($this->Item->get_quantity($tok) > 0){
						$itemDesiredArrived[$i] = $tok;
						$i++;      
					}
				}
    			$tok = strtok(",");
			}
		}	
			
			if($i!=0){
				$customer_data=array(
					'first_name'=>$customer->first_name,
					'last_name'=>$customer->last_name,
					'email'=>$customer->email,
					'phone_number'=>$customer->phone_number,
					'desired_items'=>$itemDesiredArrived,
				);
				$data['customers'][$customer->person_id] = $customer_data;

				echo "<script> var answer = confirm('There are desired items, will you manage them?')
				if (answer){
					window.location = 'home/desired';
					}
				</script>";

			}
			
			
				
				if($this->session->userdata('store_sel')){
					$this->load->view("home");
				}else{
					$data = array();
					$adresses = array('' => $this->lang->line('config_adress'));
					$adresses_selected = "";

					foreach($this->Appconfig->get_all_store()->result_array() as $row)
					{
						$adresses[$row['store_id']][$row['store_id'].".".$row['workstation']] = $row['store_address']." - workstation ".$row['workstation'];
					}
					$data['adresses'] = $adresses;
					$this->load->view("selectStore", $data);
				}
			/*}			
		}	*/	
	}
	
	function desired(){
		$this->Customer->getDesiredItems();
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$itemDesiredArrived = "";
			$i=0;
			
			$tok = strtok($customer->desired_items, ",");
			while ($tok !== false) {
				if($this->Item->existsItemNumber($tok)){
					if($this->Item->get_quantity($tok) > 0){
						if($itemDesiredArrived == ""){
							$itemDesiredArrived .= $tok;
						}else{
							$itemDesiredArrived .= ",".$tok;
						}
						$i++;      
					}
				}
    			$tok = strtok(",");
			}
			if($i!=0){
				$customer_data=array(
					'client_id' =>$customer->person_id,
					'client_name'=>$customer->first_name.' '.$customer->last_name,
					'email'=>$customer->email,
					'phone_number'=>$customer->phone_number,
					'desired_items'=>$itemDesiredArrived,

				);
				$data['customers'][$customer->person_id] = $customer_data;
				$data['headers'] = array('', 'Client Name','Send Mail','Call','Arrived Items');
			}
		}
		$this->load->view("desired", $data);
	}
	
	function delete($customer_id){
	
		$items_to_delete=$this->input->post('desired_items');
		
		//Send a email
		//TODO
		echo $items_to_delete;
		echo $customer_id;
		
		$this->Customer->updateDesiredItems($items_to_delete);
		
		
		$this->Customer->getDesiredItems();
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$itemDesiredArrived = "";
			$i=0;
			
			$tok = strtok($customer->desired_items, ",");
			while ($tok !== false) {
				if($this->Item->existsItemNumber($tok)){
					if($this->Item->get_quantity($tok) > 0){
						if($itemDesiredArrived == ""){
							$itemDesiredArrived .= $tok;
						}else{
							$itemDesiredArrived .= ",".$tok;
						}
						$i++;      
					}
				}
    			$tok = strtok(",");
			}
			if($i!=0){
				$customer_data=array(
					'client_id' =>$customer->person_id,
					'client_name'=>$customer->first_name.' '.$customer->last_name,
					'email'=>$customer->email,
					'phone_number'=>$customer->phone_number,
					'desired_items'=>$itemDesiredArrived,
				);
				$data['customers'][$customer->person_id] = $customer_data;
				$data['headers'] = array('', 'Client Name','Send Mail','Call','Arrived Items');
			}
		}

		$this->load->view("desired", $data);
	}
	
	function pullcash()
	{
		//Info by default
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$sale_type = 'all';
		$export_excel=0;
		
		$this->load->model('reports/Pullcash');
		$model = $this->Pullcash;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			if(substr($row['payment_type'], 0, 4) == "Cash")$row['payment_type'] = "Cash";
			if(substr($row['payment_type'], 0, 4) == "Chec")$row['payment_type'] = "Check";
			if(substr($row['payment_type'], 0, 4) == "Debi")$row['payment_type'] = "Debit Card";
			if(substr($row['payment_type'], 0, 4) == "Cred")$row['payment_type'] = "Credit Card";
			if(substr($row['payment_type'], 0, 4) == "Gift")$row['payment_type'] = "Giftcard";			

			$summary_data[] = array($row['payment_type'], to_currency($row['total']));
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array($drow['name'], $drow['serialnumber'], $drow['description'], $drow['quantity_purchased'], to_currency(
				$drow['subtotal']), to_currency($drow['total']), to_currency($drow['tax']), $drow['discount_percent'].'%');
			}
		}

		$data = array(
			"title" =>$this->lang->line('reports_pullcash'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$this->load->view("pullcash",$data);
	}
	
	function open(){
		//Info by default
		$start_date = date('Y-m-d');
		$end_date = date('Y-m-d');
		$sale_type = 'all';
		$export_excel=0;
	
		
		$this->load->model('reports/Open');
		$model = $this->Open;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		$count = 0;
			
		foreach($report_data['summary'] as $key=>$row)
		{	 
			foreach($report_data['details'][$key] as $drow)
			{
				if($count == 0){
				//	$summary_data[] = array("Deposit", to_currency(-1000));
					$count = 1;				
				}
				if(substr($row['payment_type'], 0, 4) == "Cash")$row['payment_type'] = "Cash";
				if(substr($row['payment_type'], 0, 4) == "Chec")$row['payment_type'] = "Check";
				if(substr($row['payment_type'], 0, 4) == "Debi")$row['payment_type'] = "Debit Card";
				if(substr($row['payment_type'], 0, 4) == "Cred")$row['payment_type'] = "Credit Card";
				if(substr($row['payment_type'], 0, 4) == "Gift")$row['payment_type'] = "Giftcard";			
					
				$summary_data[] = array($row['payment_type'], to_currency($row['total']));

				$details_data[$key][] = array($drow['name'], $drow['serialnumber'], $drow['description'], to_currency($drow[
				'subtotal']), to_currency($drow['total']), to_currency($drow['tax']));
			}
		}

		$data = array(
			"title" =>$this->lang->line('reports_open'),
			"deposit" => $model->getDeposit($this->session->userdata('workstation')),
			"subtitle" => date(date('m/d/Y', strtotime($end_date))),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'sale_type' =>
			$sale_type)),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}

	function deposit(){
	
		$deposit_data = array(
			'employee_id' => $this->session->userdata('person_id'),
			'workstation_id' => $this->session->userdata('workstation'),
			'date' => date('Y-m-d'),
			'cash_deposit' => $this->input->post("cash_deposit"),
			'check_deposit' => $this->input->post("check_deposit"),
		);
		$this->Sale->deposit($deposit_data);
		$this->load->view("home");
	}

	
	function logout()
	{
		$this->Employee->logout();
	}
	
	function addstore(){
		$store_data=array(
			'store_address'=>$this->input->post('address'),
			'store_name'=>$this->input->post('name'),			
			'phone'=>$this->input->post('phone'),
			'store_email'=>$this->input->post('email'),
			'store_fax'=>$this->input->post('fax'),
			'store_timezone'=>$this->input->post('timezone'),
		);
		$this->Appconfig->save_store($store_data);
	}

	function selectStore(){
		$store_id_and_workstation = $this->input->post('address_id');
			
		$store_id = strtok($store_id_and_workstation, ".");
		$workstation = strtok(".");	
		//echo $store_id;
		//echo $workstation;		

		foreach($this->Appconfig->get_all_store_info_by_id($store_id, $workstation)->result_array() as $row)
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

}
?>