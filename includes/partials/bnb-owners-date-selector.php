<?php

/**
 *
 * @since 1.0.0
 *
 * Date picker for the user to be able to select their start date and their end date
 * TODO: https://rudrastyh.com/wordpress/self-hosted-plugin-update.html
 */

class date_selector extends Bnb_Owners_API
{

    public function display_date_selector($bnb_id)
    {

        /**
         * In this function we want to create our Date Pickers
         * TODO:
         *      Start Date field
         *      End Date Field
         *      Create our AJAX calls and action handlers
         */

        $votes = 0;
        $votes = ($votes == "") ? 0 : $votes;

        $nonce = wp_create_nonce("start_date_nonce");
        $link = admin_url('admin-ajax.php?action=my_user_vote&nonce=' . $nonce);
        $linkAJAX = admin_url('admin-ajax.php');
        $html = '
            <div class="datePickerContainer">
                <div class="startDateContainer">
                    <label for="start_date" name="start_date_label">Start Date</label>
                    <input type="date" name="start_date" value="" class="start_date" data-nonce="' . $nonce . '">
                    </input>
                </div>
                <div class="endDateContainer">
                    <label for="end_date" name="end_date_label">End Date</label>
                    <input type="date" name="end_date"value="" class="end_date" data-nonce="' . $nonce . '">
                    </input>
                </div>
                <div class="checkAvailabilityContainer">
                    <a class="check_availability" data-nonce="' . $nonce . '">Check Dates</a>
                </div>
            </div>
            <div class="modal" id="myModal">
                <div class="availableRooms modal-content">
                    <div class="availableRoomsHeader">
                        <p>Select the room(s) you wish to book</p>
                    </div>
                    <div class="availableHeader">
                        <div class="currentMonth"></div>
                        <div class="availableDates"></div>
                    </div>
                    <div class="availableBody">

                    </div>
                    <div class="availableFooter">
                        <div class="availableFooter-left" style="font-size: 14px">
                            Room rates always include breakfast unless otherwise shown > All rates in Euro
                        </div>
                        <div class="availableFooter-right">
                            <form method="post" action="https://your-booking.com/book" name="submitToYourBooking" class="submitBookingForm">
                                                        
                                <button name="submitButton" class="submitBooking"></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <style>

                /*Include Font*/
                @font-face {
                    font-family: "Filson";
                    src: url("'. plugin_dir_url( __FILE__ ) . '../assets/fonts/filson.ttf");
                }

                /**
                *
                *Display Modal
                *
                **/
                /* The Modal (background) */
                .modal {
                    display: none; /* Hidden by default */
                    position: fixed; /* Stay in place */
                    z-index: 1; /* Sit on top */
                    left: 0;
                    top: 0;
                    width: 100%; /* Full width */
                    height: 100%; /* Full height */
                    overflow: auto; /* Enable scroll if needed */
                    background-color: rgb(0,0,0); /* Fallback color */
                    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
                    max-width: 100% !important;
                    margin-top: 0px !important;
                    vertical-align: middle !important;
                    z-index: 999999;

                }

                .availableRoomsHeader{
                    /* background: #222222; */
                    /* height: 80px; */
                    width: 100% !important;
                    /* padding-left: 20px; */
                    /* color: #ffffff !important; */
                    margin-top: 0px !important;
                    /* top: 50% !important; */
                    position: relative;
                    
                }

                .availableRoomsHeader p{
                    top: 50% !important;
                    position: relative;
                    margin: 0px !important;
                    font-size: 16px;
                    padding-bottom: 5px;
                }

                .availableNight .roomName{
                    font-size: 18px !important;
                }

              

                /* Modal Content/Box */
                .modal-content {
                    background-color: #ffffff;
                    margin: 15% auto; /* 15% from the top and centered */
                    /* padding: 20px; */
                    /* border: 1px solid #888; */
                    width: 70%; /* Could be more or less, depending on screen size */
                    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
                    -webkit-animation-name: animatetop;
                    -webkit-animation-duration: 0.2s;
                    animation-name: animatetop;
                    animation-duration: 0.2s;
                    margin-top: 3% !important;
                    font-family: "Filson", sans-serif;
                }



                  /* Add Animation */
                @-webkit-keyframes animatetop {
                    from {top:-300px; opacity:0} 
                    to {top:0; opacity:1}
                }

                @keyframes animatetop {
                    from {top:-300px; opacity:0}
                    to {top:0; opacity:1}
                }

                /* The Close Button */
                .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                }

