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

	private function formate_numbers($number, $onlyNums)
	{
		$lengtNumber = strlen($number);
		$newDelimetr = " ";
		if ($lengtNumber > 3) {
			if ($onlyNums == true) {
				return str_replace(",", "", $number);
			} else {
				return str_replace(",", $newDelimetr, $number);
			}
		} else {
			return $number;
		}
	}

	private function formate_country_name($country)
	{
		return str_replace(" ", "%20", $country);
	}
	/**	
	 * Get curl response from API server
	 * 
	 * @param string URL of server with parameters
	 * @return array Return assoc array with data
	 */
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
	private function corona_by_country_api($country, $data, $onlyNums)
	{
		if ($country != "all") {
			$country = $this->formate_country_name($country);
			$response = $this->get_curl_response("https://coronavirus-monitor.p.rapidapi.com/coronavirus/latest_stat_by_country.php?country=$country");

			$rawLastUpdate = $response['latest_stat_by_country'][0]['record_date'];
			$rawLastUpdate = explode(" ", $rawLastUpdate);
			$rawLastDateOfUpdate = $rawLastUpdate[0];
			$rawLastTimeOfUpdate = $rawLastUpdate[1];
			$rawLastDateOfUpdate = explode("-", $rawLastDateOfUpdate);
			$rawLastTimeOfUpdate = explode(".", $rawLastTimeOfUpdate);
			$rawLastTimeOfUpdate = explode(":", $rawLastTimeOfUpdate[0]);
			// Ready var to display
			$_lastTimeOfUpdate = intval($rawLastTimeOfUpdate[0]) + 1 . ":" . $rawLastTimeOfUpdate[1] . ":" . $rawLastTimeOfUpdate[0];
			$_lastDateOfUpdate = $rawLastDateOfUpdate[2] . "." . $rawLastDateOfUpdate[1] . "." . $rawLastDateOfUpdate[0];

			if ($data == "time") {
				return $_lastTimeOfUpdate;
			} elseif ($data == "date") {
				return $_lastDateOfUpdate;
			} else {
				$returnData = $this->formate_numbers($response['latest_stat_by_country'][0][$data], $onlyNums);

				if (isset($returnData)) {
					if ($returnData == "") {
						$returnData = "0";
					}
					return $returnData;
				} else {
					return "Selected data to show does not exist. Please select correct data to show";
				}
			}
			/*
			 * Example of variable with data
			 * 
			* $_actuallyTotalCzechia = $this->formate_numbers($response['latest_stat_by_country'][0]['total_cases']);
			* $_totalRecovered = $this->formate_numbers($response['latest_stat_by_country'][0]['total_recovered']);
			*/
		} else {
			$responseWorld = $this->get_curl_response("https://coronavirus-monitor.p.rapidapi.com/coronavirus/worldstat.php");
			$rawLastUpdate = $responseWorld['statistic_taken_at'];
			$rawLastUpdate = explode(" ", $rawLastUpdate);
			$rawLastDateOfUpdate = $rawLastUpdate[0];
			$rawLastTimeOfUpdate = $rawLastUpdate[1];
			$rawLastDateOfUpdate = explode("-", $rawLastDateOfUpdate);
			$rawLastTimeOfUpdate = explode(".", $rawLastTimeOfUpdate);
			$rawLastTimeOfUpdate = explode(":", $rawLastTimeOfUpdate[0]);
			// Ready var to display
			$_lastTimeOfUpdate = intval($rawLastTimeOfUpdate[0]) + 1 . ":" . $rawLastTimeOfUpdate[1] . ":" . $rawLastTimeOfUpdate[0];
			$_lastDateOfUpdate = $rawLastDateOfUpdate[2] . "." . $rawLastDateOfUpdate[1] . "." . $rawLastDateOfUpdate[0];

			if ($data == "time") {
				return $_lastTimeOfUpdate;
			} elseif ($data == "date") {
				return $_lastDateOfUpdate;
			} else {
				$returnData = $this->formate_numbers($responseWorld[$data], $onlyNums);
				if (isset($returnData)) {
					if ($returnData == "") {
						$returnData = "0";
					}
					return $returnData;
				} else {
					return "Selected data to show does not exist. Please select correct data to show";
				}
			}
			/**
			 *	Example variable with data
			 * $_totalWorldCases = str_replace(",", " ", $responseWorld['total_cases']);
			 * $_totalWorldDeaths = str_replace(",", " ", $responseWorld['total_deaths']);
			 */
		}
	}
	/**
	 * Get live data from API about total cases of COVID 19 by the selected country and data
	 * 
	 * @since 1.0.0.
	 * @return string Number of selected data from country
	 */
	public function corona_data_by_country($atts = [])
	{
		$a = shortcode_atts(array(
			'country' => '',
			'data' => '',
			'onlyNums' => ''
		), $atts);

		if (isset($a['country']) && isset($a['data'])) {
			$country = $a['country'];
			$data = $a['data'];
			if (isset($a['onlyNums'])) {
				$onlyNums = true;
			} else {
				$onlyNums = false;
			}

			$countryData = $this->corona_by_country_api(
				$country,
				$data,
				$onlyNums
			);
			return $countryData;
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
	public function corona_total_cases_on_world($atts = [])
	{
		$a = shortcode_atts(array(
			'data' => '',
			'onlyNums' => ''
		), $atts);
		if (isset($a['data'])) {
			$data = $a['data'];
			if (isset($a['onlyNums'])) {
				$onlyNums = true;
			} else {
				$onlyNums = false;
			}
			$countryData = $this->corona_by_country_api("all", $data, $onlyNums);
			return $countryData;
		} else {
			return $this->error_empty_attr;
		}
	}
}
