
     <div class="content">
       <div class="page-title">
		   <div class="row">
			   <div class="col-sm-6">
				   <h1>Edit Profile <?php //echo $mainheader; ?></h1>
			   </div>
			   <div class="col-sm-6">
				   <div class="text-right">
					   <a href="<?php echo $this->config->item("base_url").'admin/member/resetPassword';?>" class="btn btn-default btnSubmit">Reset Password</a>
				   </div>
			   </div>
		   </div>
	   </div>
  		<div class="whiteBg clearfix">




<!-- Content -->
<!--<h2>Admin Profile Page</h2>
<a href="login/logout">Logout</a>-->
<?php //echo form_open('admin/member/update','role="form"' ); ?>
<form action="<?php echo $this->config->item("base_url").'admin/member/update';?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm" enctype="multipart/form-data" >
<input type="hidden" name="id" value="<?php echo $view_data['id']; ?>"/>
<?php echo $this->session->flashdata('message_name');?>

<div class="form-group">
	<div class="col-lg-2 noPaddAll">
	<label for="exampleInputEmail1">Username:</label>
	</div>
	<div class="col-lg-4 noPaddAll">
		<input type="text" name="username" value="<?php echo $view_data['username']; ?>" class="form-control txtfield"/>
		<div class="error"><?php //echo form_error('username'); ?></div>
	</div>
  </div>

 <div class="clearfix"></div>
		<div class="form-group">
          	<div class="col-lg-2 noPaddAll">
            <label for="exampleInputEmail1"><span class="redTxt">*</span>First Name:</label>
            </div>
            <div class="col-lg-4 noPaddAll">
				<input type="text" name="first_name" value="<?php echo $view_data['first_name']; ?>" class="form-control txtfield"/>
				<div class="error"><?php echo form_error('first_name'); ?></div>
            </div>
          </div>

		  	  <div class="clearfix"></div>
	  		  <div class="form-group">
	            	<div class="col-lg-2 noPaddAll">
	              <label for="exampleInputEmail1"><span class="redTxt">*</span>Last Name:</label>
	              </div>
	              <div class="col-lg-4 noPaddAll">
					  <input type="text" name="last_name" value="<?php echo $view_data['last_name']; ?>" class="form-control txtfield"/>
					  <div class="error"><?php echo form_error('last_name'); ?></div>
	              </div>
	            </div>

			  <div class="clearfix"></div>
			  <div class="form-group">
	          	<div class="col-lg-2 noPaddAll">
	            <label for="exampleInputEmail1"><span class="redTxt">*</span>Email:</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="text" readonly="true" name="email" value="<?php echo $view_data['email']; ?>" class="form-control txtfield"/>
					<div class="error"><?php echo form_error('email'); ?></div>
	            </div>
	          </div>

  			  <div class="clearfix"></div>
			  <div class="form-group">
	          	<div class="col-lg-2 noPaddAll">
	            <label for="exampleInputEmail1"><span class="redTxt">*</span>Phone Number:</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="text" name="mob_no" class="mob_no" minlength="10" maxlength="15" value="<?php echo $view_data['mob_no']; ?>" class="form-control txtfield"/>
					<div class="error"><?php echo form_error('mob_no'); ?></div>
	            </div>
	          </div>

			  <div class="clearfix"></div>
			  <div class="form-group">
	          	<div class="col-lg-2 noPaddAll">
	            <label for="exampleInputEmail1">Profile Image:</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="file" name="member_file" id="member_file"  value="<?php if(isset($view_data['member_img'])){ echo $view_data['member_img']; } ?>" >
  					<?php if(isset($view_data['member_img'])){?>
  						<img src="<?=$this->config->item('base_url').'uploads/user/'.$view_data['member_img'];?>" width="40">
  					<?php } ?>
	            </div>
	          </div>



  	  		  <!-- <div class="clearfix"></div>
			  <div class="form-group">
	          	<div class="col-lg-2 noPaddAll">
	            <label for="exampleInputEmail1">Password:</label>
	            </div>
	            <div class="col-lg-4 noPaddAll">
					<input type="password" name="password" value="< ?php echo $view_data['password']; ?>" class="form-control txtfield"/>
					<div class="error">< ?php echo form_error('password'); ?></div>
	            </div>
	          </div> -->
			<div class="clearfix"></div>

			<input type="submit" name="update" value="Update" class = "btn btn-default btnSubmit"/>
			<a href="javascript:void();" onclick="window.history.back();" class = "btn btn-default btnSubmit">Cancel </a>

		</form>
<?php //echo form_close(); ?>
<!-- Content -->




				</div>
			</div>
<script type="text/javascript">
	$(document).ready(function() {
	    $('#storefrm').formValidation({
	      message: 'This value is not valid',

	        icon: {
	            valid: 'glyphicon glyphicon-ok',
	            //invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
	        },
	        fields: {

				mob_no: {
	                row: '.col-lg-4',
	                validators: {
	                    notEmpty: {
	                        message: 'Please provide mobile number.'
	                    },
						numeric: {
	                        message: 'The price must be a number'
	                    }
	                }
	            },
				username:{
					row: '.col-lg-4',
	                validators: {
	                    notEmpty: {
	                        message: 'Please provide username.'
	                    }
	                }
				},
				first_name:{
					row: '.col-lg-4',
	                validators: {
	                    notEmpty: {
	                        message: 'Please provide first name.'
	                    }
	                }
				},
				last_name:{
					row: '.col-lg-4',
	                validators: {
	                    notEmpty: {
	                        message: 'Please provide last name.'
	                    }
	                }
				}
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
