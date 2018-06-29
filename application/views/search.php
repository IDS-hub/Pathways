<script>
function check(){
	var search = document.getElementById('search').value;
	search  = search.toString().trim();
	if(search==''){
		alert('Please enter any keyword');
		return false;
	}
	return true;
}
function follow_unfollow_update(id,fval){
	//alert(id+' '+fval);
	var search = $('#search').val();
	if(fval=='0'){
		var dataString = 'follower_id='+id;
		$.ajax({
			url: "<?=$this->config->item('base_url')?>profile/unFollow",
			type: "POST",
			data: dataString,
			success: function (res){
				var addfol_jsn = JSON.parse(res);
				alert(addfol_jsn.message);
			}
		});
	}else{
		var dataString = 'follower_id='+id;
		$.ajax({
			url: "<?=$this->config->item('base_url')?>profile/addFollower",
			type: "POST",
			data: dataString,
			success: function (res){
				var unfol_jsn = JSON.parse(res);
				alert(unfol_jsn.message);
			}
		});
	}
	window.location.reload();
}
</script>
<?php //print_r($_REQUEST['search']);?>
<form class="search-form" action="" method="post" onsubmit="return check();">
	<div class="contener">
		<div class="row">
			<div class="col s8 offset-s2">
				<a class="btn" href="<?php echo $this->config->item('base_url').'home';?>">Back</a>
			</div>
			<div class="col s8 offset-s2">
				<input type="text" name="search" id="search" placeholder="search text" value="<?=(isset($_REQUEST['search']) && $_REQUEST['search']!='')?$_REQUEST['search']:''?>"/>
			</div>
		</div>
	</div>
<!-- <input type="submit" name="sub" value="Search"/> -->
</form>

<?php
if(isset($detl)){
	$detl = json_decode($detl);
	//print_r($detl->res);
	if(isset($detl->res) && $detl->errorcode!=1){ //is_object
?>

<div class="contener">
	<div class="row">
		<div class="col s8 offset-s2">
			<table class="table table-striped search">
				<thead>
					<th></th>
					<th></th>
				</thead>
	            <tbody>
			<?php foreach($detl->res as  $val){ ?>

				<tr>
					<td><img src="<?=($val->profile_image)?$val->profile_image:$this->config->item('base_url').'uploads/user/no-image.png';?>"  width="30px" height="30px"/>
					<?php echo json_decode('"'.$val->first_name.' '.$val->last_name.'"');?><?php //echo $val->first_name.' '.$val->last_name;?></td>
					<td>
						<a class="btn pull-right <?=($val->is_follow==0)?'btn-Follow':'btn-Unfollow';?>" href="javascript:follow_unfollow_update(<?php echo $val->id;?>,<?php echo ($val->is_follow==0)?1:0;?>);"><?php echo ($val->is_follow==0)?'Follow':'Unfollow';?></a>
					</td>
				</tr>
			<?php } ?>
			</tbody>
	        </table>
		</div>
		<?php }else if($detl->errorcode!=0){ ?>
		<div class="col s8 offset-s2">
			<h5 class="no-record center">No Record Found.</h5>
		</div>
	<?php
	} ?>
	</div>
</div>
<?php }
 ?>
