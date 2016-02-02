    `<?php
require_once "mysql.php";


class TransactionDBAPI {
    static function CreateTransaction($buyerid, $sellerid, $itin, $amount, $offerid, $closingDate) {
        
        $db = getDBConnection();
        
        // check to make sure this is not a duplicate offer
        $qstr = "SELECT count(*) FROM BUYOFFERS WHERE REQUESTERID='$buyerid' AND";
        $qstr .=  " SOURCEAIRPORT='$origin' AND DESTINATIONAIRPORT ='$destination' AND DEPARTUREDATE = '$depDate'";
        if(mysqli_fetch_assoc($db->query($qstr))['count(*)'] > 0) {
            throw new Exception("Trying to create duplicate entry");
        }

        $qstr = "INSERT INTO BUYOFFERS ";
            $qstr .= "(REQUESTERID, OFFERPRICE, EXPIRYDATE, SOURCEAIRPORT, DESTINATIONAIRPORT, ROUNDTRIP, ";
            $qstr .= "MAXNUMSTOPSOUTBOUND, DEPARTUREDATE, DEPARTUREDATEFLEXIBILITY, ";
            $qstr .= "DEPARTURETIMEBEGIN, DEPARTURETIMEEND, ";
            $qstr .= "MAXNUMSTOPSRETURN, RETRUNDATE, RETURNDATEFLEXIBILITY, RETURNTIMEBEGIN, RETURNTIMEEND, STATUS";
            $qstr .= ") VALUES (";
            $qstr .= "'$buyerid', '$amount', '$expiryDate', '$origin', '$destination',";
            $qstr .= "'$rountrip', '$maxHopsOutBound',";
            $qstr .= "'$depDate', '$depFlexibility', '$depEarliestHour', '$depLatestHour',";
            $qstr .= "'$maxHopsInBound', '$retDate', '$retFlexibility', '$retEarliestHour',";
            $qstr .= "'$retLatestHour', 'OPEN')";

        $rs = $db->query($qstr);
        if($rs !== TRUE) {
            print_r($rs);
            echo "Error: " . $db->error;
        } 
        // get transaction and return it to the caller
        $qstr = "SELECT ID FROM BUYOFFERS WHERE REQUESTERID = '$buyerid' ORDER BY REQUESTDATE DESC LIMIT 1";
        $rs = $db->query($qstr);
        if(!$rs) {
            echo "Error: " . $db->error;
            throw new Exception("problem getting ifd from the offer table");
        } 
        $ret = mysqli_fetch_assoc($rs);
        return $ret['ID'];
    }
    
    static function getOffer($oid) {
        $db = getDBConnection();
        $qstr = "SELECT * FROM BUYOFFERS WHERE ID = '$oid'";
        $res  = $db->query($qstr);
        if($db == false) {
            throw new Exception("offer not in the system");
        }
        $o = mysqli_fetch_assoc($res);
        return $o;
    }
    
    static function RemoveOffer($offerId) {
        $db = getDBConnection();
        $ret = $db->query("UPDATE BUYOFFFERS SET STATUS='CLOSED' WHERE ID=\'$offerId\'");
        if($ret == false) {
            throw new Exception("Unable to close offer");
        }
        return true;
    }
    
    static function LockOffer($offerId) {
        $db = getDBConnection();
        $ret = $db->query("UPDATE BUYOFFFERS SET STATUS='LOCKED' WHERE ID=\'$offerId\'");
        if($ret == false) {
            throw new Exception("Unable to lock offer");
        }
        return true;
    }
    
    static function UnlockOffer($offerId) {
        $db = getDBConnection();
        $ret = $db->query("UPDATE BUYOFFFERS SET STATUS='OPEN' WHERE ID=\'$offerId\'");
        if($ret == false) {
            throw new Exception("Unable to unlock offer");
        }
        return true;
    }
    
    static function CloseOffer($offerId) {
        return self::RemoveOffer($offerId);
    }
    
    static function OpenOffer($offerId) {
        return self::UnlockOffer($offerId);
    }
    
    static function GetOpenOfferList() {
        $db = getDBConnection();
        $qstr = "SELECT * FROM BUYOFFERS WHERE STATUS='OPEN'";
        $res = $db->query($qstr);
        if($res == false) {
            throw new Exception("Unable to get list of offers");
        }
        
        // loop over rows and create an object array
        $offerList = array();
        while(($row = mysqli_fetch_assoc($res))) {
            // convert row into an object and add to the array
            $offer = new Offer($row['ID'], $row['REQUESTERID'], $row['OFFERPRICE'], $row['EXPIRYDATE'], 
            $row['SOURCEAIRPORT'], $row['DESTINATIONAIRPORT'],
            $row['ROUNDTRIP'], $row['MAXNUMSTOPSOUTBOUND'],
            $row['DEPARTUREDATE'], $row['RETURNDATEFLEXIBILITY'], 
            $row['DEPARTURETIMEBEGIN'], $row['DEPARTURETIMEEND'], 
            $row['RETRUNDATE'], $row['RETURNDATEFLEXIBILITY'], 
            $row['RETURNTIMEBEGIN'], $row['RETURNTIMEEND']);
            array_push($offerList, $offer);
        }

        return $offerList;
    }
}
    
?>

