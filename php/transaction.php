<?php

class Transaction {
    private $_tid;
    private $_verified;
    private $_offerId;
    private $_buyerId;
    private $_sellerId;
    private $_transactionDate;
    private $_itineraryId;
    private $_closingDate; //date when funds will be transferred to the seller
    private $_fundsRemitted;
    private $_amount;
    
    function create($buyerId, $sellerId, $itin) {
        // pull the amount info from the offer record
        // closingdate shouyld be 24 hours after journey completion
        // we need flight info here as well
        
        return $_tid;
    }
   
    
    function getDetails() {
        
    }
    
    function verified() {
        
    }
    
    function setVerified() {
        
    }
    
    function getTransactionsByBuyer() {
        
    }
    
    function getTransactionsBySeller() {
        
    }
    
    function getTranbsactionsByOfferDate() {
        
    }
    
    function getTranbsactionsByClosingDate() {
        
    }
?>
