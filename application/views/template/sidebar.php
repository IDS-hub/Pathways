<div class="sidebar-offcanvas lft_sidebar" id="sidebar" role="navigation">
  <div class="sidebar_logoDiv">
	  <a href="<?=$this->config->item('base_url')?>home">
		  <img src="<?=$this->config->item('base_url')?>public/frontend/images/visuLive_innerLogo.png" alt="" /></div>
	  </a>

	<ul class="nav visuLive_sidebar nav-sidebar">
	  <li><a href="<?=$this->config->item('base_url')?>home" class="<?=($selected=='home')?"selected":'';?>">Dashboard</a></li>
	  <li><a href="<?=$this->config->item('base_url')?>profile" class="<?=($selected=='account')?"selected":'';?>">Profile</a></li>
	  <li><a href="<?=$this->config->item('base_url')?>follower" class="<?=($selected=='follower')?"selected":'';?>">Following/ Follower</a></li>
	  <li><a href="<?=$this->config->item('base_url')?>discover" class="<?=($selected=='discover')?"selected":'';?>">Discover</a></li>
	</ul>
	<div class="footDiv">Powered by VISU LIVE</div>
</div>
