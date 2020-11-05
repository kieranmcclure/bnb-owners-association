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
            <input type="date" name="start_date" class="start_date" data-nonce="' . $nonce . '">
            </input>
            <br>
            <label for="end_date" name="end_date_label">End Date</label>
            <input type="date" name="end_date" class="end_date" data-nonce="' . $nonce . '">
            </input>
            <br>
            <a class="check_availability" data-nonce="' . $nonce . '">Check Dates</a>

            <div class="availableRooms"></div>

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
                           
                               console.log(response);

                                
                                 jQuery(".availableRooms").append(JSON.stringify(response));
                                

                            
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