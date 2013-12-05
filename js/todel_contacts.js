function toggle_display(name){
	div_display = document.getElementById(name).style.display;
	if(div_display == "none"){
		document.getElementById(name).style.display = "block";
	}else{
		document.getElementById(name).style.display = "none";
	}
}

function get_contacts(){
	new Ajax.Updater('list_of_contacts','../ajax/');
}