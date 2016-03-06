


function gotEmailMsg() {
	$("#forgot-password-div").html("We have sent a link to your email to reset your password.")
}

function handleResponse(data, status) {
    // change salutation on the top right of the page
    // include "Hello user
    // remove register here link
    // data will contain user details
    var ret = JSON.parse(data);
	if(ret.status=='success') {
        alert("User logged in with id = " + data.id);
    } else {
        alert("Fraudulent user");
    }
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

function loginUser() {
    var email = $("#login-emailid").val(),
        pwd = $("#login-password").val();

    if(email.length==0 || pwd.length==0) {
        alert("Please provide valid email and password to login.");
        return;
    }


	$.ajax({
		url: "/php/process-user-requests.php",
		type: 'POST',
		data: { op       : 'login',
                requestDetails : JSON.stringify({ emailid  : email, password : pwd})
			},
		error: function() { alert("Something wrong"); },
		success: function(data,status) {
            var ret = JSON.parse(data);
            if(ret.status=='success') {
                alert("User Name: " + email + ", id: " + ret.uid)
            } else {
                alert(ret.msg);
            }
        }
	})
}

function showLoginForm() {
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

$(document).ready(
	function() { 
		$("#sign-in-button").click(showLoginForm);
	}
)

