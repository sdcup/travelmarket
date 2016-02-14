<?php

require_once "OfferDBAPI.php";



class Passenger {
    private $_fname;
    private $_mname;
    private $_lname;
    private $_dob_year;
    private $_dob_month;
    private $_dob_day;
    private $_gender;

    public function __construct($fname, $mname, $lname, $y, $m, $d, $gender) {
        $_fname = $fname;
        $_mname = $mname;
        $_lname = $lname;
        $_dob_year = $y;
        $_dob_month = $m;
        $_dob_day = $d;
        $_gender = $gender;
    }
}

class Offer {
    private $_oid; // database handle for this offer
    private $_amount;
    private $_buyer;
    private $_createdOn; // time/date of creation
    private $_expiryDate;
    private $_numPassengers;
    private $_cabinClass;
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
    private $_passengers;

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
            $retDate, $retFlexibility, $retEarliestHour, $retLatestHour,
            $numPassengers, $cabinClass, $passengers) {

        $this->_oid = $oid;
        $this->_buyer = $buyerid;
        $this->_amount = $amount;
        $this->_createdOn = time();
        // reset to make sure it is not more that xx days from creation date
        $this->_expiryDate = $expiryDate; // earlier of thirty days or departure date

        $this->_numPassengers = $numPassengers;
        $this->_cabinClass = $cabinClass;
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
        $this->_passengers = passengers; //will be filled in as we add passengers to this offer
    }

    public function __destruct() {
        //echo "User - destructor called\r\n";
    }

    static function CreateNewOffer($offer, $pList) {

        print("something\n");
        print("something more\n");

        // argument validation is done on client side, so no need to check again
        if (!($oid = OfferDBAPI::CreateOffer(
            $offer['offerPrice'],
            '2016-01-01', /* TODO grab expiry date from the offer*/
            $offer['origin'],
            $offer['destination'],
            $offer['rounTrip'],
            $offer['maxStops'],
            $offer['leavingDate'],
            0, /* TODO $depFlexibility, for the time being ignore this */
            $offer['toLeavingEarliest'], /* TODO need to convert to an hour */
            $offer['toLeavingLatest'],
            $offer['returnDate'],
            0, /* TODO $retFlexibility, for the time being ignore this */
            $retEarliestHour,
            $retLatestHour,
            FALSE,
            $offer['numPassengers'],
            $offer['classV'],
            $passengers))) {
                throw new Exception("Unabel to create offer");
        }

        $ret = new self($offer, $pList);

        // offer is now active and open for selection by seller
        return $ret;
    }

    //dont think this function is needed, this will be done in createOffer
    static function AddPassenger($oid, $name, $dob, $gender) {
        $d = $dob->year."-".$dob->month."-".$dob->day;
        OfferDBAPI::AddPassenger(oid, $name->fname, $name->mname, $name->lname, $d, $gender);
    }

    public function getId() {
        return $this->_oid;
    }

    public function getPassengerList() {
        return $this->_passengers;
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
