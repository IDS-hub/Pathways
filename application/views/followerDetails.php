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
			<div class="col-md-6 col-md-offset-3">
				<h1 class="main_heading">Public Profile</h1>
				<?php //print_r($prof);?>
				<div class="imageDiv profileview">
					<div class="coverPic_div"><img src="<?=(isset($prof->cover_image) && $prof->cover_image!='')?$prof->cover_image:$this->config->item('base_url').'uploads/coverImage/no-image.png';?>" />
						<div class="profile_pic"><img src="<?=(isset($prof->profile_image) && $prof->profile_image!='')?$prof->profile_image:$this->config->item('base_url').'uploads/user/no-image.png';?>" />

						</div>

					</div>
					<div class="profile_info">

						<div class="profile_name">
							<?php echo json_decode('"'.$prof->first_name.' '.$prof->last_name.'"');?>
							<?php
							if($prof->gender == 'Male'){ ?>
								<img src="<?php echo $this->config->item('base_url')?>public/images/icon/male.png" style="width:20px;height:20px;" />
							<?php }else{ ?>
								<img src="<?php echo $this->config->item('base_url')?>public/images/icon/female.png" style="width:20px;height:20px;" />
							<?php } ?>
						</div>

						<div class="profile_name">
							<?php echo json_decode('"'.$prof->user_code.'"');?>
						</div>

						<p>Following : <?php echo (isset($prof->following)?$prof->following:'');?> &nbsp; / &nbsp;  Followers : <?php echo (isset($prof->follower)?$prof->follower:'');?> &nbsp;  /  &nbsp; Fans : <?php echo (isset($prof->no_of_fan)?$prof->no_of_fan:'');?></p>
						<div class="profile_name">Location  : <?php echo json_decode('"'.$prof->location.'"');?></div>
						<div class="profile_name">Level : <?php echo json_decode('"'.$prof->level.'"');?></div>
						<a href="<?php echo $this->config->item('base_url');?>follower/fanDetails/<?=$prof->id;?>" class="profile_name" style="color:#000;">Fan List</a>

					</div>
					<div class="wrapfol_<?=$prof->id?>">
						<a class="<?=($prof->is_follow=="1")?"btn btn-Unfollow btn-block":"btn btn-Follow btn-block"?> "
							href="javascript:followFlg('<?=$prof->id?>','<?=($prof->is_follow=="1")?"0":"1"?>')">
							<?php echo ($prof->is_follow!=0)?'Unfollow':'+Follow';?>
						</a>
					</div>

				</div>
				<div class="row text-right">

					<button onclick="window.history.back();" class="btn btn-danger" style="background-color:red">Back</button>
				</div>
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
			  var input_valid = validatePassword($(this).val());
	          if(!input_valid){
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
	              $('<small class="text-danger">Password and Confirm password did not match.</small>').insertAfter($(this));
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
        if(password_error || c_password_error || current_password_error){
			if(c_password_error){
				$('<small class="text-danger">Password and Confirm password did not match.</small>').insertAfter($('#confirm_password'));
			}
			if(password_error){
				$('<small class="text-danger">Password should contain one capital letter, one small letter, one number & one special character & must be between 8 and 20 characters.</small>').insertAfter($('#password'));
			}
			if(current_password_error){
				$('<small class="text-danger">This is required field.</small>').insertAfter($('#current_password'));
			}
            e.preventDefault();
        }


      });
});
</script>
<script>
function followFlg(id,fol_unfol){	//alert(fol_unfol);
	var ranking_user_id = id;
	if(fol_unfol=='0'){
		var dataString = 'follower_id='+ranking_user_id;
		$.ajax({
			url: "<?=$this->config->item('base_url')?>profile/unFollow",
			type: "POST",
			data: dataString,
			success: function (res){
				//alert(res);
				var addfol_jsn = JSON.parse(res);
				alert(addfol_jsn.message);
				//alert('Unfollowed Successfully');

				var flag;
				var fol;
				if(fol_unfol=='1'){
					flag=0;
					fol = 'Unfollow';
				}else{
					flag=1;
					fol = 'Follow';
				}
				var tab=document.createElement("a");
				tab.setAttribute("class", "btn btn-"+fol+" btn-block");
				tab.setAttribute("href","javascript:followFlg('"+id+"','"+flag+"')");

				var liText = document.createTextNode('+'+fol);
				tab.appendChild(liText);
				$('.wrapfol_'+id).empty().append(tab);
			}
		});
	}else{
		var dataString = 'follower_id='+ranking_user_id;
		$.ajax({
			url: "<?=$this->config->item('base_url')?>profile/addFollower",
			type: "POST",
			data: dataString,
			success: function (res){
				//alert(res);
				var unfol_jsn = JSON.parse(res);
				alert(unfol_jsn.message);
				//alert('Followed Successfully');
				var flag;
				var fol;
				if(fol_unfol=='1'){
					flag=0;
					fol = 'Unfollow';
				}else{
					flag=1;
					fol = 'Follow';
				}
				var tab=document.createElement("a");
				tab.setAttribute("class", "btn btn-"+fol+" btn-block");
				tab.setAttribute("href","javascript:followFlg('"+id+"','"+flag+"')");

				var liText = document.createTextNode(fol);
				tab.appendChild(liText);
				$('.wrapfol_'+id).empty().append(tab);
			}
		});
	}

}
</script>
