var TMCOModule = (function () {

    var offerDetails = null;

    function processCreateOffer() {
        // gather all the offer info and call processor
    }

    function resetBackground() {
        $(this).css({
            "background-color": "white"
        });
    }

    function swapClasses(o, removeC, addC) {
        o.removeClass(removeC);
        o.addClass(addC);
    }


    function setRoundTripOption() {
        swapClasses($("#co-roundtrip-option"), "co-form-value-inactive", "co-form-value-active");
        swapClasses($("#co-oneway-option"), "co-form-value-active", "co-form-value-inactive");

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
        if (offerP === undefined || offerP < 50) {
            $("#co-offer-price-value").spinner("value", "50").spinner("option", "min", 50);
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
        if (offerP === undefined || offerP < 250) {
            $("#co-offer-price-value").spinner("value", "250").spinner("option", "min", 250);
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
        if (offerP === undefined || offerP < 250) {
            $("#co-offer-price-value").spinner("value", "250").spinner("option", "min", 250);
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

        $("#co-leaving").datepicker(opts);
        $("#co-returning").datepicker(opts);
    }

    function setupAirportAutoComplete(airportTags) {
        $("#co-from").autocomplete({
            source: airportTags
        });
        $("#co-to").autocomplete({
            source: airportTags
        });
    }


    function timeRangeToString(range) {
        function valToString(val) {
            if (val == 24) {
                return "00AM";
            } else if (val == 12) {
                return "12Noon";
            } else if (val > 12) {
                return (val - 12) + "PM";
            } else return (val + "AM");
        }

        return (valToString(range[0]) + " - " + valToString(range[1]));
    }

    function updateSliderValues(event, ui) {
        var vals;

        switch ($(this).attr('id')) {
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
            max: 8,
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
            values: [7, 18],
            animate: "slow",
            orientation: "horizontal",
            slide: updateSliderValues,
            range: true
        };

        $("#co-to-leaving-slider").slider(sliderOptions);
        $("#co-to-arriving-slider").slider(sliderOptions);

        // sliders for return journey

        $("#co-return-leaving-slider").slider(sliderOptions);
        $("#co-return-arriving-slider").slider(sliderOptions);
    }

    function normalizeAirportCodes(airports) {
        var jObj = JSON.parse(airports);
        var aCodes = [],
            airprtCode, cityState, ridx = 0;

        for (cityState in jObj) {
            aCodes[ridx++] = jObj[cityState] + " " + cityState;
        }
        return aCodes;
    }

    function setupDOBSpinners(numP) {
        var spinnerMonthOptions = {
            min: 1,
            max: 31,
        }

        var spinnerYearOptions = {
            min: 1915,
            max: 2016,
        }
        for (var pidx = 1; pidx <= numP; pidx++) {
            // day of month spinner
            $("#co-dob-day-" + pidx).spinner(spinnerMonthOptions);
            // year spinner
            $("#co-dob-year-" + pidx).spinner(spinnerYearOptions);

        }
    }

    function submitOffer() {
        // read all passenger data
        var passengers = [];
        var errCount = 0,
            nP = offerDetails['numPassengers'];

        for (var pidx = 1, aIdx; pidx <= nP; pidx++) {
            // get individual passenger records
            aIdx = pidx - 1;
            passengers[aIdx] = {};
            passengers[aIdx].name = {};
            passengers[aIdx].dob = {};
            passengers[aIdx].name['fname'] = $("#co-fname-" + pidx).val();
            passengers[aIdx].name['mname'] = $("#co-mname-" + pidx).val();
            passengers[aIdx].name['lname'] = $("#co-lname-" + pidx).val();
            passengers[aIdx].gender = $("#co-gender-" + pidx).val();
            passengers[aIdx].dob['month'] = $("#co-dob-month-" + pidx).val();
            passengers[aIdx].dob['day'] = $("#co-dob-day-" + pidx).val();
            passengers[aIdx].dob['year'] = $("#co-dob-year-" + pidx).val();
        }
        
        $.ajax({
              url: "/php/process-offer.php",
              type: 'post',
              data: {   'op'            : 'createoffer',
                        'offer'         : JSON.stringify(offerDetails),
                        'passengerlist' : JSON.stringify(passengers),
                    },
              success: function (data, status) {
                            $("#content").html(data);
              },
              error: function () { alert("Unable to process request"); }
        });

        // TODO - error checking on data
    }

    function processOfferDetails() {
        // extract values form the form
        var offerData = {};
        var val;

        offerData['roundTrip'] = $("#co-roundtrip-option").hasClass("co-form-value-active");

        if ($("#co-first-class").hasClass("co-form-value-active")) {
            offerData['classV'] = "first";
        } else if ($("#co-business-class").hasClass("co-form-value-active")) {
            offerData['classV'] = "business";
        } else {
            offerData['classV'] = "economy";
        }

        offerData['origin'] = $("#co-from").val();
        offerData['destination'] = $("#co-to").val();

        val = $("#co-leaving").val().split("/");
        offerData['leavingDate'] = { year : val[2], month : val[0], day : val[1]};

        val = $("#co-returning").val().split("/");
        offerData['returnDate'] = { year : val[2], month : val[0], day : val[1] };

        offerData['numPassengers'] = $("#co-num-passengers").val();
        offerData['maxStops'] = $("#co-num-stops").val();

        // orign flight time details
        offerData['toLeavingEarliest'] = $("#co-to-leaving-slider").slider("values", 0) + ":00";
        offerData['toLeavingLatest'] = $("#co-to-leaving-slider").slider("values", 1) + ":00";

        offerData['toArrivingEarliest'] = $("#co-to-arriving-slider").slider("values", 0) + ":00";
        offerData['toArrivingLatest'] = $("#co-to-arriving-slider").slider("values", 1) + ":00";

        // destination flight time details
        offerData['fromLeavingEarliest'] = $("#co-return-leaving-slider").slider("values", 0) + ":00";
        offerData['fromLeavingLatest'] = $("#co-return-leaving-slider").slider("values", 1) + ":00";

        offerData['fromArrivingEarliestt'] = $("#co-return-leaving-slider").slider("values", 0) + ":00";
        offerData['froArrivingLatest'] = $("#co-return-leaving-slider").slider("values", 1) + ":00";

        offerData['offerPrice'] = $("#co-offer-price-value").val();

        // error checking
        var errCount = 0;
        if (offerData['numPassengers'] === undefined || offerData['numPassengers'] == 0) {
            errCount++;
            $("#co-num-passengers").css({
                "background-color": "salmon"
            });
        }

        if (offerData['maxStops'] === undefined || offerData['maxStops'] == 0) {
            offerData['maxStops'] = 0;
        }

        if (offerData['origin'] === undefined || offerData['origin'].length == 0) {
            errCount++;
            $("#co-from").css({
                "background-color": "salmon"
            });
        }

        if (offerData['destination'] === undefined || offerData['destination'].length == 0) {
            errCount++;
            $("#co-to").css({
                "background-color": "salmon"
            });
        }

        if (offerData['leavingDate'] === undefined || offerData['leavingDate'].length == 0) {
            errCount++;
            $("#co-leaving").css({
                "background-color": "salmon"
            });
        }

        if (offerData['roundTrip'] && (offerData['returnDate'] === undefined || offerData['returnDate'].length == 0)) {
            errCount++;
            $("#co-returning").css({
                "background-color": "salmon"
            });
        }

        if (offerData['offerPrice'] === undefined || offerData['offerPrice'] <= 50) {
            errCount++;
            $("#co-offer-price-value").css({
                "background-color": "salmon"
            });
        }

        if (errCount > 0) {

            $("#co-errors").css({
                "visibility": "visible"
            });
            $("#co-offer-price-value").focusin(resetBackground);
            $("#co-leaving").focusin(resetBackground);
            $("#co-returning").focusin(resetBackground);
            $("#co-from").focusin(resetBackground);
            $("#co-to").focusin(resetBackground);
            $("#co-offer-price-value").focusin(resetBackground);
            $("#co-num-passengers").focusin(resetBackground);

        } else {
            offerDetails = offerData;

            $.ajax({
                url: "/html/passenger-details-form.html",
                type: 'post',
                success: function (data, status) {
                    $("#content").html(data);

                    // depending on number of passengers, hide unneeded passenger blocks
                    for (var pidx = 8; pidx > offerData['numPassengers']; pidx--) {
                        var bName = "#passenger-details-" + pidx;
                        $(bName).hide();
                    }
                    setupDOBSpinners(offerData['numPassengers']);
                    $("#co-passenger-continue-button").click(submitOffer);
                },
                error: function () {
                    alert("Unable to get airport codes from server");
                }
            });
        }
    }

    function displayCreateOfferForm() {

        var aCodes;

        // get airport codes from server
        $.ajax({
            url: "/php/airport-codes.php",
            type: 'post',
            success: function (data, status) {
                aCodes = normalizeAirportCodes(data);
            },
            error: function () {
                alert("Unable to get airport codes from server");
            }
        });

        // display html
        $.ajax({
            url: "/html/createoffer.html",
            type: 'post',
            data: {
                'gethtml': 'createoffer.html'
            },
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
            error: function () {
                alert("Unable to get data for createoffer.html from server");
            }
        });
    }

    return ({
        showForm: displayCreateOfferForm
    });
})();
