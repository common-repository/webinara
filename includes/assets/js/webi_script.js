jQuery(document).ready(function(){	
	jQuery(".webi_add_speaker").click(function(){
		var count = jQuery(".speaker_section .repeated_speaker_section").length;
		jQuery(".webi_remove_speaker").show();					
		jQuery(".speaker_section .repeated_speaker_section:first-child").clone().appendTo('.speaker_section');		
		jQuery(".speaker_section .repeated_speaker_section:last-child input[type='text'], .speaker_section .repeated_speaker_section:last-child input[type='hidden'], .speaker_section .repeated_speaker_section:last-child textarea").val('');	
		jQuery(".speaker_section .repeated_speaker_section:last-child img").attr('src','');
		jQuery(".speaker_section .repeated_speaker_section:last-child img").hide();
		jQuery(".speaker_section .repeated_speaker_section:last-child .webi_remove_image").hide();
		
	});	
	jQuery(".webi_add_attachment").click(function(){
		var count = jQuery(".attachment_section .repeated_attachment_section").length;
		jQuery(".webi_remove_attachment").show();				
		jQuery(".attachment_section .repeated_attachment_section:first-child").clone().appendTo('.attachment_section');
		jQuery(".attachment_section .repeated_attachment_section:last-child input").val('');
		if(jQuery(".attachment_section .repeated_attachment_section:first-child input").val() == '')
		{
			jQuery(".attachment_section .repeated_attachment_section:first-child .webi_remove_attachment").hide();
		}
		
	});		
	jQuery(".webi_add_sponsorimg").click(function(){
		var count = jQuery(".sponsorimg_section .repeated_sponsorimg_section").length;
		jQuery(".webi_remove_imgsponsor").show();				
		jQuery(".sponsorimg_section .repeated_sponsorimg_section:first-child").clone().appendTo('.sponsorimg_section');
		jQuery(".sponsorimg_section .repeated_sponsorimg_section:last-child input").val('');	
		jQuery(".sponsorimg_section .repeated_sponsorimg_section:last-child img").attr('src','');	
		if(jQuery(".sponsorimg_section .repeated_sponsorimg_section:first-child img").attr('src') == '')
		{
			jQuery(".sponsorimg_section .repeated_sponsorimg_section:first-child .webi_remove_imgsponsor").hide();
		}		
	});	

	jQuery(".webi_add_new_field").click(function(){
		var webi_table = jQuery(this).closest('table');
		var table_row    = webi_table.data( 'field' );
		table_row = table_row.replace( /\[new_row\]/g, "[" + parseInt(webi_table.children('tbody').find('tr').size()+1) + "]");
		webi_table.append( table_row );
	});

	jQuery('.tabs .tab-links a').on('click', function(e) {
		var webi_url = jQuery("#webi_url").val();
		var currentAttrValue = jQuery(this).attr('href');		
		jQuery('.tabs ' + currentAttrValue).show().siblings().hide();
		jQuery(this).parent('li').addClass('active').siblings().removeClass('active');
		if(currentAttrValue == '#tab1')
		{
			history.pushState('', 'Settings', webi_url);
		}
		else if(currentAttrValue == '#tab2')
		{
			history.pushState('', 'Settings', webi_url+'&license');
		}
		else if(currentAttrValue == '#tab3')
		{
			history.pushState('', 'Settings', webi_url+'&connect');
		}
		e.preventDefault();
	});	
	navigator.userAgent.toLowerCase().indexOf("chrome") > -1 ? window.console.log.apply(console, ["\n %c Made with ❤️ by Webinara %c https://www.webinara.com/ %c %c 🐳 \n\n", "border: 1px solid #000;color: #000; background: #fff001; padding:5px 0;", "color: #fff; background: #1c1c1c; padding:5px 0;border: 1px solid #000;", "background: #fff; padding:5px 0;", "color: #b0976d; background: #fff; padding:5px 0;"]) : window.console && window.console.log("Made with love ❤️ Webinara - https://www.webinara.com/  ❤️");
	
	if(jQuery("#cur_tab").val() == 'p')
	{		
		jQuery(".webi_fremium_sec .webi_loader").show();
		var data = {
			'action': 'webi_get_info',
			'fetch_data': 1, 
		};		
		jQuery.post(ajax_var.url, data, function (response) {
			jQuery(".webi_profile_section table").removeClass("loading_state");
			if(response == 0)
			{
				alert("Something is wrong");
			}
			else
			{
				res_arr = response.split('|+|');
				jQuery("#webireg_fname").val(res_arr[0]);
				jQuery("#webireg_lname").val(res_arr[1]);
				jQuery("#webireg_jobtitle").val(res_arr[3]);
				jQuery("#webireg_joblevel").val(res_arr[4]);				
				jQuery('#webireg_joblevel').trigger("chosen:updated");
				jQuery("#webireg_jobfunction").val(res_arr[5]);
				jQuery('#webireg_jobfunction').trigger("chosen:updated");
				jQuery("#webireg_country").val(res_arr[6]);
				jQuery('#webireg_country').trigger("chosen:updated");
				jQuery("#webireg_prefzone").val(res_arr[7]);
				jQuery('#webireg_prefzone').trigger("chosen:updated");
				jQuery("#webireg_websolution").val(res_arr[8]);
				jQuery('#webireg_websolution').trigger("chosen:updated");
				jQuery("#webireg_cname").val(res_arr[2]);
				jQuery("#webireg_cindustry").val(res_arr[9]);
				jQuery('#webireg_cindustry').trigger("chosen:updated");
				jQuery("#webireg_website").val(res_arr[10]);
				jQuery("#webireg_csize").val(res_arr[11]);
				jQuery('#webireg_csize').trigger("chosen:updated");
				jQuery("#webireg_cdesc").val(res_arr[12]);
				jQuery("#webi_pp_button").attr("href",res_arr[14]);
			}
		});
	}
	else if(jQuery("#cur_tab").val() == 'l')
	{
		var data = {
			'action': 'webi_get_info',
			'fetch_data': 2, 
		};		
		jQuery.post(ajax_var.url, data, function (response) {
			res_arr = response.split("++");
			if(res_arr[0] == 0)
			{
				alert("Your license key is invalid or expire");
			}
			else if(res_arr[0] == 2)
			{
				alert("Your key is invalid");
				location.reload(true);
			}
			else
			{
				if(res_arr[1] == 0)
				{
					jQuery(".license_exp_message").html("(Your License Key Expires On "+res_arr[0]+".)");
				}
				else
				{
					jQuery(".license_exp_message").html("(Your License Key is Expired On "+res_arr[0]+". You must renew it for accessing premium features.)");
				}
			}
		});
	}
	
	jQuery(".webi_banner_theme").wpColorPicker();
});

