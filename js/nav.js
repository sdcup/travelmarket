

$(document).ready(function() {
		$("#bookticket").click(TMCOModule.showForm);
		$("#sellticket").click(handleNavButton);
		$("#howitworks").click(handleNavButton);
		$("#faq").click(handleNavButton);		
		$("#aboutus").click(handleNavButton);
	}
) 

function handleNavButton() {	
	alert("handleNavButton called for - " + this.id);
}
	
