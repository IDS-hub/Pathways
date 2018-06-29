<?php
if(isset($detl)){
	$detl = json_decode($detl);
	if(is_object($detl->res)){ //is_array
?>
<table width="300">
	<tr>
		<th>Amount</th>
		<th>Created</th>
	</tr>
<?php
foreach($detl->res as  $val){ ?>
	<tr>
		<td><?php echo $val->amount;?></td>
		<td><?php echo $val->created;?></td>
	</tr>
<?php } ?>
</table>
<?php
}else{
	echo "<br>No Record Found.";
}
}?>
