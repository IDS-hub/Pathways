<?php
	if($header->member_img){
		$profile_image =$this->config->item('base_url').'uploads/user/'.$header->member_img;
	}else{
		$profile_image=$this->config->item('base_url').'public/images/no_avatar_full.png';
	}
 ?>
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar" id="main-menu">
   <div class="user-info-wrapper clearfix">
	  <div class="profile-wrapper">
		  <img data-src-retina="<?php echo $profile_image; ?>" data-src="<?php echo $profile_image; ?>" alt="" src="<?php echo $profile_image; ?>" width="70" height="70">
	  </div>
	  <div class="user-info">
		<div class="username"><?php echo $header->first_name; ?> <span class="semi-bold"><?php //echo $userdetails->last_name; ?></span></div>
		<!-- <div class="status"><div class="status-icon green"></div>&nbsp;Online</div> -->
		<!--<div><a href="<?php echo $this->config->item('base_url').'profile/edit'?>"><i class="fa fa-fw fa-edit"></i>&nbsp;Edit Profile</a></div>-->
		<!--<div class="showName"><div class="headtitle">Fraternity Name :</div><p>Lorem Ipsum Dolor Lorem Ipsum Dolor</p>
  </div>-->
	  </div>
	</div>


  <!-- BEGIN MINI-PROFILE -->
  <div class="page-sidebar-wrapper" id="main-menu-wrapper">
	<!-- BEGIN SIDEBAR MENU -->
	<ul>
	  <!--<li <?php if($mainheader=='Email Template') { ?> class="start active open" <?php } ?>> <a href="<?php echo $this->config->item('base_url').'emailtemplate'; ?>"> <span class="glyphicon glyphicon-envelope"></span> &nbsp;<span class="title">Email Template</span><span class="arrow"></span></a>
	  </li>-->
	  <li <?php if(isset($active) && $active=='user') { ?> class="start active open" <?php } ?>><a class="icon-user" href="<?=$this->config->item('base_url')?>admin/user/index/1">  <i class="glyphicon glyphicon-record"></i> <span class="title"> &nbsp;User</span><span class="arrow"></span></a> </li>

	  <li <?php if(isset($active) && $active=='diagnosisrequest') { ?> class="start active open" <?php } ?>><a class="icon-user" href="<?=$this->config->item('base_url')?>admin/diagnosisrequest/index/0">  <i class="glyphicon glyphicon-record"></i> <span class="title"> &nbsp;Diagnosis Request</span><span class="arrow"></span></a> </li>

	  <li <?php if(isset($active) && $active=='quiz') { ?> class="start active open" <?php } ?>><a class="icon-user" href="<?=$this->config->item('base_url')?>admin/quiz/index/0">  <i class="glyphicon glyphicon-record"></i> <span class="title"> &nbsp;Quiz</span><span class="arrow"></span></a> </li>

	</ul>
	<div class="clearfix"></div>
	<!-- END SIDEBAR MENU -->
  </div>
</div>
<!-- END SIDEBAR -->