var file_frame;
jQuery(document).on('click','.webi_upload_file_button',function(event){		
	event.preventDefault();	
	if(jQuery(this).hasClass('add_speaker_img'))
	{		
		jQuery("#webi_current_section").val(1);
		file_target_wrapper = jQuery( this ).closest('.webi_speakerimg');		
		file_target_input   = file_target_wrapper.find('input');
		file_target_img   = file_target_wrapper.find('img');	
	}
	else if(jQuery(this).hasClass('add_file_attachment'))
	{
		jQuery("#webi_current_section").val(2);
		file_target_wrapper = jQuery( this ).parent().parent('.repeated_attachment_section').children('.webi_attach_fld');	
		file_target_wrapper_2 = jQuery( this ).parent().parent('.repeated_attachment_section').children('.webi_attach_action');				
		file_target_input   = file_target_wrapper.find('input');
	}
	else if(jQuery(this).hasClass('add_imgcarosel_attachment'))
	{
		jQuery("#webi_current_section").val(3);
		file_target_wrapper = jQuery( this ).parent().parent('.repeated_caroselimg_section').children('.webi_imgcarosel_fld');	
		file_target_wrapper_2caro = jQuery( this ).parent().parent('.repeated_caroselimg_section').children('.webi_caro_action');		
		file_target_input   = file_target_wrapper.find('input');
		file_target_img_carosel   = file_target_wrapper.find('img');
	}	
	else if(jQuery(this).hasClass('add_imgsponsor_attachment'))
	{
		jQuery("#webi_current_section").val(4);
		file_target_wrapper = jQuery( this ).parent().parent('.repeated_sponsorimg_section').children('.webi_imgsponsor_fld');	
		file_target_wrapper_3caro = jQuery( this ).parent().parent('.repeated_sponsorimg_section').children('.webi_sponsor_action');		
		file_target_input   = file_target_wrapper.find('input');
		file_target_img_carosel   = file_target_wrapper.find('img');
	}		
	// If the media frame already exists, reopen it.

	/* if ( file_frame ) 
	{
		file_frame.open();
		return;
	} */

	// Create the media frame.
	if(jQuery("#webi_current_section").val() == 1 || jQuery("#webi_current_section").val() == 3 || jQuery("#webi_current_section").val() == 4)
	{
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false,  // Set to true to allow multiple files to be selected
			library: {
				type: [ 'image' ]
			}
		});
	}
	else
	{
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false,  // Set to true to allow multiple files to be selected	
			library: {
				type: [ 'application/pdf' ]
			}		
		});
	}

	// When an image is selected, run a callback.
	file_frame.on( 'select', function() 
	{		
		// We set multiple to false so only get one image from the uploader
		attachment = file_frame.state().get('selection').first().toJSON();			
		jQuery( file_target_input ).val( attachment.url );
		if(jQuery("#webi_current_section").val() == 1)
		{
			jQuery( file_target_img ).attr( 'src', attachment.url );
			jQuery( file_target_img ).show();
			jQuery( file_target_wrapper ).children(".webi_remove_image").show();
		}
		if(jQuery("#webi_current_section").val() == 2)
		{
			jQuery( file_target_wrapper_2 ).children(".webi_remove_attachment").show();
		}
		if(jQuery("#webi_current_section").val() == 3){
			jQuery( file_target_img_carosel ).attr( 'src', attachment.url );
			jQuery( file_target_img_carosel ).show();
			jQuery( file_target_wrapper_2caro ).children(".webi_remove_imgcarosel").show();
		}
		if(jQuery("#webi_current_section").val() == 4){
			jQuery( file_target_img_carosel ).attr( 'src', attachment.url );
			jQuery( file_target_img_carosel ).show();
			jQuery( file_target_wrapper_3caro ).children(".webi_remove_imgsponsor").show();
		}
	});
	// Finally, open the modal
	file_frame.open();
});	

jQuery(document).on('change', '#_webi_platform', function(){
	var current_platform = jQuery(this).val();
	var webi_timezone_status = jQuery("#webi_timezone_status").val();
	jQuery(".webi_tz_zoom").hide();
	jQuery(".gotowebinar_id_section, .onstream_msg_section").hide();
	jQuery("#_webi_gotowebinar_id").val('');
	jQuery(".webi_tz").show();
	if(current_platform == 3)
	{
		jQuery(".other_url_section").show();
		jQuery("#_webi_registration_url").val('');
	}
	else
	{
		var webi_con_platform = jQuery("#webi_con_platform").val();
		webi_con_platform_arr = webi_con_platform.split(",");
		if(current_platform == 1){ var webi_platform_name = 'GoToWebinar'; }
		if(current_platform == 2){ var webi_platform_name = 'Onstream'; }
		if(current_platform == 4){ var webi_platform_name = 'ReadyTalk'; }
		if(current_platform == 7){ var webi_platform_name = 'Zoom'; }
		if(webi_timezone_status == 0)
		{
			alert("You must enable Timezone field from setting page for posting "+webi_platform_name+" platform webinar.");
			jQuery(".other_url_section").show();
			jQuery("#_webi_registration_url").val('');
			jQuery("#_webi_platform").val(3);
		}
		else
		{			
			if(webi_con_platform_arr[current_platform] == 0)
			{
				alert("Please connect with your "+webi_platform_name+" account from setting page");
				jQuery(".other_url_section").show();
				jQuery("#_webi_registration_url").val('');
				jQuery("#_webi_platform").val(3);
				
				jQuery(".webi_tz_zoom").hide();
				jQuery(".webi_tz").show();
			}
			else
			{							
				if(current_platform == 1)
				{
					jQuery(".gotowebinar_id_section").show();
				}
				else if(current_platform == 2)
				{
					jQuery(".onstream_msg_section").show();				
				}
				else if(current_platform == 7)
				{
					jQuery(".webi_tz_zoom").show();
					jQuery(".webi_tz").hide();
				}				
				jQuery(".other_url_section").hide();
				jQuery("#_webi_registration_url").val('');
			}
		}
	}
});

