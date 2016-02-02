


function registerUser() {
	
	// check input for errors
	alert("our are now a registered user of travelmarket");
}

function displayRegistrationForm() {
	$.ajax({
		url: "/html/registration.html",
		type: 'post',
			success: function (data, status) { 
				$("#content").html(data); 
				$("#rf-form-ok-button").click(registerUser);
			},
			error: function () { alert("Unable to get data for registration.html from server"); }
		} 
	);
}

$(document).ready(
	function() { 
		$("#regsitration-label").click(displayRegistrationForm);
	}
)

