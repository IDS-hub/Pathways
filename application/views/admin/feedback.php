
     <div class="content">

	   <div>
      	<ol class="breadcrumb">
			<li>Feedback List<?php //echo $mainpagename1; ?></li>
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
/*if($this->uri->segment(4)!=''){
	$featured = $this->uri->segment(4);
}else{
	$featured = $this->input->post('featured');
}*/
?>
</div>
	<div class="col-md-9">
		<ul class='menuList row'>
		  <li class="col-md-2">
		<form role="form" class="form-inline m-b-5" method="post" action="<?php $this->config->item('base_url').'feedback/index';?>" id="searchfrm">
			<input name="frmsubmit" type="hidden" value="YES">
			<input name="clearval" id="clearval" type="hidden" value="">

			<div class="form-group" style="width: 100%;">
				<select class="selVal" id="selVal" name="selVal" style="vertical-align:top;">
					<option value="0" <?=(isset($selVal) && $selVal==0)?'selected':''?>>Open</option>
					<option value="1" <?=(isset($selVal) && $selVal==1)?'selected':''?>>Close</option>
				</select>
			</div>
		</li>
		<li class="col-md-4">
			<div class="form-group" style="width: 100%;">
			  <div class="input-group inside no-boarder" style="vertical-align:top;">
				<div class="input-group-addon add-on no-boarder"><i class="fa fa-search" style="color:#212C32;"></i></div>
				<input name="txtSearch" id="txtSearch" type="text" data-toggle="tooltip" data-placement="top" title="Search By : User Name, Email" class="form-control no-boarder white-tooltip" placeholder="Search by User Name or EMAIL" value="<?php echo ($this->input->post('txtSearch')!='')?$txtSearch:''; ?>">
				<a href="javascript:void(0);" onclick="deleteSession();" class="input-group-addon add-on no-boarder"><i class="fa fa-times-circle" style="color:#212C32;"></i></a>
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
				echo form_dropdown('type', $type, $this->session->userdata('sv_feedbackpageid'), "id='type' class='pageTyp form-control' onchange='myPagination(this.value);' style='background:#eeeeee;'");
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
					<th>Date</td>
                    <th>User</th>
					<th>Type</th>
					<th>Email</th>
					<th width="300px">Description</th>
					<th>Image1</th>
					<th>Image2</th>
					<th>Image3</th>
					<!-- <th>Status</th> -->
					<th class="right-text">Action</th>
                </tr>
            </thead>
            <tbody>

<?php
//echo form_open($this->config->item('base_url').'users/delete', $emailtmp_delete_frm_attribute);

	$edit_image=array('src' => $this->config->item('base_url').'public/images/icon_edit.png','alt' => 'Edit','title'=>'Edit');
	$delete_image=array('src' => $this->config->item('base_url').'public/images/icon_delete.png','alt' => 'Delete','title'=>'Delete');
	if(count($tmpdetails) > 0){
		$i=$pg_start;
		foreach($tmpdetails as $tmpdetails1){
?>
				<!-- Table row -->
                <tr>
                    <td class="center"><?php //echo form_checkbox($check_attr); ?></td>
					<td><?php echo $i; ?></td>
					<td><?php echo date("Y-m-d", strtotime($tmpdetails1['created'])); ?></td>
					<td><?=json_decode('"'.$tmpdetails1['user'].'"');?><?php //echo $tmpdetails1['user'] ; ?></td>
					<td><?php echo $tmpdetails1['type'] ; ?></td>
					<td><?php echo $tmpdetails1['email'] ; ?></td>
					<td><?php echo $tmpdetails1['desc'] ; ?></td>
					<td><?php echo ($tmpdetails1['fdbk_image1']!='')?"<img src=".$this->config->item('base_url').'uploads/feedback/'.$tmpdetails1['fdbk_image1']." width=60>":'';?></td>
					<td><?php echo ($tmpdetails1['fdbk_image2']!='')?"<img src=".$this->config->item('base_url').'uploads/feedback/'.$tmpdetails1['fdbk_image2']." width=60>":'';?></td>
					<td><?php echo ($tmpdetails1['fdbk_image3']!='')?"<img src=".$this->config->item('base_url').'uploads/feedback/'.$tmpdetails1['fdbk_image3']." width=60>":'';?></td>
					<!-- <td><?=(($tmpdetails1['status']==0)?'Active':'Inactive')?></td> -->
					<td><a href="<?php echo $this->config->item('base_url').'admin/feedback/changeStatus/'.$tmpdetails1['id']; ?>"><?=(($tmpdetails1['status']!=0)?'Close':'Open')?></a></td>



                </tr>
                <!--Table row END -->
<?php
		$i++;
		}
	}else{
?>
				<tr><td colspan="10">No details found</td></tr>
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
function deleteSession(){
	//$('#clearval').val('clear');
	//$('#txtSearch').val('');
	//$('#selVal').val();
	//var val = 0;
	//die();

	var val = $(".selVal").val();
	window.location.href = '<?php echo $action;?>/'+val;
}
$(function(){
	$('#txtSearch').keypress(function(e) {
        if ( e.keyCode == 13 ) {
			if($.trim($('#txtSearch').val())===''){
				alert('Please enter User name.');
				e.preventDefault();
			}
        }
    });
     $('.selVal').on('change',function(){
		var val = $(".selVal").val();
		//alert(val);
		window.location.href = '<?php echo $action;?>/'+val;
    });
});
</script>

<script src="<?=$this->config->item('base_url')?>public/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>


<script>
function myPagination(getval){
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('base_url').'admin/feedback/setpagination';?>",
		cache: false,
		data: 'pageid='+getval,
		datatype: "text",
		success: function(data){
			//$('#form2').submit();
			var val = 0;
			window.location.href = '<?php echo $action;?>/'+val+'/';
		}
  	});
}
</script>

<!-- Content -->

				</div>
			</div>
