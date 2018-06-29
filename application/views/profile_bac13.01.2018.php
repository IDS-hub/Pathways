<?php
$vpf = json_decode($vpf);
$prof = $vpf->res;
//print_r($prof);
//echo $prof->profile_image;
$balance = json_decode($balance);
$balc = $balance->res;
?>
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script> -->
<script>
$(document).ready(function(){
	$('#blah1').attr('src', 'uploads/coverImage/no-image.png');
	$('#blah2').attr('src', 'uploads/coverImage/no-image.png');
	$('#blah3').attr('src', 'uploads/coverImage/no-image.png');
	$('.profilefrm').hide();
	$('.cancel').hide();
	$('.edit').click(function(){
		$('.profilefrm').show();
		$('.cancel').show();
		$('.profileview').hide();
		$('.edit').hide();
	});
	$('.cancel').click(function(){
		$('.edit').show();
		$('.profileview').show();
		$('.profilefrm').hide();
		$('.cancel').hide();
	});

/////purchase histroy////////
	$('.purchaseAdd').hide();
	$('.purchase-close').hide();

	$('.purchase-add').click(function(){
		$('.purchaseAdd').show();
		$('.purchaseList').hide();
		$('.purchase-close').show();
		$('.purchase-add').hide();
		$('.purchase-more').hide();
	});
	$('.purchase-close').click(function(){
		$('.purchaseList').show();
		$('.purchase-add').show();
		$('.purchase-more').show();
		$('.purchase-close').hide();
		var dataString = 'page=1';
		$.ajax({
			type: "POST",
	                url: "<?=$this->config->item('base_url')?>profile/purchaseHisy",
			data: dataString,
	        success: function(res){
				var dataString1 = 'res=' + res;
				$.ajax({
					type: "POST",
			        url: "<?=$this->config->item('base_url')?>ajaxPurchaseHistroy.php",
					data: dataString1,
			        success: function(res1){
						$('.purchaseList').html(res1);
						$('.purchaseAdd').hide();
						$('.purchase_page').val(2);
					}
				});
			}
		});
	});

	$('.purchase-more').click(function(){
		var purchase_page = parseInt($('.purchase_page').val());
		var dataString = 'page='+purchase_page;
		$.ajax({
			type: "POST",
	        url: "<?=$this->config->item('base_url')?>profile/purchaseHisy",
			data: dataString,
	        success: function(res){
				var fst_jsn = JSON.parse(res);
				//alert(fst_jsn.res);
				var errorcode = fst_jsn.errorcode;
				if(errorcode!=0){
					$('.purchase-more').hide();
				}
				var dataString1 = 'res=' + res;
				$.ajax({
					type: "POST",
			        url: "<?=$this->config->item('base_url')?>ajaxPurchaseHistroy.php",
					data: dataString1,
			        success: function(res1){
						$('.purchaseList').append(res1);
						$('.purchaseAdd').hide();
						$('.purchase_page').val(purchase_page+1);
					}
				});
			}
		});

	});

///////////Income//////////////
	$('.incomeAdd').hide();
	$('.inclome-close').hide();

	// function in end of javascript tag

	$('.inclome-close').click(function(){
		$('.incomeBalance').show();
		$('.inclome-close').hide();
		$('.incomeAdd').hide();
	});

	/*$('.income_sub').click(function(){
		var baladd = $('.baladd').val();
		//alert(baladd);
	});*/

	$('.inc_mob_no').bind('input', 'input:text',function(){
		var inc_mob_no = $('.inc_mob_no').val();
		if(inc_mob_no!=''){
			$('.inc_bank_name').val('');
			$('.inc_acc_no').val('');
			$('.inc_acc_holder').val('');
		}
	});
	$('.inc_bank_name').bind('input', 'input:text',function(){
		var inc_bank_name = $('.inc_bank_name').val();
		if(inc_bank_name!=''){
			$('.inc_mob_no').val('');
			$('.inc_wallet_type').val('');
		}
	});
	$('.income_sub').click(function(){
		var baladd = $('.baladd').val();
		var inc_mob_no = $('.inc_mob_no').val();
		var inc_wallet_type = $('.inc_wallet_type').val();
		var inc_bank_name = $('.inc_bank_name').val();
		var inc_acc_no = $('.inc_acc_no').val();
		var inc_acc_holder = $('.inc_acc_holder').val();

		if(inc_mob_no!='' && inc_wallet_type!=''){
			var pattern = /^[\s()+-]*([0-9][\s()+-]*){10,12}$/;
	        if (!pattern.test(inc_mob_no)) {
	            alert("It is not valid mobile number : "+inc_mob_no);
	            return false;
	        }
			var dataString = 'mycoin='+baladd +'&mob_no='+inc_mob_no +'&wallet_type='+inc_wallet_type+'&bank_name=' + inc_bank_name +'&acc_no='+inc_acc_no+'&acc_holder='+inc_acc_holder;
			$.ajax({
				type: "POST",
				url: "<?=$this->config->item('base_url')?>profile/myIncomeBalance",
				data: dataString,
				success: function(res){
					if(res==1){
						alert('Coin redeemed successfully');
						incmEfct();
						$('.baladd').val('');
						$('.inc_mob_no').val('');
						$('.inc_wallet_type').val('');
						$('.inc_bank_name').val('');
						$('.inc_acc_no').val('');
						$('.inc_acc_holder').val('');

						$('.incomeBalance').show();
						$('.inclome-close').hide();
						$('.incomeAdd').hide();
					}else{
						alert('Failure');
					}
				}
			});
		}else if(inc_bank_name!='' && inc_acc_no!='' && inc_acc_holder!=''){
			var dataString = 'mycoin='+baladd +'&mob_no='+inc_mob_no +'&wallet_type='+inc_wallet_type+'&bank_name=' + inc_bank_name +'&acc_no='+inc_acc_no+'&acc_holder='+inc_acc_holder;
			$.ajax({
				type: "POST",
				url: "<?=$this->config->item('base_url')?>profile/myIncomeBalance",
				data: dataString,
				success: function(res){
					if(res==1){
						alert('Coin redeemed successfully');
						incmEfct();
						$('.baladd').val('');
						$('.inc_mob_no').val('');
						$('.inc_wallet_type').val('');
						$('.inc_bank_name').val('');
						$('.inc_acc_no').val('');
						$('.inc_acc_holder').val('');

						$('.incomeBalance').show();
						$('.inclome-close').hide();
						$('.incomeAdd').hide();
					}else{
						alert('Failure');
					}
				}
			});
		}else{
			alert('Please give the valid data.');
		}
	});

	$('.incm_efct').click(function(){
		incmEfct();
		$('.baladd').val('');
	});

/////////////Feedback////////////////

	$(".feed_type") // select the radio by its id
	.change(function(){ // bind a function to the change event
		if( $(this).is(":checked") ){ // check if the radio is checked
			var val = $(this).val(); // retrieve the value
			//alert(val);
		}
	});

	/*$('.feedback_sub').click(function(){
		var feed_email = $('.feed_email').val();

		var fdbk_image1=$('#fdbk_image1').val();
    	alert(fdbk_image1);
		//e.preventDefault();
		var dataString = 'fdbk_image1='+fdbk_image1;
	    $.ajax({
	        url: "<?=$this->config->item('base_url')?>profile/uploadImg",
	        type: "POST",
			data: dataString,
	        success: function (data){
	            //alert(data);
	            console.log(data);
	        },
	    });
	});*/

	$('#fdbk_image1').change(function(){
        var f = this.files[0];
        if(f.size > 665600){
           alert("Allowed file size exceeded. (Max. 650 KB)");
           this.value = null;
        }
    });
	$('#fdbk_image2').change(function(){
        var f = this.files[0];
        if(f.size > 665600){
           alert("Allowed file size exceeded. (Max. 650 KB)");
           this.value = null;
        }
    });
	$('#fdbk_image3').change(function(){
        var f = this.files[0];
        if(f.size > 665600){
           alert("Allowed file size exceeded. (Max. 650 KB)");
           this.value = null;
        }
    });

///////////ranking////////////////
	$('#spent').hide();
	$('.coinErnCls').click(function(){
		$('#defaultOpen').addClass('active');
		$('.coinGot').html('');
		var ranking_page = 1;
		var typ = 1;
		var dataString = 'type='+typ+'&page='+ranking_page;
		$.ajax({
	        url: "<?=$this->config->item('base_url')?>profile/ranking",
	        type: "POST",
			data: dataString,
	        success: function (res){
	            //alert(res);
				var dataString1 = 'res=' + res+'&typ=' + typ;
				$.ajax({
					type: "POST",
			        url: "<?=$this->config->item('base_url')?>ajaxRanking.php",
					data: dataString1,
			        success: function(res1){	//alert(res1);
						$('.coinGot').append(res1);
					}
				});
	        },
	    });
	});
	// $("#search").keypress(function (e) {
	// 	var key = e.which;
	// 	if (key  == 13) {
	// 		$(this).submit();
	// 	}
	// });


});
function buynow(id){
	$('.prchs_color_'+id).addClass('active');
	var dataString = 'purchase_id=' + id;
	$.ajax({
		type: "POST",
        url: "<?=$this->config->item('base_url')?>profile/ajaxCoinPurchase",
		data: dataString,
        success: function(data){
			alert('Coin added successfully');
		}
	});
}

