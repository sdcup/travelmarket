
function updateSignon() {
	$.ajax({
			url: "/html/login.html",
			type: 'post',
			data: { 'gethtml' : 'loginfile.html'},
			success: function (data, status) { 
					$("#content").html(data); 
					$("#login-form-ok-button").click(loginUser);
					$("#forgot-password").click(displayForgotPassword);
				},
			error: function () { alert("Unable to get data for login.html from server"); }
			} 
	);
}

function gotEmailMsg() {
	$("#forgot-password-div").html("We have sent a link to your email to reset your password.")
}

function handleResponse(data, status) {
	$("#content").html(data);
}

function loginUser() {
	$.ajax({
		url: "/php/signon-processor.php",
		type: 'POST',
		data: { emailid  : $("#login-emailid").val(),
			    password : $("#login-password").val()
			},
		error: function() { alert("Something wrong"); },
		success: handleResponse, 
	})
}

function displayForgotPassword() {
	$("#forgot-password").html("just hold on...");
	$.ajax({
			url: "/html/forgot-password.html",
			type: 'post',

			success: function (data, status) { 
				$("#content").html(data); 
				$("#forgot-password-ok-button").click(gotEmailMsg);
			},
			error: function () { alert("Unable to get data for forgot-password.html from server"); }
			} 
	);
}

$(document).ready(
	function() { 
		$("#sign-in-button").click(updateSignon); 
	}
)

