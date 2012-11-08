<?php $this->load->view("partial/header"); ?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $this->lang->line('sales_register'); ?></div>
<?php
if(isset($error))
{
	echo "<div class='error_message'>".$error."</div>";
}

if (isset($warning))
{
	echo "<div class='warning_mesage'>".$warning."</div>";
}

if (isset($success))
{
	echo "<div class='success_message'>".$success."</div>";
}
?>
<div id="register_wrapper">
<div id="button" style="float:left; width:34px; height:34px;">
	<img id="helpMode" src="<?php echo base_url().'images/help.png' ?>" />
</div>

<?php echo form_open("sales/change_mode",array('id'=>'mode_form')); ?>
	<span><?php echo $this->lang->line('sales_mode') ?></span>
<?php echo form_dropdown('mode',$modes,$mode,'onchange="$(\'#mode_form\').submit();"'); ?>

</form>

	<?php if($mode=='sale' || $mode=='return'){?>
	<div id="button" style="float:left; width:34px; height:34px;">
		<img id="helpFindItem" src="<?php echo base_url().'images/help.png' ?>" />
	</div>
	<?php }?>

<?php 
if($mode=='sale' || $mode=='return')
		echo form_open("sales/add",array('id'=>'add_item_form')); 
?>

<label id="item_label" for="item">
<?php
if($mode=='sale')
{
	echo $this->lang->line('sales_find_or_scan_item');
}
if($mode=='return')
{
	echo $this->lang->line('sales_find_or_scan_item_or_receipt');
}
?>
</label>
<?php 
if($mode=='sale' || $mode=='return'){
	echo form_input(array('name'=>'item','id'=>'item','size'=>'40'));
	echo "</form>";
}
?>


<div id="new_item_button_register" >
<div id="button" style="float:left; width:34px; height:34px;">
	<img id="helpAddItem" src="<?php echo base_url().'images/help.png' ?>" />
</div>

<?php	
		if($mode=='buy')
		{
			 echo anchor("items/view/-2/width:360",
			"<div class='small_button'><span>".$this->lang->line('sales_new_item')."</span></div>",
			array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_item')));						
		}else{
			 echo anchor("items/view/-1/width:360",
			"<div class='small_button'><span>".$this->lang->line('sales_new_item')."</span></div>",
			array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_item')));			
		}
?>
</div>

<?php if($mode=='giftcard'){?>
	<fieldset id="item_basic_info">
	<legend></legend>
	
	<?php
	echo form_open('sales/add/');
	?>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('giftcards_giftcard_number').':', 'name',array('class'=>'required wide')); ?>
		<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'giftcard_number',
			'id'=>'giftcard_number',
			'value'=>'')
		);?>
		</div>
	</div>
	
	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('giftcards_card_value').':', 'name',array('class'=>'required wide')); ?>
		<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'giftcard_value',
			'id'=>'giftcard_value',
			'value'=>'')
		);?>
		</div>
	</div>
	
	<?php
	echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>$this->lang->line('common_submit'),
		'class'=>'submit_button')
	);
	?>
	<?php
	echo form_close();
	?>
<?php }?>


