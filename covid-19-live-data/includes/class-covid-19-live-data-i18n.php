<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://dameweb.eu
 * @since      1.0.0
 *
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/includes
 * @author     Jan Vrkota <jan.vrkota@dameweb.eu>
 */
class Covid_19_Live_Data_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'covid-19-live-data',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
