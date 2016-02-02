<?php

require_once "OfferDBAPI.php";

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Offer {
    private $_oid; // database handle for this offer
    private $_amount;
    private $_buyer;
    private $_createdOn; // time/date of creation
    private $_expiryDate;
    private $_originAirport; // airport code for origin
    private $_destinationAirport;  // airport code for destination
    private $_roundTrip; // True if roudtrip, False if Oneway ticket
    private $_departureDate;
    private $_departureDateFlexibility; // +/- date flexibility for departure
    private $_depEarliestHour;
    private $_depLatestHour;
    private $_returnDate;
    private $_returnDateFlexibility; // +/- date flexibility for return
    private $_returnEarliestHour;
    private $_returnLatestHour;
    
    static function getOpenOfferList() {
    }
    
    static function getOpenOffersByUser($uid) {
    }
    
    static function getAllOffersByUser($uid, $fromDate, $toDate) {
    }
    
    static function getOffer($oid) {
        // load offer from database
        $ret = OfferDBAPI::getOffer($oid);
        if($ret==null) {
            throw new Exception("offer not found");
        }
        
        //$ret is a associative array, use it o populate the offer object
        $o = new self($oid, $ret['REQUESTERID'], $ret['OFFERPRICE'], $ret['EXPIRYDATE'], 
            $ret['SOURCEAIRPORT'], $ret['DESTINATIONAIRPORT'],
            $ret['ROUNDTRIP'], $ret['MAXNUMSTOPSOUTBOUND'],
            $ret['DEPARTUREDATE'], $ret['RETURNDATEFLEXIBILITY'], 
            $ret['DEPARTURETIMEBEGIN'], $ret['DEPARTURETIMEEND'], 
            $ret['RETRUNDATE'], $ret['RETURNDATEFLEXIBILITY'], 
            $ret['RETURNTIMEBEGIN'], $ret['RETURNTIMEEND']);
        return $o;
        
    }

    public function __construct($oid, $buyerid, $amount, $expiryDate, $origin, $destination,
            $roundTrip, $maxHops,
            $depDate, $depFlexibility, $depEarliestHour, $depLatestHour, 
            $retDate, $retFlexibility, $retEarliestHour, $retLatestHour) {
        
        $this->_oid = $oid;
        $this->_buyer = $buyerid;
        $this->_amount = $amount;
        $this->_createdOn = time();
        // reset to make sure it is not more that xx days from creation date
        $this->_expiryDate = $expiryDate; // earlier of thirty days or departure date
        
        $this->_originAirport = $origin;
        $this->_destinationAirport = $destination;
        
        $this->_roundTrip = $roundTrip;
        
        $this->_departureDate = $depDate;
        $this->_departureDateFlexibility = $depFlexibility;
        $this->_depEarliestHour = $depEarliestHour;
        $this->_depLatestHour = $depLatestHour;
        
        $this->_returnDate = $retDate;
        $this->_returnDateFlexibility = $retFlexibility;
        $this->_returnEarliestHour = $retEarliestHour;
        $this->_returnLatestHour = $retLatestHour;
    }
    
    public function __destruct() {
        //echo "User - destructor called\r\n";
    }
    
    static function CreateNewOffer($buyerid, $amount, $expiryDate, $origin, $destination,
            $rountrip, $maxHops,
            $depDate, $depFlexibility, $depEarliestHour, $depLatestHour, 
            $retDate, $retFlexibility, $retEarliestHour, $retLatestHour) {
        
        // argument validation is done on client side, so no need to check again
        if (!($oid = OfferDBAPI::CreateOffer($buyerid, $amount, $expiryDate, $origin, $destination,
            $rountrip, $maxHops,
            $depDate, $depFlexibility, $depEarliestHour, $depLatestHour, 
            $retDate, $retFlexibility, $retEarliestHour, $retLatestHour, FALSE))) {
                throw new Exception("Unabel to create offer");
        }
        
        $ret = new self($oid, $buyerid, $amount, $expiryDate, $origin, $destination,
            $rountrip, $maxHops,
            $depDate, $depFlexibility, $depEarliestHour, $depLatestHour, 
            $retDate, $retFlexibility, $retEarliestHour, $retLatestHour);
        
        // offer is now active and open for selection by seller
        return $ret;
    }
    
    public function getId() {
        return $this->_oid;
    }
    
    public function lock() {
        // lock bit is not stored with object as it may be changed by another session
        return OfferDBAPI::LockOffer($this->$_oid);
    }
    
    public  function unlock() {
        return OfferDBAPI::UnlockOffer($this->$_oid);
    }
    
    public function close($uid) {
        if($uid != $this->_buyer) {
            throw new Exception("User not authorized to close this offer");
        }
        OfferDBAPI::closeOffer($this->_oid);
    }
    
    public function reopen($uid) {
        if($uid != $this->_buyer) {
            throw new Exception("User not authorized to open this offer");
        }
        OfferDBAPI::closeOffer($this->_oid);
    }
}
/* TEST CODE 

try {
    $o = Offer::CreateNewOffer(1, 250, '2015-11-30', 'sfo', 'yvr', 1, 2, '2015-10-30', 2, '9', '12', 
            '2015-7-30', 2, 9, 12);
    $o = Offer::CreateNewOffer(1, 250, '2015-12-30', 'sjc', 'oak', 1, 2, '2015-10-30', 2, '9', '12', 
            '2015-7-30', 2, 9, 12);
    $o = Offer::CreateNewOffer(1, 250, '2015-9-30', 'yvr', 'oak', 1, 2, '2015-10-30', 2, '9', '12', 
            '2015-7-30', 2, 9, 12);
} catch (Exception $e) {
    echo "Caught exception - ".$e->getMessage();
    die();
}

$oList = OfferDBAPI::GetOpenOfferList();
$count = count($oList);
echo "Found $count open offers\r\n";

//print_r($o);
//$oid = $o->getId();
//$o = Offer::getOffer($oid);
//print_r($o);
 * 
 */

?>