<?php
	if(($mode=='buy') && isset($customer) && ($banned==0)){?>	

	
		<fieldset id="item_basic_info">
		<legend></legend>
		
		<div id="leftCol" style="width:50%; float:left;">

		<?php echo form_open("sales/select_upc",array('id'=>'select_item_number_form')); ?>	
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('buy_item_number').':', 'item_number'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'item_number',
					'id'=>'item_number',
					'value'=>$item_number)
				);?>
				</div>
			</div>
		<?php echo form_close(); ?>
		<?php echo form_open('sales/add/');	?>
		<?php echo form_hidden('item_number_hidden',$item_number);	?>

		<?php if(!$select_upc){ ?>
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('buy_item_serial_number').':', 'serial_number'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'serial_number',
					'id'=>'serial_number',
					'readonly' => 'true',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php }else{ ?>	
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('buy_item_serial_number').':', 'serial_number'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'serial_number',
					'id'=>'serial_number',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php } ?>


		<?php if($select_upc){ ?>
			<?php if($item_name != ''){ ?>
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('buy_item_name').':', 'item_name'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'item_name',
						'id'=>'item_name',
						'readonly' => 'true',
						'value'=>$item_name)
					);?>
					</div>
				</div>
			<?php }else{?>
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('buy_item_name').':', 'item_name'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'item_name',
						'id'=>'item_name',
						'value'=>'')
					);?>
					</div>
				</div>
			<?php } ?> 
		<?php }else{ ?> 
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('buy_item_name').':', 'item_name'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'item_name',
						'id'=>'item_name',
						'readonly' => 'true',
						'value'=>'')
					);?>
					</div>
				</div>
		<?php }?> 
	

		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('buy_inventory_type').':', 'inventory_type'); ?>
			<div class='form_field'>
				<?php echo form_dropdown('inventory_type',
				array('New' => $this->lang->line('buy_inventory_type_new'), 
					  'Used2Repair' => $this->lang->line('buy_inventory_type_used_to_repair'),
  					  'Used2Sale' => $this->lang->line('buy_inventory_type_used_ready_for_sale')
					  )); 
				?>	  
			</div>
		</div>	

		<?php if($select_upc){ ?>
			<?php if($category != ''){ ?>	
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('buy_category').':', 'category'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'category',
						'id'=>'category',
						'readonly' => 'true',
						'value'=>$category)
					);?>
					</div>
				</div>
			<?php }else{ ?>
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('buy_category').':', 'category'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'category',
						'id'=>'category',
						'value'=>'')
					);?>
					</div>
				</div>
			<?php }?>
		<?php }else{ ?>
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('buy_category').':', 'category'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'category',
						'id'=>'category',
						'readonly' => 'true',
						'value'=>'')
					);?>
					</div>
				</div>
		<?php } ?>
		

		<?php if(!$select_upc){ ?>
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('defect_type').':', 'defect_type'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'defect_type',
					'id'=>'defect_type',
					'readonly' => 'true',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php }else{ ?>	
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('defect_type').':', 'defect_type'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'defect_type',
					'id'=>'defect_type',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php } ?>		

		<?php if(!$select_upc){ ?>		
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_notes').':', 'notes',array('class'=>'wide')); ?>
				<div class='form_field'>
				<?php echo form_textarea(array(
					'name'=>'notes',
					'id'=>'notes',
					'readonly' => 'true',
					'value'=>'',
					'rows'=>'5',
					'cols'=>'29')
				);?>
				</div>
			</div>	
		<?php }else{ ?>	
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_notes').':', 'notes',array('class'=>'wide')); ?>
				<div class='form_field'>
				<?php echo form_textarea(array(
					'name'=>'notes',
					'id'=>'notes',
					'value'=>'',
					'rows'=>'5',
					'cols'=>'29')
				);?>
				</div>
			</div>	
		<?php } ?>

		<?php if(!$select_upc){ ?>				
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_accessories').':', 'accessories',array('class'=>'required wide')); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'accessories',
					'id'=>'accessories',
					'readonly' => 'true',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php }else{ ?>				
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_accessories').':', 'accessories',array('class'=>'required wide')); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'accessories',
					'id'=>'accessories',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php } ?>
		
		</div>
	
		<div id="rightCol" style="width:50%; float:left;">

		<?php if($select_upc){ ?>					
			<?php if($cost_price != ''){ ?>
				<div class="field_row clearfix">
					<?php echo form_label($this->lang->line('repair_cost_price_buy').':', 'buy_price'); ?>
						<div class='form_field'>
						<?php echo form_input(array(
							'name'=>'buy_price',
							'id'=>'buy_price',
							'readonly' => 'true',
							'value'=>$cost_price)
						);?>
						</div>
				</div>
			<?php }else{ ?>
				<div class="field_row clearfix">
					<?php echo form_label($this->lang->line('repair_cost_price_buy').':', 'buy_price'); ?>
						<div class='form_field'>
						<?php echo form_input(array(
							'name'=>'buy_price',
							'id'=>'buy_price',
							'value'=>$cost_price)
						);?>
						</div>
				</div>
			<?php }?>
		<?php }else{ ?>					
				<div class="field_row clearfix">
					<?php echo form_label($this->lang->line('repair_cost_price_buy').':', 'buy_price'); ?>
						<div class='form_field'>
						<?php echo form_input(array(
							'name'=>'buy_price',
							'id'=>'buy_price',
							'readonly' => 'true',
							'value'=>$cost_price)
						);?>
						</div>
				</div>
		<?php } ?>					


		<?php if($select_upc){ ?>					
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_cost_price').':', 'repair_price'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'repair_price',
					'id'=>'repair_price',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php }else{ ?>					
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_cost_price').':', 'repair_price'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'repair_price',
					'readonly' => 'true',
					'id'=>'repair_price',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php } ?>					

		<?php if($select_upc){ ?>					
			<?php if($tax1 != ''){ ?>	
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_tax1').':', 'buy_tax1'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'buy_tax1',
						'id'=>'buy_tax1',
						'value'=>$tax1)
					);?>
					</div>
				</div>
			<?php }else{ ?>	
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_tax1').':', 'buy_tax1'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'buy_tax1',
						'id'=>'buy_tax1',
						'value'=>'')
					);?>
					</div>
				</div>
			<?php } ?>	
		<?php }else{ ?>	
			<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_tax1').':', 'buy_tax1'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'buy_tax1',
					'id'=>'buy_tax1',
					'readonly' => 'true',
					'value'=>'')
				);?>
				</div>
			</div>
		<?php } ?>	

		
		<?php if($select_upc){ ?>					
			<?php if($tax2 != ''){ ?>	
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_tax2').':', 'buy_tax2'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'buy_tax2',
						'id'=>'buy_tax2',
						'value'=>$tax2)
					);?>
					</div>
				</div>
			<?php }else{ ?>	
				<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_tax2').':', 'buy_tax2'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'buy_tax2',
						'id'=>'buy_tax2',
						'value'=>'')
					);?>
					</div>
				</div>
			<?php } ?>	
		<?php }else{ ?>			
			<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_tax2').':', 'buy_tax2'); ?>
					<div class='form_field'>
					<?php echo form_input(array(
						'name'=>'buy_tax2',
						'id'=>'buy_tax2',
						'readonly' => 'true',
						'value'=>'')
					);?>
				</div>
			</div>			
		<?php } ?>	
		

		<?php if($select_upc){ ?>							
			<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_flaws').':', 'flaws',array('class'=>'wide')); ?>
				<div class='form_field'>
				<?php echo form_textarea(array(
					'name'=>'flaws',
					'id'=>'flaws',
					'value'=>'',
					'rows'=>'5',
					'cols'=>'25')
				);?>
				</div>
			</div>
		<?php }else{ ?>							
			<div class="field_row clearfix">
				<?php echo form_label($this->lang->line('repair_flaws').':', 'flaws',array('class'=>'wide')); ?>
				<div class='form_field'>
				<?php echo form_textarea(array(
					'name'=>'flaws',
					'id'=>'flaws',
					'readonly' => 'true',
					'value'=>'',
					'rows'=>'5',
					'cols'=>'25')
				);?>
				</div>
			</div>
		<?php } ?>							

		
		</div>
	
	
		<?php 
		echo form_submit(array(
		'name'=>'submit',
		'id'=>'submit',
		'value'=>$this->lang->line('common_submit'),
		'class'=>'submit_button')
		);
		echo "</fieldset>";
		echo form_close();
		
	}
	
	if(($mode=='buy') && !isset($customer)){

		echo form_open('sales/add/');
		?>
		<fieldset id="item_basic_info">
		<legend></legend>
		
		<div id="leftCol" style="width:50%; float:left;">
	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('buy_item_number').':', 'item_number'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'item_number',
				'id'=>'item_number',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>
	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('buy_item_serial_number').':', 'serial_number'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'serial_number',
				'id'=>'serial_number',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>
	
	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('buy_item_name').':', 'item_name'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'item_name',
				'id'=>'item_name',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>

		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('buy_inventory_type').':', 'inventory_type'); ?>
			<div class='form_field'>
				<?php echo form_dropdown('inventory_type',
				array('New' => $this->lang->line('buy_inventory_type_new'), 
					  'Used2Repair' => $this->lang->line('buy_inventory_type_used_to_repair'),
  					  'Used2Sale' => $this->lang->line('buy_inventory_type_used_ready_for_sale')
					  )); 
				?>	  
			</div>
		</div>	
	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('buy_category').':', 'category'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'category',
				'id'=>'category',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>
	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('defect_type').':', 'defect_type'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'defect_type',
				'id'=>'defect_type',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>	
	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_notes').':', 'notes',array('class'=>'wide')); ?>
			<div class='form_field'>
			<?php echo form_textarea(array(
				'name'=>'notes',
				'disabled'=>'disabled',
				'id'=>'notes',
				'value'=>'',
				'rows'=>'5',
				'cols'=>'29')
			);?>
			</div>
		</div>	
		
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_accessories').':', 'accessories',array('class'=>'required wide')); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'accessories',
				'disabled'=>'disabled',
				'id'=>'accessories',
				'value'=>'')
			);?>
			</div>
		</div>

		
		</div>
	
		<div id="rightCol" style="width:50%; float:left;">
		
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_cost_price_buy').':', 'buy_price'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'buy_price',
				'id'=>'buy_price',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>

		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_cost_price').':', 'repair_price'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_price',
				'id'=>'repair_price',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>

		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_tax1').':', 'buy_tax1'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'buy_tax1',
				'id'=>'buy_tax1',
				'disabled'=>'disabled',
				'value'=>'')
			);?>
			</div>
		</div>

		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_tax2').':', 'buy_tax2'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'buy_tax2',
				'disabled'=>'disabled',
				'id'=>'buy_tax2',
				'value'=>'')
			);?>
			</div>
		</div>
		
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_flaws').':', 'flaws',array('class'=>'wide')); ?>
			<div class='form_field'>
			<?php echo form_textarea(array(
				'name'=>'flaws',
				'disabled'=>'disabled',
				'id'=>'flaws',
				'value'=>'',
				'rows'=>'5',
				'cols'=>'25')
			);?>
			</div>
		</div>
	
		</div>
	
	
		<?php 
		echo form_submit(array(
		'name'=>'submit',
		'disabled'=>'disabled',
		'id'=>'submit',
		'value'=>$this->lang->line('common_submit'),
		'class'=>'submit_button')
		);
		echo "</fieldset>";
		echo form_close();	
	}