function checkFeedback(){
	//var feed_type = $("[name='type']:checked").val();
	var feed_email = $('.feed_email').val();
	var feed_dtl = $('.feed_dtl').val();
	if(feed_email!=''){
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{1,4})+$/;
		//alert(regex.test(feed_email));
  		var eml = regex.test(feed_email);
		if(eml===false){
			alert('Please enter valid email address.');
			$('.feed_email').val('');
			return false;
		}
	}else if(feed_email==''){
		alert('Please enter email address');
		return false;
	}
	if(feed_dtl==''){
		alert('Please provide problem description.');
		return false;
	}
	alert('Thank you for your feedback');
	return true;
}


function coin_rank(typ){
	if(typ==1){
		var ranking_page = parseInt($('.ranking_page').val());
		var dataString = 'type='+typ+'&page='+ranking_page;
	}else{
		var ranking_page_spent = parseInt($('.ranking_page_spent').val());
		var dataString = 'type='+typ+'&page='+ranking_page_spent;
	}
	$.ajax({
		url: "<?=$this->config->item('base_url')?>profile/ranking",
		type: "POST",
		data: dataString,
		success: function (res){
			var rank_jsn = JSON.parse(res);
			alert(rank_jsn.res);
			var errorcode = rank_jsn.errorcode;
			$('.data-more').show();
			var dataString1 = 'res=' + res+'&typ=' + typ;
			$.ajax({
				type: "POST",
				url: "<?=$this->config->item('base_url')?>ajaxRanking.php",
				data: dataString1,
				success: function(res1){
					if(typ==1){
						$('.coinGot').append(res1);
						$('.ranking_page').val(ranking_page+1);
						$('.ranking_page_spent').val(1);
						$('.coinSpent').html('');
					}else{
						$('.coinSpent').append(res1);
						$('.ranking_page_spent').val(ranking_page_spent+1);
						$('.ranking_page').val(1);
						$('.coinGot').html('');
					}
					if(errorcode!=0){
						$('.data-more').hide();
					}
				}
			});
		},
	});
}

