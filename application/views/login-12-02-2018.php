<!DOCTYPE html>
<html lang="en">
  <head>
	<link rel="icon" href="<?= base_url() ?>public/images/favicon.ico">
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="<?= base_url() ?>public/frontend/node_modules/bootstrap/dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>public/frontend/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?= base_url() ?>public/frontend/css/style.css">
	<style>
	.before-login-footer {}
	.before-login-footer ul { float:left;  }
	.before-login-footer li { display:inline-block; margin:0 10px;}
        .before-login-footer li:first-child { margin-right: 0;}
	.before-login-footer li a { color:#fff; font-size:1.4rem;}
        .before-login-footer li a:hover { color:#e8adff; }
	</style>
  </head>
  <body>
	<section class="mainContainer">
	  <div class="container">
		<div class="row">
		  <div class="col-md-8 col-xs-12 col-md-offset-2">
			<div class="mainDiv">
			  <div class="qr_container">
				<div class="logo_Container"><img src="<?= base_url() ?>public/frontend/images/visuLife_logo.png" alt="" /></div>
				  <div class="row">
					<div class="col-md-6 col-sm-6">
					  <div class="qrDiv"><img src="<?= base_url() ?>login/print_qr/<?=$rnd ?>" alt=""/></div>
					</div>
					<div class="col-md-6 col-sm-6">
					  <p><?php //echo $rnd;?> <b>Scan the QR Code from the mobile application & login.</b></p>
					  <ul class="iconList">
						<li><span><i class="fa fa-apple" aria-hidden="true"></i></span>iOS version</li>
						<li><span><i class="fa fa-android" aria-hidden="true"></i></span>Android version</li>
					  </ul>
					</div>
				  </div>
			  </div>
			</div>
			<footer>
			 <div class="row">
                          <div class="col-sm-7">
                            <div class="before-login-footer">
                            <ul>
                                  <li><a href="<?= base_url() ?>privacy_policy" target="_blank">Privacy Policy</a></li>
                                  <li><a href="<?= base_url() ?>terms_and_conditions" target="_blank">Terms & Conditions</a></li>
                                  <li><a href="<?= base_url() ?>about_us" target="_blank">About Us</a></li>
                            </ul>
                            </div>
                         </div>
                          <div class="col-sm-5">
                              <div style="text-align:right">
                               Powered by VISU LIVE
                              </div>
                          </div> 
                         </div>
			</footer>
		  </div>
		</div>
	  </div>
	</section>

	<!-- jQuery first, then Tether, then Bootstrap JS. -->
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
	<script src="<?= base_url() ?>public/frontend/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
   	 var rnd = <?=$rnd ?>;
   	 <?php $url = site_url('login/windowLogin');?>
       function chkLogin(){
   		$.ajax({
   			url:"<?php echo $url;?>",
   			type: "POST",
   			data: {"rnd":rnd},
   			success: function(repons){
   				//console.log(repons);
   				if(repons > 0){
   					//alert(repons);
   					window.location.href = '<?=base_url()?>home/index/'+repons;
   				}else{
   					setTimeout(function(){ chkLogin(); }, 10000);
   				}
          	}
   		});
   		
       }
   	chkLogin();
   });
   </script>

  </body>
</html>
