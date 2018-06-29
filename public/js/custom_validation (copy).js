function msgshow(gettype,getval,form_id,focus_id)
{
	if(gettype=='error')
	{
		$('#get_error_msg_main_id1').hide();
		$('#get_success_msg_main_id1').hide();
		$('#get_success_msg_main_id').hide();
		$('#get_error_msg_main_id').show();
		$('#get_error_msg_id').html(getval);
	}
	else if(gettype=='success')
	{
		$('#get_error_msg_main_id1').hide();
		$('#get_success_msg_main_id1').hide();
		$('#get_error_msg_main_id').hide();
		$('#get_success_msg_main_id').show();
		$('#get_success_msg_id').html(getval);
	}
	else
	{
		$('#get_error_msg_main_id1').hide();
		$('#get_success_msg_main_id1').hide();
		$('#get_error_msg_main_id').hide();
		$('#get_success_msg_main_id').hide();
	}
	
	if(focus_id!='')
	{
		$('form[id="'+form_id+'"] input').each(function(index,element){
			if($(this).attr("id")!=undefined)
			{
				$('#'+$(this).attr("id")+'').css("border-color", "");
			}
		});
		
		$('form[id="'+form_id+'"] select').each(function(index,element){
			if($(this).attr("id")!=undefined)
			{
				$('#'+$(this).attr("id")+'').css("border-color", "");
			}
		});
		$('#'+focus_id+'').css("border-color", "#EF6565");
		$('#'+focus_id+'').focus();
	}
}
function check_cngpassfrm()
{
	if(/\S/.test($('#old_password').val())==false)
	{
		msgshow('error',"Please provide old password.",'cngpassfrm','old_password');
		return false;
	}
	else if(/\S/.test($('#new_password').val())==false)
	{
		msgshow('error',"Please provide new password.",'cngpassfrm','new_password');
		return false;
	}
	else if(/\S/.test($('#retype_password').val())==false)
	{
		msgshow('error',"Please provide retype password.",'cngpassfrm','retype_password');
		return false;
	}
	else if($('#retype_password').val()!=$('#new_password').val())
	{
		msgshow('error',"Password and retype password doesn't match.",'cngpassfrm','retype_password');
		return false;
	}
	else
	{
		msgshow('','','','');
	}
}

function hide_error_msg(getid)
{
	$('#'+getid+'').hide();
}

function check_editproffrm()
{
	if(/\S/.test($('#first_name').val())==false)
	{
		msgshow('error','Please provide first name.','editproffrm','first_name');
		return false;
	}
	else if(/\S/.test($('#last_name').val())==false)
	{
		msgshow('error','Please provide last name.','editproffrm','last_name');
		return false;
	}
	else if($('#profile_image').val()!='')
	{
		var ext = $('#profile_image').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			msgshow('error','Please upload valid image.','editproffrm','');
			return false;
		}
		else
		{
			msgshow('','','','');
		}
	}
	else
	{
		msgshow('','','','');
	}
}

function check_settingsfrm()
{
	if(/\S/.test($('#site_name').val())==false)
	{
		msgshow('error','Please provide site name.','settingsfrm','site_name');
		return false;
	}
	else if(/\S/.test($('#mailing_email').val())==false)
	{
		msgshow('error','Please provide email address.','settingsfrm','mailing_email');
		return false;
	}
	else if(/^\w+[\w-\.]*\@\w+((-\w+)|(\w*))\.[a-z]{2,3}$/.test($('#mailing_email').val())==false)
	{
		msgshow('error','Please provide valid email address.','settingsfrm','mailing_email');
		return false;
	}
	else if(/\S/.test($('#facebook_id').val())==false)
	{
		msgshow('error','Please provide show per page.','settingsfrm','facebook_id');
		return false;
	}
	else if(/\S/.test($('#show_per_page').val())==false)
	{
		msgshow('error','Please provide show per page.','settingsfrm','show_per_page');
		return false;
	}
	else if(/^\d+$/.test($('#show_per_page').val())==false)
	{
		msgshow('error','Please provide valid show per page.','settingsfrm','show_per_page');
		return false;
	}
	else if($('#admin_logo').val()!='')
	{
		var ext = $('#admin_logo').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			msgshow('error','Please upload valid admin logo.','settingsfrm','');
			return false;
		}
	}
	else if($('#admin_login_logo').val()!='')
	{
		var ext = $('#admin_login_logo').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			msgshow('error','Please upload valid admin login logo.','settingsfrm','');
			return false;
		}
	}
	else
	{
		msgshow('','','','');
	}
}

