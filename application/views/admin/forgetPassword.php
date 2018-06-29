<h1>Forget Password</h1>
<?php /*if($this->session->flashdata('msg2')){
	$msg2 = $this->session->flashdata('msg2'); ?>
	<div class="alert alert-danger center" style="" id="get_error_msg_main_id">
		<a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');">×</a>
		<?php echo $msg2;?>
	</div>
<?php }*/ ?>
<div class="col-xs-12 paddingNone marginNone">
		<div class="panel panelDiv">
<?php //echo form_open('admin/login/frgtPass'); ?>
<form action="<?=$this->config->item('base_url').'admin/login/frgtPass'?>" method="post" onsubmit="return validData();">
<div class="form-group panelInput">
    <span class="icon_user">
		<img src="<?=$this->config->item('base_url')?>public/images/icon_user.png" alt="">
	</span>
	<input type="text" name="txt_username" value="" id="useremail" class="form-control txtfield" placeholder="Enter Username" value="<?php echo set_value('txt_username'); ?>">
</div>
<input type="submit" name="btn_forget" value="Submit" class="btn btn-primary btn-block loginBtn">
<button name="back" type="button" value="Cancel" class="btn btn-primary btn-block loginBtn backBtn" onclick="location.href='<?=$this->config->item('base_url')?>admin/login'">Cancel</button>
<?php //echo form_error('txt_username'); ?>

<?php /*if($this->session->flashdata('item')){
	$message = $this->session->flashdata('item'); ?>
	<div><?php echo $message['message']; ?></div>
<?php }*/ ?>
<div id="contentMsg"></div>
</form>


</div>
</div>
<script type="text/javascript">
function validData(){
	var useremail = document.getElementById('useremail').value;
	useremail  = useremail.toString().trim();
	var maincontent = document.getElementById("contentMsg");
	if(useremail==''){
		maincontent.innerHTML = 'Please enter username';
		return false;
	}
	return true;
}
</script>
