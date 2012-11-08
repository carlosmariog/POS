<?php 
$a = 2;
function foo()
{
    $a += 3;
	return $a;
}
foo();
echo $a;
?>