jQuery(document).on("click",".webi_reg_btn",function(){
	jQuery("#webi_rf_fname, #webi_rf_lname, #webi_rf_email").val('');
	jQuery(".webi_register_response").html('');
	jQuery(".webi_register_response").hide();
}); 

jQuery(document).on("submit","#webi-register-form",function(e){
	e.preventDefault();
	jQuery("#webi-register-form p").removeClass("webi_form_err");
	var rf_fname = jQuery("#webi_rf_fname").val();
	var rf_lname = jQuery("#webi_rf_lname").val();
	var rf_email = jQuery("#webi_rf_email").val();
	var rf_platform = jQuery("#webi_rf_platform").val();
	var rf_number = jQuery("#webi_rf_number").val();
	
	var rf_info = jQuery("#webi_rf_info").val();
	if(rf_fname.length == 0)
	{
		jQuery("#webi_rf_fname").closest("p").addClass("webi_form_err");
		jQuery("#webi_rf_fname").focus();
		jQuery(".webi_register_response").html("Please fill all required fields");
		jQuery(".webi_register_response").show();
	}
	else if(rf_lname.length == 0)
	{
		jQuery("#webi_rf_lname").closest("p").addClass("webi_form_err");
		jQuery("#webi_rf_lname").focus();
		jQuery(".webi_register_response").html("Please fill all required fields");
		jQuery(".webi_register_response").show();
	}
	else if(rf_email.length == 0)
	{
		jQuery("#webi_rf_email").closest("p").addClass("webi_form_err");
		jQuery("#webi_rf_email").focus();
		jQuery(".webi_register_response").html("Please fill all required fields");
		jQuery(".webi_register_response").show();
	}
	else
	{
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		if(emailReg.test( rf_email ))
		{
			jQuery(".webi_register_response").html("Registering...");
			jQuery(".webi_register_response").show();
			var data = {
				'action': 'webi_register_user',
				'rf_fname': rf_fname,
				'rf_lname': rf_lname,
				'rf_email': rf_email,				
				'rf_platform': rf_platform,
				'rf_info': rf_info,
				'rf_number': rf_number,
			};		
			jQuery.post(ajax_var.url, data, function (response) {
				console.log(response);
				alert(response);				
				jQuery(".webi_register_response").html("");
				jQuery(".webi_register_response").hide();
				if(response == 201)
				{
					jQuery(".webi_register_response").html("Registered successfully. You will receive a confirmation email. Thanks");
					jQuery(".webi_register_response").show();
					setTimeout(
						function() 
						{
							jQuery.modal.close();
							jQuery("#webi_rf_fname, #webi_rf_lname, #webi_rf_email").val('');
						}, 3000);					
				}
				else if(response == 409)
				{
					jQuery(".webi_register_response").html("Already registered with this webinar.");
					jQuery(".webi_register_response").show();
				}
				else
				{
					jQuery(".webi_register_response").html("Something went wrong, please try again or contact support.");
					jQuery(".webi_register_response").show();
				}
			});
		}
		else
		{
			jQuery("#webi_rf_email").closest("p").addClass("webi_form_err");
			jQuery(".webi_register_response").html("Please fill valid email");
			jQuery(".webi_register_response").show();
			jQuery("#webi_rf_email").focus();
		}
	}
});

	