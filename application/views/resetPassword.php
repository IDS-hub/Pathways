<?php
?>
<style>
    .rsp-block .form-group label { font-size: 15px; padding-top: 10px;}
</style>
<div class="rgt_container">
	<div class="navbar navbar-inverse topNav">
		<div class="col-xs-6">
			<form class="globalSearch" method="post" action="<?php echo $this->config->item('base_url');?>search/search" onsubmit="return check3();">
				<i class="material-icons dp48">search</i>
				<input type="text" class="form-control search" name="search" id="search" placeholder="Search User Name/ Mobile No.">
			</form>
		</div>
		<ul class="nav cust-nav navbar-nav navbar-right">
			<li class="dropdown dropdown-user" id="logout_menu">
				<a href="javascript:void(0);" class="dropdown-toggle pad-tb-5" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<span class="profile-pic-holder marg-t-0 marg-r-10"><img alt="" src="<?=$res['profile_image']?>"></span>
					<span class="username username-hide-on-mobile marg-t-10 cust-username ng-binding" style="display:inline-block;"><?=json_decode('"'.$res['first_name'].' '.$res['last_name'].'"');?></span>
					<i class="fa fa-angle-down" style="position: relative; top: -4px;"></i>
				</a>
				<ul class="dropdown-menu dropdown-menu-default">
					<li>
						<a href="<?php echo $this->config->item('base_url')?>notification">
							Notification
						</a>
					</li>
					<li>
						<a href="<?=$this->config->item('base_url')?>resetPassword">
							<i class="icon-key"></i> Reset Password
						</a>
					</li>
					<li>
						<a href="<?=$this->config->item('base_url')?>home/logout">
							<i class="icon-key"></i> Logout
						</a>
					</li>
				</ul>
			</li>
			<!-- END USER LOGIN DROPDOWN -->
		</ul>
	</div>
	<div class="contentDiv">
		<div class="row">
			<div class="col-md-6 col-md-offset-3 rsp-block">
				<h1 class="main_heading">Reset Password</h1>
				<form data-toggle='validator' action="<?php echo $this->config->item("base_url").'resetPassword/update';?>" method="post" name="resetPasswordFrm" id="resetPasswordFrm" >
					<?php echo $this->session->flashdata('message_name');?>

					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-lg-4 col-sm-6 noPaddAll">
							<label for="exampleInputEmail1">Current Password:</label>
						</div>
						<div class="col-lg-5 col-sm-6 noPaddAll">
							<input type="password" name="current_password" id="current_password" value="" class="form-control txtfield"/>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-lg-4 col-sm-6 noPaddAll">
							<label for="exampleInputEmail1">Password:</label>
						</div>
						<div class="col-lg-5 col-sm-6 noPaddAll">
							<input type="password" name="password" id="password" value="" class="form-control txtfield"/>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="form-group">
						<div class="col-lg-4 col-sm-6 noPaddAll">
							<label for="exampleInputEmail1">Confirm Password:</label>
						</div>
						<div class="col-lg-5 col-sm-6 noPaddAll">
							<input type="password" name="confirm_password" id="confirm_password" value="" class="form-control txtfield"/>
						</div>
					</div>
					<div class="clearfix"></div>

					<button type="submit" class="btn btn-default">Submit</button>
					<a href="javascript:void();" onclick="window.history.back();" class="btn btn-default" style="background-color:#e60e0a">Cancel </a>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready(function() {

      function validatePassword(contact_no) {
        var re =/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^()_+])[A-Za-z\d][A-Za-z\d!@#$%^()_+]{8,32}$/;
        return re.test(contact_no);
      }
      var password_error = true;
      var c_password_error = true;
	  var current_password_error = true;
      $('#password').keyup(function(){
          $(this).next('small').remove();
		  if($(this).val()){
			  //var input_valid = validatePassword($(this).val());
	          //if(!input_valid){
			  if(false){
	              $('<small class="text-danger">Password should contain one capital letter, one small letter, one number & one special character & must be between 8 and 20 characters.</small>').insertAfter($(this));
	              password_error = true;
	          }else{
	              password_error = false;
	          }
		  }else{
			  $('<small class="text-danger">This is required field.</small>').insertAfter($(this));
			  password_error = true;
		  }
      });

	  $('#current_password').keyup(function(){
          $(this).next('small').remove();
		  if($(this).val()){
			  current_password_error = false;
		  }else{
			  $('<small class="text-danger">This is required field.</small>').insertAfter($(this));
			  current_password_error = true;
		  }
      });

      $('#confirm_password').keyup(function(){
          $(this).next('small').remove();
		  if($(this).val()){
			  if($(this).val() != $('#password').val()){
	              $('<small class="text-danger">Password and Confirm password do not match.</small>').insertAfter($(this));
	              c_password_error = true;
	          }else{
	              c_password_error = false;
	          }
		  }else{
			  $('<small class="text-danger">This is required field.</small>').insertAfter($(this));
			  c_password_error = true;
		  }

      });


      $('#resetPasswordFrm').on('submit',function(e){
		  $('#confirm_password').next('small').remove();
		  $('#current_password').next('small').remove();
		  $('#password').next('small').remove();
        if(password_error || c_password_error || current_password_error){
			if(c_password_error){

				if($('#confirm_password').val()){
					$('<small class="text-danger">Password and Confirm password do not match.</small>').insertAfter($('#confirm_password'));
				}else{
					$('<small class="text-danger">This is required field.</small>').insertAfter($('#confirm_password'));
				}
			}
			if(password_error){
				$('<small class="text-danger">This is required field.</small>').insertAfter($('#password'));
				/*if($('#password').val()){
					$('<small class="text-danger">Password should contain one capital letter, one small letter, one number & one special character & must be between 8 and 20 characters.</small>').insertAfter($('#password'));
				}else{
					$('<small class="text-danger">This is required field.</small>').insertAfter($('#password'));
				}*/
			}
			if(current_password_error){
				$('<small class="text-danger">This is required field.</small>').insertAfter($('#current_password'));
			}
            e.preventDefault();
        }


      });
});
</script>
