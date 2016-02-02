<?php

// this file contains all configurable limits and keywords
class TMConfig {
       public static $LOGFILENAME = 'c:\users\satyen\desktop\travelmarket-php-log';
       public static $mysqlUser = "root";
       public static $mysqlHost = "localhost";
       public static $mysqlPassword = "root";
       public static $mysqlDatabase = "travelmarket";
}

echo "value of  logfile args is: ", TMConfig::$LOGFILENAME, "\n";
?>
