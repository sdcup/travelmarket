<?php
    /* process offer details */
    echo $_POST['offer'];
    echo "<p>";
    echo $_POST['passengerlist'];
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
    }
?>
