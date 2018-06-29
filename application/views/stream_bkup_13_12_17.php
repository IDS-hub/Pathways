
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
	<div class="col m8 offset-m2 s10 offset-s1 striming-sec">
		<img width="30" height="30" src="<?=(isset($profile_image) && ($profile_image!=''))?$this->config->item('base_url').'uploads/user/'.$profile_image:'../../uploads/user/no-image.png';?>"/>
		<span><?=isset($user_name)?ucwords(json_decode('"'.$user_name.'"')):'';?></span>
	</div>
	<div class="col m8 offset-m2 s10 offset-s1 striming-sec">
			<video id="video"></video>
	</div>
	<div class="col m8 offset-m2 s10 offset-s1 striming-sec">
		<input type="hidden" name="stream_id" class="stream_id" value="<?=isset($stream_id)?$stream_id:'';?>"/>
		<input type="hidden" name="stream_url" class="stream_url" value="<?=isset($stream_url)?$stream_url:'';?>"/>
		<input type="hidden" name="tokbox_session_id" class="tokbox_session_id" value="<?=isset($tokbox_session_id)?$tokbox_session_id:'';?>"/>

		<h4>Viewer :</h4>
		
		<div class="streamVwr"></div>
	</div>
</div>

<!-- Chat:<br>
<div class="streamChatH"></div> -->




<script>
  if(Hls.isSupported()) {
    var video = document.getElementById('video');
    var hls = new Hls();
	//hls.loadSource('https://cdn-broadcast002-pdx.tokbox.com/19298/19298_a18591f8-1ef6-4dbf-83cc-6f682a5c9433.smil/playlist.m3u8');
	hls.loadSource('<?=isset($stream_url)?$stream_url:'';?>');
    hls.attachMedia(video);
    hls.on(Hls.Events.MANIFEST_PARSED,function() {
      video.play();
  });
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
	var dataString = 'stream_id='+stream_id+'&page=1';
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
				success: function(res1){	//alert(res1);
					$('.streamVwr').empty().append(res1);
				}
			});
		}
	});


	var tokbox_session_id = $('.tokbox_session_id').val();
	var dataString2 = 'tokbox_session_id='+tokbox_session_id+'&time='+dateTime;
	$.ajax({
		type: "POST",
		url: "<?=$this->config->item('base_url')?>stream/strmChatHist",
		data: dataString2,
		success: function(res){
			//alert(res);
			//console.log(res);
			var dataString1 = 'res=' + res;
			$.ajax({
				type: "POST",
				url: "<?=$this->config->item('base_url')?>ajaxStreamChatHistory.php",
				data: dataString1,
				success: function(res1){	//alert(res1);
					$('.streamChatH').empty().append(res1);
				}
			});
		}
	});


}
strmVwr();
setInterval(strmVwr, 5000);
</script>

<script>
/*function strmVwr(){
	console.log('dddd');
}
strmVwr();
var interval = setInterval(strmVwr, 200);
setTimeout(function() {
    clearInterval(interval)
}, 400);*/
</script>
