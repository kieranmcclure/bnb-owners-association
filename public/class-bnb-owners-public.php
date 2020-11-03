<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://splash.ie
 * @since      1.0.0
 *
 * @package    Bnb_Owners
 * @subpackage Bnb_Owners/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Bnb_Owners
 * @subpackage Bnb_Owners/public
 * @author     Splash Marketing <support@splash.ie>
 */
class Bnb_Owners_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{
		// include(plugin_dir_path(__DIR__) . "includes/class-bnb-api.php");
		// $this->Bnb_Owners_API = new Bnb_Owners_API();
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		// $this->parse_bnb_owners_property_api_call = new parse_bnb_owners_property_api_call();
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bnb_Owners_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bnb_Owners_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/bnb-owners-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Bnb_Owners_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Bnb_Owners_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/bnb-owners-public.js', array('jquery'), $this->version, false);
	}


	/**
	 * TEMP
	 * Display Results retrieved from API Call
	 */

	// public function register_api_room_results_shortcode()
	// {
	// 	add_shortcode('display_rooms', array($this, $this->Bnb_Owners_API->parse_get_room_details()));
	// }
}
