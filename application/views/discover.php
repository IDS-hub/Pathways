
<script>

$(document).ready(function(){
	$('.feature-more').click(function(){
		var feature_page = parseInt($('.feature_page').val());
		var dataString = 'page='+feature_page;
		//alert(dataString);
		$.ajax({
			url: "<?=$this->config->item('base_url')?>home/ajaxfeatured",
			type: "POST",
			data: dataString,
			success: function (res){
				var feature_jsn = JSON.parse(res);
				if(feature_jsn.res==''){
					$('.feature-more').hide();
				}
				//alert(res);
				var dataString1 = 'res=' + res;
				$.ajax({
					type: "POST",
					url: "<?=$this->config->item('base_url')?>ajaxfeatured.php",
					data: dataString1,
					success: function(res1){
						//alert(res1);
						$('.streamFeatured').append(res1);
						$('.feature_page').val(feature_page+1);

					}
				});
			}
		});
	});



	$('.new-more').click(function(){
		var new_page = parseInt($('.new_page').val());
		var dataString = 'page='+new_page;
		//alert(dataString);
		$.ajax({
			url: "<?=$this->config->item('base_url')?>home/ajaxNewStreaming",
			type: "POST",
			data: dataString,
			success: function (res){
				var new_jsn = JSON.parse(res);
				if(new_jsn.res==''){
					$('.new-more').hide();
				}
				//alert(res);
				var dataString1 = 'res=' + res;
				$.ajax({
					type: "POST",
					url: "<?=$this->config->item('base_url')?>ajaxNewStreaming.php",
					data: dataString1,
					success: function(res1){
						//alert(res1);
						$('.streamnew').append(res1);
						$('.new_page').val(new_page+1);
					}
				});
			}
		});
	});

	$('.follow-more').click(function(){
		var follow_page = parseInt($('.follow_page').val());
		var dataString = 'page='+follow_page;
		$.ajax({
			url: "<?=$this->config->item('base_url')?>home/ajaxFollowStreaming",
			type: "POST",
			data: dataString,
			success: function (res){
				var follow_jsn = JSON.parse(res);
				if(follow_jsn.res==''){
					$('.follow-more').hide();
				}
				//alert(res);
				var dataString1 = 'res=' + res;
				$.ajax({
					type: "POST",
					url: "<?=$this->config->item('base_url')?>ajaxFollowStreaming.php",
					data: dataString1,
					success: function(res1){
						$('.streamfollow').append(res1);
						$('.follow_page').val(follow_page+1);
					}
				});
			}
		});
	});


	/*$("#search").keypress(function (e) {
		var key = e.which;
		if (key  == 13) {
		  	var val = $(".search").val();
			window.location.href = '<?php echo $this->config->item('base_url')?>search/search/'+val;
			return false;
		}
	});*/

	// $("#search").keypress(function (e) {
	// 	var key = e.which;
	// 	if (key  == 13) {
	// 		$(this).submit();
	// 	}
	// });
});


function check2(){
	var search = document.getElementById('search').value;
	search  = search.toString().trim();
	if(search==''){
		alert('Please enter any keyword');
		return false;
	}
	return true;
}

</script>

<input type="hidden" name="feature_page" class="feature_page" value="<?=(isset($feature_page) && $feature_page!='')?$feature_page:2;?>" />
<input type="hidden" name="new_page" class="new_page" value="<?=(isset($new_page) && $new_page!='')?$new_page:1;?>" />
<input type="hidden" name="follow_page" class="follow_page" value="<?=(isset($follow_page) && $follow_page!='')?$follow_page:2;?>" />

<div class="rgt_container">
  <div class="navbar navbar-inverse topNav">
	<div class="col-xs-6">
	  <form class="globalSearch" method="post" action="<?php echo $this->config->item('base_url');?>search/search" onsubmit="return check2();">
		<i class="material-icons dp48 srchBtn">search</i>
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
	  <div class="col-md-12">
		<div class="row">
			<form action="" method="get" >
				<div class="col-sm-4">
					<h1>Discover</h1>
				</div>
				<div class="col-sm-5">
					<input type="text" class="form-control" name="search" id="search" value="<?php echo $search;?>" placeholder="Search">
				</div>
				<div class="col-sm-3">
					<button type="submit" class="btn" name="button">Search</button>
					<a href="<?php echo $this->config->item('base_url')?>discover" class="btn btn-danger" style="background-color:red" type="button" name="button">Reset</a>
				</div>
			</form>
		</div>

		<div class="tab-content">
		  <div role="tabpanel" class="tab-pane active" id="profile">
			<div class="card_container streamfollow">
				<?php
				//$follow = json_decode($follow);
				//print_r($follow);
				if(count($discover)>0){
					foreach($discover as  $val){ ?>
						<a href="<?php echo $this->config->item('base_url')?>follower/details/<?=$val->id;?>" class="cardDiv">
  						<div class="card">
  						  <div class="card-image">
  							<div class="video-stream-img-holder"><img src="<?=(isset($val->profile_image) && ($val->profile_image!=''))?$val->profile_image:$this->config->item('base_url').'uploads/coverImage/no-image.png';?>"></div>
  						  </div>
  						  <div class="card-content clearfix">

  							<div>
  								<h2 class="userName truncate"><?php echo json_decode('"'.$val->first_name.' '.$val->last_name.'"');?></h2>
  							</div>
  						  </div>
  						</div>
					</a>
				  <?php }
			  }else{ ?>
				  <div class="row text-center text-danger">
					  No record found.
				  </div>
			  <?php } ?>
			</div>

		  </div>

		</div>
		</div>
	</div>
  </div>
</div>
