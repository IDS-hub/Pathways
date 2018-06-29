<script>
	var roomId = '<?php echo $stream_id;?>';
	console.log(roomId);
</script>
<!-- vertex shader -->
<script id="2d-vertex-shader" type="x-shader/x-vertex">attribute vec2 a_position; attribute vec2 a_texCoord; uniform vec2 u_resolution; varying vec2 v_texCoord; void main() { // convert the rectangle from pixels to 0.0 to 1.0 vec2 zeroToOne = a_position / u_resolution; // convert from 0->1 to 0->2 vec2
	zeroToTwo = zeroToOne * 2.0; // convert from 0->2 to -1->+1 (clipspace) vec2 clipSpace = zeroToTwo - 1.0; gl_Position = vec4(clipSpace * vec2(1, -1), 0, 1); // pass the texCoord to the fragment shader // The GPU will interpolate this value between
	points. v_texCoord = a_texCoord; }</script>
<!-- fragment shader -->
<script id="2d-fragment-shader" type="x-shader/x-fragment">precision mediump float; // our texture uniform sampler2D u_image; // the texCoords passed in from the vertex shader. varying vec2 v_texCoord; void main() { gl_FragColor = texture2D(u_image, v_texCoord); }</script>
<script id="2d-yuv-shader" type="x-shader/x-fragment">precision mediump float; uniform sampler2D Ytex; uniform sampler2D Utex,Vtex; varying vec2 v_texCoord; void main(void) { float nx,ny,r,g,b,y,u,v; mediump vec4 txl,ux,vx; nx=v_texCoord[0]; ny=v_texCoord[1]; y=texture2D(Ytex,vec2(nx,ny)).r; u=texture2D(Utex,vec2(nx,ny)).r;
	v=texture2D(Vtex,vec2(nx,ny)).r; //" y = v;\n"+ y=1.1643*(y-0.0625); u=u-0.5; v=v-0.5; r=y+1.5958*v; g=y-0.39173*u-0.81290*v; b=y+2.017*u; gl_FragColor=vec4(r,g,b,1.0); }</script>

<?php
$date = new DateTime(null, new DateTimezone("Asia/Kolkata"));
$time = $date->format('Y-m-d').' '.$date->format('H:i:s');
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>

	<div class="striming-page">
	<div class="col m8 offset-m2 s10 offset-s1">
			<a class="btn" href="<?=$this->config->item('base_url').'stream/back';?>">Back</a>
	</div>
	<div class="col m8 offset-m2 s10 offset-s1 striming-sec">
			<h4 class="Page-Header">Live Streaming</h4>
	</div>
	<div class="col m8 offset-m2 s10 offset-s1 striming-sec stream-all">
                
            <div class="stream-name-box">
		<img width="30" height="30" src="<?=(isset($profile_image) && ($profile_image!=''))?''.$profile_image:'../../uploads/user/no-image.png';?>"/>
		<span><?=isset($user_name)?ucwords(json_decode('"'.$user_name.'"')):'';?></span>
            </div>
           <div class="streamVwr"></div>
           <div class="star-section">
              <div class="star-section-mid">
               <span class="star-section-left"><img src="<?=$this->config->item('base_url')?>public/frontend/images/Star-app.png"></span>
               <span class="star-section-right"> <?=isset($balance)?$balance:'';?> </span>
              </div>
           </div>
        </div>
            
         
       
         
	<!-- <div class="col m8 offset-m2 s10 offset-s1 striming-sec">
			<video id="video"></video>

	</div> -->
	<div id="wrapper" class="col m8 offset-m2 s10 offset-s1 striming-sec">
        <!-- <div class="room-name-title" id="room-name-meeting"></div> -->
        <div class="main-container">
            <div id="video-container-parent">
                <div id="video-container" class="video-container">
                    <div id="video-main-div" class="video-gallery-all">
                        <div id="full-screen-video" class="video-gallery-left">
                                
                        </div>
                        <div id="demoName">
                            <div class="row text-center">
                                    <span class="text-danger"><h1>Loading...</h1></span>

                            </div>
                        </div>

                        <div id="small-screen-video" class="video-gallery-right">

                                

                        </div>
                </div>
                    <!-- <div class="toolbar">
                        <ul>
                            <li>
                                <a class="switch-audio-button" href="#"><img src="images/btn_voice@2x.png" alt="Voice"></a>
                            </li>
                            <li>
                                <a class="mute-button" href="#"><img src="images/btn_mute@2x.png" alt="Mute"></a>
                            </li>
                            <li style="display: none">
                                <a class="fullscreen-button" style="display: none" href="#"><img style="display: none" src="images/btn_maximize@2x.png" alt="Fullscreen"></a>
                            </li>
                            <li style="display: none">
                                <a class="expension-button" style="display: none" href="#"><img style="display: none" src="images/btn_expansion.png" alt="Switch"></a>
                            </li>
                            <li>
                                <a class="end-call-button" href="#"><img src="images/btn_endcall@2x.png" alt="End"></a>
                            </li>
                        </ul>
                    </div> -->
                </div>
            </div>
            <div class="video-side-bar">
                <div class="video-operation-bar">
					<span class="video-operation-btn list-switch-audio-btn"></span>
					<span class="video-operation-btn list-hang-up-btn"></span>
					<span class="video-operation-btn list-close-btn"></span>
				</div>
            </div>
            <div class="info"></div>
        </div>
    </div>
	<div id="roomInfoModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <p>No remote stream yet&hellip;, :(</p>
                </div>
                <div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

	<div class="col m8 offset-m2 s10 offset-s1 striming-sec">
		<input type="hidden" name="stream_id" class="stream_id" value="<?=isset($stream_id)?$stream_id:'';?>"/>
		<input type="hidden" name="stream_url" class="stream_url" value="<?=isset($stream_url)?$stream_url:'';?>"/>
		<input type="hidden" name="tokbox_session_id" class="tokbox_session_id" value="<?=isset($tokbox_session_id)?$tokbox_session_id:'';?>"/>
                <input type="hidden" name="balance" class="balance" value="<?=isset($balance)?$balance:'';?>"/>

		<!---<h4>Viewer :</h4>
		<div class="streamVwr"></div>--->

                <h4>Chat :</h4>
                <div class="streamChatH"> </div>
                 <div class="panda-absolute">
		  <img src="<?=$this->config->item('base_url')?>public/frontend/images/visuLive_innerLogo.png" alt="">
                 </div>
               
	</div>
</div>
<!--<div class="streamChatH"></div>-->

<!----->

    <!-- /.modal -->
    <!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->

    <script src="<?=$this->config->item('base_url')?>public/js1/vendor-bundle.js?v=1.13.0"></script>
    <script src="<?=$this->config->item('base_url')?>public/js1/AgoraRTCSDK-1.13.0.js"></script>
    <script src="<?=$this->config->item('base_url')?>public/js1/meeting.js?v=1.13.0"></script>
	<!-- <script src="js/AgoraRTCSDK-1.14.0.js"></script>
    <script src="js/meeting.js?v=1.14.0"></script> -->
    <script>
        if ('addEventListener' in window) {
            window.addEventListener('load', function() {
                document.body.className = document.body.className.replace(/\bis-loading\b/, '');
            });
            document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
        }
    </script>
	<script>
	function getDateTime() {
	    var now     = new Date();
	    var year    = now.getFullYear();
	    var month   = now.getMonth()+1;
	    var day     = now.getDate();
	    var hour    = now.getHours()-1;
	    var minute  = now.getMinutes();
	    var second  = now.getSeconds();
	    if(month.toString().length == 1) {
	        var month = '0'+month;
	    }
	    if(day.toString().length == 1) {
	        var day = '0'+day;
	    }
	    if(hour.toString().length == 1) {
	        var hour = '0'+hour;
	    }
	    if(minute.toString().length == 1) {
	        var minute = '0'+minute;
	    }
	    if(second.toString().length == 1) {
	        var second = '0'+second;
	    }
	    var dateTime = year+'-'+month+'-'+day+' '+hour+':'+minute+':'+second;
	     return dateTime;
	}
	var dateTime = getDateTime();
	function strmVwr(){
		//console.log('dddddd');
		var stream_id = $('.stream_id').val();
                //alert(stream_id);
		var dataString = 'stream_id='+stream_id+'&page=1';
                //alert(dataString);
		$.ajax({
			type: "POST",
			url: "<?=$this->config->item('base_url')?>stream/streamViewer",
			data: dataString,
			success: function(res){
				//alert(res);
				var dataString1 = 'res=' + res;
				$.ajax({
					type: "POST",
					url: "<?=$this->config->item('base_url')?>ajaxStreamViewer.php",
					data: dataString1,
					success: function(res1){
                                                //alert(res1);
						$('.streamVwr').empty().append(res1);
					}
				});
			}
		});


		//var tokbox_session_id = $('.tokbox_session_id').val();
                //alert(tokbox_session_id);

                var stream_id = $('.stream_id').val();

		//var dataString2 = 'tokbox_session_id='+tokbox_session_id+'&time='+dateTime;

                var dataString2 = 'stream_id='+stream_id+'&time='+dateTime;
                //alert(dataString2);
		$.ajax({
			type: "POST",
			url: "<?=$this->config->item('base_url')?>stream/strmChatHist",
			data: dataString2,
			success: function(res11){
				//alert(res11);
				//console.log(res1);
				var dataString11 = 'res11=' + res11;
				$.ajax({
					type: "POST",
					url: "<?=$this->config->item('base_url')?>ajaxStreamChatHistory.php",
					data: dataString11,
					success: function(res22){
                                                //alert(res22);
						$('.streamChatH').empty().append(res22);
					}
				});
			}
		});


	}
	strmVwr();
	setInterval(strmVwr, 5000);
	</script>
