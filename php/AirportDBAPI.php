<?php

require_once "mysql.php";

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AirportDBAPI
 *
 * @author satyen
 */
class AirportDBAPI {
    
    // return a array, each row of the array is an associative array with 
    // key = airportcode
    // value = airportname
    public static function loadAirportCodes () {
    
        // open db connection
         $db = getDBConnection();
         
        // create db query
        $tbl = _DBTableNames::$_airportCodes;
        $sqlstmt = "SELECT * FROM $tbl";
        $rs = null;
        
        // fetch the data
        if(!$rs = $db->query($sqlstmt)) {
 		Logger::logMsg(__FILE__, __FUNCTION__, "Error executing query - " + $sqlstmt);
 		// trigger_error(mysql_error(), E_USER_ERROR);
 		return NULL;
 	}

        $airportName = $code = null;
        for($airportCodes=null; ($row = mysqli_fetch_assoc($rs)); ) {
            // print_r($row);
            foreach ($row as $key => $val) {
                if(strcmp($key, "code")==0) { 
                    $code = $val; 
                } else if(strcmp($key, "airport")==0) { 
                    $airportName = $val; 
                }  
            }
            //$rowEntry = "Code - $code; Name - $airportName";
            //Logger::logTxt($rowEntry);
            $airportCodes[$code] = $airportName;
        }
        
        return $airportCodes; 
    }
}

$test = new AirportDBAPI();
$test->loadAirportCodes();


