<?php


require_once "config.php";
require_once "logger.php";

static $mysqli = null;

class _DBTableNames  {
    static $_airportCodes = "iata_airport_codes";
    static $_users = "users";
    static $_airlineCodes = "airline_descriptors";
}

function getDBConnection() {
	
	global $mysqli;
	
	Logger::logEntry(__FILE__, __FUNCTION__);
	if($mysqli==null) {
		$mysqli = new mysqli(TMConfig::$mysqlHost,
				TMConfig::$mysqlUser,
				TMConfig::$mysqlPassword,
				TMConfig::$mysqlDatabase);
		if($mysqli->connect_error > 0) {
			die('Connect Error(' .
			    $mysqli->connect_errno . ') ' .
			    $mysqli->connect_error);
		}
	}
	
	Logger::logMsg(__FILE__, __FUNCTION__, "host is - " + TMConfig::$mysqlHost);
	Logger::logExit(__FILE__, __FUNCTION__, true);
	return $mysqli;
}

function closeDBConnection() {}

// getDBConnection();

// echo "Everything works\n";


?>
