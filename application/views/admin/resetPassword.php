
<div class="content">
	<div class="page-title">
		<div class="row">
			<div class="col-sm-6">
				<h1>Reset Password </h1>
			</div>
		</div>
	</div>
	<div class="whiteBg clearfix">




		<!-- Content -->
		<!--<h2>Admin Profile Page</h2>
		<a href="login/logout">Logout</a>-->
		<?php //echo form_open('admin/member/update','role="form"' ); ?>
		<form action="<?php echo $this->config->item("base_url").'admin/member/confirmPassword';?>" method="post" accept-charset="utf-8" name="storefrm" id="storefrm" enctype="multipart/form-data" >
			<?php echo $this->session->flashdata('message_name');?>

			<div class="clearfix"></div>
			<div class="form-group">
				<div class="col-lg-2 noPaddAll">
					<label for="exampleInputEmail1">Password:</label>
				</div>
				<div class="col-lg-4 noPaddAll">
					<input type="password" name="new_admin_pwd" value="" class="form-control txtfield" placeholder="********"/>
					<div class="error"><?php echo form_error('password'); ?></div>
				</div>
			</div>
			<div class="clearfix"></div>
			<div class="form-group">
				<div class="col-lg-2 noPaddAll">
					<label for="exampleInputEmail1">Confirm Password:</label>
				</div>
				<div class="col-lg-4 noPaddAll">
					<input type="password" name="confirm_password" value="" class="form-control txtfield" placeholder="********"/>
					<div class="error"><?php echo form_error('password'); ?></div>
				</div>
			</div>
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
             new_admin_pwd: {
                validators: {
                    notEmpty: {
						message: 'Please provide password.'
                    },
					regexp: {
                        regexp: /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^()_+])[A-Za-z\d][A-Za-z\d!@*#$%^()_+]{7,32}$/,
                        message: 'Password should contain one capital letter, one small letter, one number & one special character & must be between 8 and 20 characters.'
                    }
                }
            },
            confirm_password: {
                validators: {
                    notEmpty: {
						message: 'Please provide confirn password.'
                    },
                    identical: {
                        field: 'new_admin_pwd',
                        message: 'The password and its confirm are not the same'
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
