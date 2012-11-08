<div id="config_wrapper">
<fieldset id="config_info">
<legend><?php echo $this->lang->line("config_info"); ?></legend>

<?php echo form_open('config/newWorkstation/',array('id'=>'config_form'));?>

<div class="field_row clearfix">	
	<?php  echo form_label($this->lang->line('config_address').':', 'address_id',array('class'=>'wide required')); ?>
	<div class='form_field'>
	<?php 
	 echo form_dropdown(
		 'address_id', 
		 $adresses);
	?>
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