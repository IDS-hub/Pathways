(function($) {
    var mainContainerHeight,
        wraperHeight = $('#wrapper').height();
    if (wraperHeight <= 768) {
        mainContainerHeight = 768;
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
        var resolution = "720p",
            channel =roomId, //Cookies.get("roomName"),   // room key
            role = 'audience', //Cookies.get("clientRole"),  // 'audience'
            remoteStreamList = [],
            client = AgoraRTC.createClient({mode: 'interop'}),
            uid,
            localStream,
            lastLocalStreamId,
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
			client.init('61f569b4c7f64febbe279d8fc1681b44', function (obj) {
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
            var $container;
            if (parentNodeId) {
                $container = $("#" + parentNodeId);
            } else {
                $container = $("#video-container-multiple");
            }
            // mixed mode
            if (isMixed) {
                width = 192;
                height = 120;
                className = 'video-item';
            } else {
                className += ' video-item';
            }

            var styleStr = 'width:' + width + 'px; height:' + height + 'px;';

            if (className.indexOf('local-partner-video') > -1) {
                var videoWidth = $('#wrapper').height() * 4 / 3;
                var right = (1200 - videoWidth) / 2 + 12;
                styleStr += 'top:12px; right:' + right + 'px;';
            }

            $container.append('<div id="' + tagId + stream.getId() + '" class="' + className + '" data-stream-id="' + stream.getId() + '" style="' + styleStr + '"></div>');

            // $("#" + tagId + stream.getId()).css();
            stream.play(tagId + stream.getId());
        }

        function showStreamOnPeerAdded(stream) {
            var size;
			console.log("remoteStreamList : "+remoteStreamList.length);
			size = calculateVideoSize(false);
			displayStream("agora-remote", stream, size.width, size.height, '');
			//toggleExpensionButton(true);
            $("div[id^='bar_']").remove();
        }

        function subscribeStreamEvents() {
            client.on('stream-added', function(evt) {
                var stream = evt.stream;
                console.log("New stream added: " + stream.getId());
                console.log("Timestamp: " + Date.now());
                console.log("Subscribe ", stream);
                client.subscribe(stream, function(err) {
                    console.log("Subscribe stream failed", err);
                });
            });

            client.on('peer-leave', function(evt) {
                console.log("Peer has left: " + evt.uid);
                console.log("Timestamp: " + Date.now());
                console.log(evt);
            });

            client.on('stream-subscribed', function(evt) {
                var stream = evt.stream;
				console.log(stream);
                console.log("Got stream-subscribed event");
                console.log("Timestamp: " + Date.now());
                console.log("Subscribe remote stream successfully: " + stream.getId());
                console.log(evt);
                showStreamOnPeerAdded(stream);
            });

            client.on("stream-removed", function(evt) {
                var stream = evt.stream;
                console.log("Stream removed: " + evt.stream.getId());
                console.log("Timestamp: " + Date.now());
                console.log(evt);
            });
        }

        function getResolutionArray(reso) {
			console.log(reso);
            switch (reso) {
                case "120p":
                    return [160, 120];
                case "240p":
                    return [320, 240];
                case "360p":
                    return [640, 360];
                case "480p":
                    return [848, 480];
                case "720p":
                    return [1280, 720];
                case "1080p":
                    return [1920, 1080];
                default:
                    return [1280, 720];
            }
        }

        function calculateVideoSize(multiple) {
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
