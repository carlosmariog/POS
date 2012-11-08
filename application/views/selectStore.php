<?php $this->load->view("partial/header"); ?>
<br />
<h3><?php echo $this->lang->line('common_select_store'); ?></h3>
<div class="field_row clearfix">	

<?php echo form_open('home/selectStore/',array('id'=>'config_form'));?>

<?php echo form_label($this->lang->line('config_address').':', 'address_id',array('class'=>'wide required')); ?>
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

<?php $this->load->view("partial/footer"); ?>