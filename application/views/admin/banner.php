
     <div class="content">

	   <div>
      	<ol class="breadcrumb">
			<li>Banner List<?php //echo $mainpagename1; ?></li>
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
		$stores_add_href_attribute=array('class' => 'btn btn-default orngBtn pull-left');
		$add_url=$this->config->item("base_url")."admin/banner/banneraddedit";
		echo anchor($add_url, '<i class="fa fa-plus"></i>&nbsp; Add Banner', $stores_add_href_attribute);
		echo "&nbsp;";
		//echo form_button($stores_delete_all_btn_attribute);;
		?>
	</div>
	<div class="col-md-9">
		<ul class='menuList row'>

		  <?php /*?><li>
		<form role="form" class="form-inline m-b-5 pull-right" method="post" action="<?php $this->config->item('base_url').'notification/index';?>" id="searchfrm">
			<input name="frmsubmit" type="hidden" value="YES">
			<input name="clearval" id="clearval" type="hidden" value="">

			<div class="form-group">
			  <div class="input-group inside no-boarder" style="vertical-align:top;">
				<div class="input-group-addon add-on no-boarder"><i class="fa fa-search" style="color:#212C32;"></i></div>
				<input name="txtSearch" id="txtSearch" type="text" data-toggle="tooltip" data-placement="top" title="Search By : Title" class="form-control no-boarder white-tooltip" placeholder="Search Panel" style="width:185px;" value="<?php echo ($this->input->post('txtSearch')!='')?$txtSearch:''; ?>">
				<a href="javascript:void(0);" onclick="deleteSession();" class="input-group-addon add-on no-boarder"><i class="fa fa-times-circle" style="color:#212C32;"></i></a>
			  </div>
		  </div>

		  </form>
	  </li><?php */?>
	  <li class="col-md-2" style="vertical-align:top;">
		<form role="form2" id="form2" action="" method="post">
			<div class="form-group drop-icon" style="width: 100%;">
		    <?php
				$type[10] = 10;
				$type[25] = 25;
				$type[50] = 50;
				$type[100] = 100;
				$type[200] = 200;
				echo form_dropdown('type', $type, $this->session->userdata('sv_bannerpageid'), "id='type' class='pageTyp form-control' onchange='myPagination(this.value);' style='background:#eeeeee;'");
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
                    <th class="center"></th>
					<th>Image</td>
					<th>URL</th>
					<th>Status</th>
					<th class="right-text">Action</th>
                </tr>
            </thead>
            <tbody>

<?php
//echo form_open($this->config->item('base_url').'users/delete', $emailtmp_delete_frm_attribute);

	$edit_image=array('src' => $this->config->item('base_url').'public/images/icon_edit.png','alt' => 'Edit','title'=>'Edit');
	$delete_image=array('src' => $this->config->item('base_url').'public/images/icon_delete.png','alt' => 'Delete','title'=>'Delete');
	if(count($tmpdetails) > 0){
		$i=1;
		foreach($tmpdetails as $tmpdetails1){
?>
				<!-- Table row -->
                <tr>
                    <td class="center"><?php //echo form_checkbox($check_attr); ?></td>
					<td><?php echo ($tmpdetails1['banner_img']!='')?"<img src=".$tmpdetails1['banner_img']." width=auto height=60>":'';?></td>
					<td><?php echo $tmpdetails1['banner_url'] ; ?></td>
					<td><?=(($tmpdetails1['status']==0)?'Published':'Unpublished')?></td>
					<td><a href="<?php echo $this->config->item('base_url').'admin/banner/banneraddedit/'.$tmpdetails1['id']; ?>">Edit</a>&nbsp;&nbsp;
					<a href="<?php echo $this->config->item('base_url').'admin/banner/bannerDelete/'.$tmpdetails1['id']; ?>">Delete</a></td>
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
function deleteSession(){
	$('#clearval').val('clear');
	$('#txtSearch').val('');
	//$('#selVal').val();
	var val = 0;
	window.location.href = '<?php echo $action;?>/'+val;
}
</script>

<script src="<?=$this->config->item('base_url')?>public/js/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>


<script>
function myPagination(getval){
	$.ajax({
		type: "POST",
		url: "<?php echo $this->config->item('base_url').'admin/banner/setpagination';?>",
		cache: false,
		data: 'pageid='+getval,
		datatype: "text",
		success: function(data){
			//$('#form2').submit();
			var val = 0;
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
