<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://dameweb.eu
 * @since      1.0.0
 *
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Covid_19_Live_Data
 * @subpackage Covid_19_Live_Data/admin
 * @author     Jan Vrkota <jan.vrkota@dameweb.eu>
 */
class Covid_19_Live_Data_Admin
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
		$this->api_key = "9a1efe9fb1mshe235e0b83a5c44cp1e4406jsn8e2df1360b6c";
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
		 * defined in Covid_19_Live_Data_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Covid_19_Live_Data_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/covid-19-live-data-admin.css', array(), $this->version, 'all');
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
		 * defined in Covid_19_Live_Data_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Covid_19_Live_Data_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/covid-19-live-data-admin.js', array('jquery'), $this->version, false);
	}

	public function covid_data_menu()
	{
		$pluginTitle = "COVID 19 Live Data";
		add_options_page($pluginTitle, $pluginTitle, 'manage_options', 'covid19-live-data-dameweb', array($this, 'covid_data_dashboard'));
	}
	/**	
	 * Get curl response from API server
	 * 
	 * @param string URL of server with parameters
	 * @return array Return assoc array with data
	 */
	private function get_list_of_affected_countries()
	{
		$curlopt_url = "https://coronavirus-monitor.p.rapidapi.com/coronavirus/affected.php";
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
			$dataReturn = json_decode($response, true);
			return $dataReturn['affected_countries'];
		}
	}
	public function covid_data_dashboard()
	{		
			$arrayOfDataTypesCountry = array("country_name", "total_cases", "new_cases", "active_cases", "total_deaths", "new_deaths", "total_recovered", "serious_critical", "total_cases_per1m", "time", "date");
			$arrayOfDataTypesWorld = array("total_cases", "new_cases", "total_deaths", "total_recovered", "new_deaths", "time", "date");

		echo '<div class="wrap dameweb-style">';
			_e("<h1>COVID 19 Live DATA - Information</h1>");
			_e("<p>Select the name of the country you want to appear in the short code.</p>");
			echo '<select id="selectCountry">';
			foreach ($this->get_list_of_affected_countries() as $x => $countryName) {
				echo '<option value="' . $countryName . '">';
				echo $countryName;
				echo "</option>";
			}
			echo "</select>";
			_e("<p>After selecting your country will appear in the input below, copy the name and paste it into your short code</p>");
			_e('<input size="60" id="countryInput" type="text" placeholder="Select the country in dropdown!" value="">');
			_e("<hr>");
			_e("<h2>ShortCodes</h2>");
			_e("<p>This plugin has 2 short codes. One for the statistics of one selected country, the other for global statistics.</p>");
			_e("<h3>SHORT CODE 1 - Stats of one country</h3>");
			_e('<p>Shortcode:</p>');
			echo '<p><code>[corona_data_by_country <i>country="<b class="param">_Name_of_the_country_</b>" data="<b class="param">_Type_of_data_" onlyNums</b>]</i></code></p>';
			_e('<p>Parameter <b>[compulsory]</b>:<code>country</code> - insert into this parameter name of the country, which you copied from the input above</p>');
			_e('<p>Parameter <b>[compulsory]</b>:<code>data</code> - insert into this parameter type of data, which you see below</p>');
			_e('<p>Parameter <b>[optional]</b>: <code>onlyNums</code> - insert into page only digit, without any character (space, comma, etc.)</p>');
			_e('<p>Parameter: Type of data for the first short code</p>');
			echo '<ul class="terminal-font">';
			foreach ($arrayOfDataTypesCountry as $i => $dataType) {
				echo "<li><code>";
				echo $dataType;
				echo "</li></code>";
			}
			echo '</ul>';
			_e('<i><b>Do not use</b> this parameter for the second short code. It has own parameters</i>');
			_e('<p>Data type <code>date</code> and <code>time</code> show timestamp of last update</p>');
			_e("<hr>");
			_e("<h3>SHORT CODE 2 - Global stats</h3>");
			_e('<p>Shortcode: </p>');
			echo '<p><code>[corona_total_cases_on_world  <i>data="<b class="param">_Type of global data_" onlyNums</b>]</i></code></p>';
			_e('<p>Parameter:<code>data</code> - insert into this parameter type of data, which you see below</p>');
			_e('<p>Parameter <b>[optional]</b>: <code>onlyNums</code> - insert into page only digit, without any character (space, comma, etc.)</p>');
			_e('<p>Parameter: Type of data for the second short code</p>');
			echo '<ul class="terminal-font">';
			foreach ($arrayOfDataTypesWorld as $i => $dataType) {
				echo "<li><code>";
				echo $dataType;
				echo "</li></code>";
			}
			echo '</ul>';
			_e('<i><b>Do not use</b> this parameter for the first short code. It has own parameters</i>');
			_e('<p>Data type <code>date</code> and <code>time</code> show timestamp of last update</p>');

		echo '</div>';
	}
}