                .close:hover,
                .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
                }

                .datePickerContainer{
                    width: 100%;
                    margin: 0 auto !important;
                    
                }

                .datePickerContainer label{
                    font-family: "Filson", sans-serif;
                }

                .startDateContainer{
                    width: 40%;
                    float: left;
                }

                .startDateContainer input{
                    width: 60%;
                    margin-left: 10px;
                    display: inline-block;
                }

                .endDateContainer{
                    width: 40%;
                    float: left;
                }

                .endDateContainer input{
                    width: 60%;
                    margin-left: 10px;
                    display: inline-block;
                }

                .start_date, .end_date{
                    border-radius: 0px;
                    height: 40px;
                }

                .checkAvailabilityContainer{
                    width: 20%;
                    float: left;
                    font-family: "Filson", sans-serif;
                }

                .availableRooms{
                    display: none;
                    clear: both;
                    /* padding-top: 30px !important; */
                    /* margin: 0 auto !important; */
                    padding: 20px !important;
                }

                .availableHeader{
                    /* background: #222222; */
                    display: flex;
                }

                .availableNight{
                    clear: both;
                    display: flex;
                }

                .availableFooter{
                    display: none;
                    padding: 10px;
                }

                .availableNight .roomName, .availableHeader .currentMonth{
                    max-width: 30%;
                    display: flex;
                    float: left;
                    border-left: 1px solid #efefef;
                    border-top: 1px solid #efefef;
                    border-right: 1px solid #efefef;
                    padding: 5px;
                    flex: 1;
                    align-items: center;
                }

                .currentMonth{
                    font-size: 26px;
                }

                .nights, .availableDates{
                    display: flex;
                    width: 70%;
                    float: left;
                    flex: 1;
                }

                .nights div, .availableDates div{
                    flex: 1;
                    display: flex;
                    /* border-left: 1px solid #efefef; */
                    border-top: 1px solid #efefef;
                    border-right: 1px solid #efefef;
                    padding: 5px;
                    vertical-align: middle;
                    align-items: center;
                    justify-content: center;
                }

                .availableDates div span{
                    display: block;
                }

                .bookNowHeader, .bookNowBody{
                    min-width: 100px !important;
                }

                .bookNowBody{
                    /* padding: 0px !important; */
                }

                .bookNowBody a{
                    text-decoration: none !important;
                    background: #222222;
                    border: 2px solid #222222;
                    color: #ffffff;
                    padding-top: 5px;
                    padding-bottom: 5px;
                    /* margin: 5px; */
                    min-width: 50% !important;
                    text-align: center;
                    font-size: 18px !important;
                    padding-left: 10px;
                    padding-right: 10px;
                }

                .bookNowBody a:hover{
                    background: #ffee00;
                    border: 2px solid #222222;
                    color: #222222;
                }

                .bookNowHeader{
                        flex-direction: column;
                        justify-content: center !important;
                        text-align: center;
                        line-height: 18px !important;
                }

                .availableDateFormat-day, .availableDateFormat-date, .availableDateFormat-month{
                    flex: 1;
                    flex-wrap: wrap;
                    flex-direction: row;
                }

                .availableDateFormat-day, .availableDateFormat-month{
                    font-size: 14px;
                    line-height: 14px;
                }

                .availableDateFormat-date{
                    font-size: 28px;
                    line-height: 28px;
                }

                .availableDates div{
                    background: #ffee00 !important;
                }

                .availableDates div.bookNowHeader{
                    background: #ffffff !important;
                }

                .availableDates div[class^="date"]{
                    flex-direction: column;
                    padding: 10px;
                    min-width: 60px !important;
                    padding-left: 5px;
                    padding-right: 5px;
                }

                .nights div[class^="night"]{
                    min-width: 60px !important;
                }

                .currentMonth{
                    /* justify-content: center; */
                    vertical-align: middle;
                    align-items: center;
                    padding-left: 10px !important;
                }

                .availableNight:last-child{
                    border-bottom: 1px solid #efefef;
                }

                .roomName{
                    padding-left: 10px !important;
                    padding-right: 10px;
                }

                .availableFooter{
                    border-left: 1px solid #efefef;
                    border-bottom: 1px solid #efefef;
                    border-right: 1px solid #efefef;
                    width: 100% !important;
                    height: 80px;
                }

                .availableFooter button{
                    background: #222222 !important;
                    border-radius: 0px !important;
                }

                .availableFooter button:hover{
                    background: #ffee00 !important;
                    color: #222222 !important;
                }

                .bookNowBody:last-child{
                    border-bottom: 1px solid #efefef;
                }

                .availableFooter-left{
                    width: 60%;
                    /* display: inline-block; */
                    float: left;
                    font-size: 16px;
                    padding-top: 15px;
                }

                .availableFooter-right{
                    width: 40%;
                    /* display: inline-block; */
                    text-align: right !important;
                    float: left;
                }

                .check_availability{
                    background: #222222;
                    padding-top: 15px;
                    padding-bottom:15px;
                    padding-left: 30px;
                    padding-right: 30px;
                    color: #ffffff !important;
                     cursor:pointer;
                     text-decoration: none !important;
                     font-family: "Filson", sans-serif;
                }

                .submitBooking{
                    border: 2px solid #222222;
                    font-family: "Filson", sans-serif;
                    padding-top: 15px;
                    padding-bottom: 15px;
                }

                .submitBooking:hover{
                    border: 2px solid #222222;
                }


            </style>

        <script>
            jQuery(document).ready( function() {
                
                //Modal Script
                // Get the modal
                var modal = document.getElementById("myModal");

                // Get the button that opens the modal
                var btn = document.getElementById("myBtn");

                // Get the <span> element that closes the modal
                var span = document.getElementsByClassName("close")[0];

                // When the user clicks on the button, open the modal
                jQuery(".check_availability").click(function() {
                     start_date = jQuery(".start_date").val();
                    end_date = jQuery(".end_date").val();

                    //Check if Dates are selected and disable the check Dates button
                    if(start_date == "" || end_date == ""){
                        alert("Please select a date");
                    }else{
                        modal.style.display = "block";
                    }
                });

                // When the user clicks on <span> (x), close the modal
                // span.onclick = function() {
                //      modal.style.display = "none";
                // }

                // When the user clicks anywhere outside of the modal, close it
                window.onclick = function(event) {
                    if (event.target == modal) {
                        modal.style.display = "none";
                    }
                }


                //Default dates & set minimum dates for datepickers
                //Start Date
                var today = new Date().toISOString().split("T")[0];
                document.getElementsByName("start_date")[0].setAttribute("min", today);

                //End Date
                var tomorrow = new Date().toISOString().split("T")[0];
                var tomorrowAdd = moment(tomorrow, "YYYY-MM-DD").add("days", 1).toDate();
                tomorrowAdd = moment(tomorrowAdd).format("YYYY-MM-DD");
                document.getElementsByName("end_date")[0].setAttribute("min", tomorrowAdd);

                jQuery(".check_availability").click( function(e) {
                    e.preventDefault();
                    nonce = jQuery(this).attr("data-nonce")
                    start_date = jQuery(".start_date").val();
                    end_date = jQuery(".end_date").val();

                    //Check if Dates are selected and disable the check Dates button
                    if(start_date == "" || end_date == ""){
                        // alert("Please select a date");
                    }else{

                        jQuery.ajax({
                            type: "post",
                            dataType: "json",
                            url: "'. $linkAJAX . '",
                            
                            data: {
                                action: "process_date_selector",
                                start_date: start_date,
                                end_date: end_date,
                                nonce: nonce
                            },
                            success: function(response) {
                            
                                
                                        //Show .availableRooms
                                        jQuery(".availableRooms").css({"display":"block"});
                                        var totalSum = 0;
                                        //clear our initial outputs incase we get called multiple times
                                        jQuery(".availableBody").html("");
                                        jQuery(".currentMonth").html("");
                                        jQuery(".availableDates").html("");
                                        jQuery(".bookNowHeader").html("");

                                        //Create our POST array
                                        var bookingSetup = `
                                            <input type="hidden" name="bnb_id" value="' . $bnb_id .'"></input>
                                            <input type="hidden" name="arrival" value="` + start_date +`"></input>
                                            <input type="hidden" name="departure" value="` + end_date +`"></input>

                                        `;
                                        jQuery(".submitBookingForm").append(bookingSetup);

                                    

                                        //Sort out dates to be displayed above table
                                        startMonth = moment(start_date).format("MMMM YYYY");
                                        var relevantStartDate = moment(start_date);
                                        var relevantEndDate = moment(end_date);
                                        var noDays = relevantEndDate.diff(relevantStartDate, "days");
                                        
                                        //loop through dates and append to availableRooms header
                                        for(var i = 0; i <= noDays; i++){
                                            var dayContext = moment(start_date, "YYYY-MM-DD").add("days", i).toDate();
                                        
                                            console.log("Day " + i + ": " + start_date);
                                            var dayAppend = `
                                                <div class="date` + i +`"> 
                                                    <span class="availableDateFormat-day">` + moment(dayContext).format("ddd") +`</span> 
                                                    <span class="availableDateFormat-date">`+ moment(dayContext).format("D") + `</span>
                                                    <span class="availableDateFormat-month">` + moment(dayContext).format("MMM") + `</span>
                                                </div>
                                            `;

                                            jQuery(".availableDates").append(dayAppend);
                                        }

                                        
                                        jQuery(".currentMonth").append(startMonth);
                                        for(var i = 0; i < response.length; i++){
                                            // console.log(response[i]);
                                            // console.log(start_date);
                                                var nights = [];
                                                nights[i] = [];
                                                var relevantDate = [];
                                                for(var j = 0; j <= response[i]["available"]; j++){
                    
                                                    nights[i][j] = `<div class="night` + j + `" data-room-id-night="` + response[i]["room_id"] +`">&euro;` + (response[i]["price"].toString() / response[i]["nights"].toString()) + `</div>`;
                                                    
                                                }
                                                jQuery(".availableDates").append(relevantDate);

                                                //  console.log(nights[i]);
                                                var returnBody = `
                                                    <div class="availableNight">
                                                        <div class="roomName" data-room-id="` + response[i]["room_id"] +`">` + response[i]["name_room"] + `</div>
                                                        <div class="nights">` + nights[i].join("") + ` <div class="bookNowBody"><a href="#"  data-room-id="` + response[i]["room_id"] +`" data-room-total-price="` + response[i]["price"] +`" data-room-sleeps="` + response[i]["sleeps"] +`" class="selectRoom">Select</a></div></div>
                                                        
                                                    </div>
                                                `;

                                            
                                                
                                                jQuery(".availableBody").append(returnBody);
                                        }

                                        var bookNow = `
                                            <div class="bookNowHeader">Select Rooms</div>
                                        `;
                                        jQuery(".availableDates").append(bookNow);
                                        
                                        

                                        jQuery(".selectRoom").click(function(e) {
                                        var roomID = jQuery(this).attr("data-room-id");
                                            
                                        if( (jQuery(this).attr("data-info-selected") == undefined) || (jQuery(this).attr("data-info-selected") == "false") ){
                                                
                                                //Set colors
                                                jQuery("div[data-room-id-night=" + roomID+ "]").css({"background":"#ffee00"});
                                                jQuery("div[data-room-id-night=" + roomID+ "]").last().css({"background":"#ffffff"});

                                                jQuery(".availableFooter").show();

                                                //Designate as selected
                                                jQuery(this).attr("data-info-selected", "true");
                                                jQuery(this).text("€" + jQuery(this).attr("data-room-total-price"));

                                                totalSum += parseFloat(jQuery(this).attr("data-room-total-price"));
                                                console.log(totalSum);

                                                //Populate our footer form
                                                jQuery(".submitBooking").html("€" + totalSum + " - Book Now");

                                                var occupancy = jQuery(this).attr("data-room-sleeps");

                                                var roomIDinput = `
                                                    <input type="hidden" name="roomids[]" class="room-` + roomID + `" value=" `+ roomID + `"></input>
                                                    <input type="hidden" name="roomnumbers[]" class="room-` + roomID + `" value="1"></input>
                                                    <input type="hidden" name="occupancy[]" class="room-` + roomID + `" value="` + occupancy  + `"></input>
                                                `;

                                                

                                                jQuery(".submitBookingForm").append(roomIDinput);
                                                

                                        }else{
                                            jQuery("div[data-room-id-night=" + roomID+ "]").css({"background":"#ffffff"});
                                            jQuery(this).removeAttr("data-info-selected");
                                            jQuery(this).text("Select");

                                                //If deselected, remove the room attributes from submit form
                                                jQuery(".room-" + roomID).remove();

                                            totalSum -= parseFloat(jQuery(this).attr("data-room-total-price"));
                                        }
                                        console.log(roomID);
                                        e.preventDefault();

                                        //Check to see if theres none selected and hide footer
                                        if( document.querySelectorAll("[data-info-selected]").length != 0 ){
                                                console.log("We have the attr");
                                                var footerForm = `
                                                    
                                                `;
                                                
                                                // jQuery(".availableFooter-right").html(footerForm);
                                                jQuery(".submitBooking").html("€" + totalSum + " - Book Now");

                                        }else{
                                            console.log("nope");
                                            jQuery(".availableFooter").hide();
                                        }
                                        });
                                        
                                        
                                    

                                
                            }
                        })
                    }
                });
            });

        </script>
        <script type="text/javascript" src="https://momentjs.com/downloads/moment.min.js"></script>
        ';

        return $html;

    }

    public function process_date_selector()
    {
        echo json_encode('Here');
        //Verify the nonce value passed
        // if (!wp_verify_nonce($_REQUEST['nonce'], "start_date_nonce")) {
        //     exit("Duplicate Request");
        // }

        //Get our passed value
        $startDate = "success";

        // echo json_encode("result");

        //decide whether to return it as JSON or not
        // if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        //     $result = json_encode($startDate);
        //     echo $result;
        // } else {
        //     header("Location: " . $_SERVER["HTTP_REFERER"]);
        // }

        die();
    }

}