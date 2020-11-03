<?php

require_once plugin_dir_path(dirname(__FILE__)) . 'includes/partials/bnb-owners-date-selector.php';
class Bnb_Owners_API
{
    /**
     * Initialize Database connection to Amazon RDS
     * This will allow us to make queries to the B&B Owners Association Database to get any results we may need
     * Credentials Hardcoded for now
     * 
     * TODO: Figure out a way to not hardcode these credentials -- Maybe make a request elsewhere to pull these back so it can be centralized?
     * 
     * @since 1.0.0
     */

    public function bnb_owners_db_connect()
    {
        $bnb_user = "ireland";
        $bnb_password = "p4ffaHBe";
        $bnb_db_name = "bnbs";
        $bnb_db_host = "bnb-owners.cy5euxhxuswi.eu-west-1.rds.amazonaws.com";

        //Connect to DB 
        global $bnb_db;
        $bnb_db = new wpdb($bnb_user, $bnb_password, $bnb_db_name, $bnb_db_host);

        return $bnb_db;
    }

    //Make our API request to https://api.bnbowners.com/property
    //Pass in our current URL, B&B ID, image dimensions (not sure what this is for yet), and currentURL again (idk why)
    public function bnb_owners_property_api_call()
    {
        $this->options = get_option('options');


        $bnb_id = esc_attr($this->options['bnb_id']);
        $curURL = get_site_url();
        $imageDimensions = "375x250";

        $api = "https://api.bnbowners.com/property?url=" . (urlencode($curURL)) . "&id=" . urlencode($bnb_id) . "&imagedimensions=" . urlencode($imageDimensions) . "&site=" . urlencode($curURL);


        //If failed to get results
        if (!($string = file_get_contents($api, FALSE))) {
            echo "<script>console.log('Failed to retrieve results from API');</script>";
        } else if (strlen($string) < 1) {
            echo "<script>console.log('No Results');</script>";
        }

        //Our JSON Results
        $bnb = json_decode($string);

        return $bnb;
    }

    /**
     * TEMP
     * Display Results retrieved from API Call
     */
    public function parse_bnb_owners_property_api_call()
    {
        $results = $this->bnb_owners_property_api_call();
        $resultsEncoded = json_encode($results);
        return $resultsEncoded;
    }

    public function register_api_shortcode()
    {
        add_shortcode('display_results', array($this, 'parse_bnb_owners_property_api_call'));
    }


    /**
     * Pull back Room details from DB for B&B ID
     *
     * @since 1.0.0
     */

    public function get_room_details()
    {
        //Get all results for now
        $this->options = get_option('options');
        $bnb_id = esc_attr($this->options['bnb_id']);

        //TEMP Arrival
        $arrival = "2020-12-19";

        //TEMP DEPARTURE
        $departure = "2020-12-24";

        $query = "
			SELECT
				room_numbers.number,
				room_numbers.sleeps,
				room_numbers.singlebeds,
				room_numbers.doublebeds,

				IF(room_state.singleprice != 0, room_state.singleprice, room_numbers.singleprice) AS singleprice,
				IF(room_state.doubleprice != 0, room_state.doubleprice, room_numbers.doubleprice) AS doubleprice,
				IF(room_state.tripleprice != 0, room_state.tripleprice, room_numbers.tripleprice) AS tripleprice,

				room_numbers.room_id,
					IF(room_numbers.name IS NULL OR room_numbers.name = '', CONCAT('Room ', room_numbers.number), room_numbers.name) AS name_room,
					IF(room_numbers.description != '', 1, 0) AS hasdescription,
				room_numbers.description,

				SUM(room_state.price) AS price,
				COUNT(*) AS available,
				DATEDIFF( DATE('{$departure}'), DATE('{$arrival}') ) AS nights

			FROM room_numbers
				JOIN room_state ON room_state.room_id = room_numbers.room_id

			WHERE room_numbers.bnb_ref = {$bnb_id}
				AND room_state.date BETWEEN '{$arrival}' AND DATE_SUB('{$departure}', INTERVAL 1 DAY)
				AND room_state.available = 1
				AND IF(room_state.minimumnights > 1, DATEDIFF( DATE('{$departure}'), DATE('{$arrival}') ) >= room_state.minimumnights, 1)

			GROUP BY room_state.room_id
			HAVING available >= nights
			ORDER BY room_numbers.name, room_numbers.number, room_numbers.sleeps
		";

        //   $results = $this->bnb_owners_db_connect()->get_results("SELECT * FROM room_numbers WHERE room_numbers.bnb_ref=" . $bnb_id);
        $results = $this->bnb_owners_db_connect()->get_results($query);

        return $results;
    }

    public function parse_get_room_details()
    {
        
        
        $this->date_selector = new date_selector();

        $results = $this->get_room_details();


        // WHILE($results){
        // 	  $results = $results['price'];
        //   }
        $keyvalue = [];
        $counter = 0;
        //TEMP - sorting array as Key => Value
        foreach ($results as $k => $v) {
            $keyvalue['room' . $counter] =
                array(
                    'price' => $v->price,
                    'sleeps' => $v->sleeps,
                    'singlebeds' => $v->singlebeds,
                    'doublebeds' => $v->doublebeds,
                    'singleprice' => $v->singleprice,
                    'doubleprice' => $v->doubleprice,
                    'tripleprice' => $v->tripleprice,
                    'room_id' => $v->room_id,
                    'available' => $v->available,
                    'nights' => $v->nights,
                    'description' => $v->description,
                    'name_room' => $v->name_room,
                    'hasdescription' => $v->hasdescription
                );

            $counter++;
        }
        $results = $keyvalue;

        $resultsEncoded = json_encode($results);

        $resultsStyled = "";

        foreach ($keyvalue as $k => $v) {
            $resultsStyled .= "
                <div class='" . $k . " roomResult'>
                    Room Name: " . $v['name_room'] . "  Price:" . $v['price'] . "
                </div>
            ";
        }

        $returnTest = $this->date_selector->display_date_selector();

        return $returnTest;
    }

    public function register_api_room_results_shortcode()
    {
        add_shortcode('display_rooms', array($this, 'parse_get_room_details'));
    }

    public function process_date_selector(){
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