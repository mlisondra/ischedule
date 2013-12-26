function validate_signup(){

	first_name = $('#first_name').val();
	last_name = $('#last_name').val();
	email = $('#user_email').val();
	password = $('#password').val();
	password2 = $('#password2').val();
	num_errors = 0;
	
	hide_errors();

	if(first_name == ''){
		$('#first_name_error').show();
		num_errors++;
	}else{
		$('#first_name_error').hide();
	}

	if(last_name == ''){
		$('#last_name_error').show();
		num_errors++;
	}else{
		$('#last_name_error').hide();
	}
	
	if(email == ''){
		$('#email_error').show();
		num_errors++;
	}else{
		$('#email_error').hide();
	}
	
	if(password == ''){
		document.getElementById('password_error').style.display = "block";
		num_errors++;
	}
	
	if(password2 == ''){
		document.getElementById('password2_error').style.display = "block";
		num_errors++;
	}

	if((password != '') && (password2 != '')){
		if(password != password2){
			document.getElementById('passwords_match_error').style.display = "block";
			num_errors++;
		}
	}
	
	if(num_errors > 0){
		return false;
	}else{
		new Ajax.Request('../ajax/',
			{ method : 'post', 
		    	parameters: {action : "create_account", email : $('email').value, password : $('password').value},
				onComplete: function(transport){
						var response = transport.responseText || "no response text";
						if(response == 1){
							$('signup_form').reset();
							$('notification').innerHTML = 'Your account has been created. <a href="../login/">Log In Now</a>.';
						}else if(response == "email exists"){
							$('notification').innerHTML = "An account already exists with the email address.";
						}
					}
				}	
		);
	}
}

function hide_errors(){
	$('#email_error').hide();
	document.getElementById('password_error').style.display = "none";
	document.getElementById('password2_error').style.display = "none";
	document.getElementById('passwords_match_error').style.display = "none";
}

	$(document).ready(function() {
		$('form#login_form').submit(
			function(form){ 
				form.preventDefault();
						var num_errors = 0;
						var error_message = "";
						
						var user_email = $('#user_email').val();
						var password = $('#password').val();
				
						//Clear error message
						$('notification').html(error_message);
				
						if(user_email == ''){
							error_message = "Email address is required<br/>";
							num_errors++;
						}else{
							if(!IsEmail(user_email)){
								error_message = "Invalid Email Address format<br/>";
								num_errors++;		
							}
						}
						if(password == ''){
							error_message = error_message + "Password is required<br/>";
							num_errors++;
						}
		
						if(num_errors == 0){
							var post = $('#login_form').serialize();
							$.post('../schedule/auth',post,
								function(data){
									var response = data.status;
									
									if(response == 'error'){
										$("#notification").html(data.message);
										$("#notification").show();
									}else if(response == 'ok'){
										$("#login_form")[0].reset();
										window.location.href = "../schedule/";
									}
									
								},"json");
						}else if(num_errors > 0){
							$("#notification").html(error_message);
							$("#notification").show();
						}
			}
		); //end submit function

	$('#reset_password_link').click(
		function(){	show_reset_password(); }
	);
	
	$("form#reset_password_form").submit(
		function(form){
			form.preventDefault();
			var num_errors = 0;
			var error_message = "";
			var user_email = $("#user_email").val();
			if(user_email == ''){
				error_message = "Email address is required<br/>";
				num_errors++;
			}else{
				if(!IsEmail(user_email)){
					error_message = "Invalid Email Address format<br/>";
					num_errors++;		
				}
			}
			if(num_errors == 0){
				var post = $('#reset_password_form').serialize();
				post = post + "&action=reset_password";
				$.post("../schedule/auth",post,
					function(data){
						if(data == 1){
							$("#notification").html("Your new password has been sent to you.");
						}else if(data == "invalid user"){
							$("#notification").html("We could not find a user with that email address.");
						}else if(data == 0){
							$("#notification").html("We could not reset your password at this time. Try again later.");
						}
						$("#notification").show();
					}
				);
			}else{
				$("#notification").html(error_message);
				$("#notification").show();
			}
			
			
		}
	);
	
	} //end overall handler for document ready
	);

