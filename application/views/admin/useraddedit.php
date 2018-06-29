<div class="content">
	<?php
		if(isset($uid) && $uid!=''){
			$add_url=$this->config->item("base_url")."admin/user/update";
			$addTitle = 'Edit';
		}else{
			$add_url=$this->config->item("base_url")."admin/user/add";
			$addTitle = 'Add';
		}
		$cancl_url=$this->config->item("base_url")."admin/user/index/1";
	?>
	<div>
	  	<ol class="breadcrumb">
			<li><?php echo $addTitle; ?> User</li>
		</ol>
	</div>
	<div class="whiteBg clearfix">
	<div class="alert alert-danger" style="display:none;" id="get_error_msg_main_id"> <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');"></a> <strong>Error!&nbsp;</strong><span id="get_error_msg_id"></span></div>
	<div class="alert alert-success" style="display:none;" id="get_success_msg_main_id"> <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_success_msg_main_id');"></a> <strong>Success!&nbsp;</strong><span id="get_success_msg_id"></span></div>

      <form action="<?=$add_url;?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm2" enctype="multipart/form-data" onsubmit="return validOne();">

<?php //echo $uid;?>

			<input type="hidden" name="user_id" value="<?=$this->uri->segment(4);?>" />
          	<div class="form-group clearfix">
	          	<div class="col-lg-2 noPaddAll">
					<label for="exampleInputEmail1"><span class="redTxt">*</span>First Name :</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="text" name="first_name" id="first_name" value="<?php if(isset($tmpdetails['first_name'])){ echo $tmpdetails['first_name']; } ?>" id="first_name" class="form-control txtfield">
				</div>
          	</div>
                        
                        <div class="form-group clearfix">
	          	<div class="col-lg-2 noPaddAll">
					<label for="exampleInputEmail1"><span class="redTxt">*</span>Last Name :</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="text" name="last_name" id="last_name" value="<?php if(isset($tmpdetails['last_name'])){ echo $tmpdetails['last_name']; } ?>"  class="form-control txtfield">
				</div>
          	</div>
                        
                        <div class="form-group clearfix">
	          	<div class="col-lg-2 noPaddAll">
					<label for="exampleInputEmail1"><span class="redTxt">*</span>Email :</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="text" name="email" id="email" value="<?php if(isset($tmpdetails['email'])){ echo $tmpdetails['email']; } ?>" class="form-control txtfield">
				</div>
          	</div>
                        
                    


		   	<div class="clearfix"></div>
			<div class="form-group pull-right">
				<a href="<?=$cancl_url;?>" class="btn btn-default btnSubmit btnCancel">Cancel</a>&nbsp;<input type="submit" name="newsubmit" value="Submit" class="btn btn-default btnSubmit">
			</div>
			<div class="clearfix"></div>
</form>


<!--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap-wizard/1.2/jquery.bootstrap.wizard.min.js"></script>-->
<script type="text/javascript">
function validOne(){
	var title = document.getElementById('title').value;
	title  = title.toString().trim();
	if(title==''){
		alert('Please enter title');
		return false;
	}
	var message = document.getElementById('message').value;
	message  = message.toString().trim();
	if(message==''){
		alert('Please enter message');
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
