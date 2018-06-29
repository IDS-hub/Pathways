<?php
$last = $this->uri->total_segments();
$rand_num = $this->uri->segment($last);
?>
<h1>RESET PASSWORD</h1>
<?php if(isset($msg1)){ ?>
<div class="alert alert-danger" style="" id="get_error_msg_main_id">
  <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');">×</a>
  <strong>Error!&nbsp;</strong><span id="get_error_msg_id"><?php echo $msg1;?></span>
</div>
<?php }?>
<?php $sessMsg = $this->session->userdata('msg'); ?>
<?php if(isset($sessMsg)){ ?>
<div class="alert alert-danger" style="" id="get_error_msg_main_id">
  <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');">×</a>
  <strong>Error!&nbsp;</strong><span id="get_error_msg_id">Please valid username & password.</span>
</div>
<?php }?>
<div id="contentMsg2"></div>
<div class="col-xs-12 paddingNone marginNone">
	<div class="panel panelDiv">
	<?php //echo form_open('admin/reset/passwordchk' ); ?>
	<?php if($message) { ?>
		<form action="" method="post" onsubmit="return validOn();">
			<div class="form-group panelInput">
			    <span class="icon_user">
					<img src="<?=$this->config->item('base_url')?>public/images/icon_passwrd.png" alt="">
				</span>
				<input type="password" name="new_password" id="new_password" class="form-control txtfield" placeholder="New Password"/>
				<span class="icon_passwrd">
					<img src="<?=$this->config->item('base_url')?>public/images/icon_passwrd.png" alt="">
				</span>
				<input type="password" name="con_password" id="con_password" class="form-control txtfield2" placeholder="Confirm Password"/>
				<input type="hidden" name="hidnum" id="hidnum" value="<?=isset($rand_num)?$rand_num:''?>" />
			</div>
			<input type="submit" name="btn_reset" value="Reset" class="btn btn-primary btn-block loginBtn" >

		</form>
	<?php }else{ ?>
		<div class="row text-center">
			<div class="alert alert-danger" role="alert">
				Link has been expired.
			</div>
		</div>
		<div class="row text-center">
			<a href="<?=$this->config->item('base_url')?>/admin" class="btn btn-primary btn-block loginBtn">Go to home</a>
		</div>
	<?php } ?>


	</div>
</div>
<script type="text/javascript">
function validOn(){
	var password = document.getElementById("new_password").value;
    var confirmPassword = document.getElementById("con_password").value;
	var maincontent2 = document.getElementById("contentMsg2");
	var regex = /^\w{6}$/;
	var reg1 = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^()_+&/\*])[A-Za-z\d][A-Za-z\d!@#$%^()_+&/\*]{8,20}$/;
    if(reg1.test(password) == false){
	   //alert("Your password must be at least 6 characters");
	   //alert("Password should contain one capital letter, one small letter, one number & one special character & must be between 8 and 20 characters.");
	   maincontent2.innerHTML = 'Password should contain one capital letter, one small letter, one number & one special character & must be between 8 and 20 characters.';
	   return false;
    }
    if (password != confirmPassword) {
        //alert("Passwords do not match.");
		maincontent2.innerHTML = 'Passwords do not match.';
        return false;
    }
	return true;
}
</script>
