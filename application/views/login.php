<!DOCTYPE html>
<html lang="en">
  <head>
	<link rel="icon" href="<?= base_url() ?>public/images/favicon.ico">
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no">
<!--new-Add-js-12-02-2018-->
	<link rel="stylesheet" href="<?= base_url() ?>public/frontend/css/visu.min.css">
	<!--new-Add-js-12-02-2018#End-->
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
	<!--new-Add-js-12-02-2018-->
	<style>
            .menu{  
               
                position: fixed;
                top: 0px;
                width: 100%;
                z-index: 999;
                text-align: center;
                box-sizing: border-box;
    background: rgba(255, 255, 255, 1);
    box-shadow: 5px 5px 2px rgba(23, 36, 52, .05);
    -webkit-transition: all .6s cubic-bezier(.165, .84, .44, 1);
    -moz-transition: all .6s cubic-bezier(.165, .84, .44, 1);
    transition: all .6s cubic-bezier(.165, .84, .44, 1);
            }
            .menu ul li{
                float: left;
                color: #000;
                font-size: 16px;
               padding: 0px 10px;
            }
            .menu ul li a{
                color: #000;
                line-height: 60px;
font-family: 'Oxygen', sans-serif; font-size:14px;
                font-weight: 800;

            }
            .active_menu{
                background: #C632FF; /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #C632FF, #8D00C4); /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #C632FF, #8D00C4); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
          
                color: #fff !important;
            }
            .active_menu a{
                color: #fff !important;
            }
        </style>
		<!--new-Add-js-12-02-2018#END-->
  </head>
  <body>
  
  <!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-sm">
    <!-- Modal content-->
    <div class="modal-content">
      <h3 style="background-color: #8D00C4; color: #fff; width: 100%; padding: 10px; font-size: 16px; margin-top: 0px;"><i class="fa fa-sign-in"></i> Login Via</h3>

      <div class="modal-body">

        <div class="form-box" style="margin-top: 0px;">
                            
                          
                            <div class="social-login" style="margin-top: 0px;">       
                                
                                <div class="social-login-buttons" style="width: 100%; margin-top: 0px; text-align:center;">
                                
                                    <p style="font-size:14px; color:#9b9898; margin-bottom:20px; ">Scan the QR Code from the mobile application & login.</p>
                                    <!--<img src="<?= base_url() ?>public/frontend/img/unnamed.png" alt="" height="100">-->
									<img src="<?= base_url() ?>login/print_qr/<?=$rnd ?>" alt=""/>
                                </div>
                            </div>
                            
                        </div>
      </div>
      <div class="modal-footer">
        <button type="button" style="height: auto; line-height: 30px; padding:0 20px; " class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<div class="menu">
    <div class="container">
  <ul>
    <li><img style="float: left;" src="<?= base_url() ?>public/frontend/img/logo.png" alt="logo" height="60"></li>
    <li class="active_menu"><a href="#" ><i class="fa fa-home"></i> Home</a></li>
    <li><a href="#" type="button" data-toggle="modal" data-target="#myModal"> Recharge</a></li>
    <li style="float: right;">
	<a href="#" type="button" data-toggle="modal" data-target="#myModal"><i class="fa fa-sign-in"></i> Login</a>
	
	

	</li>
  </ul>
  </div>
  </div>

        
        <div id="fullpage">

            <section class="section section1 fp-section">
                
                <div class="container-fluid section-content container-1">
                    <div class="content-wrapper">
                        <div class="logo-title">
                            <img src="<?= base_url() ?>public/frontend/img/logo.png" alt="logo">
                            <div class="mi-title">Visu Live</div>
                        </div>
                        <div class="title">LIVE EVERY MOMENT</div>
                        <div class="btns clear">
                            <a href="#"><div class="btnweb ios-btn"></div></a>
                            <a href="#"><div class="btnweb google-btn"></div></a>
                            <a href="#"><div class="btnweb and-btn"></div></a>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section section2 fp-section">
                <div class="container-fluid section-content container-2">
                    <div class="content-wrapper">
                        <div class="section2-left-phone-img">
                            <!--<img src="./img/home_phone1.png" alt="">-->
                            <div class="l-ear"></div>
                            <div class="r-ear"></div>
                        </div>
                        <div class="section2-right-content">
                            <p class="big-title section2-title">Live Selfie Mask</p>
                            <p class="sub-title section2-line1">Try lovely Selfie Mask and you will find</p>
                            <p class="sub-title section2-line2">everything in the magic wand!</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section section3 fp-section">
                <div class="container-fluid section-content container-3">
                    <div class="content-wrapper">
                        <div class="section3-left-content">
                            <p class="big-title section3-title">Live Life Live</p>
                            <p class="sub-title section3-line1">Share your life, ideas and talent</p>
                            <p class="sub-title section3-line2">with people just like you all over</p>
                            <p class="sub-title section3-line3">the world!</p>
                        </div>
                        <div class="section3-right-phone-img">
                           <!-- <img src="./img/home_phone2.png" alt="">-->
                            <div class="img-3"></div>
                            <ul class="dialogs">
                                <li class="section3-dialog-1-li"><div class="dialog-1"></div></li>
                                <li class="section3-dialog-2-li"><div class="dialog-2"></div></li>
                                <li class="section3-dialog-3-li"><div class="dialog-3"></div></li>
                                <li class="section3-dialog-4-li"><div class="dialog-4"></div></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section section4 fp-section">
                <div class="container-fluid section-content container-4">
                    <div class="content-wrapper">
                        <div class="section4-left-phone-img">
                           <!-- <img src="./img/home_phone3.png" alt="">-->
                            <div class="img-4"></div>
                            <ul class="dialogs">
                                <li class="section4-dialog-1-li"><div class="dialog-1"></div></li>
                                <li class="section4-dialog-2-li"><div class="dialog-2"></div></li>
                                <li class="section4-dialog-3-li"><div class="dialog-3"></div></li>
                            </ul>
                        </div>
                        <div class="section4-right-content">
                            <p class="big-title section4-title">Stunning Gifts</p>
                            <p class="sub-title section4-line1">Help your favourite broadcasters trend</p>
                            <p class="sub-title section4-line2">with likes and fantastic localized gifts!</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section section5 fp-section">
                <div class="container-fluid section-content container-5">
                    <div class="content-wrapper">
                        <div class="section5-left-content">
                            <p class="big-title section5-title">Share</p>
                            <p class="sub-title section5-line1">Invite your friends and followers</p>
                            <p class="sub-title section5-line2">to watch, interact and chat!</p>
                        </div>
                        <div class="section5-right-phone-img">
                             <div class="social-phone-icons">
                                 <div class="wh-phone-icon phone-icon"></div>
                                 <div class="fb-phone-icon phone-icon"></div>
                                 <div class="tw-phone-icon phone-icon"></div>
                                 <div class="we-phone-icon phone-icon"></div>
                                 <div class="mo-phone-icon phone-icon"></div>
                             </div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="section section6 fp-section">
                <div class="container-fluid section-content container-6">
                    <div class="content-wrapper" style="text-align:center;">
                        <div class="follow-us-on section6-title">Follow us on</div>
                        <div class="follow-icons section6-icons">
                            <div class="facebook last-icon"></div>
                            <div class="twiter last-icon"></div>
                            <div class="instagram last-icon"></div>
                        </div>
                        <div class="copyright">Copyright &copy; 2017-2018 visu.live All Rights Reserved </div>
                    </div>
                </div>
            </section>
        </div>
        <ul class="cb-slideshow">
            <li><img src="<?= base_url() ?>public/frontend/img/home_bg1.jpg" alt="" class="bg"></li>
            <li><img src="<?= base_url() ?>public/frontend/img/home_bg2.jpg" alt="" class="bg"></li>
            <li><img src="<?= base_url() ?>public/frontend/img/home_bg3.jpg" alt="" class="bg"></li>
            <li><img src="<?= base_url() ?>public/frontend/img/home_bg4.jpg" alt="" class="bg"></li>
            <li><img src="<?= base_url() ?>public/frontend/img/home_bg5.jpg" alt="" class="bg"></li>
            <li><img src="<?= base_url() ?>public/frontend/img/home_bg6.jpg" alt="" class="bg"></li>
        </ul>
        <div class="arrow-wrapper">
            <div class="arrow bounce"></div>
        </div>
	<!--<section class="mainContainer">
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
	</section>-->

	<!-- jQuery first, then Tether, then Bootstrap JS. -->
	<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>
	<script src="<?= base_url() ?>public/frontend/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<!--new-Add-js-12-02-2018-->
	<script src="<?= base_url() ?>public/frontend/js/visu.util.js"></script>
    <script src="<?= base_url() ?>public/frontend/js/index.min.js"></script>
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