jQuery(document).on('change', 'input[name="_webi_all_day"]:checkbox', function(){
	 if(this.checked) {
         jQuery("#webi_datetime .time").attr("disabled", "disabled");		 
    }
	else
	{
		 jQuery("#webi_datetime .time").removeAttr("disabled"); 		 
	}
});
jQuery(document).on("click",".webi_remove_speaker",function(){	
	jQuery(this).parent().parent("div").remove();	
	var count = jQuery(".speaker_section .repeated_speaker_section").length;
	if(count <= 1)
	{
		jQuery(".webi_remove_speaker").hide();
	}
});

jQuery(document).on("click",".webi_remove_attachment",function(){		
	var count = jQuery(".attachment_section .repeated_attachment_section").length;
	if(count <= 1)
	{
		jQuery(this).parent().parent("div").children(".webi_attach_fld").children("input").val('');
		jQuery(this).hide();
	}
	else
	{
		jQuery(this).parent().parent("div").remove();
	}
});

jQuery(document).on("click",".webi_remove_imgcarosel",function(){		
	var count = jQuery(".caroselimg_section .repeated_caroselimg_section").length;
	if(count <= 1)
	{
		jQuery(this).parent().parent("div").children(".webi_imgcarosel_fld").children("input").val('');
		jQuery(this).parent().parent("div").children(".webi_imgcarosel_fld").children("img").removeAttr('src');
		jQuery(this).hide();
	}
	else
	{
		jQuery(this).parent().parent("div").remove();
	}
});

jQuery(document).on("click",".webi_remove_imgsponsor",function(){		
	var count = jQuery(".sponsorimg_section .repeated_sponsorimg_section").length;
	if(count <= 1)
	{
		jQuery(this).parent().parent("div").children(".webi_imgsponsor_fld").children("input").val('');
		jQuery(this).parent().parent("div").children(".webi_imgsponsor_fld").children("img").removeAttr('src');
		jQuery(this).hide();
	}
	else
	{
		jQuery(this).parent().parent("div").remove();
	}
});


/* jQuery(document).on("submit","form#post",function(e){	
	e.preventDefault();
	if(jQuery("#post_type").val() == 'webinars'){	
		if(jQuery("#_webi_start_date").val().length == 0){
			alert("Start date is a required field");
			jQuery("#_webi_start_date").focus();
			return false;
		}
		else if(jQuery("#_webi_start_time").val().length == 0){
			alert("Start time is a required field");
			jQuery("#_webi_start_time").focus();
			return false;
		}
		else if(jQuery("#_webi_end_date").val().length == 0){
			alert("End date is a required field");
			jQuery("#_webi_end_date").focus();
			return false;
		}
		else if(jQuery("#_webi_end_time").val().length == 0){
			alert("End date is a required field");
			jQuery("#_webi_end_time").focus();
			return false;
		}		
		else if(jQuery("input[name='_event_type']:radio").val() == 'seminar'){
			if(jQuery("#_webi_address").val().length == 0){
				alert("Address is a required field");
				jQuery("#_webi_address").focus();
				return false;
			}
			else if(jQuery("#_webi_city").val().length == 0){
				alert("City is a required field");
				jQuery("#_webi_city").focus();
				return false;
			}
			else if(jQuery("#_webi_state").val().length == 0){
				alert("State is a required field");
				jQuery("#_webi_state").focus();
				return false;
			}
			else if(jQuery("#_webi_country").val().length == 0){
				alert("Country is a required field");
				jQuery("#_webi_country").focus();
				return false;
			}
			else if(jQuery("#_webi_zipcode").val().length == 0){
				alert("Zipcode is a required field");
				jQuery("#_webi_zipcode").focus();
				return false;
			}
		}
		else if(jQuery("#_webi_registration_url").val().length == 0){
			alert("Webinar registration URL is a required field");
			jQuery("#_webi_registration_url").focus();
			return false;
		}
		else{ 
			alert(jQuery("input[name^='_webi_speaker_first_name']").length);
			jQuery("input[name^='_webi_speaker_first_name']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("First name is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});	
			jQuery("input[name^='_webi_speaker_last_name']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Last name is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});
			jQuery("input[name^='_webi_speaker_company']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Company is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});
			jQuery("input[name^='_webi_speaker_title']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Title is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});
			jQuery("input[name^='_webi_speaker_website']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Website is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});
			jQuery("input[name^='_webi_speaker_twitter']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Twitter is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});	
			jQuery("input[name^='_webi_speaker_facebook']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Facebook is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});	
			jQuery("input[name^='_webi_speaker_linkedin']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("LinkedIn is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});	
			jQuery("input[name^='_webi_speaker_bio']").each(function(){
				if(jQuery(this).closest("p").hasClass('webi_require')){				
					if(jQuery(this).val().length == 0){
						alert("Speaker Bio is a required field");
						jQuery(this).focus();
						return false;
					}
				}
			});
			//jQuery("form#post").submit();
		}
	}	
}); */


/* jQuery(document).on("click","#publish",function(e){	
	e.preventDefault();
	if(jQuery("#post_type").val() == 'webinars'){
		var not_submit = 0;		
		jQuery(".webi_require").each(function(){			
			alert(jQuery(this).children("input").attr("id"));
			if(jQuery(this).children("input").val().length == 0){
				jQuery(this).children("input").focus();
				not_submit = 1;	
				alert(not_submit);
				return false;				
			}
			else{
				not_submit = 0;	
			}
			if(jQuery(this).children("textarea").val().length == 0){
				jQuery(this).children("textarea").focus();
				not_submit = 1;	
				alert(not_submit);
				return false;				
			}
			else{
				not_submit = 0;	
			}	
		});	
		alert(not_submit);
		if(not_submit == 0){
			jQuery("form#post").submit();
		}			
	}
}); */

jQuery(document).on("click",".webi_remove_image",function(){
	jQuery(this).hide();
	file_target_wrapper = jQuery( this ).closest('.webi_speakerimg');		
	file_target_input   = file_target_wrapper.find('input');
	file_target_img   = file_target_wrapper.find('img');
	jQuery( file_target_input ).val( '' );
	jQuery( file_target_img ).attr( 'src', '' );
	jQuery( file_target_img ).hide();
});

