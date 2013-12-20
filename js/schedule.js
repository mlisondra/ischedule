function goto_specific_month(month){
	var current_calendar_date = $('#mycalendar').fullCalendar('getDate');
	var current_calendar_year = current_calendar_date.getFullYear();
	$('#mycalendar').fullCalendar( 'gotoDate', current_calendar_year, month);
 }
		 
function get_contacts(){
	var html = '';
	$.post('../schedule/get_contacts',
		function(data){
			$.each(data.contacts, function(i, item) {
				html = '<div class="color_list"></div>';
				html += '<li>' + item.first_name + ' ' +  item.last_name + '</li>';
				$('ul#contacts_list').append(html);
			});
		},"json");
}

function show_modal(modal_type,obj_id){
	var obj_id = typeof(obj_id) != 'undefined' ? obj_id : 0;
	
	var modal_type = modal_type;
	var modal_content = "";
	var modal_title = "";
	var modal_height = 400;
	var modal_width = 500;
	
	switch(modal_type){
		case "add_contact":
			modal_title = "Add Contact";			
			break;
		case "edit_contact":
			modal_title = "Edit Contact";
			break;
		case "manage_contacts":
			modal_title = "Manage Contacts";
			break;
		case "add_category":
			modal_title = "Add Calendar";
			modal_height = 350;
			modal_width = 350;			
			break;
		case "edit_category": //Really editing a Calendar
			modal_title = "Edit Calendar";
			modal_height = 470;
			modal_width = 350;
			break;
		case "manage_categories":
			modal_title = "Manage Calendars";
			break;		
		case "faqs":
			modal_title = "FAQs";
			break;
		case "terms":
			modal_title = "Terms & Conditions";
			break;
		case "about":
			modal_title = "About";
			break;
		case "delete_selected_contacts":
			modal_title = "Delete selected contacts";
			obj_id = $('#contact_list_form').serialize();
			break;
		case "manage_contacts":
			modal_title = "Manage Contacts";
			break;
		case "add_event":
			modal_height = 450;
			modal_title = "Add Event";
			break;
		case "edit_event":
			modal_height = 450;
			modal_title = "Edit Event";
			break;
		case "manage_events":
			modal_title = "Manage Events";
			break;
		case "my_account":
			modal_title = "Your Account Details";
			break;
		case "bulk_add":
			modal_title = "Add Multiple Contacts";
			break;
	}
	
	//post_data = "action=get_modal_content&modal_type=" + modal_type + "&obj_id=" + obj_id;
	post_data = "modal_type=" + modal_type + "&obj_id=" + obj_id;
	var modal_content = $.post('../schedule/get_modal_form',post_data,
		function(data){
			$("#modal_container").html(data);
			if(modal_type == "add_event" || modal_type == "edit_event"){
				var cdt = new Date();
				var current_month = cdt.getMonth() + 1;
				if(current_month < 10){
					current_month = "0" + current_month;
				}
				var current_day = cdt.getDate();
				var current_year = cdt.getFullYear();
				var today_date = current_month + "/" + current_day + "/" + current_year;
				if(modal_type == "add_event"){
					$("#begin_date").val(today_date); //apply today date to begin date picker only for Add Event
				}
				var dates = $( "#begin_date, #end_date" ).datepicker({	
							minDate : today_date,
							numberOfMonths:1,
							onSelect: function( selectedDate ) {
								var option = this.id == "begin_date" ? "minDate" : "maxDate",
									instance = $( this ).data( "datepicker" ),
									date = $.datepicker.parseDate(
										instance.settings.dateFormat ||
										$.datepicker._defaults.dateFormat,
										selectedDate, instance.settings );
								dates.not( this ).datepicker( "option", option, date );
								$("#end_date").val(selectedDate);
							}						
						});
				
				$('#begin_time').timepicker({
						showLeadingZero : false,
						showPeriod : true,
						amPmText: ['AM', 'PM'],
						onSelect : function(time,inst){
								$('#end_time').val(time);
							}
				});
				
				$('#end_time').timepicker({
					showLeadingZero : false,
					showPeriod : true,
					amPmText: ['AM', 'PM']
				});								
			}
		}
	);	

	$('#modal_container').dialog({
		autoOpen: true,
		resizable: false,
		width: modal_width,
		height: modal_height,
		draggable: false,
               /* buttons: {
			"Save": function() {
				switch(modal_type){
					case "add_contact":
						validate_add_contact();
						get_contacts();
						break;
					case "edit_contact":
						validate_edit_contact();
						get_contacts();
						break;
					case "manage_contacts":
						validate_delete_contact();
						get_contacts();
						break;
					//case "add_category":
						//validate_add_category();
						//get_categories(); // this should happend in validate.js
						//get_contacts(); // this should happend in validate.js
						//break;
					//case "edit_category":
						//validate_edit_category();
						//get_categories(); // this should happend in validate.js
						//get_contacts(); // this should happend in validate.js
						//break;
					case "delete_selected_contacts":
						delete_selected_contacts();
						break;
					case "my_account":
						validate_my_account();
						break;
					case "add_event":
						validate_add_event();
						get_user_events();
						break;
					case "edit_event":
						validate_edit_event();
						break;
					case "manage_categories":
						validate_delete_category();
						get_contacts();
						refresh_calendar();
						break;
					case "manage_contacts":
						validate_delete_contact();
						break;
					case "manage_events":
						validate_delete_event();
						break;
					case "bulk_add":
						bulk_add(); //from validate.js
						break;
				}
				
			}, 
			"Cancel": function() {
				$(this).dialog("destroy");
			}		
		},*/
		modal: true,
		title: modal_title,
		open: function(event, ui) { $('#modal_container').html(""); },
                dialogClass: 'no-close' // Ensures that the Close button is disabled for all modal instances
	});
	
}

