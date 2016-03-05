<?php

require_once "logger.php";
require_once "config.php";
require_once "UserDBAPI.php";

class User {
    private $_uName;
    private $_email;
    private $_uid;
    private $_fName;
    private $_lName;
    private $_addr1;
    private $_addr2;
    private $_phone;
    private $_city;
    private $_state;
    private $_zip;
    
    public static function getCurrentUser() {
        // TODO - define this function
        return null;
    }

    public static function createUser($uname, $password, $email, $fname, $lname,
                                    $phone, $addr1, $addr2, $city, $state, $zip, $vToken) {
        // validate fields
        /* TODO - reinstate this check */

        if($uname==null || $password==null || $email==null || $fname==null || 
            $lname==null /*|| $phone==null || $addr1==null || $city ==null || $state==null ||
            $zip==null*/) {
                throw new BadFunctionCallException("One or more args are null");
        }

        if(strlen($uname)==0 || strlen($password)==0 || strlen($email)==0 || strlen($email)==0 || 
            strlen($fname)==0 || strlen($lname)==0 /*|| strlen($phone)==0 ||strlen($addr1)==0
            || strlen($city) ==0 || strlen($state)==0 || strlen($zip)==0*/) {
                throw new BadFunctionCallException("One or more args are undefined");
        }

        // validate this user does not exist
        if(UserDBAPI::userExists($uname)!=NULL) {
            throw new InvalidArgumentException("user already in the system");
        }
    
        //create user and get uid
        try {
            $_uid = UserDBAPI::createUser($uname, $password, $email, $fname, $lname, $phone, $addr1, $addr2, $city, $state, $zip, $vToken);
        } catch (Exception $e) {
            throw new Exception("This is an unmitigated disaster", 0, $e);
        } 

        // call the constructor and return newly created user
        $newUser = new self($_uid, $uname, $email, $fname, $lname, $phone, $addr1, $addr2, $city, $state, $zip);
        return $newUser;
    }
    
    public static function getUser($uname) {
        
        if($uname===NULL || strlen($uname)==0) {
            throw new InvalidArgumentException("No username specified");
        }
        // fetch the user from database, if successful, create a new instance
        $uDetails = UserDBAPI::getUserDetails($uname);
        $u = new self($uDetails['ID'],
                        $uname, $uDetails['EMAIL'],
                           $uDetails['USERLASTNAME'], $uDetails['USERFIRSTNAME'], 
                           $uDetails['PHONE'], $uDetails['ADDRESSLINE1'],
                           $uDetails['ADDRESSLINE2'], $uDetails['CITY'],
                           $uDetails['STATE'], $uDetails['POSTALCODE']);

        return $u;
    }
    
    public static function verifyUser($uname, $tok) {

        if(($uname===NULL || strlen($uname)==0) ||
           ($tok===NULL || strlen($tok)==0)) {
            throw new InvalidArgumentException("invalid username or verification token");
        }

        // fetch the user from database, if successful,
        // verify the user was previously inactive, and,
        // token matches
        $uDetails = UserDBAPI::getUserDetails($uname);
        if($uDetails==null) {
            return false;
        }

        if(strcmp($uDetails['ACTIVATIONCODE'], $tok)!=0 || $uDetails['ACTIVE']==1) {
            return false;
        }

        // activate user
        UserDBAPI::setActiveBit($uDetails['ID'], 1);
        return true;
    }


    function __construct($uid, $uname, $email, $fname, $lname, $phone, $addr1, $addr2, $city, $state, $zip) {
        // fill the properties 
        $this->_uid = $uid;
        $this->_uName = $uname;
        $this->_email = $email;
        $this->_lName = $lname;
        $this->_fName = $fname;
        $this->_phone = $phone;
        $this->_addr1 = $addr1;
        $this->_addr2 = $addr2;
        $this->_city = $city;
        $this->_state = $state;
        $this->_zip = $zip;
    }
 
    public function activateUser() {
            return UserDBAPI::setActiveBit($this->_uName, true);
    }

    public function disableUser() {
        return UserDBAPI::setActtiveBit($this->_uName, false);
    } 
    
    // getter/setter functions
    function getUserName() { return $this->_uName; }
    
    function getUserPhone() { return $this->_phone; }
    
    function getUserEmail() { return $this->_email; }
    
    function getName(&$first, &$last) { 
          $first = $this->_fName; 
          $last = $this->_lName; 
    }
    
    function getAddr(&$addr1, &$addr2, &$city, &$state, &$zip) {
        $addr1 = $this->_addr1;
        $addr2 = $this->_addr2;
        $city = $this->_city;
        $state = $this->_state;
        $zip = $this->_zip;
    } 
    
    /**
     * updateUser
     * updates all non-null values supplied in the args in the database
     * 
     * @param type $password
     * @param type $email
     * @param type $fname
     * @param type $lname
     * @param type $addr1
     * @param type $addr2
     * @param type $city
     * @param type $state
     * @param type $zip
     */
    function updateUser($password, $email, $fname, $lname, $addr1, $addr2, $city, $state, $zip) {
        // call db function to update user
        try {
            UserDBAPI::updateUser($this->_uName, $password, $email, $fname, $lname, 
                                $addr1, $addr2, $city, $state, $zip);
        } catch (Exception $ex) {
            throw new Exception("Unabel to update user record : ".$ex.getMessage(), 0, $ex);
        }
        return true;
    }
}   



/* test code 

// register a user
$u = User::createUser("gitty2", "something", "godrej@aol.com", 
    "Gitty", "Godrej-Dhingra", "408-504-6491", "11765 Seven Springs Parkway", null, "Cupertino", "CA", "95014");
echo "=========printing new user\r\n";


// validate that user exists
$u  = User::getUser($u->getUserName());
echo "=========printing  user  from getUser()\r\n";
print_r($u);
 
// activate user
$u->activateUser();


//test getter functions
echo "User name - ".$u->getUserName()."\r\n";
echo "User phone - ".$u->getUserPhone()."\r\n";
$u->getName($first, $last);
echo "User name - (".$first.", ".$last.")\r\n";
$u->getAddr($addr1, $addr2, $city, $state, $zip);
echo "Address: $addr1\r\n\t$addr2\r\n\t$city, $state, $zip";
END OF TEST CODE*/


?>
