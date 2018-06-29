<div class="content">
	<?php
		if(isset($uid) && $uid!=''){
			$add_url=$this->config->item("base_url")."admin/quiz/update";
			$addTitle = 'Edit';
		}else{
			$add_url=$this->config->item("base_url")."admin/quiz/add";
			$addTitle = 'Add';
		}
		$cancl_url=$this->config->item("base_url")."admin/quiz/index";
	?>
	<div>
	  	<ol class="breadcrumb">
			<li><?php echo $addTitle; ?> Quiz</li>
		</ol>
	</div>
	<div class="whiteBg clearfix">
	<div class="alert alert-danger" style="display:none;" id="get_error_msg_main_id"> <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');"></a> <strong>Error!&nbsp;</strong><span id="get_error_msg_id"></span></div>
	<div class="alert alert-success" style="display:none;" id="get_success_msg_main_id"> <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_success_msg_main_id');"></a> <strong>Success!&nbsp;</strong><span id="get_success_msg_id"></span></div>

	<?php if(isset($tmpdetails['id'])){?>

		<form action="<?=$add_url;?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm2" enctype="multipart/form-data"  onsubmit="return validTwo();">
  			<input type="hidden" name="gift_id" value="<?=$this->uri->segment(4);?>" />
            	<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Title :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="text" name="title" id="title" value="<?php if(isset($tmpdetails['title'])){ echo $tmpdetails['title']; } ?>" id="title" class="form-control txtfield">
  				</div>
            	</div>
  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Description :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
					<textarea name="session_description" cols="5" rows="5" id="session_description" class="form-control txtarea"><?php if(isset($tmpdetails['session_description'])){ echo $tmpdetails['session_description']; } ?></textarea>

  				</div>
            </div>

			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Summary :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
					<textarea name="session_summary" cols="5" rows="5" id="session_summary" class="form-control txtarea"><?php if(isset($tmpdetails['session_summary'])){ echo $tmpdetails['session_summary']; } ?></textarea>

  				</div>
            </div>

  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1">Image Upload :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="file" name="icon_file" id="session_summary_image" class="gift_img" value="<?php if(isset($tmpdetails['session_summary_image'])){ echo $tmpdetails['session_summary_image']; } ?>">
  					<?php if(isset($tmpdetails['session_summary_image'])){?>
  						<img class="gift_img" src="<?=$tmpdetails['session_summary_image'];?>" width="40">
  					<?php } ?>
  				</div>
            </div>

			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1">Audio Upload :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="file" name="icon_file_audio" id="audio_url" class="gift_img" value="<?php if(isset($tmpdetails['audio_url'])){ echo $tmpdetails['audio_url']; } ?>">
  					<?php if(isset($tmpdetails['audio_url'])){?>
  						<img class="gift_img" src="<?=$tmpdetails['session_summary_image'];?>" width="40">
  					<?php } ?>
  				</div>
            </div>


  		   	<div class="clearfix"></div>
  			<div class="form-group pull-right">
  				<a href="<?=$cancl_url;?>" class="btn btn-default btnSubmit btnCancel">Cancel</a>&nbsp;<input type="submit" name="newsubmit" value="Submit" class="btn btn-default btnSubmit">
  			</div>
  			<div class="clearfix"></div>
  		</form>
	<?php }else{ ?>
		<form action="<?=$add_url;?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm2" enctype="multipart/form-data"  onsubmit="return validOne();">

            	<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Title :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="text" name="title" id="title" value="" id="title" class="form-control txtfield">
  				</div>
            	</div>
  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Amount :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="text" name="coin_amt" id="coin_amt" value="" class="form-control txtfield">
  				</div>
            	</div>
  			<div class="form-group clearfix">
  	          	<div class="col-lg-2 noPaddAll">
  					<label for="exampleInputEmail1"><span class="redTxt">*</span>Icon :</label>
  	            </div>
  	            <div class="col-lg-4 noPaddAll">
  					<input type="file" name="icon_file" id="icon_file" value="" >

  				</div>
            	</div>


  		   	<div class="clearfix"></div>
  			<div class="form-group pull-right">
  				<a href="<?=$cancl_url;?>" class="btn btn-default btnSubmit btnCancel">Cancel</a>&nbsp;<input type="submit" name="newsubmit" value="Submit" class="btn btn-default btnSubmit">
  			</div>
  			<div class="clearfix"></div>
  		</form>


	<?php } ?>



<!--<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap-wizard/1.2/jquery.bootstrap.wizard.min.js"></script>-->
<script type="text/javascript">
function validOne(){
	var title = document.getElementById('title').value;
	title  = title.toString().trim();
	if(title==''){
		alert('Please enter title');
		return false;
	}
	var coin_amt = document.getElementById('coin_amt').value;
	coin_amt  = coin_amt.toString().trim();
	if(coin_amt==''){
		alert('Please enter coin amount');
		return false;
	}
	if(!coin_amt.match(/\d+$/)){
	    alert("Please enter valid amount");
		return false;
	}

	var val = $("#icon_file").val();
	val  = val.toString().trim();
	if(val==''){
		alert('Please enter Image');
		return false;
	}
	if(!val.match(/(?:gif|jpg|jpeg|png|bmp|svg)$/)){
	    alert("Please enter valid Image");
		return false;
	}
	return true;
}

function validTwo(){
	var title = document.getElementById('title').value;
	title  = title.toString().trim();
	if(title==''){
		alert('Please enter title');
		return false;
	}
	var coin_amt = document.getElementById('coin_amt').value;
	coin_amt  = coin_amt.toString().trim();
	if(coin_amt==''){
		alert('Please enter coin amount');
		return false;
	}
	if(!coin_amt.match(/\d+$/)){
	    alert("Please enter valid amount");
		return false;
	}
	//var gift_img = $("#gift_img").val();
	//alert(gift_img);
	var val = $("#gift_img").val();
	val  = val.toString().trim();
	// alert(val);
	// if(val==''){
	// 	alert('Please enter Image');
	// 	return false;
	// }
	// if(!val.match(/(?:gif|jpg|jpeg|png|bmp|svg)$/)){
	//     alert("Please enter valid Image");
	// 	return false;
	// }
	//$(this).attr("value", "");
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