function check_emailtmpfrm()
{
	if(/\S/.test($('#title').val())==false)
	{
		msgshow('error','Please provide title.','emailtmpfrm','title');
		return false;
	}
	else if(CKEDITOR.instances.content.getData().replace(/<[^>]*>|\s/g, '') == '')
	{
		msgshow('error','Please provide content.','emailtmpfrm','content');
		return false;
	}
	else
	{
		msgshow('','','','');
	}
}

function checkall(delete_id,deleted_id)
{
	if(document.getElementById(delete_id).checked==true)
	{
		var checktoggle=true;
	}
	else
	{
		var checktoggle=false;
	}


	var checkboxes = new Array();
      checkboxes = document.getElementsByName(''+deleted_id+'[]');
      for (var i = 0; i < checkboxes.length; i++) {
          if (checkboxes[i].type === 'checkbox') {
               checkboxes[i].checked = checktoggle;
          }
    }
}

function deletecheckeddata(deletefrmid)
{
	var deleteconfirm=confirm("Do you want to delete data(s)?");
	
	if(deleteconfirm==true)
	{
		$('#'+deletefrmid+'').submit();
	}
}

function check_locationfrm()
{
	if(/\S/.test($('#location_name').val())==false)
	{
		msgshow('error','Please provide location name.','locationfrm','location_name');
		return false;
	}
	else if(/\S/.test($('#location_address').val())==false)
	{
		msgshow('error','Please provide location address.','locationfrm','location_address');
		return false;
	}
	else
	{
		msgshow('','','','');
	}
}


function datetimechecking(startdate,enddate)
{
	var newstartdate=new Date(startdate);
	var newenddate=new Date(enddate);
	
	if(Date.parse(newstartdate) >= Date.parse(newenddate))
	{
		return 0;
	}
	else
	{
		return 1;
	}
}

function check_eventfrm()
{
	if(/\S/.test($('#event_title').val())==false)
	{
		msgshow('error','Please provide event title.','eventfrm','event_title');
		return false;
	}
	else if($('#location_id').val()==0)
	{
		msgshow('error','Please select location.','eventfrm','location_id');
		return false;
	}
	else if($('#cat_id').val()==0)
	{
		msgshow('error','Please select category.','eventfrm','cat_id');
		return false;
	}
	else if($('#subcat_id').val()==0)
	{
		msgshow('error','Please select sub-category.','eventfrm','subcat_id');
		return false;
	}
	else if(/\S/.test($('#event_start_date').val())==false)
	{
		msgshow('error','Please provide event start date.','eventfrm','event_start_date');
		return false;
	}
	else if(/\S/.test($('#event_start_time').val())==false)
	{
		msgshow('error','Please provide event start time.','eventfrm','event_start_time');
		return false;
	}
	else if(/\S/.test($('#event_end_date').val())==false)
	{
		msgshow('error','Please provide event end date.','eventfrm','event_end_date');
		return false;
	}
	else if(/\S/.test($('#event_end_time').val())==false)
	{
		msgshow('error','Please provide event end time.','eventfrm','event_end_time');
		return false;
	}
	else if(datetimechecking($('#event_start_date').val()+' '+$('#event_start_time').val(),$('#event_end_date').val()+' '+$('#event_end_time').val())==0)
	{
		msgshow('error','Event end date should be greater than event start date.','eventfrm','event_end_date');
		return false;
	}
	else if(/\S/.test($('#event_feed_start_date').val())==false)
	{
		msgshow('error','Please provide feed post start date.','eventfrm','event_feed_start_date');
		return false;
	}
	else if(/\S/.test($('#event_feed_start_time').val())==false)
	{
		msgshow('error','Please provide feed post start time.','eventfrm','event_feed_start_time');
		return false;
	}
	else if(datetimechecking($('#event_feed_start_date').val()+' '+$('#event_feed_start_time').val(),$('#event_start_date').val()+' '+$('#event_start_time').val())==0)
	{
		msgshow('error','Feed post start date should be less than event start date.','eventfrm','event_feed_start_date');
		return false;
	}
	else if(/\S/.test($('#event_feed_end_date').val())==false)
	{
		msgshow('error','Please provide feed post end date.','eventfrm','event_feed_end_date');
		return false;
	}
	else if(/\S/.test($('#event_feed_end_time').val())==false)
	{
		msgshow('error','Please provide feed post end time.','eventfrm','event_feed_end_time');
		return false;
	}
	else if(datetimechecking($('#event_feed_start_date').val()+' '+$('#event_feed_start_time').val(),$('#event_feed_end_date').val()+' '+$('#event_feed_end_time').val())==0)
	{
		msgshow('error','Feed post end date should be greater than feed post start date.','eventfrm','event_feed_end_date');
		return false;
	}
	else if(datetimechecking($('#event_feed_end_date').val()+' '+$('#event_feed_end_time').val(),$('#event_end_date').val()+' '+$('#event_end_time').val())==0)
	{
		msgshow('error','Feed post end date should be less than event end date.','eventfrm','event_feed_end_date');
		return false;
	}
	else if(CKEDITOR.instances.description.getData().replace(/<[^>]*>|\s/g, '') == '')
	{
		msgshow('error','Please provide description.','eventfrm','description');
		return false;
	}
	else
	{
		msgshow('','','','');
	}
}