function show_reset_password(){
	$("#notification").html("");
	$("#login_form").remove();
	$("#reset_password_form").show();	
}

function cancel_reset_password(){
	window.location.href = "../login/index.php";
}

//Email Validator
function IsEmail(str) {
	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	
	if (str.indexOf(at)==-1){
	   return false
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   return false
	}
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
	    return false
	}
	if (str.indexOf(at,(lat+1))!=-1){
	    return false
	}
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
	    return false
	}
	if (str.indexOf(dot,(lat+2))==-1){
	    return false
	}
	if (str.indexOf(" ")!=-1){
	    return false
	}
	return true
}

function validate_add_contact(){
	//clear errors
	$("#form_notification").html("");
	$("#form_notification").hide();
	
	var first_name = $("#add_contact_form #first_name").val();
	var last_name = $("#add_contact_form #last_name").val();
	var email = $("#add_contact_form #email").val();
	var phone = $("#add_contact_form #phone").val();
	var phone_carrier = $("#add_contact_form #phone_carrier").val();
	var reminder_type = $("#add_contact_form #reminder_type").val();
	var num_errors = 0;
	var error_message = "";

	var email_domain_index = email.indexOf("@");
	var email_domain_name = email.slice(email_domain_index + 1,email.length);
					
	if(first_name == ''){
		error_message = error_message + "First Name is required<br/>";
		num_errors++;
	}
	if(last_name == ''){
		error_message = error_message + "Last Name is required<br/>";
		num_errors++;
	}
	
	if(email == ''){
		error_message = error_message + "Email address is required<br/>";
		num_errors++;
	}else if(email_domain_name == "googlegroups.com" || email_domain_name == "googlegroup.com" || email_domain_name == "yahoogroups.com" || email_domain_name == "yahoogroup.com"){
		error_message = error_message + "Google or Yahoo Groups emails are not allowed<br/>";
		num_errors++;				
	}else{
		if(!IsEmail(email)){
			error_message = error_message + "Invalid Email Address format<br/>";
			num_errors++;		
		}
	}

	if(phone == ''){
		error_message = error_message + "Phone is required<br/>";
		num_errors++;
	}
	
	if(phone_carrier == ''){
		error_message = error_message + "Phone Carrier is required<br/>";
		num_errors++;
	}

	if(reminder_type == ""){
		error_message = error_message + "Reminder Type is required<br/>";
		num_errors++;
	}	
	
	if(num_errors > 0){
		$("#form_notification").html(error_message);
		$("#form_notification").show();
		return false;
	}else{
		var post = $('#add_contact_form').serialize();
		var post_result = "";
		$.ajax({url:'process.php',data:post,type:"POST",async:false,
			success:function(data){
				post_result = data;
			}
		});
		if(post_result == 1){
			//return true;
			$("#add_contact_form").remove();
			$("#modal_container").html("Contact Added");
			reset_modal_buttons(); //reset buttons
		}else{
			return false;
		}
	}
}