?>	


<?php
	if($mode=='repair')
	{?>
	
	<fieldset id="item_basic_info">
	<legend></legend>
	
	<div id="leftCol" style="width:50%; float:left;">

	<?php echo form_open("sales/select_upc",array('id'=>'select_item_number_form')); ?>	
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_item_number').':', 'item_number'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'item_number',
				'id'=>'item_number',
				'value'=>$item_number)
			);?>
			</div>
		</div>
	<?php echo form_close(); ?>
	
	<?php echo form_open('sales/add/');	?>
	<?php echo form_hidden('item_number_hidden',$item_number);	?>

	<?php echo form_open('sales/add/'); ?>

	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_item_serial_number').':', 'serial_number'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'serial_number',
					'id'=>'serial_number',
					'readonly' => 'true',
					'value'=>'')
				);?>
				</div>
		</div>
	<?php }?>

	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_item_name').':', 'item_name'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'item_name',
				'id'=>'item_name',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_item_name').':', 'item_name'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'item_name',
				'id'=>'item_name',
				'readonly' => 'true',
				'value'=>$item_name)
			);?>
			</div>
		</div>
	<?php } ?>


	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_password').':', 'password'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'password',
				'id'=>'password',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_password').':', 'password'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'password',
				'id'=>'password',
				'value'=>'')
			);?>
			</div>	
		</div>			
	<?php }?>

	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('buy_category').':', 'category'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'category',
					'id'=>'category',
					'readonly' => 'true',
					'value'=>'')
				);?>
				</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('buy_category').':', 'category'); ?>
				<div class='form_field'>
				<?php echo form_input(array(
					'name'=>'category',
					'id'=>'category',
					'value'=>$category)
				);?>
				</div>
		</div>	
	<?php }?>

	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('defect_type').':', 'defect_type'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'defect_type',
				'id'=>'defect_type',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('defect_type').':', 'defect_type'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'defect_type',
				'id'=>'defect_type',
				'value'=>'')
			);?>
			</div>
		</div>	
	<?php }?>

	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_cost_price').':', 'repair_price'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_price',
				'id'=>'repair_price',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_cost_price').':', 'repair_price'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_price',
				'id'=>'repair_price',
				'value'=>'')
			);?>
			</div>
		</div>	
	<?php }?>

	<?php if(!$select_upc){ ?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_notes').':', 'notes',array('class'=>'wide')); ?>
			<div class='form_field'>
			<?php echo form_textarea(array(
				'name'=>'notes',
				'id'=>'notes',
				'value'=>'',
				'readonly' => 'true',
				'rows'=>'5',
				'cols'=>'29')
			);?>
			</div>
		</div>	
	<?php }else{?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_notes').':', 'notes',array('class'=>'wide')); ?>
			<div class='form_field'>
			<?php echo form_textarea(array(
				'name'=>'notes',
				'id'=>'notes',
				'value'=>'',
				'rows'=>'5',
				'cols'=>'29')
			);?>
			</div>
		</div>		
	<?php }?>

	<?php if(!$select_upc){ ?>	
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_accessories').':', 'accessories'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'accessories',
				'id'=>'accessories',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_accessories').':', 'accessories'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'accessories',
				'id'=>'accessories',
				'value'=>'')
			);?>
			</div>
		</div>	
	<?php }?>
	
	</div>

	<div id="rightCol" style="width:50%; float:left;">

	<?php if(!$select_upc){ ?>		
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_tax1').':', 'repair_tax1'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_tax1',
				'id'=>'repair_tax1',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_tax1').':', 'repair_tax1'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_tax1',
				'id'=>'repair_tax1',
				'value'=>'')
			);?>
			</div>
		</div>	
	<?php }?>

	<?php if(!$select_upc){ ?>		
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_tax2').':', 'repair_tax2'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_tax2',
				'id'=>'repair_tax2',
				'readonly' => 'true',
				'value'=>'')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
		<?php echo form_label($this->lang->line('repair_tax2').':', 'repair_tax2'); ?>
			<div class='form_field'>
			<?php echo form_input(array(
				'name'=>'repair_tax2',
				'id'=>'repair_tax2',
				'value'=>'')
			);?>
			</div>
		</div>		
	<?php }?>

	<?php if(!$select_upc){ ?>			
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_flaws').':', 'flaws',array('class'=>'wide')); ?>
			<div class='form_field'>
			<?php echo form_textarea(array(
				'name'=>'flaws',
				'id'=>'flaws',
				'readonly' => 'true',
				'value'=>'',
				'rows'=>'5',
				'cols'=>'29')
			);?>
			</div>
		</div>
	<?php }else{?>
		<div class="field_row clearfix">
			<?php echo form_label($this->lang->line('repair_flaws').':', 'flaws',array('class'=>'wide')); ?>
			<div class='form_field'>
			<?php echo form_textarea(array(
				'name'=>'flaws',
				'id'=>'flaws',
				'value'=>'',
				'rows'=>'5',
				'cols'=>'29')
			);?>
			</div>
		</div>	
	<?php }?>

	<?php 
		    echo "<div class='field_row clearfix'>";
  		    echo form_label($this->lang->line('repair_employee').':', 'employee'); 
  	  		echo form_dropdown( 'employee_id', 
		 						$employees);	
			echo "</div>";
	?>

	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('delivery_date').':', 'delivery',array('class'=>'required wide')); ?>
		<div class='form_field'>
			<?php echo form_dropdown('start_month',$months, $selected_month, 'id="start_month"'); ?>
			<?php echo form_dropdown('start_day',$days, $selected_day, 'id="start_day"'); ?>
			<?php echo form_dropdown('start_year',$years, $selected_year, 'id="start_year"'); ?>
		</div>
	</div>
	
	</div>

	<?php 
	echo form_submit(array(
	'name'=>'submit',
	'id'=>'submit',
	'value'=>$this->lang->line('common_submit'),
	'class'=>'submit_button')
	);
	echo "</fieldset>";
	echo form_close();
	}
