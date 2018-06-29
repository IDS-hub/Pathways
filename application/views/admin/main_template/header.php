<!DOCTYPE html>
<html>
<head>
<!--<link rel="icon" href="<?=$this->config->item('base_url')?>public/images/favicon.ico">-->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<meta charset="utf-8" />
<title>Pathways<?php if(isset($active)){echo ' - '.ucfirst($active);}?><?php //echo $site_name.' : '.$mainheader; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta content="" name="description" />
<meta content="" name="author" />


<link href="<?=$this->config->item('base_url')?>public/plugins/jquery-slider/css/jquery.sidr.light.css" rel="stylesheet" type="text/css" media="screen"/>
<!-- BEGIN CORE CSS FRAMEWORK -->
<link href="<?=$this->config->item('base_url')?>public/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="<?=$this->config->item('base_url')?>public/dist/css/formValidation.css"/>

<link href="<?=$this->config->item('base_url')?>public/css/font-awesome.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/animate.min.css" rel="stylesheet" type="text/css"/>
<!-- END CORE CSS FRAMEWORK -->

<!-- BEGIN CSS TEMPLATE -->
<link href="<?=$this->config->item('base_url')?>public/css/style.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/custom.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/custom-icon-set.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/magic_space.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/tiles_responsive.css" rel="stylesheet" type="text/css"/>
<link href="<?=$this->config->item('base_url')?>public/css/switch_on_off.css" rel="stylesheet" type="text/css"/>



<!-- BEGIN CSS DATEPICKER -->
<link href="<?=$this->config->item('base_url')?>public/datepicker/bootstrap-datetimepicker.css" rel="stylesheet" type="text/css"/>
<!-- END CSS DATEPICKER -->

<!-- BEGIN FANCYBOX CSS -->
<link rel="stylesheet" type="text/css" href="<?=$this->config->item('base_url')?>public/fancybox/source/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" type="text/css" href="<?=$this->config->item('base_url')?>public/fancybox/source/helpers/jquery.fancybox-buttons.css?v=1.0.5" />
<link rel="stylesheet" type="text/css" href="<?=$this->config->item('base_url')?>public/fancybox/source/helpers/jquery.fancybox-thumbs.css?v=1.0.7" />
<!-- BEGIN FANCYBOX CSS  -->

<!-- <script src="<?php echo $this->config->item('css_images_js_base_url'); ?>js/jquery-1.8.3.min.js" type="text/javascript"></script> -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/dist/js/formValidation.js"></script>
</head>
<!-- END HEAD -->


<!-- BEGIN BODY -->
<body class="">
<?php
	if($header->member_img){
		$profile_image =$this->config->item('base_url').'uploads/user/'.$header->member_img;
	}else{
		$profile_image=$this->config->item('base_url').'public/images/no_avatar_full.png';
	}
 ?>
