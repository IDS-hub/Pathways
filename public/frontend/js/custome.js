$(document).ready(function () {
  $('[data-toggle="offcanvas"]').click(function () {
    $('.row-offcanvas').toggleClass('active')
  });
  var minwinHgt=$(window).height();
    $('.lft_sidebar').css({'min-height':minwinHgt});

  var winWidth = $(window).width();
  var topnav_width = winWidth - 250;
  if( winWidth > 767){
  	$(".topNav").css({"width": topnav_width});
  }else{
  	$(".topNav").css({"width": winWidth});
  	$(".navbar-nav.cust-nav").css({"margin-top":0, "margin-bottom":0});
  	$(".navbar-nav.cust-nav").addClass("col-xs-6");
  };
});
$(window).resize(function () {
  $('[data-toggle="offcanvas"]').click(function () {
    $('.row-offcanvas').toggleClass('active')
  });
  var minwinHgt=$(window).height();
    $('.lft_sidebar').css({'min-height':minwinHgt});

  var winWidth = $(window).width();
  var topnav_width = winWidth - 250;
  if( winWidth > 767){
    $(".topNav").css({"width": topnav_width});
  }else{
    $(".topNav").css({"width": winWidth});
    $(".navbar-nav.cust-nav").css({"margin-top":0, "margin-bottom":0});
    $(".navbar-nav.cust-nav").addClass("col-xs-6");
  };
});