function validate_edit_contact(){
	//clear errors
	$("#form_notification").html("");
	$("#form_notification").hide();
	
	var first_name = $("#edit_contact_form #first_name").val();
	var last_name = $("#edit_contact_form #last_name").val();
	var email = $("#edit_contact_form #email").val();
	var phone = $("#edit_contact_form #phone").val();
	var phone_carrier = $("#edit_contact_form #phone_carrier").val();
	var reminder_type = $("#add_contact_form #reminder_type").val();
	var num_errors = 0;
	var error_message = "";
	
	var email_domain_index = email.indexOf("@");
	var email_domain_name = email.slice(email_domain_index + 1,email.length);	

	if(first_name == ''){
		error_message = error_message + "First Name is required<br/>";
		num_errors++;
	}
	if(last_name == ''){
		error_message = error_message + "Last Name is required<br/>";
		num_errors++;
	}
	
	if(email == ''){
		error_message = error_message + "Email address is required<br/>";
		num_errors++;
	}else if(email_domain_name == "googlegroups.com" || email_domain_name == "googlegroup.com" || email_domain_name == "yahoogroups.com" || email_domain_name == "yahoogroup.com"){
		error_message = error_message + "Google or Yahoo Groups emails are not allowed<br/>";
		num_errors++;						
	}else{
		if(!IsEmail(email)){
			error_message = error_message + "Invalid Email Address format<br/>";
			num_errors++;		
		}
	}

	if(phone == ''){
		error_message = error_message + "Phone is required<br/>";
		num_errors++;
	}
	
	if(phone_carrier == ''){
		error_message = error_message + "Phone Carrier is required<br/>";
		num_errors++;
	}
	if(reminder_type == ""){
		error_message = error_message + "Reminder Type is required<br/>";
		num_errors++;
	}	
	
	
	if(num_errors > 0){
		$("#form_notification").html(error_message);
		$("#form_notification").show();
		return false;
	}else{
		var post = $('#edit_contact_form').serialize();
		var post_result = "";
		$.ajax({url:'process.php',data:post,type:"POST",async:false,
			success:function(data){
				post_result = data;
			}
		});
		if(post_result == 1){
			$("#edit_contact_form").remove();
			$("#modal_container").html("Contact Updated.");
			reset_modal_buttons(); //reset buttons
		}else{
			return false;
		}
	}
}

function validate_add_category(){
	clear_modal_form_notification();
	
	var name = $("#add_category_form #name").val();
	var color = $("#add_category_form #color").val();
	var num_errors = 0;
	var error_message = ""; 
	
	if(name == ''){
		error_message = error_message + "Calendar name is required<br/>";
		num_errors++;
	}

	if(num_errors > 0){
		$("#form_notification").html(error_message);
		$("#form_notification").show();
		return false;
	}else{
		var post = $('#add_category_form').serialize();
		var post_result = "";
		$.ajax({
			url:'../schedule/add_calendar',
			data:post,
			type:"POST",
			async:false,
			success:function(data){
				if(data.status == 1){
					get_categories(); // refressh list
                                        $('#modal_container').dialog('close');
				}else{
                                        $('#form_notification').html(data.message);
                                        $('#form_notification').show();
				}				
			},
                        dataType: "json"
		});
	}	
}

function validate_edit_category(){
	clear_modal_form_notification();
	
	var name = $("#edit_category_form #name").val();
	var color = $("#edit_category_form #color").val();
	var num_errors = 0;
	var error_message = ""; 
	
	if(name == ''){
		error_message = error_message + "Category name is required<br/>";
		num_errors++;
	}

	if(num_errors > 0){
		$("#form_notification").html(error_message);
		$("#form_notification").show();
		return false;
	}else{
		var post = $('#edit_category_form').serialize();
		var post_result = "";
		$.ajax({
			url:'process.php',
			data:post,
			type:"POST",
			async:false,
			success:function(data){
				post_result = data;
			}
		});
		if(post_result == 1){
			$("#edit_category_form").remove();
			$("#modal_container").html("Calendar Updated.");
			reset_modal_buttons(); //reset buttons
			refresh_calendar(); //from schedule.js
		}else{
			return false;
		}
	}	
}


//posts the selected contact ids
function delete_selected_contacts(){
	var post = $("#contact_list_form").serialize();
	post = post + "&action=delete_selected_contacts";
	$.ajax({url:'process.php',data:post,type:"POST",
		success:function(data){
			post_result = data;
		}
	});	
}