?>	

<table id="register">
<thead>
<tr>
<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
<th style="width:30%;"><?php echo $this->lang->line('sales_item_number'); ?></th>
<th style="width:30%;"><?php echo $this->lang->line('sales_item_name'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_price'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_quantity'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_discount'); ?></th>
<th style="width:15%;"><?php echo $this->lang->line('sales_total'); ?></th>
<th style="width:11%;"><?php echo $this->lang->line('sales_edit'); ?></th>
</tr>
</thead>
<tbody id="cart_contents">
<?php
if(count($cart)==0)
{
?>
<tr><td colspan='8'>
<div class='warning_message' style='padding:7px;'><?php echo $this->lang->line('sales_no_items_in_cart'); ?></div>
</tr></tr>
<?php
}
else
{
	foreach(array_reverse($cart, true) as $line=>$item)
	{
		$cur_item_info = $this->Item->get_info($item['item_id']);
		echo form_open("sales/edit_item/$line");
	?>
		<tr>
		<td><?php echo anchor("sales/delete_item/$line",'['.$this->lang->line('common_delete').']');?></td>
		<td><?php echo $item['item_number']; ?></td>
		<td style="align:center;"><?php echo $item['name']; ?></td>



		<?php if ($items_module_allowed)
		{
		?>
			<td><?php echo form_input(array('name'=>'price','value'=>$item['price'],'size'=>'6'));?></td>
		<?php
		}
		else
		{
		?>
			<td><?php echo $item['price']; ?></td>
			<?php echo form_hidden('price',$item['price']); ?>
		<?php
		}
		?>

		<td>
		<?php
        	if($item['is_serialized']==1)
        	{
        		echo $item['quantity'];
        		echo form_hidden('quantity',$item['quantity']);
        	}
        	else
        	{
        		echo form_input(array('name'=>'quantity','value'=>$item['quantity'],'size'=>'2'));
        	}
		?>
		</td>

		<td><?php echo form_input(array('name'=>'discount','value'=>$item['discount'],'size'=>'3'));?></td>
		<td><?php echo to_currency($item['price']*$item['quantity']-$item['price']*$item['quantity']*$item['discount']/100); ?></td>
		<td><?php echo form_submit("edit_item", $this->lang->line('sales_edit_item'));?></td>
		</tr>
		<tr>
		<td style="color:#2F4F4F";><?php echo $this->lang->line('sales_description_abbrv').':';?></td>
		<td colspan=2 style="text-align:left;">

		<?php
        	if($item['allow_alt_description']==1)
        	{
        		echo form_input(array('name'=>'description','value'=>$item['description'],'size'=>'20'));
        	}
        	else
        	{
				if ($item['description']!='')
				{
					echo $item['description'];
        			echo form_hidden('description',$item['description']);
        		}
        		else
        		{
        			echo 'None';
        			echo form_hidden('description','');
        		}
        	}
		?>
		</td>
		<td>&nbsp;</td>
		<td style="color:#2F4F4F";>
		<?php
        	if($item['is_serialized']==1)
        	{
				echo $this->lang->line('sales_serial').':';
			}
		?>
		</td>
		<td colspan=3 style="text-align:left;">
		<?php
        	if($item['is_serialized']==1)
        	{
        		echo form_input(array('name'=>'serialnumber','value'=>$item['serialnumber'],'size'=>'20'));
			}
			else
			{
				echo form_hidden('serialnumber', '');
			}
		?>
		</td>


		</tr>
		<tr style="height:3px">
		<td colspan=8 style="background-color:white"> </td>
		</tr>		</form>
	<?php
	}
}
?>
</tbody>
</table>
</div>


<div id="overall_sale">

	<?php echo form_open("sales/item_search_info",array('id'=>'search_info_form')); ?>
	<label id="search_item_info" for="search_info"><b><?php echo $this->lang->line('sales_search_info');?> </b></label>
	<?php   echo form_input(array('name'=>'search_info','id'=>'search_info','size'=>'30','value'=>$this->lang->line(
		 						  'sales_start_typing_search_info')));
			echo '</br></br>';
			if($item_number_searched)
			{
				echo '<b>'.$this->lang->line("sales_search_info_cost_price").': </b>'.$info_cost_price. '<br />';
				echo '<b>'.$this->lang->line("sales_search_info_unit_price").': </b>'.$info_unit_price. '<br />';
				echo '<b>'.$this->lang->line("sales_search_info_quantity").': </b>'.$info_quantity. '<br />';
				echo '<b>'.$this->lang->line("sales_search_info_location").': </b>'.$info_location. '<br /><br />';
			}
	?>
	</form>
		
	<?php
	if(isset($customer))
	{
		echo form_open("sales/detailed_customer_report",array('id'=>'detailed_customer_report_form', 'target' => '_blank', 'class' => 'new_window'));
		echo form_submit('detailed_customer_report', $this->lang->line('sales_detailed_customer_report'),array('class' => 'small_button', 'style' =>
		 				 'margin: 2px 3px 0px -3px;')); 
		?>
		   	
		</form>
		<?php 
		echo $this->lang->line("sales_customer").': <b>'.$customer. '</b><br />';
		echo anchor("sales/remove_customer",'['.$this->lang->line('common_remove').' '.$this->lang->line('customers_customer').']');
		echo form_open("sales/detailed_customer_report",array('id'=>'customer_details_form'));?> 
		<?php 
	}
	else
	{
		echo form_open("sales/select_customer",array('id'=>'select_customer_form')); ?>
		<label id="customer_label" for="customer"><?php echo $this->lang->line('sales_select_customer'); ?></label>
		<?php echo form_input(array('name'=>'customer','id'=>'customer','size'=>'30','value'=>$this->lang->line('sales_start_typing_customer_name')))
		;?>
		</form>
		<div style="margin-top:5px;text-align:center;">
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<div id="button" style="float:left; width:34px; height:34px;">
			<img id="helpCustomer" src="<?php echo base_url().'images/help.png' ?>" />
		</div>	
		<?php echo anchor("customers/view/-1/width:350",
		"<div class='small_button' style='margin:0 auto;'><span>".$this->lang->line('sales_new_customer')."</span></div>",
		array('class'=>'thickbox none','title'=>$this->lang->line('sales_new_customer')));
		?>
		</div>
		<div class="clearfix">&nbsp;</div>
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<?php
	}?>	

	<div id='sale_details'>
		<div class="float_left" style="width:55%;"><?php echo $this->lang->line('sales_sub_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($subtotal); ?></div>

		<?php foreach($taxes as $name=>$value) { ?>
		<div class="float_left" style='width:55%;'><?php echo $name; ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($value); ?></div>
		<?php }; ?>

		<div class="float_left" style='width:55%;'><?php echo $this->lang->line('sales_total'); ?>:</div>
		<div class="float_left" style="width:45%;font-weight:bold;"><?php echo to_currency($total); ?></div>
	</div>




	<?php
	// Only show this part if there are Items already in the sale.
	if(count($cart) > 0)
	{
	?>
	<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<div id="button" style="float:left; width:34px; height:34px;">
			<img id="helpCancelSale" src="<?php echo base_url().'images/help.png' ?>" />
		</div>
		
    	<div id="Cancel_sale">
		<?php echo form_open("sales/cancel_sale",array('id'=>'cancel_sale_form')); ?>
		<div class='small_button' id='cancel_sale_button' style='margin-top:5px;'>
			<span><?php echo $this->lang->line('sales_cancel_sale'); ?></span>
		</div>
    	</form>
    	</div>
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<div class="clearfix" style="margin-bottom:1px;">&nbsp;</div>
		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
			<div id="finish_sale">
				<?php echo form_open("sales/complete",array('id'=>'finish_sale_form')); ?>
				<label id="comment_label" for="comment"><?php echo $this->lang->line('common_comments'); ?>:</label>
				<?php echo form_textarea(array('name'=>'comment', 'id' => 'comment', 'value'=>$comment,'rows'=>'4','cols'=>'23'));?>
				<br /><br />
				
				<?php
				
				if(!empty($customer_email))
				{
					echo $this->lang->line('sales_email_receipt'). ': '. form_checkbox(array(
					    'name'        => 'email_receipt',
					    'id'          => 'email_receipt',
					    'value'       => '1',
					    'checked'     => (boolean)$email_receipt,
					    )).'<br />('.$customer_email.')<br />';
				}
				 
				if ($payments_cover_total)
				{	//echo  "<img id='helpFinish' src='".base_url()."'images/help.png' />";
					echo "<div class='small_button' id='finish_sale_button' style='float:left;margin-top:5px;'><span>".$this->lang->line('sales_complete_sale')."</span></div>";
				}
				//echo  "<img id='helpSuspend' src='".base_url()."'images/help.png' />";
				//echo "<div class='small_button' id='suspend_sale_button' style='float:right;margin-top:5px;'><span>".$this->lang->line('sales_suspend_sale')."</span></div>";
				?>
			</div>
			</form>
		<?php
		}
		?>



    <table width="100%"><tr>
    <td style="width:55%; "><div class="float_left"><?php echo 'Payments Total:' ?></div></td>
    <td style="width:45%; text-align:right;"><div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($payments_total); ?></div></td>
	</tr>
	<tr>
	<td style="width:55%; "><div class="float_left" ><?php echo 'Amount Due:' ?></div></td>
	<td style="width:45%; text-align:right; "><div class="float_left" style="text-align:right;font-weight:bold;"><?php echo to_currency($amount_due); ?></div></td>
	</tr></table>

	<div id="Payment_Types" >

		<div style="height:100px;">
			<?php echo form_open("sales/add_payment",array('id'=>'add_payment_form')); ?>
			<table width="100%">
			<tr>
			<td>
				<?php echo $this->lang->line('sales_payment').':   ';?>
			</td>
			<td>			
				<?php //echo form_open("sales/payment_types",array('id'=>'payment_types_form')); ?>
				<?php echo form_dropdown('payment_type',$payment_options, $this->lang->line('sales_cash'), 'id="payment_types"');?>
				<?php //echo form_close(); ?>
			</td>
			</tr>
			<tr>
			<td>
				<span id="amount_tendered_label"><?php echo $this->lang->line('sales_amount_tendered').': ';?></span>
			</td>
			<td>
				<?php 
					echo form_input(array('name'=>'amount_tendered','id'=>'amount_tendered','value'=>to_currency_no_money($amount_due),'size'=>'10'));	
					echo form_hidden('amount_due', to_currency_no_money($amount_due));
				?>
			</td>
			</tr>
        	</table>
			<div id="button" style="float:left; width:34px; height:34px;">
				<img id="helpPayment" src="<?php echo base_url().'images/help.png' ?>" />
			</div>	
			<div class='small_button' id='add_payment_button' style='float:left;margin-top:2px;'>
				<span><?php echo $this->lang->line('sales_add_payment'); ?></span>
			</div>
		</div>
		</form>

		<?php
		// Only show this part if there is at least one payment entered.
		if(count($payments) > 0)
		{
		?>
	    	<table id="register">
	    	<thead>
			<tr>
			<th style="width:11%;"><?php echo $this->lang->line('common_delete'); ?></th>
			<th style="width:60%;"><?php echo 'Type'; ?></th>
			<th style="width:18%;"><?php echo 'Amount'; ?></th>


			</tr>
			</thead>
			<tbody id="payment_contents">
			<?php
				foreach($payments as $payment_id=>$payment)
				{
				echo form_open("sales/edit_payment/$payment_id",array('id'=>'edit_payment_form'.$payment_id));
				?>
	            <tr>
	            <td><?php echo anchor("sales/delete_payment/$payment_id",'['.$this->lang->line('common_delete').']');?></td>


				<td><?php echo  $payment['payment_type']    ?> </td>
				<td style="text-align:right;"><?php echo  to_currency($payment['payment_amount'])  ?>  </td>


				</tr>
				</form>
				<?php
				}
				?>
			</tbody>
			</table>
		    <br />
		<?php
		}
		?>



	</div>

	<?php
	}
	?>
</div>
<div class="clearfix" style="margin-bottom:30px;">&nbsp;</div>


<?php $this->load->view("partial/footer"); ?>

<script type="text/javascript" language="javascript">
$(document).ready(function()
{

	$("#category").autocomplete("<?php echo site_url('items/suggest_category');?>",{max:100,minChars:0,delay:10});
    $("#category").result(function(event, data, formatted){});
	$("#category").search();

    $("#item,#search_info").autocomplete('<?php echo site_url("sales/item_search"); ?>',
    {
    	minChars:0,
    	max:100,
    	selectFirst: false,
       	delay:10,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#item").result(function(event, data, formatted)
    {
		$("#add_item_form").submit();
    });

	$('#item').focus();

	$('#item').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
    });

	$('#item,#customer,#search_info').click(function()
    {
    	$(this).attr('value','');
    });

    $("#customer").autocomplete('<?php echo site_url("sales/customer_search"); ?>',
    {
    	minChars:0,
    	delay:10,
    	max:100,
    	formatItem: function(row) {
			return row[1];
		}
    });

    $("#customer").result(function(event, data, formatted)
    {
		$("#select_customer_form").submit();
    });

    $('#customer').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_customer_name'); ?>");
    });

