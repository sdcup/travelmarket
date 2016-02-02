<?php

//airlines.php


// we currently download airline code  information from the link 


// this site has sql version of the code, the files has to be changed to put the information 
// in the travelmarket database
// 
// TODO
// this list of airlines and their corresponding codes should be initialized using a webservice 
// where this list is refreshed once everyday to make sure we are aware of all the changes to the list.

require_once "logger.php";
require_once "mysql.php";
require_once "AirlineDBAPI.php";

class AirlineInfo {
	private static $_airlines  = null;
	
        /** 
         * _initDescriptorArray
         * This function loads all airline records in memory array
         * 
         *@param
         *@return array
         *@throws Exception 
         */
	static function _initDescriptorArray() { 
            // load airline descriptors in memory
            try {
                self::$_airlines = AirlineDBAPI::loadAirlineCodes();
                if((self::$_airlines == null) || (count(self::$_airlines)==0)) {
                    throw new Exception(__CLASS_.":".__FILE__." : empty airline table", $code, $previous);
                }
            } catch (Exception $e) {
                echo $e->getMessage();
                die();
            }
	}
	
	/**
         * getMatchingEntries 
         *  this function returns a string array with the list of airlines and codes that have this pattern
         * 
         * @param $searchPattern string
         * @return array containg matching airline entries
         */
	static function getMatchingEntries($searchPattern) {
            if (self::$_airlines==null) { self::_initDescriptorArray(); }
            
            $ret = null; $idx=0;
            foreach(self::$_airlines as $airline) {
                if(!stristr($airline['NAME'], $searchPattern) && 
                   !stristr($airline['IATA_CODE'], $searchPattern) &&
                   !stristr($airline['ICAOCODE'], $searchPattern)) {
                    continue;
                }
                $ret[$idx++] = $airline;
            }
            return $ret;
	}
	
	/**
         * getAirlineCodes
         * this function returns a string with IATA code for the airline
         * 
         * @param $name name of airline
         * @ret $code string containing IATA code for this airline
         */
	static function getAirlineCode($name) {
            if(self::$_airlines==null) { self::_initDescriptorArray(); }
            
            foreach(self::$_airlines as $airline) {
                if(strcasecmp($name, $airline['NAME'])==0) {
                    return $airline['IATA_CODE'];
                }
            }
            return null;
	}
	
        /**
         * getAirlineName
         * this function returns a string  with the name of the airline that matches the code
         * 
         * @param $code IATA code of airline
         * @ret $name string containing name for this airline
         */
	// 
	static function getAirlineName($code) {
            if (self::$_airlines==null)  { self::_initDescriptorArray(); }
            
            foreach(self::$_airlines as $airline) {
                if(strcasecmp($code, $airline['IATA_CODE'])==0) {
                    return $airline['NAME'];
                }
            }
            return null;
	}

}

//test code
echo "Code = AC, Name = ".AirlineInfo::getAirlineName("AC")."\r\n";
echo "Name  = United Airlines, Code = ".AirlineInfo::getAirlineCode("United Airlines")."\r\n";


?>
