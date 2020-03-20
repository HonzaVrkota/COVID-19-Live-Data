<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://dameweb.eu
 * @since      1.0.0
 *
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/public
 * @author     Jan Vrkota <jan.vrkota@dameweb.eu>
 */
class Covid_19_Live_Data_Public
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

	private $api_key;

	private $error_empty_attr;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->api_key = "9a1efe9fb1mshe235e0b83a5c44cp1e4406jsn8e2df1360b6c";
		$this->error_empty_attr = "Please fill the name of the country";
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
		 * defined in Covid_19_Live_Data_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Covid_19_Live_Data_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/covid-19-live-data-public.css', array(), $this->version, 'all');
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
		 * defined in Covid_19_Live_Data_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Covid_19_Live_Data_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/covid-19-live-data-public.js', array('jquery'), $this->version, false);
	}


	private function get_curl_response($curlopt_url)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $curlopt_url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(
				"x-rapidapi-host: coronavirus-monitor.p.rapidapi.com",
				"x-rapidapi-key: $this->api_key"
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			return "cURL Error #:" . $err;
		} else {
			return json_decode($response, true);
		}
	}
	/**
	 * Connect to API server and get response 
	 * 
	 * @param string Name of the country
	 * @return array Array of values 
	 * [0] -> Acctualy total cases in coutrny
	 * [1] -> Total recovered
	 * [2] -> Last date of update
	 * [3] -> Last time of update
	 * @access Private
	 */
	private function corona_by_country_api($country)
	{
		if ($country != "all") {
			$response = $this->get_curl_response("https://coronavirus-monitor.p.rapidapi.com/coronavirus/latest_stat_by_country.php?country=$country");

			$_actuallyTotalCzechia = $response['latest_stat_by_country'][0]['total_cases'];
			$_totalRecovered = $response['latest_stat_by_country'][0]['total_recovered'];
			$rawLastUpdate = $response['latest_stat_by_country'][0]['record_date'];
			$rawLastUpdate = explode(" ", $rawLastUpdate);
			$rawLastDateOfUpdate = $rawLastUpdate[0];
			$rawLastTimeOfUpdate = $rawLastUpdate[1];
			$rawLastDateOfUpdate = explode("-", $rawLastDateOfUpdate);
			$rawLastTimeOfUpdate = explode(".", $rawLastTimeOfUpdate);
			$rawLastTimeOfUpdate = explode(":", $rawLastTimeOfUpdate[0]);
			$_lastTimeOfUpdate = intval($rawLastTimeOfUpdate[0]) + 1 . ":" . $rawLastTimeOfUpdate[1] . ":" . $rawLastTimeOfUpdate[0];
			$_lastDateOfUpdate = $rawLastDateOfUpdate[2] . "." . $rawLastDateOfUpdate[1] . "." . $rawLastDateOfUpdate[0];

			$returnDataCountry = array($_actuallyTotalCzechia, $_totalRecovered, $_lastDateOfUpdate, $_lastTimeOfUpdate);
			return $returnDataCountry;
		} else {
			$responseWorld = $this->get_curl_response("https://coronavirus-monitor.p.rapidapi.com/coronavirus/worldstat.php");

			$_totalWorldCases = str_replace(",", " ", $responseWorld['total_cases']);
			return  $_totalWorldCases;
		}
	}

	/**
	 * Get live data from API about total cases of COVID 19 by the selected country
	 * 
	 * @since 1.0.0.
	 * @return string Number of total cases in selected country
	 */
	public function corona_country_total_cases($atts = [])
	{
		$a = shortcode_atts(array(
			'country' => ''
		), $atts);
		if (isset($a['country'])) {
			$country = $a['country'];

			$countryData = $this->corona_by_country_api(
				$country
			);
			return $countryData[0];
		} else {
			return $this->error_empty_attr;
		}
	}

	/**
	 * Get live data from API about total recovered of COVID 19 by the selected country
	 * 
	 * @since 1.0.0.
	 * @return string Number of total recovered in selected country
	 */
	public function corona_country_total_recovered($atts = [])
	{
		$a = shortcode_atts(array(
			'country' => ''
		), $atts);
		if (isset($a['country'])) {
			$country = $a['country'];

			$countryData = $this->corona_by_country_api(
				$country
			);
			return $countryData[1];
		} else {
			return $this->error_empty_attr;
		}
	}

	/**
	 * Get live data from API about last date update of the selected country
	 * 
	 * @since 1.0.0.
	 * @return string Last date of update
	 */
	public function corona_country_last_date_update($atts = [])
	{
		$a = shortcode_atts(array(
			'country' => ''
		), $atts);
		if (isset($a['country'])) {
			$country = $a['country'];

			$countryData = $this->corona_by_country_api(
				$country
			);
			return $countryData[2];
		} else {
			return $this->error_empty_attr;
		}
	}

	/**
	 * Get live data from API about last time update of the selected country
	 * 
	 * @since 1.0.0.
	 * @return string Last time of update
	 */
	public function corona_country_last_time_update($atts = [])
	{
		$a = shortcode_atts(array(
			'country' => ''
		), $atts);
		if (isset($a['country'])) {
			$country = $a['country'];

			$countryData = $this->corona_by_country_api(
				$country
			);
			return $countryData[3];
		} else {
			return $this->error_empty_attr;
		}
	}

	/**
	 * Get live data from API about total cases on the world
	 * 
	 * @since 1.0.0.
	 * @return string Total cases on world
	 */
	public function corona_total_cases_on_world()
	{
		$countryData = $this->corona_by_country_api("all");
		return $countryData;
	}
}
