<?php $this->load->view("partial/header"); ?>
<?php
echo form_open('repairs/save/');
?>
<fieldset id="item_basic_info">
<legend><?php echo $this->lang->line("repair_basic_information"); ?></legend>

<div id="leftCol" style="width:50%; float:left;">

<div class="field_row clearfix">
		<?php 
			  echo form_label($this->lang->line('items_customer').':', 'customer',array('class'=>'required wide')); 
 			  echo form_dropdown(
	 						'customer_id', 
	 						$customers
							);
		?>
</div>

<div class="field_row clearfix">
	<?php 	
			  echo form_label($this->lang->line('repair_employee').':', 'employee',array('class'=>'required wide')); 
  	  		  echo form_dropdown(
		 						'employee_id', 
		 						$employees);	
	?>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('repair_item_number').':', 'name',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'repair_item_number',
		'id'=>'repair_item_number',
		'value'=>'')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('equipment_type').':', 'equipment',array('class'=>'required wide')); ?>
	<div class='form_field'>
		<?php echo form_dropdown('equipment',
		array('portable_pc' => $this->lang->line('portable_pc'), 
			  'desktop_pc' => $this->lang->line('desktop_pc'),
			  'cell_phone' => $this->lang->line('cell_phone'))); 
		?>	  
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('defect_type').':', 'defect_type',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'defect_type',
		'id'=>'defect_type',
		'value'=>'')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('repair_cost_price').':', 'repair_price',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'repair_price',
		'id'=>'repair_price',
		'value'=>'')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('repair_notes').':', 'notes',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'notes',
		'id'=>'notes',
		'value'=>'',
		'rows'=>'5',
		'cols'=>'17')
	);?>
	</div>
</div>

	<div class="field_row clearfix">
	<?php echo form_label($this->lang->line('repair_password').':', 'password',array('class'=>'required wide')); ?>
		<div class='form_field'>
		<?php echo form_input(array(
			'name'=>'password',
			'id'=>'password',
			'value'=>'')
		);?>
		</div>
	</div>
</div>

<div id="rightCol" style="width:50%; float:right;">

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('repair_flaws').':', 'flaws',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_textarea(array(
		'name'=>'flaws',
		'id'=>'flaws',
		'value'=>'',
		'rows'=>'5',
		'cols'=>'17')
	);?>
	</div>
</div>

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

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('repair_deposit').':', 'deposit',array('class'=>'required wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'deposit',
		'id'=>'deposit',
		'value'=>'')
	);?>
	</div>
</div>

<div class="field_row clearfix">
<?php echo form_label($this->lang->line('delivery_date').':', 'delivery',array('class'=>'required wide')); ?>
	<div class='form_field'>
		<?php echo form_dropdown('start_month',$months, $selected_month, 'id="start_month"'); ?>
		<?php echo form_dropdown('start_day',$days, $selected_day, 'id="start_day"'); ?>
		<?php echo form_dropdown('start_year',$years, $selected_year, 'id="start_year"'); ?>
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
</fieldset>
<?php
echo form_close();
?>

</div>


<script type='text/javascript'>

//validation and submit handling
$(document).ready(function()
{
	$("#category").autocomplete("<?php echo site_url('items/suggest_category');?>",{max:100,minChars:0,delay:10});
    $("#category").result(function(event, data, formatted){});
	$("#category").search();

	$('#item_form').validate({
		submitHandler:function(form)
		{
			/*
			make sure the hidden field #item_number gets set
			to the visible scan_item_number value
			*/
			$('#item_number').val($('#scan_item_number').val());
			$(form).ajaxSubmit({
			success:function(response)
			{
				tb_remove();
				post_item_form_submit(response);
			},
			dataType:'json'
		});

		},
		errorLabelContainer: "#error_message_box",
 		wrapper: "li",
		rules:
		{
			name:"required",
			customer:"required",
			category:"required",
			cost_price:
			{
				required:true,
				number:true
			},

			unit_price:
			{
				required:true,
				number:true
			},
			tax_percent:
			{
				required:true,
				number:true
			},
			quantity:
			{
				required:true,
				number:true
			},
			reorder_level:
			{
				required:true,
				number:true
			}
   		},
		messages:
		{
			name:"<?php echo $this->lang->line('items_name_required'); ?>",
			category:"<?php echo $this->lang->line('items_category_required'); ?>",
			cost_price:
			{
				required:"<?php echo $this->lang->line('items_cost_price_required'); ?>",
				number:"<?php echo $this->lang->line('items_cost_price_number'); ?>"
			},
			unit_price:
			{
				required:"<?php echo $this->lang->line('items_unit_price_required'); ?>",
				number:"<?php echo $this->lang->line('items_unit_price_number'); ?>"
			},
			tax_percent:
			{
				required:"<?php echo $this->lang->line('items_tax_percent_required'); ?>",
				number:"<?php echo $this->lang->line('items_tax_percent_number'); ?>"
			},
			quantity:
			{
				required:"<?php echo $this->lang->line('items_quantity_required'); ?>",
				number:"<?php echo $this->lang->line('items_quantity_number'); ?>"
			},
			reorder_level:
			{
				required:"<?php echo $this->lang->line('items_reorder_level_required'); ?>",
				number:"<?php echo $this->lang->line('items_reorder_level_number'); ?>"
			}

		}
	});
});


