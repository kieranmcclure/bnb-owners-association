<?php

/**
 *
 * @since 1.0.0
 *
 * Date picker for the user to be able to select their start date and their end date
 */

class date_selector extends Bnb_Owners_API
{

    public function display_date_selector()
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
                    <input type="date" name="start_date" value="2020-11-10" class="start_date" data-nonce="' . $nonce . '">
                    </input>
                </div>
                <div class="endDateContainer">
                    <label for="end_date" name="end_date_label">End Date</label>
                    <input type="date" name="end_date"value="2020-11-18" class="end_date" data-nonce="' . $nonce . '">
                    </input>
                </div>
                <div class="checkAvailabilityContainer">
                    <a class="check_availability" data-nonce="' . $nonce . '">Check Dates</a>
                </div>
            </div>

            <div class="availableRooms">
                <div class="availableHeader">
                    <div class="currentMonth"></div>
                    <div class="availableDates"></div>
                </div>
                <div class="availableBody">

                </div>
            </div>

            <style>

                .datePickerContainer{
                    width: 100%;
                    margin: 0 auto !important;
                    
                }

                .startDateContainer{
                    width: 40%;
                    float: left;
                }

                .endDateContainer{
                    width: 40%;
                    float: left;
                }

                .checkAvailabilityContainer{
                    width: 20%;
                    float: left;
                }

                .availableRooms{
                    display: none;
                    clear: both;
                    padding-top: 30px !important;
                    margin: 0 auto !important;
                }

                .availableHeader{
                    /* background: #222222; */
                    display: flex;
                }

                .availableNight{
                    clear: both;
                    display: flex;
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


            </style>

        <script>
            jQuery(document).ready( function() {
                jQuery(".check_availability").click( function(e) {
                    e.preventDefault();
                    nonce = jQuery(this).attr("data-nonce")
                    start_date = jQuery(".start_date").val();
                    end_date = jQuery(".end_date").val();
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
                           
                            //    console.log(response);
                                    //Show .availableRooms
                                    jQuery(".availableRooms").css({"display":"block"});

                                    //clear our initial outputs incase we get called multiple times
                                    jQuery(".availableBody").html("");
                                    jQuery(".currentMonth").html("");
                                    jQuery(".availableDates").html("");
                                    jQuery(".bookNowHeader").html("");

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
                                               
                                                
                                                nights[i][j] = `<div class="night` + j + `">&euro;` + (response[i]["price"].toString() / response[i]["nights"].toString()) + `</div>`;
                                                

                                               
                                                
                                            }
                                             jQuery(".availableDates").append(relevantDate);

                                            //  console.log(nights[i]);
                                             var returnBody = `
                                                <div class="availableNight">
                                                    <div class="roomName" data-room-id="` + response[i]["room_id"] +`">` + response[i]["name_room"] + `</div>
                                                    <div class="nights">` + nights[i].join("") + ` <div class="bookNowBody"><a href="#">Select</a></div></div>
                                                    
                                                </div>
                                            `;

                                           
                                            
                                            jQuery(".availableBody").append(returnBody);
                                    }

                                    var bookNow = `
                                        <div class="bookNowHeader">Select Rooms</div>
                                    `;
                                    jQuery(".availableDates").append(bookNow);
                                
                                    

                                    
                                

                            
                        }
                    })
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