function openTabs(evt, tabName) {
    var i, tabcontent, tablinks;
	//console.log(evt,tabName);
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks1");
	// console.log(tablinks);
    for (i = 0; i < tablinks.length; i++) {
		console.log(tablinks[i].className);
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

	if(tabName=='got'){
		coin_rank(1);
	}else if(tabName=='spent'){
		coin_rank(0);
	}

}

function  incmEfct(){
	$.ajax({
		type: "POST",
		url: "<?=$this->config->item('base_url')?>profile/incomeBal",
		success: function(res){
			var incm_jsn = JSON.parse(res);
			//alert(incm_jsn.res.coins_earned);
			$('.incm_cnern').text(incm_jsn.res.coins_earned);
			$('.incm_cnext').text(incm_jsn.res.coins_exist);
		}
	});
}

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
				tab.setAttribute("class", "btn btn-"+fol);
				tab.setAttribute("href","javascript:followFlg('"+id+"','"+flag+"')");

				var liText = document.createTextNode(fol);
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
				tab.setAttribute("class", "btn btn-"+fol);
				tab.setAttribute("href","javascript:followFlg('"+id+"','"+flag+"')");

				var liText = document.createTextNode(fol);
				tab.appendChild(liText);
				$('.wrapfol_'+id).empty().append(tab);
			}
		});
	}

}

function check3(){
	var search = document.getElementById('search').value;
	search  = search.toString().trim();
	if(search==''){
		alert('Please enter any keyword');
		return false;
	}
	return true;
}

