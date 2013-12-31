$(document).ready(function() {
    get_contacts(); // found in /js/schedule.js
    get_categories(); // found in /js/schedule.js
		  
    //initialize the calendar

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
                
			$('#mycalendar').fullCalendar({
			  eventClick: function(calEvent, jsEvent, view) { //clicking on event within calendar brings up Edit Event dialog box
                                show_modal('edit_event',calEvent.id);
				},
                            dayClick: function(date, allDay, jsEvent, view){ //clicking within a calendar day brings up the Add Event
                               show_modal('add_event');
                               
                               var begin_date = $.fullCalendar.formatDate(date, 'MM/dd/yyyy'); // Format date
                               
                               $.post('../schedule/process.php',{
                                   "action":"get_modal_content",
                                   "modal_type":"add_event",
                                   "begin_date":begin_date,
                                   "end_date":begin_date},
                                       function(data){
                                               var cdt = new Date();
                                               var current_month = cdt.getMonth() + 1;
                                               var current_day = cdt.getDate();
                                               var current_year = cdt.getFullYear();
                                               var today_date = current_month + "/" + current_day + "/" + current_year;
                                               $("#begin_date").val(begin_date);
                                               $("#end_date").val(begin_date);

                                                $('#end_time').timepicker({
                                                    showLeadingZero : false,
                                                    showPeriod : true,
                                                    amPmText: ['AM', 'PM']
                                                });

                                               $("#modal_container").dialog('open');
                               });
                                }, // End dayClick
				header: {
					left: 'month,basicWeek,basicDay',
					center: 'title',
					right:'today prev,next'
				},
                                events : "/schedule/get_events",
				eventRender: function(event,element){
                                        $(element).css({
                                            "background-color":event.color,
                                            "border-color":event.color,
                                            "border-style":"solid",
                                            "color":"#000",
                                            "font-weight":"#bold"
                                        });
				}
			});
			
                      
			// End calendar init
                        
			 $('.calendar_manager').live('keyup.autocomplete', 
                            function(){ 
                                $(this).autocomplete({ 
                                    source: "../controller/user_list.php",
                                    select: function( event, ui ) {
                                                    $(this).val(ui.item.value);
                                                    var hidden_input = '<input type="hidden" name="manager_ids[]" value="'+ ui.item.id + '">';
                                                    $("#managers").append(hidden_input);
                                                    return false;
                                            }				
                                    }
                            ); 			
			});
		
});
		 
		 
		 