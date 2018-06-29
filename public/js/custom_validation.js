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

function check_loginfrm()
{
	var newdate=new Date();
	
	if(/\S/.test($('#loginemail').val())==false)
	{
		msgshow('error','Please provide email address.','loginfrm','loginemail');
		return false;
	}
	else if(/^\w+[\w-\.]*\@\w+((-\w+)|(\w*))\.[a-z]{2,3}$/.test($('#loginemail').val())==false)
	{
		msgshow('error','Please provide valid email address.','loginfrm','loginemail');
		return false;
	}
	if(/\S/.test($('#loginpassword').val())==false)
	{
		msgshow('error','Please provide password.','loginfrm','loginpassword');
		return false;
	}
	else
	{
		$('#site_current_timezone').val(newdate);
		msgshow('','','','');
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

function check_categoryfrm()
{
	if(/\S/.test($('#category_name').val())==false)
	{
		msgshow('error','Please provide category name.','categoryfrm','category_name');
		return false;
	}
	else
	{
		var gotourl=$('#site_url').val()+'admin/card/checkinputname';
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

function check_cardfrm()
{
	var today_date=new Date();
	var dd = today_date.getDate();
	var mm = today_date.getMonth()+1;
	var yyyy = today_date.getFullYear();
	var fulltime=today_date;
	var today_date=mm+'/'+dd+'/'+yyyy;

	if($('#cat_id').val()==0)
	{
		msgshow('error','Please select category.','cardfrm','cat_id');
		return false;
	}
	else if($('#id').val()=='' && $('#file_name').val() == '')
	{
		msgshow('error','Please upload greeting card image.','cardfrm','file_name');
		return false;
	}
	else if($('#file_name').val()!='')
	{
		var ext = $('#file_name').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			msgshow('error','Please upload valid image.','cardfrm','');
			return false;
		}
	}
	else
	{
		$('#current_time').val(fulltime);
		msgshow('','','','');
	}
}

function submitcatfunc()
{
	$('#catfrm').submit();
}

function check_offerfrm()
{
	if(/\S/.test($('#offer_text').val())==false)
	{
		msgshow('error','Please provide offer text.','offersfrm','offer_text');
		return false;
	}
	else if((offersfrm.offer_type[0].checked == false) && (offersfrm.offer_type[1].checked == false))
	{
		msgshow('error','Please select offer type.','offersfrm','offer_percentage');
		return false;
	}
	else if($('#price').val=='')
	{
		msgshow('error','Please Input Price value.','offersfrm','price');
		return false;
	}
	else if(isNaN($('#price').val()))
	{
		msgshow('error','Price should be numeric value only.','offersfrm','price');
		return false;
	}
	else if($('#offer_price').val=='')
	{
		msgshow('error','Please Input Offer Price value.','offersfrm','price');
		return false;
	}
	else if(isNaN($('#offer_price').val()))
	{
		msgshow('error','Price should be numeric value only.','offersfrm','offer_price');
		return false;
	}
	else
	{
		msgshow('','','','');
	}
}

function check_storefrm()
{
	if(/\S/.test($('#store_name').val())==false)
	{
		msgshow('error','Please provide store name.','storefrm','store_name');
		return false;
	}
	else if(/^[a-zA-Z0-9_\s'.]+$/.test($('#store_name').val())==false)
	{
		msgshow('error','Special character not allowed','storefrm','store_name');
		return false;
	}
	else if($('#storeimg').val()!='')
	{
		var ext = $('#storeimg').val().split('.').pop().toLowerCase();
		
		if($.inArray(ext, ['gif','png','jpg','jpeg']) == -1) {
			msgshow('error','Please upload jpg,png,gif type image.','storefrm','storeimg');
			return false;
		}
	}
	else if(/\S/.test($('#address').val())==false)
	{
		msgshow('error','Please provide address.','storefrm','address');
		return false;
	}
	else
	{
		msgshow('','','','');
	}
}