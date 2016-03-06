<?php

require_once"mysql.php";

class UserDBAPI {
    /**
     * createUser
     * creates a user entry in the database
     * 
     * @param type $userDetails
     * @return type uid
     */	
    public static function createUser($uname, $password, $email, $fname, $lname, $phone, $addr1, 
                        $addr2, $city, $state, $zip, $vToken) {

            Logger::logEntry(__FILE__, __FUNCTION__);
            // This method assumes all the data validation has been done at 
            // higher layers
            // register new user 
            $db = getDBConnection();


            $qstr = "INSERT INTO USERS ";
            $qstr .= "(USERNAME, PASSWD, EMAIL, USERLASTNAME, USERFIRSTNAME, PHONE, ";
            $qstr .= "ADDRESSLINE1, ADDRESSLINE2, CITY, STATE, POSTALCODE, ACTIVATIONCODE";
            $qstr .= ") VALUES (";
            $qstr .= "'$uname', '$password', '$email', '$lname', '$fname', '$phone', '$addr1', '$addr2', '$city', '$state', '$zip', '$vToken'";
            $qstr .= ")";
            
            $rs = $db->query($qstr);
            if($rs !== TRUE) {
                // TODO throw exception
                // echo "Error: " . $rs->error;
            }
    }

    public static function setActiveBit($uid, $value) { 
        $db = getDBConnection();

        $sqlstmt = "UPDATE USERS SET ACTIVE=" + $value + " WHERE ID=" + $uid;
        if(! $rs=$db->query($sqlstmt)) {
                Logger::logMsg(__FILE__, __FUNCTION__, "Error executing query - " + $sqlstmt);
                // trigger_error(mysql_error(), E_USER_ERROR);
                return NULL;
        } 
        return true;
    }

    public static function getUserDetails($uname) {
        $db = getDBConnection();

        $qstr = "SELECT * FROM USERS WHERE USERNAME='$uname'";

        if(!$rs = $db->query($qstr)) {
            return NULL;
        }
        $user = mysqli_fetch_assoc($rs);
        return $user;
    }
    
    public static function userExists($uname) {
        return (self::getUserDetails($uname)) ? 1 : 0;
    }


    /**
     * deleteUser
     * does not actually remove the record from database, simply sets the active bit to 0
     * 
     * @param type $uname
     * @return boolean
     */
    public static function deleteUser($uname) {
        $db = getDBConnection();

        $sqlstmt = "UPDATE USERS SET ACTIVE=" + 0 + " WHERE USERNAME=" + $uname;
        if(! $rs=$db->query($sqlstmt)) {
                Logger::logMsg(__FILE__, __FUNCTION__, "Error executing query - " + $sqlstmt);
                // trigger_error(mysql_error(), E_USER_ERROR);
                return NULL;
        } 
        return true;
    }
    
     /**
     * deleteUser
     * does not actually remove the record from database, simply sets the active bit to 0
     * 
     * @param type $uname
     * @return boolean
     */
    public static function updateUser($uname, $password, $email, $fname, $lname, $addr1, $addr2, $city, $state, $zip) {
        $db = getDBConnection();

        // create sql string based on non-null arguments
        $sqlstmt = "UPDATE USERS ";
        if($password!=null) { $sqlstmt .= "SET PASSWD=".$password; }
        if($email!=null) { $sqlstmt .= ", SET EMAIL=".$email; }
        if($fname!=null) { $sqlstmt .= ", SET USERFIRSTNAME=".$fname; }
        if($lname!=null) { $sqlstmt .= ", SET USERLASTNAME=".$lname; }
        if($addr1!=null) { $sqlstmt .= ", SET ADDRESSLINE1=".$addr1; }
        if($addr2!=null) { $sqlstmt .= ", SET ADDRESSLINE2=".$addr2; }
        if($city!=null) { $sqlstmt .= ", SET CITY=".$city; }
        if($state!=null) { $sqlstmt .= ", SET STATE=".$state; }
        if($zip!=null) { $sqlstmt .= ", SET POSTALCODE=".$zip; }
        
        $sqlstmt .= " WHERE USERNAME=" . $uname;
        
        if(! $rs=$db->query($sqlstmt)) {
            return NULL;
        } 
        return true;
    }
}

?>
