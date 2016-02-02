<?php


require_once "mysql.php";


/**
 * Description of AirlineDBAPI: database abstraction API for looking up airliens names and codes
 *
 * @author satyen
 */
class AirlineDBAPI {
    
    // return a array, each row of the array is an associative array with 
    // key = airportcode
    // value = airportname
    public static function loadAirlineCodes () {
    
        // open db connection
         $db = getDBConnection();
         
        // create db query
        $tbl = _DBTableNames::$_airlineCodes;
        $sqlstmt = "SELECT NAME, IATA_CODE, ICAOCODE FROM $tbl";
        $rs = null;
        
        // fetch the data
        if(!$rs = $db->query($sqlstmt)) {
 		Logger::logMsg(__FILE__, __FUNCTION__, "Error executing query - " + $sqlstmt);
 		return NULL;
 	}

        for($airlines =null, $idx=0; ($row = mysqli_fetch_assoc($rs)); $idx++) {
            // print_r($row);
            $airlines[$idx] = $row;
        }
        
        return $airlines; 
    }
}

//echo "Calling  AirlineCodes()\r\n";
//$airlines = AirlineDBAPI::loadAirlineCodes();
//echo "Back from AirlineCodes()\r\n";
//print_r($airlines);