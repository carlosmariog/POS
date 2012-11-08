<?php $this->load->view("partial/header"); ?>

<br />
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
				<td>
				<?php 
					if($i==1){
						echo form_open("home/delete/".$cell);
						$data = array(
              					'desired_items'  => $customers[$cell]['desired_items'],
              				);
						echo form_hidden($data);
						echo form_submit('sendelete', 'Called & Delete'); 
						echo form_close();
						$i++;
					}else{
						echo $cell;
					}
				?>
				</td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div>

<br>

<?php $this->load->view("partial/footer"); ?>