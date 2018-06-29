<?php
//echo '<pre>';
$vpf = json_decode($vpf);
//print_r($vpf->res);
$prof = $vpf->res;
?>
<br>
<span class="edit">Edit</span>
<span class="cancel">Cancel</span>
<br>

<div class="profileview">
Name : <?php echo $prof->first_name.' '.$prof->last_name;?><br>
Profile Image : <?php if(isset($prof->profile_image) && $prof->profile_image!=''){ ?><img src="<?=isset($prof->profile_image)?$prof->profile_image:'';?>"
	width="100"/><?php } ?><br>
Cover Image : <?php if(isset($prof->cover_image) && $prof->cover_image!=''){ ?><img src="<?=isset($prof->cover_image)?$prof->cover_image:'';?>"
	width="100"/><?php } ?><br>
</div>

<div class="profilefrm">
<form action="<?= base_url() ?>profile/update" method="post" accept-charset="utf-8" name="profilefrm" id="profilefrm" enctype="multipart/form-data">
Name : <input type="text" name="profile_name" value="<?php echo $prof->first_name.' '.$prof->last_name;?>"/><br>

Profile Image <input type="file" name="profile_image"><?php if(isset($prof->profile_image) && $prof->profile_image!=''){ ?><img src="<?=isset($prof->profile_image)?$prof->profile_image:'';?>"
	width="100"/><?php } ?><br>
Cover Image <input type="file" name="cover_image"><?php if(isset($prof->cover_image) && $prof->cover_image!=''){ ?><img src="<?=isset($prof->cover_image)?$prof->cover_image:'';?>"
	width="100"/><?php } ?><br>
<input type="submit" name="upd" value="Update"/>
</form>
</div>
<br>
Following : <?php echo (isset($prof->following)?$prof->following:'');?><br>
Follower : <?php echo (isset($prof->follower)?$prof->follower:'');?><br>
Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?><br>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
	$('.profilefrm').hide();
	$('.cancel').hide();
	$('.edit').click(function(){
		$('.profilefrm').show();
		$('.cancel').show();
		$('.profileview').hide();
		$('.edit').hide();
	});
	$('.cancel').click(function(){
		$('.edit').show();
		$('.profileview').show();
		$('.profilefrm').hide();
		$('.cancel').hide();
	});
});
</script>

<!--Mobile No : <?php echo (isset($prof->mob_no)?$prof->mob_no:'');?><br>
Coin Earned : <?php echo $prof->coins_earned;?><br>
Coin Spent : <?php echo $prof->coins_spent;?><br>
Coin Withdrawn : <?php echo $prof->coins_withdrawn;?><br>
Following : <?php echo (isset($prof->following)?$prof->following:'');?><br>
Follower : <?php echo (isset($prof->follower)?$prof->follower:'');?><br>
Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?><br>-->
