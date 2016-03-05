<?php

// this file contains all configurable limits and keywords
class TMConfig {
    public static $LOGFILENAME = 'c:\users\satyen\desktop\travelmarket-php-log';
    public static $hName = "www.travelmarket.com";

    // mysql configuration
    public static $mysqlUser = "root";
    public static $mysqlHost = "localhost";
    public static $mysqlPassword = "root";
    public static $mysqlDatabase = "travelmarket";

    // mail configuration
    public static $mUserName = "tm.verify@yahoo.com";
    public static $mPassword = "3m.verify";
    public static $mPort = "465";
    public static $mSMTPHost = "ssl://plus.smtp.mail.yahoo.com";

}

//echo "value of  logfile args is: ", TMConfig::$LOGFILENAME, "\n";
?>
