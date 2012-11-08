<?php
require_once ("secure_area.php");
class Sales extends Secure_area
{
	function __construct()
	{
		parent::__construct('sales');
		$this->load->library('sale_lib');
		$this->load->helper('report');		
	}

	function index()
	{
		$transactions = array();
		$data=array();
		$data['item_number_searched']=false;
		$data['select_upc'] = false;
		$data['item_number'] = '';
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';			


		$this->sale_lib->set_transactions($transactions);
		$this->_reload($data);
	}

	function _get_common_report_data($data)
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

	function item_search()
	{
		$suggestions = $this->Item->get_item_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		$suggestions = array_merge($suggestions, $this->Item_kit->get_item_kit_search_suggestions($this->input->post('q'),$this->input->post('limit')));
		echo implode("\n",$suggestions);
	}

	function item_search_info(){
		$data = array();
		$search_info = $this->input->post("search_info");
		//$item_id=$this->Item->get_item_id_with_item_number($search_info);
		$item = $this->Item->get_info($search_info);
		$data['item_number_searched'] = true;
		$data['info_cost_price']=$item->cost_price;
		$data['info_unit_price']=$item->unit_price;
		$data['info_quantity']=$item->quantity;
		$data['info_location']=$item->location;
		$data['item_number'] = '';
		$data['select_upc'] = false;
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';			
		$this->sale_lib->set_search_info($item->item_number);	
		$this->_reload($data);
	}

	function customer_search()
	{
		$suggestions = $this->Customer->get_customer_search_suggestions($this->input->post('q'),$this->input->post('limit'));
		echo implode("\n",$suggestions);
	}

	function select_customer()
	{
		$customer_id = $this->input->post("customer");
		$data = array();

		if(($this->Customer->getBanned($customer_id)==1) && ($this->sale_lib->get_mode()=="buy")){
			echo "
			<script>
				alert('Call Manager', 'Hola');
			</script>
			";
			$this->sale_lib->set_mode('sale');
		}else{
			$this->sale_lib->set_customer($customer_id);	
		}			
		$this->index();
	}

	function select_upc()
	{
		$item_number = $this->input->post("item_number");
		$data = array();
		$data['select_upc'] = true;

		if($this->Item->existsItemNumber($item_number)){
			$item_id = $this->Item->get_item_id_with_item_number($item_number);
			$data['item_number'] = $item_number;
			$data['item_name'] =  $this->Item->get_item_name($item_id);
			$data['unit_price'] = $this->Item->get_item_unit_price($item_id);
			$data['cost_price'] = $this->Item->get_item_cost_price($item_id);			
			$data['category'] = $this->Item->get_item_category($item_id);			
			$data['tax1'] = $this->Item_taxes->get_tax1($item_id);			
			$data['tax2'] = $this->Item_taxes->get_tax2($item_id);			
			$data['item_number_searched']=false;
		}else{
			$data['item_number_searched']=false;
			$data['item_number'] = $item_number;
			$data['item_name'] = '';
			$data['unit_price'] = '';
			$data['cost_price'] = '';			
			$data['category'] = '';			
			$data['tax1'] = '';			
			$data['tax2'] = '';			
		}
		$this->_reload($data);
	}


	function change_mode()
	{
		$mode = $this->input->post("mode");
		$this->sale_lib->set_mode($mode);
		$this->index();
	}
	
	function set_comment() 
	{
 	  $this->sale_lib->set_comment($this->input->post('comment'));
	}
	
	function set_email_receipt()
	{
 	  $this->sale_lib->set_email_receipt($this->input->post('email_receipt'));
	}