//-----------------------------------------------------------

    $("#search_info").result(function(event, data, formatted)
    {
		$("#search_info_form").submit();
    });

    $('#search_info').blur(function()
    {
    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_search_info'); ?>");
    });
	
	
    $("#item_number").result(function(event, data, formatted)
    {
		$("#select_item_number_form").submit();
    });

	//-----------------------------
	

	
	$('#comment').change(function() 
	{
		$.post('<?php echo site_url("sales/set_comment");?>', {comment: $('#comment').val()});
	});
	
	$('#email_receipt').change(function() 
	{
		$.post('<?php echo site_url("sales/set_email_receipt");?>', {email_receipt: $('#email_receipt').is(':checked') ? '1' : '0'});
	});
	
	
    $("#finish_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_finish_sale"); ?>'))
    	{
    		$('#finish_sale_form').submit();
    	}
    });

	$("#suspend_sale_button").click(function()
	{
		if (confirm('<?php echo $this->lang->line("sales_confirm_suspend_sale"); ?>'))
    	{
			$('#finish_sale_form').attr('action', '<?php echo site_url("sales/suspend"); ?>');
    		$('#finish_sale_form').submit();
    	}
	});

    $("#cancel_sale_button").click(function()
    {
    	if (confirm('<?php echo $this->lang->line("sales_confirm_cancel_sale"); ?>'))
    	{
    		$('#cancel_sale_form').submit();
    	}
    });

	$("#add_payment_button").click(function()
	{
	   $('#add_payment_form').submit();
    });

	$("#payment_types").change(checkPaymentTypeGiftcard).ready(checkPaymentTypeGiftcard)
});

