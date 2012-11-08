<?php 
$this->load->view("partial/header");
?>
<div id="page_title" style="margin-bottom:8px;"><?php echo $title ?></div>
<div id="page_subtitle" style="margin-bottom:8px;"><?php echo $subtitle ?></div>
<div id="table_holder">
	<table class="tablesorter report" id="sortable_table">
		<thead>
			<tr>
				<th>+</th>
				<?php foreach ($headers['summary'] as $header) { ?>
				<th><?php echo $header; ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($summary_data as $key=>$row) { ?>
			<tr>
				<td><a href="#" class="expand">+</a></td>
				<?php foreach ($row as $cell) { ?>
				<td><?php echo $cell; ?></td>
				<?php } ?>
			</tr>
			<tr>
				<td colspan="100">
				<table class="innertable">
					<thead>
						<tr>
							<?php foreach ($headers['details'] as $header) { ?>
							<th><?php echo $header; ?></th>
							<?php } ?>
						</tr>
					</thead>
				
					<tbody>
						<?php foreach ($details_data[$key] as $row2) { ?>
						
							<tr>
								<?php foreach ($row2 as $cell) { ?>
								<td><?php echo $cell; ?></td>
								<?php } ?>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<?php
echo form_open('home/deposit/',array('id'=>'deposit_form'));
?>
<div class="field_row clearfix">
<?php echo form_label($this->lang->line('reports_cash_deposit').':', 'cash_deposit',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'cash_deposit',
		'id'=>'cash_deposit',
		'value'=>'')
	);?>
	</div>
</div>
<div class="field_row clearfix">
<?php echo form_label($this->lang->line('reports_check_deposit').':', 'check_deposit',array('class'=>'wide')); ?>
	<div class='form_field'>
	<?php echo form_input(array(
		'name'=>'check_deposit',
		'id'=>'check_deposit',
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
</fieldset>
<?php
echo form_close();
?>

<!--<div id="report_summary">
<?php //foreach($overall_summary_data as $name=>$value) { ?>
	<div class="summary_row"><?php //echo $this->lang->line('reports_'.$name). ': '.to_currency($value); ?></div>
<?php //}?>
</div>-->
<?php 
$this->load->view("partial/footer"); 
?>
<script type="text/javascript" language="javascript">
$(document).ready(function()
{
	$(".tablesorter a.expand").click(function(event)
	{
		$(event.target).parent().parent().next().find('.innertable').toggle();
		
		if ($(event.target).text() == '+')
		{
			$(event.target).text('-');
		}
		else
		{
			$(event.target).text('+');
		}
		return false;
	});
	
});
</script>