// Retrieves categories
function get_categories(){
	$.post('../calendars',
		function(data){
			$('ul#category_list li').remove(); // Remove existing the list
			$.each(data.calendars, function(i, item) {
				html = '<li><a href="">+</a><a href="###" rel="' + item.id + '">' + item.name + '</a></li>';
				$('ul#category_list').append(html);
			});
		},"json");
}

function get_user_events(){
	$.post('../schedule/get_user_events',{"action":"get_user_events"},
		function(data){
				$('#events_list').html(data);
		}
	);
}

function add_event(date_obj){
	show_modal('add_event','');
}

function show_static_modal(modal_type){
	var modal_type = modal_type;
	var modal_content = "";
	var modal_title = "";
	switch(modal_type){
		case "faqs":
			modal_title = "FAQs";
			break;
		case "terms":
			modal_title = "Terms & Conditions";
			break;
		case "about":
			modal_title = "About";
			break;
	}

	$.post('../schedule/process.php',{"action":"get_static_modal_content","modal_type":modal_type},
		function(data){
			$("#static_modal_container").html(data);
		}
	);	
	
	$('#static_modal_container').dialog({
		autoOpen: true,
		width: 600,
		height: 400,
		modal: true,
		title: modal_title
	});
}

function refresh_calendar(){
	$('#mycalendar').fullCalendar('refetchEvents');
}

$("#logout").click(function(){
	if(confirm("Are you sure you want to logout?")){
		window.location.href="../logout/";
	}else{
		return false;
	}
});

function expand_events(elem){
	if(document.getElementById(elem).style.display == "none"){
		$("#"+elem).show();
	}else{
		$("#"+elem).hide();
	}	
}

function remove_manager(manager,calendar){
	var manager_id = manager;
	var calendar_id = calendar;
	var post = "action=remove_manager&manager_id=" + manager_id + "&calendar_id=" + calendar_id;
	$.post("../schedule/process.php",post,
		function(data){
			$.post("../schedule/process.php",{action:"get_calendar_managers","calendar_id":calendar_id},
				function(data){
					$("#calendar_managers").html(data);
				}
			);
		}
	);
}