jQuery('#webi_datetime .time').timepicker({
	'showDuration': true,
	'timeFormat': 'g:ia'
});

jQuery('#webi_datetime .date').datepicker({
	'format': 'm/d/yyyy',
	'autoclose': true
});

// initialize datepair
jQuery('#webi_datetime').datepair();


var config = {
    '.input-select'           : {max_selected_options: 3},
    '.input-select-deselect'  : {allow_single_deselect:true},
    '.input-select-no-single' : {disable_search_threshold:10},
    '.input-select-no-results': {no_results_text:'Oops, nothing found!'},
    '.input-select-width'     : {width:"95%"}
}
for (var selector in config) {
    jQuery(selector).chosen(config[selector]);
}

jQuery(document).on("click",".webi_remove_current_field",function(){
	jQuery(this).closest("tr").remove();
});

jQuery(document).on('click', '.webi_enable_platform', function(){
	var plat_id = jQuery(this).attr("id");
	var plat_id_arr = plat_id.split("_");
	jQuery("#"+plat_id_arr[0]).toggle();
	jQuery(".webi_con_response").hide();
	jQuery("#"+plat_id_arr[0]+" .sec_2").show();	
});

jQuery(document).on('click','.connect_platform',function(){
	jQuery(".webi_con_response").hide();
	jQuery(".webi_con_response").removeClass("err");
	if(jQuery(this).attr('id') == 'auth_gotowebinar')
	{		
		var webi_gotowebinar_key = jQuery("#webi_gotowebinar_key").val();
		var webi_gotowebinar_secret = jQuery("#webi_gotowebinar_secret").val();
		if(webi_gotowebinar_key == '')
		{
			alert("GoToWebinar Consumer Key field is required");
			jQuery("#webi_gotowebinar_key").focus();
		}
		else if(webi_gotowebinar_secret == '')
		{
			alert("GoToWebinar Consumer Secret field is required");
			jQuery("#webi_gotowebinar_secret").focus();
		}
		else
		{
			jQuery("#webigotowebinar .webi_loader").show();
			var data = {
				'action': 'webi_authgotowebinar',
				'webi_gotowebinar_key': webi_gotowebinar_key, // We pass php values differently!
				'webi_gotowebinar_secret': webi_gotowebinar_secret,	
			};		
			jQuery.post(ajax_var.url, data, function (response) {				
				if(response == 'Success'){
					jQuery(location).attr('href','https://api.getgo.com/oauth/v2/authorize?response_type=code&client_id='+webi_gotowebinar_key);
				}
			});
		}
	}
	
	if(jQuery(this).attr('id') == 'auth_onstream')
	{		
		var webi_onstream_username = jQuery("#webi_onstream_username").val();
		var webi_onstream_password = jQuery("#webi_onstream_password").val();
		if(webi_onstream_username == '')
		{
			alert("Onstream API Username field is required");
			jQuery("#webi_onstream_username").focus();
		}
		else if(webi_onstream_password == '')
		{
			alert("Onstream Password field is required");
			jQuery("#webi_onstream_password").focus();
		}
		else
		{
			jQuery("#webionstream .webi_loader").show();
			var data = {
				'action': 'webi_authgotowebinar',
				'webi_onstream_username': webi_onstream_username, // We pass php values differently!
				'webi_onstream_password': webi_onstream_password,	
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery("#webionstream .webi_loader").hide();
				if(response == 'Success'){
					jQuery("#onstreamwebinar_top .sec_1").show();
					jQuery("#onstreamwebinar_top .sec_2").hide();
					jQuery("#webionstream").hide();
					jQuery("#onstream_status").html("Connected");
					jQuery("#onstream_status").removeClass("err");	
					jQuery('#onstream_topmsg').html("Successfully connected");
					jQuery('#onstream_topmsg').show();
				}
				else
				{
					jQuery('#onstream_msg').html("Wrong API username or password.");
					jQuery('#onstream_msg').addClass("err");
					jQuery('#onstream_msg').show();					
				}		
			});
		}
	}

	if(jQuery(this).attr('id') == 'auth_readytalk')
	{		
		var webi_rt_number = jQuery("#webi_rt_number").val();
		var webi_rt_code = jQuery("#webi_rt_code").val();
		var webi_rt_passcode = jQuery("#webi_rt_passcode").val();
		if(webi_rt_number == '')
		{
			alert("ReadyTalk Toll-Free Number field is required");
			jQuery("#webi_rt_number").focus();
		}
		else if(webi_rt_code == '')
		{
			alert("Readytalk Access code field is required");
			jQuery("#webi_rt_code").focus();
		}
		else if(webi_rt_passcode == '')
		{
			alert("Readytalk Passcode field is required");
			jQuery("#webi_rt_passcode").focus();
		}
		else
		{
			jQuery("#webireadytalk .webi_loader").show();
			var data = {
				'action': 'webi_authgotowebinar',
				'webi_rt_number': webi_rt_number, // We pass php values differently!
				'webi_rt_code': webi_rt_code,
				'webi_rt_passcode': webi_rt_passcode
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery("#webireadytalk .webi_loader").hide();	
				if(response == 'Success'){
					jQuery("#readytalkwebinar_top .sec_1").show();
					jQuery("#readytalkwebinar_top .sec_2").hide();
					jQuery("#webireadytalk").hide();
					jQuery("#readytalk_status").html("Connected");
					jQuery("#readytalk_status").removeClass("err");	
					jQuery('#readytalk_topmsg').html("Successfully connected");
					jQuery('#readytalk_topmsg').show();
				}
				else
				{
					jQuery('#readytalk_msg').html("Wrong toll-free number or access code or passcode.");
					jQuery('#readytalk_msg').addClass("err");
					jQuery('#readytalk_msg').show();
				}
			});
		}
	}	
	
	if(jQuery(this).attr('id') == 'auth_zoom')
	{		
		var webi_zoom_key = jQuery("#webi_zoom_key").val();
		var webi_zoom_secret = jQuery("#webi_zoom_secret").val();
		var zoom_url = jQuery("#zoom_url").val();
		if(webi_zoom_key == '')
		{
			alert("Zoom Consumer Key field is required");
			jQuery("#webi_zoom_key").focus();
		}
		else if(webi_zoom_secret == '')
		{
			alert("GoToWebinar Consumer Secret field is required");
			jQuery("#webi_zoom_secret").focus();
		}
		else
		{
			jQuery("#webizoom .webi_loader").show();
			var data = {
				'action': 'webi_authgotowebinar',
				'webi_zoom_key': webi_zoom_key, // We pass php values differently!
				'webi_zoom_secret': webi_zoom_secret,	
			};		
			jQuery.post(ajax_var.url, data, function (response) {				
				if(response == 'Success'){
					jQuery(location).attr('href','https://zoom.us/oauth/authorize?response_type=code&client_id='+webi_zoom_key+'&redirect_uri='+zoom_url);					
				}
			});
		}
	}
});

