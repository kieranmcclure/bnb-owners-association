<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://splash.ie
 * @since      1.0.0
 *
 * @package    Bnb_Owners
 * @subpackage Bnb_Owners/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Bnb_Owners
 * @subpackage Bnb_Owners/includes
 * @author     Splash Marketing <support@splash.ie>
 */
class Bnb_Owners
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Bnb_Owners_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('BNB_OWNERS_VERSION')) {
			$this->version = BNB_OWNERS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bnb-owners';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Bnb_Owners_Loader. Orchestrates the hooks of the plugin.
	 * - Bnb_Owners_i18n. Defines internationalization functionality.
	 * - Bnb_Owners_Admin. Defines all hooks for the admin area.
	 * - Bnb_Owners_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bnb-owners-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bnb-owners-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-bnb-owners-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-bnb-owners-public.php';


		/**
		 * Our shared classes to be used in both public and admin
		 * sides of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bnb-api.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/partials/bnb-owners-date-selector.php';


		$this->loader = new Bnb_Owners_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Bnb_Owners_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Bnb_Owners_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Bnb_Owners_Admin($this->get_plugin_name(), $this->get_version());
		$plugin_shared = new Bnb_Owners_API($this->get_plugin_name(), $this->get_version());


		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		//Add to the Dashboard Menu
		//Initializes /admin/class-bnb-owners-admin.php > add_menu 
		$this->loader->add_action('admin_menu', $plugin_admin, 'add_menu');

		//Setup our Options
		$this->loader->add_action('admin_init', $plugin_admin, 'bnb_manage_register_options');

		//Initialize our DB connection to B&B Owners server
		$this->loader->add_action('admin_init', $plugin_shared, 'bnb_owners_db_connect');

		//Initialize our API call
		$this->loader->add_action('admin_init', $plugin_shared, 'register_api_shortcode');

		//Initialize our Rooms Shortcode
		$this->loader->add_action('admin_init', $plugin_shared, 'register_api_room_results_shortcode');


		//API Listeners for requests
		// $this->loader->add_action('admin_init', $plugin_shared, 'process_date_selector');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Bnb_Owners_Public($this->get_plugin_name(), $this->get_version());
		$plugin_shared = new Bnb_Owners_API($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_action('init', $plugin_shared, 'register_api_room_results_shortcode');
		
		$this->loader->add_action('wp_ajax_process_date_selector', $plugin_shared, 'process_date_selector');
		$this->loader->add_action('wp_ajax_nopriv_process_date_selector', $plugin_shared, 'process_date_selector');
		

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Bnb_Owners_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}