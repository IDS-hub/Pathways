<div class="logWrapper">
  	<div class="logHeader" style="height:86px;">
        <a class="logo" href="<?php echo $this->config->item('base_url');  ?>"><img src="<?php echo $this->config->item('css_images_js_base_url'); ?>images/logo.jpg" alt=""></a>
    </div>
	<div class="container_confirmation">
    	
<?php
if($type == 'VERIFICATION')
{
	if($msg == 'SUCCESS')
	{
?>
		<div class="clearfix">
            <h2 class="greenTxt">Thanks for verifying your email address.</h2>
            <div class="sep-12"></div>
        </div>
        
<?php
}}
	
?>

	</div>
<div class="push"></div>
</div>
