<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://dameweb.eu
 * @since      1.0.0
 *
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/includes
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
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/includes
 * @author     Jan Vrkota <jan.vrkota@dameweb.eu>
 */
class Covid_19_Live_Data
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Covid_19_Live_Data_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if (defined('COVID_19_LIVE_DATA_VERSION')) {
			$this->version = COVID_19_LIVE_DATA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'covid-19-live-data';

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
	 * - Covid_19_Live_Data_Loader. Orchestrates the hooks of the plugin.
	 * - Covid_19_Live_Data_i18n. Defines internationalization functionality.
	 * - Covid_19_Live_Data_Admin. Defines all hooks for the admin area.
	 * - Covid_19_Live_Data_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-covid-19-live-data-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-covid-19-live-data-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-covid-19-live-data-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-covid-19-live-data-public.php';

		$this->loader = new Covid_19_Live_Data_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Covid_19_Live_Data_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Covid_19_Live_Data_i18n();

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

		$plugin_admin = new Covid_19_Live_Data_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_menu', $plugin_admin, 'covid_data_menu');
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

		$plugin_public = new Covid_19_Live_Data_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		/**
		 * Return number of covid-19 cases in selected country
		 */
		$this->loader->add_shortcode('corona_data_by_country', $plugin_public, 'corona_data_by_country');
		/**
		 * Return number of covid-19 cases in selected country
		 */
		$this->loader->add_shortcode('corona_country_total_cases', $plugin_public, 'corona_country_total_cases');
		/**
		 * Return number of recovered people in selected country
		 */
		$this->loader->add_shortcode('corona_country_total_recovered', $plugin_public, 'corona_country_total_recovered');
		/**
		 * Return string date of last update
		 */
		$this->loader->add_shortcode('corona_country_last_date_update', $plugin_public, 'corona_country_last_date_update');
		/**
		 * 
		 * Return string time of last update
		 */
		$this->loader->add_shortcode('corona_country_last_time_update', $plugin_public, 'corona_country_last_time_update');
		/**
		 * Return string total cases on the world
		 */
		$this->loader->add_shortcode('corona_total_cases_on_world', $plugin_public, 'corona_total_cases_on_world');
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
	 * @return    Covid_19_Live_Data_Loader    Orchestrates the hooks of the plugin.
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
