
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/master.css">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/registration.css">
        <link rel="stylesheet" type="text/css" href="css/createoffer.css">
        <link rel="stylesheet" type="text/css" href="css/passenger.css">

        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" type="text/css" href="css/jqueryui-hacks.css">
        <link rel="shortcut icon" type="image/png" href="favicon.png">


    </head>
    <?php
        require_once "php/users.php";

        // check if this is a email verification avatar of this page
        if(isset($_GET['email']) && !empty($_GET['email']) AND
           isset($_GET['token']) && !empty($_GET['token'])) {
            // Verify data
            if(User::verifyUser($_GET['email'], $_GET['token'])) {
                // we are good
                echo "Thank you for joining travelmarket<br>your account is now active";
            } else {
                // give user the bad news
                echo "Sorry, there is no user with that name in our system.";
            }
        }
    ?>

    <body>
        <div id="page-container">

            <div id="title">
                <h1>t r a v e l m a r k e t s</h1>
                <div id="sign-in">
                    <a href="#"><button id="sign-in-button">Sign In</button></a>
                </div>
                <div id="regsitration-label">
                    <a href="#">Not a member, click here...</a>
                </div>
            </div>

            <div id="main-nav">
                <ul id="main-nav-list">
                    <li id="bookticket"><a href="#">Book Ticket</a></li>
                    <li id="sellticket"><a href="#">Sell Ticket</a></li>
                    <li id="howitworks"><a href="#">How it Works</a></li>
                    <li id="faq"><a href="#">FAQ</a></li>
                    <li id="aboutus"><a href="#">About Us</a></li>
                </ul>

            </div>
            <div id="main-container">
                <div id="sidebar-left"></div>
                <div id="content"></div>
                <div id="ad-bar"></div>
            </div>

            <div id="footer">
                <div id="copyright"><p>Copyright, travelmarket Inc. 2016</p></div>
            </div>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
            <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
            <script src="js/globalize.min.js"></script> <!-- for currency in the spinner -->
            <script src="js/createoffer.js"></script>
            <script src="js/signon.js"></script>
            <script src="js/registration.js"></script>
            <script src="js/nav.js"></script>
        </div>
    </body>
</html>

