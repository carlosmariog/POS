<?php $this->load->view("partial/header"); ?>

<br />
<div id="title"><?php echo $this->lang->line('common_list_of').' '.'Desired Items Arrived' ?></div>
<div id="table_action_header">
	<ul>
		<li class="float_left"><span><?php echo anchor("home/delete",$this->lang->line("common_delete"),array('id'=>'delete')); ?></span></li>
	</ul>
</div>

<?php echo form_open(home/delete)?>
<div id="table_holder">
	<table class="tablesorter report" id="sortable_table">
		<thead>
			<tr>
				<?php foreach ($headers as $header) { ?>
				<th><?php echo $header; ?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($customers as $key=>$row) {
				  $i=1; ?>
			<tr>
				<?php foreach ($row as $cell) { ?>
				<?php 
					if($i==1){
						$data = array('name' => 'customers', 'id' => 'customers', 'value' => ,$cell 'checked' => FALSE, 'style' => 'margin:10px',);
						echo form_checkbox($data);
						i++;
					}
				?>
				<td><?php echo $cell; ?></td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<?php $this->load->view("partial/footer"); ?>