/**
 * Created by levin on 14/12/2.
 * @desc å¸¸ç”¨å·¥å…·ç±»åº“ï¼Œä¸ºwidgetã€pluginç­‰åŠŸèƒ½æ€§åº“æä¾›ä¾èµ–
 *
 */
;(function (root) {

    "use strict";

    var _util = root.util = {};
    //ç¼“å­˜åŽŸåž‹å¥æŸ„
    var ArrayProto = Array.prototype, ObjProto = Object.prototype, FunProto = Function.prototype;
    //ç¼“å­˜åŽŸåž‹å¥æŸ„ä¸­çš„å¸¸ç”¨æ–¹æ³•
    var slice = ArrayProto.slice,
        toString = ObjProto.toString,
        hasOwnProperty = ObjProto.hasOwnProperty;
    //åŽŸç”Ÿæ–¹æ³• ECMAScript 5
    var nativeIsArray = Array.isArray,
        nativeKeys = Array.keys;

    /**
     * @method isString
     * @param obj
     * @returns {boolean}
     * @desc åˆ¤æ–­ä¼ å…¥å¯¹è±¡æ˜¯å¦æ˜¯å­—ç¬¦ä¸²ç±»åž‹
     */
    _util.isString = function (obj) {
        return toString.call(obj) == '[object String]';
    };

    /**
     * @method isArray
     * @type {Function|*|_.isArray}
     * @desc åˆ¤æ–­å½“å‰å¯¹è±¡æ˜¯å¦æ˜¯æ•°ç»„ç±»åž‹
     */
    _util.isArray = nativeIsArray || function (obj) {
        return toString.call(obj) == '[object Array]';
    };

    /**
     * @method isObject
     * @param obj
     * @returns {boolean}
     * @desc åˆ¤æ–­å½“å‰å¯¹è±¡æ˜¯å¦æ˜¯å¯¹è±¡ç±»åž‹
     */
    _util.isObject = function (obj) {
        var type = typeof obj;
        return type === 'function' || type === 'object' && !!obj;
    };

    /**
     * @method isDate
     * @param obj!
     * @returns {boolean}
     * @desc åˆ¤æ–­å½“å‰å¯¹è±¡æ˜¯å¦æ˜¯æ—¶é—´ç±»åž‹
     */
    _util.isDate = function (obj) {
        return toString.call(obj) == '[object Date]';
    };

    /**
     * @method getQueryVal
     * @param name å‚æ•°å
     * @param url (å¯é€‰)å¦‚æžœä»Žå½“å‰é¡µé¢URLä¸­èŽ·å–å‚æ•°å€¼ï¼Œurlå‚æ•°å¯ä»¥ä¸ç”¨æŒ‡å®š
     * @returns {string}
     * @desc æ ¹æ®å‚æ•°åä»Žå½“å‰åœ°å€æˆ–æŒ‡å®šåœ°å€ä¸­èŽ·å–å‚æ•°å€¼
     */
    _util.getQueryVal = function (name,url) {
        var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
        var r;
        //å¦‚æžœurlæœ‰æŒ‡å®šï¼Œé‚£ä¹ˆä»ŽæŒ‡å®šçš„åœ°å€ä¸­å–å€¼
        if(url && url.indexOf('?') != -1){
            r = url.split('?')[1].match(reg);
        }else{
            //é»˜è®¤ä»Žå½“å‰é¡µé¢åœ°å€ä¸­å–å€¼
            r = window.location.search.substr(1).match(reg);//r = ['åŒ¹é…åˆ°çš„ä¸»ä¸²', $1, $2, $3, index, input]$1..9ä¸ºæ­£åˆ™è¡¨è¾¾å¼åœ†æ‹¬å·åŒ¹é…çš„å­ä¸²
        }
        if (r != null) return decodeURIComponent(r[2]);
        return '';
    };

    /**
     * @method trim
     * @param obj
     * @returns {string}
     * @desc æ¸…é™¤å­—ç¬¦ä¸²ä¸¤è¾¹çš„ç©ºç™½å­—ç¬¦
     */
    _util.trim = function (obj) {
        return _util.isString(obj) ? obj.replace(/^\s+|\s+$/g, '') : '';
    };

    /**
     * @method extend
     * @param obj
     * @returns {*}
     * @desc å¯¹è±¡å±žæ€§çš„æ‰©å±•
     * @example _util.extend({aa:'abc',cc:'like that'},{aa:'cba',bb:'like this'})
     *          ç»“æžœï¼š{aa:'cba',bb:'like this',cc:'like that'}
     */
    _util.extend = function (obj) {
        if (!_util.isObject(obj)) {
            return obj;
        }
        var source, prop;
        for (var i = 0, l = arguments.length; i < l; i++) {
            source = arguments[i];
            for (prop in source) {
                if (hasOwnProperty.call(source, prop)) {
                    obj[prop] = source[prop];
                }
            }
        }
        return obj;
    };

    /**
     * @method sendStatistic
     * @param para ç»Ÿè®¡å±žæ€§
     * @param next å‘é€ç»Ÿè®¡åŽè¦æ‰§è¡Œçš„æ–¹æ³•
     * @desc å‘é€ç»Ÿè®¡è¯·æ±‚ï¼ŒåŽŸç†åˆ›å»ºä¸€ä¸ªimgå¯¹è±¡ï¼Œå›¾ç‰‡æºä¸ºä¸€ä¸ª1*1åƒç´ çš„å›¾ç‰‡ï¼Œè¿™æ ·å¯ä»¥è§£å†³ç»Ÿè®¡è¯·æ±‚åŸŸä¸ŽåŠŸèƒ½é¡µå­˜æ”¾çš„åŸŸä¸ä¸€è‡´çš„é—®é¢˜ã€‚
     * @example
     *      //ç»Ÿè®¡å±žæ€§å‚æ•°
     *      var opt = {
     *          curpageid:xxx, //æ´»åŠ¨ID
     *          type:xxx,//é¡µé¢åç§°
     *          fuid:xxx,//é¡µé¢å±•çŽ°æˆ–æŸä¸ªæ“ä½œåç§°ï¼Œå¦‚ï¼šé¡µé¢å±•ç¤º-> show; æŸ¥çœ‹æŒ‰é’®ç‚¹å‡»æ“ä½œï¼šview_click(å¯è‡ªå®šä¹‰)
     *          uid:xxx,//ç”¨æˆ·ID
     *          cid:xxx//æ¸ é“ID
     *      };
     *      //ç»Ÿè®¡å‘é€åŽè¦æ‰§è¡Œçš„æ–¹æ³•(ä¸€èˆ¬å‘é€å®Œç»Ÿè®¡å†è¿›è¡Œè·³è½¬é¡µé¢çš„æƒ…å†µå±…å¤š)
     *      var next = function(){
     *          window.location = 'www.mi.com';
     *      }
     *      //å‘é€ç»Ÿè®¡
     *      _util.sendStatistic(opt,next);
     */
    _util.sendStatistic = function (para, next) {
        //å›ºå®šçš„ç»Ÿè®¡åŸŸååœ°å€
        var url = 'https://data.game.xiaomi.com/1px.gif?ac=xm_client&client=sales_pic';
        if (!para) {
            return;
        }
        var str = '', prop;
        for (prop in para) {
            if (hasOwnProperty.call(para, prop)) {
                str += '&' + prop + '=' + para[prop];
            }
        }
        url += str + '&_' + (new Date()).getTime();
        var img = new Image();
        img.error = img.onload = function () {
            next && next();
        };
        img.src = url;
    };

    /**
     * @method isWXPlatform
     * @returns {boolean}
     * @desc åˆ¤æ–­å½“å‰é¡µé¢æ˜¯å¦åœ¨å¾®ä¿¡å¹³å°ä¸­
     */
    _util.isWXPlatform = function(){
        return /MicroMessenger/i.test(navigator.userAgent);
    };


    /**
     * @method ajaxReq
     * @param _url è¯·æ±‚åœ°å€
     * @param _type è¯·æ±‚ç±»åž‹ï¼ˆå¯é€‰ï¼‰
     * @param _param è¯·æ±‚å‚æ•°
     * @param _successCb è¯·æ±‚æˆåŠŸåŽè¦æ‰§è¡Œçš„å›žè°ƒæ–¹æ³•
     * @param _errCb è¯·æ±‚å¤±è´¥è¦æ‰§è¡Œçš„å›žè°ƒæ–¹æ³•
     * @desc å°è£…çš„ajaxè¯·æ±‚
     */
    _util.ajaxReq = function(_url,_type,_param,_successCb,_errCb){
        var option = {
            type:_type ||'get',
            url:_url,
            data:_param,
            dataType:'json',
            cache:false,
            timeout:5000,
            success:function(res,status,xhr){
                if(!res){
                    _errCb &&_errCb(null);
                    return;
                }
                if(res.code!='200'){
                    _errCb &&_errCb(res);
                    return;
                }
                _successCb && _successCb(res);
            },
            error:function(xhr,ts,err){
                _errCb && _errCb(err);
            }
        };
        $.ajax(option);
    };

    /**
     * @method checkOperaSys
     * @returns {string}
     * @desc åˆ¤æ–­å½“å‰å®¢æˆ·ç«¯ç³»ç»Ÿç±»åž‹
     */
    _util.checkOperaSys = function(){
        if (/(iPhone|iPad|iPod|iOS)/i.test(navigator.userAgent)) {
            return 'ios'
        } else if (/(Android)/i.test(navigator.userAgent)) {
            return 'android'
        }
        return 'pc'
    };

    /**
     * @method åˆ›å»ºé®ç½©
     * @param _conf é®ç½©çš„CSSæ ·å¼ CSSç±»åæˆ–è€…JSå¯¹è±¡{property: value}
     * @param _content é®ç½©å†…å®¹
     * @param _registerEv é®ç½©å†…å®¹ç»‘å®šäº‹ä»¶
     */
    _util.mask = function (_conf, _content, _registerEv) {
        var is_100 = ($(window).height() > $(document.body).height()) ? true : false;
        var
            $mask = $('<div id="bg_mask"></div>'),
            $maskStyle = {
                "position": "absolute",
                "left": 0,
                "top": 0,
                "height": !is_100 ? '100%' : $(window).height(),
                "bottom": 0,
                'width': '100%',
                "background": "rgba(0, 0, 0, .7)",
                'z-index': 9999
            };
        if (!!_conf) {
            if (_util.isObject(_conf)) {
                _util.extend($maskStyle, _conf);
            } else if (_util.isString(_conf)){
                $mask.addClass(_conf);
            }
        }
        $mask.css($maskStyle);
        $(document.body).css({
            'position': 'relative'
        }).append($mask);

        /***************** å¼€å§‹å‘é®ç½©æ·»åŠ å†…å®¹ ****************/
        if (!!_content && _util.isArray(_content)) {
            for (var i= 0, size=_content.length; i<size; i++) {
                var $content = '';
                if (_util.isArray(_content[i].content)) {
                    for (var j= 0, len=_content[i].content.length; j<len; j++) {
                        $content += _content[i].content[j];
                    }
                } else {
                    $content = _content[i].content;
                }
                $(_content[i].parentNodeSelector).append($(_content[i].tag).addClass(_content[i].className).html($content));
            }
        }

        /* é˜²æ­¢ç‚¹é€ */
        $mask.on('touchmove', function (event) {
            event.preventDefault();
            event.stopPropagation();
        });

        _util.registerEvent(_registerEv);
    };

    /**
     * @method registerEvent
     * @param _evs å…ƒç´ ç›‘å¬äº‹ä»¶é…ç½®å¯¹è±¡
     * @desc æ³¨å†Œäº‹ä»¶
     */
    _util.registerEvent = function (_evs) {
        if (!!_evs && _util.isArray(_evs)) {
            for (var i= 0, size=_evs.length; i<size; i++) {
                if (_evs[i].eventType === 'swipe') {
                    $(_evs[i].selector).swipe({
                        tap: _evs[i].listener
                    });
                } else if (_evs[i].eventType === 'touch') {
                    $(_evs[i].selector).touch({
                        tap: _evs[i].listener
                    });
                } else {
                    $(_evs[i].selector).on(_evs[i].eventType, _evs[i].listener);
                }
            }
        }
    };

    // åˆ¤æ–­æ˜¯å¦æ˜¯ç±³èŠ
    _util.isMiTalk = function () {
        var client = window.MLJsHandler,
            userId = getCookie('userId') != "" ? true : false,
            nick = getCookie('nick') != "" ? true : false,
            avatar = getCookie('avatar') != "" ? true : false,
            passToken = getCookie('passToken') != "" ? true : false,
            serviceToken = getCookie('serviceToken') != "" ? true : false,
            miVersion = getCookie('miVersion') != "" ? true : false;
        return (client && client.jsCallBack) || userId || miVersion || serviceToken || passToken || nick || avatar;
    };

    /**
     * @desc ç”ŸæˆæŸä¸ªèŒƒå›´å†…çš„éšæœºæ•°, eg: random(0, 10)ç”Ÿæˆ0-9çš„éšæœºæ•´æ•°
     * @param min
     * @param max
     */
    _util.random = function (min, max) {
        return Math.floor(min+Math.random()*(max-min));
    };

    /**
     * @desc èŽ·å–æŒ‡å®šcookieå€¼
     * @param c_name
     * @returns {string}
     */
    _util.getCookie = function (c_name) {
        var c_start = 0,
            c_end = 0;
        if (document.cookie.length>0) {
            c_start=document.cookie.indexOf(c_name + "=");
            if (c_start!=-1) {
                c_start=c_start + c_name.length+1;
                c_end=document.cookie.indexOf(";",c_start);
                if (c_end==-1) c_end=document.cookie.length;
                return document.cookie.substring(c_start,c_end);
            }
        }
        return ""
    };
    /**
     * @desc è®¾ç½®cookie
     * @param cookiename
     * @param cookievalue
     * @param milsecond
     * @param type
     */
    _util.setCookie = function (cookiename, cookievalue, milsecond, type) {
        var date = new Date();
        if (type == 'milsecond') {
            date.setTime(date.getTime() + milsecond);
        } else {
            date.setDate(date.getDate() + milsecond);
        }
        document.cookie = cookiename + "=" + cookievalue + ";expires = " + date.toGMTString();
    };
})(window.mi || (window.mi = {}));