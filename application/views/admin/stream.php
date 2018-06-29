
     <div class="content">

	   <div>
      	<ol class="breadcrumb">
			<li>Stream List<?php //echo $mainpagename1; ?></li>
		</ol>
	</div>
  		<div class="whiteBg clearfix">




<!-- Content -->

<style>
	.menuList{padding:0px; margin:0px; list-style-type:none;}
	/*.menuList li{display:inline-block;}*/
	/*.menuList li select{padding:0px 11px !important; height:29px !important; min-height:29px; line-height:20px;}*/
</style>
<div class="row clearfix">
	<div class="col-md-3">
<?php
if($this->uri->segment(4)!=''){
	$featured = $this->uri->segment(4);
}else{
	$featured = $this->input->post('featured');
}
?>
</div>
	<div class="col-md-9">
		<ul class='menuList row'>
		  <li class="col-md-4">
		<form role="form" class="form-inline m-b-5" method="post" action="<?php $this->config->item('base_url').'stream/index';?>" id="searchfrm">
			<input name="frmsubmit" type="hidden" value="YES">
			<input name="clearval" id="clearval" type="hidden" value="">

			  <div class="form-group" style="width: 100%;">
				<div class="input-group inside no-boarder">
				  <input type="checkbox" name="featured" id="featured"
				  <?=(isset($featured) && $featured==1)?'checked':''?>
				   value="<?php echo $featured;?>" class="checkbox featured"/> Show Featured Stream
				</div>
			  </div>
		  </form>
	  </li>
	  <li class="col-md-2" style="vertical-align:top;">
		<form role="form2" id="form2" action="" method="post">
			<div class="form-group drop-icon" style="width: 100%;">
		    <?php
				$type[10] = 10;
				$type[25] = 25;
				$type[50] = 50;
				$type[100] = 100;
				$type[200] = 200;
				echo form_dropdown('type', $type, $this->session->userdata('sv_streampageid'), "id='type' class='pageTyp form-control' onchange='myPagination(this.value);' style='background:#eeeeee;'");
			?>
			</div>
		</form>
		<li>
	</ul>

	</div>
</div>

<div class="table-responsive">

        <table class="table table-condensed table-striped border-top margin-none">
            <thead class="bg-gray borderRadius_3">
                <tr>
                    <th class="center"><?php //echo form_checkbox($check_all_attr); ?></th>
                    <th>#</th>
                    <th>User Name</th>
					<th>View Cnt</th>
					<th>Fan Cnt</th>
					<th>Created Date & Time</th>
					<th class="right-text">Is Featured?</th>
                </tr>
            </thead>
            <tbody>

<?php
//echo form_open($this->config->item('base_url').'users/delete', $emailtmp_delete_frm_attribute);

	$edit_image=array('src' => $this->config->item('base_url').'public/images/icon_edit.png','alt' => 'Edit','title'=>'Edit');
	$delete_image=array('src' => $this->config->item('base_url').'public/images/icon_delete.png','alt' => 'Delete','title'=>'Delete');
	if(count($tmpdetails) > 0){
		$i=$pg_start;
		foreach($tmpdetails as $tmpdetails1){?>
				<!-- Table row -->
                <tr>
                    <td class="center"><?php //echo form_checkbox($check_attr); ?></td>
                    <td><?php echo $i; ?></td>
                    <td><?=json_decode('"'.$tmpdetails1['created_by_user_id'].'"');?></td>
					<!-- <td><?php $tok_sess_id = $tmpdetails1['tokbox_session_id'] ;  echo $tok_sess_id = (strlen($tok_sess_id) > 30) ? substr($tok_sess_id,0,15).'...' : $tok_sess_id;?></td>
					<td><?php $string = $tmpdetails1['stream_url']; //echo $string = (strlen($string) > 30) ? substr($string,0,20).'...' : $string; ?>
						<?php if(isset($string) && $string!=''){ ?>
							<a href="<?=$string?>" target="_blank">Stream Url</a>
							&nbsp;<a href="<?php echo $this->config->item('base_url').'admin/stream/removeurl/'.$tmpdetails1['id'].'/'.$this->uri->segment(4); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
						<?php }?>
					</td> -->
					<td><?php echo $tmpdetails1['viewer'];?></td>
					<td><?php echo $tmpdetails1['fans'];?></td>
					<td><?php echo date("Y-m-d H:i:s", strtotime($tmpdetails1['created'])); ?></td>
					<td><?php echo(($tmpdetails1['is_featured']==1)?'Yes':'No'); ?>
						<?php //$string = $tmpdetails1['stream_url'];?>
						<!-- <?php if(isset($string) && $string!=''){ ?>
							&nbsp;<a href="<?php echo $this->config->item('base_url').'admin/stream/removeurl/'.$tmpdetails1['id'].'/'.$this->uri->segment(4); ?>"><i class="fa fa-times" aria-hidden="true"></i></a>
						<?php }?> -->
					</td>
                </tr>
                <!--Table row END -->
<?php
		$i++;
		}
	}else{
?>
				<tr><td colspan="6">No details found</td></tr>
<?php
	}
//echo form_close();
?>
            </tbody>
        <!-- // Table body END -->
      </table>

      </div>
<ul class="pagination pull-right">
<?php echo $pagination; ?>
</ul>
<script>
$(function(){
     $('.checkbox').on('change',function(){
		var chk = $("#featured").is(":checked");
		var val = 0;
		if(chk==true){
			val =1;
		}else if(chk==false){
			val =0;
		}
		window.location.href = '<?php echo $action;?>/'+val;
    });
});
</script>

<script src="<?=$this->config->item('base_url')?>public/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script>
$(document).ready(function(){
	$(".popup_box #toggle").each(function(e){
		//alert("Hi");
		$(this).click(function(){
			$(".target").each(function(i){
				if(e==i){
				$(this).toggle( "slide",
				{ direction: "right" }, 'slow' );
				}else{
				$(this).css({"display":"none"});
				}
			});
		 });
	   });
});
</script>

<script>
function myPagination(getval){
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('base_url').'admin/stream/setpagination';?>",
		cache: false,
		data: 'pageid='+getval,
		datatype: "text",
		success: function(data){
			//$('#form2').submit();
			var val = $(".featured").val();
			//var featured = ((val=='') && (val==0))?0:1;
			//alert(featured);
			window.location.href = '<?php echo $action;?>/'+val+'/';
		}
  	});
}
</script>

<!-- Content -->

				</div>
			</div>