function validate_my_account(){
	//clear errors
	clear_modal_form_notification();
	
	var first_name = $("#my_account_form #first_name").val();
	var last_name = $("#my_account_form #last_name").val();
	var phone = $("#my_account_form #phone").val();
	var phone_carrier = $("#my_account_form #phone_carrier").val();
	var current_password = $("#my_account_form #current_password").val();
	var new_password = $("#my_account_form #new_password").val();
	var check_password = "no"; //alert(check_password);
	var num_errors = 0;
	var error_message = "";

	if(first_name == ''){
		error_message = error_message + "First Name is required<br/>";
		num_errors++;
	}
	if(last_name == ''){
		error_message = error_message + "Last Name is required<br/>";
		num_errors++;
	}

	if(phone == ''){
		error_message = error_message + "Phone is required<br/>";
		num_errors++;
	}
	
	if(phone_carrier == ''){
		error_message = error_message + "Phone Carrier is required<br/>";
		num_errors++;
	}		

	if(new_password != '' && current_password == ''){
		error_message = error_message + "You need to enter your Current Password<br/>";
		num_errors++;
	}else if(current_password != "" && new_password != ""){
		if(current_password == new_password){
			error_message = error_message + "Your new password must be different from your current one<br/>";
			num_errors++;			
		}else{
			check_password = "yes";
		}
	}
	//alert(check_password);
	if(num_errors > 0){
		$("#form_notification").html(error_message);
		$("#form_notification").show();
		return false;
	}else{
		var post = $('#my_account_form').serialize();
		post = post + "&check_password=yes";
		var post_result = "";
		$.ajax({url:'process.php',data:post,type:"POST",async:false,
			success:function(data){
				post_result = data;
			}
		});
		if(post_result == 1){
			$("#my_account_form").remove();
			$("#modal_container").html("Account Information Updated.");
			reset_modal_buttons(); //reset buttons
		}else{
			return false;
		}
	}
}

/**
* validate_add_event
* Validate Adding of an Event
*/
function validate_add_event(){
	var num_errors = 0;
	var error_message = "";
	var title = $("#title").val();
	var description = $("#description").val();
	var begin_date = $("#begin_date").val();
	var end_date = $("#end_date").val();
	var num_category_chosen = $('input[name="category"]:checked').length > 0;
	var reminder_type = $("#reminder_type").val();
	var num_reminder_chosen = $('input[name="reminder_notification[]"]:checked').length > 0;
	
	if(title == ""){
		error_message = error_message + "Title Required<br/>";
		num_errors++;
	}
	
	if(begin_date == ""){
		error_message = error_message + "Begin Date Required<br/>";
		num_errors++;	
	}
	if(end_date == ""){
		error_message = error_message + "End Date Required<br/>";
		num_errors++;	
	}

	if(!num_category_chosen){
		error_message = error_message + "Assign Event to at least one Category<br/>";
		num_errors++;
	}
	
	if(reminder_type == ""){
		error_message = error_message + "Choose a Reminder Type<br/>";
		num_errors++;
	}
	
	if(!num_reminder_chosen){
		error_message = error_message + "Choose at least 1 reminder time<br/>";
		num_errors++;
	}	
	if(num_errors == 0){
            
		var post = $('#add_event_form').serialize();
                $.post("/schedule/add_event",post,function(data){
                    if(data.status == "ok"){
                                    $("#add_event_form").remove();
                                    $("#modal_container").html("Event Added");
                                    $("#modal_container").dialog("close");
                                    //get_user_events();
                                    get_categories();
                                    refresh_calendar();                        
                    }
                },"json");
                
	}else{
		$("#form_notification").html(error_message);
		$("#form_notification").show();	
                //return false;
	}
}