function openPaymentPage (price,coin){
	console.log(parseInt($('.incm_cnext').text()));
	var coin_exist = parseInt($('.incm_cnext').text());
	var baladd = coin;
	if(baladd > coin_exist){
		alert('Redeem coins is greater then total existing balance');
	}else{
		if(baladd!='' && baladd>0){
			var txt = coin+' Coin  =  $ '+price
			$('.convertion').text(txt);
			$('.incomeAdd').show();
			$('.inclome-close').show();
			$('.incomeBalance').hide();
		}
	}
}
</script>

<!--Mobile No : <?php echo (isset($prof->mob_no)?$prof->mob_no:'');?><br>
Coin Earned : <?php echo $prof->coins_earned;?><br>
Coin Spent : <?php echo $prof->coins_spent;?><br>
Coin Withdrawn : <?php echo $prof->coins_withdrawn;?><br>
Following : <?php echo (isset($prof->following)?$prof->following:'');?><br>
Follower : <?php echo (isset($prof->follower)?$prof->follower:'');?><br>
Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?><br>-->


<input type="hidden" name="purchase_page" class="purchase_page" value="<?=(isset($purchase_page) && $purchase_page!='')?$purchase_page:2;?>" />
<input type="hidden" name="ranking_page" class="ranking_page" value="<?=(isset($ranking_page) && $ranking_page!='')?$ranking_page:2;?>" />
<input type="hidden" name="ranking_page_spent" class="ranking_page_spent" value="<?=(isset($ranking_page_spent) && $ranking_page_spent!='')?$ranking_page_spent:1;?>" />

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
                            <h1 class="main_heading">Profile</h1>
							<?php //echo json_decode('" Roy\uD83D\uDE09\uD83E\uDD17"');?>
							<div class="imageDiv profileview">
								<div class="coverPic_div"><img src="<?=(isset($prof->cover_image) && $prof->cover_image!='')?$prof->cover_image:$this->config->item('base_url').'uploads/coverImage/no-image.png';?>" />
									<div class="profile_pic"><img src="<?=(isset($prof->profile_image) && $prof->profile_image!='')?$prof->profile_image:$this->config->item('base_url').'uploads/user/no-image.png';?>" />

									</div>
									<div class="coverPic_edit btn-xs edit"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;EDIT</div>
								</div>
								<div class="profile_info">

									<div class="profile_name">
										<?php echo json_decode('"'.$prof->first_name.' '.$prof->last_name.'"');?>
										<?php
										if($prof->gender == 'Male'){ ?>
											<img src="<?php echo $this->config->item('base_url')?>public/images/icon/male.png" style="width:20px;height:20px;" />
										<?php }else if($prof->gender == 'Female'){ ?>
											<img src="<?php echo $this->config->item('base_url')?>public/images/icon/female.png" style="width:20px;height:20px;" />
										<?php }else{ ?>
											<img src="<?php echo $this->config->item('base_url')?>public/images/icon/secret.png" style="width:20px;height:20px;" />
										<?php } ?>
									</div>
                                                                        <div class="profile_name">ID : <?php echo json_decode('"'.$prof->id.'"');?></div>
									<div class="profile_name">Coin : <?php echo json_decode('"'.$prof->coins_earned.'"');?></div>
									<div class="profile_name">DOB : <?php echo json_decode('"'.$prof->dob.'"');?></div>
									<div class="profile_name">Mobile Number : <?php echo json_decode('"'.$prof->mob_no.'"');?></div>

									<p>Following : <?php echo (isset($prof->following)?$prof->following:'');?> &nbsp; / &nbsp;  Followers : <?php echo (isset($prof->follower)?$prof->follower:'');?> &nbsp;  /  &nbsp; Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?></p>
								</div>
							</div>

							<div class="imageDiv profilefrm">
								<form action="<?= base_url() ?>profile/update" method="post" accept-charset="utf-8" name="profilefrm" id="profilefrm" enctype="multipart/form-data">
									<div class="coverPic_div">
										<label for="cover_image" class="cover_pic_edit"><i class="fa fa-pencil" aria-hidden="true"></i></label>
										<input type="file" name="cover_image" id="cover_image" onchange="document.getElementById('cover_image1').src = window.URL.createObjectURL(this.files[0])">
										<?php //if(isset($prof->cover_image) && $prof->cover_image!=''){ ?>
											<img id="cover_image1" src="<?=(isset($prof->cover_image) && $prof->cover_image!='')?$prof->cover_image:'uploads/coverImage/no-image.png';?>" width="100"/>
										<?php //} ?>


										<div class="profile_pic">

											<?php //if(isset($prof->profile_image) && $prof->profile_image!=''){ ?>
												<img id="profile_image1" src="<?=(isset($prof->profile_image) && $prof->profile_image!='')?$prof->profile_image:'uploads/user/no-image.png';?>"/>
											<?php //} ?>

											<input type="file" name="profile_image" id="profile_image" onchange="document.getElementById('profile_image1').src = window.URL.createObjectURL(this.files[0])">
											<label for="profile_image" class="profile_pic_edit"><i class="fa fa-pencil" aria-hidden="true"></i></label>
										</div>

										<div class="coverPic_edit btn-xs"><input type="submit" name="upd" value="Update"/><span class="cancel">Cancel</span></div>

									</div>
									<div class="profile_info">
										<div class="profile_name">
											<input type="text" name="profile_name" id="profile_name" value="<?php echo json_decode('"'.$prof->first_name.' '.$prof->last_name.'"');?>"/>
											<div class="text-danger">
												<p>
												    <input name="gender" type="radio" value="Male" id="test1" <?php echo ($prof->gender == 'Male')?'checked':'';?> />
												    <label for="test1">Male</label>
											    </p>
											    <p>
											      	<input name="gender" type="radio" value="Female" id="test2" <?php echo ($prof->gender == 'Female')?'checked':'';?> />
											      	<label for="test2">Female</label>
											    </p>
												<p>
											      	<input name="gender" type="radio" value="Secret" id="test3" <?php echo ($prof->gender == 'Secret')?'checked':'';?> />
											      	<label for="test3">Secret</label>
											    </p>
											</div>
											<!-- <input type="text" name="gender"  id="gender" value="< ?php echo json_decode('"'.$prof->gender.'"');?>"/> -->


											<input type="date" name="dob"  id="dobEdit" value= "<?php echo date("Y-m-d", strtotime($prof->dob));?>"/>
										</div>
										<p>Following : <?php echo (isset($prof->following)?$prof->following:'');?> &nbsp; / &nbsp;  Followers : <?php echo (isset($prof->follower)?$prof->follower:'');?> &nbsp;  /  &nbsp; Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?></p>
									</div>
								</form>
							</div>


                              <div class="panel-group collapseDiv" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title clearfix incm_efct">
                                      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="clearfix">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/income_icon.png" /></span>Wallet
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
										<i class="fa fa-times inclome-close right" aria-hidden="true"></i>
										<div class="incomeBalance">
											<!-- Total Coin Earned : <span class="incm_cnern">< ?php echo $balc->coins_earned;?></span><br> -->
											<div class="row text-center">
												Existing Balance : <img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" /><span class="incm_cnext"><?php echo $balc->coins_exist;?></span><br>
											</div>

  										  	<!-- <input type="number" class="baladd" name="baladd" >
											<button class="btn incsub">SUBMIT</button> -->
											</br>
											<!---<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp; 70000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(32,70000);" style="width: 100px;" ><b>$ 32</b></button>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp; 700000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(325,700000);" style="width: 100px;" ><b>$ 325</b></button>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp;3500000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(1625,3500000);" style="width: 100px;" ><b>$ 1625</b></button>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp;7000000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(3300,7000000);" style="width: 100px;" ><b>$ 3300</b></button>
												</div>
											</div>
  									 	</div>
										<div class="incomeAdd">
											<span class="convertion"></span><br><br>
											<select class="inc_wallet_type" name="wallet_type" style="display:block;">
												<option value="">Select your Wallet</option>
												<option value="Vodafone">Vodafone</option>
												<option value="Pay U Money">Pay U Money</option>
												<option value="Airtel Money">Airtel Money</option>
												<option value="Paytm">Paytm</option>
											</select><br>
											<input type="number" class="inc_mob_no" name="mob_no" placeholder="Mobile No.">
											OR <br>
											<input type="text" class="inc_bank_name" name="bank_name" placeholder="Bank Name">
											<input type="text" class="inc_acc_no" name="acc_no" placeholder="Account No.">
											<input type="text" class="inc_acc_holder" name="acc_holder" placeholder="Account Holder Name">
											<button class="btn income_sub">SUBMIT</button>
										</div>----->
                                    </div>
                                  </div>
                                </div>

                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingTwo">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/purshased_history_icon.png" /></span>Purchase History
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
                                    <div class="panel-body">
										<!-- <i class="fa fa-plus purchase-add" aria-hidden="true"></i>
										<i class="fa fa-times purchase-close purchsCls" aria-hidden="true"></i>-->
										<?php $purchaseHist = json_decode($purchaseHist); //echo '<pre>'; print_r($purchaseHist);?>
										<div class="row purchaseList">
											<div class="col s12">
												<div><h4>Amount<h4></div>
												<div><h4>Date<h4></div>
											</div>
											<?php
												$purchaseHistory = $purchaseHist->res;
												if($purchaseHist->errorcode!=1){
													foreach ($purchaseHistory as $val){
											?>
													<div class="col s12">
														<div><img src="<?=$this->config->item('base_url')?>public/images/icon/coin_icon.png" style="height: 25px;" />&nbsp;<?php echo $val->amount;?></div>
														<div><?php echo $val->created;?></div>
													</div>
											<?php
													}
											?>
											<?php
												}else{
													?>
													<div class="col s12">
														<h5 class="no-record center"><?php echo 'No Record Found.';?></h5>
													</div>
													<?php
												}
											?>
										</div>
										<?php if($purchaseHist->errorcode!=1 && $purchaseHist->prchis_end!=1){ ?>
											<div class="row right">
												<div class="col s12"><a class="page-link purchase-more" href="javascript:void(0);">More</a></div>
											</div>
										<?php } ?>
										<div class="purchaseAdd">
											<?php
												$purchaseList = json_decode($purchaseList);
												$purchaseL = $purchaseList->res;
												if($purchaseList->errorcode!=1){
													foreach ($purchaseL as $val){
											?>
													<div><?php echo $val->coin_no;?></div>
													<div><?php echo $val->description;?></div>
													<button class="btn-xs btn-primary prchs_color_<?php echo $val->pid;?>" onclick="javascript:buynow(<?php echo $val->pid;?>);">Buy Now</button>

											<?php
													}
												}
											?>
										</div>
                                    </div>
                                  </div>
                                </div>
                                 <!-----------Added menu by Rakesh on 05.01.2018-------->
								
                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingTen">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/withdraw.png" height="33" width="30"></span>Withdrawn
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseTen" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTen">
                                    <div class="panel-body">
										<i class="fa fa-times inclome-close right" aria-hidden="true"></i>
										<div class="incomeBalance">
											<!-- Total Coin Earned : <span class="incm_cnern">< ?php echo $balc->coins_earned;?></span><br> -->
											<div class="row text-center">
												Existing Balance : <img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" /><span class="incm_cnext"><?php echo $balc->coins_exist;?></span><br>
											</div>

  										  	<!-- <input type="number" class="baladd" name="baladd" >
											<button class="btn incsub">SUBMIT</button> -->
											</br>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp; 70000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(32,70000);" style="width: 100px;" ><b>$ 32</b></button>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp; 700000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(325,700000);" style="width: 100px;" ><b>$ 325</b></button>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp;3500000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(1625,3500000);" style="width: 100px;" ><b>$ 1625</b></button>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<img src="<?=$this->config->item('base_url')?>public/images/icon/diamond.png" style="height: 20px;" />&nbsp;7000000
												</div>
												<div class="col-sm-6">
													<button class="btn" onclick="openPaymentPage(3300,7000000);" style="width: 100px;" ><b>$ 3300</b></button>
												</div>
											</div>
  									 	</div>
										<div class="incomeAdd">
											<span class="convertion"></span><br><br>
											<select class="inc_wallet_type" name="wallet_type" style="display:block;">
												<option value="">Select your Wallet</option>
												<option value="Vodafone">Vodafone</option>
												<option value="Pay U Money">Pay U Money</option>
												<option value="Airtel Money">Airtel Money</option>
												<option value="Paytm">Paytm</option>
											</select><br>
											<input type="number" class="inc_mob_no" name="mob_no" placeholder="Mobile No.">
											OR <br>
											<input type="text" class="inc_bank_name" name="bank_name" placeholder="Bank Name">
											<input type="text" class="inc_acc_no" name="acc_no" placeholder="Account No.">
											<input type="text" class="inc_acc_holder" name="acc_holder" placeholder="Account Holder Name">
											<button class="btn income_sub">SUBMIT</button>
										</div>
                                    </div>
                                </div>
								

                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title coinErnCls">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/ranking_icon.png" /></span>Star
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
										<ul class="nav nav-tabs nav-justified">
											  <li role="presentation" class="tablinks1 active" id="defaultOpen" onclick="javascript:openTabs(event, 'got')"><a href="javascript:void(0)" >COIN GOT</a></li>
											  <li role="presentation" class="tablinks1 " onclick="javascript:openTabs(event, 'spent')"><a href="javascript:void(0)">COIN SPENT</a></li>
										</ul>
									    <!-- <div class="tab">

										  <button class="btn tablinks"  id="defaultOpen">Coin Got</button>
										  <button class="btn tablinks" onclick="javascript:openTabs(event, 'spent')">Coin Spent</button>
										</div> -->

										<div id="got" class="tabcontent">
											  <div class="coinGot"></div>
											  <!-- <a class="data-more" href="javascript:coin_rank('1');">More</a> -->
										</div>

										<div id="spent" class="tabcontent">
											  <div class="coinSpent"></div>
											  <!-- <a class="data-more" href="javascript:coin_rank('0');">More</a> -->
										</div>

                                    </div>
                                  </div>
                                </div>

								<div class="panel">
                                  <div class="panel-heading" role="tab" id="headingThree">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/lavel_icon.png" /></span>Level
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                                    <div class="panel-body">
                                      <div class="row">
										  <div class="col-sm-12">
											  <img style="width: 100%;" src="<?=$this->config->item('base_url')?>public/images/icon/stars_icon.png">
											  <span style="position: absolute; top: 47%; left: 50%; font-size: 26px; font-weight: 900; color: black;"><?php echo $prof->level;?></span>
										  </div>

										  <!-- <h4 class="profile-Level">Level < ?php echo $prof->level;?></h4> -->
									  </div>
									  <div class="row text-center">
										  <span>Your Level</span>
									  </div>
                                    </div>
                                  </div>
                                </div>

								<div class="panel">
								  <div class="panel-heading" role="tab" id="headingEight">
									<h4 class="panel-title">
									  <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
										<span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/lavel_icon.png" /></span>Settings
									  </a>
									</h4>
								  </div>
								  <div id="collapseEight" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingEight">
									<div class="panel-body">
									  <div class="row">
										  <!-- <h4 class="profile-Level">Settings <?php //echo $prof->level;?></h4> -->

											<div class="panel-body feedback-form">
												<ul class="list-group">
													<li class="list-group-item">
														<a href="<?=$this->config->item('base_url')?>privacy_policy">
															Privacy Policy
															<span style="float:right">
																<img src="<?=$this->config->item('base_url')?>public/images/icon/arrow.png" style="height: 20px;" />
															</span>
														</a>
													</li>
													<li class="list-group-item">
														<a href="<?=$this->config->item('base_url')?>terms_and_conditions">
															Terms & Conditions
															<span style="float:right">
																<img src="<?=$this->config->item('base_url')?>public/images/icon/arrow.png" style="height: 20px;" />
															</span>
														</a>
													</li>
													<li class="list-group-item">
														<a href="<?=$this->config->item('base_url')?>about_us">
															About Us
															<span style="float:right">
																<img src="<?=$this->config->item('base_url')?>public/images/icon/arrow.png" style="height: 20px;" />
															</span>
														</a>
													</li>
												</ul>
												<!-- <div class="row">
													<div class="col s12">
														<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>&nbsp;&nbsp;<a target="_blank" href="< ?=$this->config->item('base_url')?>privacy_policy">Privacy Policy</a>
													</div>
													<div class="col s12">
														<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>&nbsp;&nbsp;<a target="_blank" href="< ?=$this->config->item('base_url')?>terms_and_conditions">Terms & Conditions</a>
													</div>
													<div class="col s12">
														<span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>&nbsp;&nbsp;<a target="_blank" href="< ?=$this->config->item('base_url')?>about_us">About Us</a>
													</div>
												 </div> -->
											</div>
									  </div>
									</div>
								  </div>
								</div>
                                <!--<div class="panel">
                                  <div class="panel-heading" role="tab" id="headingSix">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/settings_icon.png" /></span>Settings
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseSix" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSix">
                                    <div class="panel-body">
                                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                  </div>
							  </div>-->
                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingSeven">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/feedback_icon.png" /></span>Feedback
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                                    <div class="panel-body feedback-form">
										<?php if($this->session->flashdata('fdbk_msg')): ?>
										  <div class="row text-center" style="color:#0aae0a; font-weight:800;"><?php echo $this->session->flashdata('fdbk_msg'); ?></div>
										<?php endif; ?>
										<?php //print_r($_REQUEST);?>
								<form action="<?=$this->config->item('base_url')?>profile/feedback" method="post" onsubmit="return checkFeedback();"  name="feedbackfrm" id="feedbackfrm" enctype="multipart/form-data">
									<div class="row">
										<div class="col s6">
										  <input type="radio" class="feed_type" name="type" checked  value="RECHARGE" id="r1">
										  	<label for="r1">RECHARGE</label>
										</div>
										<div class="col s6">
										  <input type="radio" class="feed_type" name="type" value="APP ERROR" id="r2">
										  	<label for="r2">APP ERROR</label>
										</div>
									  <div class="col s6">
										  <input type="radio" class="feed_type" name="type" value="OPERATION" id="r3">
										  <label for="r3">OPERATION</label>
									  </div>
									<div class="col s6">
										  <input type="radio" class="feed_type" name="type" value="SUGGESTION" id="r4">
										  <label for="r4">SUGGESTION</label>
									  </div>
								  </div>
                                      <div class="row">
                                          <div class="col s12">
                                              <h4>Contact <small class="text-danger">*</small></h4>
                                              <input type="text" class="feed_email" name="feed_email" placeholder="email address">
                                          </div>
                                      </div>
									  <div class="row">
										  <div class="col s12">
										  <h4>Problem Description <small class="text-danger">*</small></h4>
										  <input type="text" class="feed_dtl" name="feed_dtl" placeholder="Enter your problem details.">
                                          </div>
                                      </div>
									  <div class="row uploded uploded-img">
										  <div class="col s12">
										  <h4>Upload Screenshot</h4>
										  <!--<input type="file" id="fdbk_image1" name="fdbk_image1">
										  <input type="file" id="fdbk_image2" name="fdbk_image2">
										  <input type="file" id="fdbk_image3" name="fdbk_image3">-->
                                          </div>
                                          <div class="col s12 fileContainer">
                                              <div class="row">
                                                  <div class="col s6 inpyt-btn-holder">
                                                  <label for="fdbk_image1" class="">
                                                    Choose file
                                                  </label>
                                                  <input type="file" id="fdbk_image1" name="fdbk_image1"  onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                                  </div>
                                                  <div class="col s6">
                                                  	<img id="blah1"/>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col s12 fileContainer">
                                              <div class="row">
                                                  <div class="col s6 inpyt-btn-holder">
                                                  <label for="fdbk_image2" class="">
                                                    Choose file
                                                  </label>
                                                  <input type="file" id="fdbk_image2" name="fdbk_image2"  onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                  </div>
                                                  <div class="col s6">
                                                  	<img id="blah2"/>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="col s12 fileContainer">
                                              <div class="row">
                                                  <div class="col s6 inpyt-btn-holder">
                                                  <label for="fdbk_image3" class="">
                                                    Choose file
                                                  </label>
                                                  <input type="file" id="fdbk_image3" name="fdbk_image3"  onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                  </div>
                                                  <div class="col s6">
                                                  	<img id="blah3"/>
                                                  </div>
                                              </div>
                                          </div>

									  </div>
								  </div>
								  <div class="row text-center">
									  <div class="col-sm-12">
									  	<small class="text-danger">User can upload 3 pictures altogether with the size of each one under 650KB</small>
									  </div>
								  </div>
									  <div class="row">
										  <div class="col-sm-12">
											  <button type="submit" class="btn btn-default btn-block" style="background-color:#000; color:#fff;">SUBMIT</button>
										  </div>
									  </div>
								  </div>
								</form>



									  <!--<input id="visa" type="radio" name="credit-card" value="visa" />
									  <label class="drinkcard-cc visa" for="visa"></label>
									  <input id="mastercard" type="radio" name="credit-card" value="mastercard" />
									  <label class="drinkcard-cc mastercard"for="mastercard"></label>-->
                                    </div>



                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
<script>
$(document).ready(function() {
	// $('#dobEdit').val(dateTime);
	//document.getElementById("dobEdit").value = "2014-02-09";
    $('#profilefrm').on('submit',function(e){
		$('#profile_name').next('small').remove();
		$('#dobEdit').next('small').remove();

        var name = $('#profile_name').val();
        var dob = $('#dobEdit').val();

		if(!name || !dob){
			if(!name){
				$('<small class="text-danger">Please enter profile name.</small>').insertAfter($('#profile_name'));
			}
			if(!dob){
				$('<small class="text-danger">Please enter dob.</small>').insertAfter($('#dobEdit'));
			}
			e.preventDefault();
		}
    });
});
</script>
