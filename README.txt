=== COVID-19 Live Data ===
Contributors: honzavrkota
Tags: COVID19, coronavirus, corona, covid, pandemic
Donate link: www.dameweb.eu
Requires at least: 2.7.1
Tested up to: 5.3.2
Requires PHP: 7.1
Stable tag: 1.0.1
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Display live data of COVID-19. With 2 shortcodes you can display all stats about that virus.

== Description ==
The necessary information to run the short codes is in the administration menu -> settings -> COVID 19 Live Data.
Actually affected countries are live from API server.
1. **Shortcode 1**- Statistics of one country: [corona_data_by_country country=\"_Name_of_the_country_\" data=\"_Type_of_data_\" onlyNums]
Parameter **[compulsory]**:country - insert into this parameter name of the country, which you copied from the input above
Parameter **[compulsory]**:data - insert into this parameter type of data, which you see below
Parameter **[optional]**: onlyNums - insert into page only digit, without any character (space, comma, etc.)
Data types for global statistics:
**country_name**
**total_cases**
**new_cases**
**active_cases**
**total_deaths**
**new_deaths**
**total_recovered**
**serious_critical**
**total_cases_per1m**
**time**
**date**
**Do not use** this parameter for the second short code. It has own parameters

SHORT CODE 2 - Global stats: **[corona_total_cases_on_world data=\"_Type_of_global_data_\" onlyNums]**
Parameter **[compulsory]**: data - insert into this parameter type of data, which you see below
Parameter **[optional]**: onlyNums - insert into page only digit, without any character (space, comma, etc.)
Parameter: Type of data for the second short code
Data types for global world statistics:
**total_cases**
**new_cases**
**total_deaths**
**total_recovered**
**new_deaths**
**time**
**date**
**Do not use** this parameter for the first short code. It has own parameters



== Installation ==
1. Upload \'covid-19-live-data.php\' to the \'/wp-content/plugins/\' directory
2. Activate the plugin through the \'Plugins\' menu in WordPress
3. Go to \'Settings\' -> COVID 19 Live DATA 
4. Copy one of the short code -> insert into a page with two parameters

== Frequently Asked Questions ==
= What countries will the plugin show data from? =

Displays data for all countries that are affected by COVID-19

= Can show global statistics? =

Yes, the second short code display global stats of world.

== Screenshots ==
1. Information admin page

== Changelog ==
= 1.0.1 =
Short codes for listing current coronavirus statistics

== Upgrade Notice ==
= 1.0.1 =
Short codes for listing current coronavirus statistics