function webi_confirm(title, msg, $true, $false, $link) { /*change*/
	var $content =  "<div class='webi_conf_dialog-ovelay'>" +
					"<div class='dialog'><header>" +
					 " <h3> " + title + " </h3> " +					 
				 "</header>" +
				 "<div class='dialog-msg'>" +
					 " <p> " + msg + " </p> " +
				 "</div>" +
				 "<footer>" +
					 "<div class='controls'>" +
						 " <button class='webi_conf_button webi_conf_button-danger doAction'>" + $true + "</button> " +
						 " <button class='webi_conf_button webi_conf_button-default cancelAction'>" + $false + "</button> " +
					 "</div>" +
				 "</footer>" +
			  "</div>" +
			"</div>";
	jQuery('body').prepend($content);
	jQuery('.doAction').click(function () {		
		jQuery(this).parents('.webi_conf_dialog-ovelay').fadeOut(100, function () {
			jQuery(this).remove();
		});
		if($link == 'dis_gotowebinar'){
			jQuery("#webigotowebinar_top .webi_loader").show();
			var data = {
				'action': 'webi_disconnect_platform',
				'platform': 'goto'		
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery("#webigotowebinar_top .webi_loader").hide();
				if(response == 'Success'){
					jQuery(".goto_notice").show();
					jQuery("#webigotowebinar_top .sec_2").show();
					jQuery("#webigotowebinar_top .sec_1").hide();
					jQuery("#goto_status").html("Not connected");
					jQuery("#goto_status").addClass("err");
					jQuery("#webi_gotowebinar_key, #webi_gotowebinar_secret").val('');
					jQuery('#gotowebinar_topmsg').html("Successfully disconnected");
					jQuery('#gotowebinar_topmsg').show();
				}
				else
				{
					jQuery('#gotowebinar_topmsg').html("Something went wrong, please try again.");
					jQuery('#gotowebinar_topmsg').addClass("err");
					jQuery('#gotowebinar_topmsg').show();
				}
			});
		}
		
		if($link == 'dis_zoom'){
			jQuery("#zoomwebinar_top .webi_loader").show();
			var data = {
				'action': 'webi_disconnect_platform',
				'platform': 'zoom'		
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery("#zoomwebinar_top .webi_loader").hide();
				if(response == 'Success'){
					jQuery(".zoom_notice").show();
					jQuery("#zoomwebinar_top .sec_2").show();
					jQuery("#zoomwebinar_top .sec_1").hide();
					jQuery("#zoom_status").html("Not connected");
					jQuery("#zoom_status").addClass("err");
					jQuery("#webi_zoom_key, #webi_zoom_secret").val('');
					jQuery('#zoom_topmsg').html("Successfully disconnected");
					jQuery('#zoom_topmsg').show();
				}
				else
				{
					jQuery('#zoom_topmsg').html("Something went wrong, please try again.");
					jQuery('#zoom_topmsg').addClass("err");
					jQuery('#zoom_topmsg').show();
				}
			});
		}
		
		if($link == 'dis_onstream'){
			jQuery("#onstreamwebinar_top .webi_loader").show();
			var data = {
				'action': 'webi_disconnect_platform',
				'platform': 'onstream'		
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery("#onstreamwebinar_top .webi_loader").hide();
				if(response == 'Success'){
					jQuery("#onstreamwebinar_top .sec_2").show();
					jQuery("#onstreamwebinar_top .sec_1").hide();
					jQuery("#onstream_status").html("Not connected");
					jQuery("#onstream_status").addClass("err");
					jQuery("#webi_onstream_username, #webi_onstream_password").val('');	
					jQuery('#onstream_topmsg').html("Successfully disconnected");
					jQuery('#onstream_topmsg').show();	
				}
				else
				{
					jQuery('#onstream_topmsg').html("Something went wrong, please try again.");
					jQuery('#onstream_topmsg').addClass("err");
					jQuery('#onstream_topmsg').show();
				}
			});
		}
		
		if($link == 'dis_readytalk'){
			jQuery("#readytalkwebinar_top .webi_loader").show();
			var data = {
				'action': 'webi_disconnect_platform',
				'platform': 'readytalk'		
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery("#readytalkwebinar_top .webi_loader").hide();
				if(response == 'Success'){
					jQuery("#readytalkwebinar_top .sec_2").show();
					jQuery("#readytalkwebinar_top .sec_1").hide();
					jQuery("#readytalk_status").html("Not connected");
					jQuery("#readytalk_status").addClass("err");
					jQuery("#webi_rt_number, #webi_rt_code, #webi_rt_passcode").val('');
					jQuery('#readytalk_topmsg').html("Successfully disconnected");
					jQuery('#readytalk_topmsg').show();		
				}
				else
				{
					jQuery('#readytalk_topmsg').html("Something went wrong, please try again.");
					jQuery('#readytalk_topmsg').addClass("err");
					jQuery('#readytalk_topmsg').show();
				}
			});
		}
		
		if($link == 'downgrade_license'){
			jQuery(".up_response").hide();
			jQuery(".up_response").empty();
			jQuery(".up_response").removeClass('err');
			jQuery(".up_response").removeClass('succ');
			jQuery(".webidowngrade_sec .webi_loader").show();
			var data = {
				'action': 'webi_downgrade_license'	
			};
			jQuery.post(ajax_var.url, data, function (response) {
				jQuery(".webidowngrade_sec .webi_loader").hide();
				if(response == 0)
				{
					jQuery(".up_response").html("Oops.. something went wrong, please try again.");	
					jQuery(".up_response").addClass('err');	
				}
				else if(response == 1)
				{					
					jQuery(".up_response").html("Your plugin is downgraded successfully. Please wait while we redirect you.");
					jQuery(".up_response").addClass('succ');
					setTimeout(function(){ 
						location.reload();
					}, 1000);
					
				}
				jQuery(".up_response").show();
			});
		}
		
		if($link == 'unpublish_evt')
		{
			jQuery(".webi_publish_event_msg").hide();
		}
	});
	jQuery('.cancelAction').click(function () {
		jQuery(this).parents('.webi_conf_dialog-ovelay').fadeOut(100, function () {
			jQuery(this).remove();
		});
	});     
}

