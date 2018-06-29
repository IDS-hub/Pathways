<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
   <head>
		<meta property="og:url" content="http://ec2-35-171-140-84.compute-1.amazonaws.com/rakesh/pathways_static_shareLink/facebook.html" />
		<style>
			#dvLoading
			{
			   background:#000 url(tenor.gif) no-repeat center center;
			   height: 150px;
			   width: 150px;
			   position: fixed;
			   z-index: 1000;
			   left: 50%;
			   top: 12%;
			   margin: -25px 0 0 -25px;
			}
			h2 {
				text-align: center;
				color:blue;
			}
			h1 {
				text-align: center;
				background-color:powderblue;
			}
		</style>
	</head>
	<body>
		<div id="dvLoading"></div>

		<div>
			<h2>please wait </h2>
		</div>
	</body>
</html>
<script>
	//console.log(location);
	//console.log(navigator);
	//console.log(navigator.userAgent);
	setTimeout(function(){
	//alert("Hello");
	//return false;
		var uagent = navigator.userAgent.toLowerCase();
		alert(uagent);
		if (uagent.search("iphone") > -1){
			alert('iphone');
			window.location = "https://itunes.apple.com/us/app/APPNAME/id1270021728";
		}else if(uagent.search("android") > -1){
			alert('android');
			window.location = "https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate";
		}else if(uagent.search("ipad") > -1){
			alert('ipad');
			window.location = "https://itunes.apple.com/us/app/APPNAME/id1270021728";
		}else if(uagent.search("ipod") > -1){
			alert('ipod');
			window.location = "https://itunes.apple.com/us/app/APPNAME/id1270021728";
		}else if(uagent.search("blackberry") > -1){
			alert('blackberry');
			window.location = "https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate";
		}else if(uagent.search("windows phone") > -1){
			alert('windows phone');
			window.location = "https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate";
		}else if(uagent.search("windows") > -1){
			alert('windows');
			//window.location = "https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate";
			window.location = "http://www.google.com";
		}
	}, 3000);
</script>

<?php


//<meta property="og:url" content="https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate" />
	/*
	set_time_limit(2);
	$userAgent = $_SERVER["HTTP_USER_AGENT"];
	//$device = '';

	if( stristr($userAgent,'windows') ) {
		//$device = "Windows";
		header("Location: https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate");
	} else if( stristr($userAgent,'iphone') || strstr($userAgent,'iphone') ) {
		//$device = "iphone";
		header("Location: https://itunes.apple.com/us/app/APPNAME/id1270021728");
	} else if( stristr($userAgent,'blackberry') ) {
		//$device = "blackberry";
		header("Location: https://itunes.apple.com/us/app/APPNAME/id1270021728");
	} else if( stristr($userAgent,'android') ) {
		//$device = "android";
		header("Location: https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate");
	}else{
		header("Location: https://itunes.apple.com/us/app/APPNAME/id1270021728");
	}
	exit;*/

?>
