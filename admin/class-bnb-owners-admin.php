<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://splash.ie
 * @since      1.0.0
 *
 * @package    Bnb_Owners
 * @subpackage Bnb_Owners/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Bnb_Owners
 * @subpackage Bnb_Owners/admin
 * @author     Splash Marketing <support@splash.ie>
 */
class Bnb_Owners_Admin
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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/bnb-owners-admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/bnb-owners-admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * Add to Dashboard Menu
	 * Adds a link to the relevant partial in the menu
	 * Partial: partials/bnb_manage.php
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function add_menu()
	{
		/**
		 * This function adds the menu entry to the Dashboard Menu
		 */
		add_menu_page("B&B Owners", "B&B Owners", 'manage_options', $this->plugin_name . '-manage', array($this, 'bnb_manage'));
	}

	public function bnb_manage()
	{
		include(plugin_dir_path(__FILE__) . 'partials/bnb_manage.php');
	}


	/**
	 * Create our Options
	 * Used to store options in the Database under splash_options
	 * WP default functionality via Settings/Options API - secure storage
	 * https://developer.wordpress.org/plugins/settings/options-api/
	 * 
	 * @since 1.0.0
	 * 
	 */
	public function bnb_manage_register_options()
	{
		//Register Settings group
		register_setting('bnb_manage_options_group', 'options', array($this, 'sanitize'));

		//Create the Settings section
		add_settings_section('bnb_info', 'B&B Options', array($this, 'print_section_info'), 'bnb-owners-manage');

		//Add settings field for B&B ID
		add_settings_field('bnb_id', 'B&B ID', array($this, 'id_callback'), 'bnb-owners-manage', 'bnb_info');
	}

	//BnB_ID -- Field label
	public function print_section_info()
	{
		print '<h3>Enter B&B ID</h3>';
	}

	//BnB_ID -- input field
	public function id_callback()
	{
		printf('<input type="text" id="bnb_id" name="options[bnb_id]" value="%s">', isset($this->options['bnb_id']) ? esc_attr($this->options['bnb_id']) : '');
	}

	//Sanitize our input
	public function sanitize($input)
	{
		$new_input = array();
		if (isset($input['bnb_id'])) {
			$new_input['bnb_id'] = sanitize_text_field($input['bnb_id']);
		}

		return $new_input;
	}
}
