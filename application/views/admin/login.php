          <h1>SIGN IN</h1>
		  <?php $msg2 = $this->session->userdata('msg2'); ?>
		  <?php if(isset($msg2)){ ?>
		  <div class="alert alert-success center" style="" id="get_error_msg_main_id">
			  <a href="javascript:void(0)" class="close" onclick="javascript:hide_error_msg('get_error_msg_main_id');">×</a>
			  <?php echo $msg2;?>
		  </div>
		  <?php }?>
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
			  <strong>Error!&nbsp;</strong><span id="get_error_msg_id">Invalid username & password.</span>
		  </div>
		  <?php }?>
		  <div class="col-xs-12 paddingNone marginNone">

			  <div class="panel panelDiv">
				<?php echo form_open('admin/login/loginchk' ); ?>
				<div class="form-group panelInput">
				    <span class="icon_user">
						<img src="<?=$this->config->item('base_url')?>public/images/icon_user.png" alt="">
					</span>
					<input type="text" name="txt_username" value="<?php echo set_value('txt_username'); ?>" id="loginemail" class="form-control txtfield" placeholder="Username"/>
					<span class="icon_passwrd">
						<img src="<?=$this->config->item('base_url')?>public/images/icon_passwrd.png" alt="">
					</span>
					<input type="password" name="txt_password" value="<?php echo set_value('txt_password'); ?>" id="loginpassword" class="form-control txtfield2" placeholder="Password"/>
				</div>

				<div class="checkbox">
					<!-- <label><input type="checkbox" name="remember_me" value="CHECKED" id="remember_me" checked="<?=($this->input->cookie('remember_me') == 'CHECKED') ?true: false;?>"> Remember Me</label> -->
					<a href="<?=$this->config->item('base_url')?>admin/login/forgetPassword" title="Forgot Password?" class="pull-right">Forgot Password?</a>
				</div>
				<input type="submit" name="btn_login" value="Login" class="btn btn-primary btn-block loginBtn" >

				<?php echo form_close(); ?>

				</div>
			</div>
