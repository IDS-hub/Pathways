		</div><!--/.container-->
	</div>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<script>window.jQuery || document.write('<script src="<?=$this->config->item('base_url')?>public/frontend/js/jquery.min.js"><\/script>')</script>
<script src="<?=$this->config->item('base_url')?>public/frontend/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="<?=$this->config->item('base_url')?>public/frontend/js/custome.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="<?=$this->config->item('base_url')?>public/frontend/node_modules/bootstrap/dist/js/ie10-viewport-bug-workaround.js"></script>-->
<script>
$(document).ready(function(){
	var count = 1;
	$('.ajaxDiv').hide();
	$('.moreFt').click(function() {
		count=count+1;
		alert('ssssss'+count);
		$.ajax({
			type: "POST",
			url: "<?php echo $this->config->item('base_url').'Home/ajaxfeatured'; ?>",
			data: {page:count},
			//async: true,
			success: function (response) {
				$('.ajaxDiv').show();
	            alert(response);
			    $('.ajaxDiv').html(response);
	        }
		});
	});
});
</script>


</body>
</html>
