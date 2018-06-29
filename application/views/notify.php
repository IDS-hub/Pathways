<?php
//print_r($_SESSION);
if(isset($detl)){
	$detl = json_decode($detl);
?>
<script>
var isApiCall = true;
$(document).ready(function(){
	$('.notify-more').bind('click',function(){
		if(isApiCall){
			isApiCall = false;
			var notify_page = parseInt($('.notify_page').val());
			var dataString = 'page='+notify_page;
			$.ajax({
				type: "POST",
		        url: "<?=$this->config->item('base_url')?>Notification/notificationList",
				data: dataString,
		        success: function(res){
					//alert(res);
					var fst_jsn = JSON.parse(res);
					var errorcode = fst_jsn.errorcode;
					if(errorcode!=0){
						$('.notify-more').hide();
					}
					var dataString1 = 'res=' + res;
					$.ajax({
						type: "POST",
				        url: "<?=$this->config->item('base_url')?>ajaxNotificationMsg.php",
						data: dataString1,
				        success: function(res1){
							isApiCall = true;
							$('.table-striped').find('tbody').append(res1);
							$('.notify_page').val(notify_page+1);
						}
					});
				}
			});
		}else{
			console.log('ok');
		}

	});
});
</script>

<div class="row table-Holder">
	<div class="col m8 offset-m2 s10 offset-s1">
		<div class="row">
			<a class="waves-effect waves-teal btn-flat btn-info btn-visu-back" href="<?php echo $_SERVER['HTTP_REFERER'];?>">Back</a>
		</div>
	</div>
	<div class="col m8 offset-m2 s10 offset-s1"><div class="row"><h4>NOTIFICATION</h4></div></div>
    <div class="col m8 offset-m2 s10 offset-s1">
    	<div class="row">
		    <table class="table table-striped notify-block">
		        <thead>
		            <th>Date</th>
		            <th>Time</th>
		            <th>Title</th>
		            <th>Message</th>
		        </thead>
		        <tbody>
				    <?php
					if(isset($detl->res) && $detl->errorcode!=1){
					    foreach($detl->res as  $val){ ?>
					        <tr>
					            <td data-label="Date"><?php echo $val->date;?></td>
					            <td data-label="Time"><?php echo $val->time;?></td>
					            <td data-label="Title"><?php echo $val->title;?></td>
					            <td data-label="Message"><?php echo $val->message;?></td>
					        </tr>
					    <?php } ?>
					<?php //}else{ ?>

						 <!-- <tr><td colspan="4">No Record Found</td></tr> -->

					<?php }?>
		    	</tbody>
		    </table>
        </div>
		<?php if(isset($detl->res) && $detl->errorcode!=1){ ?>
			<?php //if(count($detl->res)>10){?>
			<a id="notify-more" class="notify-more right" href="javascript:void(0);">More</a>
			<?php //} ?>
		<?php } ?>

        <div class="row btn-Holder">

        </div>
    </div>
</div>
<?php	} ?>
<input type="hidden" name="notify_page" class="notify_page" value="<?=(isset($notify_page) && $notify_page!='')?$notify_page:2;?>" />
