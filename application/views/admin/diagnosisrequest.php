<?php
$search_frm_attribute=array('name' => 'searchfrm', 'id' => 'searchfrm','role'=>'form','class'=>"form-inline");
$search_submit_btn_attribute=array('name' => 'searchsubmit', 'class' => 'btn btn-default btnSubmit','value'=>'Search','style'=>'margin-left:0px; margin-right:0px;','onclick' => 'return checksearchdata();');
$search_by_title_attribute=array('name' => 'search_by_title', 'id' => 'search_by_title','class' => 'form-control','value'=>'','placeholder' => 'Search by title','value' => $this->session->userdata('gv_emailtemp_search'));
$clear_button_btn_attribute=array('name' => 'clear', 'class' => 'btn btn-default btnSubmit','content' => 'Clear','style'=>'margin-left:0px; margin-right:0px;','onclick' => 'javascript:clear_search();');

?>


     <div class="content">

	   <div>
      	<ol class="breadcrumb">
			<li>Diagnosis request List<?php //echo $mainpagename1; ?></li>
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
		   <li class="col-md-2">
		<form role="form" class="form-inline m-b-5 " method="post" action="<?php echo $action;?>" id="searchfrm">
			<input name="frmsubmit" type="hidden" value="YES">
			<input name="clearval" id="clearval" type="hidden" value="">

			<!--<div class="form-group" style="width: 100%;">
				<select class="selVal" id="selVal" name="selVal" style="vertical-align:top;">
					<option value="0" <?=(isset($selVal) && $selVal==0)?'selected':''?>>Approve</option>
					<option value="1" <?=(isset($selVal) && $selVal==1)?'selected':''?>>Not Approve</option>
				</select>
			</div>-->
		</li>
		<li class="col-md-4">
			<div class="form-group" style="width: 100%;">
			  <!--<div class="input-group inside no-boarder" style="vertical-align:top;">
				<div class="input-group-addon add-on no-boarder"><i class="fa fa-search" style="color:#212C32;"></i></div>
				<input name="txtSearch" id="txtSearch" type="text" data-toggle="tooltip" data-placement="top" title="Search By : User Name, Email" class="form-control no-boarder white-tooltip" placeholder="Search by Name or Mobile Number" value="<?php echo ($this->input->post('txtSearch')!='')?$txtSearch:''; ?>">
				<a href="javascript:void(0);" onclick="deleteSession();" class="input-group-addon add-on no-boarder"><i class="fa fa-times-circle" style="color:#212C32;"></i></a>
			  </div>--->
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
				echo form_dropdown('type', $type, $this->session->userdata('sv_incomepageid'), "id='type' class='pageTyp form-control' onchange='myPagination(this.value);' style='background:#eeeeee;'");
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
					<th>Diagnosisrequest Title</th>
					<th>Entries</th>
                    <th>Created</th>
					<th class="right-text">Action</th>

                </tr>
            </thead>
            <tbody>

<?php
//echo form_open($this->config->item('base_url').'users/delete', $emailtmp_delete_frm_attribute);

	$edit_image=array('src' => $this->config->item('base_url').'public/images/icon_edit.png','alt' => 'Edit','title'=>'Edit');
	$delete_image=array('src' => $this->config->item('base_url').'public/images/icon_delete.png','alt' => 'Delete','title'=>'Delete');
	if(count($tmpdetails) > 0){
		//echo '<pre>'; print_r($tmpdetails); die();
		$i=$pg_start;
		foreach($tmpdetails as $tmpdetails1){
		$ReqTitle = $tmpdetails1['diagnosis_request_title'];
		$entryQuery = "SELECT count(*) as title FROM diagnosis_request_tbl WHERE diagnosis_request_title='".$ReqTitle."'";
		$res = $this->db->query($entryQuery);
		//echo $this->db->last_query(); die();
		//echo '<pre>'; print_r($res);
		$result = $res->row_array();
        $entriesCount = $result['title'];

		?>

				<!-- Table row -->
                <tr>
                    <td class="center"><?php //echo form_checkbox($check_attr); ?></td>
                    <td><?php echo $i; ?></td>
					<td><?php echo $tmpdetails1['diagnosis_request_title'];?></td>
					<td><?php echo $entriesCount; ?></td>
					<td><?php echo $tmpdetails1['created'];?></td>
					<td class="requested-release"><a href="<?php echo $this->config->item('base_url').'admin/diagnosisrequest/delete_click_approve/'.$tmpdetails1['id']; ?>"><?=(($tmpdetails1['is_approved']==0)?'Approve':'Approved')?></a></td>
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
		url: "<?php echo $this->config->item('base_url').'admin/diagnosisrequest/setpagination';?>",
		cache: false,
		data: 'pageid='+getval,
		datatype: "text",
		success: function(data){
			//$('#form2').submit();
			//var val = $(".featured").val();
			//var featured = ((val=='') && (val==0))?0:1;
			//alert(featured);
			var val = 0;
			window.location.href = '<?php echo $action;?>/'+val+'/';
		}
  	});
}
</script>

<!-- Content -->

				</div>
			</div>
