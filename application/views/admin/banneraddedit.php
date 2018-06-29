<div class="content">
	<?php
		if(isset($uid) && $uid!=''){
			$add_url=$this->config->item("base_url")."admin/banner/update";
			$addTitle = 'Edit';
		}else{
			$add_url=$this->config->item("base_url")."admin/banner/add";
			$addTitle = 'Add';
		}
		$cancl_url=$this->config->item("base_url")."admin/banner/index";
	?>
	<div>
	  	<ol class="breadcrumb">
			<li><?php echo $addTitle; ?> Banner</li>
		</ol>
	</div>
	<div class="whiteBg clearfix">
	<div class="alert alert-danger" style="display:none;" id="get_error_msg_main_id"> <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');"></a> <strong>Error!&nbsp;</strong><span id="get_error_msg_id"></span></div>
	<div class="alert alert-success" style="display:none;" id="get_success_msg_main_id"> <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_success_msg_main_id');"></a> <strong>Success!&nbsp;</strong><span id="get_success_msg_id"></span></div>

	<?php if(isset($tmpdetails['id'])){?>
		<form action="<?=$add_url;?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm2" enctype="multipart/form-data" onsubmit="return validTwo();">
  			<input type="hidden" name="banner_id" value="<?=$this->uri->segment(4);?>" />
            	<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>URL :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="text" name="banner_url" id="banner_url" value="<?php if(isset($tmpdetails['banner_url'])){ echo $tmpdetails['banner_url']; } ?>"  class="form-control txtfield">
  				</div>
            	</div>

  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1">Image11111 :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="file" name="banner_file" id="banner_file"  value="<?php if(isset($tmpdetails['banner_file'])){ echo $tmpdetails['banner_file']; } ?>" >
  					<?php if(isset($tmpdetails['banner_img'])){?>
  						<img src="<?=$tmpdetails['banner_img'];?>" width="40">
  					<?php } ?>
  				</div>
            	</div>
  			<?php if(isset($tmpdetails)){?>
  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1">Status :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="radio" name="status" value="0" <?php if(isset($tmpdetails['status']) && $tmpdetails['status']=='0'){ echo 'checked'; } ?>> Published |
  					<input type="radio" name="status" value="1" <?php if(isset($tmpdetails['status']) && $tmpdetails['status']=='1'){ echo 'checked'; } ?>> Unpublished
  				</div>
            	</div>
  			<?php } ?>
  		   	<div class="clearfix"></div>
  			<div class="form-group pull-right">
  				<a href="<?=$cancl_url;?>" class="btn btn-default btnSubmit btnCancel">Cancel</a>&nbsp;<input type="submit" name="newsubmit" value="Submit" class="btn btn-default btnSubmit">
  			</div>
  			<div class="clearfix"></div>
  		</form>

	<?php }else{ ?>
		<form action="<?=$add_url;?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm2" enctype="multipart/form-data" onsubmit="return validOne();">
  			<input type="hidden" name="banner_id" value="<?=$this->uri->segment(4);?>" />
            	<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>URL :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="text" name="banner_url" id="banner_url" value="<?php if(isset($tmpdetails['banner_url'])){ echo $tmpdetails['banner_url']; } ?>"  class="form-control txtfield">
  				</div>
            	</div>

  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Image11 :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="file" name="banner_file" id="banner_file"  value="<?php if(isset($tmpdetails['banner_file'])){ echo $tmpdetails['banner_file']; } ?>" >
  					<?php if(isset($tmpdetails['banner_img'])){?>
  						<img src="<?=$tmpdetails['banner_img'];?>" width="40">
  					<?php } ?>
					
					<p style="font-weight:bold; padding-top:3px; font-size:13px">[Recommended Dimention (W x H) : 1425 x 475]</p>
  				</div>
            	</div>

  		   	<div class="clearfix"></div>
  			<div class="form-group pull-right">
  				<a href="<?=$cancl_url;?>" class="btn btn-default btnSubmit btnCancel">Cancel</a>&nbsp;<input type="submit" name="newsubmit" value="Submit" class="btn btn-default btnSubmit">
  			</div>
  			<div class="clearfix"></div>
  		</form>
	<?php }?>



<!--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap-wizard/1.2/jquery.bootstrap.wizard.min.js"></script>-->
<script type="text/javascript">

function validTwo(){
	var title = document.getElementById('banner_url').value;
	title  = title.toString().trim();
	if(title==''){
		alert('Please enter url');
		return false;
	}

	return true;
}

function validOne(){
	var title = document.getElementById('banner_url').value;
	title  = title.toString().trim();
	if(title==''){
		alert('Please enter url');
		return false;
	}
	var val = $("#banner_file").val();
	val  = val.toString().trim();
	if(val==''){
		alert('Please enter Image');
		return false;
	}
	if (!val.match(/(?:gif|jpg|jpeg|png|bmp|svg)$/)){
	    alert("Please enter valid Image");
		return false;
	}
	return true;
}


$(document).ready(function() {
    $('#storefrm').formValidation({
      message: 'This value is not valid',

        icon: {
            valid: 'glyphicon glyphicon-ok',
            //invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
			earn: {
                row: '.col-lg-4',
                validators: {
                    notEmpty: {
                        message: 'Please provide coin.'
                    },
					numeric: {
                        message: 'The price must be a number'
                    }/*,
					regexp: {
                        regexp: /^[a-zA-Z0-9_ \'.]+$/,
                        message: 'store name can only consist of alphabetical, number, dot and underscore'
                    }*/
                }
            },
        }
    })
	.on('success.field.fv', function(e, data) {
            data.fv.disableSubmitButtons(false);
    }).end().on('success.form.fv', function(e) {
		e.preventDefault();
		var $form = $(e.target);
		var bv = $form.data('formValidation');
		bv.defaultSubmit();
		return true;
	});


});
</script>
      </div>
</div>
