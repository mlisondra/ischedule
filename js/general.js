function show_modal(content){
	var content = content;
	$.post('../ajax/',{"action":"get_static_content","content":content},
		function(data){
			$("#ui-dialog-title-dialog-modal").html(data.display_title);
			$("#dialog-modal").html(data.display_content);
		},'json'
	);
	
	$('#dialog-modal').dialog({
		autoOpen: true,
		width: 600,
		height: 400,
		buttons: {
			"Ok": function() { 
				$(this).dialog("close"); 
			}, 
			"Cancel": function() { 
				$(this).dialog("close"); 
			} 
		},
		modal: true
	});
}
