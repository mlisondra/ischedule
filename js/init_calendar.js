$(document).ready(function() {
    get_contacts(); // found in /js/schedule.js
    get_categories(); // found in /js/schedule.js
		  
    //initialize the calendar

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
                
			$('#mycalendar').fullCalendar({
			  eventClick: function(calEvent, jsEvent, view) { // Binding for each event in calendar; Edit Event mode
                                //console.log(new Date(calEvent.begin_date_time));
                                $(this).children().attr("rel",calEvent.id);
                                $(this).children().attr("id","add_event");
                                post_data = "modal_type=edit_event&obj_id=" + calEvent.id;
                                $.post('../schedule/get_modal_form',post_data,
                                    function(data){
                                        
                                    $('#modal_container').dialog({
                                            autoOpen: true,
                                            resizable: false,
                                            width: 500,
                                            height: 400,
                                            draggable: false,
                                            modal: true,
                                            title: 'Edit Event',
                                            //open: function(event, ui) { $('#modal_container').html(""); },
                                            dialogClass: 'no-close' // Ensures that the Close button is disabled for all modal instances
                                    });
                                
                                        $("#modal_container").html(data);
                                        
                                        $( "#begin_date, #end_date" ).datepicker({
                                            //minDate : '02/07/2014'
                                        });
                                        
                                        $('#begin_time').timepicker({
						showLeadingZero : false,
						showPeriod : true,
						amPmText: ['AM', 'PM'],
						onSelect : function(time,inst){
                                                    $('#end_time').val(time);
                                                }
                                        });
                                                
                                        $("#modal_container").dialog('open');
                                    });                                
				},
                            dayClick: function(date, allDay, jsEvent, view){ //clicking within a calendar day brings up the Add Event
                        
                              	$('#modal_container').dialog({
                                        autoOpen: true,
                                        resizable: false,
                                        width: 500,
                                        height: 400,
                                        draggable: false,
                                        modal: true,
                                        title: 'Add Event',
                                        open: function(event, ui) { $('#modal_container').html(""); },
                                        dialogClass: 'no-close' // Ensures that the Close button is disabled for all modal instances
                                });
                               
                                cdt = new Date();
                                var current_month = date.getMonth() + 1;
				if(current_month < 10){
					current_month = "0" + current_month;
				}
				current_day = date.getDate();
				current_year = date.getFullYear();
				today_date = current_month + "/" + current_day + "/" + current_year;
                                begin_date = $.fullCalendar.formatDate(date, 'MM/dd/yyyy'); // Format date
                                //console.log(begin_date);
                               $.post('../schedule/get_modal_form',{
                                   "modal_type":"add_event",
                                   "begin_date":begin_date,
                                   "end_date":begin_date, 
                                   "obj_id":0},
                                       function(data){
                                               var cdt = new Date();
                                               var current_month = cdt.getMonth() + 1;
                                               var current_day = cdt.getDate();
                                               var current_year = cdt.getFullYear();
                                               var today_date = current_month + "/" + current_day + "/" + current_year;

                                               $("#modal_container").html(data);
                                                
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
                                            "font-weight":"bold"
                                        });
				}
			});
			
                      
			// End calendar init
                        
			
		
});
		 
		 
		 