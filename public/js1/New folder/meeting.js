(function($) {
    var mainContainerHeight,
        wraperHeight = $('#wrapper').height();
		console.log(wraperHeight);
    if (wraperHeight <= 630) {
        mainContainerHeight = 630;
    } else {
        mainContainerHeight = wraperHeight;
    }
    $('.main-container').css({
        height: mainContainerHeight
    });
    $('#video-container').css({
        width: $('.main-container').width(),
        height: $('.main-container').height()
    });
    $(function() {
        var resolution = "360p",
            channel =roomId, //Cookies.get("roomName"),   // room key
            role = 'audience', //Cookies.get("clientRole"),  // 'audience'
            remoteStreamList = [],
            client = AgoraRTC.createClient(),
            uid,
            localStream,
            lastLocalStreamId,
            divCounter = 0,
            broadcastStreamID=0,
            isMixed = false;
			console.log(channel);
			console.log(roomId);
        /* Joining channel */
        (function initAgoraRTC() {
			client.on('error', function(err) {
				console.log(err);
				if (err.reason === 'INVALID_CHANNEL_NAME') {
					$.alert("Invalid channel name, Chinese characters are not allowed in channel name.");
				}
			});
			client.init('73c12740f3404f56b0903a5b5ad79e88', function (obj) {
				console.log("AgoraRTC client initialized");
				client.join(null,channel, undefined, function(uid) {
					console.log("User " + uid + " join channel successfully");
					console.log("Timestamp: " + Date.now());

				},function(err){
					console.log('error');
					console.log(err);
				});
			});
        }());

        subscribeStreamEvents();
        $("#room-name-meeting").html(channel);

        function displayStream(tagId, stream, width, height, className, parentNodeId) {
            $("#demoName").hide();
            console.log('displayStream function called.');
            console.log(divCounter);
            console.log('--------------');
            console.log(tagId, stream, width, height, className, parentNodeId);
            var $container;
            if (divCounter===0) {
                $container = $("#full-screen-video");
                className = "video-gallery-box last-video";
                //$container = $("#" + parentNodeId);
                broadcastStreamID=stream.getId();
            }
            else {
                //$container = $("#full-screen-video");
                $container = $("#small-screen-video");
                if(divCounter === 1){
                    className = "video-gallery-box last-video";
                }else{
                    className = "video-gallery-box";
                }

            }
            console.log(parentNodeId);
            // mixed mode
            console.log(isMixed);
            /*if (isMixed) {
                width = 192;
                height = 120;
                className = 'video-item';
            } else {
                className += ' video-item';
            }*/
            console.log(stream.getId());

            //var styleStr = 'width:' + width + 'px; height:' + height + 'px;';
            var styleStr ="";
            console.log(className.indexOf('local-partner-video'));
            if (className.indexOf('local-partner-video') > -1) {
                var videoWidth = $('#wrapper').height() * 4 / 3;
                var right = (1200 - videoWidth) / 2 + 12;
                styleStr += 'top:12px; right:' + right + 'px;';
            }

            $container.append('<div id="' + tagId + stream.getId() + '" class="' + className + '" data-stream-id="' + stream.getId() + '" style="' + styleStr + '"></div>');
            stream.play(tagId + stream.getId());
            divCounter ++;


            // $("#" + tagId + stream.getId()).css();
        }

        function deleteDiv(screenId){
            console.log("deleteDiv function called");
            console.log(screenId);
            var hideDivName = "#agora-remote"+screenId;
            $(hideDivName).hide();
            if(broadcastStreamID===screenId){
                 alert("Broadcaster has stopped the live streaming.");
                 window.location = "http://ec2-34-195-90-14.compute-1.amazonaws.com/VisuLive/home";
            }
        }

        function showStreamOnPeerAdded(stream) {
            var size;
            //alert(stream);
            console.log("showStreamOnPeerAdded function called.");
            console.log("remoteStreamList : "+remoteStreamList.length);
            console.log("remoteStreamListArray : "+remoteStreamList);
            size = calculateVideoSize(false);
            displayStream("agora-remote", stream, size.width, size.height, '');
            //toggleExpensionButton(true);
            $("div[id^='bar_']").remove();
        }

        function subscribeStreamEvents() {
            console.log("subscribeStreamEvents function called.");
            client.on('stream-added', function(evt) {
                console.log("stream-added function called.");
                var stream = evt.stream;
                console.log("New stream added: " + stream.getId());
                console.log("Timestamp: " + Date.now());
                console.log("Subscribe ", stream);
                client.subscribe(stream, function(err) {
                    console.log("Subscribe stream failed", err);
                });
            });

            client.on('peer-leave', function(evt) {
                //alert(evt.uid);
                console.log("peer-leave function called.");
                deleteDiv(evt.uid);
                console.log("Peer has left: " + evt.uid);
                console.log("Timestamp: " + Date.now());
                console.log(evt);

            });

           //client.on('onMessageChannelReceive', function(account, uid, msg) {
                    //alert("hellll");
                   // console.log("Chanel receive message:");
                    //console.log('client.onMessageChannelReceive ' + account + ' ' + uid + ' : ' + msg);
            //});

            client.on('stream-subscribed', function(evt) {
                console.log("stream-subscribed function called.");
                var stream = evt.stream;
		console.log(stream);
                console.log("Got stream-subscribed event");
                console.log("Timestamp: " + Date.now());
                console.log("Subscribe remote stream successfully: " + stream.getId());
                console.log(evt);
                showStreamOnPeerAdded(stream);
            });

            client.on("stream-removed", function(evt) {
                console.log("stream-removed function called.");
                var stream = evt.stream;
                console.log("Stream removed: " + evt.stream.getId());
                console.log("Timestamp: " + Date.now());
                console.log(evt);
            });
        }



        function getResolutionArray(reso) {
	    console.log(reso);
            console.log("getResolutionArray function called.");
            switch (reso) {
                case "120p":
                    return [160, 120];
                case "240p":
                    return [320, 240];
                case "360p":
                    return [640, 360];
                case "480p":
                    return [848, 480];
				case "640p":
                    return [640, 480];
                case "720p":
                    return [1280, 720];
                case "1080p":
                    return [1920, 1080];
                default:
                    return [1280, 720];
            }
        }

        function calculateVideoSize(multiple) {
            console.log("calculateVideoSize function called.");
            var viewportWidth = $(window).width(),
                viewportHeight = $(window).height(),
                curResolution = getResolutionArray(resolution),
                width,
                height,
                newWidth,
                newHeight,
                ratioWindow,
                ratioVideo;

            if (multiple) {
                width = viewportWidth / 2 - 50;
                height = viewportHeight / 2 - 40;
            } else {
                width = viewportWidth - 100;
                height = viewportHeight - 80;
            }
            ratioWindow = width / height;
            ratioVideo = curResolution[0] / curResolution[1];
            if (ratioVideo > ratioWindow) {
                // calculate by width
                newWidth = width;
                newHeight = width * curResolution[1] / curResolution[0];
            } else {
                // calculate by height
                newHeight = height;
                newWidth = height * curResolution[0] / curResolution[1];
            }

            newWidth = Math.max(newWidth, 160);
            newHeight = Math.max(newHeight, 120);
            return {
                width: newWidth,
                height: newHeight
            };
        }
    });
}(jQuery));
