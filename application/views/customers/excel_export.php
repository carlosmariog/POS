<?php 
//OJB: Check if for excel export process
	ob_start();
	$this->load->view("partial/header_excel");
?>
<div id="table_holder">
	<?php echo $manage_table; ?>
</div>
<?php 
	$filename = '';
	$this->load->view("partial/footer_excel");
	$content = ob_end_flush();
	$filename .= "Customers_Export.xls";
	header('Content-type: application/ms-excel');
	header('Content-Disposition: attachment; filename='.$filename);
	echo $content;
	die();
?>