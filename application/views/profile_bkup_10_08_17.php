
<?php
//echo '<pre>';
$vpf = json_decode($vpf);
 //print_r($vpf->res);
$prof = $vpf->res;
?>
<?php /*?>
<br>
<span class="edit">Edit</span>
<span class="cancel">Cancel</span>
<br>

<div class="profileview">
Name : <?php echo $prof->first_name.' '.$prof->last_name;?><br>
Profile Image : <?php if(isset($prof->profile_image) && $prof->profile_image!=''){ ?><img src="<?=isset($prof->profile_image)?$prof->profile_image:'';?>"
	width="100"/><?php } ?><br>
Cover Image : <?php if(isset($prof->cover_image) && $prof->cover_image!=''){ ?><img src="<?=isset($prof->cover_image)?$prof->cover_image:'';?>"
	width="100"/><?php } ?><br>
</div>

<div class="profilefrm">
<form action="<?= base_url() ?>profile/update" method="post" accept-charset="utf-8" name="profilefrm" id="profilefrm" enctype="multipart/form-data">
Name : <input type="text" name="profile_name" value="<?php echo $prof->first_name.' '.$prof->last_name;?>"/><br>

Profile Image <input type="file" name="profile_image"><?php if(isset($prof->profile_image) && $prof->profile_image!=''){ ?><img src="<?=isset($prof->profile_image)?$prof->profile_image:'';?>"
	width="100"/><?php } ?><br>
Cover Image <input type="file" name="cover_image"><?php if(isset($prof->cover_image) && $prof->cover_image!=''){ ?><img src="<?=isset($prof->cover_image)?$prof->cover_image:'';?>"
	width="100"/><?php } ?><br>
<input type="submit" name="upd" value="Update"/>
</form>
</div>
<br>
Following : <?php echo (isset($prof->following)?$prof->following:'');?><br>
Follower : <?php echo (isset($prof->follower)?$prof->follower:'');?><br>
Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?><br>
<?php */?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>
$(document).ready(function(){
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
});
</script>

<!--Mobile No : <?php echo (isset($prof->mob_no)?$prof->mob_no:'');?><br>
Coin Earned : <?php echo $prof->coins_earned;?><br>
Coin Spent : <?php echo $prof->coins_spent;?><br>
Coin Withdrawn : <?php echo $prof->coins_withdrawn;?><br>
Following : <?php echo (isset($prof->following)?$prof->following:'');?><br>
Follower : <?php echo (isset($prof->follower)?$prof->follower:'');?><br>
Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?><br>-->


				<div class="rgt_container">
					<div class="navbar navbar-inverse topNav">
					  <div class="col-xs-6">
						<form class="globalSearch">
						  <i class="material-icons dp48">search</i>
						  <input type="text" class="form-control" placeholder="Search User">
						</form>
					  </div>
					  <ul class="nav cust-nav navbar-nav navbar-right">
						  <li class="dropdown dropdown-user" id="logout_menu">
							  <a href="javascript:void(0);" class="dropdown-toggle pad-tb-5" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
								  <span class="profile-pic-holder marg-t-0 marg-r-10"><img alt="" src="<?=$res['profile_image']?>"></span>
								  <span class="username username-hide-on-mobile marg-t-10 cust-username ng-binding" style="display:inline-block;"><?=$res['first_name'].' '.$res['last_name'];?></span>
								  <i class="fa fa-angle-down" style="position: relative; top: -4px;"></i>
							  </a>
							  <ul class="dropdown-menu dropdown-menu-default">
								  <li>
									  <a href="<?=$this->config->item('base_url')?>home/logout">
										  <i class="icon-key"></i> Log Out
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
                            <h1 class="main_heading">My Account</h1>

							<div class="imageDiv profileview">
								<div class="coverPic_div"><img src="<?=isset($prof->cover_image)?$prof->cover_image:$this->config->item('base_url').'public/frontend/images/coverPic.png';?>" />
									<div class="profile_pic"><img src="<?=isset($prof->profile_image)?$prof->profile_image:$this->config->item('base_url').'public/frontend/images/profilePic.png';?>" />

									</div>
									<div class="coverPic_edit btn-xs edit"><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Change</div>
								</div>
								<div class="profile_info">
									<div class="profile_name"><?php echo $prof->first_name.' '.$prof->last_name;?></div>
									<p>Following : <?php echo (isset($prof->following)?$prof->following:'');?> &nbsp; / &nbsp;  Followers : <?php echo (isset($prof->follower)?$prof->follower:'');?> &nbsp;  /  &nbsp; Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?></p>
								</div>
							</div>

							<div class="imageDiv profilefrm">
								<form action="<?= base_url() ?>profile/update" method="post" accept-charset="utf-8" name="profilefrm" id="profilefrm" enctype="multipart/form-data">
									<div class="coverPic_div">
										<input type="file" name="cover_image"><?php if(isset($prof->cover_image) && $prof->cover_image!=''){ ?><img src="<?=isset($prof->cover_image)?$prof->cover_image:'';?>"
										width="100"/><?php } ?>
										<div class="profile_pic">
											<?php if(isset($prof->profile_image) && $prof->profile_image!=''){ ?><img src="<?=isset($prof->profile_image)?$prof->profile_image:'';?>"
											/><?php } ?><input type="file" name="profile_image">
											<div class="profile_pic_edit"><i class="fa fa-pencil" aria-hidden="true"></i></div>
										</div>
										<div class="coverPic_edit btn-xs cancel"><input type="submit" name="upd" value="Update"/><i class="fa fa-pencil" aria-hidden="true"></i>&nbsp;Cancel</div>

									</div>
									<div class="profile_info">
										<div class="profile_name"><input type="text" name="profile_name" value="<?php echo $prof->first_name.' '.$prof->last_name;?>"/></div>
										<p>Following : <?php echo (isset($prof->following)?$prof->following:'');?> &nbsp; / &nbsp;  Followers : <?php echo (isset($prof->follower)?$prof->follower:'');?> &nbsp;  /  &nbsp; Fans : <?php echo (isset($prof->fans)?$prof->fans:'');?></p>
									</div>
								</form>
							</div>


                              <div class="panel-group collapseDiv" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingOne">
                                    <h4 class="panel-title clearfix">
                                      <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne" class="clearfix">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/income_icon.png" /></span>Income
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                    <div class="panel-body">
                                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
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
                                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
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
                                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                  </div>
                                </div>
                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingFour">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/ranking_icon.png" /></span>Ranking
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
                                    <div class="panel-body">
                                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                  </div>
                                </div>

                                <div class="panel">
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
                                </div>
                                <div class="panel">
                                  <div class="panel-heading" role="tab" id="headingSeven">
                                    <h4 class="panel-title">
                                      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                        <span class="iconDiv"><img src="<?=$this->config->item('base_url')?>public/frontend/images/feedback_icon.png" /></span>Feedback
                                      </a>
                                    </h4>
                                  </div>
                                  <div id="collapseSeven" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingSeven">
                                    <div class="panel-body">
                                      Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>
