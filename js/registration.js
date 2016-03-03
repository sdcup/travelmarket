// TODO
// 1. enter user in the database
// 2. check for duplicate username
// 3. formatting of forms and error boxes
// 4. send email to user for verification
// 5. Capture user email, verification

var User = function () {

    function isEmail(email) {
        //var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(email);

        //return regex.test(email);
    }

    function registerUser() {
        var ud = {}, ret, errCount = 0, errStr = "";
        var successStr = "User created, an email has been sent to you mail " +
                "box, <br>please follow the link in email for verification";

        $("#reg-form-error-message").html(errStr).css({"visibility": "hidden"});

        // collect all the user input
        ud.emailId = $("#rf-emailid").val();
        ud.fName = $("#rf-firstname").val();
        ud.lName = $("#rf-lastname").val();
        ud.pwd = $("#rf-password").val();
        ud.rpwd = $("#rf-password-again").val();

        // error checking on input
        if(ud.fName.length==0) {
            $("#rf-firstname").css("border", "solid red thin");
            errCount++;
        } else {
            $("#rf-firstname").css("border", "solid black thin");
        }

        if(ud.lName.length==0) {
            $("#rf-lastname").css("border", "solid red thin");
            errCount++;
        } else {
            $("#rf-lastname").css("border", "solid black thin");
        }

        if(errCount > 0) {
            errStr = "Please enter your full name.<br>";
        }

        if(ud.emailId.length==0 || !isEmail(ud.emailId)) {
            $("#rf-emailid").css("border", "solid red thin");
            errStr += "\nEnter a valid email id<br>";
            errCount++;
        } else {
            $("#rf-emailid").css("border", "solid black thin");
        }

        if(ud.pwd.length < 6 || ud.pwd!=ud.rpwd) {
            errStr += "\nPasswords must be atleast 6 characters in length and match<br>"
            $("#rf-password").css("border", "solid red thin");
            $("#rf-password-again").css("border", "solid red thin");
            errCount++;
        } else {
            $("#rf-password").css("border", "solid black thin");
            $("#rf-password-again").css("border", "solid black thin");
        }

        if(errCount > 0) {
            // print eror message
            errStr = "There were errors in your form, please correct them and try again<br><br>" + errStr;
            $("#reg-form-error-message").html(errStr).css({"visibility": "visible"});
            return;
        }

        // check input for errors
        /*alert("you  are now a registered user of travelmarket" + "\n" +
              "First - " + ud.fName + "\n" +
              "Last - " + ud.lName  + "\n" +
              "Password - " + ud.pwd  + "\n" +
              "Password2 - " + ud.rpwd);
        */

        // register user
        $.ajax({
            url: "/php/process-user-requests.php",
            type: 'post',
            data: { op: 'registeruser',
                    requestDetails : JSON.stringify(ud),
                  },

            success: function (data) {
                ret = JSON.parse(data);
                if(ret.status=="success") {
                    $("#content").html(ret.msg);
                } else {
                    alert("Error in registering user - " + ret.msg);
                }
            },

            error: function () {
                alert("Unable to register user");
            }
        });
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
    return ({ displayRF : displayRegistrationForm });
}();

$(document).ready(
	function() { 
		$("#regsitration-label").click(User.displayRF);
	}
)

