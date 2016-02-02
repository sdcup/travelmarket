
function processCreateOffer() {
	// gather all the offer info and call processor
}

function resetBackground() {
    $(this).css({ "background-color": "white"});
}

function swapClasses(o, removeC, addC) {
    o.removeClass(removeC);
    o.addClass(addC);
}


function setRoundTripOption() {    
    swapClasses($("#co-roundtrip-option"), "co-form-value-inactive", "co-form-value-active");
    swapClasses($("#co-oneway-option"), "co-form-value-active", "co-form-value-inactive");
    
	//$("#co-returning").show();
        $("#co-returning-td").show();
	$("#co-roundtrip-details-block").show();
}

function setOnewayOption() {
    swapClasses($("#co-roundtrip-option"), "co-form-value-active", "co-form-value-inactive");
    swapClasses($("#co-oneway-option"), "co-form-value-inactive", "co-form-value-active");
    
	//$("#co-returning").hide();
        $("#co-returning-td").hide();
	$("#co-roundtrip-details-block").hide();
}

function setEconomyOption() {
   	swapClasses($("#co-economy-class"), "co-form-value-inactive", "co-form-value-active");
   	swapClasses($("#co-business-class"), "co-form-value-active", "co-form-value-inactive");    
    swapClasses($("#co-first-class"), "co-form-value-active", "co-form-value-inactive");
    
    // set price placeholder to min $50
    var offerP = $("#co-offer-price-value").spinner("value");
    if(offerP===undefined || offerP < 50) {
        $("#co-offer-price-value").spinner("value" , "50").spinner("option", "min", 50);
    } else {
         $("#co-offer-price-value").spinner("option", "min", 50);
    }
}

function setBusinessOption() {
   	swapClasses($("#co-business-class"), "co-form-value-inactive", "co-form-value-active");
   	swapClasses($("#co-economy-class"), "co-form-value-active", "co-form-value-inactive");    
    swapClasses($("#co-first-class"), "co-form-value-active", "co-form-value-inactive");
    
    // set price placeholder to min $250
    var offerP = $("#co-offer-price-value").spinner("value");
    if(offerP===undefined || offerP < 250) {
        $("#co-offer-price-value").spinner("value" , "250").spinner("option", "min", 250);
    } else {
         $("#co-offer-price-value").spinner("option", "min", 250);
    }
}

function setFirstOption() {
   	swapClasses($("#co-first-class"), "co-form-value-inactive", "co-form-value-active");
   	swapClasses($("#co-business-class"), "co-form-value-active", "co-form-value-inactive");    
    swapClasses($("#co-economy-class"), "co-form-value-active", "co-form-value-inactive"); 

    // set price placeholder to min $250
    var offerP = $("#co-offer-price-value").spinner("value");
    if(offerP===undefined || offerP < 250) {
        $("#co-offer-price-value").spinner("value" , "250").spinner("option", "min", 250);
    } else {
         $("#co-offer-price-value").spinner("option", "min", 250);
    }
}

function setupDatePicker() {
    var opts = {
        minDate: 1,
        showAnim: 'fold',
        showOn: 'button',
        buttonImage: "/images/calendar.gif",
        buttonImageOnly: true,
        buttonText: 'Select Date'
    };
    
	 $("#co-leaving" ).datepicker(opts);
	 $("#co-returning" ).datepicker(opts);
}

function setupAirportAutoComplete(airportTags) {
	$("#co-from").autocomplete({ source: airportTags });
	$("#co-to").autocomplete({ source: airportTags });
}


function timeRangeToString(range) {
	function valToString(val) {
		if(val == 24) { return "00AM"; } 
		else if(val==12) { return "12Noon"; }
		else if(val > 12) { return (val-12) + "PM"; }
		else return (val+"AM");
	}

	return (valToString(range[0]) + " - " + valToString(range[1]));
}

function updateSliderValues(event, ui) {
	var vals;
	
	switch($(this).attr('id')) {
		case 'co-to-leaving-slider':
			$("#co-leaving-time").html(timeRangeToString(ui.values));
			break;
		case 'co-to-arriving-slider':
			$("#co-arriving-time").html(timeRangeToString(ui.values));
			break;
		case 'co-return-leaving-slider':
			$("#co-return-leaving-time").html(timeRangeToString(ui.values));
			break;
		case 'co-return-arriving-slider':
			$("#co-return-arriving-time").html(timeRangeToString(ui.values));
			break;		
		default:
			alert("I don't know what happened");
	}
}

function setupOfferPrice() {
	var spinnerOptions = {
		min: 50,
		max: 10000,
		numberFormat: "C",
		culture: "en-US",
	};
	$("#co-offer-price-value").spinner(spinnerOptions);
}

function setupNumPassengers() {
	var spinnerOptions = {
		min: 1,
		max: 12,
	}
	$("#co-num-passengers").spinner(spinnerOptions);
}

function setupNumStops() {
	var spinnerOptions = {
		min: 0,
		max: 3
	}
	$("#co-num-stops").spinner(spinnerOptions);
}

function setupTimeSliders() {
	var sliderOptions = {
		min: 0,
		max: 24,
		values: [7,18],
		animate: "slow", 
		orientation: "horizontal", 
		slide: updateSliderValues,
		range: true
	};
	
	$("#co-to-leaving-slider").slider(sliderOptions);
	$("#co-to-arriving-slider").slider( sliderOptions );
	
	// sliders for return journey
	
	$("#co-return-leaving-slider").slider( sliderOptions );
	$("#co-return-arriving-slider").slider( sliderOptions );
}

