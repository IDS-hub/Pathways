			</div>
	  <footer class="site-footer">
          <div class="text-center">
              Copyright &copy; 2018 . All Rights Reserved.
          </div>
          <a href="#" class="scrollup">Scroll</a>
      </footer>
</div>
<!-- END CONTAINER -->


<!-- START CORE JS FRAMEWORK -->
<script src="<?=$this->config->item('base_url')?>public/datepicker/bootstrap.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/datepicker/moment.js"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/datepicker/bootstrap-datetimepicker.js"></script>
<script src="<?=$this->config->item('base_url')?>public/js/breakpoints.js" type="text/javascript"></script>
<script src="<?=$this->config->item('base_url')?>public/js/custom_validation.js" type="text/javascript"></script>


<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/dist/js/framework/bootstrap.js"></script>

<!-- END CORE JS FRAMEWORK -->

<!-- BEGIN PAGE LEVEL JS -->
<script src="<?=$this->config->item('base_url')?>public/plugins/jquery-slider/jquery.sidr.min.js" type="text/javascript"></script>
<script src="<?=$this->config->item('base_url')?>public/js/jquery.slimscroll.min.js" type="text/javascript"></script>
<!-- END PAGE LEVEL PLUGINS -->

<!-- BEGIN CORE TEMPLATE JS -->
<script src="<?=$this->config->item('base_url')?>public/js/core.js" type="text/javascript"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/js/script.js"></script>
<script src="<?=$this->config->item('base_url')?>public/ckeditor/ckeditor.js" type="text/javascript"></script>
<!-- END CORE TEMPLATE JS -->

<!-- BEGIN FANCYBOX JS -->
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/fancybox/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/fancybox/source/jquery.fancybox.js?v=2.1.5"></script>

<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/fancybox/source/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript" src="<?=$this->config->item('base_url')?>public/fancybox/source/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>


<!-- END FANCYBOX JS -->

<!-- END CORE DATEPICKER JS -->

<script>
$(document).ready(function() {

	$(window).scroll(function(){
            if ($(this).scrollTop() > 100) {
                $('.scrollup').fadeIn();
            } else {
                $('.scrollup').fadeOut();
            }
        });

        $('.scrollup').click(function(){
            $("html, body").animate({ scrollTop: 0 }, 600);
            return false;
        });

						$('.actionBtn_img').on('click', function() {
	if($(this).find(".actionPopup").css("display")=="block"){
		$(this).find(".actionPopup").slideUp(300);
	}else{
		$('.actionBtn_img').each(function(index, element) {
			$(element).find(".actionPopup").css("display","none");
		});
		$(this).find(".actionPopup").slideDown(300);
	}
});

$("#single_2").fancybox({
    	openEffect	: 'elastic',
    	closeEffect	: 'elastic',
    	helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
    });

	$('#offer_start_date').datetimepicker({pickTime: false});
	$('#offer_end_date').datetimepicker({pickTime: false});
	$('#offer_start_time').datetimepicker({pickDate: false});
	$('#offer_end_time').datetimepicker({pickDate: false});

	 var today = new Date();
	 var yesterday = new Date(today);
	 var currentDate = yesterday.setDate(today.getDate() - 1);

	//alert(currentDate+'--'+new Date($('#offer_start_date').val()));



	$('#offer_start_date').data("DateTimePicker").setMinDate(currentDate);

	if($('#offer_start_date').val()==""){
		$('#offer_end_date').data("DateTimePicker").setMinDate(currentDate);
	}else{
		$('#offer_end_date').data("DateTimePicker").setMinDate($("#offer_start_date").data("DateTimePicker").getDate());
	}

	$('#offer_start_date').on("dp.change",function(e){
		$('#offer_end_date').data("DateTimePicker").setMinDate(e.date);
		//$('#offer_end_date').data("DateTimePicker").setStartDate("14/7/2015");
	});

	/*$('#offer_end_time').on("dp.change",function(e){
	alert($("#offer_start_time").val()+'--'+$(this).val());
		if($("#offer_start_date").val() == $("#offer_end_date").val()){
			if($("#offer_start_time").val()<$(this).val()){
				alert("ok");
			}else{
				alert("not ok");
			}
		}else{
			alert("ok");
	});*/

});

function logout()
{
	$('#backid1').hide();
	$('#backid').hide();

	document.location.href="<?php echo $this->config->item('base_url').'login/logout'; ?>";
}
</script>

<!-- END CSS TEMPLATE -->
</body>
</html>