jQuery(document).on('click','.disconnect_platform',function(){
	jQuery(".webi_con_response").hide();
	jQuery(".webi_con_response").removeClass("err");
	disconnect_id = jQuery(this).attr('id');
	if(disconnect_id == 'dis_gotowebinar')
	{
		var disconnect_name = 'GoToWebinar';
	}
	else if(disconnect_id == 'dis_zoom')
	{
		var disconnect_name = 'Zoom';
	}
	else if(disconnect_id == 'dis_onstream')
	{
		var disconnect_name = 'Onstream';
	}
	else if(disconnect_id == 'dis_readytalk')
	{
		var disconnect_name = 'ReadyTalk';
	}
	webi_confirm('Disconnect webinara platform', 'Do you really want to disconnect? All of your webinars connected to '+disconnect_name+' will be hidden from your front-end.', 'Yes, I\'m sure', 'No, keep it connected', disconnect_id); /*change*/
});

jQuery(document).on('click','#webi_upgrade_btn',function(){
	jQuery('#webi_upgrade_section').toggle();
});

jQuery(document).on('click','#update_webi_prof', function(){
	jQuery(".acc_response").hide();
	jQuery(".acc_response").empty();
	jQuery(".acc_response").removeClass('succ');
	jQuery(".acc_response").removeClass('err');
	jQuery("#webi_update_mp .webi_loader").show();
	var data = {
		'action': 'webi_send_profilelink',
	}
	jQuery.post(ajax_var.url, data, function (response) {		
		jQuery("#webi_update_mp .webi_loader").hide();
		if(response == 0)
		{
			jQuery("#webi_update_mp .acc_response").html("Something went wrong, please try again");
			jQuery("#webi_update_mp .acc_response").addClass('err');
		}
		else if(response == 1)
		{
			jQuery("#webi_update_mp .acc_response").html("We have sent you an email with a link for updating your profile. Please check it. Thanks!");
			jQuery("#webi_update_mp .acc_response").addClass('succ');
		}
		else
		{
			jQuery("#webi_update_mp .acc_response").html(response);
			jQuery("#webi_update_mp .acc_response").addClass('err');
		}
		jQuery("#webi_update_mp .acc_response").show();
	});
});

jQuery(document).on('click','#webi_upgrade_license', function(){
	jQuery(".acc_response").hide();
	jQuery(".acc_response").empty();
	jQuery(".acc_response").removeClass('succ');
	jQuery(".acc_response").removeClass('err');
	jQuery("#webi_upgrade_section .webi_loader").hide();
	var ustep = jQuery("#ustep").val();
	var webireg_cname = jQuery("#webireg_cname").val();
	var webireg_fname = jQuery("#webireg_fname").val();
	var webireg_lname = jQuery("#webireg_lname").val();
	var webireg_email = jQuery("#webireg_email").val();	
	var webireg_jobtitle = jQuery("#webireg_jobtitle").val();
	var webireg_joblevel = jQuery("#webireg_joblevel").val();
	var webireg_jobfunction = jQuery("#webireg_jobfunction").val();
	var webireg_country = jQuery("#webireg_country").val();
	var webireg_prefzone = jQuery("#webireg_prefzone").val();
	var webireg_websolution = jQuery("#webireg_websolution").val();
	var webireg_cindustry = jQuery("#webireg_cindustry").val();
	var webireg_website = jQuery("#webireg_website").val();
	var webireg_csize = jQuery("#webireg_csize").val();
	var webireg_cdesc = jQuery("#webireg_cdesc").val();
						
	if(ustep == 1)
	{
		if(webireg_email.length == 0)
		{
			alert("Email is required.");
			jQuery("#webireg_email").focus();
		}
		else if(!/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(webireg_email))
		{
			alert("Email ID is invalid.");
			jQuery("#webireg_email").focus();
		}
		else
		{
			jQuery("#webi_upgrade_section .webi_loader").show();
			var data = {
				'action': 'webi_check_account',
				'webireg_email': webireg_email,
				'us': ustep,	
			};
		}
	}
	else if(ustep == 2)
	{
		if(webireg_email.length == 0)
		{
			alert("Email is required.");
			jQuery("#webireg_email").focus();
		}
		else if(!/^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/.test(webireg_email))
		{
			alert("Email ID is invalid.");
			jQuery("#webireg_email").focus();
		}		
		else if(webireg_fname.length == 0)
		{
			alert("First name is required.");
			jQuery("#webireg_fname").focus();
		}
		else if(webireg_lname.length == 0)
		{
			alert("Last name is required.");
			jQuery("#webireg_lname").focus();
		}
		else if(webireg_jobtitle.length == 0)
		{
			alert("Job title is required.");
			jQuery("#webireg_jobtitle").focus();
		}
		else if(webireg_joblevel == null)
		{
			alert("Job level is required.");
			jQuery("#webireg_joblevel").focus();
		}
		else if(webireg_jobfunction == null)
		{
			alert("Job function is required.");
			jQuery("#webireg_jobfunction").focus();
		}
		else if(webireg_country == null)
		{
			alert("Country is required.");
			jQuery("#webireg_country").focus();
		}
		else if(webireg_prefzone == null)
		{
			alert("Prefered timezone is required.");
			jQuery("#webireg_prefzone").focus();
		}
		else if(webireg_websolution == null)
		{
			alert("Webinar solution is required.");
			jQuery("#webireg_websolution").focus();
		}
		else if(webireg_cname.length == 0)
		{
			alert("Company name is required.");
			jQuery("#webireg_cname").focus();
		}
		else if(webireg_cindustry == null)
		{
			alert("Company industry is required.");
			jQuery("#webireg_cindustry").focus();
		}
		else if(webireg_website.length == 0)
		{
			alert("Website is required.");
			jQuery("#webireg_website").focus();
		}
		else if(webireg_csize == null)
		{
			alert("Company size is required.");
			jQuery("#webireg_csize").focus();
		}
		else if(webireg_cdesc.length == 0)
		{
			alert("Company desciption is required.");
			jQuery("#webireg_cdesc").focus();
		}
		else
		{
			jQuery("#webi_upgrade_section .webi_loader").show();
			var data = {
				'action': 'webi_check_account',
				'webireg_email': webireg_email,	
				'webireg_cname': webireg_cname,	
				'webireg_fname': webireg_fname,	
				'webireg_lname': webireg_lname,
				'webireg_jobtitle': webireg_jobtitle,
				'webireg_joblevel': webireg_joblevel,
				'webireg_jobfunction': webireg_jobfunction,
				'webireg_country': webireg_country,
				'webireg_prefzone': webireg_prefzone,
				'webireg_websolution': webireg_websolution,
				'webireg_cindustry': webireg_cindustry,
				'webireg_website': webireg_website,
				'webireg_csize': webireg_csize,
				'webireg_cdesc': webireg_cdesc,
				'us': ustep,
			};	
		}			
	}
	
	jQuery.post(ajax_var.url, data, function (response) {
		jQuery("#webi_upgrade_section .webi_loader").hide();
		if(response == 1)
		{
			jQuery(".acc_response").html("We see that you already have an account on Webinara. We have now sent you an email with an upgrade plugin link. Please check it. Thanks!");
			jQuery(".acc_response").addClass('succ');
			jQuery("#license_key").focus();
		}
		else if(response == 2)
		{
			jQuery(".acc_response").html("We found that you already have license key with this account on Webinara. Please create another account for another license key or contact with support. Thanks");
			jQuery(".acc_response").addClass('err');
		}
		else if(response == 3)
		{
			jQuery(".acc_response").html("We found that you have paid plan subscription with this account. Kindly create new account for getting license key or contact with support. Thanks");
			jQuery(".acc_response").addClass('err');
		}
		else if(response == 4)
		{
			jQuery(".acc_response").html("Something went wrong, please try with another email.");
			jQuery(".acc_response").addClass('err');
		}
		else if(response == 0)
		{
			jQuery(".acc_response").html("We have now sent you an email with an upgrade plugin link. Please check it. Thanks!");
			jQuery(".acc_response").addClass('succ');
			jQuery(".webi_addi_field").hide();
			jQuery("#license_key").focus();
		}
		else if(response == 5)
		{
			jQuery(".webi_addi_field").show();
			jQuery("#ustep").val(2);
		}
		if(response != 5)
		{
			jQuery("#webireg_cname, #webireg_fname, #webireg_lname, #webireg_email, #webireg_jobtitle, #webireg_joblevel, #webireg_jobfunction, #webireg_country, #webireg_prefzone, #webireg_websolution, #webireg_cindustry, #webireg_website, #webireg_csize, #webireg_cdesc").val('');
		}
		jQuery(".acc_response").show();
	});			
});

