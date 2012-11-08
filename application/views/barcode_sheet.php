<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
        "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php echo $this->lang->line('items_generate_barcodes'); ?></title>
</head>
<body>
<table align='center' cellpadding='20'>
<tr>
<?php 
$count = 0;
foreach($items as $item)
{
	$i = 0;
	while($i<$item['barcodes_to_generate']){
		
		if(!$item['kit']){
			$barcode = $item['id'];
			$text = $item['item_name']." ".$item['item_number']."  $".$item['unit_price'];		
		}else{
			$barcode = $item['id'];
			$text = $item['id'];
		}
		
		
		//if ($count % 2 ==0 and $count!=0)
		//{
			echo '</tr><tr>';
		//}
		echo "<td><img src='".site_url()."/barcode?barcode=$barcode&text=$text&width=256' /></td>";
		$i++;
	}
	$count++;
}
?>
</tr>
</table>
</body>
</html>