	//Alain Multiple Payments
	function add_payment()
	{		
		$data=array();
		$data['item_number_searched']=false;
		$data['select_upc'] = false;
		$data['item_number'] = '';
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';	

		$this->form_validation->set_rules('amount_tendered', 'lang:sales_amount_tendered', 'numeric');

		if ($this->form_validation->run() == FALSE)
		{
			if ( $this->input->post('payment_type') == $this->lang->line('sales_gift_card') )
				$data['error']=$this->lang->line('sales_must_enter_numeric_giftcard');
			else
				$data['error']=$this->lang->line('sales_must_enter_numeric');
				
 			$this->_reload($data);
 			return;
		}
		
		
		$payment_type=$this->input->post('payment_type');
		if ( $payment_type == $this->lang->line('sales_giftcard') )
		{
			$payments = $this->sale_lib->get_payments();
			$payment_type=$this->input->post('payment_type').':'.$payment_amount=$this->input->post('amount_tendered');
			$current_payments_with_giftcard = isset($payments[$payment_type]) ? $payments[$payment_type]['payment_amount'] : 0;
			$cur_giftcard_value = $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $current_payments_with_giftcard;
			if ( $cur_giftcard_value < 0 )
			{
				$data['error']='Giftcard balance is '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) ).' !';
				$this->_reload($data);
				return;
			}
			elseif ( ( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ) > 0 )
			{
				$data['warning']='Giftcard balance is '.to_currency( $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) - $this->sale_lib->get_total() ).' !';
			}
			
				//Se lo aplicamos a las compras que esten en el carrito de compras
				$cart = $this->sale_lib->get_cart();
				$bonus =0;
				$buy_prices=0;
				foreach(array_reverse($cart, true) as $line=>$item)
				{	
					if ($item['mode']=='buy'){
						$items_taxes_data[] = array('name'=>'GC Bonus', 'percent'=> 10);				
						$this->Item_taxes->save($items_taxes_data, $item['item_id']);
						$buy_prices += $item['price'];
					}
				}
				$bonus = $buy_prices*(0.10);
				
				if($cur_giftcard_value < $this->input->post('amount_due')){
					$payment_amount=$cur_giftcard_value-$bonus;
					$this->sale_lib->add_giftcard_payment();
				}else{
					$payment_amount=$this->input->post('amount_due')-$bonus;
					$this->sale_lib->add_giftcard_payment();
					//$payment_amount=min( $this->sale_lib->get_total(), $this->Giftcard->get_giftcard_value( $this->input->post('amount_tendered') ) );
				}
			
			}
		else
		{
			$payment_amount=$this->input->post('amount_tendered');
		}
		
		if( !$this->sale_lib->add_payment( $payment_type, $payment_amount ) )
		{
			$data['error']='Unable to A Payment! Please try again!';
		}
		
		$this->_reload($data);
	}

	//Alain Multiple Payments
	function delete_payment($payment_id)
	{
		$this->sale_lib->delete_payment($payment_id);
		$data['item_number_searched']=false;		
		$data['item_number'] = '';
		$data['select_upc'] = false;
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';			
		$this->_reload($data);
	}

	function payment_types()
	{
		$data['item_number_searched']=false;
		$data['item_number'] = '';
		$data['select_upc'] = false;
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';			
		
		//Se lo aplicamos a las compras que esten en el carrito de compras
		$cart = $this->sale_lib->get_cart();
		foreach(array_reverse($cart, true) as $line=>$item)
		{	
			if ($item['mode']=='buy'){
				$items_taxes_data[] = array('name'=>'Giftcard Tax', 'percent'=> 10);				
				$this->Item_taxes->save($items_taxes_data, $item['item_id']);
			}
		}
		
							
		$this->_reload($data);
	}


	function add(){
	
		$data=array();
		$mode = $this->sale_lib->get_mode();
		$item_id_or_number_or_item_kit_or_receipt = $this->input->post("item");
		//$quantity = ($mode=="sale") ? 1:-1;
		if(($mode=="sale")||($mode=="repair")||($mode=="giftcard"))
			$quantity = 1;
		else 
			$quantity = -1;
	
		if($mode=='giftcard'){
		
			$category_id = 0;
			if(!$this->Item->category_exists($this->lang->line('sales_giftcard'))){
				$this->Item->add_category($this->lang->line('sales_giftcard'));
				$category_id = 	$this->Item->get_category_id($this->lang->line('sales_giftcard'));		
			}else{
				$category_id = 	$this->Item->get_category_id($this->lang->line('sales_giftcard'));		
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
			if($next_sequential_number == 0) $sku = $cat_id.'00000';
			else $sku = $next_sequential_number;
			
			$item_data = array(
				'name'=>$this->lang->line('sales_giftcard'),
				'item_number'=> $this->input->post('giftcard_number'),
				'sku' => $sku,
				'description'=>$this->lang->line('sales_giftcard'),
				'serial_number'=>$this->input->post('giftcard_number'),
				'cost_price' => $this->input->post('giftcard_value'),
				'register_mode'=>'Giftcard',
				'location'=>$this->session->userdata('store_address'),			
				'store_id'=>$this->session->userdata('store_id'),			
				
			);

			if(!$this->Giftcard->existsGCnumber($this->input->post('giftcard_number')))
				$this->Item->save($item_data);
			else
				$this->Item->save($item_data);
				

			$item_data = array(
				'item_id' => $this->Item->get_last_item_id(),
				'item_number'=> $this->input->post('giftcard_number'),
				'sku' => $sku,
				'serial_number'=>$this->input->post('giftcard_number'),
				'name'=>$this->lang->line('sales_giftcard'),
				'description'=>$this->lang->line('sales_giftcard'),
				'cost_price' => $this->input->post('giftcard_value'),
				'register_mode'=>'Giftcard',
				'location'=>$this->session->userdata('store_address'),			
				'store_id'=>$this->session->userdata('store_id'),			
			);
		
			$giftcard_data = array(
				'quantity' => $quantity,
				'item_id'=> $this->Item->get_last_item_id(),
				'item_number' =>$this->input->post('giftcard_number'),				
				'sku' => $sku,
				'customer_id'=>$this->input->post('customer'),
				'giftcard_price'=>$this->input->post('giftcard_value'),
				'giftcard_number'=>$this->input->post('giftcard_number'),
				'value'=>$this->input->post('giftcard_value'),
				'serial_number'=>$this->input->post('giftcard_number')
			);
			
			$giftcard_da = array(
				'giftcard_number'=>$this->input->post('giftcard_number'),
				'value'=>$this->input->post('giftcard_value')
			);
			
			if(!$this->Giftcard->existsGCnumber($this->input->post('giftcard_number'))){
				$giftcard_id=-1;
				$this->Giftcard->save( $giftcard_da, $giftcard_id );

				$items_taxes_data[] = array('name'=>'Sales Tax', 'percent'=> 0);				
				$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());					
				
				$items_taxes_data[] = array('name'=>'Sales Tax 2', 'percent'=> 0);				
				$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());	
				
				$this->sale_lib->add_item_giftcard($giftcard_data);	
				$transactions = $this->sale_lib->get_transactions();
				$transactions['giftcard']=1;
				$this->sale_lib->set_transactions($transactions);
			}else{
				//This giftcard exists
				$this->Giftcard->addCredit($this->input->post('giftcard_number'), $this->input->post('giftcard_value'), $this->Giftcard->get_giftcard_id($this->input->post(
				'giftcard_number')) );

				$items_taxes_data[] = array('name'=>'Sales Tax', 'percent'=> 0);				
				$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());					
				
				$items_taxes_data[] = array('name'=>'Sales Tax 2', 'percent'=> 0);				
				$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());	

				$transactions = $this->sale_lib->get_transactions();
				$transactions['giftcard']=1;
				$this->sale_lib->set_transactions($transactions);

				$this->sale_lib->add_item_giftcard($giftcard_data);	
				
			}

		}

			
		elseif($mode=='buy'){
			if(($this->input->post('serial_number')=='') || !$this->Item->exists($this->Item->get_item_id($this->input->post('serial_number')))){
				
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
				if($next_sequential_number == 0) $sku = $cat_id.'00000';
				else $sku = $next_sequential_number;
				
				if($this->input->post('serial_number')==''){
		
					$item_data = array(
						'item_number' =>$this->input->post('item_number_hidden'), 
						'sku' => $sku,
						'name'=>$this->input->post('item_name'),
						'description'=>$this->input->post('defect_type')."-".$this->input->post('notes'),
						'cost_price' => $this->input->post('buy_price')-$this->input->post('repair_price'),
						'category_id' => $category_id,
						'register_mode'=>'Buy',
						'location'=>$this->session->userdata('store_address'),			
						'store_id'=>$this->session->userdata('store_id'),			
					);
				}else{
					$item_data = array(
						'item_number' =>$this->input->post('item_number_hidden'), 
						'sku' => $sku,
						'serial_number' =>$this->input->post('serial_number'),
						'name'=>$this->input->post('item_name'),
						'description'=>$this->input->post('defect_type')."-".$this->input->post('notes'),
						'cost_price' => $this->input->post('buy_price')-$this->input->post('repair_price'),
						'category_id' => $category_id,
						'register_mode'=>'Buy',
						'location'=>$this->session->userdata('store_address'),			
						'store_id'=>$this->session->userdata('store_id'),			
					);
				}
				
				$this->Item->save($item_data);
				
				if((is_numeric($this->input->post('buy_tax1')) &&  ($this->input->post('buy_tax1')>0))){
					$items_taxes_data[] = array('name'=>'Sales Tax', 'percent'=> $this->input->post('buy_tax1'));				
					$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());					
				}
				
				if((is_numeric($this->input->post('buy_tax2')) &&  ($this->input->post('buy_tax2')>0))){				
					$items_taxes_data[] = array('name'=>'Sales Tax 2', 'percent'=> $this->input->post('buy_tax2'));				
					$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());	
				}
							
				$buy_data = array(
					'quantity' => $quantity,
					'item_id'=> $this->Item->get_last_item_id(),
					'item_name' => $this->input->post('item_name'),
					'serial_number' =>$this->input->post('serial_number'),
					'buy_item_number' => $this->input->post('item_number_hidden'),
					'sku' => $sku,
					'customer_id'=>$this->input->post('customer'),
					'employee_id'=>$this->input->post('employee'),
					'equipment'=>$this->input->post('equipment'),
					'defect_type'=>$this->input->post('defect_type'),
					'repair_price'=>$this->input->post('repair_price'),
					'buy_price'=>$this->input->post('buy_price'),
					'notes'=>$this->input->post('notes'),
					'flaws'=>$this->input->post('flaws'),
					'accessories'=>$this->input->post('accessories'),
				);
				$this->sale_lib->add_item_buy($buy_data);
				$transactions = $this->sale_lib->get_transactions();
				$transactions['sale']=1;
				$this->sale_lib->set_transactions($transactions);
				$this->session->set_userdata('buy_repair_transaction', $this->session->userdata('buy_repair_transaction')+1);

			}else{
				//This article exists
				echo "<script> alert('The serial number of this article already exists');</script>";
			}
		}
		elseif($mode=='repair')
		{
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
			if($next_sequential_number == 0) $sku = $cat_id.'00000';
			else $sku = $next_sequential_number;

		
			if(!$this->Item->exists($this->Item->get_item_id($this->input->post('item_number')))){
				$item_data = array(
					'item_number' =>$this->input->post('item_number'),
					'sku' => $sku,
					'serial_number' =>$this->input->post('serial_number'), 
					'name'=>$this->input->post('name'),
					'description'=>$this->input->post('defect_type')."-".$this->input->post('notes'),
					'register_mode'=>'Repair',
					'category_id' => $category_id,
					'location'=>$this->session->userdata('store_address'),			
					'store_id'=>$this->session->userdata('store_id'),			
				);
				
				$this->Item->save($item_data);
	
				if((is_numeric($this->input->post('repair_tax1')) &&  ($this->input->post('repair_tax1')>0))){
					$items_taxes_data[] = array('name'=>'Sales Tax', 'percent'=> $this->input->post('repair_tax1'));				
					$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());					
				}
				
				if((is_numeric($this->input->post('repair_tax2')) &&  ($this->input->post('repair_tax2')>0))){				
					$items_taxes_data[] = array('name'=>'Sales Tax 2', 'percent'=> $this->input->post('repair_tax2'));				
					$this->Item_taxes->save($items_taxes_data, $this->Item->get_last_item_id());	
				}

	
				$item_data = array(
					'item_id' => $this->Item->get_last_item_id(),
					'item_number' =>$this->input->post('item_number'), 	
					'sku' => $sku,
					'serial_number' =>$this->input->post('serial_number'),								
					'name'=>$this->input->post('name'),
					'description'=>$this->input->post('defect_type')."-".$this->input->post('notes'),
					'register_mode'=>'Repair',
					'quantity'=>$quantity,
					'location'=>$this->session->userdata('store_address'),			
					'store_id'=>$this->session->userdata('store_id'),			
				);
				
				
				$repair_data = array(
					'item_number' =>$this->input->post('item_number'),
					'sku' => $sku,
					'serial_number' =>$this->input->post('serial_number'),
					'item_id'=> $this->Item->get_last_item_id(),
					'customer_id'=>$this->input->post('customer'),
					'employee_id'=>$this->input->post('employee'),
					'repair_item_number'=>$this->input->post('item_number'),
					'equipment'=>$this->input->post('item_name'),
					'defect_type'=>$this->input->post('defect_type'),
					'repair_price'=>$this->input->post('repair_price'),
					'notes'=>$this->input->post('notes'),
					'password'=>$this->input->post('password'),
					'flaws'=>$this->input->post('flaws'),
					'accessories'=>$this->input->post('accessories'),
					'deposit'=>$this->input->post('deposit'),
					'delivery_date'=>$this->input->post('start_year')."-".$this->input->post('start_month')."-".$this->input->post('start_day')
				);
				
				$this->sale_lib->add_item_repair($repair_data);
				$transactions = $this->sale_lib->get_transactions();
				$transactions['repair']=1;
				$this->sale_lib->set_transactions($transactions);
				$this->session->set_userdata('buy_repair_transaction', $this->session->userdata('buy_repair_transaction')+1);
				
				if($this->Sale->isCoveredbyWarrantyRepair($this->input->post('item_number'))){
					$repair_price= -($this->input->post('repair_price'));
					$defect_type = $this->input->post('defect_type')." (Covered by warranty on sales)";
					$repair_data = array(
						'item_number' =>$this->input->post('item_number'),
						'item_id'=> $this->Item->get_last_item_id(),
						'serial_number' =>$this->input->post('serial_number'),
						'sku' => $sku,
						'customer_id'=>$this->input->post('customer'),
						'employee_id'=>$this->input->post('employee'),
						'repair_item_number'=>$this->input->post('repair_item_number'),
						'equipment'=>"Warranty",
						'defect_type'=>"Warranty",
						'repair_price'=>$repair_price,
						'notes'=>$this->input->post('notes'),
						'password'=>$this->input->post('password'),
						'flaws'=>$this->input->post('flaws'),
						'accessories'=>$this->input->post('accessories'),
						'deposit'=>$this->input->post('deposit'),
						'delivery_date'=>$this->input->post('start_year')."-".$this->input->post('start_month')."-".$this->input->post('start_day')
					);				
				$this->sale_lib->add_item_repair($repair_data);

				}elseif($this->Sale->isCoveredbyWarrantySales($this->input->post('item_number'))){
					$repair_price= -($this->input->post('repair_price'));
					$defect_type = $this->input->post('defect_type')." (Covered by warranty on repair)";
					$repair_data = array(
						'item_number' =>$this->input->post('item_number'),
						'item_id'=> $this->Item->get_last_item_id(),
						'serial_number' =>$this->input->post('serial_number'),
						'sku' => $sku,
						'customer_id'=>$this->input->post('customer'),
						'employee_id'=>$this->input->post('employee'),
						'repair_item_number'=>$this->input->post('repair_item_number'),
						'equipment'=>"Warranty",
						'defect_type'=>"Warranty",
						'repair_price'=>$repair_price,
						'notes'=>$this->input->post('notes'),
						'password'=>$this->input->post('password'),
						'flaws'=>$this->input->post('flaws'),
						'accessories'=>$this->input->post('accessories'),
						'deposit'=>$this->input->post('deposit'),
						'delivery_date'=>$this->input->post('start_year')."-".$this->input->post('start_month')."-".$this->input->post('start_day')
					);		
					$this->sale_lib->add_item_repair($repair_data);

				}
				
			}else{
				//This article exists
				echo "<script> alert('The number of this article already exists');</script>";
			}	
		}
		elseif($this->sale_lib->is_valid_receipt($item_id_or_number_or_item_kit_or_receipt) && $mode=='return')
		{
			$transactions = $this->sale_lib->get_transactions();
			$transactions['return']=1;
			$this->sale_lib->set_transactions($transactions);
			$this->sale_lib->return_entire_sale($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif($this->sale_lib->is_valid_item_kit($item_id_or_number_or_item_kit_or_receipt))
		{
			$transactions = $this->sale_lib->get_transactions();
			$transactions['sale']=1;
			$this->sale_lib->set_transactions($transactions);
			$this->sale_lib->add_item_kit($item_id_or_number_or_item_kit_or_receipt);
		}
		elseif(!$this->sale_lib->add_item($item_id_or_number_or_item_kit_or_receipt,$quantity))
		{
			$data['error']=$this->lang->line('sales_unable_to_add_item');
		}else{
			$transactions = $this->sale_lib->get_transactions();
			$transactions['sale']=1;
			$this->sale_lib->set_transactions($transactions);
		}
		
		if($this->sale_lib->out_of_stock($item_id_or_number_or_item_kit_or_receipt))
		{
			$data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
		}

		$data['item_number'] = $this->input->post('item_number');
		$data['item_number_searched']=false;		
		$data['select_upc'] = false;
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';			
		
		$this->_reload($data);
	}


	function edit_item($line)
	{
		$data= array();

		$this->form_validation->set_rules('price', 'lang:items_price', 'required|numeric');
		$this->form_validation->set_rules('quantity', 'lang:items_quantity', 'required|numeric');

        $description = $this->input->post("description");
        $serialnumber = $this->input->post("serialnumber");
		$price = $this->input->post("price");
		$quantity = $this->input->post("quantity");
		$discount = $this->input->post("discount");


		if ($this->form_validation->run() != FALSE)
		{
			$this->sale_lib->edit_item($line,$description,$serialnumber,$quantity,$discount,$price);
		}
		else
		{
			$data['error']=$this->lang->line('sales_error_editing_item');
		}
		
		if($this->sale_lib->out_of_stock($this->sale_lib->get_item_id($line)))
		{
			$data['warning'] = $this->lang->line('sales_quantity_less_than_zero');
		}


		$this->_reload($data);
	}

	function delete_item($item_number)
	{	
		echo "Item id: ".$this->sale_lib->get_item_id($item_number);
		echo "Item number: ".$this->Item->get_item_number($this->sale_lib->get_item_id($item_number));
		$gifcard_id = $this->Giftcard->get_giftcard_id($this->Item->get_item_number($this->sale_lib->get_item_id($item_number)));
		echo "Giftcard id: ".$gifcard_id;
		$this->Giftcard->deleteGift($gifcard_id, $this->sale_lib->get_item_price($item_number));
		$this->sale_lib->delete_item($item_number);
		
		$data['item_number_searched']=false;
		$data['select_upc'] = false;
		$data['item_number'] = '';
		$data['item_name'] =   '';
		$data['unit_price'] =  '';
		$data['cost_price'] =  '';			
		$data['category'] =  '';			
		$data['tax1'] =  '';			
		$data['tax2'] =  '';	
		$this->_reload($data);
	}

	function detailed_customer_report(){
		$this->specific_customer('1969-12-31',  date('Y-m-d'), $this->sale_lib->get_customer(),'all', $export_excel=0);			
	}
	
	function specific_customer($start_date, $end_date, $customer_id, $sale_type, $export_excel=0)
	{
		$this->load->model('reports/Specific_customer');
		$model = $this->Specific_customer;
		
		$headers = $model->getDataColumns();
		$report_data = $model->getData(array('start_date'=>$start_date, 'end_date'=>$end_date, 'customer_id' =>$customer_id, 
									   'sale_type' => $sale_type));
		
		$summary_data = array();
		$details_data = array();
		
		foreach($report_data['summary'] as $key=>$row)
		{
			$summary_data[] = array(anchor('sales/edit/'.$row['sale_id'], 'POS '.$row['sale_id'], array('target' => '_blank')), $row['sale_date'], 
									$row['items_purchased'], $row['employee_name'], to_currency($row['subtotal']), to_currency($row['total']),
									to_currency($row['tax']),to_currency($row['profit']), $row['payment_type'], $row['comment']);
			
			foreach($report_data['details'][$key] as $drow)
			{
				$details_data[$key][] = array($drow['name'], $drow['category'], $drow['serialnumber'], $drow['description'],$drow['quantity_purchased'
				], to_currency($drow['subtotal']), to_currency($drow['total']), to_currency($drow['tax']),to_currency($drow['profit']), $drow[
				'discount_percent'].'%');
			}
		}

		$customer_info = $this->Customer->get_info($customer_id);
		$data = array(
			"title" => $customer_info->first_name .' '. $customer_info->last_name.' '.$this->lang->line('reports_report'),
			"subtitle" => date('m/d/Y', strtotime($start_date)) .'-'.date('m/d/Y', strtotime($end_date)),
			"headers" => $model->getDataColumns(),
			"summary_data" => $summary_data,
			"details_data" => $details_data,
			"overall_summary_data" => $model->getSummaryData(array('start_date'=>$start_date, 'end_date'=>$end_date,'customer_id' =>$customer_id, 
									  'sale_type' => $sale_type)),
			"export_excel" => $export_excel
		);

		$this->load->view("reports/tabular_details",$data);
	}
	

	function remove_customer()
	{
		$this->sale_lib->remove_customer();
		$data['item_number_searched']=false;
		$this->_reload($data);
	}

	function complete()
	{
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->sale_lib->get_comment();
		$emp_info=$this->Employee->get_info($employee_id);
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}else{
		
			if($this->Customer->anonymousExists()){
				$customer_id = $this->Customer->get_anonymous_id();
			}else{
				$person_data = array(
					'first_name'=>'Anonymous',
					'last_name'=>'Anonymous',
					'banned'=>1
				);
				$customer_data=array(
					'account_number'=>$this->input->post('account_number')=='' ? null:$this->input->post('account_number'),
					'taxable'=>1,
				);
				$this->Customer->save($person_data,$customer_data,$customer_id);
				$customer_id = $this->Customer->get_anonymous_id();
			}
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;

		}

		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments']);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('sales_transaction_failed');
		}
		else
		{
			if ($this->sale_lib->get_email_receipt() && !empty($cust_info->email))
			{
				$this->load->library('email');
				$config['mailtype'] = 'html';				
				$this->email->initialize($config);
				$this->email->from($this->config->item('email'), $this->config->item('company'));
				$this->email->to($cust_info->email); 

				$this->email->subject($this->lang->line('sales_receipt'));
				$this->email->message($this->load->view("sales/receipt_email",$data, true));	
				$this->email->send();
			}
		}
		$this->load->view("sales/receipt",$data);
		$this->session->set_userdata('buy_repair_transaction', 0);
		$this->sale_lib->clear_all();
	}
	
	function receipt($sale_id)
	{
		$sale_info = $this->Sale->get_info($sale_id)->row_array();
		$this->sale_lib->copy_entire_sale($sale_id);
		$data['cart']=$this->sale_lib->get_cart();
		$data['payments']=$this->sale_lib->get_payments();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a', strtotime($sale_info['sale_time']));
		$customer_id=$this->sale_lib->get_customer();
		$emp_info=$this->Employee->get_info($sale_info['employee_id']);
		$data['payment_type']=$sale_info['payment_type'];
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}
		$data['sale_id']='POS '.$sale_id;
		$this->load->view("sales/receipt",$data);
		$this->sale_lib->clear_all();
		$this->session->set_userdata('buy_repair_transaction', 0);
	}
	
	function edit($sale_id)
	{
		$data = array();

		$data['customers'] = array('' => 'No Customer');
		foreach ($this->Customer->get_all()->result() as $customer)
		{
			$data['customers'][$customer->person_id] = $customer->first_name . ' '. $customer->last_name;
		}

		$data['employees'] = array();
		foreach ($this->Employee->get_all()->result() as $employee)
		{
			$data['employees'][$employee->person_id] = $employee->first_name . ' '. $employee->last_name;
		}

		$data['sale_info'] = $this->Sale->get_info($sale_id)->row_array();
				
		
		$this->load->view('sales/edit', $data);
	}
	
	function delete($sale_id)
	{
		$data = array();
		
		if ($this->Sale->delete($sale_id))
		{
			$data['success'] = true;
		}
		else
		{
			$data['success'] = false;
		}
		
		$this->load->view('sales/delete', $data);
		
	}
	
	function save($sale_id)
	{
		$sale_data = array(
			'sale_time' => date('Y-m-d', strtotime($this->input->post('date'))),
			'customer_id' => $this->input->post('customer_id') ? $this->input->post('customer_id') : null,
			'employee_id' => $this->input->post('employee_id'),
			'comment' => $this->input->post('comment')
		);
		
		if ($this->Sale->update($sale_data, $sale_id))
		{
			echo json_encode(array('success'=>true,'message'=>$this->lang->line('sales_successfully_updated')));
		}
		else
		{
			echo json_encode(array('success'=>false,'message'=>$this->lang->line('sales_unsuccessfully_updated')));
		}
	}
	
	function _payments_cover_total()
	{
		$total_payments = 0;

		foreach($this->sale_lib->get_payments() as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}

		/* Changed the conditional to account for floating point rounding */
		if ( ( $this->sale_lib->get_mode() == 'sale' ) && ( ( to_currency_no_money( $this->sale_lib->get_total() ) - $total_payments ) > 1e-6 ) )
		{
			return false;
		}
		
		return true;
	}
	
	function _reload($data=array())
	{
		$data['report_date_range_simple'] = get_simple_date_ranges();
		$data['months'] = get_months();
		$data['days'] = get_days();
		$data['years'] = get_years();
		$data['selected_month']=date('n');
		$data['selected_day']=date('d');
		$data['selected_year']=date('Y');	
		//$data = $this->_get_common_report_data();
		foreach($this->Employee->get_all()->result_array() as $row)
		{
			$employees[$row['person_id']] = $row['first_name'] .' '. $row['last_name'];
		}
		$data['employees']=$employees;
		$person_info = $this->Employee->get_logged_in_employee_info();
		$data['cart']=$this->sale_lib->get_cart();
		
		$customer_id=$this->sale_lib->get_customer();							

		$data['modes']=array('sale'=>$this->lang->line('sales_sale'), 
							 'buy'=>$this->lang->line('sales_buy'),
							 'repair'=>$this->lang->line('sales_repair'),
							 'return'=>$this->lang->line('sales_return'),
							 'giftcard'=>$this->lang->line('sales_giftcard'));
	
		if($customer_id != -1){
			$data['banned'] = $this->Customer->getBanned($customer_id);
		}	
	
		$data['mode']=$this->sale_lib->get_mode();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['items_module_allowed'] = $this->Employee->has_permission('items', $person_info->person_id);
		$data['comment'] = $this->sale_lib->get_comment();
		$data['email_receipt'] = $this->sale_lib->get_email_receipt();
		$data['payments_total']=$this->sale_lib->get_payments_total();
		$data['amount_due']=$this->sale_lib->get_amount_due();
		$data['payments']=$this->sale_lib->get_payments();
		$data['payment_options']=array(
			$this->lang->line('sales_cash') => $this->lang->line('sales_cash'),
			$this->lang->line('sales_check') => $this->lang->line('sales_check'),
			$this->lang->line('sales_giftcard') => $this->lang->line('sales_giftcard'),
			$this->lang->line('sales_debit') => $this->lang->line('sales_debit'),
			$this->lang->line('sales_credit') => $this->lang->line('sales_credit')
		);
		if($customer_id!=-1)
		{
			$info=$this->Customer->get_info($customer_id);
			$data['customer']=$info->first_name.' '.$info->last_name;
			$data['customer_email']=$info->email;
		}
		$data['payments_cover_total'] = $this->_payments_cover_total();
		$this->load->view("sales/register",$data);
	}

    function cancel_sale()
    {
    	$this->sale_lib->clear_all();
    	$this->_reload();

    }
	
	function suspend()
	{
		$data['cart']=$this->sale_lib->get_cart();
		$data['subtotal']=$this->sale_lib->get_subtotal();
		$data['taxes']=$this->sale_lib->get_taxes();
		$data['total']=$this->sale_lib->get_total();
		$data['receipt_title']=$this->lang->line('sales_receipt');
		$data['transaction_time']= date('m/d/Y h:i:s a');
		$customer_id=$this->sale_lib->get_customer();
		$employee_id=$this->Employee->get_logged_in_employee_info()->person_id;
		$comment = $this->input->post('comment');
		$emp_info=$this->Employee->get_info($employee_id);
		$payment_type = $this->input->post('payment_type');
		$data['payment_type']=$this->input->post('payment_type');
		//Alain Multiple payments
		$data['payments']=$this->sale_lib->get_payments();
		$data['amount_change']=to_currency($this->sale_lib->get_amount_due() * -1);
		$data['employee']=$emp_info->first_name.' '.$emp_info->last_name;

		if($customer_id!=-1)
		{
			$cust_info=$this->Customer->get_info($customer_id);
			$data['customer']=$cust_info->first_name.' '.$cust_info->last_name;
		}

		$total_payments = 0;

		foreach($data['payments'] as $payment)
		{
			$total_payments += $payment['payment_amount'];
		}

		//SAVE sale to database
		$data['sale_id']='POS '.$this->Sale_suspended->save($data['cart'], $customer_id,$employee_id,$comment,$data['payments']);
		if ($data['sale_id'] == 'POS -1')
		{
			$data['error_message'] = $this->lang->line('sales_transaction_failed');
		}
		$this->sale_lib->clear_all();
		$this->_reload(array('success' => $this->lang->line('sales_successfully_suspended_sale')));
	}
	
	function suspended()
	{
		$data = array();
		$data['suspended_sales'] = $this->Sale_suspended->get_all()->result_array();
		$this->load->view('sales/suspended', $data);
	}
	
	function unsuspend()
	{
		$sale_id = $this->input->post('suspended_sale_id');
		$this->sale_lib->clear_all();
		$this->sale_lib->copy_entire_suspended_sale($sale_id);
		$this->Sale_suspended->delete($sale_id);
    	$this->_reload();
	}
}
?>