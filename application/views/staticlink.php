<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="https://www.facebook.com/2008/fbml">
   <head>
		<meta property="og:url" content="https://pathways.health/public/ststic_share_link/facebook.html" />
		<style>
			#dvLoading
			{
			   background:#000 url(/public/ststic_share_link/65-A-Soft-Close.png) no-repeat center center;
			   height: 450px;
			   width: 450px;
			   position: fixed;
			   z-index: 1000;
			   left: 50%;
			   top: 50%;
			   margin: -225px 0 0 -225px;
			}
			h2 {
				text-align: center;
				color:blue;
				margin-top: 100px;
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
		//alert(uagent);
	    //alert(navigator.userAgent.indexOf('Mac OS X'));
		if (uagent.search("iphone") > -1){
			//alert('iphone');
			window.location = "https://itunes.apple.com/app/id1388251688";
		}else if (navigator.userAgent.indexOf('Mac OS X') != -1){
			//alert('MAC');
			window.location = "https://itunes.apple.com/app/id1388251688";
		}
		else if(uagent.search("ipad") > -1){
			//alert('ipad');
			window.location = "https://itunes.apple.com/app/id1388251688";
		}else if(uagent.search("ipod") > -1){
			//alert('ipod');
			window.location = "https://itunes.apple.com/app/id1388251688";
		}else if(uagent.search("android") > -1){
			//alert('android');
			window.location = "https://play.google.com/store/apps/details?id=com.pathways.pathwayspainrelief";
		}

		else if(uagent.search("Tablet") > -1){
			//alert('Tablet');
			window.location = "https://play.google.com/store/apps/details?id=com.pathways.pathwayspainrelief";
		}
		else if(uagent.search("blackberry") > -1){
			//alert('blackberry');
			window.location = "https://play.google.com/store/apps/details?id=com.pathways.pathwayspainrelief";
		}else if(uagent.search("windows phone") > -1){
			//alert('windows phone');
			window.location = "https://play.google.com/store/apps/details?id=com.pathways.pathwayspainrelief";
		}else if(uagent.search("windows") > -1){
			//alert('windows');
			//window.location = "https://play.google.com/store/apps/details?id=com.mymatenateapps.mymatenate";
			window.location = "https://play.google.com/store/apps/details?id=com.pathways.pathwayspainrelief";
		}
	}, 5000);
</script>