<!-- BEGIN HEADER -->
<div class="header navbar navbar-inverse ">
  <!-- BEGIN TOP NAVIGATION BAR -->
  <div class="navbar-inner">

	<?php /*if($this->session->userdata('storid')!='') {?><span id="backid1" style="display:block;"><a class="pull-letf backtoadmin backtoadmin2" href="<?php //echo $this->config->item('base_url').'stores/' ?>"><span class="glyphicon glyphicon-chevron-left"></span> Back to Super Admin</a> </span><?php }*/?>
    <div class="header-seperation">
      <ul class="nav pull-left notifcation-center" id="main-menu-toggle-wrapper" style="display:none">
        <li class="dropdown"> <a id="main-menu-toggle" href="#main-menu"  class="" >
          <div class="iconset top-menu-toggle-white"></div>
          </a> </li>
      </ul>
      <!-- BEGIN LOGO -->
      <!--<div class="logo"><a href="<?php echo $this->config->item('base_url').'admin/user/index/1'; ?>"><img src="<?=$this->config->item('base_url')?>public/images/logo.gif" alt=""  data-src="<?=$this->config->item('base_url')?>public/images/logo.gif" data-src-retina="<?=$this->config->item('base_url')?>public/images/logo.gif"/></a></div>-->
      <!-- END LOGO -->
      <div class="pull-right mob-profile"> <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
        <div class="profile-picGray">

			<img src="<?php echo $profile_image; ?>"  alt="" data-src="<?php echo $profile_image; ?>" data-src-retina="<?php echo $profile_image; ?>" width="35" height="35" /></div>
        <div class="iconset-mob top-down-arrow"></div>
        </a>
        <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
          <li><a href="<?php //echo $this->config->item('base_url').'profile'; ?>"> My Profile</a> </li>
		  <?php
			if($this->session->userdata('type')!='user')
			{
		  ?>
          <!--<li><a href="<?php //echo $this->config->item('base_url').'settings'; ?>"> Settings</a></li>-->
		  <?php }?>
          <li class="divider"></li>
          <li><a onclick="javascript:logout();" href="javascript:void(0);" ><i class="fa fa-power-off"></i> &nbsp;&nbsp;Sign Out</a></li>
        </ul>
      </div>
    </div>
    <!-- END RESPONSIVE MENU TOGGLER -->
    <div class="header-quick-nav" >
      <!-- BEGIN TOP NAVIGATION MENU -->
      <div class="pull-left">
        <ul class="nav quick-section">
          <li class="quicklinks"> <a href="#" class="" id="layout-condensed-toggle" >
            <div class="iconset top-menu-toggle-dark"></div>
            </a>
          </li>
        </ul>
        <ul class="nav quick-section">
          <!--<li class="m-r-10 input-prepend inside search-form no-boarder"> <span class="add-on"> <span class="iconset top-search"></span></span>
            <input name="" type="text"  class="no-boarder " placeholder="Search Dashboard" style="width:250px;">
          </li>-->
        </ul>
      </div>
      <!-- END TOP NAVIGATION MENU -->
      <!-- BEGIN CHAT TOGGLER -->
      <div class="pull-right">
        <div class="chat-togglerdpd">
		  <?php /*if($this->session->userdata('storid')!='') {?><span id="backid" style="display:block;"><a class="pull-letf backtoadmin" href="<?php //echo $this->config->item('base_url').'stores/' ?>" style="color:yellow"><span class="glyphicon glyphicon-chevron-left"></span> Back to Super Admin</a></span> <?php }*/?>
          <ul class="nav quick-section pull-right">
            <li class="quicklinks">
              <div class="profile-pic">
				<span><img src="<?php echo $profile_image; ?>"  alt="" data-src="<?php echo $profile_image; ?>" data-src-retina="<?php echo $profile_image; ?>" /></span>
			  </div>
              <a data-toggle="dropdown" class="dropdown-toggle  pull-right " href="#" id="user-options">
              <!--<div class="iconset top-settings-dark "></div>-->
			  <?php
					if($this->session->userdata('type')=='Admin')
					{
				?>
              <div class="user-details">
                <div class="username"><span class="bold"><?php echo $header->first_name; ?></span> <?php //echo $userdetails->last_name; ?> </div>
              </div>
			  <?php
					}else{
			  ?>
			  <div class="user-details">
                <div class="username"><span class="bold"><?php //echo stripslashes($userdetails->store_name); ?></div>
              </div>
			  <?php
		  			}
			  ?>
              <div class="iconset top-down-arrow"></div>
              </a>
              <ul class="dropdown-menu  pull-right" role="menu" aria-labelledby="user-options">
			    <?php
				if($this->session->userdata('type')!='user')
					{
				?>
                <li><a href="<?=$this->config->item('base_url')?>admin/member"><span class="glyphicon glyphicon-user"></span> &nbsp;My Profile</a></li>
				<?php
					}else{
					$editurl=$this->uri->segment(3)!='' ? $this->config->item('base_url').'dashboard/add-edit/'.$userdetails->id.'/'.$this->uri->segment(3) : $this->config->item('base_url').'dashboard/add-edit/'.$userdetails->id;
				?>
				 <li><a href="<?php //echo $editurl; ?>"><span class="glyphicon glyphicon-user"></span> &nbsp;Store Profile</a></li>

				<?php
				    }
				/*	if($this->session->userdata('type')!='user')
					{
				?>
					<li><a href="<?php //echo $this->config->item('base_url').'settings'; ?>"><span class="glyphicon glyphicon-cog"></span> &nbsp;Settings</a></li>
				<?php } */?>
                <li class="divider"></li>
                <li><a href="<?=$this->config->item('base_url')?>admin/login/logout"><i class="fa fa-power-off"></i> Sign Out</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
      <!-- END CHAT TOGGLER -->
    </div>
    <!-- END TOP NAVIGATION MENU -->
  </div>
  <!-- END TOP NAVIGATION BAR -->
</div>
<!-- END HEADER -->



<!-- BEGIN CONTAINER -->
<div class="page-container row-fluid">


	<?php $this->load->view('admin/main_template/sidebar');?>


  <!-- BEGIN PAGE CONTAINER-->
   <div class="page-content">
	 <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	 <div id="portlet-config" class="modal hide">
	   <div class="modal-header">
		 <button data-dismiss="modal" class="close" type="button"></button>
		 <h3>Widget Settings</h3>
	   </div>
	   <div class="modal-body"> Widget settings form goes here </div>
	 </div>
	 <div class="clearfix"></div>