function check_categoryfrm()
{
	if(/\S/.test($('#category_name').val())==false)
	{
		msgshow('error','Please provide category name.','categoryfrm','category_name');
		return false;
	}
	else
	{
		var gotourl=$('#site_url').val()+'admin/event/checkinputname';
		$.ajax({
			 type: "POST",
			 url: gotourl, 
			 data: {inputname: $('#category_name').val(),inputtype: 'Category',inputid: $('#id').val()},
			 dataType: "text",  
			 cache:false,
			 success: 
				  function(data){

				   if(data == 'Error')
				   {
						msgshow('error','Please enter category name.','categoryfrm','category_name');
						return false;
				   }
				   else
				   {
						if(data > 0)
						{
							msgshow('error','Category name already exists.','categoryfrm','category_name');
							return false;
						}
						else
						{
							msgshow('','','','');
							$('#categoryfrm').submit();
						}
				   }

				  }

		});
	}
}



function check_subcategoryfrm()
{
	if(/\S/.test($('#subcategory_name').val())==false)
	{
		msgshow('error','Please provide subcategory name.','subcategoryfrm','subcategory_name');
		return false;
	}
	else
	{
		var gotourl=$('#site_url').val()+'admin/event/checkinputname';
		$.ajax({
			 type: "POST",
			 url: gotourl, 
			 data: {inputname: $('#subcategory_name').val(),inputtype: 'Subcategory',inputid: $('#id').val()},
			 dataType: "text",  
			 cache:false,
			 success: 
				  function(data){

				   if(data == 'Error')
				   {
						msgshow('error','Please enter subcategory name.','subcategoryfrm','subcategory_name');
						return false;
				   }
				   else
				   {
						if(data > 0)
						{
							msgshow('error','Subcategory name already exists.','subcategoryfrm','subcategory_name');
							return false;
						}
						else
						{
							if($('#parent_id').val()==0)
							{
								msgshow('error','Please select category.','subcategoryfrm','parent_id');
								return false;
							}
							else
							{
								msgshow('','','','');
								$('#subcategoryfrm').submit();
							}
						}
				   }

				  }

		});
	}
}

function ajaxsubcat(getcatid)
{
	$('#getsubcatresponse').html('<select id="subcat_id" name="subcat_id"><option selected="selected" value="0">Loading...</option></select>');
	var gotourl=$('#site_url').val()+'admin/event/getsubcat';
	$.ajax({
         type: "POST",
         url: gotourl, 
         data: {getcatid: getcatid},
         dataType: "text",  
         cache:false,
         success: 
              function(data){
               $('#getsubcatresponse').html(data);  //as a debugging message.
				return false;
              }

	});
	
}

$('#event_start_date').datetimepicker({pickTime: false});
$('#event_start_time').datetimepicker({pickDate: false});
$('#event_end_date').datetimepicker({pickTime: false});
$('#event_end_time').datetimepicker({pickDate: false});
$('#event_feed_start_date').datetimepicker({pickTime: false});
$('#event_feed_start_time').datetimepicker({pickDate: false});
$('#event_feed_end_date').datetimepicker({pickTime: false});
$('#event_feed_end_time').datetimepicker({pickDate: false});