jQuery(document).on('submit','#webinara_licensekey_form',function(e){
	e.preventDefault();
	jQuery(".up_response").hide();
	jQuery(".up_response").empty();
	jQuery(".up_response").removeClass('err');
	jQuery(".up_response").removeClass('succ');
	jQuery(".webi_fremium_sec .webi_loader").hide();
	var license_key = jQuery("#license_key").val();
	if(license_key.length == 0)
	{
		alert("License key is required.");
		jQuery("#license_key").focus();
	}	
	else
	{
		jQuery(".webi_fremium_sec .webi_loader").show();
		var data = {
			'action': 'webi_check_license',
			'license_key': license_key,
		};		
		jQuery.post(ajax_var.url, data, function (response) {			
			jQuery(".webi_fremium_sec .webi_loader").hide();
			if(response == 0)
			{
				jQuery(".up_response").html("Invalid license key. Please enter valid licesne key.");	
				jQuery(".up_response").addClass('err');	
			}
			else if(response == 1)
			{
				jQuery(".up_response").html("Congratulations. Please wait while we enable premium features.");
				jQuery(".up_response").addClass('succ');
				setTimeout(function(){ 
					jQuery(location).attr('href',jQuery("#license_tab_url").val());
				}, 1000);
				
			}
			else if(response == 2)
			{
				jQuery(".up_response").html("Sorry, your license key has expired. Please purchase new one.");
				jQuery(".up_response").addClass('err');
			}
			else if(response == 3)
			{
				jQuery(".up_response").html("License key is already used on another server. Please buy new one.");
				jQuery(".up_response").addClass('err');
			}
			jQuery(".up_response").show();
		});
	}
});

jQuery(document).on('click','#webi_save_license', function(){
	jQuery(".up_response").hide();
	jQuery(".up_response").empty();
	jQuery(".up_response").removeClass('err');
	jQuery(".up_response").removeClass('succ');
	jQuery(".webi_fremium_sec .webi_loader").hide();
	var license_key = jQuery("#license_key").val();
	if(license_key.length == 0)
	{
		alert("License key is required.");
		jQuery("#license_key").focus();
	}	
	else
	{
		jQuery(".webi_fremium_sec .webi_loader").show();
		var data = {
			'action': 'webi_check_license',
			'license_key': license_key,
		};		
		jQuery.post(ajax_var.url, data, function (response) {			
			jQuery(".webi_fremium_sec .webi_loader").hide();
			if(response == 0)
			{
				jQuery(".up_response").html("Invalid license key. Please enter valid licesne key.");	
				jQuery(".up_response").addClass('err');	
			}
			else if(response == 1)
			{
				jQuery(".up_response").html("Congratulations. Please wait while we enable premium features.");
				jQuery(".up_response").addClass('succ');
				setTimeout(function(){ 
					jQuery(location).attr('href',jQuery("#license_tab_url").val());
				}, 1000);
				
			}
			else if(response == 2)
			{
				jQuery(".up_response").html("Sorry, your license key has expired. Please purchase new one.");
				jQuery(".up_response").addClass('err');
			}
			else if(response == 3)
			{
				jQuery(".up_response").html("License key is already used on another server. Please buy new one.");
				jQuery(".up_response").addClass('err');
			}
			jQuery(".up_response").show();
		});
	}
});

