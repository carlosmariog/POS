<?php
require_once ("secure_area.php");
require_once ("interfaces/idata_controller.php");
class Items extends Secure_area implements iData_controller
{
	function __construct()
	{
		parent::__construct('items');
	}

	function index()
	{	
		$config['base_url'] = site_url('/items/index');
		$config['total_rows'] = $this->Item->count_all();
		$config['per_page'] = '20';
		$config['uri_segment'] = 3;
		$this->pagination->initialize($config);
		
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		$data['manage_table']=get_items_manage_table( $this->Item->get_all( $config['per_page'], $this->uri->segment( $config['uri_segment'] ) ), $this );
		$this->load->view('items/manage',$data);
	}

	function fieldModify($item){
				
		$tok = strtok($item, "_");
		$name = $tok;
		$tok = strtok("_");
		$item_id = $tok;
		$tok = strtok("_");
		$cotent_field_to_modify = $tok;
		$item_number = $this->Item->get_item_number($item_id);
		if($name == 'name')      $this->Item->updateItemName($item_number, $cotent_field_to_modify);		
		if($name == 'number')  	 $this->Item->updateUPC($item_number, $cotent_field_to_modify);
		if($name == 'category'){
			if( $this->Item->category_exists($cotent_field_to_modify)) $category_id =  $this->Item->get_category_id($cotent_field_to_modify);
			else $category_id =  $this->Item->add_category($cotent_field_to_modify);			
			
			 $this->Item->updateCategory($item_number, $category_id);		
		}
		if($name == 'cost') 	 $this->Item->updateCostPrice($item_number, $cotent_field_to_modify);
		if($name == 'unit')		 $this->Item->updateUnitPrice($item_number, $cotent_field_to_modify);
		if($name == 'barcodes')  $this->Item->updateBarcodes($item_number, $cotent_field_to_modify);		

		$this->index();
	}

	function refresh()
	{
		$low_inventory=$this->input->post('low_inventory');
		$is_serialized=$this->input->post('is_serialized');
		$no_description=$this->input->post('no_description');

		$data['search_section_state']=$this->input->post('search_section_state');
		$data['low_inventory']=$this->input->post('low_inventory');
		$data['is_serialized']=$this->input->post('is_serialized');
		$data['no_description']=$this->input->post('no_description');
		$data['controller_name']=strtolower(get_class());
		$data['form_width']=$this->get_form_width();
		if(($low_inventory==0) && ($is_serialized==0) && ($no_description==0)){
			$config['base_url'] = site_url('/items/index');
			$config['total_rows'] = $this->Item->count_all();
			$config['per_page'] = '20';
			$config['uri_segment'] = 3;
			$this->pagination->initialize($config);
			$data['manage_table']=get_items_manage_table( $this->Item->get_all( $config['per_page'], $this->uri->segment( $config['uri_segment'] ) ), $this );
		}else{
			$data['manage_table']=get_items_manage_table($this->Item->get_all_filtered($low_inventory,$is_serialized,$no_description),$this);
		}
		$this->load->view('items/manage',$data);
	}

	function find_item_info()
	{
		$item_number=$this->input->post('scan_item_number');
		echo json_encode($this->Item->find_item_info($item_number));
	}

