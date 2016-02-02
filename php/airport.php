<?php

//airports.php


// we currently download airport code  information from the link 
// http://www.codediesel.com/data/international-airport-codes-download/
// this site has sql version of the code, the files has to be changed to put the information 
// in the travelmarket database
// 
// TODO
// this list of airports and their corresponding codes should be initialized using a webservice 
// where this list is refreshed once everyday to make sure we are aware of all the changes to the list.

require_once "logger.php";
require_once "mysql.php";
require_once "AirportDBAPI.php";

class AirPortInfo {
        private static $airports = null;
	
	static function _loadAirportRecords() { 
            // load airport descriptors in memory
            try {
                self::$airports = AirportDBAPI::loadAirportCodes();
                if(self::$airports==null || count(self::$airports)==0) {
                    throw  new Exception(__CLASS__." : ".__METHOD__." : Empty arport codes");
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
	}
	
	// this function returns a string array with the list of airports and codes that have this pattern
	static function getMatchingEntries($searchPattern) {
            if (!self::$airports) { self::_loadAirportRecords();}
            // search through the airports that have the matching substr in the name 
            $ret = null;
            for(reset(self::$airports); $entry = each(self::$airports); ) {
                //print_r($entry);
                if(strstr($searchPattern, $entry['airport'], false)) {
                    // add this entry top $ret array
                    array_push($ret, $entry);
                }
            }
            $cnt = count($ret);
            Logger::logTxt("matched " . $cnt . " entries");
            return;
        }
	
	// this function returns a string array with list of codes that match the name of the input city
	static function getAirportCode($city) {
            if (!self::$airports) { _loadAirportRecords(); }
            
            $ret = null;
            foreach(self::$airports as $code => $name) {
                if(stristr($name, $city)) { //partial match found
                    $ret[$code] = $name;
                }
            }
            if(count($ret) > 0) {
                echo "found " . count($ret) . " matching records\r\n";
            }
            return $ret;
	}
	
	// this function returns a string  with the name of the airport that matches the code
	static function getAirportName($code) {
            if (!self::$airports) { _loadAirportRecords(); }
            
            foreach(self::$airports as $key => $name) {
                if(strcasecmp($code, $key)==0)  {     
                    return $name;
                }
            }
            return null;
	}
        
        public function dumpData() {
            print_r(self::$airports);
        }

}

/*
 * // test code

$ai = new AirportInfo();
AirPortInfo::_loadAirportRecords();
//$ai->dumpdata();


$ret = $ai->getAirportName("SFO");
print_r($ret."\r\n");

$ret = $ai->getAirportName("yvr");
print_r($ret);

$ret = $ai->getAirportCode("san");
print_r($ret);
*/

?>
