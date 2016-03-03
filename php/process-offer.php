<?php
    /* process offer details */
    require_once "Offer.php";


    $op = $_POST['op'];

    if(strcmp($op, "createoffer")==0) {

        /* process offer details */
        $oDetails = json_decode($_POST['offer'], TRUE);
        $pList = json_decode($_POST['passengerlist']);

        try {
            $o = Offer::CreateNewOffer($oDetails, $pList);
        } catch (Exception $e) {
            echo "Caught exception - ".$e->getMessage();
            die();
        }
    } else {
        echo "you want me to do $op....I don't know how to do that";
    }
?>