	function search()
	{
		$search=$this->input->post('search');
		$data_rows=get_items_manage_table_data_rows($this->Item->search($search),$this);
		echo $data_rows;
	}

	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest()
	{
		$suggestions = $this->Item->get_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}
	
	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}
	
	/*
	Gives search suggestions based on what is being searched for
	*/
	function suggest_category()
	{
		$suggestions = $this->Item->get_category_suggestions($this->input->post('q'));
		echo implode("\n",$suggestions);
	}

	function get_row()
	{
		$item_id = $this->input->post('row_id');
		$data_row=get_item_data_row($this->Item->get_info($item_id),$this);
		echo $data_row;
	}

	function view($item_id=-1)
	{
		if($item_id==-2){
			$mode = 'buy';
		}else{
			$mode = 'sale';				
		}
		$data['mode']=$mode;
		$data['item_info']=$this->Item->get_info($item_id);
		$data['item_tax_info']=$this->Item_taxes->get_info($item_id);
		
		$suppliers = array('' => $this->lang->line('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['company_name'] .' ('.$row['first_name'] .' '. $row['last_name'].')';
		}
		
		$customers = array('' => $this->lang->line('items_none'));
		foreach($this->Customer->get_all()->result_array() as $row)
		{
			$customers[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}

		$data['item_id'] = $item_id;		
		$data['suppliers']=$suppliers;
		$data['customers']=$customers;
		$data['selected_supplier'] = $this->Item->get_info($item_id)->supplier_id;
		$data['default_tax_1_rate']=($item_id==-1) ? $this->Appconfig->get('default_tax_1_rate') : '';
		$data['default_tax_2_rate']=($item_id==-1) ? $this->Appconfig->get('default_tax_2_rate') : '';
		$this->load->view("items/form",$data);
		
	}
	
	//Ramel Inventory Tracking
	function inventory($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$this->load->view("items/inventory",$data);
	}
	
	function count_details($item_id=-1)
	{
		$data['item_info']=$this->Item->get_info($item_id);
		$this->load->view("items/count_details",$data);
	} //------------------------------------------- Ramel

	function generate_barcodes($item_ids)
	{
		$result = array();

		$item_ids = explode(':', $item_ids);
		foreach ($item_ids as $item_id)
		{
			$item_info = $this->Item->get_info($item_id);
			
			$result[] = array('item_number' =>$item_info->item_number, 
							  'item_name' =>$item_info->name,	
							  'unit_price'=> $item_info->unit_price, 
							  'id'=> $item_id,
							  'kit' => false,
							  'barcodes_to_generate'=> $item_info->barcodes_to_generate);
			//$result[] = array('name' =>$item_info->name, 'id'=> $item_id);
		}

		$data['items'] = $result;
		$this->load->view("barcode_sheet", $data);
	}

	function bulk_edit()
	{
		$data = array();
		$suppliers = array('' => $this->lang->line('items_none'));
		foreach($this->Supplier->get_all()->result_array() as $row)
		{
			$suppliers[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}
		$data['suppliers'] = $suppliers;
		$data['allow_alt_desciption_choices'] = array(
			''=>$this->lang->line('items_do_nothing'), 
			1 =>$this->lang->line('items_change_all_to_allow_alt_desc'),
			0 =>$this->lang->line('items_change_all_to_not_allow_allow_desc'));
				
		$data['serialization_choices'] = array(
			''=>$this->lang->line('items_do_nothing'), 
			1 =>$this->lang->line('items_change_all_to_serialized'),
			0 =>$this->lang->line('items_change_all_to_unserialized'));
		$this->load->view("items/form_bulk", $data);
	}

	function save($item_id=-1)
	{
		if($this->input->post('serial_number')!=''){
			if($this->Item->existsSerialNumber($this->input->post('serial_number'))){
				echo "<script>alert('This serial number already exist');</script>";
			}
		}
		
		$category_id = 0;
		if(!$this->Item->category_exists($this->input->post('category'))){
			$this->Item->add_category($this->input->post('category'));
			$category_id = 	$this->Item->get_category_id($this->input->post('category'));		
		}else{
			$category_id = 	$this->Item->get_category_id($this->input->post('category'));		
		}	
		
		if($category_id<10){
			$cat_id = '00'.$category_id;
		}
		elseif($category_id>=10 & $category_id<100){
			$cat_id = '0'.$category_id;
		}
		elseif($category_id>=100 & $category_id<1000){
			$cat_id = $category_id;
		}
		
		$next_sequential_number = $this->Item->next_sequential_number($category_id);
		if($next_sequential_number == 0)		
			$sku = $cat_id.'00000';
		else
			$sku = $next_sequential_number;
			
			
		if(($item_id==-1)||($item_id==-2)){
			$item_data = array(
			'name'=>$this->input->post('name'),
			'description'=>$this->input->post('description'),
			'category_id'=>$category_id,
			'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
			'item_number'=>$this->input->post('item_number')=='' ? null:$this->input->post('item_number'),
			'sku' => $sku,
			'serial_number'=>$this->input->post('serial_number')=='' ? null:$this->input->post('serial_number'),
			'cost_price'=>$this->input->post('cost_price'),
			'unit_price'=>$this->input->post('unit_price'),
			'quantity'=>1,
			'reorder_level'=>$this->input->post('reorder_level'),
			'location'=>$this->session->userdata('store_address'),			
			'store_id'=>$this->session->userdata('store_id'),			
			'allow_alt_description'=>$this->input->post('allow_alt_description'),
			'is_serialized'=>$this->input->post('is_serialized'),
			'barcodes_to_generate' => $this->input->post('barcodes')=='' ? 1:$this->input->post('barcodes'),
			'register_mode'=>'Inventory'
			);
		}else{
			$item_data = array(
			'name'=>$this->input->post('name'),
			'description'=>$this->input->post('description'),
			'category_id'=>$category_id,
			'supplier_id'=>$this->input->post('supplier_id')=='' ? null:$this->input->post('supplier_id'),
			'item_number'=>$this->input->post('item_number')=='' ? null:$this->input->post('item_number'),
			'sku' => $sku,
			'cost_price'=>$this->input->post('cost_price'),
			'unit_price'=>$this->input->post('unit_price'),
			'reorder_level'=>$this->input->post('reorder_level'),
			'location'=>$this->session->userdata('store_address'),			
			'store_id'=>$this->session->userdata('store_id'),			
			'quantity'=>1,
			'allow_alt_description'=>$this->input->post('allow_alt_description'),
			'barcodes_to_generate' => $this->input->post('barcodes')=='' ? 1:$this->input->post('barcodes'),			
			'is_serialized'=>$this->input->post('is_serialized'),
			);
		}
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);


		if($this->Item->saveU($item_data,$item_id))
		{
			//New item
			if($item_id==-1)
			{
				//echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_adding').' '.
				//$item_data['name'],'item_id'=>$item_data['item_id']));
				$item_id = $item_data['item_id'];
			}
			else //previous item
			{
				//echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating').' '.
				//$item_data['name'],'item_id'=>$item_id));
			}
			
			
			$commentMod = $this->lang->line('items_manually_editing_of_quantity');
			
			if($item_data['name'] != $cur_item_info->name)
				$commentMod .= " Name -";
			if($item_data['description'] != $cur_item_info->description)
				$commentMod .= " Description -";
			if($item_data['category_id'] != $cur_item_info->category_id)
				$commentMod .= " Category -";
			if($item_data['supplier_id'] != $cur_item_info->supplier_id)
				$commentMod .= " Supplier -";
			if($item_data['cost_price'] != $cur_item_info->cost_price)
				$commentMod .= " Cost price - ";
				if($item_data['unit_price'] != $cur_item_info->unit_price)
				$commentMod .= " Unit price - ";
			if($item_data['quantity'] != $cur_item_info->quantity)
				$commentMod .= " Quantity - ";
			if($item_data['location'] != $cur_item_info->location)
				$commentMod .= " Location - ";
			
			
			$inv_data = array
			(
				'trans_date'=>date('Y-m-d H:i:s'),
				'trans_items'=>$item_id,
				'trans_user'=>$employee_id,
				'trans_comment'=>$commentMod,
				'trans_inventory'=>$cur_item_info ? $this->input->post('quantity') - $cur_item_info->quantity : $this->input->post('quantity')
			);
			$this->Inventory->insert($inv_data);
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k] );
				}
			}
			$this->Item_taxes->save($items_taxes_data, $item_id);
		}
		else//failure
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating').' '.
			$item_data['name'],'item_id'=>-1));
		}
		
		$this->index();		
	}
	
	//Ramel Inventory Tracking
	function save_inventory($item_id=-1)
	{	
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$cur_item_info = $this->Item->get_info($item_id);
		$inv_data = array
		(
			'trans_date'=>date('Y-m-d H:i:s'),
			'trans_items'=>$item_id,
			'trans_user'=>$employee_id,
			'trans_comment'=>$this->input->post('trans_comment'),
			'trans_inventory'=>$this->input->post('newquantity')
		);
		$this->Inventory->insert($inv_data);
		
		//Update stock quantity
		$item_data = array(
		'quantity'=>$cur_item_info->quantity + $this->input->post('newquantity')
		);
		if($this->Item->save($item_data,$item_id))
		{			
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_updating').' '.
			$cur_item_info->name,'item_id'=>$item_id));
		}
		else//failure
		{	
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_adding_updating').' '.
			$cur_item_info->name,'item_id'=>-1));
		}

	}//---------------------------------------------------------------------Ramel

	function bulk_update()
	{
		$items_to_update=$this->input->post('item_ids');
		$item_data = array();

		foreach($_POST as $key=>$value)
		{
			//This field is nullable, so treat it differently
			if ($key == 'supplier_id')
			{
				$item_data["$key"]=$value == '' ? null : $value;
			}
			elseif($value!='' and !(in_array($key, array('item_ids', 'tax_names', 'tax_percents'))))
			{
				$item_data["$key"]=$value;
			}
		}

		//Item data could be empty if tax information is being updated
		if(empty($item_data) || $this->Item->update_multiple($item_data,$items_to_update))
		{
			$items_taxes_data = array();
			$tax_names = $this->input->post('tax_names');
			$tax_percents = $this->input->post('tax_percents');
			for($k=0;$k<count($tax_percents);$k++)
			{
				if (is_numeric($tax_percents[$k]))
				{
					$items_taxes_data[] = array('name'=>$tax_names[$k], 'percent'=>$tax_percents[$k] );
				}
			}
			$this->Item_taxes->save_multiple($items_taxes_data, $items_to_update);

			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_bulk_edit')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_error_updating_multiple')));
		}
	}

	function delete()
	{
		$items_to_delete=$this->input->post('ids');

		if($this->Item->delete_list($items_to_delete))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('items_successful_deleted').' '.
			count($items_to_delete).' '.$this->lang->line('items_one_or_multiple')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('items_cannot_be_deleted')));
		}
	}
	
	function excel()
	{
		$data = file_get_contents("import_items.csv");
		$name = 'import_items.csv';
		force_download($name, $data);
	}
	
	function excel_import()
	{
		$this->load->view("items/excel_import", null);
	}

	function do_excel_import()
	{
		$msg = 'do_excel_import';
		$failCodes = array();
		if ($_FILES['file_path']['error']!=UPLOAD_ERR_OK)
		{
			$msg = $this->lang->line('items_excel_import_failed');
			echo json_encode( array('success'=>false,'message'=>$msg) );
			return;
		}
		else
		{
			if (($handle = fopen($_FILES['file_path']['tmp_name'], "r")) !== FALSE)
			{
				//Skip first row
				fgetcsv($handle);
				
				$i=1;
				while (($data = fgetcsv($handle)) !== FALSE) 
				{
					$item_data = array(
					'name'			=>	$data[1],
					'description'	=>	$data[13],
					'location'		=>	$data[12],
					'category'		=>	$data[2],
					'cost_price'	=>	$data[4],
					'unit_price'	=>	$data[5],
					'quantity'		=>	$data[10],
					'reorder_level'	=>	$data[11],
					'supplier_id'	=>  $this->Supplier->exists($data[3]) ? $data[3] : null,
					'allow_alt_description'=> $data[14] != '' ? '1' : '0',
					'is_serialized'=>$data[15] != '' ? '1' : '0'
					);
					$item_number = $data[0];
					
					if ($item_number != "")
					{
						$item_data['item_number'] = $item_number;
					}
					
					if($this->Item->save($item_data)) 
					{
						$items_taxes_data = null;
						//tax 1
						if( is_numeric($data[7]) && $data[6]!='' )
						{
							$items_taxes_data[] = array('name'=>$data[6], 'percent'=>$data[7] );
						}

						//tax 2
						if( is_numeric($data[9]) && $data[8]!='' )
						{
							$items_taxes_data[] = array('name'=>$data[8], 'percent'=>$data[9] );
						}

						// save tax values
						if(count($items_taxes_data) > 0)
						{
							$this->Item_taxes->save($items_taxes_data, $item_data['item_id']);
						}
						
							$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
							$emp_info=$this->Employee->get_info($employee_id);
							$comment ='Qty CSV Imported';
							$excel_data = array
								(
								'trans_items'=>$item_data['item_id'],
								'trans_user'=>$employee_id,
								'trans_comment'=>$comment,
								'trans_inventory'=>$data[10]
								);
								$this->db->insert('inventory',$excel_data);
						//------------------------------------------------Ramel
					}
					else//insert or update item failure
					{
						$failCodes[] = $i;
					}
				}
				
				$i++;
			}
			else 
			{
				echo json_encode( array('success'=>false,'message'=>'Your upload file has no data or not in supported format.') );
				return;
			}
		}

		$success = true;
		if(count($failCodes) > 1)
		{
			$msg = "Most items imported. But some were not, here is list of their CODE (" .count($failCodes) ."): ".implode(", ", $failCodes);
			$success = false;
		}
		else
		{
			$msg = "Import items successful";
		}

		echo json_encode( array('success'=>$success,'message'=>$msg) );
	}

	/*
	get the width for the add/edit form
	*/
	function get_form_width()
	{
		return 360;
	}
		
}
?>