<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://dameweb.eu
 * @since             1.0.0
 * @package           Covid_19_Live_Data
 *
 * @wordpress-plugin
 * Plugin Name:       COVID-19 Live Data
 * Plugin URI:        https://dameweb.eu
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Jan Vrkota
 * Author URI:        https://dameweb.eu
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       covid-19-live-data
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'COVID_19_LIVE_DATA_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-covid-19-live-data-activator.php
 */
function activate_covid_19_live_data() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-covid-19-live-data-activator.php';
	Covid_19_Live_Data_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-covid-19-live-data-deactivator.php
 */
function deactivate_covid_19_live_data() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-covid-19-live-data-deactivator.php';
	Covid_19_Live_Data_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_covid_19_live_data' );
register_deactivation_hook( __FILE__, 'deactivate_covid_19_live_data' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-covid-19-live-data.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_covid_19_live_data() {

	$plugin = new Covid_19_Live_Data();
	$plugin->run();

}
run_covid_19_live_data();