function post_item_form_submit(response)
{
	if(response.success)
	{
		$("#item").attr("value",response.item_id);
		$("#add_item_form").submit();
	}
}

function post_person_form_submit(response)
{
	if(response.success)
	{
		$("#customer").attr("value",response.person_id);
		$("#select_customer_form").submit();
	}
}

function checkPaymentTypeGiftcard()
{
	if ($("#payment_types").val() == "<?php echo $this->lang->line('sales_giftcard'); ?>")
	{
		//$("#payment_types_form").submit();
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_giftcard_number'); ?>");
		$("#amount_tendered").val('');
		$("#amount_tendered").focus();
	}
	else
	{
		$("#amount_tendered_label").html("<?php echo $this->lang->line('sales_amount_tendered'); ?>");		
	}
}

</script>

<script type="text/javascript">
$(function()
{
    $('#helpMode').qtip(
    {
     content:'Select "Buy" to purchase a used item. \n Select "Sale" to sell a new item. \n Select "Return" to  register a returned item.',
     style: {name: 'dark', tip: 'topLeft'}
    });
});
$(function()
{
    $('#helpAddItem').qtip(
    {
     content:'If the item does not exist, add the information of a new item to inventory.',
     style: {name: 'dark', tip: 'topLeft'}
    });
});
$(function()
{
    $('#helpFindItem').qtip(
    {
     content:'Find an item that is in inventory.',
     style: {name: 'dark', tip: 'topLeft'}
    });
});
$(function()
{
    $('#helpCustomer').qtip(
    {
     content:'If the customer does not exist, add a new one.',
     style: {name: 'dark', tip: 'topLeft'}
    });
});
$(function()
{
    $('#helpCancelSale').qtip(
    {
     content:'If you want cancel this transaction, press this button.',
     style: {name: 'dark', tip: 'topLeft'}
    });
});
$(function()
{
    $('#helpPayment').qtip(
    {
     content:'To do',
     style: {name: 'dark', tip: 'topLeft'}
    });
});


</script>