function validate_edit_event(){
	var num_errors = 0;
	var error_message = "";
	var title = $("#title").val();
	var description = $("#description").val();
	var begin_date = $("#begin_date").val();
	var begin_time = $("#begin_time").val();
	var end_date = $("#end_date").val();
	var end_time = $("#end_time").val();
	var num_category_chosen = $('input[name="category"]:checked').length > 0;
	var reminder_type = $("#reminder_type").val();
	var num_reminder_chosen = $('input[name="reminder_notification[]"]:checked').length > 0;
	
	if(title == ""){
		error_message = error_message + "Title Required<br/>";
		num_errors++;
	}
	
	if(begin_date == ""){
		error_message = error_message + "Begin Date Required<br/>";
		num_errors++;	
	}
	if(begin_time == ""){
		error_message = error_message + "Begin Time Required<br/>";
	}
	if(end_date == ""){
		error_message = error_message + "End Date Required<br/>";
		num_errors++;	
	}
	if(end_time == ""){
		error_message = error_message + "End Time Required<br/>";
	}
	if(!num_category_chosen){
		error_message = error_message + "Assign Event to at least one Category<br/>";
		num_errors++;
	}
	
	if(reminder_type == ""){
		error_message = error_message + "Choose a Reminder Type";
		num_errors++;
	}
	if(!num_reminder_chosen){
		error_message = error_message + "Choose at least 1 reminder time<br/>";
		num_errors++;
	}	
	if(num_errors == 0){
		var post = $('#edit_event_form').serialize();
		var post_result = "";
		$.ajax({url:'process.php',data:post,type:"POST",async:false,
			success:function(data){
				post_result = data;
			}
		});
		if(post_result == 1){
			$("#edit_event_form").remove();
			$("#modal_container").html("Event Updated");
			reset_modal_buttons(); //reset buttons
			//get_user_events();			
			get_categories();
			refresh_calendar();
		}else{
			return false;
		}
	}else{
		$("#form_notification").html(error_message);
		$("#form_notification").show();	
	}
}

function validate_delete_category(){
	clear_modal_form_notification();
	var n = $("input:checkbox:checked").length;
	if(n > 0){
		var post = $("#manage_categories").serialize();
		$.post("process.php",post,
			function(data){
				if(data == 1){
					$("#modal_container").html("Selected Calendars have been deleted.");
					reset_modal_buttons();
					get_categories(); //update list
				}else{
					$("#modal_container").html("Selected Calendars could not be deleted. Try again.");
				}
			}
		);	
	}else{
		$("#form_notification").html("Select at least one Category");
		$("#form_notification").show();
	}
	
}

function validate_delete_contact(){
	clear_modal_form_notification();
	var n = $("input:checkbox:checked").length;
	if(n > 0){
		var post = $("#manage_contacts").serialize();
		$.post("process.php",post,
			function(data){
				if(data == 1){
					$("#modal_container").html("Selected Contacts have been deleted.");
					reset_modal_buttons();
					get_contacts(); //update list
				}else{
					$("#modal_container").html("Contacts could not be deleted. Try again.");
				}
			}
		);	
	}else{
		$("#form_notification").html("Select at least one Contact");
		$("#form_notification").show();
	}
	
}

function validate_delete_event(){
	clear_modal_form_notification();
	var n = $("input:checkbox:checked").length;
	if(n > 0){
            var post = $("#manage_events").serialize();
            $.post("process.php",post,
                    function(data){
                            if(data == 1){
                                    $("#modal_container").html("Selected Events have been deleted.");
                                    reset_modal_buttons();
                                    //get_user_events(); // Update list under each Calendar
                                    get_categories();
                                    refresh_calendar();
                            }else{
                                    $("#modal_container").html("Events could not be deleted. Try again.");
                            }
                    }
            );	
	}else{
		$("#form_notification").html("Select at least one Event");
		$("#form_notification").show();
	}
	
}

function reset_modal_buttons(){
	$('#modal_container').dialog({
		buttons:{
			"Close": function(){
				$('#modal_container').dialog("close");
			}
		},
		height: 200
	});
}

function clear_modal_form_notification(){
	//clear errors
	$("#form_notification").html("");
	$("#form_notification").hide();
}

function bulk_add(){
	var email_list = $("#email_list").val();
	var email_list_cleaned = email_list.replace(/;/g,',');
	
	//$.post("/controller/process_bulk_add.php",{email_list:email_list_cleaned},
	//$.post("/controller/process_bulk_add_v2.php",{email_list:email_list_cleaned},
	$.post("/controller/process_bulk_add_v3.php",{email_list:email_list_cleaned},
		function(data){
				if(data.status == "success"){
				$("#modal_container").html("New contacts added.");
				reset_modal_buttons();
				get_contacts();
			}else if(data.status == "fail"){
				$("#modal_container").html(data.message);
			}
		},"json"
	);	
}