function normalizeAirportCodes(airports) {
	var jObj = JSON.parse(airports);
	var aCodes = [], airprtCode, cityState, ridx=0;
	
	for(cityState in jObj) {
		aCodes[ridx++] = jObj[cityState] + " " + cityState;
	}
	return aCodes;
}

function processOfferDetails() {
	// extract values form the form
	
	var roundTrip, origin, destination, leavingDate, returnDate, 
		numPassengers, maxStops, ferPrice, classV = 'economy';
		
	var toLeavingEarliest, toLeavingLatest, toArrivingEarliest, toArrivingLatest,
		fromLeavingEarliest, fromLeavingLatest, fromArrivingEarliest, fromArrivingLatest;
	
	roundTrip = $("#co-roundtrip-option").hasClass("co-form-value-active");
    
    if($("#co-first-class").hasClass("co-form-value-active")) { classV = "first"; } 
    else if($("#co-business-class").hasClass("co-form-value-active")) { classV = "business"; } 
    
	
	origin = $("#co-from").val();
	destination = $("#co-to").val();
	
	leavingDate = $("#co-leaving").val();
	returnDate = $("#co-returning").val();
	
	numPassengers = $("#co-num-passengers").val();
	maxStop = $("#co-num-stops").val();
	
	// orign flight time details
	toLeavingEarliest = $("#co-to-leaving-slider").slider("values", 0);
	toLeavingLatest = $("#co-to-leaving-slider").slider("values", 1);	
	
	toArrivingEarliest = $("#co-to-arriving-slider").slider("values", 0);
	toArrivingLatest = $("#co-to-arriving-slider").slider("values", 1);	
	
	// destination flight time details
	fromLeavingEarliest = $("#co-return-leaving-slider").slider("values", 0);
	fromLeavingLatest = $("#co-return-leaving-slider").slider("values", 1);

	fromArrivingEarliestt = $("#co-return-leaving-slider").slider("values", 0);
	froArrivingLatest = $("#co-return-leaving-slider").slider("values", 1);	
	
	offerPrice = $("#co-offer-price-value").val();
		
	// error checking
	var errCount=0;
	if(numPassengers===undefined || numPassengers==0) {
		errCount++;
		$("#co-num-passengers").css({"background-color" : "salmon"});
	} 
	
	if(maxStop===undefined || maxStop==0) {
		maxStop = 0;
	}
	
	if( origin===undefined || origin.length==0) {
        errCount++;
		$("#co-from").css({"background-color" : "salmon"});
	}
	
	if(destination===undefined || destination.length==0) {
        errCount++;
        $("#co-to").css({"background-color" : "salmon"});
	}
	
	if(leavingDate===undefined || leavingDate.length==0) {
        errCount++;
        $("#co-leaving").css({"background-color" : "salmon"});
	}
	
	if(roundTrip && (returnDate===undefined || returnDate.length==0)) {
        errCount++;
        $("#co-returning").css({"background-color" : "salmon"});
	}
    
    if(offerPrice===undefined || offerPrice<=50) {
        errCount++;
        $("#co-offer-price-value").css({"background-color" : "salmon"});
	}

	if(errCount > 0) {

        $("#co-errors").css({"visibility" : "visible"});
        $("#co-offer-price-value").focusin(resetBackground);
        $("#co-leaving").focusin(resetBackground);
        $("#co-returning").focusin(resetBackground);
        $("#co-from").focusin(resetBackground);
        $("#co-to").focusin(resetBackground);
        $("#co-offer-price-value").focusin(resetBackground);
        $("#co-num-passengers").focusin(resetBackground);

	} else {
        //stick the data in localstorage and move to nextpage for collecting passenger details

        alert("processOfferDetails : called\n" + (roundTrip ? "roundtrip" : "oneway")
            + "\nFrom: " + origin 
            + "\nTo: " + destination
            + "\nLeaving: " + leavingDate
            + "\nReturning: " + returnDate
            + "\nClass: " + classV
            + "\n#Passengers: " + numPassengers
            + "\nStop: " + maxStop
            + "\nLeaving: " + toLeavingEarliest + " : " + toLeavingLatest
            + "\nArriving: " + toArrivingEarliest + " : " + toArrivingLatest
            + "\nLeaving: " + fromLeavingEarliest + " : " + fromLeavingLatest
            + "\nArriving: " + fromArrivingEarliestt + " : " + froArrivingLatest
            + "\nOffer Price : " + offerPrice);
    }
}

function displayCreateOfferForm () {

	var aCodes;
	
	// get airport codes from server
	$.ajax({
		url: "/php/airport-codes.php",
			type: 'post',
			success: function (data, status) { 
				    aCodes = normalizeAirportCodes(data);
				},
			error: function () { alert("Unable to get airport codes from server"); }
	});
	
	// display html
	$.ajax({
			url: "/html/createoffer.html",
			type: 'post',
			data: { 'gethtml' : 'createoffer.html'},
			success: function (data, status) { 
                    $("#content").html(data); 
                
                    setupAirportAutoComplete(aCodes);
                    setupNumPassengers();
                    setupNumStops();
                    setupTimeSliders();
                    setupOfferPrice();
                    setRoundTripOption();
                    setEconomyOption();
                    setupDatePicker();
                					
                    $("#co-roundtrip-option").click(setRoundTripOption);
                    $("#co-oneway-option").click(setOnewayOption);
                    $("#co-continue-button").click(processOfferDetails);
                    $("#co-economy-class").click(setEconomyOption);
                    $("#co-business-class").click(setBusinessOption);
                    $("#co-first-class").click(setFirstOption);
				},
			error: function () { alert("Unable to get data for createoffer.html from server"); }
			} 
	);
}