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

            <label for="start_date" name="start_date_label">Start Date</label>
            <input type="date" name="start_date" value="2020-11-10" class="start_date" data-nonce="' . $nonce . '">
            </input>
            <br>
            <label for="end_date" name="end_date_label">End Date</label>
            <input type="date" name="end_date"value="2020-11-18" class="end_date" data-nonce="' . $nonce . '">
            </input>
            <br>
            <a class="check_availability" data-nonce="' . $nonce . '">Check Dates</a>

            <div class="availableRooms">
                <div class="availableHeader"></div>
                <div class="availableBody">

                </div>
            </div>

            <style>
                .availableHeader{
                    background: #222222;
                }

                .availableNight{
                    clear: both;
                    display: flex;
                }

                .availableNight .roomName{
                    max-width: 30%;
                    display: flex;
                    float: left;
                    border-left: 1px solid #efefef;
                    border-top: 1px solid #efefef;
                    border-right: 1px solid #efefef;
                    padding: 5px;
                    flex: 1;
                }

                .nights{
                    display: flex;
                    width: 70%;
                    float: left;
                    flex: 1;
                }

                .nights div{
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

                                    for(var i = 0; i < response.length; i++){
                                        console.log(response[i]);

                                            var nights = [];
                                             nights[i] = [];
                                            for(var j = 0; j < response[i]["available"]; j++){
                                               
                                                nights[i][j] = `<div class="night` + j + `">&euro;` + (response[i]["price"].toString() / response[i]["nights"].toString()) + `</div>`;

                                                
                                            }
                                            //  console.log(nights[i]);
                                             var returnBody = `
                                                <div class="availableNight">
                                                    <div class="roomName">` + response[i]["name_room"] + `</div>
                                                    <div class="nights">` + nights[i].join("") + `</div>
                                                    
                                                </div>
                                            `;

                                           
                                            
                                            jQuery(".availableBody").append(returnBody);
                                    }
                                
                               
                                    // jQuery.each(response, function(k, v){
                                    //     // jQuery(".availableRooms").append(k);
                                    //     // console.log("Key: " + k + " Value: " + v);
                                    //     console.log(k.name_room);
                                    //     var name_room;
                                    //     if(k.name_room == "name_room"){
                                    //         var name_room = k;
                                    //     }


                                   
                                    // });
                                

                            
                        }
                    })
                });
            });

        </script>

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