$("#reminder_notification_all").live("click",(function(){
	var checked_status = this.checked;
	$("#add_event_form input:checkbox").each(function()
	{
		this.checked = checked_status;
	});
}));

/**
 * Bind delete category
 */
$('.delete_category').live('click',function(){
	obj_id = $(this).attr("id");
	$('.alert_modal p').html('Are you sure you want to delete the Calendar? Doing so will delete all associated events.');
	$('.alert_modal #obj_id').val(obj_id);
	open_alert_modal();
});

function open_alert_modal(){
	$('.alert_modal').dialog('open');	
}

function close_alert_modal(){
	$('.alert_modal').dialog('close');	
}

function update_category_list_modal(){
	post_data = "action=get_modal_content&modal_type=manage_categories";
	$.post('../schedule/process.php',post_data,
		function(data){
			$('#modal_container').html(data);
		}
	);	
}

// on load
$(document).ready(function(){
	/* Removed as it appears not to be production code
	$("#add_contact_button").click(function(){
		$.post("../schedule/process.php",{action:"verify_account"},function(data){
			alert(data);
		});
	});
	*/
	
	// Binding for calendar name/links; for editing
	$("#category_list li a").live("click",function(){
		var calendar_id = $(this).attr("rel");
		console.log(calendar_id);
		show_modal('edit_category',calendar_id);
		
	});
	
	// Binding for the months header
	$("#calendar_months ul li a").click(function(){
		var month_num = $(this).attr("rel");
		goto_specific_month(month_num);
	});
	
	// Binding for the action buttons within Schedule view
	$(".button, .deselect_all").click(function(){
		show_modal($(this).attr("id"));
	});
	
    //Setup Alert Modal
     $('.alert_modal').dialog({
		modal: true,
		closeOnEscape: false,
		hide : 'fade',
		autoOpen: false,
		resizable: false,
		draggable: false,
		minWidth: 300,
		title : "Please confirm"
	}); 
	
	$('.alert_modal .btn').click(function(){
		if($(this).attr('id') == 'yes'){
			$.post('../schedule/process.php',{"action":"delete_associated_events","category_id":obj_id},
				function(data){
					if(data.status == 'success'){
						$('.alert_modal	').dialog('The Calendar has been removed');	
						open_alert_modal();
						setTimeout(close_alert_modal,1500);
						refresh_calendar();
						get_categories();
						update_category_list_modal();
					}
				},'json'
			);
		}else{
			close_alert_modal();
		}
	});
	
	$("#contacts_list li").live("click",function(){
		alert("got it");
	});	
	
	// Delete button
	$(".delete_button").live("click",function(){
		$(this).next().show();
	});
	
	// Delete button confirm; send ajax post with object id
	$(".delete_button_confirm").live("click",function(){
		var obj_type = $(this).attr("rel");
		var form_id = $(this).closest('form').attr("id");
		var obj_id = $('#' + form_id + ' #id').val();
		// ajax post to delete given object
		var post_data = {
			"obj_type":obj_type,
			"obj_id":obj_id
			};
		
		$.post('../schedule/delete_obj',post_data,
                    function(data){
                        if(data.status == 1){
                            get_categories();
                            $('#modal_container').dialog('close');
                        }
                    },"json"
                );
		
	});
	
	
	// Delete button cancel
	$(".delete_button_cancel").live("click",function(){
		$(this).parent().hide();
	});
        
        // Save button for Add Calendar
        $(".save_button").live("click",function(){
                validate_add_category();
            }
        );
        
        // Save button cancel for Add Calendar
 	$(".save_button_cancel").live("click",function(){
            $('#modal_container').dialog('close');
	});       
});	