jQuery(document).on('click','#downgrade_license',function(){
	webi_confirm('Confirm Downgrade', 'Are you sure you want to downgrade your Webinara Premium License? This will disable all Premium features and return you to the free version of Webinara.', 'Yes, I\'m sure', 'No, keep it upgraded', 'downgrade_license'); /*change*/
});

jQuery(document).on('change', 'input[name="_webi_publish_event"]:checkbox', function(){
	if(jQuery(this).hasClass('evt_shared'))
	{
		if(this.checked) {
			jQuery(".webi_publish_event_msg").html("This webinar is published on Webinara.com.");	 
		}
		else
		{
			jQuery(".webi_publish_event_msg").html("When you submit, this webinar and its related data will be delete from Webinara.com server.");			
		}		
	}
	else
	{		
		if(this.checked) {
			jQuery(".webi_publish_event_msg").html("When you select this option, we publish this webinar to Webinara.com and promote it publicly across the web.");	 
		}
		else
		{
			 jQuery(".webi_publish_event_msg").html("");	 
		}
	}
});

jQuery(document).on('click',"#renew_license",function(){
	jQuery(".up_response").hide();
	jQuery(".up_response").empty();
	jQuery(".up_response").removeClass('succ');
	jQuery(".up_response").removeClass('err');
	jQuery(".webi_loader").hide();	
	var lk = jQuery("#license_key").val();
	if(lk != '')
	{
		jQuery(".webi_loader").show();
		var data = {
			'action': 'webi_renew_license',
			'license_key': lk,
		};	
		
		jQuery.post(ajax_var.url, data, function (response) {	
			jQuery(".webi_loader").hide();
			if(response == 1)
			{
				jQuery(".up_response").html("We have now sent you an email with a renewal link. Please check it. Thanks!");
				jQuery(".up_response").addClass('succ');
			}			
			else if(response == 0)
			{
				jQuery(".up_response").html("Something went wrong with your account.");
				jQuery(".up_response").addClass('err');
			}					
			jQuery(".up_response").show();
		});
	}
	else
	{
		alert("Something went wrong");
	}
});

jQuery(document).on('click','#webi_update_profile',function(){
	jQuery(".acc_response").hide();
	jQuery(".acc_response").empty();
	jQuery(".acc_response").removeClass('succ');
	jQuery(".acc_response").removeClass('err');
	jQuery("#webi_profile_section .webi_loader").hide();	
	var webireg_cname = jQuery("#webireg_cname").val();
	var webireg_fname = jQuery("#webireg_fname").val();
	var webireg_lname = jQuery("#webireg_lname").val();
	var webireg_jobtitle = jQuery("#webireg_jobtitle").val();
	var webireg_joblevel = jQuery("#webireg_joblevel").val();
	var webireg_jobfunction = jQuery("#webireg_jobfunction").val();
	var webireg_country = jQuery("#webireg_country").val();
	var webireg_prefzone = jQuery("#webireg_prefzone").val();
	var webireg_websolution = jQuery("#webireg_websolution").val();
	var webireg_cindustry = jQuery("#webireg_cindustry").val();
	var webireg_website = jQuery("#webireg_website").val();
	var webireg_csize = jQuery("#webireg_csize").val();
	var webireg_cdesc = jQuery("#webireg_cdesc").val();
						
			
	if(webireg_fname.length == 0)
	{
		alert("First name is required.");
		jQuery("#webireg_fname").focus();
	}
	else if(webireg_lname.length == 0)
	{
		alert("Last name is required.");
		jQuery("#webireg_lname").focus();
	}
	else if(webireg_jobtitle.length == 0)
	{
		alert("Job title is required.");
		jQuery("#webireg_jobtitle").focus();
	}
	else if(webireg_joblevel == null)
	{
		alert("Job level is required.");
		jQuery("#webireg_joblevel").focus();
	}
	else if(webireg_jobfunction == null)
	{
		alert("Job function is required.");
		jQuery("#webireg_jobfunction").focus();
	}
	else if(webireg_country == null)
	{
		alert("Country is required.");
		jQuery("#webireg_country").focus();
	}
	else if(webireg_prefzone == null)
	{
		alert("Prefered timezone is required.");
		jQuery("#webireg_prefzone").focus();
	}
	else if(webireg_websolution == null)
	{
		alert("Webinar solution is required.");
		jQuery("#webireg_websolution").focus();
	}
	else if(webireg_cname.length == 0)
	{
		alert("Company name is required.");
		jQuery("#webireg_cname").focus();
	}
	else if(webireg_cindustry == null)
	{
		alert("Company industry is required.");
		jQuery("#webireg_cindustry").focus();
	}
	else if(webireg_website.length == 0)
	{
		alert("Website is required.");
		jQuery("#webireg_website").focus();
	}
	else if(webireg_csize == null)
	{
		alert("Company size is required.");
		jQuery("#webireg_csize").focus();
	}
	else if(webireg_cdesc.length == 0)
	{
		alert("Company desciption is required.");
		jQuery("#webireg_cdesc").focus();
	}
	else
	{
		jQuery("#webi_profile_section .webi_loader").show();
		var data = {
			'action': 'webi_update_profile',
			'webireg_cname': webireg_cname,	
			'webireg_fname': webireg_fname,	
			'webireg_lname': webireg_lname,
			'webireg_jobtitle': webireg_jobtitle,
			'webireg_joblevel': webireg_joblevel,
			'webireg_jobfunction': webireg_jobfunction,
			'webireg_country': webireg_country,
			'webireg_prefzone': webireg_prefzone,
			'webireg_websolution': webireg_websolution,
			'webireg_cindustry': webireg_cindustry,
			'webireg_website': webireg_website,
			'webireg_csize': webireg_csize,
			'webireg_cdesc': webireg_cdesc,
		};
		jQuery.post(ajax_var.url, data, function (response) {	
			jQuery("#webi_profile_section .webi_loader").hide();
			if(response == 1)
			{
				jQuery("#webi_profile_section .acc_response").html("Updated");
				jQuery("#webi_profile_section .acc_response").addClass('succ');
			}			
			else if(response == 0)
			{
				jQuery("#webi_profile_section .acc_response").html("Error");
				jQuery("#webi_profile_section .acc_response").addClass('err');
			}					
			jQuery("#webi_profile_section .acc_response").show();
		}); 
	}					
});